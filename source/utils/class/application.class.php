<?php
defined('SYS_IN') or exit('Access Denied.');
/**
 * application.class.php 控制器基类
 *
 */
class application {
    /**
     * 构造函数
     */
    public function __construct() {
        $route = Base::load_sys_class('route');
        define('ROUTE_M',$route->route_m());
        define('ROUTE_C',$route->route_c());
        define('ROUTE_A',$route->route_a());
        $this->init();
    }
    /**
     * 调用件事
     */
    private function init() {
        $controller = $this->load_controller();
        if (method_exists($controller,ROUTE_A)) {
            if (preg_match('/^[_]/i',ROUTE_A)) {
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
     * @param string $classname
     * @param string $m
     * @return obj
     */
    private function load_controller($classname = '', $m = '') {
        if (empty($classname))
        	$classname = ROUTE_C;
        if (empty($m))
            $m = ROUTE_M;
        
        $filepath = FRAME_PATH.DS.'applications'.DS.$m.DS.$classname.'.php';
        
        if (file_exists($filepath)) {
            include $filepath;
            return new $classname();
        } else {
            exit('Controller does not exist.');
        }
    }
}