<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_system extends application_admin_base {
	private $systemdb;

	function __construct() {
		parent::__construct();
		$this->systemdb = new model_system();
	}

	function init() {
		$where = " WHERE 1 ";
		$count = $this->systemdb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		$list = $this->systemdb->get_list($p->perpage, $p->offset, " * ", $where);
		include trig_mvc_template::view('system_index');
	}

	function add() {
		if (!empty($_POST['action'])) {
			if (empty($_POST['config_key']) || empty($_POST['config_value'])) {
				trig_func_common::ShowMsg("配置键或者配置值不能为空!", -1);
			}
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			$data = array(
				'config_key' => $_POST['config_key'],
				'config_value' => $_POST['config_value'],
				'name' => $_POST['name'],
				'dateline' => $_POST['dateline'] 
			);
			if ($this->systemdb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("system", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_mvc_template::view('system_form');
	}

	public function edit() {
		$sid = $_GET['sid'];
		if (!empty($sid)) {
			$value = $this->systemdb->get_one($sid);
		}
		if (!empty($_POST['action']) && !empty($_POST['sid'])) {
			$data = array(
				'config_key' => $_POST['config_key'],
				'config_value' => $_POST['config_value'],
				'name' => $_POST['name'] 
			);
			if ($this->systemdb->update($data, "sid=" . $_POST['sid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("system", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_mvc_template::view('system_form');
	}

	function cache() {
		if ($this->systemdb->cache()) {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_cache_success'), trig_mvc_route::get_uri("system", "init"));
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_cache_failure'), -1);
		}
	}
}