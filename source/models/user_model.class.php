<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class user_model extends model {
    private $table = "user";
    function __construct() {
        parent::__construct();
    }
	/**
     * 
     * 插入一条信息
     * @param array $data
     * return Boolean $flag
     */
    function insert($data) {
        $flag = false;
        //用户名是必须的
        if (!empty($data['username'])) {
            $this->db->insert(tname($this->table),$data);
            $flag = true;
        }
        return $flag;
    }
    /**
     * 修改一条信息
     * 
     * @param array $data
     * @param string $where
     * @author Myron
     * 2011-5-27 上午10:18:10
     */
    function update($data, $where) {
        $flag = false;
        if (!empty($data)&&!empty($where)) {
            $this->db->update(tname($this->table),$data,$where);
            $flag = true;
        }
        return $flag;
    }
	/**
     * 
     * 删除一条信息
     * @param int $uid
     * return Boolean $flag
     */
    function delete($uid) {
        $flag = false;
        $sql = "DELETE FROM ".tname($this->table)." WHERE uid=".$uid;
        if ($this->db->query($sql)) {
            $flag = true;
        }
        return $flag;
    }
	/**
     * 获取一组信息
     * @param int $num
     * @param int $offset
     * @param string $field
     * @param stirng $where
     * @param string $orderby
     * reunt @param array $list
     */
    function get_list($num = 10, $offset, $field = '', $where = '', $oderbye = '') {
        $num = empty($num) ? 10 : intval($num);
        $offset = (empty($offset)||$offset<0) ? 0 : intval($offset);
        $field = empty($field) ? ' * ' : $field;
        $where = empty($where) ? ' WHERE 1' : $where;
        $oderbye = empty($oderbye) ? ' ORDER BY dateline DESC ' : ' ORDER BY '.$oderbye;
        $sql = "SELECT ".$field." FROM ".tname($this->table).$where.$oderbye." LIMIT $offset,$num ";
        //echo $sql;
        $list = $this->db->get_list($sql);
        return $list;
    }
    /**
     * 获取总数
     * @param stirng $where
     * return @param int $count
     */
    function get_count($where = '') {
        $where = empty($where) ? ' WHERE 1  ' : $where;
        $sql = "SELECT COUNT(*) as c FROM ".tname($this->table).$where;
        $value = $this->db->get_one($sql);
        return $value['c'];
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
            //不在sql语句中判断password,防sql注入,user_typeid=1代表是管理员身份
            $where = " where username='$username' ";
            if (!empty($usertype)) {
                $where .= " and usertype='$usertype' ";
            }
            $sql = "select uid,password,usertype from ".tname($this->table)." $where ";
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
            $sql = "update ".tname($this->table)." set last_ip='$loginip',last_login='$logintime',logins=logins+1 where uid='$uid'";
            $this->db->query($sql);
        }
    }
    /*
	 * 获取一个用户信息
	 */
    function get_user_info($uid) {
        if (!empty($uid)) {
            $sql = "select * from ".tname($this->table)." where uid='$uid'";
            $value = $this->db->get_one($sql);
            return $value;
        }
    }
	/*
	 * 获取一个用户信息
	 */
    function get_one($uid="") {
        if (!empty($uid)) {
            $sql = "select * from ".tname($this->table)." where uid='$uid'";
            $value = $this->db->get_one($sql);
            return $value;
        }
    }
    /*
	 * 修改用户密码
	 */
    function update_password($admin_uid, $password) {
        $flag = false;
        if (!empty($admin_uid)&&!empty($password)) {
            $this->db->update(tname($this->table),array(
                'password'=>password($password)
            ),"uid='$admin_uid'");
            $flag = true;
        }
        return $flag;
    }
}
?>