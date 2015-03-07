<?php

class controller {

	function __construct() {
		Base::load_model("login_model")->is_login();
	}
}