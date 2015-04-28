<?php
defined('SYS_IN') or exit('Access Denied.');

class application_mobile_article extends application_mobile_base {
	private $articledb;

	function __construct() {
		$this->articledb = new model_article();
	}

	public function getone() {
		$aid = empty($_GET['aid']) ? "" : intval($_GET['aid']);
		$value = $this->articledb->get_one($aid, "a.aid,a.sceneid,a.catid,a.title,a.image,a.content,a.dateline");
		trig_helper_html::json_success($value);
	}

	public function getlist() {
		$catid = empty($_GET['catid']) ? "" : intval($_GET['catid']);
		$sceneid = empty($_GET['sceneid']) ? "" : intval($_GET['sceneid']);
		$where = " WHERE 1 ";
		if (!empty($catid)) {
			$where .= " and a.`catid`=" . $catid;
		}
		if (!empty($sceneid)) {
			$where .= " and a.`sceneid`=" . $sceneid;
		}
		// 分页
		$count = $this->articledb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 100));
		// 获取分页后的数据
		$list = array();
		$list = $this->articledb->get_list($p->perpage, $p->offset, " a.aid,a.sceneid,a.catid,a.title,a.image,a.dateline ", $where, "a.ordernum DESC ");
		trig_helper_html::json_success($list);
	}
}
?>