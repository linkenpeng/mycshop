<?php
/**
 * 允许的模块 index 前台模块 admin 后台模块
 */
if (!defined('SYS_IN')) {
    exit('Access Denied');
}
$module_config = array(
    "index",
	"android",
    "admin",
	"spider"
);
return $module_config;
?>