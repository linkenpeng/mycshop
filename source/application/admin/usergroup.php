<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_usergroup extends application_admin_base {
	private $usergroupdb;

	function __construct() {
		parent::__construct();
		$this->usergroupdb = new model_usergroup();
	}

	function init() {
		$where = " WHERE 1 ";
		// 分页
		$count = $this->usergroupdb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		// 获取分页后的数据
		$list = $this->usergroupdb->get_list($p->perpage, $p->offset, " * ", $where, "uid ASC ");
		
		$this->display('usergroup', array(
			'list' => $list,
			'p' => $p,
			'show_zone' => 1
		));
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
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("usergroup", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$show_validator = 1;
		
		$this->display('usergroupform', array(
			'show_validator' => 1
		));
	}

	public function edit() {
		if (!empty($_POST['action']) && !empty($_POST['ugid'])) {
			$data = array(
				'name' => $_POST['name'],
				'description' => $_POST['description'] 
			);
			if ($this->usergroupdb->update($data, "ugid=" . $_POST['ugid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("usergroup", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
        $ugid = $_GET['ugid'];
        if (!empty($ugid)) {
            $value = $this->usergroupdb->get_one($ugid);
        }
		$this->display('usergroupform', array(
			'show_validator' => 1,
			'value' => $value
		));
	}

	public function delete() {
		$ugid = $_GET['ugid'];
		if (!empty($ugid)) {
			if ($this->usergroupdb->delete($ugid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("usergroup", "init"));
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
				trig_func_common::ShowMsg("权限设置成功!", trig_mvc_route::get_uri("usergroup", "init"));
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
			$list = $menudb->get_all(" * ", $where, "sort_order,ctrl ASC,menuid ASC ");
			$list = $menudb->make_tree_list($list);
			
			$this->display('usergroup_permission', array(
				'value' => $value,
				'permissions' => $permissions,
				'list' => $list
			));
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}
?>