<?php
defined('SYS_IN') or exit('Access Denied.');

class batchcode_model extends model {
    protected $_table = 'batchcode';
    protected $_primarykey = 'batchid';
    
    function __construct() {
        parent::__construct();
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
    function get_list($field = '', $num = 0, $offset = 1,  $where = '', $oderbye = ' dateline DESC') {
        $num = intval($num);
        $offset = (empty($offset) || $offset < 0) ? 0 : intval($offset);
        $field = empty($field) ? ' * ' : $field;
        $where = empty($where) ? ' WHERE 1 AND status!=9 ' : $where;
        $oderbye = ' ORDER BY '.$oderbye;
		$limit = empty($num) ? "" : " LIMIT $offset,$num";		
        $sql = "SELECT ".$field." FROM ".$this->tname($this->_table).$where.$oderbye.$limit;
        $list = $this->db->get_list($sql);
        return $list;
    }
    
    /**
     * 
     * 删除一条信息
     * @param array $data
     * return Boolean $flag
     */
    function delete($batchid) {
        $flag = false;
        $sql = "UPDATE ".$this->tname($this->_table)." set status=9 WHERE batchid=".$batchid;
        if ($this->db->query($sql)) {
            $flag = true;
        }
        return $flag;
    }    
	
	function isExistsBatchnum($batchnum) {
		$sql = "select COUNT(*) as c from ".$this->tname($this->_table)." WHERE batchnum='$batchnum'";
		$value = $this->db->get_one($sql);
        return ($value['c'] > 0);
	}
}
?>