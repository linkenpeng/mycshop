<?php
defined('SYS_IN') or exit('Access Denied.');

class model_notetype extends model_base {
    protected $_table = 'notetype';
    protected $_primarykey = 'notetypeid';
    
    function __construct() {
        parent::__construct();
    }    
}