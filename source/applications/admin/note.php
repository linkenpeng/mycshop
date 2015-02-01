<?php
defined('SYS_IN') or exit('Access Denied.');
class note extends controller {
    private $notedb;
    function __construct() {
        parent::__construct();
        $this->notedb = Base::load_model("note_model");
    }
    function init() {
        $keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
        $startdate = empty($_GET['startdate']) ? '' : trim($_GET['startdate']);
        $enddate = empty($_GET['enddate']) ? '' : trim($_GET['enddate']);
        $notetypeid = empty($_GET['notetypeid']) ? '' : intval($_GET['notetypeid']);
        $where = " WHERE 1 ";
        if (!empty($notetypeid)) {
            $where .= " and notetypeid=".$notetypeid;
        }
        if (!empty($keyword)) {
            $where .= " and `title` like '%".$keyword."%' ";
        }
        if (!empty($startdate)) {
            $where .= " and dateline>".strtotime($startdate);
        }
        if (!empty($enddate)) {
            $where .= " and dateline<".(strtotime($enddate)+24*3600-1);
        }
        //分页       
        
        $count = $this->notedb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->notedb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"dateline DESC ");
        $notetypedb = Base::load_model("notetype_model");
        $notetype_list = $notetypedb->get_list(100,0," notetypeid,name ","","dateline DESC ");
        $notetypes = array();
        foreach($notetype_list as $k=>$v) {
            $notetypes[$v['notetypeid']] = $v['name'];
        }
        $show_date_js = 1;
        include admin_template('note');
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
                'notetypeid'=>$_POST['notetypeid'],
                'uid'=>$_SESSION['admin_uid'],
                'username'=>$_SESSION['admin_username'],
                'title'=>$_POST['title'],
                'content'=>$_POST['content'],
                'sendto'=>$_POST['sendto'],
            	'attachment'=>$attachment,
            	'attachment_name'=>$attachment_name,
                'dateline'=>$_POST['dateline']
            );
            if ($this->notedb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("note","init"));
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
        $notetypedb = Base::load_model("notetype_model");
        $notetype_list = $notetypedb->get_list(100,0," notetypeid,name ",$where,"dateline DESC ");
        include admin_template('noteform');
    }
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $noteid = $_GET['noteid'];
        if (!empty($noteid)) {
            $value = $this->notedb->get_one($noteid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['noteid'])) {
            if (!empty($_FILES['attachment']['name'])) {
                Base::load_sys_class("uploadfile",'',0);
                $upfile = new uploadfile();
                $attachment = $upfile->upload($_FILES['attachment']);
                $attachment_tempname=$_FILES['attachment']['name'];
                $attachmentnames=explode(".",$attachment_tempname);
                $attachment_name = $attachmentnames[0];
            }
            $data = array(
                'notetypeid'=>$_POST['notetypeid'],
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
            if ($this->notedb->update($data,"noteid=".$_POST['noteid'])) {
                ShowMsg(lang('message','update_success'),get_uri("note","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
		$usergroupdb = Base::load_model("usergroup_model");
		$usergroup_list = $usergroupdb->get_list(100,0," ugid,name ","","uid ASC ");
		foreach ($usergroup_list as $k=>$v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
        $notetypedb = Base::load_model("notetype_model");
        $notetype_list = $notetypedb->get_list(100,0," notetypeid,name ",$where,"dateline DESC ");
        //print_r($notetype_list);
        $show_validator = 1;
        $show_editor = 1;
        include admin_template('noteform');
    }
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $noteid = $_GET['noteid'];
        if (!empty($noteid)) {
            if ($this->notedb->delete($noteid)) {
                ShowMsg(lang('message','delete_success'),get_uri("note","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
}
?>