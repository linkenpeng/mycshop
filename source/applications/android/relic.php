<?php
defined('SYS_IN') or exit('Access Denied.');
class relic {
    private $relicdb;
    function __construct() {
        $this->relicdb = Base::load_model("relic_model");
    }
	
	public function getone() {
		$relicid = empty($_GET['relicid']) ? "" : intval($_GET['relicid']);
		$value = $this->relicdb->get_one($relicid, "rel.relicid,rel.scenespotid,rel.relicname,
		rel.relicnum,rel.image,rel.description,rel.cn_audio,rel.en_audio,rel.sign_num");
		exit(json_encode($value));
	}
	
	public function getlist() {
		$keyword = empty($_GET['keyword']) ? '' : trim($_GET['keyword']);
        $where = " WHERE 1 ";
        if (!empty($keyword)) {
            $where .= " and rel.`relicname` LIKE '%".$keyword."%' ";
        }
        //分页       
        
        $count = $this->relicdb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "100" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        
        //获取分页后的数据
        $list = $this->relicdb->get_list($pagesize,$pagesize*($nowpage-1),"rel.relicid,rel.scenespotid,rel.relicname,
		rel.relicnum,rel.image,rel.description,rel.cn_audio,rel.en_audio,rel.sign_num",$where,"rel.relicid ASC ");
        
		exit(json_encode($list));
	}
	
	public function getinfobynum() {
		$relicnum = empty($_GET['relicnum']) ? "" : intval($_GET['relicnum']);
		$value = $this->relicdb->get_one_byparam(array('relicnum'=>$relicnum));
		exit(json_encode($value));
	}
}
?>