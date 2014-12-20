<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class session_model extends model {
    protected $_table = 'session';
    protected $_primarykey = 'sid';    
    var $max_life_time = 600; // SESSION 过期时间
    var $ip = '';
    var $time = 0;
    var $session_id;
    
    function __construct() {
        global $_G,$_CTCONFIG;
        parent::__construct();
        $this->ip = get_ip();
        $this->max_life_time = ($_CTCONFIG['onlinetime']<600) ? "600" : $_CTCONFIG['onlinetime'];
        $this->time = $_G['timestamp'];
    }
    
    function my_session_start() {
        return session_start();
    }
    
    function insert_session($data) {
        $usertype = !empty($data['usertype']) ? intval($data['usertype']) : 0;
        $uid = !empty($data['uid']) ? intval($data['uid']) : 0;
        $username = !empty($data['username']) ? $data['username'] : '';
        $password = !empty($data['password']) ? $data['password'] : '';
        $this->session_id = $this->gen_session_id();
        if (!empty($uid)) {
            //删除过期的用户session
            $this->db->query("DELETE FROM ".tname($this->_table)." where uid = '$uid' OR lastactivity < ".($this->time-$this->max_life_time));
            //添加新的session 
            $setarr['sid'] = $this->session_id;
            $setarr['uid'] = $uid;
            $setarr['username'] = $username;
            $setarr['password'] = $password;
            $setarr['usertype'] = $usertype;
            $setarr['lastactivity'] = $this->time;
            $setarr['ip'] = $this->ip;
            $this->db->insert(tname($this->_table),$setarr);
        }
    }
    
    function delete_session($uid) {
    	if (!empty($uid)) {
    		$this->db->query("DELETE FROM ".tname($this->_table)." where uid = '$uid' OR lastactivity < ".($this->time-$this->max_life_time));
    		session_destroy();
    	}
    }

    function gen_session_id() {
        return md5(uniqid(rand(),true).$this->time);
    }
    
    function clearcookie($cookiename) {
        $this->ssetcookie($cookiename,'',-86400*365);
    }
    
    function ssetcookie($cookiename, $value, $life = 0) {
        global $_G;
        setcookie($_G['cookiepre'].$cookiename,$value,$life ? ($_G['timestamp']+$life) : 0,$_G['cookiepath'],$_G['cookiedomain'],$_SERVER['SERVER_PORT']==443 ? 1 : 0);
    }
    
    function sgetcookie($cookiename) {
    	global $_G;
    	return isset($_COOKIE[$_G['cookiepre'].$cookiename]) ? $_COOKIE[$_G['cookiepre'].$cookiename] : '';
    }
}
?>