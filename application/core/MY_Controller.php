<?php

class MY_Controller extends CI_Controller{
	
	function __construct(){
		parent::__construct();
	}

	public function json($data){
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}