<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_login extends application_admin_base {

	function __construct() {
		parent::__construct(false);
	}

	function init() {
		if (!empty($_SESSION['admin_uid']) && ($_SESSION['admin_usertype'] == ADMIN_USER_TYPE)) {
			header('location:' . trig_mvc_route::get_uri("index", "init"));
		} else {
			$this->layout = 'layouts/login';
			$this->display('login');
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
		$username = trim(trig_http_request::getForm('username'));
		$password = trim(trig_http_request::getForm('password'));
		$usertype = trim(trig_http_request::getForm('usertype'));
		$checkcode = trim(trig_http_request::getForm('checkcode'));
		$cookietime = intval(trig_http_request::getForm('cookietime'));
		
		$session = new model_session();
		
		if ($checkcode != $_SESSION['checkcode']) {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'checkcode_is_not_right'), "-1");
		}
		
		$userdb = new model_user();
		$logininfo = $userdb->check_user_exist($username, $password);
		switch ($logininfo['uid']) {
			case "-2":
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'password_error'), "-1");
				break;
			case "-1":
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'user_is_not_exist'), "-1");
				break;
			case "0":
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'username_or_password_empty'), "-1");
				break;
			default:
				// 登录前台
				$_SESSION['uid'] = $logininfo['uid'];
				$_SESSION['usertype'] = $logininfo['usertype'];
				$_SESSION['username'] = $username;
				$_SESSION['password'] = trig_func_common::password($password);
				// ADMIN_USER_TYPE代表管理员后台身份
				if ($usertype == ADMIN_USER_TYPE) {
					$_SESSION['admin_uid'] = $logininfo['uid'];
					$_SESSION['admin_usertype'] = $logininfo['usertype'];
					$_SESSION['admin_username'] = $username;
					$_SESSION['admin_password'] = trig_func_common::password($password);
				}
				$usergroupdb = new model_usergroup();
				$usergroup_info = $usergroupdb->get_one($logininfo['usertype']);
				$_SESSION['usergroupname'] = $usergroup_info['name'];
				
				unset($_SESSION['checkcode']);
				$userdb->add_login_log($logininfo['uid']);
				// 添加session到数据库
				$session->insert_session(array(
					'uid' => $logininfo['uid'],
					'username' => $username,
					'password' => trig_func_common::password($password),
					'usertype' => $logininfo['usertype'] 
				));
				// 设置cookie
				$session->ssetcookie('auth', $logininfo['uid'] . '|' . trig_func_common::password($password) . '|' . $logininfo['usertype'], $cookietime);
				// 跳转
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'login_success'), trig_mvc_route::get_uri("index", "init"));
		}
	}
	/*
	 * 管理员退出后台登录
	 */
	function admin_logout() {
		$session = new model_session();
		$session->delete_session($_SESSION['admin_uid']);
		$session->clearcookie('auth');
		trig_func_common::ShowMsg(trig_func_common::lang('message', 'logout_success'), trig_mvc_route::get_uri("login", "init"));
	}
	/*
	 * 普通用户退出登录
	 */
	function logout() {
		$session = new model_session();
		$session->delete_session($_SESSION['uid']);
		$session->clearcookie('auth');
		trig_func_common::ShowMsg(trig_func_common::lang('message', 'logout_success'), trig_mvc_route::get_uri("login", "init"));
	}
}
?>