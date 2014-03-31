<?php
/**
 * 默认的路由 
 * m:模块（对应applications下面的目录）
 * c:控制器（对应于applications下面目录下面的文件中的class）
 * a:事件 对应于控制器中的方法事件
 */
if (!defined('SYS_IN')) {
    exit('Access Denied');
}
$route_config = array(
    M=>'admin',
    C=>'index',
    A=>'init'
);
return $route_config;
?>