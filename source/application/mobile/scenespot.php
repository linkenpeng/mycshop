<?php
defined('SYS_IN') or exit('Access Denied.');
class application_mobile_scenespot extends application_mobile_base {
    private $scenespotdb;
    function __construct() {
        $this->scenespotdb = new model_scenespot();
    }
	
	public function getone() {
		$scenespotid = empty($_GET['scenespotid']) ? "" : intval($_GET['scenespotid']);
		$value = $this->scenespotdb->get_one($scenespotid);
		trig_helper_html::json_success($value);
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
        $p = new trig_page(array('total_count' => $count,'default_page_size' => 100));
        $field = "sp.scenespotid,sp.parent_scenespotid,sp.sceneid,sp.scenespotname,sp.scenespot_enname,sp.infocards,
        sp.image,sp.description, sp.cn_audio, sp.en_audio, sp.sign_num,s.scenename,s.typeid,s.traveltopicid,s.level";
        //获取分页后的数据
        $list = $this->scenespotdb->get_list($p->perpage, $p->offset,$field,$where,"sp.infocards ASC ");
        
		foreach($list as $k=>$v) {
			$list[$k]['description'] = trig_func_common::cn_substr($v['description'], 36);
		}
		
		trig_helper_html::json_success($list);
	}

	
	public function getinfobynum() {
		$infocards = empty($_GET['infocards']) ? "" : trim($_GET['infocards']);
		$value = $this->scenespotdb->get_one_byparam(array('infocards'=>$infocards));
		trig_helper_html::json_success($value);
	}
	
	public function getinfobyid() {
		$scenespotid = empty($_GET['scenespotid']) ? "" : intval($_GET['scenespotid']);
		$value = $this->scenespotdb->get_one($scenespotid);
		trig_helper_html::json_success($value);
	}
	
}
?>