<?php
defined('SYS_IN') or exit('Access Denied.');

class application_android_article {
	private $articledb;

	function __construct() {
		$this->articledb = new model_article();
	}

	public function getone() {
		$aid = empty($_GET['aid']) ? "" : intval($_GET['aid']);
		$value = $this->articledb->get_one($aid, "a.aid,a.sceneid,a.catid,a.title,a.image,a.content,a.dateline");
		exit(json_encode($value));
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
		$pagesize = !isset($_GET['pagesize']) ? "100" : $_GET['pagesize'];
		$nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$setarr = array(
			'total' => $count,
			'perpage' => $pagesize 
		);
		$p = new trig_page($setarr);
		// 获取分页后的数据
		$list = array();
		$list = $this->articledb->get_list($pagesize, $pagesize * ($nowpage - 1), " a.aid,a.sceneid,a.catid,a.title,a.image,a.dateline ", $where, "a.ordernum DESC ");
		
		exit(json_encode($list));
	}
}
?>