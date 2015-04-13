<?php
if (!defined('SYS_IN')) {
	exit('Access Denied');
}
$config = array(
	'db' => array(
		'master' => array(
			'host' => 'localhost',
			'dbname' => 'mycshop',
			'user' => 'mycshop',
			'password' => 'mycshop123ewq',
			'charset' => 'utf8',
			'pconnect' => '0',
			'table_pre' => 'hb_',
			'driver' => 'pdo' 
		) 
	),
	'memcache' => array(
		'master' => array(
			'server' => array(
				array('host' => '127.0.0.1', 'port' => 11211),
			),
			'timeout' => 30,
			'weight' => 1,
			'persistent' => 1,
			'retry_interval' => 10,
			'expire' => 3600,
			'compress_data' => 1,
			'compress_min_size' => 20000,
			'compress_level' => 0.2,
		),
	),
	'redis' => array(
		'sharding_node' => 256,
		'open_lock' => 1,
		'lock_prefix' => 'REDIS_KEY_LOCK_',	
		'master' => array(
			0 => array(
				'host'    => '192.168.12.10',
				'port'    => '6379',
				'timeout' => 30,
				'expire'  => 300,
			),
			1 => array(
				'host'    => '192.168.12.11',
				'port'    => '6379',
				'timeout' => 30,
				'expire'  => 300,
			),
		),
	),
	'system' => array(
		'charset' => 'utf-8', // 网站编码
		'pass_key' => 'ZmF3dXlvdS5jb20', // 用户加密密匙
		'gzip' => 1,
		'cookiepre' => 'mycshop_', // 设置cookie前缀
		'cookiepath' => '/', // 设置cookie路径
		'cookiedomain' => '.mycshop.com', // 设置cookie域名
		'template' => 'default' 
	),
	'autoload' => array(
		'/source',
	),
	'module' => array(
		"index",
		"android",
		"admin",
		"spider" 
	),
	'route' => array(
		M => 'admin',
		C => 'index',
		A => 'init' 
	) 
);
return $config;