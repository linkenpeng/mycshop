<?php
defined('SYS_IN') or exit('Access Denied.');
class application_mobile_articlecat extends application_mobile_base {
    private $articlecatdb;
    function __construct() {
        $this->articlecatdb = new model_articlecat();
    }
	
	public function getone() {
		$catid = empty($_GET['catid']) ? "" : intval($_GET['catid']);
		$value = $this->articlecatdb->get_one($catid);
		trig_helper_html::json_success($value);
	}
	
	public function getlist() {
		$cattype = empty($_GET['cattype']) ? "" : intval($_GET['cattype']);
		$sceneid = empty($_GET['sceneid']) ? "" : intval($_GET['sceneid']);
        $where = " WHERE upid!='0' ";
        if (!empty($cattype)) {
            $where .= " and `cattype`=".$cattype;
        }
		if (!empty($sceneid)) {
            $where .= " and `sceneid`=".$sceneid;
        }
        //分页               
        $count = $this->articlecatdb->get_count($where);
        $p = new trig_page(array('total_count' => $count,'default_page_size' => 100));
        //获取分页后的数据
		$list = array();
        $sourcelist = $this->articlecatdb->get_list($p->perpage, $p->offset," * ",$where,"ordernum DESC, dateline DESC ");
        trig_helper_html::json_success($sourcelist);
	}	
}
?>