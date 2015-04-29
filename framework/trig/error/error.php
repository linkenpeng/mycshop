<?php

class trig_error_code {
	const SUCCESS = 1; // 操作成功
	const ERR_SYTEM = 100; // 系统错误
	const ERR_PARAM = 101; // 参数错误
	const ERR_EMPTY_RESULT = 102; // 请求接口无返回
	const ERR_INVALID_PARAMETER = 400; // 请求参数错误
	const ERR_CHECK_SIGN = 401; // 签名验证错误
	const ERR_NO_PARAMETERS = 402; // 参数缺失
	const ERR_SERVICE_FORBBIDEN = 403; // 方法禁止访问
	const ERR_SERVICE_NOT_FOUND = 404; // 方法未找到
	const ERR_VER_NOTEXISTS = 405; // 版本号错误
	const ERR_DB_ERROR = 406; // 数据库操作错误
	const ERR_UNKNOWN_TYPE = 407; // 未知类型错误
	const ERR_FOREIGN_HTTP_CODE = 408; // 状态码错误
	private static $_messages = array(
		1 => '操作成功',
		100 => '系统错误',
		101 => '参数错误',
		102 => '请求接口无返回',
		400 => ' 请求参数错误',
		401 => ' 签名验证错误',
		402 => ' 签名验证错误',
		403 => ' 参数缺失',
		404 => ' 方法未找到',
		405 => ' 版本号错误',
		406 => ' 数据库操作错误',
		407 => '未知类型错误',
		408 => '状态码错误' 
	);

	public static function errorMsg($errorCode, $msg = '', $data = '') {
		$response['code'] = $errorCode;
		
		if ($msg != '') {
			$response['error'] = $msg;
		} else {
			$response['error'] = self::getErrorMsg($errorCode);
		}
		if ($data != '') {
			$response['data'] = $data;
		}
		
		return $response;
	}

	public static function getErrorMsg($errorCode) {
		return (isset(self::$_messages[$errorCode])) ? self::$_messages[$errorCode] : '';
	}
}
