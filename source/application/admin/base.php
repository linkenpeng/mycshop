<?php

class application_admin_base extends trig_mvc_controller {
	protected $layout = 'layout_main';
	
	function __construct($check_login = true) {
		global $_CTCONFIG;
		
		// 是否登录
		if ($check_login) {
			$login_model = new model_login();
			$login_model->is_login();
		}
		
		// 定义模板路径
		$admin_tpl = 'templates' . DS . 'admin' . DS . $_CTCONFIG['admin_template'];
		define('TEMPLATE_URL', SITE_URL . DS . $admin_tpl);
		define('TEMPLATE_PATH', ROOT_PATH . DS . $admin_tpl);
	}
}