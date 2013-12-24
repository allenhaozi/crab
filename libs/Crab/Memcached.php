<?php
/**
 * Crab/Memcached.php
 *
 * Memcached接口，支持热备
 * 
 * @category Crab
 * @author allenhaozi@baidu.com
 */
class Crab_Memcached {
	/**
	 * 配置参数
	 *
	 * @var array
	 */
	protected static $_arrOption;
	/**
	 * singleton模式
	 *
	 * @var Crab_Memcached
	 */
	protected static $_objSelf;
	/**
	 * 父节点链接
	 *
	 * @var Memcache
	 */
	protected $_objMaster;
	/**
	 * 子结点链接
	 *
	 * @var Memcache
	 */
	protected $_objSlave;
	/**
	 * mc 分片存储的最大片数
	 */
	const MC_SLICE_CNT = 20; 

	/**
	 * mc 单片存储的最大数组数
	 */
	const MC_ARRAY_MAX = 600; 
	/**
	 * 使用分片的临界值 大于该值使用分片
	 */
	const MC_BASE_CNT = 1;

	/**
	 * 设置参数，格式：
	 * 重置参数后 对象注销
	 * array(
	 *  'master' => array(
	 *      array('host' => '', 'port' => ''),
	 *      array('host' => '', 'port' => ''),
	 *  ),
	 *  'slave' => array(
	 *      array('host' => '', 'port' => ''),
	 *      array('host' => '', 'port' => ''),
	 *  ),
	 * );
	 *
	 * @param array $arrOption
	 */
	public static function setOption($arrOption) {
		self::$_arrOption = $arrOption;
		/** 设置参数后注销生成的对象防止单例对象 */
		if (self::$_objSelf instanceof Crab_Memcached) {
			self::$_objSelf = null;
		}
	}
	/**
	 * Singleton模式，获得实例
	 *
	 * @return Crab_Memcached
	 */
	public static function getInstance() {
		if (self::$_objSelf instanceof Crab_Memcached) {
			return self::$_objSelf;
		}
		if (!self::$_arrOption) {
			return false;
		}
		self::$_objSelf = new self();
		return self::$_objSelf;
	}
	/**
	 * 构造函数
	 *
	 */
	protected function __construct() {
		$this->_objMaster = new Memcache();
		$this->_objSlave = new Memcache();
		foreach(self::$_arrOption['master'] as $arrServer) {
			$this->_objMaster->addServer($arrServer['host'], $arrServer['port'], true, 1, 1, 15, true);
		}
		foreach(self::$_arrOption['slave'] as $arrServer) {
			$this->_objSlave->addServer($arrServer['host'], $arrServer['port'], true, 1, 1, 15, true);
		}
	}
	/**
	 * 读取
	 *
	 * @param string $strKey
	 * @return mixed
	 */
	public function get($strKey) {
		$value = $this->_objMaster->get($strKey);
		return $value ? $value : $this->_objSlave->get($strKey);
	}
	/**
	 * 设置
	 *
	 * @param string $strKey
	 * @param mixed $mixVal
	 * @param integer $intFlag
	 * @param integer $intExpire
	 * @return boolean
	 */
	public function set($strKey, $mixVal, $intFlag = 0, $intExpire = 3600) {
		$res1 = $this->_objMaster->set($strKey, $mixVal, $intFlag, $intExpire);
		$res2 = $this->_objSlave->set($strKey, $mixVal, $intFlag, $intExpire);
		return $res1&$res2;
	}
	/**
	 * 获取分片数据
	 *
	 * @param string $strKeyPre mc key
	 * @return array/false  
	 */
	public function getSliceData( $strKeyPre )
	{
		$arrData = array();
		/** 分次获取分片数据 */
		for( $intLoop = self::MC_BASE_CNT; $intLoop<=self::MC_SLICE_CNT;$intLoop++ ){
			$strKey = $strKeyPre . '_' . $intLoop;  
			$arrTmp = array();
			$arrTmp = $this->get( $strKey );   
			/** 获取 false 默认分片数据全部取出 */
			if( false == $arrTmp ){
				break;
			}
			$arrData = array_merge( $arrData, $arrTmp );    
		}           
		if( empty( $arrData ) ){
			$arrData = false;
		}
		return $arrData;
	}

	/**
	 * 删除分片数据
	 *
	 * @param string $strKeyPre
	 */
	public function delSliceData( $strKeyPre )
	{
		/** 分次删除分片数据 */
		for( $intLoop = self::MC_BASE_CNT; $intLoop<=self::MC_SLICE_CNT;$intLoop++ ){
			$strKey = $strKeyPre . '_' . $intLoop;  
			$this->delete( $strKey );   
		}
	}
	/**
	 * 存储分片数据
	 *
	 * @param string $strKeyPre
	 * @param array $arrData  
	 * @return boolean 
	 */
	public function setSliceData( $strKeyPre, $arrData, $intFlag = 0, $intExpire = 3600 )
	{
		$intSlice = ceil ( count( $arrData ) / self::MC_ARRAY_MAX );    
		if( $intSlice == self::MC_BASE_CNT ){
			$strKey = $strKeyPre . '_' . self::MC_BASE_CNT; 
			return $this->set( $strKey, $arrData, $intFlag = 0, $intExpire = 3600 );
		} else {
			/** 默认存储成功 */
			$bolCache = true;
			for( $intLoop = self::MC_BASE_CNT; $intLoop<=self::MC_SLICE_CNT;$intLoop++ ){
				$strKey = $strKeyPre . '_' . $intLoop;
				/** eg: 0 3000 6000 9000 */
				$intOffSet = ( $intLoop - self::MC_BASE_CNT ) * self::MC_ARRAY_MAX;
				$arrTmp = array();
				$arrTmp = array_slice( $arrData, $intOffSet, self::MC_ARRAY_MAX );
				if( ! $this->set( $strKey, $arrTmp,$intFlag = 0, $intExpire = 3600 ) ){
					/** 单次失败 即 失败 */
					$bolCache = false;
				}
				/** 等于分片数 或者 等于最大分片数 防止对内存压力过大 PHP内存溢出 */
				if( self::MC_SLICE_CNT == $intLoop || $intLoop == $intSlice )
					break;
			}
			return $bolCache;	
		}
	}



	/**
	 * 删除
	 *
	 * @param string $strKey
	 * @param integer $intTimeOut
	 * @return boolean
	 */
	public function delete($strKey, $intTimeOut = null) {
		$res1 = $this->_objMaster->delete($strKey, $intTimeOut);
		$res2 = $this->_objSlave->delete($strKey, $intTimeOut);
		return $res1&$res2;
	}
	/**
	 * 自增
	 *
	 * @param string $strKey
	 * @param integer $intVal
	 * @return boolean
	 */
	public function increment($strKey, $intVal = 1) {
		$res1 = $this->_objMaster->increment($strKey, $intVal);
		$res2 = $this->_objSlave->increment($strKey, $intVal);
		return $res1&$res2;
	}
	/**
	 * 自减
	 *
	 * @param string $strKey
	 * @param integer $intVal
	 * @return boolean
	 */
	public function decrement($strKey, $intVal = 1) {
		$res1 = $this->_objMaster->decrement($strKey, $intVal);
		$res2 = $this->_objSlave->decrement($strKey, $intVal);
		return $res1&$res2;
	}
	/**
	 * 清除
	 *
	 * @return boolean
	 */
	public function flush() {
		$res1 = $this->_objMaster->flush();
		$res2 = $this->_objSlave->flush();
		return $res1&$res2;
	}
}
?>
