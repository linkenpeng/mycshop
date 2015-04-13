<?php

class trig_mvc_controller {
	private $_vars = array();
	
	public function assign($variable, $value = null) {
		$this->_vars[$variable] = $value;
	}
	
	public function display($view, $variable = array()) {
		$template_file = trig_mvc_template::include_template(view);
		if(!file_exists($template_file)) {
			throw new trig_exception_system(1003);
		}
		if(!empty($variable)) {
			foreach($variable as $var => $value) {
				$this->_vars[$var] = $value;
			}
		}
		empty($this->_vars) || extract($this->_vars);
		include $template_file;
	}
}