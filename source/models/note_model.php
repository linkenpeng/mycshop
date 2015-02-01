<?php
defined('SYS_IN') or exit('Access Denied.');

class note_model extends model {
    protected $_table = 'note';
    protected $_primarykey = 'noteid';
    
    function __construct() {
        parent::__construct();
    }
}
?>