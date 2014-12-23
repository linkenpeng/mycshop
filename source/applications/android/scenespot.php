<?php
defined('SYS_IN') or exit('Access Denied.');
class scenespot {
    private $scenespotdb;
    function __construct() {
        $this->scenespotdb = Base::load_model("scenespot_model");
    }
	
	public function getone() {
		$scenespotid = empty($_GET['scenespotid']) ? "" : intval($_GET['scenespotid']);
		$value = $this->scenespotdb->get_one($scenespotid);
		exit(json_encode($value));
	}
	
	public function getlist() {
		$keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
		$sceneid = empty($_GET['sceneid']) ? '' : intval($_GET['sceneid']);
        $where = " WHERE 1 ";
        if (!empty($keyword)) {
            $where .= " and sp.`scenespotname` like '%".$keyword."%' ";
        }
		 if (!empty($sceneid)) {
            $where .= " and sp.`sceneid`=".$sceneid." ";
        }
        //分页       
        
        $count = $this->scenespotdb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "100" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        $field = "sp.scenespotid,sp.parent_scenespotid,sp.sceneid,sp.scenespotname,sp.scenespot_enname,sp.infocards,
        sp.image,sp.description, sp.cn_audio, sp.en_audio, sp.sign_num,s.scenename,s.typeid,s.traveltopicid,s.level";
        //获取分页后的数据
        $list = $this->scenespotdb->get_list($pagesize,$pagesize*($nowpage-1),$field,$where,"sp.infocards ASC ");
        
		foreach($list as $k=>$v) {
			$list[$k]['description'] = cn_substr($v['description'], 36);
		}
		
		exit(json_encode($list));
	}

	
	public function getinfobynum() {
		$infocards = empty($_GET['infocards']) ? "" : trim($_GET['infocards']);
		$value = $this->scenespotdb->get_one_byparam(array('infocards'=>$infocards));
		exit(json_encode($value));
	}
	
	public function getinfobyid() {
		$scenespotid = empty($_GET['scenespotid']) ? "" : intval($_GET['scenespotid']);
		$value = $this->scenespotdb->get_one($scenespotid);
		exit(json_encode($value));
	}
	
}
?>