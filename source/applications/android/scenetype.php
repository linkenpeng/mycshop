<?php
defined('SYS_IN') or exit('Access Denied.');
class scenetype {
    private $scenetypedb;
    function __construct() {
        $this->scenetypedb = Base::load_model("scenetype_model");
    }
	
	public function getone() {
		$typeid = empty($_GET['typeid']) ? "" : intval($_GET['typeid']);
		$value = $this->scenetypedb->get_one($typeid);
		exit(json_encode($value));
	}
	
	public function getlist() {
        $where = " WHERE 1 ";
        //分页       
        Base::load_sys_class("page",'',0);
        $count = $this->scenetypedb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "100" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
		$list = array();
        $list = $this->scenetypedb->get_list($pagesize,$pagesize*($nowpage-1),
        		" typeid, parentid, name, image, description",$where,"dateline ASC ");
        
		exit(json_encode($list));
	}
	
	public function getcount() {
		$where = " WHERE 1 ";
		$count = $this->scenetypedb->get_count($where);
		exit(json_encode(array('c'=>$count)));
	}
}
?>