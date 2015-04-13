<?php

interface trig_db_driver {

	public static function getInstance($config);

	function dbconn();

	function query($sql, $type = '');

	function insert_id();

	function affected_rows();

	function fetch_fields($query);

	function fetch_row($query);

	function fetch_array($query, $result_type = MYSQL_ASSOC);

	function get_one($sql, $type = '');

	function get_list($sql, $type = '');

	function free_result($query);

	function escape_string($str);

	function version();

	function error();

	function errno();

	function close();

	function halt($message = '');
}