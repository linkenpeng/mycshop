<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class scenespot_model extends model {
    private $table = "scenespot";
	const numlength = 6;
    function __construct() {
        parent::__construct();
    }
    /**
     * 
     * 获取一条信息
     * @param int $scenespotid
     */
    function get_one($scenespotid = "",$field="") {
        if(!empty($scenespotid)) {
			$where = "where sp.scenespotid=".$scenespotid;
			$field = empty($field)?"sp.*":$field;
			$sql = "select $field,s.scenename from ".tname($this->table)." sp
					LEFT JOIN ".tname("scene")." s ON 
					sp.sceneid=s.sceneid 
					$where limit 0,1";
			$value = $this->db->get_one($sql);
			return $value;
		} else {
			return '';
		}
    }
    
    function get_one_byparam($param = array()) {
        if(!empty($param)) {
        $where = " WHERE 1";
			if($param['infocards']) {
				$where .= " AND sp.infocards=".$param['infocards'];
			}
			
			$field = empty($field) ? "sp.*" : $field;
			
			$sql = "select $field,s.scenename from ".tname($this->table)." sp
					LEFT JOIN ".tname("scene")." s ON 
					sp.sceneid=s.sceneid 
					$where limit 0,1";
			
			$value = $this->db->get_one($sql);
			return $value;
		} else {
			return '';
		}
    }
    
    /**
     * 
     * 插入一条信息
     * @param array $data
     * return Boolean $flag
     */
    function insert($data) {
        $flag = false;
        if ($this->db->insert(tname($this->table),$data)) {
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
     * @param array $data
     * return Boolean $flag
     */
    function delete($scenespotid) {
        $flag = false;
        $sql = "DELETE FROM ".tname($this->table)." WHERE scenespotid=".$scenespotid;
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
     * reunt <array> $list
     */
    function get_list($num = 10, $offset, $field = '', $where = '', $oderbye = '') {
        $num = intval($num);
        $offset = (empty($offset)||$offset<0) ? 0 : intval($offset);
        $field = empty($field) ? ' * ' : $field;
        $where = empty($where) ? ' WHERE 1 ' : $where;
        $oderbye = empty($oderbye) ? '' : ' ORDER BY '.$oderbye;
		$limit = empty($num) ? '' : " LIMIT $offset, $num ";
        $sql = "SELECT ".$field." FROM ".tname($this->table)." 
				sp LEFT JOIN ".tname("scene")." s ON 
				sp.sceneid=s.sceneid 
				".$where.$oderbye.$limit;
        //echo $sql;
        $list = $this->db->get_list($sql);
        return $list;
    }
	/**
     * 获取一组信息
     * @param int $num
     * @param int $offset
     * @param string $field
     * @param stirng $where
     * @param string $orderby
     * reunt <array> $list
     */
    function get_parent_list($num = 10, $offset, $field = '', $where = '', $oderbye = '') {
        $num = empty($num) ? 10 : intval($num);
        $offset = (empty($offset)||$offset<0) ? 0 : intval($offset);
        $field = empty($field) ? ' * ' : $field;
        $where = empty($where) ? ' WHERE 1 ' : $where;
        $oderbye = empty($oderbye) ? '' : ' ORDER BY '.$oderbye;
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
        $where = empty($where) ? ' WHERE 1 ' : $where;
        $sql = "SELECT COUNT(*) as c FROM ".tname($this->table)." 
				sp LEFT JOIN ".tname("scene")." s ON 
				sp.sceneid=s.sceneid 
				".$where;
        $value = $this->db->get_one($sql);
        return $value['c'];
    }
	
	/**
     * 检查编号是否存在
     * return @param string $infocards
     */
	function exists_infocards($infocards) {
		$flag = false;
		if(!empty($infocards)) {
			if($this->get_count(" WHERE sp.infocards='$infocards' ") > 0) {
				$flag = true;
			}
		}
		return $flag;
	}
	
	/**
     * 得到最大的景点编号
     * return @param string $infocards
     */
	function get_max_infocards() {
		$where = " where 1";
		$sql = "SELECT infocards FROM ".tname($this->table)." $where ORDER BY infocards DESC LIMIT 0,1";
		$value = $this->db->get_one($sql);		
		return $value['infocards'];
	}
	
	/**
     * 得到自动增加的景点编号
     * return @param string $infocards
     */
	function get_auto_infocards($add = 1) {
		$num = $this->get_max_infocards();
		$num = $num + $add;
		$num = $this->fillZeros($num);
		return $num;
	}
	
	/**
     * 前导0填充
     * return @param string $relicnum
     */
	private function fillZeros($num) {
		if(strlen($num) < self::numlength) {
			$zeros = self::numlength - strlen($num);
			for($i = 0; $i < $zeros; $i++) {
				$zero .= '0';
			}
			$num = $zero.$num;
		}
		return $num;
	}
}
?>