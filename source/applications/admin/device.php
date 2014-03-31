<?php
defined('SYS_IN') or exit('Access Denied.');
class device {
    private $devicedb;
    function __construct() {
        //判断是否登录
        Base::load_model("login_model")->is_login();
        $this->devicedb = Base::load_model("device_model");
    }
	
    function init() {
		$brand = empty($_GET['brand']) ? '' : trim($_GET['brand']);		
        $where = " WHERE 1 ";
        if (!empty($brand)) {
            $where .= " and `brand` like '%".$brand."%' ";
        }
        //分页       
        Base::load_sys_class("page",'',0);
        $count = $this->devicedb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "50" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
		$field = " * ";
        //获取分页后的数据
        $list = $this->devicedb->get_list($pagesize,$pagesize*($nowpage-1),$field,$where," loginnum DESC");
		
        include admin_template('device');
    }
    
}
?>