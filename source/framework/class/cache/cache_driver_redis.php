<?php

class cache_driver_redis implements cache_driver {
	private $_connections = array();
	private $_config = null;
	private $_counter = 0;
	private $_shardingNodes = 256;
	private $_nodeList = array();
	private $_openBatch = 0;
	private $_isMulti = array();
	private $_keyArr = array();
	private static $_keyLockPrefix = 'REDIS_KEY_LOCK_';
	private static $_server_key = '';
	const DEFAULT_HASH_KEY = 'hash';
	private static $_initShardingNodesSet = false;

	public function __construct($config) {
		if (!is_array($config) || empty($config)) {
		}
		
		$this->_config = $config;
		if (!empty($config['sharding_node'])) {
			$this->_shardingNodes = $config['sharding_node'];
		}
		
		if (!empty($config['lock_prefix'])) {
			self::$_keyLockPrefix = $config['lock_prefix'];
		}
	}

	public function __destruct() {
		if (!empty($this->_connections)) {
			$this->_connections = array();
		}
	}

	/**
	 * 连接服务器
	 *
	 * @param string $group        	
	 * @param string $hashKey
	 *        	分片key
	 *        	
	 * @throws Vipcore_Exception_ResourceException
	 * @throws Vipcore_Exception_SystemException
	 *
	 * @return mixed 成功返回redis连接句柄，失败返回void
	 */
	private function _connect($group, $hashKey = self::DEFAULT_HASH_KEY) {
		if (empty($group)) {
			return;
		}
		
		if (!class_exists('Redis')) {
			return;
		}
		
		if ($this->_isClusterSetting($group)) {
			
			$server = $this->_getShardingServerNode($group, $hashKey);
			if (empty($server)) {
				return;
			}
			
			if (empty($this->_connections[$group][$server['key']])) {
				$redis = new Redis();
				$result = $redis->connect($server['host'], $server['port'], $server['timeout']);
				if (false === $result) {
					return;
				}
				
				$this->_connections[$group][$server['key']] = $redis;
				
				if (isset($server['db']) && !empty($server['db'])) {
					$redis->select($server['db']);
				}
			}
			
			if ($this->_openBatch == 1) {
				
				$this->_keyArr[$server['key']][$this->_counter] = $hashKey; // 记录Key
				$this->_counter++; // 总计数器自增
				
				if (empty($this->_isMulti[$server['key']])) {
					$ret = $this->_connections[$group][$server['key']]->multi(Redis::PIPELINE);
					$this->_isMulti[$server['key']] = 1; // 标志此分片开启multi
				} else {
					$ret = $this->_connections[$group][$server['key']];
				}
				
				return $ret;
			}
			
			self::$_server_key = $server['key'];
			return $this->_connections[$group][$server['key']];
		} else {
			if (empty($this->_connections[$group])) {
				$redis = new Redis();
				$result = $redis->connect($this->_config[$group]['host'], $this->_config[$group]['port'], $this->_config[$group]['timeout']);
				$this->_connections[$group] = ($result) ? $redis : false;
				
				if (false === $this->_connections[$group]) {
					return;
				}
			}
			if ($this->_openBatch == 1) {
				return $this->_connections[$group]->multi(Redis::PIPELINE);
			}
			return $this->_connections[$group];
		}
	}

	/**
	 * 初始化分片配置
	 *
	 * @param string $group        	
	 *
	 * @return void
	 */
	private function _initShardingNodes($group) {
		if (self::$_initShardingNodesSet) {
			return;
		}
		
		$startCount = 0;
		$serverCounts = count($this->_config[$group]);
		if ($serverCounts > 0) {
			$step = floor($this->_shardingNodes / $serverCounts);
			foreach ($this->_config[$group] as $key => $value) {
				$startNum = $startCount * $step;
				$endNum = ($startCount < ($serverCounts - 1)) ? (($startCount + 1) * $step - 1) : $this->_shardingNodes - 1;
				$this->_config[$group][$key]['hashIdRangeFrom'] = $startNum;
				$this->_config[$group][$key]['hashIdRangeTo'] = $endNum;
				$startCount += 1;
			}
		}
		self::$_initShardingNodesSet = true;
	}

	/**
	 * 获取分片节点
	 *
	 * @param string $group        	
	 * @param string $hashKey
	 *        	分片key
	 *        	
	 * @return mixed 成功返回分片服务器配置，失败返回false
	 */
	private function _getShardingServerNode($group, $hashKey = self::DEFAULT_HASH_KEY) {
		if (empty($this->_config[$group])) {
			return false;
		}
		
		$this->_initShardingNodes($group);
		
		foreach ($this->_config[$group] as $key => $config) {
			if (empty($config) || (!isset($config['host']) || empty($config['host'])) || (!isset($config['port']) || empty($config['port'])) || (!isset($config['timeout']) || empty($config['timeout'])) || (!isset($config['hashIdRangeFrom'])) || (!isset($config['hashIdRangeTo']))) {
				return false;
			}
			
			$hashID = self::_getHashId($hashKey, $this->_shardingNodes);
			if ($hashID >= $config['hashIdRangeFrom'] && $hashID <= $config['hashIdRangeTo']) {
				$config['key'] = $key;
				return $config;
			}
		}
		
		// 如果找不到匹配的host,使用第一个host
		if ($this->_config[$group][0]) {
			$this->_config[$group][0]['key'] = 0;
			return $this->_config[$group][0];
		} else {
			return false;
		}
		
		return false;
	}

	/**
	 * 根据ID得到 hash 后 0～m-1 之间的值
	 *
	 * @param string $id        	
	 * @param int $m        	
	 * @return int
	 */
	private function _getHashId($key, $m = 256, $type = 'crc32') {
		// 把字符串K转换为 0～m-1 之间的一个值作为对应记录的散列地址
		if ($type == 'crc32') {
			return ((int) crc32($key)) & ($m - 1);
		} else {
			$k = md5($key);
			$l = strlen($k);
			$b = bin2hex($k);
			$h = 0;
			for($i = 0; $i < $l; $i++) {
				// 相加模式HASH
				$h += substr($b, $i * 2, 2);
			}
			
			$hash = ($h * 1) % $m;
			return $hash;
		}
	}

	/**
	 * 判断是否集群配置
	 * 集群配置：
	 * 'group_name' => array(
	 * 0 => array(
	 * 'host' => $_ENV['VIPBLACKLIST_REDIS_HOST_A'],
	 * 'port' => $_ENV['VIPBLACKLIST_REDIS_PORT_A'],
	 * 'timeout' => $_ENV['VIPBLACKLIST_REDIS_TIMEOUT_A'],
	 * 'db' => $_ENV['VIPBLACKLIST_REDIS_DB_A'],
	 * ),
	 * 1 => array(
	 * 'host' => $_ENV['VIPBLACKLIST_REDIS_HOST_B'],
	 * 'port' => $_ENV['VIPBLACKLIST_REDIS_PORT_B'],
	 * 'timeout' => $_ENV['VIPBLACKLIST_REDIS_TIMEOUT_B'],
	 * 'db' => $_ENV['VIPBLACKLIST_REDIS_DB_B'],
	 * ),
	 * ),
	 *
	 * 单机配置：
	 * 'group_name' => array(
	 * 'host' => $_ENV['VIPBLACKLIST_REDIS_HOST_A'],
	 * 'port' => $_ENV['VIPBLACKLIST_REDIS_PORT_A'],
	 * 'timeout' => $_ENV['VIPBLACKLIST_REDIS_TIMEOUT_A'],
	 * 'db' => $_ENV['VIPBLACKLIST_REDIS_DB_A'],
	 * ),
	 *
	 * @param array $group        	
	 *
	 * @return boolean 集群配置返回true，否则返回false
	 */
	private function _isClusterSetting($group) {
		$keys = array_keys($this->_config[$group]);
		foreach ($keys as $key) {
			if (!is_numeric($key)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * 关闭非集群连接
	 *
	 * @param string $group        	
	 *
	 * @return void
	 */
	public function disConnect($group) {
		if ($this->_isClusterSetting($group)) {
			foreach ($this->_connections[$group] as $conn) {
				if ($conn) {
					$conn->close();
				}
			}
		} else {
			if ($this->isConnected($group)) {
				$this->_connections[$group]->close();
			}
		}
	}

	/**
	 * 判断是否已连接
	 *
	 * @param string $group        	
	 * @param string $key        	
	 *
	 * @return boolean 已连接返回true，未连接返回false
	 */
	public function isConnected($group, $key = self::DEFAULT_HASH_KEY) {
		if ($this->_isClusterSetting($group)) {
			$server = $this->_getShardingServerNode($group, $key);
			if (!$server) {
				return false;
			}
			return !empty($this->_connections[$group][$server['key']]) ? true : false;
		} else {
			return !empty($this->_connections[$group]) ? true : false;
		}
	}

	/**
	 * 重新连接
	 *
	 * @param string $group        	
	 * @param string $key        	
	 *
	 * @return boolean 成功返回true，失败返回false
	 */
	public function reConnect($group, $key = self::DEFAULT_HASH_KEY) {
		return ($this->_connect($group, $key)) ? true : false;
	}

	/**
	 * 获取一个key
	 * 该方法支持存储互斥锁
	 *
	 * @param string $group        	
	 * @param string $key        	
	 *
	 * @return mixed
	 */
	public function get($group, $key) {
		$return = null;
		if ($this->_openBatch == 1) {
			$this->_connect($group, $key)->get($key);
		} else {
			$value = json_decode($this->_connect($group, $key)->get($key), true);
			if (isset($value['t']) && isset($value['v'])) {
				if ($value['t'] < time()) {
					if (true === $this->_connect($group, self::$_keyLockPrefix . $key)->setnx(self::$_keyLockPrefix . $key, '1')) {
						$this->setTimeout($group, self::$_keyLockPrefix . $key, 30);
						// 加锁成功返回空数据，不需要处理
					} else {
						// 加锁失败返回旧数据
						$return = $value['v'];
					}
				} else {
					// 数据未过期
					$return = $value['v'];
				}
			}
		}
		
		return $return;
	}

	/**
	 * 设置一个key
	 * 该方法支持存储互斥锁
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param string $value        	
	 * @param int $expire        	
	 *
	 * @return boolean true成功,false失败
	 */
	public function set($group, $key, $value, $expire = null) {
		if (!$expire) {
			$expire = 7200;
		}
		$expire = intval($expire);
		$value = array(
			'v' => $value,
			't' => time() + $expire 
		);
		$expire = (isset($expire)) ? $expire * 2 : 2592000; // 默认30天超时
		
		if ($this->_openBatch == 1) {
			$return = $this->_connect($group, $key)->setex($key, $expire, json_encode($value)); // 采用multi时选择setex方法
		} else {
			$return = $this->_connect($group, $key)->set($key, json_encode($value));
			if ($return) {
				$this->setTimeout($group, $key, $expire);
			}
		}
		return $return;
	}

	/**
	 * 删除一个key
	 *
	 * @param string $group        	
	 * @param string $key        	
	 *
	 * @return int 成功删除的数目
	 */
	public function delete($group, $key) {
		return $this->_connect($group, $key)->delete($key);
	}

	/**
	 * 设置单个不存在的key对应的值
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param mixed $value        	
	 * @param int $expire
	 *        	存活周期 默认一天 单位秒
	 *        	
	 * @return mixed [false失败已存在key|true成功]
	 */
	public function setKeyNotExist($group, $key, $value, $expire = null) {
		$return = $this->_connect($group, $key)->setnx($key, $value);
		if (true === $return) {
			if ($this->_openBatch == 1) {
				return $return;
			} else {
				$expire = (isset($expire)) ? intval($expire) : $this->_config[$group]['expire'];
				$this->setTimeout($group, $key, $expire);
			}
		}
		return $return;
	}

	/**
	 * 设置多个key对应的值
	 *
	 * @param string $group        	
	 * @param array $data
	 *        	key键值对
	 *        	
	 * @return boolean true成功,false失败
	 */
	public function multiSet($group, $data) {
		if (empty($data))
			return false;
		
		if ($this->_isClusterSetting($group)) {
			$nodes = array();
			foreach ($data as $key => $val) {
				$server = $this->_getShardingServerNode($group, $key);
				if (!$server) {
					return false;
				}
				
				$nodes[$server['host'] . $server['port'] . $server['db']][$key] = $val;
			}
			
			foreach ($nodes as $node) {
				if (!empty($node)) {
					$keys = array_keys($node);
					$this->_connect($group, $keys[0])->mSet($node);
				}
			}
			return true;
		} else {
			return $this->_connect($group)->mSet($data);
		}
	}

	/**
	 * 获取多个key对应的值
	 *
	 * @param string $group        	
	 * @param array $keys
	 *        	key键值
	 *        	
	 * @return array 查询结果
	 */
	public function multiGet($group, $keys) {
		if (empty($keys))
			return false;
		
		if ($this->_isClusterSetting($group)) {
			$res = array();
			$nodes = array();
			foreach ($keys as $key) {
				$server = $this->_getShardingServerNode($group, $key);
				if (!$server) {
					return false;
				}
				
				$nodes[$server['host'] . $server['port'] . $server['db']][] = $key;
				$res[$key] = null;
			}
			
			foreach ($nodes as $keys) {
				$values = $this->_connect($group, $keys[0])->mGet($keys);
				foreach ($keys as $k => $key) {
					$res[$key] = $values[$k];
				}
			}
			
			return $res;
		} else {
			return $this->_connect($group)->mGet($keys);
		}
	}

	/**
	 * 设置自增ID
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param int $increment
	 *        	自增量，默认为每次自增1，可传float，如:1.0
	 *        	
	 * @return int 自增后新值
	 */
	public function increment($group, $key, $increment = null) {
		if (empty($increment)) {
			return $this->_connect($group, $key)->incr($key);
		} elseif (is_float($increment)) {
			return $this->_connect($group, $key)->incrByFloat($key, $increment);
		} else {
			return $this->_connect($group, $key)->incrBy($key, $increment);
		}
	}

	/**
	 * 设置自减ID
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param int $decrement
	 *        	自减量，默认为每次自减1，可传float，如:1.0
	 *        	
	 * @return int 自减后新值
	 */
	public function decrement($group, $key, $decrement = null) {
		if (empty($decrement)) {
			return $this->_connect($group, $key)->decr($key);
		} elseif (is_float($decrement)) {
			return $this->_connect($group, $key)->incrByFloat($key, -$decrement);
		} else {
			return $this->_connect($group, $key)->decrBy($key, $decrement);
		}
	}

	/**
	 * 检测给定的key是否存在
	 *
	 * @param string $group        	
	 * @param string $keyword        	
	 *
	 * @return boolean true成功,false失败
	 */
	public function keyExists($group, $key) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->exists($key);
		} else {
			return false;
		}
	}

	/**
	 * 返回给定key的数据类型
	 *
	 * @param string $group        	
	 * @param array $keys        	
	 *
	 * @return int
	 */
	public function getDataType($group, $key) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->type($key);
		} else {
			return false;
		}
	}

	/**
	 * 设定key过期时间
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param int $expire        	
	 *
	 * @return boolean true成功,false失败
	 */
	public function setTimeout($group, $key, $expire) {
		if (!is_int($expire))
			return false;
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->setTimeout($key, $expire);
		} else {
			return false;
		}
	}

	/**
	 * 标记一个批量命令的开始（只支持pipeline）
	 *
	 * @return boolean true
	 */
	public function multi() {
		$this->_openBatch = 1;
		return true;
	}

	/**
	 * 执行所有批量命令
	 *
	 * @param string $group        	
	 *
	 * @return
	 *
	 */
	public function exec($group) {
		if ($this->_isClusterSetting($group)) {
			$resultArr = array();
			$ret = array();
			foreach ($this->_isMulti as $k => $v) {
				$resultArr[$k] = $this->_connections[$group][$k]->exec();
			}
			foreach ($resultArr as $k => $v) {
				$tmp_key = array_keys($this->_keyArr[$k]);
				$tmp_arr = array_combine($tmp_key, $v);
				$ret = $ret + $tmp_arr;
			}
			$this->_isMulti = array();
			$this->_keyArr = array();
			$this->_openBatch = 0;
		} else {
			$ret = $this->_connections[$group]->exec();
		}
		
		$this->_counter = 0;
		ksort($ret);
		foreach ($ret as $k => $v) {
			$tmp = json_decode($v, true); // 针对set方法的互斥锁来剥除多余的json格式封装
			if (is_array($tmp)) {
				$ret[$k] = $tmp['v'];
			}
		}
		
		return $ret;
	}
	
	// ///////////////////////// 队列部分 //////////////////////////////
	
	/**
	 * 队列头压栈
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param mixed $value        	
	 *
	 * @return mixed 成功返回新队列长度，失败返回false
	 */
	public function lPushList($group, $key, $value) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->lpush($key, $value);
		} else {
			return false;
		}
	}

	/**
	 * 队列尾压栈
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param mixed $value        	
	 *
	 * @return mixed 成功返回新队列长度，失败返回false
	 */
	public function rPushList($group, $key, $value) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->rpush($key, $value);
		} else {
			return false;
		}
	}

	/**
	 * 队列尾批量压栈
	 * [exsample]
	 * $redis->rMultiPushList('group_name', 'list_name', array(1, 2, 3));
	 * key 'list_name' now points to the following list: [ 1, 2, 3 ]
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param array $value
	 *        	批量压栈元素列表
	 *        	
	 * @return mixed 成功返回新队列长度，失败返回false
	 */
	public function rMultiPushList($group, $key, $value) {
		$redis = $this->_connect($group, $key);
		if ($redis && is_array($value) && !empty($value)) {
			return call_user_func_array(array(
				$redis,
				'rpush' 
			), array_merge(array(
				$key 
			), $value));
		} else {
			return false;
		}
	}

	/**
	 * 对已存在的key队列头压栈
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param string $value        	
	 *
	 * @return mixed 成功返回新队列长度，失败返回false
	 */
	public function lPushListIfExist($group, $key, $value) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->lPushx($key, $value);
		} else {
			return false;
		}
	}

	/**
	 * 对已存在的key队列尾压栈
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param string $value        	
	 *
	 * @return mixed 成功返回新队列长度，失败返回false
	 */
	public function rPushListIfExist($group, $key, $value) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->rPushx($key, $value);
		} else {
			return false;
		}
	}

	/**
	 * 队列头出栈
	 *
	 * @param string $group        	
	 * @param string $key        	
	 *
	 * @return mixed 成功返回出栈元素，失败返回false
	 */
	public function lPopList($group, $key) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->lPop($key);
		} else {
			return false;
		}
	}

	/**
	 * 队列尾出栈
	 *
	 * @param string $group        	
	 * @param string $key        	
	 *
	 * @return mixed 成功返回出栈元素，失败返回false
	 */
	public function rPopList($group, $key) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->rPop($key);
		} else {
			return false;
		}
	}

	/**
	 * 队列头带阻塞出栈
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param int $timeout
	 *        	阻塞超时时间
	 *        	
	 * @return mixed 成功返回出栈元素，失败返回false
	 */
	public function lBlockPopList($group, $key, $timeout = 10) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->blPop($key, $timeout);
		} else {
			return false;
		}
	}

	/**
	 * 队列尾带阻塞出栈
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param int $timeout
	 *        	阻塞超时时间
	 *        	
	 * @return mixed 成功返回出栈元素，失败返回false
	 */
	public function rBlockPopList($group, $key, $timeout = 10) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->brPop($key, $timeout);
		} else {
			return false;
		}
	}

	/**
	 * 获取队列长度
	 *
	 * @param string $group        	
	 * @param string $key        	
	 *
	 * @return mixed 成功返回新队列长度，失败返回false
	 */
	public function getListSize($group, $key) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->lSize($key);
		} else {
			return false;
		}
	}

	/**
	 * 获取单个队列元素
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param int $index        	
	 *
	 * @return mixed 成功返回元素值，失败返回false
	 */
	public function getListElement($group, $key, $index) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->lGet($key, $index);
		} else {
			return false;
		}
	}

	/**
	 * 更新单个队列元素
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param int $index        	
	 * @param mixed $value        	
	 *
	 * @return mixed 成功返回元素值，失败返回false
	 */
	public function setListElement($group, $key, $index, $value) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->lSet($key, $index, $value);
		} else {
			return false;
		}
	}

	/**
	 * 获取队列元素列表
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param int $start        	
	 * @param int $end        	
	 *
	 * @return array 元素列表
	 */
	public function getListRange($group, $key, $start, $end) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->lRange($key, $start, $end);
		} else {
			return false;
		}
	}

	/**
	 * 截取队列
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param int $start        	
	 * @param int $end        	
	 *
	 * @return mixed 成功返回新元素列表，失败返回false
	 */
	public function trimList($group, $key, $start, $end) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->lTrim($key, $start, $end);
		} else {
			return false;
		}
	}

	/**
	 * 队列指定基点中插入元素
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param int $position
	 *        	Redis::BEFORE | Redis::AFTER
	 * @param string $pivot        	
	 * @param mixed $value        	
	 *
	 * @return mixed 成功返回新队列元素个数，$pivot不存在返回-1
	 */
	public function insertListElemet($group, $key, $position, $pivot, $value) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->lInsert($key, $position, $pivot, $value);
		} else {
			return false;
		}
	}

	/**
	 * 删除队列指定元素
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param mixed $value        	
	 * @param int $count        	
	 *
	 * @return 成功返回被删除元素个数，失败返回false
	 */
	public function removeListElement($group, $key, $value, $count) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->lRem($key, $value, $count);
		} else {
			return false;
		}
	}
	
	// ///////////////////////// 系统部分 //////////////////////////////
	
	/**
	 * 选择数据库
	 *
	 * @param string $group        	
	 * @param string $db        	
	 * @param string $key
	 *        	计算key值所在内存分片
	 *        	
	 * @return boolean
	 */
	public function selectDb($group, $db, $key = self::DEFAULT_HASH_KEY) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->select($db);
		} else {
			return false;
		}
	}
	
	// ///////////////////////// 集合部分 //////////////////////////////
	
	/**
	 * 添加集合元素
	 *
	 * @param string $group        	
	 * @param string $setName
	 *        	集合名
	 * @param string $value        	
	 *
	 * @return long 成功添加元素数量
	 */
	public function addSetElement($group, $setName, $value) {
		$redis = $this->_connect($group);
		if ($redis) {
			return $redis->sAdd($setName, $value);
		} else {
			return false;
		}
	}

	/**
	 * 删除集合元素
	 *
	 * @param string $group        	
	 * @param string $setName
	 *        	集合名
	 * @param array $values        	
	 *
	 * @return long 成功删除元素数量
	 */
	public function delSetElement($group, $setName, $values) {
		$redis = $this->_connect($group);
		if ($redis) {
			return $redis->sRem($setName, $values);
		} else {
			return false;
		}
	}

	/**
	 * 判断是否集合元素
	 *
	 * @param string $group        	
	 * @param string $setName
	 *        	集合名
	 * @param string $value        	
	 *
	 * @return boolean 是集合成员返回true，不是返回false
	 */
	public function isSetMember($group, $setName, $value) {
		$redis = $this->_connect($group);
		if ($redis) {
			return $redis->sIsMember($setName, $value);
		} else {
			return false;
		}
	}

	/**
	 * 获取集合元素数量
	 *
	 * @param string $group        	
	 * @param string $setName
	 *        	集合名
	 *        	
	 * @return long 元素数量
	 */
	public function getSetSize($group, $setName) {
		$redis = $this->_connect($group);
		if ($redis) {
			return $redis->sCard($setName);
		} else {
			return false;
		}
	}

	/**
	 * 获取差集
	 *
	 * @param string $group        	
	 * @param array $setNames
	 *        	集合名列表
	 *        	
	 * @return array 差集元素
	 */
	public function diffSet($group, $setNames) {
		if (!is_array($setNames) || empty($setNames)) {
			return false;
		}
		
		$redis = $this->_connect($group);
		if ($redis) {
			return $redis->sDiff(array_values($setNames));
		} else {
			return false;
		}
	}

	/**
	 * 获取交集
	 *
	 * @param string $group        	
	 * @param array $keys
	 *        	集合名列表
	 *        	
	 * @return array 交集元素
	 */
	public function interSet($group, $setNames) {
		if (!is_array($setNames) || empty($setNames)) {
			return false;
		}
		
		$redis = $this->_connect($group);
		if ($redis) {
			return $redis->sInter(array_values($setNames));
		} else {
			return false;
		}
	}

	/**
	 * 获取并集
	 *
	 * @param string $group        	
	 * @param array $setNames
	 *        	集合名列表
	 *        	
	 * @return array 并集元素
	 */
	public function unionSet($group, $setNames) {
		if (!is_array($setNames) || empty($setNames)) {
			return false;
		}
		
		$redis = $this->_connect($group);
		if ($redis) {
			return $redis->sUnion(array_values($setNames));
		} else {
			return false;
		}
	}
	
	// ///////////////////////// 有序集合部分 //////////////////////////////
	
	/**
	 * 添加有序集合元素
	 *
	 * @param string $group        	
	 * @param string $setName
	 *        	集合名
	 * @param string $value        	
	 * @param double $score
	 *        	排序权值
	 *        	
	 * @return long 成功返回1,失败返回0
	 */
	public function addSortSetElement($group, $setName, $value, $score) {
		$redis = $this->_connect($group);
		if ($redis) {
			return $redis->zAdd($setName, $score, $value);
		} else {
			return false;
		}
	}

	/**
	 * 获取有序集合元素列表
	 *
	 * @param string $group        	
	 * @param string $setName
	 *        	集合名
	 * @param long $start        	
	 * @param long $end        	
	 * @param boolean $withScore
	 *        	是否返回排序权值
	 *        	
	 * @return array 元素列表，$withScore为true则同时返回排序权值
	 */
	public function getSortSetRange($group, $setName, $start, $end, $withScore = false) {
		$redis = $this->_connect($group);
		if ($redis) {
			return $redis->zRange($setName, $start, $end, $withScore);
		} else {
			return false;
		}
	}

	/**
	 * 获取有序集合元素score值
	 *
	 * @param string $group        	
	 * @param string $setName
	 *        	集合名
	 * @param string $value        	
	 *
	 * @return double 元素score值
	 */
	public function getSortSetScore($group, $setName, $value) {
		$redis = $this->_connect($group);
		if ($redis) {
			return $redis->zScore($setName, $value);
		} else {
			return false;
		}
	}
	
	// ///////////////////////// 哈希部分 //////////////////////////////
	
	/**
	 * 添加哈希值
	 *
	 * @param string $group
	 *        	分组名
	 * @param string $key
	 *        	哈希键
	 * @param string $hashKey
	 *        	哈希属性
	 * @param string $value
	 *        	哈希值
	 *        	
	 * @return mixed 成功返回1,已存在返回0,出错返回false
	 */
	public function setHash($group, $key, $hashKey, $value) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->hSet($key, $hashKey, $value);
		} else {
			return false;
		}
	}

	/**
	 * 获取哈希值
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param string $hashKey        	
	 *
	 * @return mixed 成功返回string,失败返回false
	 */
	public function getHash($group, $key, $hashKey) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->hGet($key, $hashKey);
		} else {
			return false;
		}
	}

	/**
	 * 删除哈希值
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param string $hashKey        	
	 *
	 * @return boolean 成功返回true,失败返回false
	 */
	public function delHash($group, $key, $hashKey) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			$res = $redis->hDel($key, $hashKey);
			return $res;
		} else {
			return false;
		}
	}

	/**
	 * 判断哈希值是否存在
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param string $hashKey        	
	 *
	 * @return boolean 存在返回true,不存在返回false
	 */
	public function hashExist($group, $key, $hashKey) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->hExists($key, $hashKey);
		} else {
			return false;
		}
	}

	/**
	 * 自增哈希值
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param string $hashKey        	
	 * @param int $increments        	
	 *
	 * @return long 自增后哈希值
	 */
	public function increaseHashValue($group, $key, $hashKey, $increments) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->hIncrBy($key, $hashKey, $increments);
		} else {
			return false;
		}
	}

	/**
	 * 批量添加哈希值
	 *
	 * @param string $group        	
	 * @param string $key        	
	 * @param array $data
	 *        	哈希键值对
	 *        	
	 * @return mixed 成功返回1,已存在返回0,出错返回false
	 */
	public function setHashSet($group, $key, $data) {
		if (!is_array($data)) {
			return false;
		}
		
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->hMset($key, $data);
		} else {
			return false;
		}
	}

	/**
	 * hash表获取多个val
	 *
	 * @param string $group        	
	 * @param string $hash        	
	 * @param array $hashKeys        	
	 *
	 * @return mixed 成功返回元素值，失败返回false
	 */
	public function getHashSet($group, $key, $hashKeys) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->hMGet($key, $hashKeys);
		} else {
			return false;
		}
	}

	/**
	 * 获取键下的所有哈希值
	 *
	 * @param string $group        	
	 * @param string $key        	
	 *
	 * @return mixed 成功返回array,失败返回false
	 */
	public function getHashAll($group, $key) {
		$redis = $this->_connect($group, $key);
		if ($redis) {
			return $redis->hGetAll($key);
		} else {
			return false;
		}
	}

	public function getLastServerKey() {
		return self::$_server_key;
	}
}