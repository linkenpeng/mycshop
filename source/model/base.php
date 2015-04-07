<?php
defined('SYS_IN') or exit('Access Denied.');

class model_base extends trig_mvc_model {
	
    function __construct() {
    	global $_G;
        parent::__construct($_G['db']['master']);
    }

}