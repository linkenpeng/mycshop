<?php
defined('SYS_IN') or exit('Access Denied.');
class talk extends controller {
    private $talkdb;
    function __construct() {
       parent::__construct();
        $this->talkdb = Base::load_model("talk_model");
    }
    function init() {
        $keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
        $startdate = empty($_GET['startdate']) ? '' : trim($_GET['startdate']);
        $enddate = empty($_GET['enddate']) ? '' : trim($_GET['enddate']);
        $talktypeid = empty($_GET['talktypeid']) ? '' : intval($_GET['talktypeid']);
        $where = " WHERE 1 ";
        if (!empty($talktypeid)) {
            $where .= " and talktypeid=".$talktypeid;
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
        
        $count = $this->talkdb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->talkdb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"dateline DESC ");
        $show_date_js = 1;
        include admin_template('talk');
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
                'talktypeid'=>$_POST['talktypeid'],
                'uid'=>$_SESSION['admin_uid'],
                'username'=>$_SESSION['admin_username'],
                'title'=>$_POST['title'],
                'content'=>$_POST['content'],
                'sendto'=>$_POST['sendto'],
            	'attachment'=>$attachment,
            	'attachment_name'=>$attachment_name,
                'dateline'=>$_POST['dateline']
            );
            if ($this->talkdb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("talk","init"));
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
        include admin_template('talkform');
    }
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $talkid = $_GET['talkid'];
        if (!empty($talkid)) {
            $value = $this->talkdb->get_one($talkid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['talkid'])) {
            if (!empty($_FILES['attachment']['name'])) {
                Base::load_sys_class("uploadfile",'',0);
                $upfile = new uploadfile();
                $attachment = $upfile->upload($_FILES['attachment']);
                $attachment_tempname=$_FILES['attachment']['name'];
                $attachmentnames=explode(".",$attachment_tempname);
                $attachment_name = $attachmentnames[0];
            }
            $data = array(
                'talktypeid'=>$_POST['talktypeid'],
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
            if ($this->talkdb->update($data,"talkid=".$_POST['talkid'])) {
                ShowMsg(lang('message','update_success'),get_uri("talk","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
		$usergroupdb = Base::load_model("usergroup_model");
		$usergroup_list = $usergroupdb->get_list(100,0," ugid,name ","","uid ASC ");
		foreach ($usergroup_list as $k=>$v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
		/*
        $talktypedb = Base::load_model("talktype_model");
        $talktype_list = $talktypedb->get_list(100,0," talktypeid,name ",$where,"dateline DESC ");
		*/
        //print_r($talktype_list);
        $show_validator = 1;
        $show_editor = 1;
        include admin_template('talkform');
    }
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $talkid = $_GET['talkid'];
        if (!empty($talkid)) {
            if ($this->talkdb->delete($talkid)) {
                ShowMsg(lang('message','delete_success'),get_uri("talk","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
	/**
     * 查看
     * 
     * @author Myron
     * 2011-08-07 上午11:36:34
     */
    public function show() {
        $talkid = $_GET['talkid'];
        $value = $this->talkdb->get_one($talkid);
		$where = " WHERE 1 ";
		$talkreplydb = Base::load_model("talkreply_model");
        //获取分页后的数据
        $talkreplylist = $talkreplydb->get_list(1000,0," * ",$where,"dateline DESC ");
        $show_validator = 1;
		include admin_template('talkshow');
    }
}
?>