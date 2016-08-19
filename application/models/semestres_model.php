<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Semestres_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all($select = '') {
        if($select != ''){
            $this->db->select($select);
        }
        $this->db->order_by('id','DESC');
        $result = $this->db->get('semestres');
        return ($result->num_rows() > 0) ? $result->result_array() : FALSE;
    }
    public function get_all_order_by_inicio($select = '') {
        if($select != ''){
            $this->db->select($select);
        }
        $this->db->order_by('ini_sem' , 'DESC');
        $result = $this->db->get('semestres');
        return ($result->num_rows() > 0) ? $result->result_array() : FALSE;
    }
    public function insert($data){
        return ($this->db->insert('semestres' , $data)) ? $this->db->insert_id() : false;
    }
    public function update($id , $data){
        $this->db->where('id' , $id);
        return ($this->db->update('semestres' , $data)) ? true : false;
    }
    public function delete($id){
        $this->db->where('id' , $id);
        return ($this->db->delete('semestres')) ? true : false;
    }
    public function get($id){
        $this->db->where('id' , $id);
        $this->db->limit(1);
        $result = $this->db->get('semestres');
        return ($result->num_rows() > 0 ) ? $result->row_array() : false;
    }
    public function get_actual(){
        $date = date('Y-m-d');
        $this->db->where('ini_sem <=' , $date);
        $this->db->where('fin_sem >=' , $date);
        $this->db->limit(1);
        $result = $this->db->get('semestres');
        return ($result->num_rows() > 0 ) ? $result->row_array() : false;
    }
    public function puede_insc($id){
        $date = date('Y-m-d H:i:s');
        if(get_type_user() == 1){
            $this->db->where('id' , $id);
            $this->db->where('ini_insc >=' , $date);
            $this->db->where('ini_sem <=' , $date);
            $this->db->limit(1);
            $result = $this->db->get('semestres');
            return ($result->num_rows() > 0 ) ? true : false;
        }else{
            $this->db->where('id' , $id);
            $this->db->where('ini_insc <=' , $date);
            $this->db->where('fin_insc >=' , $date);
            $this->db->limit(1);
            $result = $this->db->get('semestres');
            return ($result->num_rows() > 0 ) ? true : false;
        }
    }
    public function check_delete($id){
        $this->db->select('id');
        $this->db->where('semestre_id' , $id);
        $result = $this->db->get('taller_semestre');
        return ($result->num_rows() === 0) ? true : false;
    }
    public function get_by_taller_semestre($id_taller){
        $this->db->select("s.*");
        $this->db->join("semestres as s" , "s.id=ts.semestre_id");
        
    }
}

?>
