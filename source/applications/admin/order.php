<?php
defined('SYS_IN') or exit('Access Denied.');
class order extends controller {
    private $orderdb;
    function __construct() {
        parent::__construct();
        $this->orderdb = Base::load_model("order_model");
    }
    function init() {
        $where = " WHERE 1 ";
        //分页       
        
        $count = $this->orderdb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->orderdb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"dateline DESC ");
        include admin_template('order');
    }
    /**
     * 添加
     * 
     * @author Myron
     * 2011-5-27 上午11:57:59
     */
    public function add() {
        if (!empty($_POST['action'])) {
            if (empty($_POST['ordername'])) {
                ShowMsg("订单名称不能为空!",-1);
            }
            $_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
            $data = array(
                'uid'=>$_SESSION['admin_uid'],
                'username'=>$title['admin_username'],
                'ordername'=>$_POST['ordername'],
                'description'=>$_POST['description'],
                'dateline'=>$_POST['dateline']
            );
            if ($this->orderdb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("order","init"));
            } else {
                ShowMsg(lang('message','insert_failure'),-1);
            }
        }
        $show_validator = 1;
        include admin_template('orderform');
    }
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $orderid = $_GET['orderid'];
        if (!empty($orderid)) {
            $value = $this->orderdb->get_one($orderid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['orderid'])) {
            $data = array(
                'ordername'=>$_POST['ordername'],
                'description'=>$_POST['description']
            );
            if ($this->orderdb->update($data,"orderid=".$_POST['orderid'])) {
                ShowMsg(lang('message','update_success'),get_uri("order","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
        $show_validator = 1;
        include admin_template('orderform');
    }
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $orderid = $_GET['orderid'];
        if (!empty($orderid)) {
            if ($this->orderdb->delete($orderid)) {
                ShowMsg(lang('message','delete_success'),get_uri("order","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
}
?>