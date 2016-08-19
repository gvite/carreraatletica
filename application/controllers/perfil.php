<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Perfil extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->helper(array('url', 'sesion', 'date'));
        $data['no_menu'] = true;
        $data['js'][] = 'js/perfil.js';
        $this->load->model('usuarios_model');
        //$this->load->helper('sesion');
        $this->load->view('main/header_view', $data);
        $data['alumno'] = $this->usuarios_model->get(get_id());
        $this->load->view("main/perfil_view", $data);
        $this->load->view('main/footer_view', '');
    }
    
    public function update($id = ''){
        $this->load->library('form_validation');
        $this->form_validation->set_rules("name_user", "Nombre", "xss|required");
        $this->form_validation->set_rules("paterno_user", "Apellido Paterno", "xss|required");
        $this->form_validation->set_rules("materno_user", "Apellido Materno", "xss");
        $this->form_validation->set_rules("correo_user", "E-Mail", "xss|required|valid_email|callback_valida_email");
        $this->form_validation->set_rules("nacimiento_user", "Fecha de Nacimiento", "xss|required|callback_valida_fecha");
        $this->form_validation->set_message("required", "Introduce %s");
        $this->form_validation->set_message("valid_email", "Introduce un correo v&aacute;lido");
        
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $this->load->helper('date_helper');
            $this->load->helper('sesion');
            $data = array(
                'nombre' => $this->input->post('name_user'),
                'paterno' => $this->input->post('paterno_user'),
                'materno' => $this->input->post('materno_user'),
                'email' => $this->input->post('correo_user'),
                'nacimiento' => exchange_date($this->input->post('nacimiento_user')),
            );
            $this->load->model('usuarios_model');
            
            if($this->usuarios_model->update($id , $data)){
                echo json_encode(array('status' => 'MSG', 'type' => 'success', 'message' => 'La operaci&oacuten se realiz&oacute; con &eacute;xito'));
            }else{
                echo json_encode(array('status' => 'MSG', 'type' => 'error', 'message' => 'La operaci&oacute;n no se pudo realizar, intentelo mas tarde.'));
            }
        }
    }
    public function valida_email($email){
        $this->load->model('usuarios_model');
        if($this->usuarios_model->check_email_no_user($email , get_id())){
            return true;
        }else{
            $this->form_validation->set_message('valida_email', 'El correo que proporcionaste ya existe, intenta poner uno nuevo.');
            return false;
        }
    }
    
    public function valida_fecha($fecha) {
        $fecha_array = explode('-', $fecha);
        if (count($fecha_array) === 3 && checkdate($fecha_array[1], $fecha_array[0], $fecha_array[2])) {
            $date1 = new DateTime($fecha);
            $date2 = new DateTime(date('d-m-Y'));
            if ($date1 < $date2) {
                return true;
            } else {
                $this->form_validation->set_message('valida_fecha', 'WOW vienes del futuro, mmm :| ya enserio pon una fecha de nacimiento correcta.');
                return false;
            }
        } else {
            $this->form_validation->set_message('valida_fecha', 'La fecha de nacimiento no es v&aacute;lida. Debe estar en este formato "dd-mm-yyyy"');
            return false;
        }
    }
}
