<?php

class Articulos extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model("articulo");
	}

	public function combo(){  
		$this->json($this->articulo->combo());
	}

	public function checarExistencia($id){ 
		$this->json($this->articulo->checarExistencia($id));
	}
}