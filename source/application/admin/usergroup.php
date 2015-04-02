<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_usergroup extends application_base {
	private $usergroupdb;

	function __construct() {
		parent::__construct();
		$this->usergroupdb = new model_usergroup();
	}

	function init() {
		$where = " WHERE 1 ";
		// 分页
		$count = $this->usergroupdb->get_count($where);
		$pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
		$nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$setarr = array(
			'total' => $count,
			'perpage' => $pagesize 
		);
		$p = new trig_page($setarr);
		// 获取分页后的数据
		$list = $this->usergroupdb->get_list($pagesize, $pagesize * ($nowpage - 1), " * ", $where, "uid ASC ");
		$show_zone = 1;
		include trig_func_common::admin_template('usergroup');
	}

	public function add() {
		if (!empty($_POST['action'])) {
			if (empty($_POST['name'])) {
				trig_func_common::ShowMsg("分类名不能为空!", -1);
			}
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			$data = array(
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'name' => $_POST['name'],
				'description' => $_POST['description'],
				'dateline' => $_POST['dateline'] 
			);
			if ($this->usergroupdb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_func_common::get_uri("usergroup", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_func_common::admin_template('usergroupform');
	}

	public function edit() {
		$ugid = $_GET['ugid'];
		if (!empty($ugid)) {
			$value = $this->usergroupdb->get_one($ugid);
		}
		if (!empty($_POST['action']) && !empty($_POST['ugid'])) {
			$data = array(
				'name' => $_POST['name'],
				'description' => $_POST['description'] 
			);
			if ($this->usergroupdb->update($data, "ugid=" . $_POST['ugid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_func_common::get_uri("usergroup", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_func_common::admin_template('usergroupform');
	}

	public function delete() {
		$ugid = $_GET['ugid'];
		if (!empty($ugid)) {
			if ($this->usergroupdb->delete($ugid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_func_common::get_uri("usergroup", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}

	public function permission() {
		if (!empty($_POST['action']) && !empty($_POST['ugid'])) {
			if (!empty($_POST['menuid'])) {
				$permission = implode(",", $_POST['menuid']);
			}
			$data = array(
				'permission' => $permission 
			);
			if ($this->usergroupdb->update($data, "ugid=" . $_POST['ugid'])) {
				trig_func_common::ShowMsg("权限设置成功!", trig_func_common::get_uri("usergroup", "init"));
			} else {
				trig_func_common::ShowMsg("权限设置失败!", -1);
			}
		}
		$ugid = $_GET['ugid'];
		if (!empty($ugid)) {
			// 获取用户组信息
			$value = $this->usergroupdb->get_one($ugid);
			$permissions = array();
			if (!empty($value['permission'])) {
				$permissions = explode(",", $value['permission']);
			}
			// 获取菜单信息
			$where = '';
			$menudb = new model_menu();
			$list = $menudb->get_list(1000, 0, " * ", $where, "sort_order,ctrl ASC,menuid ASC ");
			$list = $menudb->make_tree_list($list);
			include trig_func_common::admin_template('usergroup_permission');
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}
?>