<?php
defined('SYS_IN') or exit('Access Denied.');
class application_android_relic extends application_android_base {
    private $relicdb;
    function __construct() {
        $this->relicdb = new model_relic();
    }
	
	public function getone() {
		$relicid = empty($_GET['relicid']) ? "" : intval($_GET['relicid']);
		$value = $this->relicdb->get_one($relicid, "rel.relicid,rel.scenespotid,rel.relicname,
		rel.relicnum,rel.image,rel.description,rel.cn_audio,rel.en_audio,rel.sign_num");
		trig_helper_html::json_success($value);
	}
	
	public function getlist() {
		$keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
        $where = " WHERE 1 ";
        if (!empty($keyword)) {
            $where .= " and rel.`relicname` LIKE '%".$keyword."%' ";
        }
        //分页       
        
        $count = $this->relicdb->get_count($where);
        $p = new trig_page(array('total_count' => $count,'default_page_size' => 100));
        
        //获取分页后的数据
        $list = $this->relicdb->get_list($p->perpage, $p->offset,"rel.relicid,rel.scenespotid,rel.relicname,
		rel.relicnum,rel.image,rel.description,rel.cn_audio,rel.en_audio,rel.sign_num",$where,"rel.relicid ASC ");
        trig_helper_html::json_success($list);
	}
	
	public function getinfobynum() {
		$relicnum = empty($_GET['relicnum']) ? "" : intval($_GET['relicnum']);
		$value = $this->relicdb->get_one_byparam(array('relicnum'=>$relicnum));
		trig_helper_html::json_success($value);
	}
}
?>