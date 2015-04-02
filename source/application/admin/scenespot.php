<?php
defined('SYS_IN') or exit('Access Denied.');
class application_admin_scenespot extends application_base {
	private $scenespotdb;
	function __construct() {
		parent::__construct();
		$this->scenespotdb = new model_scenespot();
	}
	function init() {
		$where = " WHERE 1 ";
		$typeid = empty($_GET['typeid']) ? "" : intval($_GET['typeid']);
		$traveltopicid = empty($_GET['traveltopicid']) ? "" : intval($_GET['traveltopicid']);
		$level = empty($_GET['level']) ? "" : intval($_GET['level']);
		$scenespotname = empty($_GET['scenespotname']) ? '' : trim($_GET['scenespotname']);
		$scenename = empty($_GET['scenename']) ? '' : trim($_GET['scenename']);
		$infocards = empty($_GET['infocards']) ? '' : trim($_GET['infocards']);
		
		$where = " WHERE 1 ";
		if (!empty($typeid)) {
			$where .= " and s.typeid=" . $typeid;
		}
		if (!empty($traveltopicid)) {
			$where .= " and s.traveltopicid=" . $traveltopicid;
		}
		if (!empty($level)) {
			$where .= " and s.level=" . $level;
		}
		if (!empty($scenespotname)) {
			$where .= " and sp.`scenespotname` like '%" . $scenespotname . "%' ";
		}
		if (!empty($scenename)) {
			$where .= " and s.`scenename` like '%" . $scenename . "%' ";
		}
		if (!empty($infocards)) {
			$where .= " and sp.`infocards`='" . $infocards . "' ";
		}
		// 分页
		$count = $this->scenespotdb->get_count($where);
		$pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
		$nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$setarr = array(
			'total' => $count,
			'perpage' => $pagesize 
		);
		$p = new trig_page($setarr);
		$field = "sp.scenespotid,sp.sceneid,sp.scenespotname,sp.scenespot_enname,sp.infocards,sp.image,s.scenename,s.typeid,s.traveltopicid,s.level";
		// 获取分页后的数据
		$list = $this->scenespotdb->get_list($pagesize, $pagesize * ($nowpage - 1), $field, $where, " sp.infocards ASC ");
		// 分类
		$scenetypedb = new model_scenetype();
		$pt_list = $scenetypedb->get_list(100, 0, " typeid,name ", "", "typeid ASC ");
		foreach ($pt_list as $k => $v) {
			$scenetype_list[$v['typeid']] = $v['name'];
		}
		// 游玩主题
		$traveltopicdb = new model_traveltopic();
		$rt_list = $traveltopicdb->get_list(100, 0, " typeid,name ", "", "typeid ASC ");
		foreach ($rt_list as $k => $v) {
			$traveltopic_list[$v['typeid']] = $v['name'];
		}
		// 景区级别
		$level_list = array(
			'5' => '5A',
			'4' => '4A',
			'3' => '3A',
			'2' => '3A以下' 
		);
		
		include trig_func_common::admin_template('scenespot');
	}
	
	public function add() {
		if (!empty($_POST['action'])) {
			if (empty($_POST['scenespotname'])) {
				trig_func_common::ShowMsg("景点名称不能为空!", -1);
			}
			if (empty($_POST['sceneid'])) {
				trig_func_common::ShowMsg("景区id不能为空!", -1);
			}
			if ($this->scenespotdb->exists_infocards(trim($_POST['infocards']))) {
				trig_func_common::ShowMsg("该景点编号已经存在!", -1);
			}
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			if (!empty($_FILES['image']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_IMAGE_FILE_TYPES);
				$upfile->savesamll = 1;
				$image = $upfile->upload($_FILES['image']);
			}
			if (!empty($_FILES['cn_audio']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_AUDIO_FILE_TYPES);
				$cn_audio = $upfile->upload($_FILES['cn_audio']);
			}
			if (!empty($_FILES['en_audio']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_AUDIO_FILE_TYPES);
				$en_audio = $upfile->upload($_FILES['en_audio']);
			}
			$data = array(
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'parent_scenespotid' => $_POST['parent_scenespotid'],
				'sceneid' => $_POST['sceneid'],
				'scenespotname' => $_POST['scenespotname'],
				'scenespot_enname' => $_POST['scenespot_enname'],
				'image' => $image,
				'infocards' => $_POST['infocards'],
				'description' => $_POST['description'],
				'cn_audio' => $cn_audio,
				'en_audio' => $en_audio,
				'dateline' => $_POST['dateline'] 
			);
			if ($this->scenespotdb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_func_common::get_uri("scenespot", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		// 得到上级景点列表
		$parent_scenespot_list = $this->scenespotdb->get_parent_list(10000, 0, "scenespotid,parent_scenespotid,scenespotname", " WHERE parent_scenespotid=0 ", " dateline DESC ");
		// 得到景区信息
		$sceneid = empty($_GET['sceneid']) ? '' : intval($_GET['sceneid']);
		$scenedb = new model_scene();
		$value = $scenedb->get_one($sceneid, "sceneid,scenename");
		
		$value['infocards'] = $this->scenespotdb->get_auto_infocards();
		
		$show_validator = 1;
		
		include trig_func_common::admin_template('scenespotform');
	}
	
	public function edit() {
		$scenespotid = $_GET['scenespotid'];
		if (!empty($scenespotid)) {
			$value = $this->scenespotdb->get_one($scenespotid);
		}
		if (!empty($_POST['action']) && !empty($_POST['scenespotid'])) {				
			if (!empty($_FILES['image']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_IMAGE_FILE_TYPES);
				$upfile->savesamll = 1;
				$image = $upfile->upload($_FILES['image']);
			}			
			if (!empty($_FILES['cn_audio']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_AUDIO_FILE_TYPES);
				$cn_audio = $upfile->upload($_FILES['cn_audio']);
			}
			if (!empty($_FILES['en_audio']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_AUDIO_FILE_TYPES);
				$en_audio = $upfile->upload($_FILES['en_audio']);
			}
			$data = array(
				'scenespotname' => $_POST['scenespotname'],
				'scenespot_enname' => $_POST['scenespot_enname'],
				'parent_scenespotid' => $_POST['parent_scenespotid'],
				'infocards' => $_POST['infocards'],
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
			if (!empty($cn_audio)) {
				$data['cn_audio'] = $cn_audio;
				// 删除老文件
				if ($cn_audio != $_POST['old_cn_audio']) {
					@unlink(UPLOAD_PATH . '/' . $_POST['old_cn_audio']);
				}
			}
			if (!empty($en_audio)) {
				$data['en_audio'] = $en_audio;
				// 删除老文件
				if ($en_audio != $_POST['old_en_audio']) {
					@unlink(UPLOAD_PATH . '/' . $_POST['old_en_audio']);
				}
			}
			if ($this->scenespotdb->update($data, "scenespotid=" . $_POST['scenespotid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_func_common::get_uri("scenespot", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		// 得到上级景点列表
		$parent_scenespot_list = $this->scenespotdb->get_parent_list(10000, 0, "scenespotid,parent_scenespotid,scenespotname", " WHERE parent_scenespotid=0 ", " dateline DESC ");
		
		$show_validator = 1;
		include trig_func_common::admin_template('scenespotform');
	}
	
	public function delete() {
		$scenespotid = $_GET['scenespotid'];
		if (!empty($scenespotid)) {
			if ($this->scenespotdb->delete($scenespotid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_func_common::get_uri("scenespot", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}
?>