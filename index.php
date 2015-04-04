<?php
/**
 * index.php 系统 入口
 */
define('DEBUG',true);
DEBUG && error_reporting(E_ALL);//& ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED

define('ROOT_PATH',str_replace("\\","/",dirname(__FILE__)));

$autoload_paths['app'] = ROOT_PATH.'/source';
include ROOT_PATH.'/framework/index.php';

include ROOT_PATH.'/source/base.php';

new trig_mvc_application();