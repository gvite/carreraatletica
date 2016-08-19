<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ocupaciones_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        $result = $this->db->get('ocupaciones');
        return ($result->num_rows() > 0 ) ? $result->result_array() : false;
    }

}

?>
