<?php

class Reportes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_alumnos_talleres($tipo_alumno, $carrera, $semestre) {
        $this->db->select('t.taller, count(u.id) as num_alumnos');
        $this->db->join('taller_semestre as ts', 'ts.taller_id=t.id');
        $this->db->join('baucher_talleres as bt', 'bt.taller_semestre_id=ts.id');
        $this->db->join('baucher as b', 'b.id=bt.baucher_id');
        $this->db->join('usuarios as u', 'u.id=b.usuario_id');
        if ($tipo_alumno == 1) {
            $this->db->where_in('u.tipo_usuario_id', array('2', '3'));
        } else {
            $this->db->where('u.tipo_usuario_id', $tipo_alumno);
        }
        if ($carrera != 0) {
            $this->db->join('datos_alumnos_ex as dae', 'dae.usuario_id=u.id');
            $this->db->where('dae.carrera_id', $carrera);
        }
        if ($semestre != 0) {
            $this->db->where('ts.semestre_id', $semestre);
        }
        $this->db->group_by('t.id');
        $result = $this->db->get('talleres as t');
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

    public function getPresupuesto1($inicio, $fin) {
        $this->db->select('bt.id,t.taller,bt.aportacion,b.folio_caja,b.fecha_caja,b.folio,c.carrera,u.nombre,u.paterno,u.materno,u.tipo_usuario_id');
        $this->db->join('taller_semestre AS ts' , 'bt.taller_semestre_id = ts.id' , 'LEFT');
        $this->db->join('talleres AS t' , 'ts.taller_id = t.id' , 'LEFT');
        $this->db->join('baucher AS b' , 'bt.baucher_id = b.id' , 'LEFT');
        $this->db->join('usuarios AS u' , 'b.usuario_id = u.id' , 'LEFT');
        $this->db->join('datos_alumnos_ex AS da' , 'da.usuario_id = u.id' , 'LEFT');
        $this->db->join('carreras AS c' , 'da.carrera_id = c.id' , 'LEFT');
        $this->db->where('b.status' , '1');
        $this->db->where('b.fecha_caja >=' , $inicio);
        $this->db->where('b.fecha_caja <=' , $fin);
        $this->db->order_by('t.taller,u.paterno');
        $result = $this->db->get('baucher_talleres AS bt');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }
    
    public function getUsuarioByTaller($taller_id , $tipo_usuario , $semestre_id = '',$fecha_ini = '', $fecha_fin = ''){
        $this->db->select('COUNT(bt.id) as count_user, SUM(bt.aportacion) as suma');
        $this->db->join('taller_semestre as ts' , 'ts.id=bt.taller_semestre_id');
        $this->db->join('baucher as b' , 'b.id=bt.baucher_id');
        $this->db->join('usuarios as u' , 'u.id=b.usuario_id');
        $this->db->where('b.status' , '1');
        $this->db->where('u.tipo_usuario_id' , $tipo_usuario);
        $this->db->where('ts.taller_id' , $taller_id);
        if($semestre_id !== ''){
            $this->db->where('ts.semestre_id' , $semestre_id);
        }
        if($fecha_ini !== ''){
            $this->db->where('b.fecha_caja >=' , $fecha_ini);
            $this->db->where('b.fecha_caja <=' , $fecha_fin);
        }
        $result = $this->db->get('baucher_talleres as bt');
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }

    public function getSumBySemestreMonth($semestre , $inicio = '' , $fin = ''){
        $this->db->select('SUM(bt.aportacion) as suma , MONTH(b.fecha_caja) as mes');
        $this->db->join('baucher as b' , 'b.id=bt.baucher_id');
        $this->db->join('taller_semestre as ts' , 'ts.id=bt.taller_semestre_id');
        $this->db->join('semestres as s' , 's.id=ts.semestre_id');
        $this->db->where('b.status' , '1');
        $this->db->where('s.id' , $semestre);
        if($inicio !== ''){
            $this->db->where('b.fecha_caja >=' , $inicio);
            $this->db->where('b.fecha_caja <=' , $fin);
        }
        $this->db->group_by('MONTH(b.fecha_caja)');
        $result = $this->db->get('baucher_talleres as bt');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    } 
}
