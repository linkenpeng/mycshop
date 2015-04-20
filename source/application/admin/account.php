<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_account extends application_base {
	private $accountdb;

	function __construct() {
		parent::__construct();
		$this->accountdb = new model_account();
	}

	function init() {
		$where = " WHERE 1 ";
		// 分页
		$count = $this->accountdb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		// 获取分页后的数据
		$list = $this->accountdb->get_list($p->perpage, $p->offset, " * ", $where, "dateline DESC ");
		// 分类
		$accounttypedb = new model_accounttype();
		$actype_list = $accounttypedb->get_list(100, 0, " actypeid,name ", "", "actypeid ASC ");
		foreach ($actype_list as $k => $v) {
			$accounttype_list[$v['actypeid']] = $v['name'];
		}
		include trig_mvc_template::admin_template('account');
	}

	public function add() {
		if (!empty($_POST['action'])) {
			if (empty($_POST['accountname'])) {
				trig_func_common::ShowMsg("名称不能为空!", -1);
			}
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			$data = array(
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'actypeid' => $_POST['actypeid'],
				'accountname' => $_POST['accountname'],
				'description' => $_POST['description'],
				'dateline' => $_POST['dateline'] 
			);
			if ($this->accountdb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("account", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		// 分类
		$accounttypedb = new model_accounttype();
		$actype_list = $accounttypedb->get_list(100, 0, " actypeid,name ", "", "actypeid ASC ");
		$show_validator = 1;
		include trig_mvc_template::admin_template('accountform');
	}

	public function edit() {
		$accountid = $_GET['accountid'];
		if (!empty($accountid)) {
			$value = $this->accountdb->get_one($accountid);
		}
		if (!empty($_POST['action']) && !empty($_POST['accountid'])) {
			$data = array(
				'accountname' => $_POST['accountname'],
				'actypeid' => $_POST['actypeid'],
				'description' => $_POST['description'] 
			);
			if ($this->accountdb->update($data, "accountid=" . $_POST['accountid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("account", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		// 分类
		$accounttypedb = new model_accounttype();
		$actype_list = $accounttypedb->get_list(100, 0, " actypeid,name ", "", "actypeid ASC ");
		$show_validator = 1;
		include trig_mvc_template::admin_template('accountform');
	}

	public function delete() {
		$accountid = $_GET['accountid'];
		if (!empty($accountid)) {
			if ($this->accountdb->delete($accountid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("account", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}