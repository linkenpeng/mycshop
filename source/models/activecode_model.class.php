<?php
defined('SYS_IN') or exit('Access Denied.');

class activecode_model extends model {
    protected $_table = 'activecode';
    protected $_primarykey = 'id';
    
    function __construct() {
        parent::__construct();
    }
    
    
    /**
     * 
     * 删除一条信息
     * @param array $ids
     * return Boolean $flag
     */
    function delete($ids) {
        $flag = false;
		if(empty($ids)) return $flag; 
		$where = " WHERE 1 ";		
		if(is_array($ids)) {
			$where .= " AND ".$this->_primarykey." IN (".implode(',',$ids).")";
		} else {
			$where .= " AND ".$this->_primarykey."=".$ids;
		}
        $sql = "UPDATE ".tname($this->_table)." SET status=9 ".$where;
        if ($this->db->query($sql)) {
            $flag = true;
        }
        return $flag;
    }
	
    function delete_batch($batchid) {
        $flag = false;
        $sql = "UPDATE ".tname($this->_table)." SET status=9 WHERE batchid=".$batchid;
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
        $where = empty($where) ? ' WHERE 1 AND a.status!=9 ' : $where;
        $oderbye = empty($oderbye) ? '' : ' ORDER BY '.$oderbye;
		$limit = empty($num) ? "" : " LIMIT $offset,$num";	
		
        $sql = "SELECT ".$field." FROM ".tname($this->_table)." 
				a LEFT JOIN ".tname("scene")." s ON 
				a.sceneid=s.sceneid 
				".$where.$oderbye.$limit;
		
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
				a LEFT JOIN ".tname("scene")." s ON 
				a.sceneid=s.sceneid 
				".$where;
        $value = $this->db->get_one($sql);
        return $value['c'];
    }
	
	 /**
     * 
     * 得到activecode对应的景点id
	 * @param string $activecode
     */
    function get_sceneid($activecode = "") {
        if(!empty($activecode)) {
			$where = " WHERE activecode='".$activecode."'";
			$sql = "SELECT sceneid FROM ".tname($this->_table).$where;
			$value = $this->db->get_one($sql);
			return $value['sceneid'];
		} else {
			return '';
		}
    }
	
	//更新验证码使用次数
	function update_usednum($activecode) {
		$sql = "UPDATE ".tname($this->_table)." SET usednum=usednum+1 WHERE activecode='$activecode'";
		$this->db->query($sql);
	}
}
?>