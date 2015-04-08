<?php
/**
 * index.php 系统 入口
 */
define('DEBUG', true);
DEBUG && error_reporting(E_ALL & ~E_NOTICE); // & ~E_STRICT & ~E_DEPRECATED
define('ROOT_PATH', str_replace("\\", "/", dirname(__FILE__)));
$autoload_paths['app'] = ROOT_PATH . '/source';
include ROOT_PATH . '/framework/index.php';
include ROOT_PATH . '/source/base.php';
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