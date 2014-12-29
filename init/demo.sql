-- test sql

-- create database 
create database if not exists crab CHARACTER SET utf8 COLLATE utf8_general_ci;

USE crab;

CREATE TABLE `crab_demo` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
		`content` varchar(10) NOT NULL DEFAULT '',
		PRIMARY KEY (`id`),

		) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='crab demo '
