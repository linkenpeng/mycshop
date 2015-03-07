<?php
defined('SYS_IN') or exit('Access Denied.');

class system extends controller {
	private $systemdb;

	function __construct() {
		parent::__construct();
		$this->systemdb = Base::load_model("system_model");
	}

	function init() {
		$where = " WHERE 1 ";
		$count = $this->systemdb->get_count($where);
		$pagesize = !isset($_GET['pagesize']) ? "100" : $_GET['pagesize'];
		$nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$setarr = array(
			'total' => $count,
			'perpage' => $pagesize 
		);
		$p = new page($setarr);
		$list = $this->systemdb->get_list($pagesize, $pagesize * ($nowpage - 1), " * ", $where);
		include admin_template('system_index');
	}

	function add() {
		if (!empty($_POST['action'])) {
			if (empty($_POST['config_key']) || empty($_POST['config_value'])) {
				ShowMsg("配置键或者配置值不能为空!", -1);
			}
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			$data = array(
				'config_key' => $_POST['config_key'],
				'config_value' => $_POST['config_value'],
				'name' => $_POST['name'],
				'dateline' => $_POST['dateline'] 
			);
			if ($this->systemdb->insert($data)) {
				ShowMsg(lang('message', 'insert_success'), get_uri("system", "init"));
			} else {
				ShowMsg(lang('message', 'insert_failure'), -1);
			}
		}
		$show_validator = 1;
		include admin_template('system_form');
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
				ShowMsg(lang('message', 'update_success'), get_uri("system", "init"));
			} else {
				ShowMsg(lang('message', 'update_failure'), -1);
			}
		}
		$show_validator = 1;
		include admin_template('system_form');
	}

	function cache() {
		if ($this->systemdb->cache()) {
			ShowMsg(lang('message', 'update_cache_success'), get_uri("system", "init"));
		} else {
			ShowMsg(lang('message', 'update_cache_failure'), -1);
		}
	}
}