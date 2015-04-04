<?php
defined('SYS_IN') or exit('Access Denied.');

class model_user extends model_base {
	protected $_table = 'user';
	protected $_primarykey = 'uid';

	function __construct() {
		parent::__construct();
	}

	function check_user_exist($username, $password, $usertype = "") {
		$username = trim($username);
		$password = trim($password);
		$logininfo['uid'] = "0";
		$logininfo['usertype'] = "";
		if (!empty($username) && !empty($password)) {
			$password_form = trig_func_common::password($password);
			$where = " where username='$username' ";
			if (!empty($usertype)) {
				$where .= " and usertype='$usertype' ";
			}
			$sql = "select uid,password,usertype from " . $this->tname($this->_table) . " $where ";			
			$value = $this->db->get_one($sql);
			if (!empty($value['uid'])) {
				if ($password_form == $value['password']) {
					$logininfo['uid'] = $value['uid'];
					$logininfo['usertype'] = $value['usertype'];
				} else {
					$logininfo['uid'] = "-2";
				}
			} else {
				$logininfo['uid'] = "-1";
			}
		}
		return $logininfo;
	}

	function add_login_log($uid) {
		if (!empty($uid)) {
			$loginip = trig_func_common::get_ip();
			$logintime = time();
			$sql = "update " . $this->tname($this->_table) . " set last_ip='$loginip',last_login='$logintime',logins=logins+1 where uid='$uid'";
			$this->db->query($sql);
		}
	}

	function get_user_info($uid) {
		if (!empty($uid)) {
			return $this->get_one($uid);
		}
	}

	function update_password($admin_uid, $password) {
		$flag = false;
		if (!empty($admin_uid) && !empty($password)) {
			$this->db->update($this->tname($this->_table), 
			array('password' => trig_func_common::password($password)), "uid='$admin_uid'");
			$flag = true;
		}
		return $flag;
	}
}