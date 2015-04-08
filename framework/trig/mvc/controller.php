<?php

class trig_mvc_controller {
	public function display($view, $data) {
		foreach ($data as $key => $val) {
			$$key = $val;
		}
		require trig_mvc_template::include_template(view);
	}
}