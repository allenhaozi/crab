<?php
/**
 * bridge-web-im 模块入口文件
 *
 * @author mahao01@baidu.com
 * @date Fri Nov 15 21:01:22 CST 2013
 */

require_once( dirname(__FILE__) . '/common/env_init.php' );

try {
	$objDispatcher = Crab_Dispatcher::getInstance (); 
	$objDispatcher->run();
} catch ( Exception $ex ) { 
	dump( $ex->getMessage() );
	exit;
}
