<?php
defined('SYS_IN') or exit('Access Denied.');

class product_model extends model {
    protected $_table = 'product';
    protected $_primarykey = 'productid';
    
    function __construct() {
        parent::__construct();
    }
}
?>