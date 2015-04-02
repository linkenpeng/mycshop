<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_device extends application_base {
	private $devicedb;

	function __construct() {
		parent::__construct();
		$this->devicedb = new model_device();
	}

	function init() {
		$brand = empty($_GET['brand']) ? '' : trim($_GET['brand']);
		$where = " WHERE 1 ";
		if (!empty($brand)) {
			$where .= " and `brand` like '%" . $brand . "%' ";
		}
		// 分页		
		$count = $this->devicedb->get_count($where);
		$pagesize = !isset($_GET['pagesize']) ? "50" : $_GET['pagesize'];
		$nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$setarr = array(
			'total' => $count,
			'perpage' => $pagesize 
		);
		$p = new trig_page($setarr);
		$field = " * ";
		// 获取分页后的数据
		$list = $this->devicedb->get_list($pagesize, $pagesize * ($nowpage - 1), $field, $where, " loginnum DESC");
		
		include trig_func_common::admin_template('device');
	}
}