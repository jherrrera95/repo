<?php

class Articulo extends MY_Model{

	function __construct(){
		parent::__construct();
		$this->table="articulos";
	}

	public function combo($fields=[]){
		if(!empty($fields)){ 
			$this->db->select("configuraciones.tasa_financiamiento, 1 as cantidad, configuraciones.plazo_maximo, configuraciones.porcentaje_enganche,articulos.*,".$fields[1]." as value");
			$this->db->join("configuraciones","articulos.configuracion_id = configuraciones.id","inner");
			$this->db->where("status",1);
			return $this->db->get($this->table)->result();
		}else{
			$this->db->select("configuraciones.tasa_financiamiento, 1 as cantidad, configuraciones.plazo_maximo, configuraciones.porcentaje_enganche,articulos.*,descripcion as value"); 
			$this->db->join("configuraciones","articulos.configuracion_id = configuraciones.id","inner");
			$this->db->where("status",1); 
			return $this->db->get($this->table)->result();
		}
	}	

	public function checarExistencia($id){
		$this->db->select("inventario_articulos.existencia");
		$this->db->join("inventario_articulos","inventario_articulos.articulo_id = articulos.id","inner");
		$this->db->where("articulos.id",$id);
		return $this->db->get("articulos")->result();
	}

	public function reducirExistencia($detalle){
		foreach($detalle as $d){
				$this->db->where("articulo_id",$d["id"]);  
				$this->db->set('existencia', 'existencia-'.$d["cantidad"], FALSE);
				$this->db->update("inventario_articulos"); 
		}
	}

} 