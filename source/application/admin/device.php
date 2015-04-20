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
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 50));
		$field = " * ";
		// 获取分页后的数据
		$list = $this->devicedb->get_list($p->perpage, $p->offset, $field, $where, " loginnum DESC");
		
		include trig_mvc_template::admin_template('device');
	}
}