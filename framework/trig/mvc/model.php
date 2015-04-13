<?php

class trig_mvc_model {
	protected $db = null;
	protected $_table = '';
	protected $_primarykey = '';
	protected $_config = array();

	function __construct($dbconfig = '') {
		$this->_config = $dbconfig;
		if(empty($this->_config)) {
			throw new trig_exception_system(2000);
		}
		$driver_name = "trig_db_" . $this->_config['driver'];
		$this->db = $driver_name::getInstance($this->_config);
	}

	public function tname($tname) {
		return empty($tname) ? '' : " `" . $this->_config['table_pre'] . $tname . "` ";
	}

	function insert($data, $last_id = true) {
		$set = array();
		foreach ($data as $col => $val) {
			$set[] = "`$col`";
			$vals[] = "'$val'";
		}
		$sql = "INSERT INTO " . $this->tname($this->_table) . ' (' . implode(', ', $set) . ') ' . 'VALUES (' . implode(', ', $vals) . ')';
		$result = $this->db->query($sql);
		return $last_id ? $this->db->insert_id() : $result;
	}

	function update($data, $where) {
		$set = array();
		foreach ($data as $col => $val) {
			if (strpos($val, '+') !== false) {
				$set[] = "$col = $val";
			} else {
				$set[] = "$col = '$val'";
			}
			unset($set[$col]);
		}
		$sql = "UPDATE " . $this->tname($this->_table) . ' SET ' . implode(',', $set) . (($where) ? " WHERE $where" : '');
		return $this->db->query($sql);
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
			$where = "WHERE " . $this->_primarykey . "=" . $primarykey;
			$sql = "SELECT * FROM " . $this->tname($this->_table) . " $where LIMIT 1";
			$value = $this->db->get_one($sql);
			return $value;
		} else {
			return array();
		}
	}

	function get_list($num = 10, $offset, $field = '', $where = '', $oderbye = '') {
		$num = empty($num) ? 10 : intval($num);
		$offset = (empty($offset) || $offset < 0) ? 0 : intval($offset);
		$field = empty($field) ? ' * ' : $field;
		$where = empty($where) ? ' WHERE 1 ' : $where;
		$oderbye = empty($oderbye) ? '' : ' ORDER BY ' . $oderbye;
		$sql = "SELECT " . $field . " FROM " . $this->tname($this->_table) . $where . $oderbye . " LIMIT $offset, $num ";
		$list = $this->db->get_list($sql);
		return $list;
	}

	function get_all($field = '', $where = '', $oderbye = '') {
		$field = empty($field) ? ' * ' : $field;
		$where = empty($where) ? ' WHERE 1 ' : $where;
		$oderbye = empty($oderbye) ? '' : ' ORDER BY ' . $oderbye;
		$sql = "SELECT " . $field . " FROM " . $this->tname($this->_table) . $where . $oderbye;
		$list = $this->db->get_list($sql);
		return $list;
	}

	function get_count($where = '') {
		$where = empty($where) ? ' WHERE 1 ' : $where;
		$sql = "SELECT COUNT(*) as c FROM " . $this->tname($this->_table) . $where;
		$value = $this->db->get_one($sql);
		return $value['c'];
	}
	
	function safe_sql($ParaName) {
		$ParaName = trim(str_replace(" ", "", $ParaName));
		return $ParaName;
	}
}