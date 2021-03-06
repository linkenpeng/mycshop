<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_comment extends application_admin_base {
	private $commentdb;

	function __construct() {
		parent::__construct();
		$this->commentdb = new model_comment();
	}

	function init() {
		$keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
		$startdate = empty($_GET['startdate']) ? '' : trim($_GET['startdate']);
		$enddate = empty($_GET['enddate']) ? '' : trim($_GET['enddate']);
		$comment_type = empty($_GET['comment_type']) ? '' : intval($_GET['comment_type']);
		$username = empty($_GET['username']) ? '' : trim($_GET['username']);
		
		$where = " WHERE 1 ";
		if (!empty($comment_type)) {
			$where .= " and comment_type=" . $comment_type;
		}
		if (!empty($keyword)) {
			$where .= " and `commented_title` like '%" . $keyword . "%' ";
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
		$count = $this->commentdb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 15));
		// 获取分页后的数据
		$list = $this->commentdb->get_list($p->perpage, $p->offset, " * ", $where, "dateline DESC ");
		$show_date_js = 1;
		include trig_mvc_template::view_file('comment');
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
				'comment_type' => $_POST['comment_type'],
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'title' => $_POST['title'],
				'content' => $_POST['content'],
				'sendto' => $_POST['sendto'],
				'attachment' => $attachment,
				'attachment_name' => $attachment_name,
				'dateline' => $_POST['dateline'] 
			);
			if ($this->commentdb->insert($data)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("comment", "init"));
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
		$commenttypedb = new model_commenttype();
		$commenttype_list = $commenttypedb->get_list(100, 0, " comment_type,name ", $where, "dateline DESC ");
		include trig_mvc_template::view_file('commentform');
	}
	
	public function edit() {
		$commentid = $_GET['commentid'];
		if (!empty($commentid)) {
			$value = $this->commentdb->get_one($commentid);
		}
		if (!empty($_POST['action']) && !empty($_POST['commentid'])) {
			if (!empty($_FILES['attachment']['name'])) {
				
				$upfile = new trig_uploadfile();
				$attachment = $upfile->upload($_FILES['attachment']);
				$attachment_tempname = $_FILES['attachment']['name'];
				$attachmentnames = explode(".", $attachment_tempname);
				$attachment_name = $attachmentnames[0];
			}
			$data = array(
				'comment_type' => $_POST['comment_type'],
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
			if ($this->commentdb->update($data, "commentid=" . $_POST['commentid'])) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_success'), trig_mvc_route::get_uri("comment", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'update_failure'), -1);
			}
		}
		$usergroupdb = new model_usergroup();
		$usergroup_list = $usergroupdb->get_list(100, 0, " ugid,name ", "", "uid ASC ");
		foreach ($usergroup_list as $k => $v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
		$commenttypedb = new model_commenttype();
		$commenttype_list = $commenttypedb->get_list(100, 0, " comment_type,name ", $where, "dateline DESC ");
		$show_validator = 1;
		$show_editor = 1;
		include trig_mvc_template::view_file('commentform');
	}
	
	public function delete() {
		$commentid = $_GET['commentid'];
		if (!empty($commentid)) {
			if ($this->commentdb->delete($commentid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("comment", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
}