<?php
/**
 * index.php 系统 入口
 */
define('ROOT_PATH',str_replace("\\","/",dirname(__FILE__)));
include ROOT_PATH.'/source/base.php';
Base::start_app();