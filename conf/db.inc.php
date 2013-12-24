<?php
/**
 * @var 数据库配置文件
 *    支持多主多从便于读写分离
 * 
 * @author hao.ma.se7en@gmail.com
 */
$arrDbConfig = array(
	'CRAB_DB_6602' => array(
		'master' => array(
			array(
				'host' => '10.26.190.45','port' => '6602','username' => 'sfwrite','password' => '123456',
			),
			array(
				'host' => '10.26.190.45','port' => '6602','username' => 'sfwrite','password' => '123456',
			),		
		),
		'slave' => array(
			array(
				'host' => '10.26.190.45','port' => '6602','username' => 'sfwrite','password' => '123456',
			),
			array(
				'host' => '10.26.190.45','port' => '6602','username' => 'sfwrite','password' => '123456',
			)
		),
	),
);
