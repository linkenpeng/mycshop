<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class batchcode_model extends model {
    private $table = "batchcode";
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
        $flag = $this->db->insert(tname($this->table),$data);
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
    function get_list($field = '', $num = 0, $offset = 1,  $where = '', $oderbye = ' dateline DESC') {
        $num = intval($num);
        $offset = (empty($offset) || $offset < 0) ? 0 : intval($offset);
        $field = empty($field) ? ' * ' : $field;
        $where = empty($where) ? ' WHERE 1 AND status!=9 ' : $where;
        $oderbye = ' ORDER BY '.$oderbye;
		$limit = empty($num) ? "" : " LIMIT $offset,$num";		
        $sql = "SELECT ".$field." FROM ".tname($this->table).$where.$oderbye.$limit;
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
        $sql = "UPDATE ".tname($this->table)." set status=9 WHERE batchid=".$batchid;
        if ($this->db->query($sql)) {
            $flag = true;
        }
        return $flag;
    }    
	
	function isExistsBatchnum($batchnum) {
		$sql = "select COUNT(*) as c from ".tname($this->table)." WHERE batchnum='$batchnum'";
		$value = $this->db->get_one($sql);
        return ($value['c'] > 0);
	}
}
?>