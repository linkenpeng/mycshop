<?php

class trig_curl_request {

	public static function send($url, $params = array(), $method = 'get', $urlencode = true) {
		$method = empty($method) || !in_array($method, array('get','post')) ? 'get' : $method;
		if ('get' == $method && !empty($params)) {
			$url = strpos($url, '?') !== false ? $url . '&' : $url . '?';
			if (!empty($params)) {
				$data = $urlencode ? http_build_query($params) : self::http_build_query_def($params);
				$url .= $data;
			}
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		if ('post' == $method) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1); // tcp握手超时时间
		curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 缓冲区接收超时时间
		$output = curl_exec($ch);
		curl_close($ch);
		
		return $output;
	}
	
	// 自定义构建url参数函数，不进行urlencode操作
	public static function http_build_query_def($parameter) {
		$stringParams = '';
		if (!empty($parameter)) {
			$parameters = array();
			foreach ($parameter as $k => $v) {
				$parameters[] = $k . '=' . $v;
			}
			$stringParams = implode('&', $parameters);
		}
		
		return $stringParams;
	}
}
