<?php

class trig_mvc_controller {
	private $_vars = array();
	protected $layout = '';
	
	public function assign($variable, $value = null) {
		$this->_vars[$variable] = $value;
	}
	
	public function display($view, $variable = array()) {
		$view_file = trig_mvc_template::view_file($view);
		if(!file_exists($view_file)) {
			throw new trig_exception_system(1003);
		}
		
		if(!empty($variable)) {
			foreach($variable as $var => $value) {
				$this->_vars[$var] = $value;
			}
		}
		
		echo trig_mvc_template::render($view_file, $this->_vars, true);
	}
}