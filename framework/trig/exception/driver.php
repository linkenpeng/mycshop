<?php

class trig_exception_driver extends Exception {
	protected $codeList = array();

	public function __construct($code, $message = '') {
		$line = $this->getLine();
		$fileName = $this->getFile();
		$errorCode = $this->codeList;
		$errMsg = isset($errorCode[$code]) ? $errorCode[$code] : 'undefined exception';
		$errMsg = $message ? $message : $errMsg;
		parent::__construct($errMsg, $code);
	}
}
