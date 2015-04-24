<?php

class trig_http_file {

	public function __construct($input_file) {
		return array(
			'name' => $input_file['name'],
			'type' => $input_file['type'],
			'tmp_name' => $input_file['tmp_name'],
			'error' => $input_file['error'],
			'size' => $input_file['size'],
		);
	}
}
