<?php

class trig_uri {

	public static function isAjax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	public static function getServerPort() {
		return $_SERVER['SERVER_PORT'];
	}

	public static function getServerName() {
		return $_SERVER['SERVER_NAME'];
	}

	public static function getRemoteAddress() {
		return $_SERVER['REMOTE_ADDR'];
	}

	public static function getUri() {
		return $_SERVER["REQUEST_URI"];
	}

	public static function getMethod() {
		return $_SERVER["REQUEST_METHOD"];
	}

	public static function getHostName() {
		$result = self::getServerName();
		$port = self::getServerPort();
		if ($port != '80') {
			return $result . ':' . $port;
		}
		return $result;
	}

	public static function isGet() {
		return self::getMethod() == 'GET';
	}

	public static function isPost() {
		return self::getMethod() == 'POST';
	}

	public static function getAllParameters() {
		return $_REQUEST;
	}

	public static function hasParameter($name) {
		if (array_key_exists($name, $_REQUEST)) {
			return TRUE;
		}
		return FALSE;
	}

	public static function getParameter($name, $default = '') {
		return self::getFrom($_REQUEST, $name, $default);
	}

	public static function getMultiParameter() {
		$result = array();
		$args = func_get_args();
		foreach ($args as $name) {
			$result = self::getParameter($name);
		}
		return $result;
	}

	public static function getAllForm() {
		return $_POST;
	}

	public static function getForm($name, $default = '') {
		return self::getFrom($_POST, $name, $default);
	}

	public static function getMultiForm() {
		$result = array();
		$args = func_get_args();
		foreach ($args as $name) {
			$result = self::getForm($name);
		}
		return $result;
	}

	public static function getAllQuery() {
		return $_GET;
	}

	public static function getQuery($name, $default = '') {
		return self::getFrom($_GET, $name, $default);
	}

	public static function getMultiQuery() {
		$result = array();
		$args = func_get_args();
		foreach ($args as $name) {
			$result = self::getQuery($name);
		}
		return $result;
	}
	
	public static function getAllSession() {
		return $_SESSION;
	}

	public static function getSession($name, $default = '') {
		return self::getFrom($_SESSION, $name, $default);
	}

	public static function getMultiSession() {
		$result = array();
		$args = func_get_args();
		foreach ($args as $name) {
			$result = self::getSession($name);
		}
		return $result;
	}
	
	public static function getAllCookie() {
		return $_COOKIE;
	}

	public static function getCookie($name, $default = '') {
		return self::getFrom($_COOKIE, $name, $default);
	}

	public static function getMultiCookie() {
		$result = array();
		$args = func_get_args();
		foreach ($args as $name) {
			$result = self::getCookie($name);
		}
		return $result;
	}

	public static function getFile($name) {
		if (!array_key_exists($name, $_FILES)) {
			return NULL;
		}
		
		if (!is_array($_FILES[$name]['name'])) {
			return new Soul_Web_Http_File($_FILES[$name]);
		}
		
		$result = array();
		$arr = $_FILES[$name];
		if (!empty($arr['name'][0])) {
			for($i = 0; $i < count($arr['name']); $i++) {
				if (!empty($arr['name'][$i])) {
					$result[] = new Soul_Web_Http_File(array(
						'name' => $arr['name'][$i],
						'type' => $arr['type'][$i],
						'tmp_name' => $arr['tmp_name'][$i],
						'error' => $arr['error'][$i],
						'size' => $arr['size'][$i] 
					));
				}
			}
		}
		return $result;
	}
	
	// 站点链接
	public static function getsiteurl() {
		$uri = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : ($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
		return 'http://' . $_SERVER['HTTP_HOST'] . substr($uri, 0, strrpos($uri, '/'));
	}

	private static function getFrom($source, $name, $default = '') {
		if (isset($source[$name]) && !empty($source[$name])) {
			return $source[$name];
		}
		return $default;
	}
}
