<?php

class cache_driver_memcache implements cache_driver {
	private $_connections = array();
	private $_config = null;
	const persistent = true;
	const weight = 10;
	const timeout = 1;
	const retryInterval = 15;

	public function __construct($config) {
		$this->_config = $config;
	}

	private function _connect($group) {
		if (empty($this->_connections[$group])) {
			$m = new Memcache();
			if (!empty($this->_config['compress_data'])) {
				$m->setCompressThreshold($this->_config['compress_min_size'], $this->_config['compress_level']);
			}
			// add servers
			$persistent = isset($this->_config[$group]['persistent']) && $this->_config[$group]['persistent'] ? $this->_config[$group]['persistent'] : self::persistent;
			$timeout = isset($this->_config[$group]['timeout']) && $this->_config[$group]['timeout'] ? $this->_config[$group]['timeout'] : self::timeout;
			$weight = isset($this->_config[$group]['weight']) && $this->_config[$group]['weight'] ? $this->_config[$group]['weight'] : self::weight;
			$retryInterval = isset($this->_config[$group]['retry_interval']) && $this->_config[$group]['retry_interval'] ? $this->_config[$group]['retry_interval'] : self::retryInterval;
			foreach ($this->_config[$group]['server'] as $server) {
				$m->addServer($server['host'], $server['port'], $persistent, $weight, $timeout, $retryInterval);
			}
			$this->_connections[$group] = $m;
		}
		
		return $this->_connections[$group];
	}

	private function addServer($serverConfig) {
		
	}

	public function get($group, $key) {
		return $this->_connect($group)->get($key);
	}

	public function set($group, $key, $val, $expire = null) {
		$expire = (isset($expire)) ? intval($expire) : $this->_config[$group]['expire'];
		return $this->_connect($group)->set($key, $val, false, $expire);
	}

	public function delete($group, $key) {
		return $this->_connect($group)->delete($key);
	}

	public function status($group) {
		return $this->_connect($group)->getExtendedStats();
	}

	public function __destruct() {
		if (!empty($this->_connections)) {
			$this->_connections = array();
		}
	}
}