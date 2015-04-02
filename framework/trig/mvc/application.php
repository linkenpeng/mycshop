<?php

class trig_mvc_application {

	/**
	 * 构造函数
	 */
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
			if (preg_match('/^[_]/i', ROUTE_A)) {
				exit('You are visiting the action is to protect the private action');
			} else {
				call_user_func(array($controller, ROUTE_A));
			}
		} else {
			exit('Action does not exist.');
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
		if (empty($classname))
			$classname = ROUTE_C;
		if (empty($m))
			$m = ROUTE_M;
		
		$class = 'application_'.$m.'_'.$classname;
		
		return new $class();
	}
}