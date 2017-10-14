<?php

class MY_Model extends CI_Model{
	protected $table="";
	
	function __construct(){
		parent::__construct();
		$this->load->database();
	}
 	
 	public function get($where=""){
 		$this->db->select("*");
 		if($where != ""){ 
 			foreach($where as $key => $val){
 				$this->db->where($key,$val); 
 			}
 		}
 		return $this->db->get($this->table)->result();
 	}
 	
 	public function save($data){
 		if($data["id"]==0 || $data["id"]==""){
 			$this->db->insert($this->table,$data);
 		}else{
 			$this->db->where("id",$data["id"]);
 			$this->db->update($this->table,$data);
 		}
 	}

	public function combo($fields=[]){
		if(!empty($fields)){ 
			$this->db->select($fields[0]." as id, ".$fields[1]." as value");
			$this->db->where("status",1);
			return $this->db->get($this->table)->result();
		}else{
			$this->db->select("id, descripcion as value");
			$this->db->where("status",1); 
			return $this->db->get($this->table)->result();
		}
	}	
}