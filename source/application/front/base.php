<?php

class application_front_base extends trig_mvc_controller {
	protected $layout = 'layouts/main';
	public $menus = array();
	
	function __construct() {
		global $_CTCONFIG;
		// 输出页面字符集
		header('Content-type: text/html; charset=' . CHARSET);
		
		// 定义模板路径
		$template = 'templates' . DS . 'front' . DS . $_CTCONFIG['template'];
		define('TEMPLATE_URL', SITE_URL . DS . $template);
		define('TEMPLATE_PATH', ROOT_PATH . DS . $template);
	}
}