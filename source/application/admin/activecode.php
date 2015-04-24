<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_activecode extends application_admin_base {
	private $activecodedb;

	function __construct() {
		parent::__construct();
		$this->activecodedb = new model_activecode();
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
			$where .= " and a.`activecode` LIKE '%" . $activecode . "%' ";
		}
		if (!empty($scenename)) {
			$where .= " and s.`scenename` like '%" . $scenename . "%' ";
		}
		if (!empty($startdate)) {
			$where .= " and a.`dateline`>='" . strtotime($startdate) . "' ";
		}
		if (!empty($enddate)) {
			$where .= " and a.`dateline`<='" . strtotime($enddate) . "' ";
		}
		// 分页		
		$count = $this->activecodedb->get_count($where);
		$p = new trig_page(array('total_count' => $count,'default_page_size' => 50));
		$field = "a.*,s.scenename ";
		// 获取分页后的数据
		$list = $this->activecodedb->get_list($p->perpage, $p->offset, $field, $where, " a.$orderby $order ");
		
		$batchdb = new model_batchcode();
		$batchlist = $batchdb->get_list('batchid,batchname');
		$batlist = array();
		foreach ($batchlist as $val) {
			$batlist[$val['batchid']] = $val['batchname'];
		}
		
		$show_date_js = 1;
		
		include trig_mvc_template::view_file('activecode');
	}

	public function add() {
		if (!empty($_POST['action'])) {
			$sceneid = intval($_POST['sceneid']);
			$length = intval($_POST['length']);
			$batchnum = trim($_POST['batchnum']);
			
			if (empty($_POST['number'])) {
				trig_func_common::ShowMsg("生成数量不能为空!", -1);
			}
			if (empty($sceneid)) {
				trig_func_common::ShowMsg("景区id不能为空!", -1);
			}
			
			if (!preg_match('/\d{3}/is', $batchnum)) {
				trig_func_common::ShowMsg("批次号只能是3个数字!", -1);
			}
			
			$_POST['dateline'] = empty($_POST['dateline']) ? time() : strtotime(trim($_POST['dateline']));
			$length = empty($length) || $length < 6 ? 6 : $length;
			
			$batchdb = new model_batchcode();
			if ($batchdb->isExistsBatchnum($batchnum)) {
				trig_func_common::ShowMsg("该批次号已经存在!", -1);
			}
			
			// 生成批号
			$data = array(
				'uid' => $_SESSION['admin_uid'],
				'username' => $_SESSION['admin_username'],
				'batchname' => $_POST['batchname'],
				'batchnum' => $batchnum,
				'dateline' => $_POST['dateline'] 
			);
			$batchid = $batchdb->insert($data);
			unset($data);
			
			// 生成激活码
			for($i < 0; $i < intval($_POST['number']); $i ++) {
				$activecode = $this->makeCode($sceneid, $length);
				$cardnum = $this->makeCardnum($batchnum, $i + 1);
				$data = array(
					'cardnum' => $cardnum,
					'sceneid' => $sceneid,
					'batchid' => $batchid,
					'activecode' => $activecode,
					'dateline' => $_POST['dateline'] 
				);
				$this->activecodedb->insert($data);
			}
			
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'insert_success'), trig_mvc_route::get_uri("activecode", "init"));
		}
		
		// 景区列表
		$scenedb = new model_scene();
		$scene_list = $scenedb->get_list(100, 0, " sceneid,scenename ", "", "dateline DESC ");
		
		$show_validator = 1;
		
		include trig_mvc_template::view_file('activecodeform');
	}

	public function batch() {
		if ($_POST['op'] == 'del') {
			$ids = $_POST['ids'];
			if (!empty($ids)) {
				$this->activecodedb->delete($ids);
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("activecode", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'no_choose'), -1);
			}
		}
	}

	public function delbatch() {
		$batchid = $_GET['batchid'];
		if (!empty($batchid)) {
			$batchdb = new model_batchcode();
			$batchdb->delete($batchid);
			if ($this->activecodedb->delete_batch($batchid)) {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_success'), trig_mvc_route::get_uri("activecode", "init"));
			} else {
				trig_func_common::ShowMsg(trig_func_common::lang('message', 'delete_failure'), -1);
			}
		} else {
			trig_func_common::ShowMsg(trig_func_common::lang('message', 'param_error'), -1);
		}
	}
	
	// 生成激活码
	private function makeCode($sceneid, $length = 6) {
		$randstr = md5($sceneid) . "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ" . md5(time());
		$randlen = strlen($randstr);
		$code = '';
		for($i = 0; $i < $length; $i ++) {
			$pos = rand(0, $randlen - 1);
			$code .= $randstr[$pos];
		}
		return strtoupper($code);
	}

	private function makeCardnum($batchnum, $num) {
		$cardnum = $batchnum . date("Y", time());
		
		$len = strlen($num);
		$numlen = $len > 5 ? $len : 5;
		$zero = '';
		for($i = 0; $i < ($numlen - $len); $i ++) {
			$zero .= '0';
		}
		
		return $cardnum . $zero . $num;
	}
}
?>