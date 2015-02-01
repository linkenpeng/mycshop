<?php
defined('SYS_IN') or exit('Access Denied.');

class article_model extends model {
    protected $_table = 'article';
    protected $_primarykey = 'aid';
    
    function __construct() {
        parent::__construct();
    }
    /**
     * 
     * 获取一条信息
     * @param int $aid
     */
    function get_one($aid = "",$field="") {
        if(!empty($aid)) {
			$where = "where a.aid=".$aid;
			$field = empty($field)?"a.*":$field;
			$sql = "select $field,c.name from ".$this->tname($this->_table)." a
					LEFT JOIN ".$this->tname("article_category")." c ON 
					a.catid=c.catid 
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
     * reunt <array> $list
     */
    function get_list($num = 10, $offset, $field = '', $where = '', $oderbye = '') {
        $num = intval($num);
        $offset = (empty($offset)||$offset<0) ? 0 : intval($offset);
        $field = empty($field) ? ' * ' : $field;
        $where = empty($where) ? ' WHERE 1 ' : $where;
        $oderbye = empty($oderbye) ? '' : ' ORDER BY '.$oderbye;
		$limit = empty($num) ? '' : " LIMIT $offset,$num ";
        $sql = "SELECT ".$field." FROM ".$this->tname($this->_table)." 
				a LEFT JOIN ".$this->tname("article_category")." c ON 
				a.catid=c.catid 
				".$where.$oderbye.$limit;
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
        $sql = "SELECT COUNT(*) as c FROM ".$this->tname($this->_table)." 
				a LEFT JOIN ".$this->tname("article_category")." c ON 
				a.catid=c.catid 
				".$where;
        $value = $this->db->get_one($sql);
        return $value['c'];
    }
}
?>