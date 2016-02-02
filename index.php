<?php
/**
 * app 模块入口文件
 *
 * @author allenhaozi@gmail.com
 */

require_once( dirname(__FILE__) . '/common/env_init.php' );

try {
	$objDispatcher = Crab_Dispatcher::getInstance (); 
	$objDispatcher->run();
} catch ( Exception $ex ) { 
	Crab_Log::Log( 'error', 'INDEX', $ex->getMessage() );
	var_dump( $ex->getMessage() );
	//header( 'Location: http://www.baidu.com/search/error.html' );
	exit;
}
