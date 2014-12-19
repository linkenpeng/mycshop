<?php
if (!defined('SYS_IN')) {
    exit('Access Denied');
}
return array(
    'charset'=>'utf-8', //网站编码
    'pass_key'=>'ZmF3dXlvdS5jb20', //用户加密密匙base64_encode 不可随意更改fawuyou.com去掉最后一个=
    'gzip'=>1,
    'cookiepre'=>'mycshop_', //设置cookie前缀
    'cookiepath'=>'/', //设置cookie路径
    'cookiedomain'=>'.mycshop.com' //设置cookie域名
);
?>