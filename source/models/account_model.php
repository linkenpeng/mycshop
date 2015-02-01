<?php
defined('SYS_IN') or exit('Access Denied.');

class account_model extends model {
    protected $_table = 'account';
    protected $_primarykey = 'accountid';
    
    function __construct() {
        parent::__construct();
    }    
}
?>