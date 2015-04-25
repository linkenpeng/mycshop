<?php

class trig_mvc_template {
	private $tpls = array();
	public static $title = ''; //页面标题
	public static $keywords = ''; //页面关键词
	public static $description = ''; //页面描述

	public function __construct() {
	}

	public static function view_file($view_name) {
		$view_file = TEMPLATE_PATH . DS . $view_name . '.php';
		return $view_file;
	}

	public static function render($view_file, $_data_ = null, $return = false) {
		if (is_array($_data_) && !empty($_data_)) {
			extract($_data_, EXTR_PREFIX_SAME, 'data');
		} else {
			$data = $_data_;
		}
		
		if ($return) {
			ob_start();
			ob_implicit_flush(false);
			require ($view_file);
			return ob_get_clean();
		} else {
			require ($view_file);
		}
	}	
}