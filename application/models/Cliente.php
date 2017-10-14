<?php

class Cliente extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->table="clientes";
	}

	public function combo($fields=[]){
		if(!empty($fields)){ 
			$this->db->select($fields[0]." as id, ".$fields[1]." as value, rfc, LPAD(clientes.id,6,'0') as clave, status, apellido_p, apellido_m, rfc, rfc, nombre, id");
			$this->db->where("status",1);
			return $this->db->get($this->table)->result();
		}else{
			$this->db->select("id, descripcion as value, rfc, LPAD(clientes.id,6,'0') as clave, status, apellido_p, apellido_m, rfc, rfc, nombre, id");
			$this->db->where("status",1); 
			return $this->db->get($this->table)->result();
		}
	}	
}