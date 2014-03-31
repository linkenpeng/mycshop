<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class article_model extends model {
    private $table = "article";
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
			$sql = "select $field,c.name from ".tname($this->table)." a
					LEFT JOIN ".tname("article_category")." c ON 
					a.catid=c.catid 
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
    function delete($aid) {
        $flag = false;
        $sql = "DELETE FROM ".tname($this->table)." WHERE aid=".$aid;
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
		$limit = empty($num) ? '' : " LIMIT $offset,$num ";
        $sql = "SELECT ".$field." FROM ".tname($this->table)." 
				a LEFT JOIN ".tname("article_category")." c ON 
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
        $sql = "SELECT COUNT(*) as c FROM ".tname($this->table)." 
				a LEFT JOIN ".tname("article_category")." c ON 
				a.catid=c.catid 
				".$where;
        $value = $this->db->get_one($sql);
        return $value['c'];
    }
}
?>