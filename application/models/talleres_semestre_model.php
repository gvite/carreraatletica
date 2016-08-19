<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Talleres_semestre_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function get($id , $select = ''){
        if($select != ''){
            $this->db->select($select);
        }
        $this->db->where('id' , $id);
        $this->db->limit(1);
        $result = $this->db->get('taller_semestre');
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }
    public function get_by_semestre($id) {
        $this->db->select('t.id as taller_id, ts.id,ts.cupo,ts.grupo , t.taller,t.edad_minima,p.nombre,p.paterno,p.materno,p.id as profesor_id,sa.salon,sa.id as salon_id,t.costo_alumno , t.costo_exalumno , t.costo_trabajador ,t.costo_externo');
        $this->db->join('talleres AS t' , 'ts.taller_id = t.id');
        $this->db->join('profesores AS p' , 'ts.profesor_id = p.id');
        $this->db->join('salones AS sa' , 'ts.salon_id = sa.id');
        $this->db->where('ts.semestre_id' , $id);
        $this->db->order_by('t.taller');
        $result = $this->db->get('taller_semestre as ts');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }
    public function get_by_semestre_group($id) {
        $this->db->select('t.id as taller_id, ts.id,ts.cupo,ts.grupo , t.taller,p.nombre,p.paterno,p.materno,p.id as profesor_id,sa.salon,sa.id as salon_id,t.costo_alumno , t.costo_exalumno , t.costo_trabajador ,t.costo_externo');
        $this->db->join('talleres AS t' , 'ts.taller_id = t.id');
        $this->db->join('profesores AS p' , 'ts.profesor_id = p.id');
        $this->db->join('salones AS sa' , 'ts.salon_id = sa.id');
        $this->db->where('ts.semestre_id' , $id);
        $this->db->order_by('t.taller');
        $this->db->group_by('t.id');
        $result = $this->db->get('taller_semestre as ts');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }
    public function get_by_taller_semestre($taller_id , $semestre_id){
        $this->db->select('id , grupo , cupo');
        $this->db->where('semestre_id' , $semestre_id);
        $this->db->where('taller_id' , $taller_id);
        $result = $this->db->get('taller_semestre');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }
    public function insert($data){
        return ($this->db->insert('taller_semestre' , $data)) ? $this->db->insert_id() : false;
    }
    public function update($id , $data){
        $this->db->where('id' , $id);
        return ($this->db->update('taller_semestre' , $data)) ? true : false;
    }
    public function get_with_name($id){
        $this->db->select('ts.id,ts.cupo,ts.grupo,t.taller,t.edad_minima,t.id as taller_id,s.salon, s.id as salon_id,p.id as profesor_id,p.paterno,p.nombre,p.materno,sm.ini_sem,sm.fin_sem,sm.semestre');
        $this->db->join('talleres AS t' , 'ts.taller_id = t.id');
        $this->db->join('profesores AS p' , 'ts.profesor_id = p.id');
        $this->db->join('salones AS s' , 'ts.salon_id = s.id');
        $this->db->join('semestres AS sm' , 'ts.semestre_id = sm.id');
        $this->db->where('ts.id' , $id);
        $this->db->limit(1);
        $result = $this->db->get('taller_semestre AS ts');
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }
    function delete($id){
        $this->db->where('id' , $id);
        return ($this->db->delete('taller_semestre')) ? true : false;
    }
    function check_tsh_delete($id){
        $this->db->select('id');
        $this->db->where('taller_semestre_id' , $id);
        $result = $this->db->get('taller_semestre_horarios as ts');
        return ($result->num_rows() == 0) ? true : false;
    }
    function check_bt_delete($id){
        $this->db->select('id');
        $this->db->where('taller_semestre_id' , $id);
        $result = $this->db->get('baucher_talleres as ts');
        return ($result->num_rows() == 0) ? true : false;
    }
}

?>
