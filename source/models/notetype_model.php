<?php
defined('SYS_IN') or exit('Access Denied.');

class notetype_model extends model {
    protected $_table = 'notetype';
    protected $_primarykey = 'notetypeid';
    
    function __construct() {
        parent::__construct();
    }    
}
?>