<?php
defined('SYS_IN') or exit('Access Denied.');
abstract class db_driver {
    protected static $_instance = null;
    protected $_config = array();
    
    abstract static function getInstance($config);
    
    abstract function dbconn();
    
    abstract function select_db($dbname);
    
    abstract function fetch_array($query, $result_type = MYSQL_ASSOC);
    
    abstract function update($table, $bind = array(), $where = '');
    
    abstract function insert($table, $bind = array());
    
    abstract function insert_id();
    
    abstract function get_one($sql, $type = '');
	
    abstract function get_list($sql, $type = '');
    
    abstract function query($sql, $type = '');
    
    abstract function counter($table_name, $where_str = "", $field_name = "*");
    
    abstract function affected_rows();
    
    abstract function error();
    
    abstract function errno();
    
    abstract function result($query, $row);
    
    abstract function num_rows($query);
    
    abstract function num_fields($query);
    
    abstract function free_result($query);   
    
    abstract function fetch_row($query);
    
    abstract function fetch_fields($query);
    
    abstract function version();
    
    abstract function close();
    
    abstract function halt($message = '');    
}