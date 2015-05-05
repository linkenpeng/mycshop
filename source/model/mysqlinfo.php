<?php
defined('SYS_IN') or exit('Access Denied.');

class model_mysqlinfo extends model_base {

	function __construct() {
		parent::__construct();
	}

	public function dbversion() {
		$sql = "SELECT VERSION()";
		return $this->db->fetch_fields($sql);
	}

	public function dbsize() {
		$dbsize = 0;
		$sql = "SHOW TABLE STATUS LIKE '".$this->_config['table_pre']."%'";
		$tables = $this->db->get_list($sql);
		foreach ($tables as $table) {
			$dbsize += $table['Data_length'] + $table['Index_length'];
		}
		return $dbsize/1024/1024;
	}
}