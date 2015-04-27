<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_menu extends application_admin_base {
	private $menudb;

	function __construct() {
		parent::__construct();
		$this->menudb = new model_menu();
	}

	function init() {
		$where = " WHERE 1 ";
		$count = $this->menudb->get_count($where);
		$p = new trig_page(array(
			'total_count' => $count,
			'default_page_size' => 100 
		));
		// 获取分页后的数据
		$list = $this->menudb->get_list($p->perpage, $p->offset, " * ", $where, "sort_order,ctrl ASC,menuid ASC ");
		$list = $this->menudb->make_tree_list($list);
		
		$this->display('menu', array(
			'p' => $p,
			'list' => $list
		));
	}

	public function add() {
		$value['parentid'] = empty($_GET['parentid']) ? '' : intval($_GET['parentid']);
		if (!empty($_POST['action'])) {
			if (empty($_POST['name'])) {
				trig_func_common::ShowMsg("菜单名不能为空!", -1);
			}
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			$data = array(
				'parentid' => $_POST['parentid'],
				'model' => $_POST['model'],
				'ctrl' => $_POST['ctrl'],
				'act' => $_POST['act'],
				'name' => $_POST['name'],
				'dateline' => $_POST['dateline'] 
			);
			if ($this->menudb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("menu", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$value_parent = $this->menudb->get_one($value['parentid']);
		$value['model'] = $value_parent['model'];
		$value['ctrl'] = $value_parent['ctrl'];
		$value['parent_name'] = $value_parent['name'];
		$show_validator = 1;
		
		$this->display('menuform', array(
			'value' => $value,
			'value_parent' => $value_parent,
			'show_validator' => $show_validator 
		));
	}

	public function edit() {
		$menuid = $_GET['menuid'];
		if (!empty($menuid)) {
			$value = $this->menudb->get_one($menuid);
			$value_parent = $this->menudb->get_one($value['parentid']);
		}
		if (!empty($_POST['action']) && !empty($_POST['menuid'])) {
			$data = array(
				'parentid' => $_POST['parentid'],
				'model' => $_POST['model'],
				'ctrl' => $_POST['ctrl'],
				'act' => $_POST['act'],
				'name' => $_POST['name'] 
			);
			if ($this->menudb->update($data, "menuid=" . $_POST['menuid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("menu", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$show_validator = 1;
		
		$this->display('menuform', array(
			'value' => $value,
			'value_parent' => $value_parent,
			'show_validator' => $show_validator 
		));
	}

	public function delete() {
		$menuid = $_GET['menuid'];
		if (!empty($menuid)) {
			if ($this->menudb->delete($menuid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("menu", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}

	function sortorder() {
		foreach ($_POST as $k => $v) {
			if (preg_match("/^orderid_(\d+)$/is", $k, $matches)) {
				$id = intval($matches[1]);
				$val = intval($v);
				if (!empty($id)) {
					$this->menudb->update_sortorder($id, $val);
				}
			}
		}
		trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_status_success'), trig_mvc_route::get_uri("menu", "init"));
	}
}
?>