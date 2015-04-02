<?php
defined('SYS_IN') or exit('Access Denied.');

class model_product extends model_base {
    protected $_table = 'product';
    protected $_primarykey = 'productid';
    
    function __construct() {
        parent::__construct();
    }
}