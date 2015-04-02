<?php
defined('SYS_IN') or exit('Access Denied.');

class model_scenetype extends model_base {
    protected $_table = 'scenetype';
    protected $_primarykey = 'typeid';
    
    function __construct() {
        parent::__construct();
    }    
}