<?php
defined('SYS_IN') or exit('Access Denied.');

class model_system extends model_base {
    protected $_table = 'system';
    protected $_primarykey = 'sid';
    protected $_cache_file = '';
    
    function __construct() {
        parent::__construct();
        $this->_cache_file = ROOT_PATH.DS.'caches'.DS.'customize_config.php';
    }
    
    function cache() {
    	$flag = false;
    	$list = $this->get_all();
    	$configs = array();
    	foreach($list as $val) {
    		$configs[$val['config_key']] = $val['config_value'];
    	}
    	$cache_content = "<?php\ndefined('SYS_IN') or exit('Access Denied.');\n";
    	$cache_content .= "return ".var_export($configs, true).";";
    	if(file_put_contents($this->_cache_file, $cache_content)) {
    		$flag = true;
    	}
    	return $flag; 	
    }
}