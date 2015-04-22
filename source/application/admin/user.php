<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_user extends application_admin_base {
	private $userdb;

	function __construct() {
		parent::__construct();
		$this->userdb = new model_user();
	}

	function init() {
		$usertype = empty($_GET['usertype']) ? "" : intval($_GET['usertype']);
		$keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
		$where = " WHERE 1 ";
		if (!empty($usertype)) {
			$where .= " and usertype=" . $usertype;
		}
		if (!empty($keyword)) {
			$where .= " and `username` like '%" . $keyword . "%' ";
		}
		// 分页
		$count = $this->userdb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		// 获取分页后的数据
		$list = $this->userdb->get_list($p->perpage, $p->offset, "*", $where, "uid DESC ");
		$usergroupdb = new model_usergroup();
		$usergroup_list = $usergroupdb->get_list(100, 0, " ugid,name ", "", "uid ASC ");
		foreach ($usergroup_list as $k => $v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
		include trig_mvc_template::view('user');
	}

	function ajax_userlist() {
		$usertype = empty($_GET['usertype']) ? "" : intval($_GET['usertype']);
		$keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
		$where = " WHERE 1 ";
		if (!empty($usertype)) {
			$where .= " and usertype=" . $usertype;
		}
		if (!empty($keyword)) {
			$where .= " and `username` like '%" . $keyword . "%' ";
		}
		// 分页		
		$count = $this->userdb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		// 获取分页后的数据
		$list = $this->userdb->get_list($p->perpage, $p->offset, "*", $where, "uid DESC ");
		$usergroupdb = new model_usergroup();
		$usergroup_list = $usergroupdb->get_list(100, 0, " ugid,name ", "", "uid ASC ");
		foreach ($usergroup_list as $k => $v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
		include trig_mvc_template::view('ajax_userlist');
	}

	public function add() {
		$value = array();
		if (!empty($_POST['action'])) {
			if (empty($_POST['username']) || empty($_POST['password'])) {
				trig_func_common::ShowMsg("用户名和密码不能为空!", -1);
			}
			$_POST['regtime'] = empty($_POST['regtime']) ? time() : strtotime(trim($_POST['regtime']));
			$data = array(
				'username' => $_POST['username'],
				'password' => password($_POST['password']),
				'realname' => $_POST['realname'],
				'usertype' => $_POST['usertype'],
				'province' => $_POST['province'],
				'city' => $_POST['city'],
				'country' => $_POST['country'],
				'address' => $_POST['address'],
				'regtime' => $_POST['regtime'],
				'content' => $_POST['content'] 
			);
			if ($this->userdb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("user", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$show_validator = 1;
		$show_zone = 1;
		
		$where = '';
		$usergroupdb = new model_usergroup();
		$ugroup_list = $usergroupdb->get_list(100, 0, " ugid,name ", $where, "uid ASC ");
		include trig_mvc_template::view('userform');
	}

	public function edit() {
		$uid = $_GET['uid'];
		if (!empty($uid)) {
			$value = $this->userdb->get_one($uid);
		}
		if (!empty($_POST['action']) && !empty($_POST['uid'])) {
			$_POST['regtime'] = empty($_POST['regtime']) ? time() : strtotime(trim($_POST['regtime']));
			$data = array(
				'username' => $_POST['username'],
				'realname' => $_POST['realname'],
				'usertype' => $_POST['usertype'],
				'province' => $_POST['province'],
				'city' => $_POST['city'],
				'country' => $_POST['country'],
				'address' => $_POST['address'],
				'regtime' => $_POST['regtime'],
				'content' => $_POST['content'] 
			);
			if (!empty($_POST['password'])) {
				$data['password'] = password($_POST['password']);
			}
			if ($this->userdb->update($data, "uid=" . $_POST['uid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("user", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		
		$where = '';
		$usergroupdb = new model_usergroup();
		$ugroup_list = $usergroupdb->get_list(100, 0, " ugid,name ", $where, "uid ASC ");
		$show_zone = 1;
		$show_validator = 1;
		include trig_mvc_template::view('userform');
	}

	public function delete() {
		$uid = $_GET['uid'];
		if (!empty($uid) && $uid != 1) {
			if ($this->userdb->delete($uid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("user", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
	
	/*
	 * 修改密码
	 */
	function editpass() {
		$user_info = $this->userdb->get_user_info($_SESSION['admin_uid']);
		include trig_mvc_template::view('changepass');
	}
	
	/*
	 * 保存修改密码
	 */
	function save_admin_pass() {
		$oldpassword = $_POST['oldpassword'];
		$password = $_POST['password'];
		$password1 = $_POST['password1'];
		$admin_uid = $_SESSION['admin_uid'];
		
		if (!empty($admin_uid)) {
			$user_info = $this->userdb->get_user_info($admin_uid);
			if ($password != $password1) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'tow_password_is_not_match'), -1);
			}
			if (trig_func_common::password($oldpassword) != $user_info['password']) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'old_password_is_wrong'), -1);
			} else {
				if ($this->userdb->update_password($admin_uid, $password)) {
					$session = new model_session();
					$session->my_session_start();
					$session->delete_session($_SESSION['admin_uid']);
					$session->clearcookie('auth');
					trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_password_success'), trig_mvc_route::get_uri("login","init","admin"));
				} else {
					trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_password_failure'), -1);
				}
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'no_user'), -1);
		}
	}
}
?>