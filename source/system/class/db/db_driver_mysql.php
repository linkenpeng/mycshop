<?php
defined('SYS_IN') or exit('Access Denied.');

class db_driver_mysql extends db_driver {

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

	function select_db($dbname) {
		return mysql_select_db($dbname, $this->link);
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
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
		$this->querynum++;
		return $query;
	}

	function counter($table_name, $where_str = "", $field_name = "*") {
		$where_str = trim($where_str);
		if (strtolower(substr($where_str, 0, 5)) != 'where' && $where_str) {
			$where_str = "WHERE " . $where_str;
		}
		$query = " SELECT COUNT($field_name) FROM $table_name $where_str ";
		$result = $this->query($query);
		$fetch_row = mysql_fetch_row($result);
		return $fetch_row[0];
	}

	function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() {
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}

	function result($query, $row) {
		$query = mysql_result($query, $row);
		return $query;
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_fields($query) {
		return mysql_fetch_field($query);
	}

	function version() {
		return mysql_get_server_info($this->link);
	}

	function close() {
		return mysql_close($this->link);
	}

	function halt($message = '') {
		$website = SITE_URL;
		$sqlerror = mysql_error();
		$sqlerrno = mysql_errno();
		$sqlerror = str_replace($this->db_host, 'dbhost', $sqlerror);
		ob_end_clean();
		GZIP == 1 && function_exists('ob_gzhandler') ? ob_start('ob_gzhandler') : ob_start();
		echo "<html><head><title>$website</title><style type='text/css'>P,BODY{FONT-FAMILY:tahoma,arial,sans-serif;FONT-SIZE:10px;}A { TEXT-DECORATION: none;}a:hover{ text-decoration: underline;}TD { BORDER-RIGHT: 1px; BORDER-TOP: 0px; FONT-SIZE: 16pt; COLOR: #000000;}</style><body>\n\n";
		echo "<table style='TABLE-LAYOUT:fixed;WORD-WRAP: break-word'><tr><td>$message";
		echo "<br><br><b>The URL Is</b>:<br>" . HTTP_REFERER;
		echo "<br><br><b>MySQL Server Error</b>:<br>$sqlerror  ( $sqlerrno )";
		echo "<br><br><b>You Can Get Help In</b>:<br><a target=_blank href=" . $website . "/><b>" . $website . "</b></a>";
		echo "</td></tr></table>";
	}
}