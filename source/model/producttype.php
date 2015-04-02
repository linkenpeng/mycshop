<?php
defined('SYS_IN') or exit('Access Denied.');

class model_producttype extends model_base {
    protected $_table = 'producttype';
    protected $_primarykey = 'typeid';
    
    function __construct() {
        parent::__construct();
    }
}