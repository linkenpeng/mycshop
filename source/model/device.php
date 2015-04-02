<?php
defined('SYS_IN') or exit('Access Denied.');

class model_device extends model_base {
    protected $_table = 'deviceinfo';
    protected $_primarykey = 'deviceid';
    
    function __construct() {
        parent::__construct();
    }
	
	function isExists($param = array()) {
		$where = " WHERE 1";
		if(!empty($param)) {
			foreach($param as $key=>$val) {
				$where .= " AND `$key`='$val' ";
			}
		}
		$sql = "SELECT COUNT(*) AS c FROM ".$this->tname($this->_table).$where;
		$list = $this->db->get_one($sql);
		return $list['c'] > 0;
	}
}
?>