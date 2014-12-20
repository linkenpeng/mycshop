<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class talkreply_model extends model {
    protected $_table = 'talkreply';
    protected $_primarykey = 'talkreplyid';
    
    function __construct() {
        parent::__construct();
    }
}
?>