<?php
defined('SYS_IN') or exit('Access Denied.');
class application_android_downloadlist {
	
	public function init() {
		$list = array();
		// 景区
		$scenedb = new model_scene();
		$scenelist = $scenedb->get_list(0,0,"image,description_cn_audio,description_en_audio,note_cn_audio,note_en_audio");
		foreach ($scenelist as $val) {
			foreach($val as $k=>$v) {
				if(!empty($v)) {
					if($k == 'image') {
						$list[] = 'thumb/'.$v;
					}
					$list[] = $v;
				}
			}
		}
		
		// 景点
		$scenespotdb = new model_scenespot();
		$scenespotlist = $scenespotdb->get_list(0,0,"sp.image,sp.cn_audio,sp.en_audio");
		foreach ($scenespotlist as $val) {
			foreach($val as $k=>$v) {
				if(!empty($v)) {
					if($k == 'image') {
						$list[] = 'thumb/'.$v;
					}
					$list[] = $v;
				}
			}
		}		
		
		// 文章
		$articledb = new model_article();
		$articlelist = $articledb->get_list(0,0,"a.image");
		foreach ($articlelist as $val) {
			foreach($val as $v) {
				if(!empty($v)) {
					$list[] = $v;
				}
			}
		}
		
		//debugEx($list);
        
		exit(json_encode($list));
	}
}
?>