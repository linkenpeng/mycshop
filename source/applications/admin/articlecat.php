<?php
defined('SYS_IN') or exit('Access Denied.');
class articlecat {
    private $articlecatdb;
	const pagesize = 100;
	public $cattypes = array(1=>'资讯',2=>'商家');
    function __construct() {
        //判断是否登录
        Base::load_model("login_model")->is_login();
        $this->articlecatdb = Base::load_model("articlecat_model");
    }
	
    function init() {
        $where = " WHERE 1 ";
        //分页       
        Base::load_sys_class("page",'',0);
        $count = $this->articlecatdb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? self::pagesize : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
        //获取分页后的数据
		$list = array();
        $sourcelist = $this->articlecatdb->get_list($pagesize,$pagesize*($nowpage-1)," * ",$where,"ordernum DESC, dateline DESC ");
        $sourcelist = $this->articlecatdb->make_tree($sourcelist);		
		$this->articlecatdb->sort_tree($sourcelist, $list);
	
		$show_zone = 1;
		
		//景区列表
		$scenedb = Base::load_model("scene_model");
        $scene_list = $scenedb->get_list(100,0," sceneid,scenename ","","dateline DESC ");
		$sc_list = array();
		foreach ($scene_list as $k=>$v) {
			$sc_list[$v['sceneid']] = $v['scenename'];
		}
		
        include admin_template('articlecat');
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
            if (!empty($_FILES['image']['name'])) {
                Base::load_sys_class("upfile",'',0);
                $upfile = new upfile("jpg,gif,bmp,png");
                $upfile->savesamll = 1;
                $image = $upfile->upload($_FILES['image']);
            }
			if (!empty($_POST['sceneid'])) {
				$sceneid = implode(',', $_POST['sceneid']);
			}
			$data = array(
                'upid'=>$_POST['upid'],
				'cattype'=>$_POST['cattype'],
				'sceneid'=>$sceneid,
                'uid'=>$_SESSION['admin_uid'],
                'username'=>$_SESSION['admin_username'],
                'name'=>$_POST['name'],
				'image'=>$image,
                'description'=>$_POST['description'],
				'ordernum'=>$_POST['ordernum'],
                'dateline'=>$_POST['dateline']
            );
            if ($this->articlecatdb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("articlecat","init"));
            } else {
                ShowMsg(lang('message','insert_failure'),-1);
            }
        }
		
		$options = $this->makeOptions();
		
		//景区列表
		$scenedb = Base::load_model("scene_model");
        $scene_list = $scenedb->get_list(100,0," sceneid,scenename ","","dateline DESC ");
		
        $show_validator = 1;
        include admin_template('articlecatform');
    }
	
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $catid = $_GET['catid'];
        if (!empty($catid)) {
            $value = $this->articlecatdb->get_one($catid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['catid'])) {
            if (!empty($_FILES['image']['name'])) {
                Base::load_sys_class("upfile",'',0);
                $upfile = new upfile("jpg,gif,bmp,png");
                $upfile->savesamll = 1;
                $image = $upfile->upload($_FILES['image']);
            }
			if (!empty($_POST['sceneid'])) {
				$sceneid = implode(',', $_POST['sceneid']);
			}
			$data = array(
				'sceneid'=>$sceneid,
				'cattype'=>$_POST['cattype'],
                'name'=>$_POST['name'],
				'upid'=>$_POST['upid'],
				'ordernum'=>$_POST['ordernum'],
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
            if ($this->articlecatdb->update($data,"catid=".$_POST['catid'])) {
                ShowMsg(lang('message','update_success'),get_uri("articlecat","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
		
		$options = $this->makeOptions();
		
		//景区列表
		$scenedb = Base::load_model("scene_model");
        $scene_list = $scenedb->get_list(100,0," sceneid,scenename ","","dateline DESC ");
		
		$sceneids = explode(',', $value['sceneid']);
		
        $show_validator = 1;
        include admin_template('articlecatform');
    }
	
	/**
     * 组合下拉列表
     * 
     * @author Myron
     * 2012-5-6 上午10:36:34
     */
	private function makeOptions() {		
		$where = " WHERE 1 ";
        $catlist = $this->articlecatdb->get_list(0, 0, " * ", $where, "dateline DESC ");
        $catlist = $this->articlecatdb->make_tree($catlist);	
		
		$topcat = array(0=>array('catid'=>0,'upid'=>0,'name'=>'顶级分类'));
		$catlist = $topcat + $catlist;
		
		$tolist = array();
		$this->articlecatdb->sort_tree($catlist,$tolist);
		
		return $this->articlecatdb->recur_options($_GET['upid'] , $_GET['catid'], $tolist);	
	}	
	
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $catid = $_GET['catid'];
        if (!empty($catid)) {
            if ($this->articlecatdb->delete($catid)) {
                ShowMsg(lang('message','delete_success'),get_uri("articlecat","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
}
?>