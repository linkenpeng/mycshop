<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class note_model extends model {
    protected $_table = 'note';
    protected $_primarykey = 'noteid';
    
    function __construct() {
        parent::__construct();
    }
}
?>