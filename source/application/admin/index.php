<?php
defined('SYS_IN') or exit('Access Denied.');

class application_admin_index extends application_admin_base {

	function __construct() {
		parent::__construct();
	}

	function init() {
		$user = new model_user();
		$user_info = $user->get_user_info($_SESSION['admin_uid']);
		
		$scene = new model_scene();
		$scene_list = $scene->get_list(10, 0, " * ", "", "dateline DESC ");
		
		$scenespot = new model_scenespot();
		$scenespot_list = $scenespot->get_list(10, 0, " * ", "", " sp.dateline DESC ");
		
		$comment = new model_comment();
		$comment_list = $comment->get_list(10, 0, " * ", "", "dateline DESC ");
		
		$signin = new model_signin();
		$signin_list = $signin->get_list(10, 0, " * ", "", "dateline DESC ");
		
		$mysqlinfo = new model_mysqlinfo();
		$dbsize = $mysqlinfo->dbsize();
		$mysql_version = $mysqlinfo->dbversion();		
		
		$this->display('index', array(
			'user_info' => $user_info,
			'scene_list' => $scene_list,
			'scenespot_list' => $scenespot_list,
			'comment_list' => $comment_list,
			'signin_list' => $signin_list,
			'dbsize' => $dbsize,
			'mysql_version' => $mysql_version,
		));
	}
}