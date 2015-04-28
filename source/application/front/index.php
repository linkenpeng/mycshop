<?php
defined('SYS_IN') or exit('Access Denied.');
class application_front_index extends application_front_base {
    function __construct() {
       parent::__construct();
    }
    
    function init() {
    	$userdb = new model_user();
        $user_info = $userdb->get_user_info($_SESSION['admin_uid']);
		
		$scenedb = new model_scene();
		$scene_list = $scenedb->get_list(10,0," * ","","dateline DESC ");
		
		$scenespotdb = new model_scenespot();
		$scenespot_list = $scenespotdb->get_list(10,0," * ",""," sp.dateline DESC ");
		
		$commentdb = new model_comment();
		$comment_list = $commentdb->get_list(10,0," * ","","dateline DESC ");
		
		$signindb =new model_signin();
		$signin_list = $signindb->get_list(10,0," * ","","dateline DESC ");
		
        $this->display('index', array(
        	'user_info' => $user_info,
        	'scene_list' => $scene_list,
        	'scenespot_list' => $scenespot_list,
        	'comment_list' => $comment_list,
        	'signin_list' => $signin_list
        ));
    }
}