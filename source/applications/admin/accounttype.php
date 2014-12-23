<?php
defined('SYS_IN') or exit('Access Denied.');
class accounttype extends controller {
    private $accounttypedb;
    function __construct() {
		parent::__construct();
        $this->accounttypedb = Base::load_model("accounttype_model");
    }
    function init() {
        $where = " WHERE 1 ";
        //分页       
        
        $count = $this->accounttypedb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->accounttypedb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"dateline DESC ");
        $show_zone = 1;
        include admin_template('accounttype');
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
                'parentid'=>$_POST['parentid'],
                'uid'=>$_SESSION['admin_uid'],
                'username'=>$_SESSION['admin_username'],
                'name'=>$_POST['name'],
                'description'=>$_POST['description'],
                'dateline'=>$_POST['dateline']
            );
            if ($this->accounttypedb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("accounttype","init"));
            } else {
                ShowMsg(lang('message','insert_failure'),-1);
            }
        }
        $show_validator = 1;
        include admin_template('accounttypeform');
    }
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $actypeid = $_GET['actypeid'];
        if (!empty($actypeid)) {
            $value = $this->accounttypedb->get_one($actypeid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['actypeid'])) {
            $data = array(
                'name'=>$_POST['name'],
				'parentid'=>$_POST['parentid'],
                'description'=>$_POST['description']
            );
            if ($this->accounttypedb->update($data,"actypeid=".$_POST['actypeid'])) {
                ShowMsg(lang('message','update_success'),get_uri("accounttype","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
        $show_validator = 1;
        include admin_template('accounttypeform');
    }
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $actypeid = $_GET['actypeid'];
        if (!empty($actypeid)) {
            if ($this->accounttypedb->delete($actypeid)) {
                ShowMsg(lang('message','delete_success'),get_uri("accounttype","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
}
?>