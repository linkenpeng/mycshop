<?php
defined('SYS_IN') or exit('Access Denied.');
class index {
    function __construct() {
        //判断是否登录
        Base::load_model("login_model")->is_login();
    }
    function init() {
        $user_info = Base::load_model("user_model")->get_user_info($_SESSION['admin_uid']);
		
		$scenedb = Base::load_model("scene_model");
		$scene_list = $scenedb->get_list(10,0," * ","","dateline DESC ");
		
		$scenespotdb = Base::load_model("scenespot_model");
		$scenespot_list = $scenespotdb->get_list(10,0," * ",""," sp.dateline DESC ");
		
		$commentdb = Base::load_model("comment_model");
		$comment_list = $commentdb->get_list(10,0," * ","","dateline DESC ");
		
		$signindb = Base::load_model("signin_model");
		$signin_list = $signindb->get_list(10,0," * ","","dateline DESC ");
		
        include admin_template('index');
    }
}
?>