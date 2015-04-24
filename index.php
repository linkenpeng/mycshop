<?php
/**
 * index.php 系统 入口
 */
define('DEBUG', true);
DEBUG && error_reporting(E_ALL & ~E_NOTICE); // & ~E_STRICT & ~E_DEPRECATED
define('ROOT_PATH', dirname(__FILE__));
define('APP_FOLDER', 'application');

$autoload_paths['app'] = ROOT_PATH . DIRECTORY_SEPARATOR . 'source';
require(ROOT_PATH . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'trig' . DIRECTORY_SEPARATOR . 'index.php');
require(ROOT_PATH . DIRECTORY_SEPARATOR . 'source' . DIRECTORY_SEPARATOR . 'base.php');
new trig_mvc_application();

set_exception_handler('exceptionCallback');

function exceptionCallback($e) {
	$ret = array(
		'code' => $e->getCode(),
		'msg' => $e->getMessage() 
	);
	if (!($e instanceof trig_exception_driver)) {
		$ret['code'] = 12306;
		$ret['msg'] = '系统异常';
	}
	echo json_encode($ret);
	exit(0);
}
