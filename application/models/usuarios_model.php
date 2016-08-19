<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function valida($nickname, $pass) {
        $this->db->select('u.id,tu.tipo,u.nombre,tu.id as type_user, u.nacimiento');
        $this->db->join('tipo_usuario as tu', 'tu.id=u.tipo_usuario_id');
        $this->db->where('u.nickname', $nickname);
        $this->db->where('u.pass', $pass);
        $this->db->where('u.status', 1);
        $this->db->limit(1);
        $result = $this->db->get('usuarios as u');
        return($result->num_rows() > 0) ? $result->row_array() : false;
    }

    function update($id, $data) {
        $this->db->where('id', $id);
        return ($this->db->update('usuarios', $data)) ? true : false;
    }

    function insert($data) {
        return ($this->db->insert('usuarios', $data)) ? $this->db->insert_id() : false;
    }

    function check_user($user) {
        $this->db->select('id');
        $this->db->where('nickname', $user);
        $this->db->limit(1);
        $result = $this->db->get('usuarios');
        return ($result->num_rows() > 0) ? false : true;
    }

    function check_email($email) {
        $this->db->select('id');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $result = $this->db->get('usuarios');
        return ($result->num_rows() > 0) ? false : true;
    }

    function check_email_no_user($email, $user) {
        $this->db->select('id');
        $this->db->where('email', $email);
        $this->db->where('id !=', $user);
        $this->db->limit(1);
        $result = $this->db->get('usuarios');
        return ($result->num_rows() > 0) ? false : true;
    }

    public function get($id , $select = '*') {
        $this->db->select($select);
        $this->db->where('id', $id);
        $this->db->limit(1);
        $result = $this->db->get('usuarios');
        return ($result->num_rows() > 0) ? $result->row_array() : false;
    }

    public function get_all_alumnos() {
        $this->db->select('u.id,u.nombre,u.paterno,u.materno,u.status,u.nickname,da.no_cuenta,da.ingreso_egreso as ingreso,c.carrera');
        $this->db->join('datos_alumnos_ex as da', 'da.usuario_id=u.id');
        $this->db->join('carreras as c', 'c.id=da.carrera_id');
        $this->db->where('u.tipo_usuario_id', 2);
        $this->db->order_by('u.paterno');
        $result = $this->db->get('usuarios as u');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }

    public function get_all_exalumnos() {
        $this->db->select('u.id,u.nombre,u.paterno,u.materno,u.status,u.nickname,da.no_cuenta,da.ingreso_egreso as egreso,c.carrera');
        $this->db->join('datos_alumnos_ex as da', 'da.usuario_id=u.id');
        $this->db->join('carreras as c', 'c.id=da.carrera_id');
        $this->db->where('u.tipo_usuario_id', 3);
        $this->db->order_by('u.paterno');
        $result = $this->db->get('usuarios as u');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }

    public function get_all_trabajadores() {
        $this->db->select('u.id,u.nombre,u.paterno,u.materno,u.status,u.nickname,dt.no_trabajador,dt.turno,dt.area');
        $this->db->join('datos_trabajador as dt', 'dt.usuario_id=u.id');
        $this->db->where('u.tipo_usuario_id', 4);
        $this->db->order_by('u.paterno');
        $result = $this->db->get('usuarios as u');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }

    public function get_all_externos() {
        $this->db->select('u.id,u.nombre,u.paterno,u.materno,u.status,u.nickname,de.direccion,de.telefono,o.ocupacion');
        $this->db->join('datos_externo as de', 'de.usuario_id=u.id');
        $this->db->join('ocupaciones as o', 'o.id=de.ocupacion_id');
        $this->db->where('u.tipo_usuario_id', 5);
        $this->db->order_by('u.paterno');
        $result = $this->db->get('usuarios as u');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }

    public function get_alumnos_by_query() {
        $alumnos_data = $this->input->post('alumnos_data');
        $repite = false;
        $repite2 = false;
        $this->db->select('u.id');
        foreach ($alumnos_data as $data) {
            if ($data === 'nombre') {
                $this->db->select('u.nombre,u.paterno,u.materno');
                $this->db->order_by('u.paterno');
            } else if (($data === 'no_cuenta' || $data === 'ingreso') && !$repite) {
                $repite = true;
                $this->db->select('da.no_cuenta,da.ingreso_egreso as ingreso');
                if (!$repite2) {
                    $this->db->join('datos_alumnos_ex as da', 'da.usuario_id=u.id');
                    $repite2 = true;
                }
            } else if ($data == 'carrera') {
                $this->db->select('c.carrera');
                if (!$repite2) {
                    $this->db->join('datos_alumnos_ex as da', 'da.usuario_id=u.id');
                    $repite2 = true;
                }
                $this->db->join('carreras as c', 'c.id=da.carrera_id');
            }
        }
        $this->db->join('baucher as b', 'b.usuario_id=u.id');
        $this->db->join('baucher_talleres as bt', 'b.id=bt.baucher_id');
        $this->db->join('taller_semestre as ts', 'bt.taller_semestre_id=ts.id');
        $semestres = $this->input->post('semestres');
        $where = '(';
        foreach ($semestres as $semestre) {
            $where .= 'ts.semestre_id=' . $semestre . ' OR ';
        }
        $where = trim($where, ' OR ') . ')';
        $this->db->where($where);
        $this->db->where('u.tipo_usuario_id', 2);
        $result = $this->db->get('usuarios as u');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }

    public function get_exalumnos_by_query() {
        $exalumnos_data = $this->input->post('exalumnos_data');
        $repite = false;
        $this->db->select('u.id');
        foreach ($exalumnos_data as $data) {
            if ($data === 'nombre') {
                $this->db->select('u.nombre,u.paterno,u.materno');
                $this->db->order_by('u.paterno');
            } else if (($data === 'no_cuenta' || $data === 'ingreso') && !$repite) {
                $repite = true;
                $this->db->select('da.no_cuenta,da.ingreso_egreso as egreso');
                $this->db->join('datos_alumnos_ex as da', 'da.usuario_id=u.id');
            } else if ($data == 'carrera') {
                $this->db->select('c.carrera');
                $this->db->join('carreras as c', 'c.id=da.carrera_id');
            }
        }
        $this->db->join('baucher as b', 'b.usuario_id=u.id');
        $this->db->join('baucher_talleres as bt', 'b.id=bt.baucher_id');
        $this->db->join('taller_semestre as ts', 'bt.taller_semestre_id=ts.id');
        $semestres = $this->input->post('semestres');
        $where = '(';
        foreach ($semestres as $semestre) {
            $where .= 'ts.semestre_id=' . $semestre . ' OR ';
        }
        $where = trim($where, ' OR ') . ')';
        $this->db->where($where);
        $this->db->where('u.tipo_usuario_id', 3);
        $result = $this->db->get('usuarios as u');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }

    public function get_trabajadores_by_query() {
        $trabajadores_data = $this->input->post('trabajadores_data');
        $repite = false;
        $this->db->select('u.id');
        foreach ($trabajadores_data as $data) {
            if ($data === 'nombre') {
                $this->db->select('u.nombre,u.paterno,u.materno');
                $this->db->order_by('u.paterno');
            } else if (($data === 'no_trabajador' || $data === 'turno' || $data === 'area') && !$repite) {
                $repite = true;
                $this->db->select('dt.no_trabajador,dt.turno,dt.area');
                $this->db->join('datos_trabajador as dt', 'dt.usuario_id=u.id');
            }
        }
        $this->db->join('baucher as b', 'b.usuario_id=u.id');
        $this->db->join('baucher_talleres as bt', 'b.id=bt.baucher_id');
        $this->db->join('taller_semestre as ts', 'bt.taller_semestre_id=ts.id');
        $semestres = $this->input->post('semestres');
        $where = '(';
        foreach ($semestres as $semestre) {
            $where .= 'ts.semestre_id=' . $semestre . ' OR ';
        }
        $where = trim($where, ' OR ') . ')';
        $this->db->where($where);
        $this->db->where('u.tipo_usuario_id', 4);
        $result = $this->db->get('usuarios as u');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }

    public function get_externos_by_query() {
        $externos_data = $this->input->post('externos_data');
        $repite = false;
        $this->db->select('u.id');
        
        foreach ($externos_data as $data) {
            if ($data === 'nombre') {
                $this->db->select('u.nombre,u.paterno,u.materno');
                $this->db->order_by('u.paterno');
            } else {
                if(!$repite){
                    $this->db->join('datos_externo as de', 'de.usuario_id=u.id');
                    $repite = true;
                }
                if (($data === 'telefono' || $data === 'direccion')) {
                    $this->db->select('de.telefono,de.direccion');
                } else if ($data === 'ocupacion') {
                    $this->db->select('o.ocupacion');
                    $this->db->join('ocupaciones as o', 'o.id=de.ocupacion_id');
                }
            }
        }
        $this->db->join('baucher as b', 'b.usuario_id=u.id');
        $this->db->join('baucher_talleres as bt', 'b.id=bt.baucher_id');
        $this->db->join('taller_semestre as ts', 'bt.taller_semestre_id=ts.id');
        $semestres = $this->input->post('semestres');
        $where = '(';
        foreach ($semestres as $semestre) {
            $where .= 'ts.semestre_id=' . $semestre . ' OR ';
        }
        $where = trim($where, ' OR ') . ')';
        $this->db->where($where);
        $this->db->where('u.tipo_usuario_id', 5);
        $result = $this->db->get('usuarios as u');
        return ($result->num_rows() > 0) ? $result->result_array() : false;
    }

    function get_by_email($email) {
        $this->db->where('email', $email);
        $this->db->limit(1);
        $result = $this->db->get('usuarios');
        return ($result->num_rows() > 0) ? $result->row_array() : true;
    }

    function get_user($nickname) {
        $this->db->where('nickname', $nickname);
        $this->db->limit(1);
        $result = $this->db->get('usuarios');
        return ($result->num_rows() > 0) ? $result->row_array() : true;
    }

    function get_user_by_cta($nickname) {
        $this->db->join('datos_alumnos_ex as da', 'da.usuario_id=u.id');
        $this->db->where('da.no_cuenta', $nickname);
        $this->db->limit(1);
        $result = $this->db->get('usuarios as u');
        return ($result->num_rows() > 0) ? $result->row_array() : true;
    }
    
    function get_users_by_name($name){
        $names = explode(" ", $name);
        $where = array();
        foreach($names as $key => $nam){
            $where[] = '(nombre like "%' . $nam . '%" OR paterno like "%' . $nam . '%" OR materno like "%' . $nam . '%")';
        }
        $this->db->where(implode(' and ' , $where));
        $result = $this->db->get('usuarios as u');
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
}

?>
