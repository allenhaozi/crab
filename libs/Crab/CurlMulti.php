<?php
/**
 * 使用curl_multi_*族函数实现简单的并发
 *
 * @author allenhaozi@gmail.com 
 * 
 */
class Crab_CurlMulti extends Crab_Abstract
{
	/**
	 * @var object Crab_CurlMulti 
	 */	 
	private static $instance = null;

	/**
	 * @var array 请求 URL List
	 */
	private $arrRequestList = array();

	/**
	 * @var array curl 请求默认参数
	 */
	private $arrOption = array(
		/** 设置cURL允许执行的最长秒数 */
		CURLOPT_TIMEOUT => 5,

		/** 返回响应结果 */
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_HEADER => 0,
		CURLOPT_COOKIE => '',
		CURLOPT_NOSIGNAL => 1,
	);

	/**
	 * @return object Crab_CurlMulti
	 */
	public static function getInstance(){
		if( self::$instance === null ){
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * 执行请求
	 * @todo 目前只支持 post 数据
	 */
	public function execute(){
		if( count( $this->arrRequestList ) > 1 ){
			$this->rollingCurl();
		} else {
			$this->singleCurl();
		}
	}
	/**
	 * 单个请求
	 */
	public function singleCurl(){
		$arrServerList = array_values( $this->arrRequestList );
		if( empty( $arrServerList ) ){
			throw new Crab_Exception( Crab_ErrCode::EC_INVALID_PARAM, 'CURL_URL_LIST' );
		}
		$index = 0;
		$intServerNum = count( $arrServerList );
		while( true ){
			if( ( $index + 1 ) > $intServerNum ){
				throw new Crab_Exception( Crab_ErrCode::EC_FAULT, 'CURL_RETRY_OVERFLOW' );
			}
			$strUrl = $arrServerList[$index];	
			$ch = curl_init();
			$this->setRequestUrl( $strUrl );
			$arrRequestData = $this->getParam();
			if( ! empty( $arrRequestData ) ){
				$this->setRequestData( array_shift( $arrRequestData ) );
			} 
			curl_setopt_array( $ch, $this->getOption() );
			$this->response = curl_exec( $ch );
			$arrInfo = curl_getInfo( $ch );
			if( $arrInfo['http_code'] == 200 ){
				return true;
			}
			$index ++;
		}
	}

	/**
	 * 模拟并发请求
	 * 
	 * 注:当计算不同数据,相同url的时候,根据请求数据的个数生成数量相同的请求地址
	 *    请求的数据和返回结果的对应关系 通过请求URL的array的索引对应的 
	 */
	private function rollingCurl(){

		/** 并行批处理cURL句柄 */
		$handle = curl_multi_init();
		$arrServerList =  $this->arrRequestList;
		$arrRequestData = $this->getParam();	

		foreach( $arrServerList as $loop => $strUrl){
			/** 初始化一个 cURL session */
			$ch[$loop] = curl_init( $strUrl );
			if( ! empty( $arrRequestData ) ){
				$this->setRequestData( $arrRequestData[$loop] );
			}
			/** 单个cURL 信息 */
			curl_setopt_array( $ch[$loop], $this->getOption() );
			/** Add a normal cURL handle to a cURL multi handle */
			curl_multi_add_handle( $handle, $ch[$loop] );
		}

		do{
			while( ( $execrun = curl_multi_exec( $handle, $running ) ) == CURLM_CALL_MULTI_PERFORM );
			if( $execrun != CURLM_OK )
				break;
		} while ( $running );
		/** 结果集 */ 
		foreach( $arrServerList as $index => $value ){
			$arrHttpInfo = curl_getinfo( $ch[$index] );
			if( $arrHttpInfo['http_code'] != 200 ){
				/** @todo 请求失败暂不处理*/
				$arrError = curl_error( $ch[$index] );
			}
			$arrRes[$index] = curl_multi_getcontent( $ch[$index] );
			curl_close( $ch[$index] );
		} 

		curl_multi_close( $handle );

		$this->setResponse( $arrRes );
	}

	/**
	 * 设置单个请求的URL
	 */
	public function setRequestUrl( $strUrl ){
		$this->arrOption[CURLOPT_URL] = $strUrl;
	}

	/**
	 * 设置请求的参数
	 *
	 * @param array $arrData 
	 *
	 */	 
	public function setRequestData( $arrData ){
		if( count( $arrData ) < 1 ){
			throw new Crab_Exception( Crab_ErrCode::EC_INVAlID_PARAM );
		}
		$this->arrOption[ CURLOPT_POST ] = 1;
		$this->arrOption[ CURLOPT_POSTFIELDS ] = http_build_query( $arrData );
	}

	/**
	 * 设置请求的参数
	 *
	 * @param array $arrData 
	 *
	 */	 
	public function setRequestDataByJson( $arrData ){
		if( count( $arrData ) < 1 ){
			throw new Crab_Exception( Crab_ErrCode::EC_INVAlID_PARAM );
		}
		$this->arrOption[ CURLOPT_POST ] = 1;
		$this->arrOption[ CURLOPT_POSTFIELDS ] = json_encode( $arrData );
	}

	/**
	 * 设置请求的cookie
	 */
	public function setRequestCookie( $strCookie = null ){
		$this->arrOption[ CURLOPT_COOKIE ] = $strCookie;
	}
	/**
	 * 设置请求的配置参数
	 */
	public function setOption( $arrOption ){
		foreach( $arrOption as $k => $v ){
			$this->arrOption[$k] = $v;
		}			
	}
	/**
	 * get 请求参数集合 
	 */
	public function getOption(){
		return $this->arrOption;
	}
	/**
	 * 设置请求的URL 集合
	 */
	public function setRequestList( $arrRequestUrl ){
		$this->arrRequestList = $arrRequestUrl;	
	}
	/**
	 * get 请求的URL集合
	 */
	public function getRequestList(){
		return $this->arrRequestList;
	} 
}
