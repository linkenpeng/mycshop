<?php
defined('SYS_IN') or exit('Access Denied.');

class comment_model extends model {
    protected $_table = 'comment';
    protected $_primarykey = 'commentid';
    
    function __construct() {
        parent::__construct();
    }
}
?>