<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_articlecat extends application_base {
	private $articlecatdb;
	const pagesize = 100;
	public $cattypes = array(
		1 => '资讯',
		2 => '商家' 
	);

	function __construct() {
		parent::__construct();
		$this->articlecatdb = new model_articlecat();
	}

	function init() {
		$where = " WHERE 1 ";
		// 分页
		$count = $this->articlecatdb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => self::pagesize));
		// 获取分页后的数据
		$list = array();
		$sourcelist = $this->articlecatdb->get_list($p->perpage, $p->offset, " * ", $where, "ordernum DESC, dateline DESC ");
		$sourcelist = $this->articlecatdb->make_tree($sourcelist);
		$this->articlecatdb->sort_tree($sourcelist, $list);
		
		$show_zone = 1;
		
		// 景区列表
		$scenedb = new model_scene();
		$scene_list = $scenedb->get_list(100, 0, " sceneid,scenename ", "", "dateline DESC ");
		$sc_list = array();
		foreach ($scene_list as $k => $v) {
			$sc_list[$v['sceneid']] = $v['scenename'];
		}
		
		include trig_mvc_template::admin_template('articlecat');
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
			if (!empty($_POST['sceneid'])) {
				$sceneid = implode(',', $_POST['sceneid']);
			}
			$data = array(
				'upid' => $_POST['upid'],
				'cattype' => $_POST['cattype'],
				'sceneid' => $sceneid,
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'name' => $_POST['name'],
				'image' => $image,
				'description' => $_POST['description'],
				'ordernum' => $_POST['ordernum'],
				'dateline' => $_POST['dateline'] 
			);
			if ($this->articlecatdb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("articlecat", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		
		$options = $this->makeOptions();
		
		// 景区列表
		$scenedb = new model_scene();
		$scene_list = $scenedb->get_list(100, 0, " sceneid,scenename ", "", "dateline DESC ");
		
		$show_validator = 1;
		include trig_mvc_template::admin_template('articlecatform');
	}

	public function edit() {
		$catid = $_GET['catid'];
		if (!empty($catid)) {
			$value = $this->articlecatdb->get_one($catid);
		}
		if (!empty($_POST['action']) && !empty($_POST['catid'])) {
			if (!empty($_FILES['image']['name'])) {
				
				$upfile = new trig_uploadfile("jpg,gif,bmp,png");
				$upfile->savesamll = 1;
				$image = $upfile->upload($_FILES['image']);
			}
			if (!empty($_POST['sceneid'])) {
				$sceneid = implode(',', $_POST['sceneid']);
			}
			$data = array(
				'sceneid' => $sceneid,
				'cattype' => $_POST['cattype'],
				'name' => $_POST['name'],
				'upid' => $_POST['upid'],
				'ordernum' => $_POST['ordernum'],
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
			if ($this->articlecatdb->update($data, "catid=" . $_POST['catid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("articlecat", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		
		$options = $this->makeOptions();
		
		// 景区列表
		$scenedb = new model_scene();
		$scene_list = $scenedb->get_list(100, 0, " sceneid,scenename ", "", "dateline DESC ");
		
		$sceneids = explode(',', $value['sceneid']);
		
		$show_validator = 1;
		include trig_mvc_template::admin_template('articlecatform');
	}

	/**
	 * 组合下拉列表
	 */
	private function makeOptions() {
		$where = " WHERE 1 ";
		$catlist = $this->articlecatdb->get_list(0, 0, " * ", $where, "dateline DESC ");
		$catlist = $this->articlecatdb->make_tree($catlist);
		
		$topcat = array(
			0 => array(
				'catid' => 0,
				'upid' => 0,
				'name' => '顶级分类' 
			) 
		);
		$catlist = $topcat + $catlist;
		
		$tolist = array();
		$this->articlecatdb->sort_tree($catlist, $tolist);
		
		return $this->articlecatdb->recur_options($_GET['upid'], $_GET['catid'], $tolist);
	}

	/**
	 * 删除
	 */
	public function delete() {
		$catid = $_GET['catid'];
		if (!empty($catid)) {
			if ($this->articlecatdb->delete($catid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("articlecat", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}