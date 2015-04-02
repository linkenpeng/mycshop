<?php
defined('SYS_IN') or exit('Access Denied.');

class model_talkreply extends model_base {
    protected $_table = 'talkreply';
    protected $_primarykey = 'talkreplyid';
    
    function __construct() {
        parent::__construct();
    }
}