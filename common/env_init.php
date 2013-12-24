<?php
/*
 * bridge-web-im 初始化 
 * 
 * @author mahao01@baidu.com 
 * @date Fri Nov 15 21:00:47 CST 2013
 */

define( 'APP_NAME', 'crab' );
/** 商桥web 根目录 shifen */
define( 'ROOT_PATH', dirname (  dirname( __FILE__ ) ) );

require_once ROOT_PATH . '/conf/common.php';

require_once ROOT_PATH . '/conf/conf.inc.php';

/** 商桥web php类库文件 */
define( 'PHP_LIBS_PATH', ROOT_PATH . '/libs' );

define( 'APP_PATH', ROOT_PATH );
/** 日志路径 */
define( 'LOG_PATH', '' );


/** include path */
$strIncludePath = 
	PHP_LIBS_PATH . ':' .
	APP_PATH . '/model/' . ':' .
	APP_PATH . '/action/' ;

/** 设置包含路径 */
set_include_path ( $strIncludePath . ':' . get_include_path() );

/** 错误报警信息开关 */
//error_reporting ( E_ALL & ~ E_NOTICE & ~ E_WARNING );
ini_set('display_errors','Off');

/** 设置 auto load */
require_once "Zend/Loader/Autoloader.php";

Zend_Loader_Autoloader::getInstance ()->setFallbackAutoloader ( true );

/** Crab 配置文件 */
Crab_Config::setConfig( $arrConfig );
/** Crab DB配置文件 */
Crab_Config::mergeConfig( $arrDbConfig );
/** Log 目录 */
Crab_Log::setOptions ( array ('logdir' => LOG_PATH ) );
Crab_Dispatcher::setOption ( 
	array (
		'mdir' => APP_PATH . '/action',
		'view' => 'Crab_View_Smarty'
	)
);

