<?php
defined('SYS_IN') or exit('Access Denied.');
class signin extends controller {
    private $signindb;
    function __construct() {
        parent::__construct();
        $this->signindb = Base::load_model("signin_model");
    }
    function init() {
        $keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
        $startdate = empty($_GET['startdate']) ? '' : trim($_GET['startdate']);
        $enddate = empty($_GET['enddate']) ? '' : trim($_GET['enddate']);
        $signintypeid = empty($_GET['signintypeid']) ? '' : intval($_GET['signintypeid']);
		$username = empty($_GET['username']) ? '' : trim($_GET['username']);
		
        $where = " WHERE 1 ";
        if (!empty($signintypeid)) {
            $where .= " and signintypeid=".$signintypeid;
        }
        if (!empty($keyword)) {
            $where .= " and `title` like '%".$keyword."%' ";
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
        
        $count = $this->signindb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->signindb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"dateline DESC ");
        $signintypedb = Base::load_model("signintype_model");
        $show_date_js = 1;
        include admin_template('signin');
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
                Base::load_sys_class("upfile",'',0);
                $upfile = new upfile();
                $attachment = $upfile->upload($_FILES['attachment']);
                $attachment_tempname=$_FILES['attachment']['name'];
                $attachmentnames=explode(".",$attachment_tempname);
                $attachment_name = $attachmentnames[0];
            }
            $data = array(
                'signintypeid'=>$_POST['signintypeid'],
                'uid'=>$_SESSION['admin_uid'],
                'username'=>$_SESSION['admin_username'],
                'title'=>$_POST['title'],
                'content'=>$_POST['content'],
                'sendto'=>$_POST['sendto'],
            	'attachment'=>$attachment,
            	'attachment_name'=>$attachment_name,
                'dateline'=>$_POST['dateline']
            );
            if ($this->signindb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("signin","init"));
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
        $signintypedb = Base::load_model("signintype_model");
        $signintype_list = $signintypedb->get_list(100,0," signintypeid,name ",$where,"dateline DESC ");
        include admin_template('signinform');
    }
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $signinid = $_GET['signinid'];
        if (!empty($signinid)) {
            $value = $this->signindb->get_one($signinid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['signinid'])) {
            if (!empty($_FILES['attachment']['name'])) {
                Base::load_sys_class("upfile",'',0);
                $upfile = new upfile();
                $attachment = $upfile->upload($_FILES['attachment']);
                $attachment_tempname=$_FILES['attachment']['name'];
                $attachmentnames=explode(".",$attachment_tempname);
                $attachment_name = $attachmentnames[0];
            }
            $data = array(
                'signintypeid'=>$_POST['signintypeid'],
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
            if ($this->signindb->update($data,"signinid=".$_POST['signinid'])) {
                ShowMsg(lang('message','update_success'),get_uri("signin","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
		$usergroupdb = Base::load_model("usergroup_model");
		$usergroup_list = $usergroupdb->get_list(100,0," ugid,name ","","uid ASC ");
		foreach ($usergroup_list as $k=>$v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
        $signintypedb = Base::load_model("signintype_model");
        $signintype_list = $signintypedb->get_list(100,0," signintypeid,name ",$where,"dateline DESC ");
        //print_r($signintype_list);
        $show_validator = 1;
        $show_editor = 1;
        include admin_template('signinform');
    }
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $signinid = $_GET['signinid'];
        if (!empty($signinid)) {
            if ($this->signindb->delete($signinid)) {
                ShowMsg(lang('message','delete_success'),get_uri("signin","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
}
?>