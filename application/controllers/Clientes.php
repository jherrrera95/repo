<?php

class Clientes extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model("cliente");
	}

	public function combo(){
		$this->json($this->cliente->combo(["id","CONCAT(LPAD(clientes.id,6,'0'),' - ',clientes.nombre,' ',clientes.apellido_p,' ',clientes.apellido_m)","rfc"]));
	}
}