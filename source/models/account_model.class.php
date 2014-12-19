<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class account_model extends model {
    protected $_table = 'account';
    protected $_primarykey = 'accountid';
    
    function __construct() {
        parent::__construct();
    }    
}
?>