<?php
defined('SYS_IN') or exit('Access Denied.');

class model_usergroup extends model_base {
    protected $_table = 'usergroup';
    protected $_primarykey = 'ugid';
    
    function __construct() {
        parent::__construct();
    }
    
	/**
     * 
     * 获取一条信息
     * @param int $ugid
     */
    function get_permission() {
		$session = new model_session();
		if($session) {
			$cookie = $session->sgetcookie('auth');
			if (!empty($cookie)) {
				$login_model = new model_login();
				$login_model->is_login();
			}
		}
		$menuids="";
        if(!empty($_SESSION['admin_usertype'])) {
			$where = "where ugid=".$_SESSION['admin_usertype'];
			$sql = "select * from ".$this->tname($this->_table)." $where limit 0,1";
			$value = $this->db->get_one($sql);
			$menuids = $value['permission'];
		}
		return $menuids;
    }    
}