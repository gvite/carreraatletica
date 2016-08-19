<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Datos_trabajador_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert($data) {
        return ($this->db->insert('datos_trabajador' , $data)) ? $this->db->insert_id() : false;
    }
    public function get_by_user_id($user_id){
        $this->db->select("d.*");
        $this->db->where("usuario_id" , $user_id);
        $this->db->limit(1);
        $result = $this->db->get("datos_trabajador as d");
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }
}

?>
