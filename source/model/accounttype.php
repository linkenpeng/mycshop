<?php
defined('SYS_IN') or exit('Access Denied.');

class model_accounttype extends model_base {
    protected $_table = 'account';
    protected $_primarykey = 'actypeid';
    
    function __construct() {
        parent::__construct();
    }    
}
?>