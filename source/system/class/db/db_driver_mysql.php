<?php
defined('SYS_IN') or exit('Access Denied.');

class db_driver_mysql extends db_driver {
	protected static $_instance = null;
	protected $_config = array();

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
		if ($this->_config['pconnect']) {
			if (!$this->link = mysql_pconnect($this->_config['host'], $this->_config['user'], $this->_config['password'])) {
				$this->halt('Can not connect to MySQL server');
			}
		} else {
			if (!$this->link = mysql_connect($this->_config['host'], $this->_config['user'], $this->_config['password'], 1)) {
				$this->halt('Can not connect to MySQL server');
			}
		}
		if ($this->version() > '4.1') {
			if ($this->_config['charset'] != 'latin1') {
				mysql_query("SET character_set_connection=" . $this->_config['charset'] . ", character_set_results=" . $this->_config['charset'] . ", character_set_client=binary", $this->link);
			}
			
			if ($this->version() > '5.0.1') {
				mysql_query("SET sql_mode=''", $this->link);
			}
		}
		
		if ($this->_config['dbname']) {
			mysql_select_db($this->_config['dbname'], $this->link);
		}
	}

	function query($sql, $type = '') {
		if (!$this->link) {
			$this->dbconn();
		}
		$func = $type == 'UNBUFFERED' && function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';
		if (!($query = $func($sql, $this->link))) {
			if ($this->errno() && substr($type, 0, 5) != 'RETRY') {
				$this->close();
				$this->dbconn();
				$this->query($sql, 'RETRY' . $type);
			} elseif ($type != 'SILENT' && substr($type, 5) != 'SILENT') {
				$this->halt('MySQL Query Error', $sql);
			}
		}
		return $query;
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
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	function fetch_fields($query) {
		return mysql_fetch_field($query);
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
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
		return mysql_free_result($query);
	}

	function version() {
		return mysql_get_server_info($this->link);
	}

	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() {
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}

	function close() {
		return mysql_close($this->link);
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