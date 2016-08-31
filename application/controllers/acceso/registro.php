<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Registro extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('baucher_model');
        $this->load->model('talleres_model');
    }

    public function index() {
        $this->load->helper(array('url', 'sesion'));
        $this->load->model('carreras_model');
        $this->load->model('ocupaciones_model');
        $data['js'][] = 'js/registro.js';
        $data['js'][] = 'js/acceso.js';
        $data['no_menu'] = true;
        $this->load->view('main/header_view', $data);
        $data['carreras'] = $this->carreras_model->get_all();
        $data['ocupaciones'] = $this->ocupaciones_model->get_all();
        $this->load->view('acceso/registro_view', $data);
        $this->load->view('acceso/login_view', $data);
        $this->load->view('main/footer_view', '');
    }

    public function insert() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("user", "Usuario", "xss|required|callback_valida_user");
        $this->form_validation->set_rules("pass", "Contrase&ntilde;a", "xss|required|callback_valida_pass[" . $this->input->post('repass') . "]");
        $this->form_validation->set_rules("repass", "Repite contrase&ntilde;a", "xss|required");
        $this->form_validation->set_rules("name_user", "Nombre", "xss|required");
        $this->form_validation->set_rules("paterno_user", "Apellido Paterno", "xss|required");
        $this->form_validation->set_rules("materno_user", "Apellido Materno", "xss");
        $this->form_validation->set_rules("correo_user", "E-Mail", "xss|required|valid_email|callback_valida_email");
        $this->form_validation->set_rules("nacimiento_user", "Fecha de Nacimiento", "xss|required|callback_valida_fecha");
        $this->form_validation->set_rules("type_user", "Tipo de usuario", "xss|required|is_natural_no_zero");
        $this->form_validation->set_rules("sexo_user", "Sexo de usuario", "xss|required");
        $this->form_validation->set_message("required", "Introduce %s");
        $this->form_validation->set_message("valid_email", "Introduce un correo v&aacute;lido");
        $this->form_validation->set_message("is_natural_no_zero", "Introduce un tipo de usuario v&aacute;lido");
        $tipo = $this->input->post('type_user');
        if ($tipo == 2 || $tipo == 3) {
            $this->form_validation->set_rules("num_cuenta", "N&uacute;mero de cuenta", "xss|required|exact_length[9]|callback_valida_nocta");
            $this->form_validation->set_rules("carrera", "Carrera", "xss|required");
            $this->form_validation->set_rules("ingreso_egreso", "Tipo de usuario", "xss|required");
            $this->form_validation->set_message("exact_length", "El n&uacute;mero de cuenta debe tener una longitud de 9");
        } else if ($tipo == 4) {
            $this->form_validation->set_rules("num_trabajador", "N&uacute;mero de Trabajador", "xss|required");
            $this->form_validation->set_rules("turno_prof", "Turno", "xss|required");
            $this->form_validation->set_rules("area", "Area", "xss|required");
        } else if ($tipo == 5) {
            $this->form_validation->set_rules("direccion", "Direcci&oacute;n", "xss|required");
            $this->form_validation->set_rules("ocupacion", "Ocupaci&oacute;n", "xss|required");
        }
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            //$this->db->trans_start();
            $this->load->helper('date_helper');
            $data = array(
                'nombre' => $this->input->post('name_user'),
                'paterno' => $this->input->post('paterno_user'),
                'materno' => $this->input->post('materno_user'),
                'nickname' => $this->input->post('user'),
                'pass' => $this->input->post('pass'),
                'email' => $this->input->post('correo_user'),
                'nacimiento' => exchange_date($this->input->post('nacimiento_user')),
                'telefono' => $this->input->post("telefono_user"),
                'sexo' => $this->input->post("sexo_user"),
                'status' => 1,
                'tipo_usuario_id' => $tipo
            );
            $this->load->model('usuarios_model');
            $id = $this->usuarios_model->insert($data);
            if($id){
                $data2 = array(
                    'usuario_id' => $id
                );
                $id_datos = false;
                if ($tipo == 2 || $tipo == 3) {
                    $data2['no_cuenta'] = $this->input->post('num_cuenta');
                    $data2['ingreso_egreso'] = $this->input->post('ingreso_egreso');
                    $data2['carrera_id'] = $this->input->post('carrera');
                    $this->load->model('datos_alumnos_ex_model');
                    $id_datos = $this->datos_alumnos_ex_model->insert($data2);
                } else if ($tipo == 4) {
                    $data2['no_trabajador'] = $this->input->post('num_trabajador');
                    $data2['turno'] = $this->input->post('turno_prof');
                    $data2['area'] = $this->input->post('area');
                    $this->load->model('datos_trabajador_model');
                    $id_datos = $this->datos_trabajador_model->insert($data2);
                } else if ($tipo == 5) {
                    $data2['direccion'] = $this->input->post('direccion');
                    $data2['ocupacion_id'] = $this->input->post('ocupacion');
                    $this->load->model('datos_externo_model');
                    $id_datos = $this->datos_externo_model->insert($data2);
                }
                if($id_datos){
                    $this->load->helper('sesion');
                    $this->load->model('tipo_usuario_model');
                    $type = $this->tipo_usuario_model->get($data['tipo_usuario_id']);
                    if($type !== false){
                        set_type($type['tipo']);
                        set_type_user($type['id']);
                    }
                    $baucher_id = $this->inscribir(2 , $id);
                    if($baucher_id){
                        //$this->db->trans_complete();
                        echo json_encode(array('status' => 'MSG', 'type' => 'success', 'message' => 'El Registro se realiz&oacute; con &eacute;xito',  "baucher" => $baucher_id , 'usr' => $id));
                    }else{
                        echo json_encode(array('status' => 'MSG', 'type' => 'error', 'message' => 'El Registro no se pudo realizar, intentelo mas tarde.'));    
                    }
                    
                }else{
                    echo json_encode(array('status' => 'MSG', 'type' => 'error', 'message' => 'El Registro no se pudo realizar, intentelo mas tarde.'));
                }
            }else{
                echo json_encode(array('status' => 'MSG', 'type' => 'error', 'message' => 'El Registro no se pudo realizar, intentelo mas tarde.'));
            }
        }
    }
    public function valida_user($user){
        $this->load->model('usuarios_model');
        if($this->usuarios_model->check_user($user)){
            return true;
        }else{
            $this->form_validation->set_message('valida_user', 'El usuario que proporcionaste ya existe, intenta poner uno nuevo.');
            return false;
        }
    }
    public function valida_email($email){
        $this->load->model('usuarios_model');
        if($this->usuarios_model->check_email($email)){
            return true;
        }else{
            $this->form_validation->set_message('valida_email', 'El correo que proporcionaste ya existe, intenta poner uno nuevo.');
            return false;
        }
    }
    public function valida_nocta($no_cuenta){
        $this->load->model('datos_alumnos_ex_model');
        if($this->datos_alumnos_ex_model->check_cta($no_cuenta)){
            return true;
        }else{
            $this->form_validation->set_message('valida_nocta', 'El número de cuenta que proporcionaste ya existe, intenta poner uno nuevo.');
            return false;
        }
    }
    public function valida_pass($pass1, $pass2) {
        if ($pass1 !== $pass2) {
            $this->form_validation->set_message('valida_pass', 'Las contrase&ntilde;as no coinsiden');
            return false;
        } else {
            return true;
        }
    }

    public function valida_fecha($fecha) {
        $fecha_array = explode('-', $fecha);
        if (count($fecha_array) === 3 && checkdate($fecha_array[1], $fecha_array[0], $fecha_array[2])) {
            $date1 = new DateTime($fecha);
            $date2 = new DateTime(date('d-m-Y'));
            if ($date1 < $date2) {
                $diff = $date2->diff($date1);
                $years = $diff->y;
                if($years >= 17){
                    return true;
                }else{
                    $this->form_validation->set_message('valida_fecha', 'No alcanzas el mínimo de edad (17 años) <br />para inscribir esta actividad.');
                    return false;    
                }
                
            } else {
                $this->form_validation->set_message('valida_fecha', 'WOW vienes del futuro, mmm :| ya enserio pon una fecha de nacimiento correcta.');
                return false;
            }
        } else {
            $this->form_validation->set_message('valida_fecha', 'La fecha de nacimiento no es v&aacute;lida. Debe estar en este formato "dd-mm-yyyy"');
            return false;
        }
    }

    public function inscribir($taller_id , $user_id) {
            
                $this->load->helper('sesion');
                $exito = true;
                $errors = array();
                $this->load->model('talleres_semestre_model');
                $this->load->model('baucher_talleres_model');
                
                $ids_aux = array();
                
                //$this->check_status_taller($id);
                $status = $this->baucher_model->get_status_by_user($taller_id);
                $taller = $this->talleres_semestre_model->get_with_name($taller_id);
                if (is_array($taller)) {
                    $ids_aux[] = array('id' => $taller_id , 'taller' => $taller['taller']);
                } else {
                    $errors [] = 'Actividad no Encontrada. No Jueges con el sistema.';
                }
                $baucher_id = false;
                if ($exito) {
                    $bauchers = array();
                    foreach ($ids_aux as $id) {
                        $folio = -1;
                        $exito = true;
                        while ($exito) {
                            $folio = mt_rand(1, 2147483647);
                            if ($this->baucher_model->check_folio_free($folio)) {
                                $exito = false;
                            }
                        }
                        $data = array(
                            'usuario_id' => $user_id,
                            'folio' => $folio,
                            'fecha_expedicion' => date('Y-m-d H:i:s'),
                            'status' => 0
                        );
                        $baucher_id = $this->baucher_model->insert($data);
                        if ($baucher_id) {
                            $exito = true;
                            $ts = $this->talleres_semestre_model->get($id['id'] , 'taller_id');
                            $costo = $this->talleres_model->get_costo_by_tipo($ts['taller_id'] , get_type_user());
                            if (date('Y-m-d') > date('Y-m-d', strtotime("2016-10-21"))) {
                                $costo += 20;
                            }
                            $data_aux = array(
                                'taller_semestre_id' => $id['id'],
                                'baucher_id' => $baucher_id,
                                'aportacion' => $costo
                            );
                            if ($this->baucher_talleres_model->insert($data_aux) === false) {
                                $exito = false;
                            }
                        }
                        if ($exito) {
                            $bauchers[] = array(
                                'id' => $baucher_id,
                                'folio' => str_pad($data['folio'], 11, "0", STR_PAD_LEFT),
                                'fecha_expedicion' => $data['fecha_expedicion'],
                                'taller' => $id['taller'],
                                'taller_id' => $id['id']
                            );
                        } else {
                            return false;
                        }
                    }
                    return $baucher_id;
                } else {
                    return false;
                }
        
    }

}

?>
