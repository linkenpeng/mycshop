<?php
if (!defined('SYS_IN')) {
	exit('Access Denied');
}
$config = array(
	'db' => array(
		'master' => array(
			'host' => 'localhost',
			'name' => 'mycshop',
			'user' => 'mycshop',
			'password' => 'mycshop123ewq',
			'charset' => 'utf8',
			'pconnect' => '0',
			'table_pre' => 'hb_',
			'type' => 'mysql' 
		) 
	),
	'sys' => array(
		'charset' => 'utf-8', // 网站编码
		'pass_key' => 'ZmF3dXlvdS5jb20', // 用户加密密匙base64_encode 不可随意更改fawuyou.com去掉最后一个=
		'gzip' => 1,
		'cookiepre' => 'mycshop_', // 设置cookie前缀
		'cookiepath' => '/', // 设置cookie路径
		'cookiedomain' => '.mycshop.com'  // 设置cookie域名
	),
	'autoload' => array(
		'model' => array(
			'path' => ROOT_PATH . '/source/models/',
			'extension' => '.class.php' 
		),
		'controller' => array(
			'path' => ROOT_PATH . '/source/applications/admin/',
			'extension' => '.php' 
		),
		'library' => array(
			'path' => ROOT_PATH . '/source/utils/class/',
			'extension' => '.class.php' 
		) 
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