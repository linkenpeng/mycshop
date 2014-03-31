<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class device_model extends model {
    private $table = "deviceinfo";
    function __construct() {
        parent::__construct();
    }

    function get_one($deviceid = "", $field="") {
        if(!empty($deviceid)) {
			$where = "where deviceid=".$deviceid;
			$field = empty($field) ? "*" : $field;
			$sql = "select $field from ".tname($this->table)." $where limit 0,1";
			$value = $this->db->get_one($sql);
			return $value;
		} else {
			return '';
		}
    }
	
	function isExists($param = array()) {
		$where = " WHERE 1";
		if(!empty($param)) {
			foreach($param as $key=>$val) {
				$where .= " AND `$key`='$val' ";
			}
		}
		$sql = "SELECT COUNT(*) AS c FROM ".tname($this->table).$where;
		$list = $this->db->get_one($sql);
		return $list['c'] > 0;
	}

    function insert($data) {
        $flag = false;
        if ($this->db->insert(tname($this->table), $data)) {
            $flag = true;
        }
        return $flag;
    }

    function update($data, $where) {
        $flag = false;
        if (!empty($data) && !empty($where)) {
            $this->db->update(tname($this->table), $data, $where);
            $flag = true;
        }
        return $flag;
    }
	
	function get_list($num = 10, $offset, $field = '', $where = '', $oderbye = '') {
        $num = intval($num);
        $offset = (empty($offset)||$offset<0) ? 0 : intval($offset);
        $field = empty($field) ? ' * ' : $field;
        $where = empty($where) ? ' WHERE 1 ' : $where;
        $oderbye = empty($oderbye) ? '' : ' ORDER BY '.$oderbye;
		$limit = empty($num) ? '' : " LIMIT $offset,$num ";
        $sql = "SELECT ".$field." FROM ".tname($this->table).$where.$oderbye.$limit;
		
        $list = $this->db->get_list($sql);
        return $list;
    }
	
	function get_count($where = '') {
        $where = empty($where) ? ' WHERE 1 ' : $where;
        $sql = "SELECT COUNT(*) as c FROM ".tname($this->table).$where;
        $value = $this->db->get_one($sql);
        return $value['c'];
    }
}
?>