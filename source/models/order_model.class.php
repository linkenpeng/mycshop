<?php
defined('SYS_IN') or exit('Access Denied.');

class order_model extends model {
    protected $_table = 'order';
    protected $_primarykey = 'orderid';
    
    function __construct() {
        parent::__construct();
    }
}
?>