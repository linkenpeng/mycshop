<?php
defined('SYS_IN') or exit('Access Denied.');

class scenetype_model extends model {
    protected $_table = 'scenetype';
    protected $_primarykey = 'typeid';
    
    function __construct() {
        parent::__construct();
    }    
}
?>