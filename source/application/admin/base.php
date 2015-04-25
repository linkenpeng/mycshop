<?php

class application_admin_base extends trig_mvc_controller {
	protected $layout = 'layout_main';
	public $menus = array();
	
	function __construct($check_login = true) {
		global $_CTCONFIG, $topmenus;
		// 输出页面字符集
		header('Content-type: text/html; charset=' . CHARSET);
		
		// 是否登录
		if ($check_login) {
			$login_model = new model_login();
			$login_model->is_login();
		}
		
		// 定义模板路径
		$admin_tpl = 'templates' . DS . 'admin' . DS . $_CTCONFIG['admin_template'];
		define('TEMPLATE_URL', SITE_URL . DS . $admin_tpl);
		define('TEMPLATE_PATH', ROOT_PATH . DS . $admin_tpl);
		// 定义管理员身份
		define('ADMIN_USER_TYPE', 1);
		
		// 开启session
		$session = new model_session();
		$session->start();		
		
		// 获取用户组对应的菜单id
		$usergroupdb = new model_usergroup();
		$menuids = $usergroupdb->get_permission();
		
		// 检查当前菜单是否被赋予该用户组
		$menudb = new model_menu();
		$menudb->check_permission($menuids);
		
		// 获得用户组分配的主菜单
		$check_modules = array('admin');
		$topmenus = $menudb->get_top_menus($menuids, $check_modules);
	}
}