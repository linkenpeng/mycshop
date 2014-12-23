<?php
defined('SYS_IN') or exit('Access Denied.');

class accounttype_model extends model {
    protected $_table = 'account';
    protected $_primarykey = 'actypeid';
    
    function __construct() {
        parent::__construct();
    }    
}
?>