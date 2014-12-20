<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class signin_model extends model {
    protected $_table = 'signin';
    protected $_primarykey = 'signinid';
    
    function __construct() {
        parent::__construct();
    }
}
?>