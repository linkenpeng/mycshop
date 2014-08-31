<?php
defined('SYS_IN') or exit('Access Denied.');
Base::load_sys_class('model');
class articlecat_model extends model {
    private $table = "article_category";
    function __construct() {
        parent::__construct();
    }
    /**
     * 
     * 获取一条信息
     * @param int $catid
     */
    function get_one($catid = "") {
        if(!empty($catid)) {
			$where = "where catid=".$catid;
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
    function delete($catid) {
        $flag = false;
        $sql = "DELETE FROM ".tname($this->table)." WHERE catid=".$catid;
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
    function get_list($num = 10, $offset, $field = '', $where = '', $oderbye = ' dateline DESC') {
        $num = intval($num);
        $offset = (empty($offset)||$offset<0) ? 0 : intval($offset);
        $field = empty($field) ? ' * ' : $field;
        $where = empty($where) ? ' WHERE 1 ' : $where;
        $oderbye = ' ORDER BY '.$oderbye;
		$limit = empty($num) ? "" : " LIMIT $offset,$num";		
        $sql = "SELECT ".$field." FROM ".tname($this->table).$where.$oderbye.$limit;
        $list = $this->db->get_list($sql);
        return $list;
    }
	
	/**
     * 将数组呈树状排列
     * @param array $datas
     * @return array $list
     */
    function make_tree($datas) {
        $list = array();
		foreach ($datas as $k=>$v) {
			$list[$v['catid']] = $v;
		}
		$list = $this->recur_tree($list);
        return $list;
    }
	
	/**
     * 递归组合树
     * @return array $list
     */
	private function recur_tree($list) {
		$un = array();
		foreach ($list as $k=>$v) {
			if(!empty($v['upid'])) {
				$list[$v['upid']]['subs'][$v['catid']] = &$list[$k]; 		
				$un[] = $k;
			}
		}
		
		foreach($un as $v) {
			unset($list[$v]);
		}
		
		return $list;
	}
	
	/**
     * 递归展平树
     * @return array $list
     */	
	public function sort_tree($sourcelist, &$tolist, $depth = 0) {
		foreach ($sourcelist as $k => $value) {
			$tolist[$k]['catid'] = $value['catid'];
			$tolist[$k]['cattype'] = $value['cattype'];
			$tolist[$k]['sceneid'] = $value['sceneid'];
			$tolist[$k]['upid'] = $value['upid'];
			$tolist[$k]['name'] = $value['name'];
			$tolist[$k]['image'] = $value['image'];
			$tolist[$k]['description'] = $value['description'];
			$tolist[$k]['ordernum'] = $value['ordernum'];
			$tolist[$k]['dateline'] = $value['dateline'];
			$tolist[$k]['depth'] = $depth;
			if(!empty($value['subs'])) {
				$this->sort_tree($value['subs'], $tolist, $depth + 1);
			}
		}
	}
	
	/**
     * 得到文章分类
     * 
     * @author Myron
     * 2012-5-6
     */
	public function get_categories() {
		$articlecat_list = array();
        $sourcelist = $this->get_list(0, 0, " * ", "", "dateline ASC ");
		$sourcelist = $this->make_tree($sourcelist);
		$this->sort_tree($sourcelist, $articlecat_list);
		return $articlecat_list;
	}
	
	/**
     * 递归组合下拉选项
     * 
     * @author Myron
     * 2012-5-6 上午10:36:34
     */
	public function recur_options($selectedid = 0 ,$noid = 0 , $catlist = array()) {
		$articlecat_list = empty($catlist) ? $this->get_categories() : $catlist;
		if(!empty($noid)) {
			unset($articlecat_list[$noid]);
		}
		$options = '';
		foreach ($articlecat_list as $value) {
			$depthsymbol = '';
			for($i = 0; $i < $value['depth']; $i++) {
				$depthsymbol .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			$selected = '';
			if($selectedid == $value['catid']) {
				$selected = ' selected="selected" ';
			}
			$options .= '<option value="'.$value['catid'].'" '.$selected.' >'.$depthsymbol.$value['name'].'</option>';
		}
		return $options;
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
}
?>