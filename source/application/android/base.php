<?php

class application_android_base extends trig_mvc_controller {
	
	function __construct() {
		// 输出页面字符集
		header('Content-type: application/json; charset=' . CHARSET);
	}
}