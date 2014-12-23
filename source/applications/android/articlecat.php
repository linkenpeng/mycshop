<?php
defined('SYS_IN') or exit('Access Denied.');
class articlecat {
    private $articlecatdb;
    function __construct() {
        $this->articlecatdb = Base::load_model("articlecat_model");
    }
	
	public function getone() {
		$catid = empty($_GET['catid']) ? "" : intval($_GET['catid']);
		$value = $this->articlecatdb->get_one($catid);
		exit(json_encode($value));
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
        $pagesize = !isset($_GET['pagesize']) ? "100" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
		$list = array();
        $sourcelist = $this->articlecatdb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"ordernum DESC, dateline DESC ");
		/*
        $sourcelist = $this->articlecatdb->make_tree($sourcelist);		
		$this->articlecatdb->sort_tree($sourcelist, $list);		
		$list = array_merge($list, array());		
		print_r($list);
		*/
		exit(json_encode($sourcelist));
	}	
}
?>