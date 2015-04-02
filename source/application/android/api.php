<?php
defined('SYS_IN') or exit('Access Denied.');
class application_android_api {
    function __construct() {
        
    }
	
    function init() {
       echo "Welcome to Android API";
    }
	
	function show_update() {
		$update_info = array(
			'version'=>'1.3.6',
			'isover'=>'0',
			'updatetime'=>'2012-09-23'
		);
		echo json_encode($update_info);
	}
	
	function check_activecode() {
		$msg = 0;
		$sceneid = empty($_GET['sceneid']) ? '' : intval($_GET['sceneid']);
		$activecode = empty($_GET['activecode']) ? '' : trim($_GET['activecode']);		
		if(!empty($sceneid) && !empty($activecode)) {
			$activecodedb = new model_activecode();
			$sid = $activecodedb->get_sceneid($activecode);
			if($sid == $sceneid) {
				$msg = 1;
				$activecodedb->update_usednum($activecode);
			}
		}		
		echo $msg;
	}	
}
?>