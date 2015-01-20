<?php
/**
 * Crab/Log.php
 * 
 * 日志记录类
 * 
 * @category Crab
 * @author songqi<songqi@baidu.com>
 * @modify allenhaozi@gmail.com
 */
/**
 * Zend_Log
 */
require_once("Zend/Log.php");
 
class Crab_Log {
    /**
     * Zend_Log对象的数组
     *
     * @var array
     */
    private static $_arrZendLogs;
    /**
     * Log相关的参数
     *
     * @var array
     */
    private static $_arrOptions = array('logdir' => '', 'userid'=> 0, 'targetuserid'=>0);
    
    /**
     * 设置参数，格式：
     * array(
     *  'logdir' => '',
     *  'userid' => '',
     *  'targetuserid' = '',
     *
     * @param unknown_type $arrOptions
     */
    public static function setOptions($arrOptions) {
        self::$_arrOptions = array_merge(self::$_arrOptions, $arrOptions);
    }
    
    public static function getOptions() {
        return self::$_arrOptions;
    }
    
    /**
     * 获取日志目录对象
     *
     * @return string
     */
    private static function getLogDir() {
        $strDir = self::$_arrOptions['logdir'].'/'.date("Ymd");   		
        if (!is_dir($strDir)) {
            $strDir = trim($strDir);
            $bolSuc = false;
            if (strlen($strDir)>3) {
                $bolSuc = @mkdir($strDir, 0775, true);               
            }
            if (!$bolSuc && !is_dir($strDir)) {
                throw new Exception( __CLASS__ . ':日志目录没有设置成正确的目录');
           }
        }
        return $strDir;
    }
    /**
     * 获取log handler
     *
     * @param string $strType
     * @return Zend_Log
     */
    protected static function getLogHandler($strType) {
        if (self::$_arrZendLogs[$strType] instanceof Zend_Log) {
            return self::$_arrZendLogs[$strType];
        } else {
            $strDir = self::getLogDir();
            $strFileName = $strDir."/".$strType.".log";
            $objWriter = new Zend_Log_Writer_Stream($strFileName);
            
            $strFormat = "%message%" . PHP_EOL;
            $objFormatter = new Zend_Log_Formatter_Simple($strFormat);
            $objWriter->setFormatter($objFormatter);    
            $objLog = new Zend_Log($objWriter);
            self::$_arrZendLogs[$strType] = $objLog;
            return self::$_arrZendLogs[$strType];
        }
    }
    /**
     * 写Log,该方法为该类的实例才能调用
     *
     * @param string strLogType 日志类型，包括:'error','db','op'等，分成单独的文件存储，可扩充
     * @param  string strOpName 操作的名称，包括
     * @param array arrParams 参数，包括url或者插入数据库参数
     */
    public static function Log($strLogType, $strOpName, $mixParam = null, $strPlace = null) {        

        if (is_null ( $strLogType )) {
            $strLogType = 'default';
        }
        if (is_null ( $strOpName )) {
            $strOpName = 'default';
        }
        $objLogHandle = self::getLogHandler ( $strLogType );
        $strTm = '[' . date ( 'Y-m-d H:i:s' ) . ']';
		
        $strParams = '-';
        if ( is_string( $mixParam ) || is_numeric( $mixParam ) ){
			$strParams = $arrParams;
		} elseif( is_object( $mixParam ) || is_array( $mixParam ) || is_resource( $mixParam ) ) {
			$strParams = serialize( $arrParams );	
		} elseif( is_bool( $mixParam ) ){
			if( $mixParam )
				$strParams = 'true';		
			else 
				$strParams = 'false';
		} elseif( is_null( $mixParam ) ){
			$strParams = 'null';	
		}
        $arrOption = self::getOptions ();
		$strLogId = $arrOption['logid'];

    	if (is_null ( $strLogId )) {
            $strLogId = '-';
        }

        if (is_null ( $strPlace )) {
            $strPlace = '-';
        }
        $strIp = self::getClientIp ();
        if (empty ( $strIp )) {
            $strIp = '-';
        }
        //chr(9)表示tab键
        $strData = $strTm.chr(9).$strLogId.chr(9).$strIp.chr(9).$strOpName.chr(9).$strParams.chr(9).$strPlace . chr ( 10 );
        
        $objLogHandle->notice ( $strData );       
	}

    /**
     * 获得客户请求携带的ip地址
     * 子类需要访问
     * @return string
     */
    protected static function getClientIp() {
        if (getenv('HTTP_CLIENT_IP')) { //getenv是取得环境变量.　先试用这个是否取得ip
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) { //否则用取得代理跳转ip
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR')) {
            $onlineip = getenv('REMOTE_ADDR'); //再次这样取得ip
            
        } else {
            $onlineip = $_SERVER['REMOTE_ADDR']; //不行再这样取..～～
            
        }
        return $onlineip;
    }
    /**
     * 清空日志句柄
     *
     */
    public static function reset() {
    	self::$_arrZendLogs = null;
    }
}
?>
