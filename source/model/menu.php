<?php
defined('SYS_IN') or exit('Access Denied.');

class model_menu extends model_base {
    protected $_table = 'menu';
    protected $_primarykey = 'menuid';
    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * 将数组呈树状排列
     * @param array $datas
     * @return array $list
     */
    function make_tree_list($datas) {
        $list = array();
		foreach ($datas as $k=>$v) {
			$list[$v['menuid']] = $v;
		}
		foreach ($list as $k=>$v) {
			if($v['parentid']) {
				$list[$v['parentid']]['subs'][$v['menuid']] = &$list[$k]; 
				unset($list[$k]);
			}
		}
        return $list;
    }
	/**
     * 获取用户组对应的顶级菜单
     * @param String $menuids
     * @return array $menus
     */
	function get_top_menus($menuids="") {
		$list = array();
		if(!empty($menuids)) {
			$list = $this->get_all($field = 'menuid,parentid,level,model,ctrl,act,name,icon', "WHERE menuid IN($menuids) ","sort_order ASC");			
		}
		return $list;
	}
	/**
     * 检查用户组是否有权限操作当前菜单
     * @param String $menuids
     * @return array $menus
     */
	function check_permission($menuids="", $check_modules = array()) {
		$m = !empty($_GET[M]) ? $_GET[M] : '';
		$c = !empty($_GET[C]) ? $_GET[C] : '';
		$a = !empty($_GET[A]) ? $_GET[A] : '';
		
		if(!in_array($m, $check_modules)) return false;
		
		if(!empty($menuids)) {
			$menuids = explode(",",$menuids);
			$where = "WHERE model='".$m."' AND ctrl='".$c."' AND act='".$a."' ";			
            $sql = "select * from ".$this->tname($this->_table)." $where limit 0,1";
            $value = $this->db->get_one($sql);			
			if(!in_array($value['menuid'],$menuids)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message','no_permission'),-1);
			}
		}
	}
	
	/**
     * 更新排序
     * 
     */
    function update_sortorder($id, $order) {
        if (!empty($id)) {
            $sql = "UPDATE ".$this->tname($this->_table)." SET sort_order='$order' WHERE menuid='$id' ";
            $this->db->query($sql);
        }
    }
	
	public function get_up_menus($level) {
		$level = $level > 1 ? ($level - 1) : 1;
		$sql = "select * from ".$this->tname($this->_table)." WHERE level = $level";
		return $this->db->get_list($sql);
	}
	
	
}