<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_signin extends application_base {
	private $signindb;

	function __construct() {
		parent::__construct();
		$this->signindb = new model_signin();
	}

	function init() {
		$keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
		$startdate = empty($_GET['startdate']) ? '' : trim($_GET['startdate']);
		$enddate = empty($_GET['enddate']) ? '' : trim($_GET['enddate']);
		$signintypeid = empty($_GET['signintypeid']) ? '' : intval($_GET['signintypeid']);
		$username = empty($_GET['username']) ? '' : trim($_GET['username']);
		
		$where = " WHERE 1 ";
		if (!empty($signintypeid)) {
			$where .= " and signintypeid=" . $signintypeid;
		}
		if (!empty($keyword)) {
			$where .= " and `title` like '%" . $keyword . "%' ";
		}
		if (!empty($username)) {
			$where .= " and `username` like '%" . $username . "%' ";
		}
		if (!empty($startdate)) {
			$where .= " and dateline>" . strtotime($startdate);
		}
		if (!empty($enddate)) {
			$where .= " and dateline<" . (strtotime($enddate) + 24 * 3600 - 1);
		}
		// 分页
		$count = $this->signindb->get_count($where);
		$pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
		$nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$setarr = array(
			'total' => $count,
			'perpage' => $pagesize 
		);
		$p = new trig_page($setarr);
		// 获取分页后的数据
		$list = $this->signindb->get_list($pagesize, $pagesize * ($nowpage - 1), " * ", $where, "dateline DESC ");
		$show_date_js = 1;
		include trig_func_common::admin_template('signin');
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
				'signintypeid' => $_POST['signintypeid'],
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'title' => $_POST['title'],
				'content' => $_POST['content'],
				'sendto' => $_POST['sendto'],
				'attachment' => $attachment,
				'attachment_name' => $attachment_name,
				'dateline' => $_POST['dateline'] 
			);
			if ($this->signindb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_func_common::get_uri("signin", "init"));
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
		$signintypedb = new model_signintype();
		$signintype_list = $signintypedb->get_list(100, 0, " signintypeid,name ", $where, "dateline DESC ");
		include trig_func_common::admin_template('signinform');
	}

	public function edit() {
		$signinid = $_GET['signinid'];
		if (!empty($signinid)) {
			$value = $this->signindb->get_one($signinid);
		}
		if (!empty($_POST['action']) && !empty($_POST['signinid'])) {
			if (!empty($_FILES['attachment']['name'])) {
				
				$upfile = new trig_uploadfile();
				$attachment = $upfile->upload($_FILES['attachment']);
				$attachment_tempname = $_FILES['attachment']['name'];
				$attachmentnames = explode(".", $attachment_tempname);
				$attachment_name = $attachmentnames[0];
			}
			$data = array(
				'signintypeid' => $_POST['signintypeid'],
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
			if ($this->signindb->update($data, "signinid=" . $_POST['signinid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_func_common::get_uri("signin", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$usergroupdb = new model_usergroup();
		$usergroup_list = $usergroupdb->get_list(100, 0, " ugid,name ", "", "uid ASC ");
		foreach ($usergroup_list as $k => $v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
		$signintypedb = new model_signintype();
		$signintype_list = $signintypedb->get_list(100, 0, " signintypeid,name ", $where, "dateline DESC ");
		$show_validator = 1;
		$show_editor = 1;
		include trig_func_common::admin_template('signinform');
	}

	public function delete() {
		$signinid = $_GET['signinid'];
		if (!empty($signinid)) {
			if ($this->signindb->delete($signinid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_func_common::get_uri("signin", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}