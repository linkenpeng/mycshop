<?php
defined('SYS_IN') or exit('Access Denied.');

class model_traveltopic extends model_base {
    protected $_table = 'traveltopic';
    protected $_primarykey = 'typeid';
    
    function __construct() {
        parent::__construct();
    }
}