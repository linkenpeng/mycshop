<?php
defined('SYS_IN') or exit('Access Denied.');
class application_mobile_scenetype extends application_mobile_base {
    private $scenetypedb;
    function __construct() {
        $this->scenetypedb = new model_scenetype();
    }
	
	public function getone() {
		$typeid = empty($_GET['typeid']) ? "" : intval($_GET['typeid']);
		$value = $this->scenetypedb->get_one($typeid);
		trig_helper_html::json_success($value);
	}
	
	public function getlist() {
        $where = " WHERE 1 ";
        //分页        
        $count = $this->scenetypedb->get_count($where);
        $p = new trig_page(array('total_count' => $count,'default_page_size' => 100));
        //获取分页后的数据
		$list = array();
        $list = $this->scenetypedb->get_list($p->perpage, $p->offset," typeid, parentid, name, image, description",$where,"dateline ASC ");
        trig_helper_html::json_success($list);
	}
	
	public function getcount() {
		$where = " WHERE 1 ";
		$count = $this->scenetypedb->get_count($where);
		trig_helper_html::json_success(array('c'=>$count));
	}
}
?>