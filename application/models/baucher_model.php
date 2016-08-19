<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Baucher_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('sesion');
    }

    public function get_by_taller_status($taller_semestre_id, $status , $status2 = '') {
        $this->db->select('b.*');
        $this->db->join('baucher_talleres as bt', 'b.id=bt.baucher_id');
        $this->db->where('bt.taller_semestre_id', $taller_semestre_id);
        $this->db->where('status', $status);
        if($status2 !== ''){
            $this->db->or_where('status', $status2);
        }
        $result = $this->db->get('baucher as b');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }

    public function get_by_status_user($status) {
        $this->db->where('status', $status);
        $this->db->where('usuario_id', get_id());
        $result = $this->db->get('baucher');
        return ($result->num_rows() > 0) ? $result->result_array() : FALSE;
    }

    public function get_by_taller_user_insc($taller_semestre_id) {
        $this->db->select('b.*');
        $this->db->join('baucher_talleres as bt', 'b.id=bt.baucher_id');
        $this->db->where('bt.taller_semestre_id', $taller_semestre_id);
        $this->db->where('b.usuario_id', get_id());
        $result = $this->db->get('baucher as b');
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }

    public function get_status_by_user($taller_semestre_id, $user_id = '') {
        $this->db->select('b.status');
        $this->db->join('baucher_talleres as bt', 'b.id=bt.baucher_id');
        $this->db->where('bt.taller_semestre_id', $taller_semestre_id);
        if ($user_id === '') {
            $this->db->where('b.usuario_id', get_id());
        } else {
            $this->db->where('b.usuario_id', $user_id);
        }
        $this->db->limit(1);
        $result = $this->db->get('baucher as b');
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }

    public function count_inscripcion_by_taller($taller_semestre_id) {
        $this->db->select('b.status');
        $this->db->join('baucher_talleres as bt', 'b.id=bt.baucher_id');
        $this->db->where('bt.taller_semestre_id', $taller_semestre_id);
        $this->db->where('b.usuario_id', get_id());
        $this->db->limit(1);
        $result = $this->db->get('baucher as b');
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }

    public function update_status($id, $status) {
        $data = array(
            'status' => $status
        );
        $this->db->where('id', $id);
        return ($this->db->update('baucher', $data)) ? true : false;
    }

    public function update($id , $data){
        $this->db->where('id' , $id);
        return ($this->db->update('baucher' , $data)) ? true : false;
    }
    
    public function check_folio_free($folio) {
        $this->db->select('id');
        $this->db->where('folio', $folio);
        $this->db->limit(1);
        $result = $this->db->get('baucher');
        return ($result->num_rows() == 0) ? true : false;
    }

    public function insert($data) {
        return ($this->db->insert('baucher', $data)) ? $this->db->insert_id() : false;
    }
    
    public function delete($id) {
        return ($this->db->delete('baucher', array("id"=>$id))) ? true : false;
    }

    public function get($id) {
        $this->db->where('id', $id);
        $this->db->limit(1);
        $result = $this->db->get('baucher');
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }

    public function get_by_folio($folio) {
        $this->db->select('b.* , u.tipo_usuario_id as tipo');
        $this->db->join('usuarios as u', 'u.id=b.usuario_id');
        $this->db->where('b.folio', $folio);
        $this->db->limit(1);
        $result = $this->db->get('baucher as b');
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }

    function get_by_user_semestre($semestre_id, $user_id = '') {
        //$this->db->select('b.*,t.taller');
        $this->db->select('b.*');
        $this->db->join('baucher_talleres as bt', 'bt.baucher_id=b.id');
        $this->db->join('taller_semestre as ts', 'ts.id=bt.taller_semestre_id');
        //$this->db->join('taller as t', 'bt.taller_id=t.taller');
        $this->db->where('ts.semestre_id', $semestre_id);
        if ($user_id === '') {
            $this->db->where('b.usuario_id', get_id());
        } else {
            $this->db->where('b.usuario_id', $user_id);
        }
        $this->db->group_by('b.id');
        $result = $this->db->get('baucher as b');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }
    
    function get_by_user_semestre_fake($semestre_id) {
        //$this->db->select('b.*,t.taller');
        $this->db->select('b.*');
        $this->db->join('baucher_talleres as bt', 'bt.baucher_id=b.id');
        $this->db->join('taller_semestre as ts', 'ts.id=bt.taller_semestre_id');
        $this->db->join('semestres as s', 'ts.semestre_id = s.id');
        //$this->db->join('taller as t', 'bt.taller_id=t.taller');
        $this->db->where('ts.semestre_id', $semestre_id);
        $this->db->where('s.ini_insc >= b.fecha_expedicion');
        $this->db->where('s.ini_sem <= b.fecha_expedicion');
        $this->db->group_by('b.id');
        $result = $this->db->get('baucher as b');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }
    
    function get_user_by_baucher($baucher_id){
        $this->db->select('u.*');
        $this->db->join('usuarios as u' , 'b.usuario_id = u.id');
        $this->db->limit(1);
        $this->db->where('b.id' , $baucher_id);
        $result = $this->db->get('baucher as b');
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }
    public function get_last_query() {
        $last_query = '';
        if (isset($this->db->queries)) {
            foreach ($this->db->queries AS $query) {
                $last_query .= "\n\n" . $query;
            }
        }
        return $last_query;
    }
}

?>
