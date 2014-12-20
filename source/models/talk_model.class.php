<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class talk_model extends model {
    protected $_table = 'talk';
    protected $_primarykey = 'talkid';
    
    function __construct() {
        parent::__construct();
    }
}
?>