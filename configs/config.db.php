<?php
if (!defined('SYS_IN')) {
    exit('Access Denied');
}
$db_config = array(
	'db_host' 		=> "localhost",
	'db_name' 		=> "mycshop",
	'db_user' 		=> "mycshop",
	'db_password' 	=> "mycshop123ewq",
	'db_pconnect' 	=> "utf8",
	'db_table_pre' 	=> "hb_",
	'db_type' 		=> "mysql",
);
return $db_config;
?>