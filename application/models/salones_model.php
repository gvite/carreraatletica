<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Salones_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        $result = $this->db->get('salones');
        return ($result->num_rows() > 0) ? $result->result_array() : FALSE;
    }
    public function insert($data){
        return ($this->db->insert('salones' , $data)) ? $this->db->insert_id() : false;
    }
    public function update($id , $data){
        $this->db->where('id' , $id);
        return ($this->db->update('salones' , $data)) ? true : false;
    }
    public function delete($id){
        $this->db->where('id' , $id);
        return ($this->db->delete('salones')) ? true : false;
    }
    public function check_delete($id){
        $this->db->select('id');
        $this->db->where('salon_id' , $id);
        $result = $this->db->get('taller_semestre');
        return ($result->num_rows() === 0) ? true : false;
    }
}

?>
