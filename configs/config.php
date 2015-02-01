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
			'driver' => 'mysql' 
		) 
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
		'/source/models',
		'/source/applications/admin',
		'/source/system/class',
		'/source/system/class/db' 
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
?>