<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Talleres_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        $result = $this->db->get('talleres');
        return ($result->num_rows() > 0) ? $result->result_array() : FALSE;
    }

    public function insert($data) {
        return ($this->db->insert('talleres', $data)) ? $this->db->insert_id() : false;
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return ($this->db->update('talleres', $data)) ? true : false;
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return ($this->db->delete('talleres')) ? true : false;
    }

    public function get($id) {
        $this->db->where('id', $id);
        $this->db->limit(1);
        $result = $this->db->get('talleres');
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }

    public function check_delete($id) {
        $this->db->select('id');
        $this->db->where('taller_id', $id);
        $result = $this->db->get('taller_semestre');
        return ($result->num_rows() === 0) ? true : false;
    }

    public function get_all_by_semestre($semestre_id) {
        $this->db->select('t.id,t.taller,t.informacion,t.objetivo,t.requisitos,t.costo_alumno,t.costo_exalumno,t.costo_trabajador,t.costo_externo');
        $this->db->join('taller_semestre as ts', 'ts.taller_id=t.id');
        $this->db->where('ts.semestre_id', $semestre_id);
        $this->db->where('t.status', 1);
        $this->db->group_by('t.id');
        $result = $this->db->get('talleres as t');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }
    
    public function get_all_by_semestre_order($semestre_id) {
        $this->db->select('t.id,t.taller');
        $this->db->join('taller_semestre as ts', 'ts.taller_id=t.id');
        $this->db->where('ts.semestre_id', $semestre_id);
        $this->db->where('t.status', 1);
        $this->db->group_by('t.id');
        $this->db->order_by('t.taller');
        $result = $this->db->get('talleres as t');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }

    public function get_costo_by_tipo($taller_id, $user_tipo) {
        $this->db->select('costo_alumno,costo_exalumno,costo_trabajador,costo_externo');
        $this->db->where('id', $taller_id);
        $this->db->limit(1);
        $result = $this->db->get('talleres');
        if ($result->num_rows() > 0) {
            $row = $result->row_array();
            $costo = false;
            if ($user_tipo == 2 || $user_tipo == 1) {
                $costo = $row['costo_alumno'];
            } else if ($user_tipo == 3) {
                $costo = $row['costo_exalumno'];
            } else if ($user_tipo == 4) {
                $costo = $row['costo_trabajador'];
            } else {
                $costo = $row['costo_externo'];
            }
            return $costo;
        } else {
            return false;
        }
    }

}

?>
