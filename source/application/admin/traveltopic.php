<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_traveltopic extends application_admin_base {
	private $traveltopicdb;

	function __construct() {
		parent::__construct();
		$this->traveltopicdb = new model_traveltopic();
	}

	function init() {
		$where = " WHERE 1 ";
		// 分页
		$count = $this->traveltopicdb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		// 获取分页后的数据
		$list = $this->traveltopicdb->get_list($p->perpage, $p->offset, " * ", $where, "dateline DESC ");
		$show_zone = 1;
		include trig_mvc_template::view_file('traveltopic');
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
				'image' => $image,
				'description' => $_POST['description'],
				'dateline' => $_POST['dateline'] 
			);
			if ($this->traveltopicdb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("traveltopic", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_mvc_template::view_file('traveltopicform');
	}
	
	public function edit() {
		$typeid = $_GET['typeid'];
		if (!empty($typeid)) {
			$value = $this->traveltopicdb->get_one($typeid);
		}
		if (!empty($_POST['action']) && !empty($_POST['typeid'])) {
			if (!empty($_FILES['image']['name'])) {
				
				$upfile = new trig_uploadfile("jpg,gif,bmp,png");
				$upfile->savesamll = 1;
				$image = $upfile->upload($_FILES['image']);
			}
			$data = array(
				'name' => $_POST['name'],
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
			if ($this->traveltopicdb->update($data, "typeid=" . $_POST['typeid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("traveltopic", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$show_validator = 1;
		include trig_mvc_template::view_file('traveltopicform');
	}
	
	public function delete() {
		$typeid = $_GET['typeid'];
		if (!empty($typeid)) {
			if ($this->traveltopicdb->delete($typeid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("traveltopic", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}
?>