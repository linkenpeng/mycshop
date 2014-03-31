<?php
defined('SYS_IN') or exit('Access Denied.');
class talkreply {
    private $talkreplydb;
    function __construct() {
        //判断是否登录
        Base::load_model("login_model")->is_login();
        $this->talkreplydb = Base::load_model("talkreply_model");
    }
    function init() {
        $where = " WHERE 1 ";
        //分页       
        Base::load_sys_class("page",'',0);
        $count = $this->talkreplydb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->talkreplydb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"dateline DESC ");
        
        //include admin_template('talkreply');
    }
    /**
     * 添加
     * 
     * @author Myron
     * 2011-5-27 上午11:57:59
     */
    public function add() {
        if (!empty($_POST['action'])) {
            if (empty($_POST['content'])) {
                ShowMsg("内容不能为空!",-1);
            }
            $_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
            $data = array(
                'talkid'=>$_POST['talkid'],
                'uid'=>$_SESSION['admin_uid'],
                'username'=>$_SESSION['admin_username'],
                'content'=>$_POST['content'],
                'dateline'=>$_POST['dateline']
            );
            if ($this->talkreplydb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("talk","show","admin","talkid=".$_POST['talkid']));
            } else {
                ShowMsg(lang('message','insert_failure'),-1);
            }
        }
        $show_validator = 1;
        //include admin_template('talkreplyform');
    }
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $talkreplyid = $_GET['talkreplyid'];
        if (!empty($talkreplyid)) {
            $value = $this->talkreplydb->get_one($talkreplyid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['talkreplyid'])) {
            $data = array(
                'content'=>$_POST['content']
            );
            if ($this->talkreplydb->update($data,"talkreplyid=".$_POST['talkreplyid'])) {
                ShowMsg(lang('message','update_success'),get_uri("talkreply","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
        $show_validator = 1;
        //include admin_template('talkreplyform');
    }
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $talkreplyid = $_GET['talkreplyid'];
		$talkid = $_GET['talkid'];
        if (!empty($talkreplyid) && !empty($talkid)) {
            if ($this->talkreplydb->delete($talkreplyid)) {
                ShowMsg(lang('message','delete_success'),get_uri("talk","show","admin","talkid=".$_GET['talkid']));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
}
?>