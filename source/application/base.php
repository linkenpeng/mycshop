<?php 
class application_base extends trig_mvc_controller {
	function __construct() {
		// 是否登录
		$login_model = new model_login();
		$login_model->is_login();
	}
}