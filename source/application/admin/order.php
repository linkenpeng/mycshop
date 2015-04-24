<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_order extends application_admin_base {
	private $orderdb;

	function __construct() {
		parent::__construct();
		$this->orderdb = new model_order();
	}

	function init() {
		$where = " WHERE 1 ";
		// 分页		
		$count = $this->orderdb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		// 获取分页后的数据
		$list = $this->orderdb->get_list($p->perpage, $p->offset, " * ", $where, "dateline DESC ");
		include trig_mvc_template::view_file('order');
	}
	
	public function add() {
		if (!empty($_POST['action'])) {
			if (empty($_POST['ordername'])) {
				trig_func_common::ShowMsg("订单名称不能为空!", -1);
			}
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			$data = array(
				'uid' => $_SESSION['admin_uid'],
				'username' => $title['admin_username'],
				'ordername' => $_POST['ordername'],
				'description' => $_POST['description'],
				'dateline' => $_POST['dateline'] 
			);
			if ($this->orderdb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("order", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_mvc_template::view_file('orderform');
	}
	
	public function edit() {
		$orderid = $_GET['orderid'];
		if (!empty($orderid)) {
			$value = $this->orderdb->get_one($orderid);
		}
		if (!empty($_POST['action']) && !empty($_POST['orderid'])) {
			$data = array(
				'ordername' => $_POST['ordername'],
				'description' => $_POST['description'] 
			);
			if ($this->orderdb->update($data, "orderid=" . $_POST['orderid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("order", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_mvc_template::view_file('orderform');
	}
	
	public function delete() {
		$orderid = $_GET['orderid'];
		if (!empty($orderid)) {
			if ($this->orderdb->delete($orderid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("order", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}