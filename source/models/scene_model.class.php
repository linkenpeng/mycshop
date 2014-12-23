<?php
defined('SYS_IN') or exit('Access Denied.');

class scene_model extends model {
    protected $_table = 'scene';
    protected $_primarykey = 'sceneid';
	const numlength = 3;
	
    function __construct() {
        parent::__construct();
    }

	function get_one_byparam($param = array(),$field="") {
        if(!empty($param)) {
			$where = " WHERE 1 ";
			if(!empty($param['sceneid'])) {
				$where .= " AND s.sceneid=".$param['sceneid'];
			}
			if(!empty($param['scenenum'])) {
				$where .= " AND s.scenenum='".$param['scenenum']."'";
			}			
			$field = empty($field) ? " s.*,st.name as typename,st.enname as typeenname " : $field;
			$sql = "select ".$field." from ".tname($this->_table)." s LEFT JOIN
					".tname("scenetype")." st ON s.typeid = st.typeid $where limit 0,1";
			$value = $this->db->get_one($sql);
			return $value;
		} else {
			return '';
		}
    }
	
	function get_list_withtype($num = 10, $offset, $field = '', $where = '', $oderbye = '') {
        $num = intval($num);
        $offset = (empty($offset)||$offset<0) ? 0 : intval($offset);
        $field = empty($field) ? ' s.sceneid,s.typeid,s.scenename,s.scene_enname,s.scenenum,s.image,s.level,s.province,s.city,s.cityen,s.country,s.address,s.sign_num, st.name as typename,st.enname as typeenname ' : $field;
        $where = empty($where) ? ' WHERE 1 ' : $where;
        $oderbye = empty($oderbye) ? ' ORDER BY s.dateline DESC ' : ' ORDER BY '.$oderbye;
		$limit = empty($num) ? '' : " LIMIT $offset,$num ";
        $sql = "SELECT ".$field." FROM ".tname($this->_table)." s LEFT JOIN 
				".tname("scenetype")." st ON s.typeid = st.typeid 
				".$where.$oderbye.$limit;
				
        $list = $this->db->get_list($sql);
        return $list;
    }
	
	function get_count_withtype($where = '') {
        $where = empty($where) ? ' WHERE 1 ' : $where;
        $sql = "SELECT COUNT(*) as c FROM ".tname($this->_table)." s LEFT JOIN 
				".tname("scenetype")." st ON s.typeid = st.typeid ".$where;
        $value = $this->db->get_one($sql);
        return $value['c'];
    }
	
	/**
     * 检查编号是否存在
     * return @param string $scenenum
     */
	function exists_scenenum($scenenum) {
		$flag = false;
		if(!empty($scenenum)) {
			if($this->get_count(" WHERE scenenum='$scenenum' ") > 0) {
				$flag = true;
			}
		}
		return $flag;
	}
	
	/**
     * 得到最大的景区编号
     * return @param string $scenenum
     */
	function get_max_scenenum() {
		$where = " where 1";
		$sql = "SELECT scenenum FROM ".tname($this->_table)." $where ORDER BY scenenum DESC LIMIT 0,1";
		$value = $this->db->get_one($sql);		
		return $value['scenenum'];
	}
	
	/**
     * 得到自动增加的景区编号
     * return @param string $scenenum
     */
	function get_auto_scenenum($add = 1) {
		$num = $this->get_max_scenenum();
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