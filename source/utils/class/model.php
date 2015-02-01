<?php
defined('SYS_IN') or exit('Access Denied.');

/**
 * model.class.php 数据模型基类
 */
class model {
	protected $db = null;
	protected $_table = '';
	protected $_primarykey = '';
	protected $_config = array();

	function __construct($dbconfig = '') {
		global $_G;
		$this->_config = empty($dbconfig) ? $_G['db']['master'] : $_G['db'][$dbconfig];
		$driver_name = "db_driver_" . $this->_config['driver'];
		Base::load_sys_class($driver_name, '', 0);
		$this->db = $driver_name::getInstance($this->_config);
	}

	public function tname($tname) {
		return empty($tname) ? '' : " `" . $this->_config['table_pre'] . $tname . "` ";
	}

	function insert($data) {
		$flag = false;
		if ($this->db->insert($this->tname($this->_table), $data)) {
			$flag = true;
		}
		return $flag;
	}

	function update($data, $where) {
		$flag = false;
		if (!empty($data) && !empty($where)) {
			$this->db->update($this->tname($this->_table), $data, $where);
			$flag = true;
		}
		return $flag;
	}

	function delete($primarykey) {
		$flag = false;
		$sql = "DELETE FROM " . $this->tname($this->_table) . " WHERE " . $this->_primarykey . "=" . $primarykey;
		if ($this->db->query($sql)) {
			$flag = true;
		}
		return $flag;
	}

	function get_one($primarykey = "") {
		if (!empty($primarykey)) {
			$where = "where " . $this->_primarykey . "=" . $primarykey;
			$sql = "select * from " . $this->tname($this->_table) . " $where limit 1";
			$value = $this->db->get_one($sql);
			return $value;
		} else {
			return '';
		}
	}

	function get_list($num = 10, $offset, $field = '', $where = '', $oderbye = '') {
		$num = empty($num) ? 10 : intval($num);
		$offset = (empty($offset) || $offset < 0) ? 0 : intval($offset);
		$field = empty($field) ? ' * ' : $field;
		$where = empty($where) ? ' WHERE 1 ' : $where;
		$oderbye = empty($oderbye) ? ' ORDER BY dateline DESC ' : ' ORDER BY ' . $oderbye;
		$sql = "SELECT " . $field . " FROM " . $this->tname($this->_table) . $where . $oderbye . " LIMIT $offset,$num ";
		$list = $this->db->get_list($sql);
		return $list;
	}

	function get_count($where = '') {
		$where = empty($where) ? ' WHERE 1 ' : $where;
		$sql = "SELECT COUNT(*) as c FROM " . $this->tname($this->_table) . $where;
		$value = $this->db->get_one($sql);
		return $value['c'];
	}
}