<?php
defined('SYS_IN') or exit('Access Denied.');

class relic_model extends model {
    protected $_table = 'relic';
    protected $_primarykey = 'relicid';
    
	const numlength = 9;
	
    function __construct() {
        parent::__construct();
    }
    /**
     * 
     * 获取一条信息
     * @param int $relicid
     */
    function get_one($relicid = "" , $field = "") {
        if(!empty($relicid)) {
			$where = "where rel.relicid=".$relicid;
			$field = empty($field) ? 'rel.*,s.scenespotname' : $field;			
			$sql = "select $field from ".tname($this->_table)." rel
					LEFT JOIN ".tname("scenespot")." s ON 
					rel.scenespotid=s.scenespotid 
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
			if($param['relicnum']) {
				$where .= " AND rel.relicnum=".$param['relicnum'];
			}
			$field = empty($field) ? 'rel.*,s.scenespotname' : $field;			
			$sql = "select $field from ".tname($this->_table)." rel
					LEFT JOIN ".tname("scenespot")." s ON 
					rel.scenespotid=s.scenespotid 
					$where limit 0,1";
					
			$value = $this->db->get_one($sql);
			
			return $value;
		} else {
			return '';
		}
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
        $where = empty($where) ? ' WHERE 1 ' : $where;
        $oderbye = empty($oderbye) ? '' : ' ORDER BY '.$oderbye;
        $sql = "SELECT ".$field." FROM ".tname($this->_table)." 
				rel LEFT JOIN ".tname("scenespot")." s ON 
				rel.scenespotid=s.scenespotid 
				".$where.$oderbye." LIMIT $offset,$num ";
        
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
        $sql = "SELECT COUNT(*) as c FROM ".tname($this->_table)." 
				rel LEFT JOIN ".tname("scenespot")." s ON 
				rel.scenespotid=s.scenespotid 
				".$where;
        $value = $this->db->get_one($sql);
        return $value['c'];
    }
	
	/**
     * 检查编号是否存在
     * return @param string $relicnum
     */
	function exists_relicnum($relicnum) {
		$flag = false;
		if(!empty($relicnum)) {
			if($this->get_count(" WHERE rel.relicnum='$relicnum' ") > 0) {
				$flag = true;
			}
		}
		return $flag;
	}
	
	/**
     * 得到最大的文物编号
     * return @param string $relicnum
     */
	function get_max_relicnum() {
		$where = " where 1";
		$sql = "SELECT relicnum FROM ".tname($this->_table)." $where ORDER BY relicnum DESC LIMIT 0,1";
		$value = $this->db->get_one($sql);		
		return $value['relicnum'];
	}
	
	/**
     * 得到自动增加的文物编号
     * return @param string $relicnum
     */
	function get_auto_relicnum($add = 1) {
		$num = $this->get_max_relicnum();
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