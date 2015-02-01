<?php
/**
 * index.php 系统 入口
 *
 * @copyright (C) 2011
 * @author Myron
 */
define('ROOT_PATH',str_replace("\\","/",dirname(__FILE__)));
include ROOT_PATH.'/source/base.php';
Base::start_app();