<?php
defined('SYS_IN') or exit('Access Denied.');
class notetype extends controller {
    private $notetypedb;
    function __construct() {
        parent::__construct();
        $this->notetypedb = Base::load_model("notetype_model");
    }
    function init() {
        $where = " WHERE 1 ";
        //分页       
        
        $count = $this->notetypedb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->notetypedb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"dateline DESC ");
        $show_zone = 1;
        include admin_template('notetype');
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
            if ($this->notetypedb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("notetype","init"));
            } else {
                ShowMsg(lang('message','insert_failure'),-1);
            }
        }
        $show_validator = 1;
        include admin_template('notetypeform');
    }
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $notetypeid = $_GET['notetypeid'];
        if (!empty($notetypeid)) {
            $value = $this->notetypedb->get_one($notetypeid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['notetypeid'])) {
            $data = array(
                'name'=>$_POST['name'],
                'description'=>$_POST['description']
            );
            if ($this->notetypedb->update($data,"notetypeid=".$_POST['notetypeid'])) {
                ShowMsg(lang('message','update_success'),get_uri("notetype","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
        $show_validator = 1;
        include admin_template('notetypeform');
    }
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $notetypeid = $_GET['notetypeid'];
        if (!empty($notetypeid)) {
            if ($this->notetypedb->delete($notetypeid)) {
                ShowMsg(lang('message','delete_success'),get_uri("notetype","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
}
?>