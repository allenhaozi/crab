<?php
/**
 * 使用curl_multi_*族函数实现简单的并发
 *
 * @author allen.mh@alibaba-inc.com
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
     * @var array request header data 
     */
    private $arrRequestHead = array();

    /**
     * @var array http response info 
     */
    private $arrHttpResInfo = array();

    private $arrOption = array();

    private $arrInitHeader = array(
        //'Accept-Encoding' => 'gzip',			
        //'Content-Type' => 'application/json',
    );
    /**
     * @var array curl 请求默认参数
     */
    private $arrInitOption = array(
        /** 设置cURL允许执行的最长秒数 */
        CURLOPT_TIMEOUT => 5,
        CURLOPT_CONNECTTIMEOUT => 1,

        /** 返回响应结果 */
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 0,	

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

        if( count( $this->arrRequestList ) > 1 && count( $this->getParam() ) > 1 ){
            $this->rollingCurl();
        } else {
            $this->singleCurl();
        }
    }
    /**
     * 单个请求
     */
    public function singleCurl(){
        /** initialize response data */
        $arrRes = array(
            'ret' => 0,
            'msg' => '',	
            'data' => '',
        );

        /** record request time begin */	
        $intTimeBegin = microtime( true ) * 1000;
        list($tmpKey,$arrServerList) = each( $this->arrRequestList );

        if( empty( $arrServerList ) ){
            throw new Crab_Exception( Crab_ErrCode::EC_INVALID_PARAM, 'CURL_URL_LIST' );
        }

        $this->initOption();

        $index = 0;
        $intServerNum = count( $arrServerList );
        $arrRetData = $this->getParam();

        if( ! empty( $arrRetData ) ){
            /** single request use the first param */
            $arrRet = $arrRetData[$tmpKey];
            $key = $tmpKey;
        } else {
            $key = 0;	
        }
        $arrRetHead = $this->getRequestHeader( $key );
        while( true ){
            if( ( $index + 1 ) > $intServerNum ){
                curl_close( $ch );
                break;
            }
            $strUrl = $arrServerList[$index];	
            $ch = curl_init( $strUrl );
            if( ! empty( $arrRet ) ){
                $this->setRequestData( $arrRet );
            } 
            if( ! empty( $arrRetHead ) ){
                $this->setRequestHeadData( $arrRetHead );	
            }
            curl_setopt_array( $ch, $this->getOption() );
            $arrData = curl_exec( $ch );
            $arrInfo = curl_getInfo( $ch );
            if( $arrInfo['http_code'] == 200 ){
                curl_close( $ch );
                break;	
            } else {
                $arrRes['msg'] = curl_error( $ch );
                $arrRes['ret'] = 1;
                $arrRes['http_info'] = $arrInfo;
            }
            $index ++;
        }
        $arrRes['data'] = $arrData;

        $this->setResponse( array( $key => $arrRes ) );
        $intTimeEnd = microtime( true ) * 1000;

        $this->setProcessTime( array( $key => ( $intTimeEnd - $intTimeBegin ) ) );
    }
    /**
     * 模拟并发请求
     * 
     * 注:当计算不同数据,相同url的时候,根据请求数据的个数生成数量相同的请求地址
     *    请求的数据和返回结果的对应关系 通过请求URL的array的索引对应的 
     */
    private function rollingCurl(){
        /** record time */
        $intTimeBegin = microtime( true ) * 1000;
        /** 并行批处理cURL句柄 */
        $handle = curl_multi_init();
        $arrServerList =  $this->arrRequestList;
        $arrRequestData = $this->getParam();	

        foreach( $arrServerList as $loop => $arrUrl){
            /** use first url, keep it for exetension */
            $strUrl = array_shift( $arrUrl );
            /** 初始化一个 cURL session */
            $ch[$loop] = curl_init( $strUrl );
            $this->initOption();
            if( ! empty( $arrRequestData[$loop] ) ){
                $this->setRequestData( $arrRequestData[$loop] );
            }

            $arrRequestHead = $this->getRequestHeader( $loop );

            if( !empty( $arrRequestHead ) ){
                $this->setRequestHeadData( $arrRequestHead );	
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
                $arrRes[$index]['msg'] = curl_error( $ch[$index] );
                $arrRes[$index]['ret'] = 1;
                $arrRes[$index]['http_info'] = $arrHttpInfo;
            } else {
                $arrRes[$index]['ret'] = 0;
                $arrRes[$index]['data'] = curl_multi_getcontent( $ch[$index] );
            }
            $intTimeEnd = microtime( true ) * 1000;
            $arrTime[$index] = $intTimeEnd - $intTimeBegin;
            curl_close( $ch[$index] );
        } 
        curl_multi_close( $handle );
        $this->setResponse( $arrRes );
        $this->setProcessTime( $arrTime );
    }

    /**
     * 设置单个请求的URL
     */
    private function setRequestUrlData( $strUrl ){
        $this->arrOption[CURLOPT_URL] = $strUrl;
    }
    /**
     * set request data
     * @param array request data
     */
    public function setRequestParam( $arrRet )
    {
        $this->setParam( $arrRet );	
    }
    /**
     * set request header data 
     * @param array 
     */
    public function setRequestHeader( $arrHeadRet )
    {
        $this->arrRequestHead = $arrHeadRet;	
    }
    /**
     * set request header data 
     * @param array 
     */
    private function setRequestHeadData( $arrHeadRet )
    {
        if( empty( $arrHeadRet ) ){
            return false;
        }
        foreach( $arrHeadRet as $key => $value ){
            $arrHeader[] = $key . ':' . $value;	
        }	
        $this->arrOption[ CURLOPT_HTTPHEADER ] = $arrHeader;
    }
    /**
     * get request header data
     * @return array 
     */
    private function getRequestHeader( $strRetKey )
    {
        $arrRet = $this->arrRequestHead[$strRetKey];
        if( empty( $arrRet ) ){
            $arrRet = array();
        }
        $arrHead = array_merge( $this->arrInitHeader, $arrRet );
        return $arrHead;	
    }
    /**
     * 设置请求的参数
     *
     * @param array $arrData 
     */	 
    private function setRequestData( $arrData ){
        if( empty( $arrData ) ){
            throw new Crab_Exception( Crab_ErrCode::EC_INVAlID_PARAM );
        }
        if( is_array( $arrData ) ){
            $arrData = http_build_query( $arrData );	
        }

        $this->arrOption[ CURLOPT_POST ] = 1;
        $this->arrOption[ CURLOPT_POSTFIELDS ] = $arrData;
    }

    /**
     * 设置请求的cookie
     */
    private function setRequestCookie( $strCookie = null ){
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
     * initialize request option data		
     */
    private function initOption()
    {
        $this->arrOption = $this->arrInitOption;	
    }
    /**
     * get 请求参数集合 
     */
    public function getOption(){
        foreach( $this->arrInitOption as $key => $value ){

            if( ! isset( $this->arrOption[$key] ) ){
                $this->arrOption[$key] = $value;
            }		
        }
        return $this->arrOption;
    }
    /**
     * 设置请求的URL 集合
     */
    public function setRequestUrl( $arrRequestUrl ){
        $this->arrRequestList = $arrRequestUrl;	
    }
    /**
     * get 请求的URL集合
     */
    public function getRequestList(){
        return $this->arrRequestList;
    } 
    /**
     * set http response info
     * @param array 
     */
    private function setHttpInfo( $arrInfo )
    {
        $this->arrHttpResInfo = $arrInfo;	
    }
    /**
     * get http response info
     * @param array 
     */
    public function getHttpInfo()
    {
        return $this->arrHttpResInfo;	
    }	
}
