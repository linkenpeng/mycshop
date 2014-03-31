<?php
if (!defined('SYS_IN')) {
    exit('Access Denied');
}
$db_config = array();
$db_config['db_host'] = "localhost";
$db_config['db_name'] = "hbrt";
$db_config['db_user'] = "hbrt";
$db_config['db_password'] = "hbrt123ewq";
$db_config['db_charset'] = "utf8";
$db_config['db_pconnect'] = 0;
$db_config['db_table_pre'] = "hb_";
$db_config['db_type'] = "mysql";
return $db_config;
?>