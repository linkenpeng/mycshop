<?php

class trig_db_mysqli implements trig_db_driver {
	protected static $_instance = null;
	protected $_config = array();
	protected $_link = null;
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
		$link = new mysqli();
		if (!$link->real_connect($this->_config['host'], $this->_config['user'], $this->_config['password'], $this->_config['dbname'], null, null, MYSQLI_CLIENT_COMPRESS)) {
			$this->halt('Can not connect to MySQL server');
		} else {
			$this->_link = $link;
			if ($this->version() > '4.1') {
				$link->set_charset($this->_config['charset']);
				$serverset = $this->version() > '5.0.1' ? 'sql_mode=\'\'' : '';
				$serverset && $link->query("SET $serverset");
			}
		}
		return $link;
	}

	function query($sql, $silent = false, $unbuffered = false) {
		if ('UNBUFFERED' === $silent) {
			$silent = false;
			$unbuffered = true;
		} elseif ('SILENT' === $silent) {
			$silent = true;
			$unbuffered = false;
		}
		
		$resultmode = $unbuffered ? MYSQLI_USE_RESULT : MYSQLI_STORE_RESULT;
		
		if (!($query = $this->_link->query($sql, $resultmode))) {
			if (in_array($this->errno(), array(
				2006,
				2013 
			)) && substr($silent, 0, 5) != 'RETRY') {
				$this->connect();
				return $this->_link->query($sql, 'RETRY' . $silent);
			}
			if (!$silent) {
				$this->halt($sql);
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
		return ($id = $this->_link->insert_id) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function result($query, $row = 0) {
		if (!$query || $query->num_rows == 0) {
			return null;
		}
		$query->data_seek($row);
		$assocs = $query->fetch_row();
		return $assocs[0];
	}

	function affected_rows() {
		return $this->_link->affected_rows;
	}

	function fetch_fields($query) {
		return $query ? $query->fetch_field() : null;
	}

	function fetch_row($query) {
		$query = $query ? $query->fetch_row() : null;
		return $query;
	}

	function fetch_array($query, $result_type = MYSQLI_ASSOC) {
		if ($result_type == 'MYSQL_ASSOC')
			$result_type = MYSQLI_ASSOC;
		return $query ? $query->fetch_array($result_type) : null;
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
		return $query ? $query->free() : false;
	}

	function escape_string($str) {
		return $this->_link->escape_string($str);
	}

	function version() {
		if (empty($this->_version)) {
			$this->_version = $this->_link->server_info;
		}
		return $this->_version;
	}

	function error() {
		return (($this->_link) ? $this->_link->error : mysqli_error());
	}

	function errno() {
		return intval(($this->_link) ? $this->_link->errno : mysqli_errno());
	}

	function close() {
		return $this->_link->close();
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