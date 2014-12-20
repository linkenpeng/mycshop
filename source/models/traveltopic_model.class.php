<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class traveltopic_model extends model {
    protected $_table = 'traveltopic';
    protected $_primarykey = 'typeid';
    
    function __construct() {
        parent::__construct();
    }
}
?>