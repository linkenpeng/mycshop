<?php
defined('SYS_IN') or exit('Access Denied.');
class user {
    private $userdb;
    function __construct() {
        //判断是否登录
        Base::load_model("login_model")->is_login();
        $this->userdb = Base::load_model("user_model");
    }
    function init() {
        $usertype = empty($_GET['usertype']) ? "" : intval($_GET['usertype']);
		$keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
        $where = " WHERE 1 ";
        if (!empty($usertype)) {
            $where .= " and usertype=".$usertype;
        }
        if (!empty($keyword)) {
            $where .= " and `username` like '%".$keyword."%' ";
        }
        //分页       
        Base::load_sys_class("page",'',0);
        $count = $this->userdb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->userdb->get_list($pagesize,$pagesize*($nowpage-1),"*",$where,"uid DESC ");
		$usergroupdb = Base::load_model("usergroup_model");
		$usergroup_list = $usergroupdb->get_list(100,0," ugid,name ","","uid ASC ");
		foreach ($usergroup_list as $k=>$v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
        include admin_template('user');
    }
	
	function ajax_userlist() {
        $usertype = empty($_GET['usertype']) ? "" : intval($_GET['usertype']);
		$keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
        $where = " WHERE 1 ";
        if (!empty($usertype)) {
            $where .= " and usertype=".$usertype;
        }
        if (!empty($keyword)) {
            $where .= " and `username` like '%".$keyword."%' ";
        }
        //分页       
        Base::load_sys_class("page",'',0);
        $count = $this->userdb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->userdb->get_list($pagesize,$pagesize*($nowpage-1),"*",$where,"uid DESC ");
		$usergroupdb = Base::load_model("usergroup_model");
		$usergroup_list = $usergroupdb->get_list(100,0," ugid,name ","","uid ASC ");
		foreach ($usergroup_list as $k=>$v) {
			$ugroup_list[$v['ugid']] = $v['name'];
		}
        include admin_template('ajax_userlist');
    }
	/**
     * 添加
     * 
     * @author Myron
     * 2011-5-27 上午11:57:59
     */
    public function add() {
    	$value = array();
        if (!empty($_POST['action'])) {
            if (empty($_POST['username']) || empty($_POST['password'])) {
                ShowMsg("用户名和密码不能为空!",-1);
            }
            $_POST['regtime'] = empty($_POST['regtime']) ? time() : strtotime(trim($_POST['regtime']));
            $data = array(
                'username'=>$_POST['username'],
				'password'=>password($_POST['password']),
				'realname'=>$_POST['realname'],
				'usertype'=>$_POST['usertype'],
                'province'=>$_POST['province'],
				'city'=>$_POST['city'],
                'country'=>$_POST['country'],
				'address'=>$_POST['address'],
				'regtime'=>$_POST['regtime'],
                'content'=>$_POST['content']
            );
            if ($this->userdb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("user","init"));
            } else {
                ShowMsg(lang('message','insert_failure'),-1);
            }
        }
        $show_validator = 1;
		$show_zone = 1;
		
		$where = '';
		$usergroupdb = Base::load_model("usergroup_model");
		$ugroup_list = $usergroupdb->get_list(100,0," ugid,name ",$where,"uid ASC ");
        include admin_template('userform');
    }
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $uid = $_GET['uid'];
        if (!empty($uid)) {
            $value = $this->userdb->get_one($uid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['uid'])) {
            $_POST['regtime'] = empty($_POST['regtime']) ? time() : strtotime(trim($_POST['regtime']));
			$data = array(
                'username'=>$_POST['username'],
				'realname'=>$_POST['realname'],
				'usertype'=>$_POST['usertype'],
                'province'=>$_POST['province'],
				'city'=>$_POST['city'],
                'country'=>$_POST['country'],
				'address'=>$_POST['address'],
				'regtime'=>$_POST['regtime'],
                'content'=>$_POST['content']
            );
			if (!empty($_POST['password'])) {
				$data['password'] = password($_POST['password']);
			}
            if ($this->userdb->update($data,"uid=".$_POST['uid'])) {
                ShowMsg(lang('message','update_success'),get_uri("user","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
        
        $where = '';
		$usergroupdb = Base::load_model("usergroup_model");
		$ugroup_list = $usergroupdb->get_list(100,0," ugid,name ",$where,"uid ASC ");
		$show_zone = 1;
        $show_validator = 1;
        include admin_template('userform');
    }
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $uid = $_GET['uid'];
        if (!empty($uid)&&$uid!=1) {
            if ($this->userdb->delete($uid)) {
                ShowMsg(lang('message','delete_success'),get_uri("user","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
    /*
	 * 
	 * 修改密码
	 */
    function editpass() {
        $user_info = $this->userdb->get_user_info($_SESSION['admin_uid']);
        include admin_template('changepass');
    }
    /*
	 * 保存修改密码
	 */
    function save_admin_pass() {
        $oldpassword = $_POST['oldpassword'];
        $password = $_POST['password'];
        $password1 = $_POST['password1'];
        $admin_uid = $_SESSION['admin_uid'];
        
        if (!empty($admin_uid)) {
            $user_info = $this->userdb->get_user_info($admin_uid);
            if ($password!=$password1) {
                ShowMsg(lang('message','tow_password_is_not_match'),-1);
            }
            if (password($oldpassword)!=$user_info['password']) {
                ShowMsg(lang('message','old_password_is_wrong'),-1);
            } else {
                if ($this->userdb->update_password($admin_uid,$password)) {
                    $session = Base::load_model("session_model");
                    $session->my_session_start();
                    $session->delete_session($_SESSION['admin_uid']);
                    $session->clearcookie('auth');
                    ShowMsg(lang('message','update_password_success'),ROUTE."?".M."=admin&".C."=login");
                } else {
                    ShowMsg(lang('message','update_password_failure'),-1);
                }
            }
        } else {
            ShowMsg(lang('message','no_user'),-1);
        }
    }
}
?>