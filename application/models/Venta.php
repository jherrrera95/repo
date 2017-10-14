<?php

class Venta extends MY_Model{

	function __construct(){
		parent::__construct();
		$this->table="ventas";
		$this->db->protect_identifiers(false);
	}
 
 	public function ventasTotales(){
 		$this->db->select("ventas.id, LPAD(ventas.id,6,'0') as folio, ventas.total_pagar, clientes.id, LPAD(clientes.id,6,'0') as clave_cliente, CONCAT(clientes.nombre,' ',clientes.apellido_p,' ',clientes.apellido_m) as nombre, ventas.fecha, IF(ventas.status=1,'Activa','Cancelada') as status");
 		$this->db->join("clientes","clientes.id = ventas.cliente_id","inner");
 		$this->db->where("ventas.status",1);
 		$this->db->order_by("ventas.id");
 		return $this->db->get("ventas")->result();
 	}

 	public function guardar($data){ 
 		$venta = $data["venta"];
 		$detalle = $data["detalle"];
 		$this->db->insert("ventas",$venta);
 		$id = $this->db->insert_id();
 		$this->guardarDetalle($detalle,$id);
 	}

 	public function guardarDetalle($detalle,$id_venta){
 		foreach($detalle as $d){
 			$this->db->insert("venta_detalles",["venta_id"=>$id_venta, "articulo_id"=>$d["id"], "cantidad"=>$d["cantidad"]]);
 		}
 	}

 	public function generarFolio(){
 		$this->db->select("LPAD((IFNULL(MAX(ventas.id),0)+1),6,'0') as folio");
 		return $this->db->get("ventas")->result();
 	}


}