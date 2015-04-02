<?php
defined('SYS_IN') or exit('Access Denied.');

class model_talk extends model_base {
    protected $_table = 'talk';
    protected $_primarykey = 'talkid';
    
    function __construct() {
        parent::__construct();
    }
}