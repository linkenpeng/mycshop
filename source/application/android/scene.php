<?php
defined('SYS_IN') or exit('Access Denied.');
class application_android_scene extends application_android_base {
    private $scenedb;
    function __construct() {
        $this->scenedb = new model_scene();
    }
	
	public function getone() {
		$sceneid = empty($_GET['sceneid']) ? "" : intval($_GET['sceneid']);
		$value = $this->scenedb->get_one($sceneid);
		trig_helper_html::json_success($value);
	}
	
	public function getlist() {
		$where = " WHERE 1 ";
		$typeid = empty($_GET['typeid']) ? "" : intval($_GET['typeid']);
		$city = empty($_GET['city']) ? '' : trim($_GET['city']);
        $where = " WHERE 1 ";
        if (!empty($typeid)) {
            $where .= " and s.`typeid`=".$typeid;
        }
		if (!empty($city)) {
            $where .= " and s.`city`='".$city."' ";
        }
        //分页        
        $count = $this->scenedb->get_count_withtype($where);
        $p = new trig_page(array('total_count' => $count,'default_page_size' => 100));
        //获取分页后的数据
        $list = $this->scenedb->get_list_withtype($p->perpage, $p->offset,"",$where,"s.scenenum ASC ");
        trig_helper_html::json_success($list);
	}
	
	public function getwithtype() {
		$sceneid = empty($_GET['sceneid']) ? "" : intval($_GET['sceneid']);
		$value = $this->scenedb->get_one_byparam(array('sceneid'=>$sceneid));
		trig_helper_html::json_success($value);
	}
	
	public function getinfobynum() {
		$scenenum = empty($_GET['scenenum']) ? "" : trim($_GET['scenenum']);
		$value = $this->scenedb->get_one_byparam(array('scenenum'=>$scenenum));
		trig_helper_html::json_success($value);
	}
	
	public function qiandao() {
		
	}
}
?>