<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cambia_contra extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->helper(array('url', 'sesion', 'date'));
        $data['no_menu'] = true;
        $data['js'][] = 'js/cambia_contra.js';
        $this->load->view('main/header_view', $data);
        $this->load->view("acceso/cambia_contra_view", '');
        $this->load->view('main/footer_view', '');
    }

    public function cambia() {
        $this->load->helper(array('url', 'sesion'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules("pass", "Contrase&ntilde;a", "xss|required|callback_valida_pass[" . $this->input->post('repass') . "]");
        $this->form_validation->set_rules("repass", "Repite contrase&ntilde;a", "xss|required");
        $this->form_validation->set_rules("pass_ant", "Nombre", "xss|required");
        $this->form_validation->set_message("required", "Introduce %s");
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $this->load->model('usuarios_model');
            $usuario = $this->usuarios_model->get(get_id());
            if ($usuario['pass'] === $this->input->post('pass_ant')){
                $data = array(
                    'pass' => $this->input->post('repass')
                );
                if($this->usuarios_model->update(get_id() , $data)){
                    echo json_encode(array('status' => 'OK'));
                }else{
                    echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'Ocurrio un error interno.'));
                }
            }else{
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'La contrase&ntilde;a anterior no es v&aacute;lida.'));
            }
        }
    }

    public function contrasenia_exito() {
        $this->load->helper(array('url', 'sesion', 'date'));
        $data['no_menu'] = true;
        $this->load->view('main/header_view', $data);
        $this->load->view("acceso/cambia_contra_exito_view", '');
        $this->load->view('main/footer_view', '');
    }
    
    public function valida_pass($pass1, $pass2) {
        if ($pass1 !== $pass2) {
            $this->form_validation->set_message('valida_pass', 'Las contrase&ntilde;as no coinsiden');
            return false;
        } else {
            return true;
        }
    }

}

?>
