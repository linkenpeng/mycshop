<?php
defined('SYS_IN') or exit('Access Denied.');
class application_admin_login extends application_base {
    function __construct() {

    }
	
    function init() {
        if (!empty($_SESSION['admin_uid'])&&($_SESSION['admin_usertype']==ADMIN_USER_TYPE)) {
            header('location:'.trig_func_common::get_uri("index","init"));
        } else {
            include trig_func_common::admin_template('login');
        }
    }
	
    /*
	 * 显示验证码
	 */
    function showcode() {
    	$checkcode = new trig_checkcode();
        $checkcode->showcode();
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
		
        $session = new model_session();
        
		if ($checkcode!=$_SESSION['checkcode']) {
            trig_func_common::ShowMsg(trig_func_common::lang('message','checkcode_is_not_right'),"-1");
        }
		
        $userdb = new user_model();
        $logininfo = $userdb->check_user_exist($username,$password);
        switch($logininfo['uid']) {
            case "-2":
                trig_func_common::ShowMsg(trig_func_common::lang('message','password_error'),"-1");
                break;
            case "-1":
                trig_func_common::ShowMsg(trig_func_common::lang('message','user_is_not_exist'),"-1");
                break;
            case "0":
                trig_func_common::ShowMsg(trig_func_common::lang('message','username_or_password_empty'),"-1");
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
				$usergroupdb = new model_usergroup();
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
                trig_func_common::ShowMsg(trig_func_common::lang('message','login_success'),trig_func_common::get_uri("index","init"));
        }
    }
    /*
	 * 管理员退出后台登录
	 */
    function admin_logout() {
        $session = new model_session();
        $session->delete_session($_SESSION['admin_uid']);
        $session->clearcookie('auth');
        trig_func_common::ShowMsg(trig_func_common::lang('message','logout_success'),trig_func_common::get_uri("login","init"));
    }
    /*
	 * 普通用户退出登录
	 */
    function logout() {
        $session = new model_session();
        $session->delete_session($_SESSION['uid']);
        $session->clearcookie('auth');
        trig_func_common::ShowMsg(trig_func_common::lang('message','logout_success'),trig_func_common::get_uri("login","init"));
    }
}
?>