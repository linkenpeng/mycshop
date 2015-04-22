<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_talkreply extends application_admin_base {
	private $talkreplydb;

	function __construct() {
		parent::__construct();
		$this->talkreplydb = new model_talkreply();
	}

	function init() {
		$where = " WHERE 1 ";
		// 分页
		$count = $this->talkreplydb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		// 获取分页后的数据
		$list = $this->talkreplydb->get_list($p->perpage, $p->offset, " * ", $where, "dateline DESC ");
	}
	
	public function add() {
		if (!empty($_POST['action'])) {
			if (empty($_POST['content'])) {
				trig_func_common::ShowMsg("内容不能为空!", -1);
			}
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			$data = array(
				'talkid' => $_POST['talkid'],
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'content' => $_POST['content'],
				'dateline' => $_POST['dateline'] 
			);
			if ($this->talkreplydb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("talk", "show", "admin", "talkid=" . $_POST['talkid']));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$show_validator = 1;
		//include trig_mvc_template::view('talkreplyform');
	}
	
	public function edit() {
		$talkreplyid = $_GET['talkreplyid'];
		if (!empty($talkreplyid)) {
			$value = $this->talkreplydb->get_one($talkreplyid);
		}
		if (!empty($_POST['action']) && !empty($_POST['talkreplyid'])) {
			$data = array(
				'content' => $_POST['content'] 
			);
			if ($this->talkreplydb->update($data, "talkreplyid=" . $_POST['talkreplyid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("talkreply", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$show_validator = 1;
		// include trig_mvc_template::view('talkreplyform');
	}
	
	public function delete() {
		$talkreplyid = $_GET['talkreplyid'];
		$talkid = $_GET['talkid'];
		if (!empty($talkreplyid) && !empty($talkid)) {
			if ($this->talkreplydb->delete($talkreplyid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("talk", "show", "admin", "talkid=" . $_GET['talkid']));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}
?>