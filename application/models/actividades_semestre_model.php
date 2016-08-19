<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Actividades_semestre_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert($data) {
        return ($this->db->insert('actividades_semestre' , $data)) ? $this->db->insert_id() : false;
    }
    public function delete($id){
        $this->db->where('id' , $id);
        return ($this->db->delete('actividades_semestre')) ? true : false;
    }
}

?>
