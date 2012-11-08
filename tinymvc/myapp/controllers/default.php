<?php

/**
 * default.php
 *
 * default application controller
 *
 * @package		TinyMVC
 * @author		Monte Ohrt
 */

class Default_Controller extends TinyMVC_Controller
{
	function index($creds = array()) {

		$this->view->display('login_view');
		if(!empty($creds)) {
			echo $creds['user'];
			echo $creds['pass'];
		}
	}
	  
	function login() {
		$pass = $_POST["u_pass"];
		$uname = $_POST["u_name"];
		if($pass === "pass" && $uname === "user") {
			$this->main();
		} else {
			$this->index(array("user"=> $uname, "pass" => $pass));
		}
	}
	
	function main() {
		$this->view->display('index_view');
	}
}

?>
