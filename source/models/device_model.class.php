<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class device_model extends model {
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
		$sql = "SELECT COUNT(*) AS c FROM ".tname($this->_table).$where;
		$list = $this->db->get_one($sql);
		return $list['c'] > 0;
	}
}
?>