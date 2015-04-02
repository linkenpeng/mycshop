<?php
defined('SYS_IN') or exit('Access Denied.');

class model_comment extends model_base {
    protected $_table = 'comment';
    protected $_primarykey = 'commentid';
    
    function __construct() {
        parent::__construct();
    }
}
?>