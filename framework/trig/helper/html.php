<?php

class trig_helper_html {

	public static function run_info($params) {
		$run_info = trig_func_common::lang("page", "thispage") . trig_func_common::lang("common", "excute");
		$run_info .= trig_func_common::lang("common", "time") . (sprintf("%.3f", ($params['endTime'] - $params['startTime'])));
		$run_info .= trig_func_common::lang("common", "second");
		return $run_info;
	}
	
	public static function page_info($p) {
		$page_info = trig_func_common::lang("page","total").'<b>'.$p->total.'</b>';
		$page_info .= trig_func_common::lang("page","item").'<b>'.$p->nowindex.'/'.$p->totalpage.'</b>';
		$page_info .= trig_func_common::lang("page","page").$p->show();
		return $page_info;
	}
	
	public static function json_success($data, $options = 0)
	{
		$ret = array(
			'code' => 200,
			'msg' => trig_func_common::lang("common", "request_success"),
			'result' => $data
		);
		echo json_encode($ret, $options);
	}
	
	public static function json_error($e)
	{
		$ret = array(
			'code' => $e->getCode(),
			'msg' => $e->getMessage(),
		);
		if (!($e instanceof trig_exception_driver)) {
			$ret['code'] = 12306;
			$ret['msg'] = trig_func_common::lang("common", "system_exception");
		}
		echo json_encode($ret);
	}
}