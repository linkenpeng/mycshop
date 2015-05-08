<?php
class trig_helper_html {

	public static function run_info($params, $show = DEBUG) {
		$run_info = '';
		if($show) {
			$run_info .= trig_func_common::lang("page", "thispage") . trig_func_common::lang("common", "excute");
			$run_info .= trig_func_common::lang("common", "time") . (sprintf("%.3f", ($params['endTime'] - $params['startTime'])));
			$run_info .= trig_func_common::lang("common", "second");
		}
		return !empty($run_info) ? '<div class="run-info">' . $run_info . '</div>' : '';;
	}

	public static function page_info($p, $show = false) {
		$page_info = '';
		if ($show) {
			$page_info .= '<li class="active"><a>' . trig_func_common::lang("page", "total") . $p->total . trig_func_common::lang("page", "item") . '</a></li>';
			$page_info .= '<li class="active"><a>' . $p->nowindex . '/' . $p->totalpage . trig_func_common::lang("page", "page") . '</a></li>';
		}
		$page_info .= $p->show();
		return !empty($page_info) ? '<ul class="pagination">' . $page_info . '</ul>' : '';
	}

	public static function json_success($data, $options = 0) {
		$ret = array(
			'code' => 200,
			'msg' => trig_func_common::lang("common", "request_success"),
			'result' => $data 
		);
		echo json_encode($ret, $options);
	}

	public static function json_error($e) {
		$ret = array(
			'code' => $e->getCode(),
			'msg' => $e->getMessage() 
		);
		if (!($e instanceof trig_exception_driver)) {
			$ret['code'] = 12306;
			$ret['msg'] = trig_func_common::lang("common", "system_exception");
		}
		echo json_encode($ret);
	}

}