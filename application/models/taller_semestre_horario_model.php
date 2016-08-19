<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Taller_semestre_horario_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert($data) {
        return ($this->db->insert('taller_semestre_horarios' , $data)) ? $this->db->insert_id() : false;
    }
    public function update($id , $data) {
        $this->db->where('id' , $id);
        return ($this->db->update('taller_semestre_horarios' , $data)) ? true : false;
    }
    public function delete($id){
        $this->db->where('id' , $id);
        return ($this->db->delete('taller_semestre_horarios')) ? true : false;
    }
    public function get_by_semestre($semestre_id){
        $this->db->select('tsh.id,ts.grupo,tsh.taller_semestre_id,tsh.dia,tsh.inicio,tsh.termino,p.nombre,p.paterno,p.materno,s.salon,t.taller,t.id as taller_id');
        $this->db->join('taller_semestre AS ts' , 'tsh.taller_semestre_id = ts.id');
        $this->db->join('profesores AS p' , 'ts.profesor_id = p.id');
        $this->db->join('salones AS s' , 'ts.salon_id = s.id');
        $this->db->join('talleres AS t' , 'ts.taller_id = t.id');
        $this->db->where('ts.semestre_id' , $semestre_id);
        $result = $this->db->get('taller_semestre_horarios AS tsh');
        return ($result->num_rows() > 0 ) ? $result->result_array() : false;
    }
    public function get_by_semestre_talleres($semestre_id){
        $this->db->select('tsh.id,ts.grupo,tsh.taller_semestre_id,tsh.dia,tsh.inicio,tsh.termino,p.nombre,p.paterno,p.materno,s.salon,t.taller,t.id as taller_id');
        $this->db->join('taller_semestre AS ts' , 'tsh.taller_semestre_id = ts.id');
        $this->db->join('profesores AS p' , 'ts.profesor_id = p.id');
        $this->db->join('salones AS s' , 'ts.salon_id = s.id');
        $this->db->join('talleres AS t' , 'ts.taller_id = t.id');
        $this->db->where('ts.semestre_id' , $semestre_id);
        $talleres = $this->input->post('talleres');
        if(is_array($talleres)){
            $where = '(';
            foreach($talleres as $taller){
                $where .= 't.id=' . $taller . ' OR ';
            }
            $where = trim($where, ' OR ') . ')';
            $this->db->where($where);
        }
        $result = $this->db->get('taller_semestre_horarios AS tsh');
        return ($result->num_rows() > 0 ) ? $result->result_array() : false;
    }
    public function get_by_taller_sem($id){
        $this->db->where('taller_semestre_id' , $id);
        $this->db->order_by('dia');
        $result = $this->db->get('taller_semestre_horarios');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }
}

?>
