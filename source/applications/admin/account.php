<?php
defined('SYS_IN') or exit('Access Denied.');
class account extends controller {
    private $accountdb;
    function __construct() {
    	parent::__construct();
        $this->accountdb = Base::load_model("account_model");
    }
    
    function init() {
        $where = " WHERE 1 ";
        //分页               
        $count = $this->accountdb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->accountdb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"dateline DESC ");
        //分类
        $accounttypedb = Base::load_model("accounttype_model");
        $actype_list = $accounttypedb->get_list(100,0," actypeid,name ","","actypeid ASC ");
        foreach($actype_list as $k=>$v) {
            $accounttype_list[$v['actypeid']] = $v['name'];
        }
		include admin_template('account');
    }
    
    public function add() {
        if (!empty($_POST['action'])) {
            if (empty($_POST['accountname'])) {
                ShowMsg("名称不能为空!",-1);
            }
            $_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
            $data = array(
                'uid'=>$_SESSION['admin_uid'],
                'username'=>$_SESSION['admin_username'],
				'actypeid'=>$_POST['actypeid'],
                'accountname'=>$_POST['accountname'],
                'description'=>$_POST['description'],
                'dateline'=>$_POST['dateline']
            );
            if ($this->accountdb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("account","init"));
            } else {
                ShowMsg(lang('message','insert_failure'),-1);
            }
        }
		//分类
        $accounttypedb = Base::load_model("accounttype_model");
        $actype_list = $accounttypedb->get_list(100,0," actypeid,name ","","actypeid ASC ");
        $show_validator = 1;
        include admin_template('accountform');
    }
    
    public function edit() {
        $accountid = $_GET['accountid'];
        if (!empty($accountid)) {
            $value = $this->accountdb->get_one($accountid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['accountid'])) {
            $data = array(
                'accountname'=>$_POST['accountname'],
				'actypeid'=>$_POST['actypeid'],
                'description'=>$_POST['description']
            );
            if ($this->accountdb->update($data,"accountid=".$_POST['accountid'])) {
                ShowMsg(lang('message','update_success'),get_uri("account","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
		//分类
        $accounttypedb = Base::load_model("accounttype_model");
        $actype_list = $accounttypedb->get_list(100,0," actypeid,name ","","actypeid ASC ");
        $show_validator = 1;
        include admin_template('accountform');
    }
    
    public function delete() {
        $accountid = $_GET['accountid'];
        if (!empty($accountid)) {
            if ($this->accountdb->delete($accountid)) {
                ShowMsg(lang('message','delete_success'),get_uri("account","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
}
?>