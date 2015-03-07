<?php

interface cache_driver {

	public function set($group, $key, $val, $expire = null);

	public function get($group, $key);

	public function delete($group, $key);
}