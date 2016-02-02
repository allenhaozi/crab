<?php
/**
 * 数据库接口类
 *
 * @author allen.mh@alibaba-inc.com
 */
interface Crab_Db_Interface
{
	/** @var 数据库服务器 */
	const DB_SERV = 'DB_SERV';
	/** @var 数据库名字 */
	const DB_NAME = 'DB_NAME';
	/** @var 数据库表明 */
	const DB_TABLE = 'DB_TABLE';
	/** 自定义配置 */
	function __define();
}
