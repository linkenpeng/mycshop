<?php

class trig_cache {
	private static $_memcache = null;
	private static $_redis = null;
	public static $config = array(
		'memcache' => array(),
		'redis' => array() 
	);

	static public function factory($adapter = 'memcache', $config = array()) {
		$adapterClassName = 'trig_cache_' . $adapter;
		if (!empty($adapter)) {
			if (class_exists($adapterClassName)) {
				return self::getInstance($adapter);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	static private function getInstance($adapter) {
		switch ($adapter) {
			case 'memcache':
				if (empty(self::$_memcache)) {
					self::$_memcache = new trig_cache_memcache(self::$config['memcache']);
				}
				return self::$_memcache;
				break;
			case 'redis':
				if (empty(self::$_redis)) {
					self::$_redis = new trig_cache_redis(self::$config['redis']);
				}
				return self::$_redis;
				break;
			default:
				return false;
				break;
		}
	}
}
