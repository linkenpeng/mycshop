<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_notetype extends application_admin_base {
	private $notetypedb;

	function __construct() {
		parent::__construct();
		$this->notetypedb = new model_notetype();
	}

	function init() {
		$where = " WHERE 1 ";
		// 分页		
		$count = $this->notetypedb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		// 获取分页后的数据
		$list = $this->notetypedb->get_list($p->perpage, $p->offset, " * ", $where, "dateline DESC ");
		$show_zone = 1;
		include trig_mvc_template::view('notetype');
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
			if ($this->notetypedb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("notetype", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_mvc_template::view('notetypeform');
	}
	
	public function edit() {
		$notetypeid = $_GET['notetypeid'];
		if (!empty($notetypeid)) {
			$value = $this->notetypedb->get_one($notetypeid);
		}
		if (!empty($_POST['action']) && !empty($_POST['notetypeid'])) {
			$data = array(
				'name' => $_POST['name'],
				'description' => $_POST['description'] 
			);
			if ($this->notetypedb->update($data, "notetypeid=" . $_POST['notetypeid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("notetype", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_mvc_template::view('notetypeform');
	}
	
	public function delete() {
		$notetypeid = $_GET['notetypeid'];
		if (!empty($notetypeid)) {
			if ($this->notetypedb->delete($notetypeid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("notetype", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}