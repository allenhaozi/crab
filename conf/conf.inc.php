<?php
/**
 * Crab 配置文件
 *
 * @author allenhaozi@gmail.com
 */

require_once dirname( __FILE__ ) . '/db.inc.php';

$arrConfig = array(
	/** 与 Crab/Model.php db 一一对应 */
	'CRAB_DEFAULT_DB_KEY' => 'CRAB_DB_8076',
	'CRAB_DEFAULT_DB_NAME' => 'crab',
	'MEMCACHE' => array(
		'master' => array( 'host' => '127.0.0.1', 'port' => '9000' ),
		'slave' => array( 'host' => '127.0.0.1', 'port' => '9000' ),
	),
);
