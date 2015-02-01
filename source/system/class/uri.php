<?php
class uri {

    public function isAjax(){
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function getServerPort() {
        return $_SERVER['SERVER_PORT'];
    }

    public function getServerName() {
        return $_SERVER['SERVER_NAME'];
    }
    
    public function getRemoteAddress(){
    	return $_SERVER['REMOTE_ADDR'];
    }

    public function getUri() {
        return $_SERVER["REQUEST_URI"];
    }

    public function getMethod() {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function getHostName(){
        $result = $this->getServerName();
        $port = $this->getServerPort();
        if($port != '80'){
            return $result.':'.$port;
        }
        return $result;
    }
    
    public function getValidator($name = NULL){
    	if(is_null($name)){
    		return $this->getValidator($this->getAllParameters());
    	}
    	return new Soul_Validation_Validator($this->getParameter($name, array()));
    }

    public function isGet() {
        return $this->getMethod() == 'GET';
    }

    public function isPost() {
        return $this->getMethod() == 'POST';
    }

    public function getAllParameters() {
        return $_REQUEST;
    }

    public function hasParameter($name){
        if(array_key_exists($name, $_REQUEST)){
            return TRUE;
        }
        return FALSE;
    }

    public function getParameter($name, $default = '') {
        return $this->getFrom($_REQUEST, $name, $default);
    }

    public function getMultiParameter() {
        $result = array();
        $args = func_get_args();
        foreach ($args as $name) {
            $result = $this->getParameter($name);
        }
        return $result;
    }

    public function getAllForm() {
        return $_POST;
    }

    public function getForm($name, $default = '') {
        return $this->getFrom($_POST, $name, $default);
    }

    public function getMultiForm() {
        $result = array();
        $args = func_get_args();
        foreach ($args as $name) {
            $result = $this->getForm($name);
        }
        return $result;
    }

    public function getAllQuery() {
        return $_GET;
    }

    public function getQuery($name, $default = '') {
        return $this->getFrom($_GET, $name, $default);
    }

    public function getMultiQuery() {
        $result = array();
        $args = func_get_args();
        foreach ($args as $name) {
            $result = $this->getQuery($name);
        }
        return $result;
    }

    public function getFile($name){
    	if(!array_key_exists($name, $_FILES)){
    		return NULL;
    	}
    	
    	if(!is_array($_FILES[$name]['name'])){
    		return new Soul_Web_Http_File($_FILES[$name]);
    	}
    	
    	$result = array();
    	$arr = $_FILES[$name];
    	if(!empty($arr['name'][0])){
	    	for($i = 0; $i < count($arr['name']); $i++){
	    		if(!empty($arr['name'][$i])){
		        	$result[] = new Soul_Web_Http_File(
		        		array(
		        			'name'=>$arr['name'][$i],
		        			'type'=>$arr['type'][$i],
		        			'tmp_name'=>$arr['tmp_name'][$i],
		        			'error'=>$arr['error'][$i],
		        			'size'=>$arr['size'][$i]
		        		)
		        	);
	    		}
	    	}
    	}
    	return $result;
    }

    private function getFrom($source, $name, $default = '') {
        if (isset($source[$name]) && !empty($source[$name])) {
            return $source[$name];
        }
        return $default;
    }
}
