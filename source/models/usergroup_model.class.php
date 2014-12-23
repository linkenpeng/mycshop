<?php
defined('SYS_IN') or exit('Access Denied.');

class usergroup_model extends model {
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
		global $session;
		if($session) {
			$cookie = $session->sgetcookie('auth');
			if (!empty($cookie)) {
				Base::load_model("login_model")->is_login();
			}
		}
		$menuids="";
        if(!empty($_SESSION['admin_usertype'])) {
			$where = "where ugid=".$_SESSION['admin_usertype'];
			$sql = "select * from ".tname($this->_table)." $where limit 0,1";
			$value = $this->db->get_one($sql);
			$menuids = $value['permission'];
		}
		return $menuids;
    }    
}
?>