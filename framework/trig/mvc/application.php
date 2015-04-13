<?php

class trig_mvc_application {
	
	public function __construct() {
		$route = new trig_mvc_route();
		define('ROUTE_M', $route->route_m());
		define('ROUTE_C', $route->route_c());
		define('ROUTE_A', $route->route_a());
		$this->init();
	}

	/**
	 * 调用方法
	 */
	private function init() {
		$controller = $this->load_controller();
		if (method_exists($controller, ROUTE_A)) {
			call_user_func(array($controller, ROUTE_A));
		} else {
			throw new trig_exception_system(1001);
		}
	}

	/**
	 * 加载控制器
	 * 
	 * @param string $classname        	
	 * @param string $m        	
	 * @return obj
	 */
	private function load_controller($classname = '', $m = '') {
		$classname = empty($classname) ? ROUTE_C : $classname;
		$m = empty($m) ? ROUTE_M : $m;
		
		if(!defined('APP_FOLDER')) {
			throw new trig_exception_system(1002);
		}
		
		$class = APP_FOLDER.'_'.$m.'_'.$classname;
		return new $class();
	}
}