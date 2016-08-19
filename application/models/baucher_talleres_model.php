<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Baucher_talleres_model extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_baucher($baucher_id) {
        $this->db->select('bt.aportacion,bt.id as bt_id,ts.id, ts.grupo,t.taller,t.costo_alumno,t.costo_exalumno,t.costo_trabajador,t.costo_externo,p.nombre,p.paterno,p.materno,s.salon');
        $this->db->join('taller_semestre as ts', 'bt.taller_semestre_id=ts.id');
        $this->db->join('talleres as t', 't.id=ts.taller_id');
        $this->db->join('profesores as p', 'p.id=ts.profesor_id');
        $this->db->join('salones as s', 's.id=ts.salon_id');
        $this->db->where('bt.baucher_id', $baucher_id);
        $result = $this->db->get('baucher_talleres as bt');
        return ($result->num_rows() > 0) ? $result->result_array() : FALSE;
    }
    
    public function get_one_by_baucher_taller($baucher_id) {
        $this->db->select('*');
        $this->db->where('bt.baucher_id', $baucher_id);
        $result = $this->db->get('baucher_talleres as bt');
        return ($result->num_rows() > 0) ? $result->result_array() : FALSE;
    }

    public function check_punish($taller_semestre_id) {
        $this->load->helper('sesion');
        $this->db->select('bt.id');
        $this->db->join('baucher as b', 'b.id=bt.baucher_id');
        $this->db->where('bt.taller_semestre_id', $taller_semestre_id);
        $this->db->where('b.usuario_id', get_id());
        $this->db->limit(1);
        $result = $this->db->get('baucher_talleres as bt');
        return ($result->num_rows() === 0) ? true : false;
    }

    public function count_insc($id) {
        $result = $this->db->query('SELECT u.id FROM usuarios AS u INNER JOIN baucher AS b ON b.usuario_id = u.id INNER JOIN baucher_talleres AS bt ON bt.baucher_id = b.id WHERE bt.taller_semestre_id = ' . $id . ' AND (b.`status` = 0 OR b.`status` = 1) GROUP BY b.id');
        return $result->num_rows();
    }
    
    public function count_insc_validados($id) {
        $result = $this->db->query('SELECT u.id FROM usuarios AS u INNER JOIN baucher AS b ON b.usuario_id = u.id INNER JOIN baucher_talleres AS bt ON bt.baucher_id = b.id WHERE bt.taller_semestre_id = ' . $id . ' AND b.`status` = 1 GROUP BY b.id');
        return $result->num_rows();
    }

    public function count_trabajadores_insc($id) {
        $result = $this->db->query('SELECT u.id FROM usuarios AS u INNER JOIN baucher AS b ON b.usuario_id = u.id INNER JOIN baucher_talleres AS bt ON bt.baucher_id = b.id WHERE bt.taller_semestre_id = ' . $id . ' AND (b.`status` = 0 OR b.`status` = 1) AND u.tipo_usuario_id=4 GROUP BY b.id');
        return $result->num_rows();
    }

    public function count_taller_insc($id, $semestre_id , $user_id = '') {
        $this->load->helper('sesion');
        $this->db->select('u.id');
        $this->db->join('baucher AS b', 'b.usuario_id = u.id');
        $this->db->join('baucher_talleres AS bt', 'bt.baucher_id = b.id');
        $this->db->join('taller_semestre as ts', 'ts.id = bt.taller_semestre_id');
        $this->db->where('ts.taller_id', $id);
        $this->db->where('b.status !=', 3);
        $this->db->where('ts.semestre_id', $semestre_id);
        if($user_id === ''){
            $this->db->where('u.id' , get_id());
        }else{
            $this->db->where('u.id' , $user_id);
        }
        $result = $this->db->get('usuarios AS u');
        return $result->num_rows();
    }

    public function get_by_taller($id) {
        $result = $this->db->query('SELECT u.nombre, u.paterno, u.materno , b.folio , b.status FROM usuarios AS u INNER JOIN baucher AS b ON b.usuario_id = u.id INNER JOIN baucher_talleres AS bt ON bt.baucher_id = b.id WHERE bt.taller_semestre_id = ' . $id . ' AND (b.`status` = 0 OR b.`status` = 1) GROUP BY b.id ORDER BY u.paterno ASC');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }

    public function get_by_taller_insc($id) {
        $result = $this->db->query('SELECT u.nombre, u.paterno, u.materno , b.folio , b.status FROM usuarios AS u INNER JOIN baucher AS b ON b.usuario_id = u.id INNER JOIN baucher_talleres AS bt ON bt.baucher_id = b.id WHERE bt.taller_semestre_id = ' . $id . ' AND (b.`status` = 1) GROUP BY b.id ORDER BY u.paterno ASC');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }

    public function insert($data) {
        return ($this->db->insert('baucher_talleres', $data)) ? $this->db->insert_id() : false;
    }
    public function update($id, $data){
        $this->db->where('id' , $id);
        return ($this->db->update('baucher_talleres', $data)) ? true : false;
    }

    function count_validadas_by_usuario($usuario_id) {
        $this->db->select('bt.id');
        $this->db->join('baucher as b', 'b.id=bt.baucher_id');
        $this->db->where('b.status', 1);
        $this->db->where('b.usuario_id', $usuario_id);
        $result = $this->db->get('baucher_talleres as bt');
        return $result->num_rows();
    }

    function count_no_validadas_by_usuario($usuario_id) {
        $this->db->select('bt.id');
        $this->db->join('baucher as b', 'b.id=bt.baucher_id');
        $this->db->where('(b.status=3 OR b.status=2 OR b.status=0)');
        $this->db->where('b.usuario_id', $usuario_id);
        $result = $this->db->get('baucher_talleres as bt');
        return $result->num_rows();
    }
    
    function get_by_semestre($semestre_id){
        $this->db->select('bt.id,u.tipo_usuario_id,t.costo_alumno,t.costo_exalumno,t.costo_trabajador,t.costo_externo');
        $this->db->join('baucher AS b', 'bt.baucher_id = b.id');
        $this->db->join('usuarios AS u', 'b.usuario_id = u.id');
        $this->db->join('taller_semestre AS ts', 'bt.taller_semestre_id = ts.id');
        $this->db->join('talleres AS t', 'ts.taller_id = t.id');
        $this->db->where('ts.semestre_id' , $semestre_id);
        $result = $this->db->get('baucher_talleres AS bt');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }
    
    function update_by_baucher_taller($baucher_id , $taller_ant , $taller_new){
        $this->db->where('baucher_id' , $baucher_id);
        $this->db->where('taller_semestre_id' , $taller_ant);
        return ($this->db->update('baucher_talleres', array('taller_semestre_id' => $taller_new))) ? true : false;
    }
    function get_by_baucher_taller($baucher_id , $taller_id){
        $this->db->where('baucher_id' , $baucher_id);
        $this->db->where('taller_semestre_id' , $taller_id);
        $this->db->limit(1);
        $result = $this->db->get('baucher_talleres');
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }
    function get_by_semestre_fakes($semestre_id){
        $this->db->select('bt.id,u.tipo_usuario_id,t.costo_alumno,t.costo_exalumno,t.costo_trabajador,t.costo_externo');
        $this->db->join('baucher AS b', 'bt.baucher_id = b.id');
        $this->db->join('usuarios AS u', 'b.usuario_id = u.id');
        $this->db->join('taller_semestre AS ts', 'bt.taller_semestre_id = ts.id');
        $this->db->join('talleres AS t', 'ts.taller_id = t.id');
        $this->db->join('semestres AS s', 'ts.semestre_id = s.id');
        $this->db->where('s.id' , $semestre_id);
        $this->db->where('s.ini_insc >= b.fecha_expedicion');
        $this->db->where('s.ini_sem <= b.fecha_expedicion');
        $result = $this->db->get('baucher_talleres AS bt');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
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
    
    public function delete_by_baucher($baucher){
        
        return ($this->db->delete('baucher_talleres' , array("baucher_id" => $baucher)))?true:false;
    }
}

?>
