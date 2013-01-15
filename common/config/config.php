<?php
//配置多数据库
return array(
	'webhost'  => 'ijk.local',
	'adapter' => array(
		'master'  => array(
			'driver'	=> 'Pdo_Mysql',
			'database'	=> 'zf2',
			'username' 	=> 'root',
			'password' 	=> '',	
			'hostname'  => 'localhost',
			'port'		=> '3306',
			'characterset' => 'UTF8',
			'driver_options' => array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
			),
		)
	)
);