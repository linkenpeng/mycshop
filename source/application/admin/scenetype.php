<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_scenetype extends application_base {
	private $scenetypedb;

	function __construct() {
		parent::__construct();
		$this->scenetypedb = new model_scenetype();
	}

	function init() {
		$where = " WHERE 1 ";
		// 分页		
		$count = $this->scenetypedb->get_count($where);
		$pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
		$nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$setarr = array(
			'total' => $count,
			'perpage' => $pagesize 
		);
		$p = new trig_page($setarr);
		// 获取分页后的数据
		$list = $this->scenetypedb->get_list($pagesize, $pagesize * ($nowpage - 1), " * ", $where, "dateline DESC ");
		$show_zone = 1;
		
		include trig_func_common::admin_template('scenetype');
	}
	
	public function add() {
		if (!empty($_POST['action'])) {
			if (empty($_POST['name'])) {
				trig_func_common::ShowMsg("分类名不能为空!", -1);
			}
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			if (!empty($_FILES['image']['name'])) {
				
				$upfile = new trig_uploadfile("jpg,gif,bmp,png");
				$upfile->savesamll = 1;
				$image = $upfile->upload($_FILES['image']);
			}
			$data = array(
				'parentid' => $_POST['parentid'],
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'name' => $_POST['name'],
				'enname' => $_POST['enname'],
				'image' => $image,
				'description' => $_POST['description'],
				'dateline' => $_POST['dateline'] 
			);
			if ($this->scenetypedb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_func_common::get_uri("scenetype", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_func_common::admin_template('scenetypeform');
	}
	
	public function edit() {
		$typeid = $_GET['typeid'];
		if (!empty($typeid)) {
			$value = $this->scenetypedb->get_one($typeid);
		}
		if (!empty($_POST['action']) && !empty($_POST['typeid'])) {
			if (!empty($_FILES['image']['name'])) {
				
				$upfile = new trig_uploadfile("jpg,gif,bmp,png");
				$upfile->savesamll = 1;
				$image = $upfile->upload($_FILES['image']);
			}
			$data = array(
				'name' => $_POST['name'],
				'enname' => $_POST['enname'],
				'description' => $_POST['description'] 
			);
			if (!empty($image)) {
				$data['image'] = $image;
				// 删除老图片
				if ($image != $_POST['oldimage']) {
					@unlink(UPLOAD_PATH . '/' . $_POST['oldimage']);
					@unlink(UPLOAD_PATH . '/thumb/' . $_POST['oldimage']);
				}
			}
			if ($this->scenetypedb->update($data, "typeid=" . $_POST['typeid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_func_common::get_uri("scenetype", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_func_common::admin_template('scenetypeform');
	}
	
	public function delete() {
		$typeid = $_GET['typeid'];
		if (!empty($typeid)) {
			if ($this->scenetypedb->delete($typeid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_func_common::get_uri("scenetype", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}