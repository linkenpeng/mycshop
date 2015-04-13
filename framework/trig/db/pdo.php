<?php

class trig_db_pdo implements trig_db_driver {
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
		$dsn = 'mysql:dbname=' . $this->_config['dbname'] . ';host=' . $this->_config['host'];
		try {
			$this->_link = new PDO($dsn, $this->_config['user'], $this->_config['password']);
		} catch (PDOException $e) {
			$this->halt('Connection failed: ' . $e->getMessage());
		}
		
		if ($this->version() > '4.1') {
			if ($this->_config['charset'] != 'latin1') {
				$this->_link->query("SET character_set_connection=" . $this->_config['charset'] . ", character_set_results=" . $this->_config['charset'] . ", character_set_client=binary");
			}
			if ($this->version() > '5.0.1') {
				$this->_link->query("SET sql_mode=''");
			}
		}
	}		

	function query($sql, $type = '') {
		return $this->execute($sql);
	}

	function insert_id() {
		return ($id = $this->_link->lastInsertId()) >= 0 ? $id : $this->execute("SELECT last_insert_id()", 'field', 0);
	}

	function affected_rows() {
		if($this->_stmt != null) {
			return $this->_stmt->rowCount();
		}
		return 0;
	}

	function fetch_fields($field = 0) {
		return $this->execute($sql, 'field', $field);
	}

	function fetch_row($sql) {
		return $this->execute($sql, 'row');
	}

	function fetch_array($sql, $result_type = MYSQL_ASSOC) {
		return $this->execute($sql, 'select');
	}

	function get_one($sql, $type = '') {
		if ($sql != "") {
			if (!preg_match("/limit/is", $sql)) {
				$sql = preg_replace("/(,|;)+?$/is", "", trim($sql)) . " limit 0,1;";
			}
		}
		$rs = $this->fetch_row($sql);
		$this->free_result($sql);
		return $rs;
	}

	function get_list($sql, $type = '') {
		$ret = $this->fetch_array($sql);
		return $ret;
	}

	function free_result($query) {
		if($this->_stmt != null) {
			return $this->_stmt->closeCursor();
		}
		return null;
	}

	function escape_string($str) {
		return $str;
	}

	function version() {
		if (empty($this->_version)) {
			$this->_version = $this->execute('SELECT VERSION()', 'field', 0);
		}
		return $this->_version;
	}

	function error() {
		return $this->_link->errorInfo();
	}

	function errno() {
		return $this->_link->errorCode();
	}

	function close() {
		$this->_link = null;
	}
	
	private function execute($sql, $query = '', $field = 0) {
		$this->_stmt = $this->prepare($sql);
		$result = $this->_stmt->execute();
		if ($query == 'select') {
			return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
		} else if ($query == 'count') {
			return count($this->_stmt->fetchAll());
		} else if ($query == 'row') {
			return $this->_stmt->fetch(PDO::FETCH_ASSOC);
		} else if ($query == 'field') {
			return $this->_stmt->fetchColumn($field);
		} else if ($query == 'column') {
			return $this->_stmt->fetchAll(PDO::FETCH_COLUMN, intval($field));
		} else if ($query == 'effect') {
			return $this->_stmt->rowCount();
		} else {
			return $result;
		}
	}
	
	private function prepare($sql, $driver_options = array()) {
		return $this->_link->prepare($sql, $driver_options);
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