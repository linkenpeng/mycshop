<?php
defined('SYS_IN') or exit('Access Denied.');

class model_account extends model_base {
    protected $_table = 'account';
    protected $_primarykey = 'accountid';
    
    function __construct() {
        parent::__construct();
    }    
}
?>