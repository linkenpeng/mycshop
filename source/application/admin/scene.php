<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_scene extends application_admin_base {
	private $scenedb;

	function __construct() {
		parent::__construct();
		$this->scenedb = new model_scene();
	}

	function init() {
		$where = " WHERE 1 ";
		$typeid = empty($_GET['typeid']) ? "" : intval($_GET['typeid']);
		$traveltopicid = empty($_GET['traveltopicid']) ? "" : intval($_GET['traveltopicid']);
		$level = empty($_GET['level']) ? "" : intval($_GET['level']);
		$scenenum = empty($_GET['scenenum']) ? '' : trim($_GET['scenenum']);
		$scenename = empty($_GET['scenename']) ? '' : trim($_GET['scenename']);
		$province = empty($_GET['province']) ? '河北省' : trim($_GET['province']);
		$city = empty($_GET['city']) ? '' : trim($_GET['city']);
		$country = empty($_GET['country']) ? '' : trim($_GET['country']);
		$where = " WHERE 1 ";
		if (!empty($typeid)) {
			$where .= " and typeid=" . $typeid;
		}
		if (!empty($traveltopicid)) {
			$where .= " and traveltopicid=" . $traveltopicid;
		}
		if (!empty($level)) {
			$where .= " and level=" . $level;
		}
		if (!empty($scenename)) {
			$where .= " and `scenename` like '%" . $scenename . "%' ";
		}
		if (!empty($scenenum)) {
			$where .= " and `scenenum` like '%" . $scenenum . "%' ";
		}
		if (!empty($province)) {
			$where .= " and `province`='" . $province . "' ";
		}
		if (!empty($city)) {
			$where .= " and `city`='" . $city . "' ";
		}
		if (!empty($country)) {
			$where .= " and `country`='" . $country . "' ";
		}
		// 分页		
		$count = $this->scenedb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		// 获取分页后的数据
		$list = $this->scenedb->get_list($p->perpage, $p->offset, " * ", $where, "scenenum ASC ");
		// 分类
		$scenetypedb = new model_scenetype();
		$pt_list = $scenetypedb->get_list(100, 0, " typeid,name ", "", "typeid ASC ");
		$scenetype_list = array();
		foreach ($pt_list as $k => $v) {
			$scenetype_list[$v['typeid']] = $v['name'];
		}
		// 游玩主题
		$traveltopicdb = new model_traveltopic();
		$rt_list = $traveltopicdb->get_list(100, 0, " typeid,name ", "", "typeid ASC ");
		$traveltopic_list = array();
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
		$show_zone = 1;
		include trig_mvc_template::view_file('scene');
	}

	public function add() {
		if (!empty($_POST['action'])) {
			if (empty($_POST['scenename']) || empty($_POST['scenenum'])) {
				trig_func_common::ShowMsg("景区名称和编号不能为空!", -1);
			}
			
			if ($this->scenedb->exists_scenenum(trim($_POST['scenenum']))) {
				trig_func_common::ShowMsg("该景区编号已经存在!", -1);
			}
			
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			if (!empty($_FILES['image']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_IMAGE_FILE_TYPES);
				$upfile->savesamll = 1;
				$image = $upfile->upload($_FILES['image']);
			}
			
			if (!empty($_FILES['description_cn_audio']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_AUDIO_FILE_TYPES);
				$description_cn_audio = $upfile->upload($_FILES['description_cn_audio']);
			}
			if (!empty($_FILES['description_en_audio']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_AUDIO_FILE_TYPES);
				$description_en_audio = $upfile->upload($_FILES['description_en_audio']);
			}
			
			if (!empty($_FILES['note_cn_audio']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_AUDIO_FILE_TYPES);
				$note_cn_audio = $upfile->upload($_FILES['note_cn_audio']);
			}
			if (!empty($_FILES['note_en_audio']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_AUDIO_FILE_TYPES);
				$note_en_audio = $upfile->upload($_FILES['note_en_audio']);
			}
			
			$data = array(
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'typeid' => $_POST['typeid'],
				'scenename' => trim($_POST['scenename']),
				'scene_enname' => trim($_POST['scene_enname']),
				'scenenum' => trim($_POST['scenenum']),
				'image' => $image,
				'level' => $_POST['level'],
				'traveltopicid' => $_POST['traveltopicid'],
				'province' => $_POST['province'],
				'city' => $_POST['city'],
				'cityen' => trig_func_common::getPinyin(stripslashes($_POST['city'])),
				'country' => $_POST['country'],
				'address' => $_POST['address'],
				'description' => $_POST['description'],
				'description_cn_audio' => $description_cn_audio,
				'description_en_audio' => $description_en_audio,
				'note_cn_audio' => $note_cn_audio,
				'note_en_audio' => $note_en_audio,
				'note' => $_POST['note'],
				'dateline' => $_POST['dateline'] 
			);
			if ($this->scenedb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("scene", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$show_validator = 1;
		$show_editor = 1;
		$show_zone = 1;
		// $show_map = 1;
		
		// 设置默认省份
		$value['province'] = empty($value['province']) ? DEFAULT_PROVINCE : $value['province'];
		$scenetypedb = new model_scenetype();
		$scenetype_list = $scenetypedb->get_list(100, 0, " typeid,name ", $where, "typeid ASC ");
		// 游玩主题
		$traveltopicdb = new model_traveltopic();
		$traveltopic_list = $traveltopicdb->get_list(100, 0, " typeid,name ", "", "typeid ASC ");
		
		$value['scenenum'] = $this->scenedb->get_auto_scenenum();
		
		include trig_mvc_template::view_file('sceneform');
	}

	public function edit() {
		$sceneid = $_GET['sceneid'];
		if (!empty($sceneid)) {
			$value = $this->scenedb->get_one($sceneid);
			// 设置默认省份
			$value['province'] = empty($value['province']) ? DEFAULT_PROVINCE : $value['province'];
		}
		if (!empty($_POST['action']) && !empty($_POST['sceneid'])) {
			if (!empty($_FILES['image']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_IMAGE_FILE_TYPES);
				$upfile->savesamll = 1;
				$image = $upfile->upload($_FILES['image']);
			}
			if (!empty($_FILES['description_cn_audio']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_AUDIO_FILE_TYPES);
				$description_cn_audio = $upfile->upload($_FILES['description_cn_audio']);
			}
			if (!empty($_FILES['description_en_audio']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_AUDIO_FILE_TYPES);
				$description_en_audio = $upfile->upload($_FILES['description_en_audio']);
			}
			
			if (!empty($_FILES['note_cn_audio']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_AUDIO_FILE_TYPES);
				$note_cn_audio = $upfile->upload($_FILES['note_cn_audio']);
			}
			if (!empty($_FILES['note_en_audio']['name'])) {
				
				$upfile = new trig_uploadfile(UPLOAD_AUDIO_FILE_TYPES);
				$note_en_audio = $upfile->upload($_FILES['note_en_audio']);
			}
			$data = array(
				'typeid' => $_POST['typeid'],
				'scenename' => trim($_POST['scenename']),
				'scene_enname' => trim($_POST['scene_enname']),
				'scenenum' => trim($_POST['scenenum']),
				'level' => $_POST['level'],
				'traveltopicid' => $_POST['traveltopicid'],
				'province' => $_POST['province'],
				'city' => $_POST['city'],
				'cityen' => trig_func_common::getPinyin(stripslashes($_POST['city'])),
				'country' => $_POST['country'],
				'address' => $_POST['address'],
				'note' => $_POST['note'],
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
			if (!empty($description_cn_audio)) {
				$data['description_cn_audio'] = $description_cn_audio;
				// 删除老文件
				if ($description_cn_audio != $_POST['old_description_cn_audio']) {
					@unlink(UPLOAD_PATH . '/' . $_POST['old_description_cn_audio']);
				}
			}
			if (!empty($description_en_audio)) {
				$data['description_en_audio'] = $description_en_audio;
				// 删除老文件
				if ($description_en_audio != $_POST['old_description_en_audio']) {
					@unlink(UPLOAD_PATH . '/' . $_POST['old_description_en_audio']);
				}
			}
			if (!empty($note_cn_audio)) {
				$data['note_cn_audio'] = $note_cn_audio;
				// 删除老文件
				if ($note_cn_audio != $_POST['old_note_cn_audio']) {
					@unlink(UPLOAD_PATH . '/' . $_POST['old_note_cn_audio']);
				}
			}
			if (!empty($note_en_audio)) {
				$data['note_en_audio'] = $note_en_audio;
				// 删除老文件
				if ($note_en_audio != $_POST['old_note_en_audio']) {
					@unlink(UPLOAD_PATH . '/' . $_POST['old_note_en_audio']);
				}
			}
			
			if ($this->scenedb->update($data, "sceneid=" . $_POST['sceneid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("scene", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		// 景区分类
		$scenetypedb = new model_scenetype();
		$scenetype_list = $scenetypedb->get_list(100, 0, " typeid,name ", '', "typeid ASC ");
		// 游玩主题
		$traveltopicdb = new model_traveltopic();
		$traveltopic_list = $traveltopicdb->get_list(100, 0, " typeid,name ", "", "typeid ASC ");
		
		$show_validator = 1;
		$show_editor = 1;
		$show_zone = 1;
		// $show_map = 1;
		
		include trig_mvc_template::view_file('sceneform');
	}

	public function delete() {
		$sceneid = $_GET['sceneid'];
		if (!empty($sceneid)) {
			if ($this->scenedb->delete($sceneid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("scene", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}