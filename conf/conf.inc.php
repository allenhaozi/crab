<?php
/**
 * Crab 配置文件
 *
 * @author allenhaozi@gmail.com
 */

require_once dirname( __FILE__ ) . '/db.inc.php';

$arrConfig = array(
	/** 与 Crab/Model.php db 一一对应 */
	'CRAB_DEFAULT_DB_KEY' => 'CRAB_DB_6602',
	'CRAB_DEFAULT_DB_NAME' => 'brg_openapi',
	'MEMCACHE' => array(
		'master' => array( 'host' => '127.0.0.1', 'port' => '9999' ),
		'slave' => array( 'host' => '127.0.0.1', 'port' => '9999' ),
	),
);
