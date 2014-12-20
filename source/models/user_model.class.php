<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class user_model extends model {
    protected $_table = 'user';
    protected $_primarykey = 'uid';
    
    function __construct() {
        parent::__construct();
    }
	
    /**
     * 
     * 验证用户密码
     * @param String $username
     * @param String $password
     * return int $uid 0：用户名或者密码为空, -1: 用户不存在,  -2:密码错误, 验证正确则返回用户uid
     */
    function check_user_exist($username, $password, $usertype = "") {
        $username = trim($username);
        $password = trim($password);
		$logininfo['uid'] = "0";
		$logininfo['usertype'] = "";
        if (!empty($username)&&!empty($password)) {
            $password_form = password($password);
            $where = " where username='$username' ";
            if (!empty($usertype)) {
                $where .= " and usertype='$usertype' ";
            }
            $sql = "select uid,password,usertype from ".tname($this->_table)." $where ";
            $value = $this->db->get_one($sql);
            if (!empty($value['uid'])) {
                if ($password_form==$value['password']) {
                    $logininfo['uid'] = $value['uid'];
					$logininfo['usertype'] = $value['usertype'];
                } else {
                   $logininfo['uid'] = "-2";
                }
            } else {
                $logininfo['uid'] = "-1";
            }
        }
        return $logininfo;
    }
    /*
	 * 更新登陆日志
	 */
    function add_login_log($uid) {
        if (!empty($uid)) {
            $loginip = get_ip();
            $logintime = time();
            $sql = "update ".tname($this->_table)." set last_ip='$loginip',last_login='$logintime',logins=logins+1 where uid='$uid'";
            $this->db->query($sql);
        }
    }
    /*
	 * 获取一个用户信息
	 */
    function get_user_info($uid) {
        if (!empty($uid)) {
            return $this->get_count($uid);
        }
    }
    /*
	 * 修改用户密码
	 */
    function update_password($admin_uid, $password) {
        $flag = false;
        if (!empty($admin_uid)&&!empty($password)) {
            $this->db->update(tname($this->_table),array(
                'password'=>password($password)
            ),"uid='$admin_uid'");
            $flag = true;
        }
        return $flag;
    }
}
?>