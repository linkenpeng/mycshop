<?php
defined('SYS_IN') or exit('Access Denied.');
class product extends controller {
    private $productdb;
    function __construct() {
        parent::__construct();
        $this->productdb = Base::load_model("product_model");
    }
    function init() {
        $where = " WHERE 1 ";
		$typeid = empty($_GET['typeid']) ? "" : intval($_GET['typeid']);
		$model = empty($_GET['model']) ? '' : trim($_GET['model']);
		$barcode = empty($_GET['barcode']) ? "" : trim($_GET['barcode']);
        $where = " WHERE 1 ";
        if (!empty($typeid)) {
            $where .= " and typeid=".$typeid;
        }
        if (!empty($model)) {
            $where .= " and `model` like '%".$model."%' ";
        }
		if (!empty($barcode)) {
            $where .= " and `barcode` like '%".$barcode."%' ";
        }
        //分页       
        
        $count = $this->productdb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
        $list = $this->productdb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"dateline DESC ");
        //分类
        $producttypedb = Base::load_model("producttype_model");
        $pt_list = $producttypedb->get_list(100,0," typeid,name ","","typeid ASC ");
        foreach($pt_list as $k=>$v) {
            $producttype_list[$v['typeid']] = $v['name'];
        }
        include admin_template('product');
    }
    /**
     * 添加
     * 
     * @author Myron
     * 2011-5-27 上午11:57:59
     */
    public function add() {
        if (!empty($_POST['action'])) {
            if (empty($_POST['model'])||empty($_POST['model'])) {
                ShowMsg("产品型号不能为空!",-1);
            }
            $_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
            if (!empty($_FILES['image']['name'])) {
                Base::load_sys_class("upfile",'',0);
                $upfile = new upfile(UPLOAD_FILE_TYPES);
                $upfile->savesamll = 1;
                $image = $upfile->upload($_FILES['image']);
            }
            $data = array(
                'uid'=>$_SESSION['admin_uid'],
                'username'=>$_SESSION['admin_username'],
                'typeid'=>$_POST['typeid'],
                'model'=>$_POST['model'],
                'image'=>$image,
                'mcolor'=>$_POST['mcolor'],
                'mstyle'=>$_POST['mstyle'],
                'bmethod'=>$_POST['bmethod'],
                'bsize'=>$_POST['bsize'],
				'barcode'=>$_POST['barcode'],
                'volumn'=>$_POST['volumn'],
                'weight'=>$_POST['weight'],
                'price'=>$_POST['price'],
                'barcode'=>$_POST['barcode'],
                'description'=>$_POST['description'],
                'dateline'=>$_POST['dateline']
            );
            if ($this->productdb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("product","init"));
            } else {
                ShowMsg(lang('message','insert_failure'),-1);
            }
        }
        $show_validator = 1;
        $show_editor = 1;
        $producttypedb = Base::load_model("producttype_model");
        $producttype_list = $producttypedb->get_list(100,0," typeid,name ",$where,"typeid ASC ");
        include admin_template('productform');
    }
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $productid = $_GET['productid'];
        if (!empty($productid)) {
            $value = $this->productdb->get_one($productid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['productid'])) {
            if (!empty($_FILES['image']['name'])) {
                Base::load_sys_class("upfile",'',0);
                $upfile = new upfile(UPLOAD_FILE_TYPES);
                $upfile->savesamll = 1;
                $image = $upfile->upload($_FILES['image']);
            }
            $data = array(
                'typeid'=>$_POST['typeid'],
                'model'=>$_POST['model'],
                'mcolor'=>$_POST['mcolor'],
                'mstyle'=>$_POST['mstyle'],
                'bmethod'=>$_POST['bmethod'],
                'bsize'=>$_POST['bsize'],
				'barcode'=>$_POST['barcode'],
                'volumn'=>$_POST['volumn'],
                'weight'=>$_POST['weight'],
                'price'=>$_POST['price'],
                'barcode'=>$_POST['barcode'],
                'description'=>$_POST['description']
            );
            if (!empty($image)) {
                $data['image'] = $image;
                //删除老图片
                if($image!=$_POST['oldimage']) {
                    @unlink(UPLOAD_PATH.'/'.$_POST['oldimage']);
                    @unlink(UPLOAD_PATH.'/thumb/'.$_POST['oldimage']);
                }
            }
            if ($this->productdb->update($data,"productid=".$_POST['productid'])) {
                ShowMsg(lang('message','update_success'),get_uri("product","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
        $producttypedb = Base::load_model("producttype_model");
        $producttype_list = $producttypedb->get_list(100,0," typeid,name ",$where,"typeid ASC ");
        $show_validator = 1;
        $show_editor = 1;
        include admin_template('productform');
    }
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $productid = $_GET['productid'];
        if (!empty($productid)) {
            if ($this->productdb->delete($productid)) {
                ShowMsg(lang('message','delete_success'),get_uri("product","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
}
?>