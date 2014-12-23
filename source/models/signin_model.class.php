<?php
defined('SYS_IN') or exit('Access Denied.');

class signin_model extends model {
    protected $_table = 'signin';
    protected $_primarykey = 'signinid';
    
    function __construct() {
        parent::__construct();
    }
}
?>