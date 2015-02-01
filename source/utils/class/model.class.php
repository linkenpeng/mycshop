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
    protected $_config = '';
    
    public function __construct($config = array()) {
        global $_G;
        $this->_config = empty($config) ? $_G['db']['master'] : $config;
        Base::load_sys_class("mysql",'',0);
        $this->db = mysql::getInstance($this->_config);
    }
    
    public function tname($tname) {
    	return empty($tname) ? '' : " `".$this->_config['table_pre'].$tname."` ";
    }
    
    /**
     *
     * 插入一条信息
     * @param array $data
     * return Boolean $flag
     */
    function insert($data) {
    	$flag = false;
    	if ($this->db->insert($this->tname($this->_table),$data)) {
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
    		$this->db->update($this->tname($this->_table),$data,$where);
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
    	$sql = "DELETE FROM ".$this->tname($this->_table)." WHERE ".$this->_primarykey."=".$primarykey;
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
    		$sql = "select * from ".$this->tname($this->_table)." $where limit 1";
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
    	$sql = "SELECT ".$field." FROM ".$this->tname($this->_table).$where.$oderbye." LIMIT $offset,$num ";
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
    	$sql = "SELECT COUNT(*) as c FROM ".$this->tname($this->_table).$where;
    	$value = $this->db->get_one($sql);
    	return $value['c'];
    }
}