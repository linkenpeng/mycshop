<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_talk extends application_base {
	private $talkdb;

	function __construct() {
		parent::__construct();
		$this->talkdb = new model_talk();
	}

	function init() {
		$keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
		$startdate = empty($_GET['startdate']) ? '' : trim($_GET['startdate']);
		$enddate = empty($_GET['enddate']) ? '' : trim($_GET['enddate']);
		$talktypeid = empty($_GET['talktypeid']) ? '' : intval($_GET['talktypeid']);
		$where = " WHERE 1 ";
		if (!empty($talktypeid)) {
			$where .= " and talktypeid=" . $talktypeid;
		}
		if (!empty($keyword)) {
			$where .= " and `title` like '%" . $keyword . "%' ";
		}
		if (!empty($startdate)) {
			$where .= " and dateline>" . strtotime($startdate);
		}
		if (!empty($enddate)) {
			$where .= " and dateline<" . (strtotime($enddate) + 24 * 3600 - 1);
		}
		// 分页		
		$count = $this->talkdb->get_count($where);
		$pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
		$nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$setarr = array(
			'total' => $count,
			'perpage' => $pagesize 
		);
		$p = new trig_page($setarr);
		// 获取分页后的数据
		$list = $this->talkdb->get_list($pagesize, $pagesize * ($nowpage - 1), " * ", $where, "dateline DESC ");
		$show_date_js = 1;
		include trig_func_common::admin_template('talk');
	}
	
	public function add() {
		if (!empty($_POST['action'])) {
			if (empty($_POST['title'])) {
				trig_func_common::ShowMsg("标题不能为空!", -1);
			}
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			
			if (!empty($_FILES['attachment']['name'])) {
				
				$upfile = new trig_uploadfile();
				$attachment = $upfile->upload($_FILES['attachment']);
				$attachment_tempname = $_FILES['attachment']['name'];
				$attachmentnames = explode(".", $attachment_tempname);
				$attachment_name = $attachmentnames[0];
			}
			$data = array(
				'talktypeid' => $_POST['talktypeid'],
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'title' => $_POST['title'],
				'content' => $_POST['content'],
				'sendto' => $_POST['sendto'],
				'attachment' => $attachment,
				'attachment_name' => $attachment_name,
				'dateline' => $_POST['dateline'] 
			);
			if ($this->talkdb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_func_common::get_uri("talk", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_failure'), -1);
			}
		}
		$usergroupdb = new model_usergroup();
		$usergroup_list = $usergroupdb->get_list(100, 0, " ugid,name ", "", "uid ASC ");
		foreach ($usergroup_list as $k => $v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
		$show_validator = 1;
		$show_editor = 1;
		include trig_func_common::admin_template('talkform');
	}
	
	public function edit() {
		$talkid = $_GET['talkid'];
		if (!empty($talkid)) {
			$value = $this->talkdb->get_one($talkid);
		}
		if (!empty($_POST['action']) && !empty($_POST['talkid'])) {
			if (!empty($_FILES['attachment']['name'])) {
				
				$upfile = new trig_uploadfile();
				$attachment = $upfile->upload($_FILES['attachment']);
				$attachment_tempname = $_FILES['attachment']['name'];
				$attachmentnames = explode(".", $attachment_tempname);
				$attachment_name = $attachmentnames[0];
			}
			$data = array(
				'talktypeid' => $_POST['talktypeid'],
				'title' => $_POST['title'],
				'content' => $_POST['content'],
				'sendto' => $_POST['sendto'] 
			);
			if (!empty($attachment)) {
				$data['attachment'] = $attachment;
				$data['attachment_name'] = $attachment_name;
				// 删除老图片
				if ($image != $_POST['oldattachment']) {
					@unlink(UPLOAD_PATH . '/' . $_POST['oldattachment']);
				}
			}
			if ($this->talkdb->update($data, "talkid=" . $_POST['talkid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_func_common::get_uri("talk", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$usergroupdb = new model_usergroup();
		$usergroup_list = $usergroupdb->get_list(100, 0, " ugid,name ", "", "uid ASC ");
		foreach ($usergroup_list as $k => $v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
		
		$show_validator = 1;
		$show_editor = 1;
		include trig_func_common::admin_template('talkform');
	}
	
	public function delete() {
		$talkid = $_GET['talkid'];
		if (!empty($talkid)) {
			if ($this->talkdb->delete($talkid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_func_common::get_uri("talk", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
	
	public function show() {
		$talkid = $_GET['talkid'];
		$value = $this->talkdb->get_one($talkid);
		$where = " WHERE 1 ";
		$talkreplydb = new model_talkreply();
		// 获取分页后的数据
		$talkreplylist = $talkreplydb->get_list(1000, 0, " * ", $where, "dateline DESC ");
		$show_validator = 1;
		include trig_func_common::admin_template('talkshow');
	}
}