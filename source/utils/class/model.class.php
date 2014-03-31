<?php
defined('SYS_IN') or exit('Access Denied.');
/**
 * model.class.php 数据模型基类
 *
 */
class model {
    //数据库连接
    protected $db = '';
    
    public function __construct() {
        global $db;
        $this->db = $db;
    }
}