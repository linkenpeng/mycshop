<?php
defined('SYS_IN') or exit('Access Denied.');
/**
 * model.class.php 数据模型基类
 *
 */
class model {
    //数据库连接
    protected $db = '';
    protected $_table = '';
    protected $_primarykey = '';
    
    public function __construct() {
        global $db;
        $this->db = $db;
    }
    
    /**
     *
     * 插入一条信息
     * @param array $data
     * return Boolean $flag
     */
    function insert($data) {
    	$flag = false;
    	if ($this->db->insert(tname($this->_table),$data)) {
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
    		$this->db->update(tname($this->_table),$data,$where);
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
    function delete($primarykey) {
    	$flag = false;
    	$sql = "DELETE FROM ".tname($this->_table)." WHERE ".$this->_primarykey."=".$primarykey;
    	if ($this->db->query($sql)) {
    		$flag = true;
    	}
    	return $flag;
    }
    
    /**
     *
     * 获取一条信息
     * @param int $accountid
     */
    function get_one($primarykey = "") {
    	if(!empty($primarykey)) {
    		$where = "where ".$this->_primarykey."=".$primarykey;
    		$sql = "select * from ".tname($this->_table)." $where limit 1";
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
     * @param string $oderbye
     * reunt @param array $list
     */
    function get_list($num = 10, $offset, $field = '', $where = '', $oderbye = '') {
    	$num = empty($num) ? 10 : intval($num);
    	$offset = (empty($offset)||$offset<0) ? 0 : intval($offset);
    	$field = empty($field) ? ' * ' : $field;
    	$where = empty($where) ? ' WHERE 1 ' : $where;
    	$oderbye = empty($oderbye) ? ' ORDER BY dateline DESC ' : ' ORDER BY '.$oderbye;
    	$sql = "SELECT ".$field." FROM ".tname($this->_table).$where.$oderbye." LIMIT $offset,$num ";
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
    	$sql = "SELECT COUNT(*) as c FROM ".tname($this->_table).$where;
    	$value = $this->db->get_one($sql);
    	return $value['c'];
    }
}