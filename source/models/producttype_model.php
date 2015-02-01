<?php
defined('SYS_IN') or exit('Access Denied.');

class producttype_model extends model {
    protected $_table = 'producttype';
    protected $_primarykey = 'typeid';
    
    function __construct() {
        parent::__construct();
    }
}
?>