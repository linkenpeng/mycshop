<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_accounttype extends application_base {
	private $accounttypedb;

	function __construct() {
		parent::__construct();
		$this->accounttypedb = new model_accounttype();
	}

	function init() {
		$where = " WHERE 1 ";
		// 分页		
		$count = $this->accounttypedb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		// 获取分页后的数据
		$list = $this->accounttypedb->get_list($p->perpage, $p->offset, " * ", $where, "dateline DESC ");
		$show_zone = 1;
		include trig_mvc_template::admin_template('accounttype');
	}
	
	public function add() {
		if (!empty($_POST['action'])) {
			if (empty($_POST['name'])) {
				trig_func_common::ShowMsg("分类名不能为空!", -1);
			}
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			$data = array(
				'parentid' => $_POST['parentid'],
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'name' => $_POST['name'],
				'description' => $_POST['description'],
				'dateline' => $_POST['dateline'] 
			);
			if ($this->accounttypedb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("accounttype", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_mvc_template::admin_template('accounttypeform');
	}
	
	public function edit() {
		$actypeid = $_GET['actypeid'];
		if (!empty($actypeid)) {
			$value = $this->accounttypedb->get_one($actypeid);
		}
		if (!empty($_POST['action']) && !empty($_POST['actypeid'])) {
			$data = array(
				'name' => $_POST['name'],
				'parentid' => $_POST['parentid'],
				'description' => $_POST['description'] 
			);
			if ($this->accounttypedb->update($data, "actypeid=" . $_POST['actypeid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("accounttype", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_mvc_template::admin_template('accounttypeform');
	}
	
	public function delete() {
		$actypeid = $_GET['actypeid'];
		if (!empty($actypeid)) {
			if ($this->accounttypedb->delete($actypeid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("accounttype", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}