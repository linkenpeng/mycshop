<?php
defined('SYS_IN') or exit('Access Denied.');
class article extends controller {
    private $articledb;
    function __construct() {
        parent::__construct();
        $this->articledb = Base::load_model("article_model");
    }
	
    function init() {
		$catid = empty($_GET['catid']) ? "" : intval($_GET['catid']);
		$title = empty($_GET['title']) ? '' : trim($_GET['title']);
		$sceneid = empty($_GET['sceneid']) ? "" : intval($_GET['sceneid']);
		
        $where = " WHERE 1 ";
        if (!empty($catid)) {
            $where .= " and c.catid=".$catid;
        }
		if (!empty($sceneid)) {
            $where .= " and a.sceneid=".$sceneid;
        }
        if (!empty($title)) {
            $where .= " and a.`title` like '%".$title."%' ";
        }
        //分页       
        
        $count = $this->articledb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "15" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
		$field = "a.aid,a.sceneid,a.title,a.catid,a.image,a.content,a.ordernum,a.dateline,c.name ";
        //获取分页后的数据
        $list = $this->articledb->get_list($pagesize,$pagesize*($nowpage-1),$field,$where," a.ordernum DESC, a.dateline DESC ");
		
		//分类
        $articlecatdb = Base::load_model("articlecat_model");
        $articlecat_list = $articlecatdb->get_list(0, 0, " catid,name ");
		$cat_list = array();
        foreach($articlecat_list as $k=>$v) {
            $cat_list[$v['catid']] = $v['name'];
        }
		
		//分类下拉选项
        $category_options = $this->get_category_options($catid);
		
		//景区列表
		$scenedb = Base::load_model("scene_model");
        $scene_list = $scenedb->get_list(100,0," sceneid,scenename ","","dateline DESC ");
		$sc_list = array();
		foreach ($scene_list as $k=>$v) {
			$sc_list[$v['sceneid']] = $v['scenename'];
		}	
		
        include admin_template('article');
    }
    /**
     * 添加
     * 
     * @author Myron
     * 2011-5-27 上午11:57:59
     */
    public function add() {
        if (!empty($_POST['action'])) {
            if (empty($_POST['title'])) {
                ShowMsg("文章标题不能为空!",-1);
            }
			if (empty($_POST['sceneid'])) {
                ShowMsg("景区id不能为空!",-1);
            }
            $_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
            if (!empty($_FILES['image']['name'])) {
                Base::load_sys_class("uploadfile",'',0);
                $upfile = new uploadfile(UPLOAD_IMAGE_FILE_TYPES);
                $upfile->savesamll = 1;
                $image = $upfile->upload($_FILES['image']);
            }
			if (!empty($_POST['sceneid'])) {
				$sceneid = implode(',', $_POST['sceneid']);
			}
            $data = array(
                'uid'=>$_SESSION['admin_uid'],
                'username'=>$_SESSION['admin_username'],
				'catid'=>$_POST['catid'],
                'sceneid'=>$sceneid,
                'title'=>$_POST['title'],
                'image'=>$image,
                'content'=>$_POST['content'],
				'ordernum'=>$_POST['ordernum'],
                'dateline'=>$_POST['dateline']
            );
            if ($this->articledb->insert($data)) {
                ShowMsg(lang('message','insert_success'),get_uri("article","init"));
            } else {
                ShowMsg(lang('message','insert_failure'),-1);
            }
        }		

		//景区列表
		$scenedb = Base::load_model("scene_model");
        $scene_list = $scenedb->get_list(100,0," sceneid,scenename ","","dateline DESC ");
		
		//分类下拉选项
        $category_options = $this->get_category_options();
		
        $show_validator = 1;

        include admin_template('articleform');
    }
	
    /**
     * 修改
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function edit() {
        $aid = $_GET['aid'];
        if (!empty($aid)) {
            $value = $this->articledb->get_one($aid);
        }
        if (!empty($_POST['action'])&&!empty($_POST['aid'])) {
            if (!empty($_FILES['image']['name'])) {
                Base::load_sys_class("uploadfile",'',0);
                $upfile = new uploadfile(UPLOAD_IMAGE_FILE_TYPES);
                $upfile->savesamll = 1;
                $image = $upfile->upload($_FILES['image']);
            }
			if (!empty($_POST['sceneid'])) {
				$sceneid = implode(',', $_POST['sceneid']);
			}
			
            $data = array(
                'title'=>$_POST['title'],
				'catid'=>$_POST['catid'],
				'sceneid'=>$sceneid,
				'ordernum'=>$_POST['ordernum'],
                'content'=>$_POST['content']
            );
            if (!empty($image)) {
                $data['image'] = $image;
                //删除老图片
                if($image!=$_POST['oldimage']) {
                    @unlink(UPLOAD_PATH.'/'.$_POST['oldimage']);
                    @unlink(UPLOAD_PATH.'/thumb/'.$_POST['oldimage']);
                }
            }
            if ($this->articledb->update($data,"aid=".$_POST['aid'])) {
                ShowMsg(lang('message','update_success'),get_uri("article","init"));
            } else {
                ShowMsg(lang('message','update_failure'),-1);
            }
        }
		
		//分类下拉选项
        $category_options = $this->get_category_options($value['catid']);
		
		//景区列表
		$scenedb = Base::load_model("scene_model");
        $scene_list = $scenedb->get_list(100,0," sceneid,scenename ","","dateline DESC ");
		
		$sceneids = explode(',', $value['sceneid']);
		
        $show_validator = 1;
        include admin_template('articleform');
    }
	
	/**
     * 得到文章分类
     * 
     * @author Myron
     * 2012-5-6
     */
	private function get_category_options($selectedid = 0) {
        $articlecatdb = Base::load_model("articlecat_model");		
		return $articlecatdb->recur_options($selectedid);
	}
	
    /**
     * 删除
     * 
     * @author Myron
     * 2011-5-27 上午09:36:34
     */
    public function delete() {
        $aid = $_GET['aid'];
        if (!empty($aid)) {
            if ($this->articledb->delete($aid)) {
                ShowMsg(lang('message','delete_success'),get_uri("article","init"));
            } else {
                ShowMsg(lang('message','delete_failure'),-1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
}
?>