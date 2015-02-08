<?php
defined('SYS_IN') or exit('Access Denied.');

class db_driver_pdo extends db_driver {
	protected static $_instance = null;
	protected $_config = array();
	protected $_link = null;
	protected $_stmt = null;
	protected $_version = '';

	function __construct($config) {
		$this->_config = $config;
		$this->dbconn();
	}

	public static function getInstance($config) {
		if (is_null(self::$_instance) || !isset(self::$_instance)) {
			self::$_instance = new self($config);
		}
		return self::$_instance;
	}

	function dbconn() {
		$dsn = 'mysql:dbname='.$this->_config['dbname'].';host='.$this->_config['host'];		
		try {
			$this->_link = new PDO($dsn, $this->_config['user'], $this->_config['password']);
		} catch (PDOException $e) {
			$this->halt('Connection failed: ' . $e->getMessage());
		}
	}
	
	public function execute($sql, $query = '', $field = 0) {
		$this->_stmt = $this->prepare($sql);
		$result = $this->_stmt->execute();
		if ($query=='select') {
			return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
		} else if ($query=='count') {
			return count($this->_stmt->fetchAll());
		} else if ($query == 'row') {
			return $this->_stmt->fetch(PDO::FETCH_ASSOC);
		} else if ($query == 'field') {
			return $this->_stmt->fetchColumn($field);
		} else if ($query == 'column') {
			return $this->_stmt->fetchAll(PDO::FETCH_COLUMN,intval($field));
		} else if ($query == 'effect') {
			return $this->_stmt->rowCount();
		} else {
			return $result;
		}
	}
	
	public function prepare($sql, $driver_options = array()) {
		return $this->_link->prepare($sql, $driver_options);
	}	

	function query($sql, $type = '') {
		return $this->execute($sql);
	}

	function update($table, $bind = array(), $where = '') {
		$set = array();
		foreach ($bind as $col => $val) {
			if (strpos($val, '+') !== false) {
				$set[] = "$col = $val";
			} else {
				$set[] = "$col = '$val'";
			}
			unset($set[$col]);
		}
		$sql = "UPDATE " . $table . ' SET ' . implode(',', $set) . (($where) ? " WHERE $where" : '');
		$this->query($sql);
	}

	function insert($table, $bind = array()) {
		$set = array();
		foreach ($bind as $col => $val) {
			$set[] = "`$col`";
			$vals[] = "'$val'";
		}
		$sql = "INSERT INTO " . $table . ' (' . implode(', ', $set) . ') ' . 'VALUES (' . implode(', ', $vals) . ')';
		$this->query($sql);
		return $this->insert_id();
	}

	function insert_id() {
		return ($id = $this->_link->lastInsertId()) >= 0 ? $id : $this->result($this->execute("SELECT last_insert_id()"), 'column', 0);
	}

	function affected_rows() {
		return null;
	}

	function fetch_fields($query) {
		return $query->fetchColumn();
	}

	function fetch_row($query) {
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	function fetch_array($query, $result_type = PDO::FETCH_ASSOC) {
		return $query->fetchAll($result_type);
	}

	function get_one($sql, $type = '') {
		if ($sql != "") {
			if (!preg_match("/limit/is", $sql)) {
				$sql = preg_replace("/(,|;)+?$/is", "", trim($sql)) . " limit 0,1;";
				$query = $this->query($sql, $type);
			} else {
				$query = $this->query($sql, $type);
			}
		}
		$rs = $this->fetch_array($query);
		$this->free_result($query);
		return $rs;
	}

	function get_list($sql, $type = '') {
		$ret = array();
		$query = $this->query($sql, $type);
		while ($rs = $this->fetch_array($query)) {
			$ret[] = $rs;
		}
		return $ret;
	}

	function free_result($query) {
		return null;
	}
	
	function escape_string($str) {
		return $str;
	}

	function version() {
		return null;
	}

	function error() {
		return $this->_link->errorInfo();
	}

	function errno() {
		return $this->_link->errorCode();
	}

	function close() {
		return null;
	}

	function halt($message = '') {
		$sqlerror = $this->error();
		$sqlerrno = $this->errno();
		ob_end_clean();
		GZIP == 1 && function_exists('ob_gzhandler') ? ob_start('ob_gzhandler') : ob_start();
		$html = "<html><head><title>MySQL Server Error</title>";
		$html .= "<style type='text/css'>";
		$html .= "p,body{FONT-FAMILY:tahoma,arial,sans-serif;FONT-SIZE:10px;}";
		$html .= "a {TEXT-DECORATION: none;}";
		$html .= "a:hover{text-decoration: underline;}";
		$html .= "td {BORDER-RIGHT: 1px; BORDER-TOP: 0px; FONT-SIZE: 16pt; COLOR: #000000;}";
		$html .= "</style>";
		$html .= "<body>\n\n";
		$html .= "<table style='TABLE-LAYOUT:fixed;WORD-WRAP: break-word'><tr><td>$message";
		$html .= "<br><br><b>MySQL Server Error</b>:<br>$sqlerror  ( $sqlerrno )";
		$html .= "</td></tr></table>";
		echo $html;
	}
}