<?php
defined('SYS_IN') or exit('Access Denied.');

class model_order extends model_base {
    protected $_table = 'order';
    protected $_primarykey = 'orderid';
    
    function __construct() {
        parent::__construct();
    }
}