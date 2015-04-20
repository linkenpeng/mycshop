<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_relic extends application_base {
	private $relicdb;

	function __construct() {
		parent::__construct();
		$this->relicdb = new model_relic();
	}

	function init() {
		$where = " WHERE 1 ";
		$relicname = empty($_GET['relicname']) ? '' : trim($_GET['relicname']);
		$scenespotname = empty($_GET['scenespotname']) ? '' : trim($_GET['scenespotname']);
		$relicnum = empty($_GET['relicnum']) ? '' : trim($_GET['relicnum']);
		
		$where = " WHERE 1 ";
		if (!empty($relicname)) {
			$where .= " and rel.`relicname` like '%" . $relicname . "%' ";
		}
		if (!empty($scenespotname)) {
			$where .= " and s.`scenespotname` like '%" . $scenespotname . "%' ";
		}
		if (!empty($relicnum)) {
			$where .= " and rel.`relicnum`='" . $relicnum . "' ";
		}
		// 分页		
		$count = $this->relicdb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		$field = "rel.relicid,rel.scenespotid,rel.relicname,rel.relic_enname,rel.relicnum,rel.level,rel.image,s.scenespotname";
		// 获取分页后的数据
		$list = $this->relicdb->get_list($p->perpage, $p->offset, $field, $where, " rel.dateline DESC ");
		
		$levels = array(
			1 => '一级',
			2 => '二级',
			3 => '三级' 
		);
		include trig_mvc_template::admin_template('relic');
	}
	
	public function add() {
		if (!empty($_POST['action'])) {
			if (empty($_POST['relicname'])) {
				trig_func_common::ShowMsg("文物名称不能为空!", -1);
			}
			if (empty($_POST['scenespotid'])) {
				trig_func_common::ShowMsg("景点id不能为空!", -1);
			}
			if ($this->relicdb->exists_relicnum(trim($_POST['relicnum']))) {
				trig_func_common::ShowMsg("该文物编号已经存在!", -1);
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
				'scenespotid' => $_POST['scenespotid'],
				'relicname' => $_POST['relicname'],
				'relic_enname' => $_POST['relic_enname'],
				'level' => $_POST['level'],
				'image' => $image,
				'relicnum' => $_POST['relicnum'],
				'description' => $_POST['description'],
				'cn_audio' => $cn_audio,
				'en_audio' => $en_audio,
				'dateline' => $_POST['dateline'] 
			);
			if ($this->relicdb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("relic", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		
		// 得到景点信息
		$scenespotid = empty($_GET['scenespotid']) ? '' : intval($_GET['scenespotid']);
		$scenespotdb = new model_scenespot();
		$value = $scenespotdb->get_one($scenespotid, "scenespotid,scenespotname");
		
		$value['relicnum'] = $this->relicdb->get_auto_relicnum();
		
		$show_validator = 1;
		
		include trig_mvc_template::admin_template('relicform');
	}
	
	public function edit() {
		$relicid = $_GET['relicid'];
		if (!empty($relicid)) {
			$value = $this->relicdb->get_one($relicid);
		}
		if (!empty($_POST['action']) && !empty($_POST['relicid'])) {
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
				'scenespotid' => $_POST['scenespotid'],
				'relicname' => $_POST['relicname'],
				'relic_enname' => $_POST['relic_enname'],
				'relicnum' => $_POST['relicnum'],
				'level' => $_POST['level'],
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
			if ($this->relicdb->update($data, "relicid=" . $_POST['relicid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("relic", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		
		$scenespotdb = new model_scenespot();
		$field = "sp.scenespotid,sp.scenespotname";
		$scenespot_list = $scenespotdb->get_list(10000, 0, $field, '', " sp.dateline DESC ");
		
		$show_validator = 1;
		include trig_mvc_template::admin_template('relicform');
	}
	
	public function delete() {
		$relicid = $_GET['relicid'];
		if (!empty($relicid)) {
			if ($this->relicdb->delete($relicid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("relic", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}