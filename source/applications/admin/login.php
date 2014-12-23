<?php
defined('SYS_IN') or exit('Access Denied.');
class login extends controller {
    function __construct() {

    }
	
    function init() {
        if (!empty($_SESSION['admin_uid'])&&($_SESSION['admin_usertype']==ADMIN_USER_TYPE)) {
            header('location:'.get_uri("index","init"));
        } else {
            include admin_template('login');
        }
    }
	
    /*
	 * 显示验证码
	 */
    function showcode() {
        Base::load_sys_class("checkcode")->showcode();
    }
	
    /*
	 * 验证后台管理员登录
	 */
    function check_user_login() {
        $username = trim(getPost('username'));
        $password = trim(getPost('password'));
        $usertype = trim(getPost('usertype'));
        $checkcode = trim(getPost('checkcode'));
        $cookietime = intval(getPost('cookietime'));
		
        $session = Base::load_model("session_model");
        
		if ($checkcode!=$_SESSION['checkcode']) {
            ShowMsg(lang('message','checkcode_is_not_right'),"-1");
        }
		
        $userdb = Base::load_model("user_model");
        $logininfo = $userdb->check_user_exist($username,$password);
        switch($logininfo['uid']) {
            case "-2":
                ShowMsg(lang('message','password_error'),"-1");
                break;
            case "-1":
                ShowMsg(lang('message','user_is_not_exist'),"-1");
                break;
            case "0":
                ShowMsg(lang('message','username_or_password_empty'),"-1");
                break;
            default:
                //登录前台
                $_SESSION['uid'] = $logininfo['uid'];
                $_SESSION['usertype'] = $logininfo['usertype'];
				$_SESSION['username'] = $username;
                $_SESSION['password'] = password($password);
                //ADMIN_USER_TYPE代表管理员后台身份
                if ($usertype==ADMIN_USER_TYPE) {
					$_SESSION['admin_uid'] = $logininfo['uid'];
					$_SESSION['admin_usertype'] = $logininfo['usertype'];
					$_SESSION['admin_username'] = $username;
					$_SESSION['admin_password'] = password($password);
                }
				$usergroupdb = Base::load_model("usergroup_model");
				$usergroup_info=$usergroupdb->get_one($logininfo['usertype']);
				$_SESSION['usergroupname'] = $usergroup_info['name'];
				 
                unset($_SESSION['checkcode']);
                $userdb->add_login_log($logininfo['uid']);
                //添加session到数据库
                $session->insert_session(array(
                    'uid'=>$logininfo['uid'],
                    'username'=>$username,
                    'password'=>password($password),
                    'usertype'=>$logininfo['usertype']
                ));
                //设置cookie
                $session->ssetcookie('auth',$logininfo['uid'].'|'.password($password).'|'.$logininfo['usertype'],$cookietime);
                //跳转
                ShowMsg(lang('message','login_success'),get_uri("index","init"));
        }
    }
    /*
	 * 管理员退出后台登录
	 */
    function admin_logout() {
        $session = Base::load_model("session_model");
        $session->delete_session($_SESSION['admin_uid']);
        $session->clearcookie('auth');
        ShowMsg(lang('message','logout_success'),get_uri("login","init"));
    }
    /*
	 * 普通用户退出登录
	 */
    function logout() {
        $session = Base::load_model("session_model");
        $session->delete_session($_SESSION['uid']);
        $session->clearcookie('auth');
        ShowMsg(lang('message','logout_success'),get_uri("login","init"));
    }
}
?>