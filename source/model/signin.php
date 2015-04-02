<?php
defined('SYS_IN') or exit('Access Denied.');

class model_signin extends model_base {
    protected $_table = 'signin';
    protected $_primarykey = 'signinid';
    
    function __construct() {
        parent::__construct();
    }
}