<?php
defined('SYS_IN') or exit('Access Denied.');

abstract class db_driver {

	abstract static function getInstance($config);

	abstract function dbconn();

	abstract function query($sql, $type = '');

	abstract function update($table, $bind = array(), $where = '');

	abstract function insert($table, $bind = array());

	abstract function insert_id();

	abstract function affected_rows();

	abstract function fetch_fields($query);

	abstract function fetch_row($query);

	abstract function fetch_array($query, $result_type = MYSQL_ASSOC);

	abstract function get_one($sql, $type = '');

	abstract function get_list($sql, $type = '');

	abstract function free_result($query);
	
	abstract function escape_string($str);	

	abstract function version();

	abstract function error();

	abstract function errno();

	abstract function close();

	abstract function halt($message = '');
}