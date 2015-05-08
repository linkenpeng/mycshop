<?php

class trig_mvc_controller {
	private $_vars = array();
	protected $layout = '';

	public function assign($variable, $value = null) {
		$this->_vars[$variable] = $value;
	}

	public function display($view, $variable = array()) {
		$view_file = trig_mvc_template::view_file($view);
		if (!file_exists($view_file)) {
			throw new trig_exception_system(1003);
		}
		
		if (!empty($variable)) {
			foreach ($variable as $var => $value) {
				$this->_vars[$var] = $value;
			}
		}
		
		if (!empty($this->layout)) {
			$layout_file = trig_mvc_template::view_file($this->layout);
			$content = trig_mvc_template::render($view_file, $this->_vars, true);
			echo trig_mvc_template::render($layout_file, array_merge($this->_vars, array('content' => $content)), true);
		} else {
			echo trig_mvc_template::render($view_file, $this->_vars, true);
		}
	}

	/**
	 * JSON格式化输出
	 *
	 * @param
	 *        	$data
	 * @param string $contentType        	
	 */
	public function renderJSON($data, $contentType = 'application/json') {
		$json = CJSON::encode($data);
		if (isset($_GET["jsonp"])) {
			header('Content-type: application/x-javascript');
			$json = sprintf("%s(%s)", $_GET["jsonp"], $json);
		} else {
			header("Content-type: $contentType");
		}
		echo $json;
		Yii::app()->end();
	}

	/**
	 * XML格式化输出
	 *
	 * @param
	 *        	$data
	 * @param string $contentType        	
	 */
	public function renderXML($data, $contentType = 'application/xml') {
		header("Content-type: $contentType");
		$xml = new SimpleXMLElement('<?xml version="1.0"?><root/>');
		$this->arrayToXml($data, $xml);
		echo $xml->asXML();
		Yii::app()->end();
	}

	private function arrayToXml($data, SimpleXMLElement &$xml) {
		foreach ($data as $key => $value) {
			if (is_array($value) || is_object($value)) {
				if (!is_numeric($key)) {
					$subNode = $xml->addChild((string) $key);
					$this->arrayToXml($value, $subNode);
				} else {
					$this->arrayToXml($value, $xml);
				}
			} else {
				$xml->addChild($key, $value);
			}
		}
	}

	/**
	 * 输出Api的错误信息
	 *
	 * @param
	 *        	$code
	 * @param
	 *        	$error
	 * @param array $data        	
	 */
	public function renderApiError($code, $error = '', $data = array()) {
		$format = strtolower(trig_http_request::getParameter('format', 'json'));
		$data = array(
			'code' => $code,
			'msg' => empty($error) ? trig_error_code::getErrorMsg($code) : $error,
			'data' => $data 
		);
		if ($format === 'xml') {
			$this->renderXML($data);
		} else {
			$this->renderJSON($data);
		}
	}

	/**
	 * 输出Api的返回值
	 *
	 * @param
	 *        	$data
	 * @param string $msg        	
	 */
	public function renderApi($data, $msg = 'success') {
		$format = strtolower(trig_http_request::getParameter('format', 'json'));
		$result = array(
			'code' => 200,
			'msg' => $msg,
			'data' => $data 
		);
		if ($format === 'xml') {
			$this->renderXML($result);
		} else {
			$this->renderJSON($result);
		}
	}
}