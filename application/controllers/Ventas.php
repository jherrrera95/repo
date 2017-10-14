<?php

class ventas extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model("venta");
		$this->load->model("articulo");
	}

	public function index(){

	}

	public function get(){ 
		$this->json($this->venta->ventasTotales());
	}

	public function generarFolio(){
		$this->json($this->venta->generarFolio());
	}

	public function guardar(){   
		$data = $this->input->post(); 
		$this->venta->guardar($data);
		$this->articulo->reducirExistencia($data["detalle"]);
	}
} 