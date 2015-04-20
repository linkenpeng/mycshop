<?php

class trig_log {
	private static $_size = null;
	private static $_path = null;
	private static $_is_log = false;

	public static function info($message, $file_name = '') {
		self::write('info', $message, $file_name);
	}

	public static function error($message, $file_name = '') {
		self::write('error', $message, $file_name);
	}

	private static function write($type, $message, $file_name = '') {
		global $_G;
		self::$_size = $_G['log']['size'] * 1048576; // MB
		self::$_path = $_G['log']['path'];
		self::$_is_log = $_G['log']['is_log'];
		
		if (self::$_is_log) {
			is_array($message) ? $message = implode('', $message) : $message;
			$message = '[' . strtoupper($type) . '][' . date('Y-m-d H:i:s') . '][message:' . $message . "]\r\n";
			
			$dir = self::$_path . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . date('Ymd') . DIRECTORY_SEPARATOR;
			trig_func_common::make_dirs($dir);
			
			$file_name = $dir . (empty($file_name) ? $type : $file_name . '.' . $type) . '.log';
			
			if (is_file($file_name) && filesize($file_name) >= (self::$_size)) {
				rename($file_name, dirname($file_name) . '/' . basename($file_name) . '.bak' . date('H'));
			}
			
			file_put_contents($file_name, $message, FILE_APPEND);
		}
	}
}
