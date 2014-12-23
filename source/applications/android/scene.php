<?php
defined('SYS_IN') or exit('Access Denied.');
class scene {
    private $scenedb;
    function __construct() {
        $this->scenedb = Base::load_model("scene_model");
    }
	
	public function getone() {
		$sceneid = empty($_GET['sceneid']) ? "" : intval($_GET['sceneid']);
		$value = $this->scenedb->get_one($sceneid);
		exit(json_encode($value));
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
        $pagesize = !isset($_GET['pagesize']) ? "100" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->scenedb->get_list_withtype($pagesize,$pagesize*($nowpage-1),"",$where,"s.scenenum ASC ");
        
		exit(json_encode($list));
	}
	
	public function getwithtype() {
		$sceneid = empty($_GET['sceneid']) ? "" : intval($_GET['sceneid']);
		$value = $this->scenedb->get_one_byparam(array('sceneid'=>$sceneid));
		exit(json_encode($value));
	}
	
	public function getinfobynum() {
		$scenenum = empty($_GET['scenenum']) ? "" : trim($_GET['scenenum']);
		$value = $this->scenedb->get_one_byparam(array('scenenum'=>$scenenum));
		exit(json_encode($value));
	}
	
	public function qiandao() {
		
	}
}
?>