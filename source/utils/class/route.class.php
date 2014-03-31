<?php
defined('SYS_IN') or exit('Access Denied.');
/**
 * route.class.php 路由分发类
 *
 */
class route {
    //路由配置
    private $route_config = '';
    
    public function __construct() {
        $this->route_config = Base::load_config('route');
    }
    /**
     * 获取模型
     */
    public function route_m() {
        $m = isset($_GET[M])&&!empty($_GET[M]) ? $_GET[M] : (isset($_POST[M])&&!empty($_POST[M]) ? $_POST[M] : '');
        //允许的模块
        $array_m = Base::load_config('module');
        $m = in_array($m,$array_m) ? $m : "admin";
        if (empty($m)) {
            return $this->route_config[M];
        } else {
            return $m;
        }
    }
    
    /**
     * 获取控制器
     */
    public function route_c() {
        $c = isset($_GET[C])&&!empty($_GET[C]) ? $_GET[C] : (isset($_POST[C])&&!empty($_POST[C]) ? $_POST[C] : 'index');
        if (empty($c)) {
            return $this->route_config[C];
        } else {
            return $c;
        }
    }
    
    /**
     * 获取事件
     */
    public function route_a() {
        $a = isset($_GET[A])&&!empty($_GET[A]) ? $_GET[A] : (isset($_POST[A])&&!empty($_POST[A]) ? $_POST[A] : 'init');
        if (empty($a)) {
            return $this->route_config[A];
        } else {
            return $a;
        }
    }
}