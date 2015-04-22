<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_note extends application_admin_base {
	private $notedb;

	function __construct() {
		parent::__construct();
		$this->notedb = new model_note();
	}

	function init() {
		$keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
		$startdate = empty($_GET['startdate']) ? '' : trim($_GET['startdate']);
		$enddate = empty($_GET['enddate']) ? '' : trim($_GET['enddate']);
		$notetypeid = empty($_GET['notetypeid']) ? '' : intval($_GET['notetypeid']);
		$where = " WHERE 1 ";
		if (!empty($notetypeid)) {
			$where .= " and notetypeid=" . $notetypeid;
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
		$count = $this->notedb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		// 获取分页后的数据
		$list = $this->notedb->get_list($p->perpage, $p->offset, " * ", $where, "dateline DESC ");
		
		$notetypedb = new model_notetype();
		$notetype_list = $notetypedb->get_list(100, 0, " notetypeid,name ", "", "dateline DESC ");
		$notetypes = array();
		foreach ($notetype_list as $k => $v) {
			$notetypes[$v['notetypeid']] = $v['name'];
		}
		$show_date_js = 1;
		include trig_mvc_template::view('note');
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
				'notetypeid' => $_POST['notetypeid'],
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'title' => $_POST['title'],
				'content' => $_POST['content'],
				'sendto' => $_POST['sendto'],
				'attachment' => $attachment,
				'attachment_name' => $attachment_name,
				'dateline' => $_POST['dateline'] 
			);
			if ($this->notedb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("note", "init"));
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
		$notetypedb = new model_notetype();
		$notetype_list = $notetypedb->get_list(100, 0, " notetypeid,name ", $where, "dateline DESC ");
		include trig_mvc_template::view('noteform');
	}
	
	public function edit() {
		$noteid = $_GET['noteid'];
		if (!empty($noteid)) {
			$value = $this->notedb->get_one($noteid);
		}
		if (!empty($_POST['action']) && !empty($_POST['noteid'])) {
			if (!empty($_FILES['attachment']['name'])) {
				
				$upfile = new trig_uploadfile();
				$attachment = $upfile->upload($_FILES['attachment']);
				$attachment_tempname = $_FILES['attachment']['name'];
				$attachmentnames = explode(".", $attachment_tempname);
				$attachment_name = $attachmentnames[0];
			}
			$data = array(
				'notetypeid' => $_POST['notetypeid'],
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
			if ($this->notedb->update($data, "noteid=" . $_POST['noteid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("note", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$usergroupdb = new model_usergroup();
		$usergroup_list = $usergroupdb->get_list(100, 0, " ugid,name ", "", "uid ASC ");
		foreach ($usergroup_list as $k => $v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
		$notetypedb = new model_notetype();
		$notetype_list = $notetypedb->get_list(100, 0, " notetypeid,name ", $where, "dateline DESC ");
		
		$show_validator = 1;
		$show_editor = 1;
		include trig_mvc_template::view('noteform');
	}
	
	public function delete() {
		$noteid = $_GET['noteid'];
		if (!empty($noteid)) {
			if ($this->notedb->delete($noteid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("note", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}