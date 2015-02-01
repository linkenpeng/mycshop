<?php
defined('SYS_IN') or exit('Access Denied.');
class comment extends controller {
    private $commentdb;
    function __construct() {
        parent::__construct();
        $this->commentdb = Base::load_model("comment_model");
    }
    function init() {
        $keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
        $startdate = empty($_GET['startdate']) ? '' : trim($_GET['startdate']);
        $enddate = empty($_GET['enddate']) ? '' : trim($_GET['enddate']);
        $comment_type = empty($_GET['comment_type']) ? '' : intval($_GET['comment_type']);
		$username = empty($_GET['username']) ? '' : trim($_GET['username']);
		
        $where = " WHERE 1 ";
        if (!empty($comment_type)) {
            $where .= " and comment_type=".$comment_type;
        }
        if (!empty($keyword)) {
            $where .= " and `commented_title` like '%".$keyword."%' ";
        }
		if (!empty($username)) {
            $where .= " and `username` like '%".$username."%' ";
        }
        if (!empty($startdate)) {
            $where .= " and dateline>".strtotime($startdate);
        }
        if (!empty($enddate)) {
            $where .= " and dateline<".(strtotime($enddate)+24*3600-1);
        }
        //分页       
        
        $count = $this->commentdb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->commentdb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"dateline DESC ");
        $commenttypedb = Base::load_model("commenttype_model");
        $show_date_js = 1;
        include admin_template('comment');
    }
    /**
     * 添加
     * 
     * @author Myron
     * 2011-5-27 上午11:57:59
     */
    public function add() {
        if (!empty($_POST['action'])) {
            if (empty($_POST['title'])) {
                ShowMsg("标题不能为空!",-1);
            }
            $_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
           
            if (!empty($_FILES['attachment']['name'])) {
                Base::load_sys_class("uploadfile",'',0);
                $upfile = new uploadfile();
                $attachment = $upfile->upload($_FILES['attachment']);
                $attachment_tempname=$_FILES['attachment']['name'];
                $attachmentnames=explode(".",$attachment_tempname);
                $attachment_name = $attachmentnames[0];
            }
            $data = array(
                'comment_type'=>$_POST['comment_type'],
                'uid'=>$_SESSION['admin_uid'],
                'username'=>$_SESSION['admin_username'],
                'title'=>$_POST['title'],
                'content'=>$_POST['content'],
                'sendto'=>$_POST['sendto'],
            	'attachment'=>$attachment,
            	'attachment_name'=>$attachment_name,
                'dateline'=>$_POST['dateline']
            );
            if ($this->commentdb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("comment","init"));
            } else {
                ShowMsg(lang('message','insert_failure'),-1);
            }
        }
		$usergroupdb = Base::load_model("usergroup_model");
		$usergroup_list = $usergroupdb->get_list(100,0," ugid,name ","","uid ASC ");
		foreach ($usergroup_list as $k=>$v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
        $show_validator = 1;
        $show_editor = 1;
        $commenttypedb = Base::load_model("commenttype_model");
        $commenttype_list = $commenttypedb->get_list(100,0," comment_type,name ",$where,"dateline DESC ");
        include admin_template('commentform');
    }
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $commentid = $_GET['commentid'];
        if (!empty($commentid)) {
            $value = $this->commentdb->get_one($commentid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['commentid'])) {
            if (!empty($_FILES['attachment']['name'])) {
                Base::load_sys_class("uploadfile",'',0);
                $upfile = new uploadfile();
                $attachment = $upfile->upload($_FILES['attachment']);
                $attachment_tempname=$_FILES['attachment']['name'];
                $attachmentnames=explode(".",$attachment_tempname);
                $attachment_name = $attachmentnames[0];
            }
            $data = array(
                'comment_type'=>$_POST['comment_type'],
                'title'=>$_POST['title'],
                'content'=>$_POST['content'],
                'sendto'=>$_POST['sendto']
            );
            if (!empty($attachment)) {
                $data['attachment'] = $attachment;
                $data['attachment_name'] = $attachment_name;
                //删除老图片
                if($image!=$_POST['oldattachment']) {
                    @unlink(UPLOAD_PATH.'/'.$_POST['oldattachment']);
                }
            }
            if ($this->commentdb->update($data,"commentid=".$_POST['commentid'])) {
                ShowMsg(lang('message','update_success'),get_uri("comment","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
		$usergroupdb = Base::load_model("usergroup_model");
		$usergroup_list = $usergroupdb->get_list(100,0," ugid,name ","","uid ASC ");
		foreach ($usergroup_list as $k=>$v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
        $commenttypedb = Base::load_model("commenttype_model");
        $commenttype_list = $commenttypedb->get_list(100,0," comment_type,name ",$where,"dateline DESC ");
        //print_r($commenttype_list);
        $show_validator = 1;
        $show_editor = 1;
        include admin_template('commentform');
    }
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $commentid = $_GET['commentid'];
        if (!empty($commentid)) {
            if ($this->commentdb->delete($commentid)) {
                ShowMsg(lang('message','delete_success'),get_uri("comment","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
}
?>