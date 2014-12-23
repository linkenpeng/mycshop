<?php
defined('SYS_IN') or exit('Access Denied.');
class usergroup extends controller {
    private $usergroupdb;
    function __construct() {
        parent::__construct();
        $this->usergroupdb = Base::load_model("usergroup_model");
    }
    function init() {
        $where = " WHERE 1 ";
        //分页        
        $count = $this->usergroupdb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->usergroupdb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"uid ASC ");
        $show_zone = 1;
        include admin_template('usergroup');
    }
    /**
     * 添加
     * 
     * @author Myron
     * 2011-5-27 上午11:57:59
     */
    public function add() {
        if (!empty($_POST['action'])) {
            if (empty($_POST['name'])) {
                ShowMsg("分类名不能为空!",-1);
            }
            $_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
            $data = array(
                'uid'=>$_SESSION['admin_uid'],
                'username'=>$_SESSION['admin_username'],
                'name'=>$_POST['name'],
                'description'=>$_POST['description'],
                'dateline'=>$_POST['dateline']
            );
            if ($this->usergroupdb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("usergroup","init"));
            } else {
                ShowMsg(lang('message','insert_failure'),-1);
            }
        }
        $show_validator = 1;
        include admin_template('usergroupform');
    }
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $ugid = $_GET['ugid'];
        if (!empty($ugid)) {
            $value = $this->usergroupdb->get_one($ugid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['ugid'])) {
            $data = array(
                'name'=>$_POST['name'],
                'description'=>$_POST['description']
            );
            if ($this->usergroupdb->update($data,"ugid=".$_POST['ugid'])) {
                ShowMsg(lang('message','update_success'),get_uri("usergroup","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
        $show_validator = 1;
        include admin_template('usergroupform');
    }
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $ugid = $_GET['ugid'];
        if (!empty($ugid)) {
            if ($this->usergroupdb->delete($ugid)) {
                ShowMsg(lang('message','delete_success'),get_uri("usergroup","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
	/**
     * 权限管理
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
	public function permission() {
		if (!empty($_POST['action'])&&!empty($_POST['ugid'])) {
			if(!empty($_POST['menuid'])) {
				$permission = implode(",",$_POST['menuid']);
			}
			$data = array(
                'permission'=>$permission
            );
            if ($this->usergroupdb->update($data,"ugid=".$_POST['ugid'])) {
                ShowMsg("权限设置成功!",get_uri("usergroup","init"));
            } else {
                ShowMsg("权限设置失败!",-1);
            }
		}
		$ugid = $_GET['ugid'];
		if (!empty($ugid)) {
			//获取用户组信息
			$value = $this->usergroupdb->get_one($ugid);
			$permissions = array();
			if(!empty($value['permission'])) {
				$permissions = explode(",",$value['permission']);
			}
			//获取菜单信息
			$where = '';
			$menudb = Base::load_model("menu_model");
			$list = $menudb->get_list(1000,0," * ",$where,"sort_order,ctrl ASC,menuid ASC ");
			$list = $menudb->make_tree_list($list);
            include admin_template('usergroup_permission');
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
	}
}
?>