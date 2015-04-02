<?php
defined('SYS_IN') or exit('Access Denied.');

class model_note extends model_base {
    protected $_table = 'note';
    protected $_primarykey = 'noteid';
    
    function __construct() {
        parent::__construct();
    }
}