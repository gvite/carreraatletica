<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tipo_usuario_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get($id) {
        $this->db->where('id' , $id);
        $this->db->limit(1);
        $result = $this->db->get('tipo_usuario');
        return ($result->num_rows() > 0 ) ? $result->row_array() : false;
    }

}

?>
