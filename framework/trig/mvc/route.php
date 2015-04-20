<?php

/**
 * 路由分发类
 *
 */
class trig_mvc_route {
	// 路由配置
	private $_config = '';
	private $_mconfig = '';

	public function __construct() {
		global $_G;
		$this->_config = $_G['route'];
		$this->_mconfig = $_G['module'];
	}

	/**
	 * 获取模型
	 */
	public function route_m() {
		$m = isset($_GET[M]) && !empty($_GET[M]) ? $_GET[M] : (isset($_POST[M]) && !empty($_POST[M]) ? $_POST[M] : '');
		// 允许的模块
		$m = in_array($m, $this->_mconfig) ? $m : "admin";
		if (empty($m)) {
			return $this->_config[M];
		} else {
			return $m;
		}
	}

	/**
	 * 获取控制器
	 */
	public function route_c() {
		$c = isset($_GET[C]) && !empty($_GET[C]) ? $_GET[C] : (isset($_POST[C]) && !empty($_POST[C]) ? $_POST[C] : 'index');
		if (empty($c)) {
			return $this->_config[C];
		} else {
			return $c;
		}
	}

	/**
	 * 获取事件
	 */
	public function route_a() {
		$a = isset($_GET[A]) && !empty($_GET[A]) ? $_GET[A] : (isset($_POST[A]) && !empty($_POST[A]) ? $_POST[A] : 'init');
		if (empty($a)) {
			return $this->_config[A];
		} else {
			return $a;
		}
	}
	
	// 获取路由
	public static function get_uri($c = '', $a = '', $m = '', $extra = '') {
		global $_G;
		$route_config = $_G['route'];
		$c = !empty($c) ? $c : (!empty($_GET[C]) ? $_GET[C] : $route_config[C]);
		$a = !empty($a) ? $a : (!empty($_GET[A]) ? $_GET[A] : '');
		$m = !empty($m) ? $m : (!empty($_GET[M]) ? $_GET[M] : $route_config[M]);
		$route = ROUTE . '?' . M . '=' . $m;
		if (!empty($c)) {
			$route .= '&' . C . '=' . $c;
		}
		if (!empty($a)) {
			$route .= '&' . A . '=' . $a;
		}
		if (!empty($extra)) {
			$route .= '&' . (is_array($extra) ? http_build_query($extra) : $extra);
		}
		return $route;
	}
}