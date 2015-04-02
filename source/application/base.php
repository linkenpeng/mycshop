<?php 
class application_base extends trig_mvc_controller {
	function __construct() {
		$login_model = new model_login();
		$login_model->is_login();
	}
}