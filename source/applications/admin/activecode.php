<?php
defined('SYS_IN') or exit('Access Denied.');
class activecode extends controller {
    private $activecodedb;
    function __construct() {
        parent::__construct();
        $this->activecodedb = Base::load_model("activecode_model");
    }
    function init() {
        $where = " WHERE 1 ";
		$activecode = empty($_GET['activecode']) ? "" : trim($_GET['activecode']);
		$scenename = empty($_GET['scenename']) ? "" : trim($_GET['scenename']);
		$startdate = empty($_GET['startdate']) ? "" : $_GET['startdate'];
		$enddate = empty($_GET['enddate']) ? "" : $_GET['enddate'];
		$orderby = empty($_GET['orderby_value']) ? "id" : trim($_GET['orderby_value']);
		$order = empty($_GET['order_value']) ? "desc" : trim($_GET['order_value']);
		
        $where = " WHERE 1 AND a.status!=9 ";
		if (!empty($activecode)) {
            $where .= " and a.`activecode` LIKE '%".$activecode."%' ";
        }
		if (!empty($scenename)) {
            $where .= " and s.`scenename` like '%".$scenename."%' ";
        }
		if (!empty($startdate)) {
			$where .= " and a.`dateline`>='".strtotime($startdate)."' ";
		}
		if (!empty($enddate)) {
			$where .= " and a.`dateline`<='".strtotime($enddate)."' ";
		}
        //分页       
        
        $count = $this->activecodedb->get_count($where);
        $pagesize = !isset($_GET['pagesize']) ? "50" : $_GET['pagesize'];
        $nowpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $setarr = array(
            'total'=>$count,
            'perpage'=>$pagesize
        );
        $p = new page($setarr);
		$field = "a.*,s.scenename ";
        //获取分页后的数据
        $list = $this->activecodedb->get_list($pagesize,$pagesize*($nowpage-1),$field,$where," a.$orderby $order ");
		
		$batchdb = Base::load_model("batchcode_model");
		$batchlist = $batchdb->get_list('batchid,batchname');
		$batlist = array();
		foreach ($batchlist as $val) {
			$batlist[$val['batchid']] = $val['batchname'];
		}
		
		$show_date_js = 1;
		
        include admin_template('activecode');
    }
	
	 /**
     * 添加
     * 
     * @author Myron
     * 2011-5-27 上午11:57:59
     */
    public function add() {
        if (!empty($_POST['action'])) {
            $sceneid = intval($_POST['sceneid']);
			$length = intval($_POST['length']);
			$batchnum = trim($_POST['batchnum']);
			
			if (empty($_POST['number'])) {
                ShowMsg("生成数量不能为空!",-1);
            }
			if (empty($sceneid)) {
                ShowMsg("景区id不能为空!",-1);
            }
			
			if(!preg_match('/\d{3}/is', $batchnum)) {
				ShowMsg("批次号只能是3个数字!",-1);
			}
			
            $_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
            $length = empty($length) || $length < 6 ? 6 : $length;
			
			$batchdb = Base::load_model("batchcode_model");
			if($batchdb->isExistsBatchnum($batchnum)) {
				ShowMsg("该批次号已经存在!",-1);
			}
			
			//生成批号
			$data = array(
					'uid'=>$_SESSION['admin_uid'],
					'username'=>$_SESSION['admin_username'],
					'batchname'=>$_POST['batchname'],
					'batchnum'=>$batchnum,
					'dateline'=>$_POST['dateline']
				);			
			$batchid = $batchdb->insert($data);
			unset($data);
			
			//生成激活码
			for ($i < 0; $i < intval($_POST['number']); $i++) {
				$activecode = $this->makeCode($sceneid , $length);
				$cardnum	= $this->makeCardnum($batchnum, $i+1);
				$data = array(
					'cardnum'=>$cardnum,
					'sceneid'=>$sceneid,
					'batchid'=>$batchid,					
					'activecode'=>$activecode,
					'dateline'=>$_POST['dateline']
				);
				$this->activecodedb->insert($data);
			}
			
			ShowMsg(lang('message','insert_success'),get_uri("activecode","init"));
        }		

		//景区列表
		$scenedb = Base::load_model("scene_model");
        $scene_list = $scenedb->get_list(100,0," sceneid,scenename ","","dateline DESC ");
		
        $show_validator = 1;

        include admin_template('activecodeform');
    }
	
	public function batch() {
		if($_POST['op'] == 'del') {
			$ids = $_POST['ids'];
			if(!empty($ids)) {
				$this->activecodedb->delete($ids);	
				ShowMsg(lang('message','delete_success'), get_uri("activecode","init"));
			} else {
				ShowMsg(lang('message','no_choose'), -1);
			}
		}
	}
	
    public function delbatch() {
        $batchid = $_GET['batchid'];
        if (!empty($batchid)) {
			$batchdb = Base::load_model("batchcode_model");
			$batchdb->delete($batchid);			
            if ($this->activecodedb->delete_batch($batchid)) {
                ShowMsg(lang('message','delete_success'), get_uri("activecode","init"));
            } else {
                ShowMsg(lang('message','delete_failure'), -1);
            }
        } else {
            ShowMsg(lang('message','param_error'),-1);
        }
    }
	
	//生成激活码
	private function makeCode($sceneid, $length = 6) {
		$randstr = md5($sceneid)."0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ".md5(time());
		$randlen = strlen($randstr);
		$code = '';
		for($i = 0; $i < $length; $i++) {
			$pos = rand(0, $randlen-1);
			$code .= $randstr[$pos];
		}
		return strtoupper($code);
	}
	
	private function makeCardnum($batchnum, $num) {
		$cardnum = $batchnum.date("Y",time());
		
		$len = strlen($num);
		$numlen = $len > 5 ? $len : 5;
		$zero = '';
		for($i=0; $i < ($numlen-$len); $i++) {
			$zero .= '0';
		}
		
		return $cardnum.$zero.$num;
	}
	
}
?>