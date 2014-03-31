<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class menu_model extends model {
    private $table = "menu";
    function __construct() {
        parent::__construct();
    }
    /**
     * 
     * 获取一条信息
     * @param int $menuid
     */
    function get_one($menuid = "") {
        if (!empty($menuid)) {
            $where = "where menuid=".$menuid;
            $sql = "select * from ".tname($this->table)." $where limit 0,1";
            $value = $this->db->get_one($sql);
            return $value;
        } else {
            return '';
        }
    }
    /**
     * 
     * 插入一条信息
     * @param array $data
     * return Boolean $flag
     */
    function insert($data) {
        $flag = false;
        if ($this->db->insert(tname($this->table),$data)) {
            $flag = true;
        }
        return $flag;
    }
    /**
     * 修改一条信息
     * 
     * @param array $data
     * @param string $where
     * @author Myron
     * 2011-5-27 上午10:18:10
     */
    function update($data, $where) {
        $flag = false;
        if (!empty($data)&&!empty($where)) {
            $this->db->update(tname($this->table),$data,$where);
            $flag = true;
        }
        return $flag;
    }
    /**
     * 
     * 删除一条信息
     * @param array $data
     * return Boolean $flag
     */
    function delete($menuid) {
        $flag = false;
        $sql = "DELETE FROM ".tname($this->table)." WHERE menuid=".$menuid;
        if ($this->db->query($sql)) {
            $flag = true;
        }
        return $flag;
    }
    /**
     * 获取一组信息
     * @param int $num
     * @param int $offset
     * @param string $field
     * @param stirng $where
     * @param string $orderby
     * reunt @param array $list
     */
    function get_list($num = 10, $offset, $field = '', $where = '', $oderbye = '') {
        $num = empty($num) ? 10 : intval($num);
        $offset = (empty($offset)||$offset<0) ? 0 : intval($offset);
        $field = empty($field) ? ' * ' : $field;
        $where = empty($where) ? ' WHERE 1 ' : $where;
        $oderbye = empty($oderbye) ? ' ORDER BY dateline DESC ' : ' ORDER BY '.$oderbye;
        $sql = "SELECT ".$field." FROM ".tname($this->table).$where.$oderbye." LIMIT $offset,$num ";
        //echo $sql;
        $list = $this->db->get_list($sql);
        return $list;
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
			$menuids = explode(",",$menuids);
			$list = $this->get_list($num = 100,0, $field = 'menuid,model,ctrl,act,name', "WHERE parentid=0 ","sort_order ASC");
			foreach ($list as $k=>$v) {
				if(!in_array($v['menuid'],$menuids)) {
					unset($list[$k]);
				}
			}
		}
		return $list;
	}
	/**
     * 检查用户组是否有权限操作当前菜单
     * @param String $menuids
     * @return array $menus
     */
	function check_permission($menuids="", $check_modules = array()) {
		if(!in_array($_GET[M], $check_modules)) return false;
		
		if(!empty($menuids)) {
			$menuids = explode(",",$menuids);
			$where = "WHERE model='".$_GET[M]."' AND ctrl='".$_GET[C]."' AND act='".$_GET[A]."' ";			
            $sql = "select * from ".tname($this->table)." $where limit 0,1";
            $value = $this->db->get_one($sql);			
			if(!in_array($value['menuid'],$menuids)) {
				ShowMsg(lang('message','no_permission'),-1);
			}
		}
	}
    /**
     * 获取总数
     * @param stirng $where
     * return @param int $count
     */
    function get_count($where = '') {
        $where = empty($where) ? ' WHERE 1 ' : $where;
        $sql = "SELECT COUNT(*) as c FROM ".tname($this->table).$where;
        $value = $this->db->get_one($sql);
        return $value['c'];
    }
	
	/**
     * 更新排序
     * 
     */
    function update_sortorder($id, $order) {
        if (!empty($id)) {
            $sql = "UPDATE ".tname($this->table)." SET sort_order='$order' WHERE menuid='$id' ";
            $this->db->query($sql);
        }
    }
}
?>