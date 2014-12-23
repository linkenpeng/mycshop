<?php
defined('SYS_IN') or exit('Access Denied.');
class menu extends controller {
    private $menudb;
    function __construct() {
        parent::__construct();
        $this->menudb = Base::load_model("menu_model");
    }
    function init() {
        $where = " WHERE 1 ";
        //分页       
        
        $count = $this->menudb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "100" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->menudb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"sort_order,ctrl ASC,menuid ASC ");
		$list = $this->menudb->make_tree_list($list);
		
        include admin_template('menu');
    }
    /**
     * 添加
     * 
     * @author Myron
     * 2011-5-27 上午11:57:59
     */
    public function add() {
        $value['parentid'] = empty($_GET['parentid']) ? '' : intval($_GET['parentid']);
		if (!empty($_POST['action'])) {
            if (empty($_POST['name'])) {
                ShowMsg("菜单名不能为空!",-1);
            }
            $_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
            $data = array(
                'parentid'=>$_POST['parentid'],
				'model'=>$_POST['model'],
                'ctrl'=>$_POST['ctrl'],
                'act'=>$_POST['act'],
				'name'=>$_POST['name'],
                'dateline'=>$_POST['dateline']
            );
            if ($this->menudb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("menu","init"));
            } else {
                ShowMsg(lang('message','insert_failure'),-1);
            }
        }
		$value_parent = $this->menudb->get_one($value['parentid']);
		$value['model'] = $value_parent['model'];
		$value['ctrl'] = $value_parent['ctrl'];
		$value['parent_name'] = $value_parent['name'];
        $show_validator = 1;
        include admin_template('menuform');
    }
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $menuid = $_GET['menuid'];
        if (!empty($menuid)) {
            $value = $this->menudb->get_one($menuid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['menuid'])) {
            $data = array(
                'parentid'=>$_POST['parentid'],
				'model'=>$_POST['model'],
                'ctrl'=>$_POST['ctrl'],
                'act'=>$_POST['act'],
				'name'=>$_POST['name']
            );
            if ($this->menudb->update($data,"menuid=".$_POST['menuid'])) {
                ShowMsg(lang('message','update_success'),get_uri("menu","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
        $show_validator = 1;
        include admin_template('menuform');
    }
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $menuid = $_GET['menuid'];
        if (!empty($menuid)) {
            if ($this->menudb->delete($menuid)) {
                ShowMsg(lang('message','delete_success'),get_uri("menu","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
	/**
     * 排序
     * 
     * @author Myron
     * 2011-8-15 21:13:34
     */
	function sortorder() {
		foreach($_POST as $k=>$v) {
			if (preg_match("/^orderid_(\d+)$/is",$k,$matches)) {
				$id = intval($matches[1]);
				$val = intval($v);
				if (!empty($id)) {
					$this->menudb->update_sortorder($id,$val);
				}
			}
		}
		ShowMsg(lang('message','update_status_success'),get_uri("menu","init"));
	}
}
?>