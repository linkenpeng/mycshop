<?php
defined('SYS_IN') or exit('Access Denied.');

class login_model extends model {
    function __construct() {
        parent::__construct();
    }
    /*
	 * 验证是否登录
	 */
    function is_login() {
        $session = Base::load_model("session_model");
        $cookie = $session->sgetcookie('auth');
        if (!empty($cookie)) {
            list($uid,$password,$usertype) = explode("|",$cookie);
            if ($uid) {
                $value = $this->db->get_one("select * from ".$this->tname("session")." where uid='$uid' and password='$password' ");
                if (!empty($value)) {
                    $session->insert_session(array(
                        'uid'=>$value['uid'],
                        'username'=>$value['username'],
                        'password'=>$value['password'],
                        'usertype'=>$value['usertype']
                    ));
                    //如果是后台管理员
                    //if ($usertype==ADMIN_USER_TYPE) {
					$_SESSION['admin_uid'] = $value['uid'];
					$_SESSION['admin_usertype'] = $value['usertype'];
					$_SESSION['admin_username'] = $value['username'];
					$_SESSION['admin_password'] = $value['password'];
                    //}
                    $_SESSION['uid'] = $value['uid'];
                    $_SESSION['username'] = $value['username'];
                    $_SESSION['usertype'] = $value['usertype'];
                    $_SESSION['password'] = $value['password'];
                } else {
                    $user_info = $this->db->get_one("select uid,username,password,usertype from ".$this->tname("user")." where uid='$uid' and password='$password' ");
                    if (!empty($user_info)) {
                        $session->insert_session(array(
                            'uid'=>$user_info['uid'],
                            'username'=>$user_info['username'],
                            'password'=>$user_info['password'],
                            'usertype'=>$user_info['usertype']
                        ));
                        //如果是后台管理员
                        //if ($usertype==ADMIN_USER_TYPE) {
						$_SESSION['admin_uid'] = $user_info['uid'];
						$_SESSION['admin_usertype'] = $value['usertype'];
						$_SESSION['admin_username'] = $user_info['username'];
						$_SESSION['admin_password'] = $user_info['password'];
                        //}
                        $_SESSION['uid'] = $user_info['uid'];
                        $_SESSION['username'] = $user_info['password'];
                        $_SESSION['usertype'] = $user_info['usertype'];
                        $_SESSION['password'] = $user_info['password'];
                    } else {
                        ShowMsg(lang('common','unlogin'),get_uri("login"));
                    }
                }
            } else {
                ShowMsg(lang('message','cookie_error'),get_uri("login"));
            }
        } else { //没有cookie的时候，还是要用到session变量            
            $uid = getSession('admin_uid');
			$usertype = getSession('admin_usertype');
            $password = getSession('admin_password');
            //验证session表是否存在登录用户
            if (!empty($uid)&&!empty($password)) {
                $user_info = $this->db->get_one("select uid,username,password,usertype from ".$this->tname("user")." where uid='$uid' and password='$password' ");
                if (empty($user_info)) {
                    ShowMsg(lang('common','unlogin'),get_uri("login"));
                }
            } else {
                ShowMsg(lang('common','unlogin'),get_uri("login"));
            }
        }
    }
}
?>