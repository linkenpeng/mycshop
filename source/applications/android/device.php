<?php
defined('SYS_IN') or exit('Access Denied.');
class device {
    function __construct() {
        
    }
	
    function init() {
	
    }
	
	function receiveinfo() {
		$deviceInfo = array();
		$deviceInfo['mac'] = $_POST['mac'];
		$deviceInfo['screen_width'] = $_POST['screen_width'];
		$deviceInfo['screen_height'] = $_POST['screen_height'];
		$deviceInfo['model'] = $_POST['model'];
		$deviceInfo['brand'] = $_POST['brand'];
		$deviceInfo['android_version'] = $_POST['android_version'];
		$deviceInfo['network'] = $_POST['network'];		
		$deviceInfo['lasttime'] = date('Y-m-d H:i:s', time());
		$deviceInfo['lastip'] = get_ip();
		
		$devicedb = Base::load_model ("device_model");
		if($devicedb->isExists(array('mac'=>$deviceInfo['mac']))) {
			$deviceInfo['loginnum'] = "loginnum+1";
			$devicedb->update($deviceInfo, "mac='".$deviceInfo['mac']."'");
		} else {
			$deviceInfo['createtime'] = date('Y-m-d H:i:s', time());
			$deviceInfo['loginnum'] = 1;			
			$devicedb->insert($deviceInfo);
		}
		
		//echo json_encode($deviceInfo);
	}
}
?>