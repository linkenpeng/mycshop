<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_index extends application_admin_base {

	function __construct() {
		parent::__construct();
	}

	function init() {
		$user = new model_user();
		$user_info = $user->get_user_info($_SESSION['admin_uid']);
		
		$mysqlinfo = new model_mysqlinfo();
		$dbsize = $mysqlinfo->dbsize();
		$mysql_version = $mysqlinfo->dbversion();		
		
		$this->display('index', array(
			'user_info' => $user_info,
			'dbsize' => $dbsize,
			'mysql_version' => $mysql_version,
		));
	}
}