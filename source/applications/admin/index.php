<?php
defined('SYS_IN') or exit('Access Denied.');
class index extends controller {
    function __construct() {
       parent::__construct();
    }
    
    function init() {
    	$userdb = new user_model();
        $user_info = $userdb->get_user_info($_SESSION['admin_uid']);
		
		$scenedb = new scene_model();
		$scene_list = $scenedb->get_list(10,0," * ","","dateline DESC ");
		
		$scenespotdb = new scenespot_model();
		$scenespot_list = $scenespotdb->get_list(10,0," * ",""," sp.dateline DESC ");
		
		$commentdb = new comment_model();
		$comment_list = $commentdb->get_list(10,0," * ","","dateline DESC ");
		
		$signindb =new signin_model();
		$signin_list = $signindb->get_list(10,0," * ","","dateline DESC ");
		
        include admin_template('index');
    }
}
?>