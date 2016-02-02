<?php
/*
 * app 初始化 
 * 
 * @author allenhaozi@gmail.com 
 */

define( 'APP_NAME', 'crab' );
/** App root path */
define( 'APP_PATH', dirname (  dirname( __FILE__ ) ) );

require_once APP_PATH . '/conf/common.php';

require_once APP_PATH . '/conf/conf.inc.php';

/** App php类库文件 */
define( 'PHP_LIBS_PATH', APP_PATH . '/libs' );

/** 日志路径 */
define( 'LOG_PATH', APP_PATH . '/../../var/' . APP_NAME );


/** include path */
$strIncludePath = 
	PHP_LIBS_PATH . ':' .
	APP_PATH . '/model/' . ':' .
	APP_PATH . '/action/' ;

/** 设置包含路径 */
set_include_path ( $strIncludePath . ':' . get_include_path() );

/** 错误报警信息开关 */
error_reporting ( E_ALL & ~ E_NOTICE & ~ E_WARNING );
//ini_set('display_errors','Off');

/** 设置 auto load */
require_once "Zend/Loader/Autoloader.php";

Zend_Loader_Autoloader::getInstance ()->setFallbackAutoloader ( true );


/** Crab 配置文件 */
Crab_Config::setConfig( $arrConfig );
/** Crab DB配置文件 */
Crab_Config::mergeConfig( $arrDbConfig );
/** Log 目录 */
$strLogId = md5(base64_encode(pack('N6',mt_rand(),mt_rand(),mt_rand(),mt_rand(),mt_rand(),uniqid()))); 
Crab_Log::setOptions ( array ('logdir' => LOG_PATH, 'logid' => $strLogId ) );
Crab_Dispatcher::setOption ( 
	array (
		'mdir' => APP_PATH . '/action',
		'view' => 'Crab_View_Smarty'
	)
);
/** 模板设置 */
Crab_View_Smarty::setOption(
	array(
		'template_dir' => APP_PATH . '/view/tpl',
		'compile_dir' => APP_PATH . '/view/compiles',
		'debugging' => true,
	)
);
