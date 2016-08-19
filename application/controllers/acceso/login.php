<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function valida() {
        $nickname = $this->input->post('user');
        $pass = $this->input->post('pass');
        $this->load->model('usuarios_model');
        $user = $this->usuarios_model->valida($nickname, $pass);
        if ($user) {
            $this->load->helper('sesion');
            set_user($nickname);
            set_name($user['nombre']);
            set_type($user['tipo']);
            set_type_user($user['type_user']);
            set_id($user['id']);
            set_date($user["nacimiento"]);
            echo json_encode(array('status' => 'OK'));
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', 'message' => 'Usuario y/o contrase&ntilde;a no v&aacute;lidos'));
        }
    }

    public function logout() {
        $this->load->helper(array('url', 'sesion'));
        clean_session();
        redirect('inicio','refresh');
    }

    public function recuperar_contra() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("email", "Email", "xss|required|callback_valida_email");
        $this->form_validation->set_message("required", "Introduce %s");
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => validation_errors()));
        } else {
            srand();
            $contra_rand = '';
            for ($i = 0; $i < 8; $i++) {
                $rand = rand(1, 3);
                srand();
                switch ($rand) {
                    case 1:
                        $rand = rand(48, 57);
                        break;
                    case 2:
                        $rand = rand(65, 90);
                        break;
                    case 3:
                        $rand = rand(97, 122);
                        break;
                    default:
                        $rand = 42;
                        break;
                }
                $contra_rand .= chr($rand);
            }
            $this->load->model('usuarios_model');
            $email = $this->input->post('email');
            $usuario = $this->usuarios_model->get_by_email($email);
            $data = array(
                'pass' => md5($contra_rand)
            );
            if($this->usuarios_model->update($usuario['id'] , $data)){
                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'ssl://smtp.googlemail.com'; # Not tls://
                $config['smtp_port'] = 465;
                $config['starttls'] = TRUE;
                $config['smtp_user'] = 'actividades.culturales.fesa@gmail.com';
                $config['smtp_pass'] = 'actividades2014';
                $config['smtp_timeout'] = 5;
                $config['newline'] = "\r\n";
                $config['charset'] = 'utf-8';
                $config['mailtype'] = 'html';
                $asunto = 'Recuperar Contraseña';
                $data = array(
                    'contra' => $contra_rand,
                    'usuario' => $usuario
                );
                $mensaje = $this->load->view('acceso/recuperar_contra_view', $data, TRUE);
                $this->load->library('email', $config);
                $this->email->from("fesar_cultura@unam.mx", "");
                $this->email->to($email);
                $this->email->subject($asunto);
                $this->email->message($mensaje);
                if($this->email->send()){
                    echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Revisa tu correo para ver la nueva contrase&ntilde;a'));
                }else{
                    echo json_encode(array('status' => 'MSG', 'type' => 'error', 'message' => 'No se pudo enviar al correo.' . $this->email->print_debugger()));
                }
            }else{
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'ocurrio un error al actualizar la contrase&ntilde;a'));
            }
            
        }
    }
    public function valida_email($email){
        $this->load->model('usuarios_model');
        if($this->usuarios_model->check_email($email)){
            $this->form_validation->set_message('valida_email', 'El correo que proporcionaste no existe.');
            return false;
        }else{
            return true;
        }
    }
}

?>
