<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Datos_alumnos_ex_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert($data) {
        return ($this->db->insert('datos_alumnos_ex' , $data)) ? $this->db->insert_id() : false;
    }
    
    public function check_cta($cta){
        $this->db->select('id');
        $this->db->where('no_cuenta', $cta);
        $this->db->limit(1);
        $result = $this->db->get('datos_alumnos_ex');
        return ($result->num_rows() > 0) ? false : true;
    }
    public function get_by_user_id($user_id){
        $this->db->select("d.*,f.facultad,c.carrera");
        $this->db->where("usuario_id" , $user_id);
        $this->db->join("carreras as c" , "c.id=d.carrera_id");
        $this->db->join("facultad as f" , "f.id=d.facultad_id");
        $this->db->limit(1);
        $result = $this->db->get("datos_alumnos_ex as d");
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }

}

?>
