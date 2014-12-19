<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class accounttype_model extends model {
    protected $_table = 'account';
    protected $_primarykey = 'actypeid';
    
    function __construct() {
        parent::__construct();
    }    
}
?>