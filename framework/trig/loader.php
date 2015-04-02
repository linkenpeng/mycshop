<?php

class trig_loader {
	protected static $_autoload_paths = array();

	public function __construct($autoPaths) {
		self::$_autoload_paths = $autoPaths;
		spl_autoload_register(array($this,'autoLoad'));
	}

	public function autoLoad($class) {
		$path = strpos($class, FRAME_NAME.'_') !== false ? self::$_autoload_paths['frame'] : self::$_autoload_paths['app'];
    	$classPath = $path . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
    	//echo $classPath.'<br />';
    	return include $classPath;
	}
}