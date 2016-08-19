<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profesores extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['active'] = 'actividades';
        $this->load->model('semestres_model');
        $data['semestre_actual'] = $this->semestres_model->get_actual();
        if ($data['semestre_actual']) {
            $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
        } else {
            $data['puede_inscribir'] = false;
        }
        $data['js'] = 'js/profesores.js';
        $this->load->view('main/header_view', $data);

        $this->load->model('profesores_model');
        $data['profesores'] = $this->profesores_model->get_all();
        $this->load->view("admin/profesores_view", $data);
        $this->load->view('main/footer_view', '');
    }

    public function insert() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("nombre", "Nombre", "xss|required");
        $this->form_validation->set_rules("paterno", "Apellido Paterno", "xss|required");
        $this->form_validation->set_message("required", "Introduce %s");
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $data = array(
                'nombre' => $this->input->post('nombre'),
                'paterno' => $this->input->post('paterno'),
                'materno' => $this->input->post('materno')
            );
            $this->load->model('profesores_model');
            $data['id'] = $this->profesores_model->insert($data);
            if ($data['id']) {
                echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.', 'profesor' => $data, 'tipo' => 0));
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se pudieron guardar los datos'));
            }
        }
    }

    public function update($id = '') {
        if ($id != '') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules("nombre", "Nombre", "xss|required");
            $this->form_validation->set_rules("paterno", "Apellido Paterno", "xss|required");
            $this->form_validation->set_message("required", "Introduce %s");
            if ($this->form_validation->run() === FALSE) {
                $errors = validation_errors();
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
            } else {
                $this->load->helper('date');
                $data = array(
                    'nombre' => $this->input->post('nombre'),
                    'paterno' => $this->input->post('paterno'),
                    'materno' => $this->input->post('materno')
                );
                $this->load->model('profesores_model');
                if ($this->profesores_model->update($id, $data)) {
                    $data['id'] = $id;
                    echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.', 'profesor' => $data, 'tipo' => 1));
                } else {
                    echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se pudieron guardar los datos'));
                }
            }
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }

    public function delete($id = '') {
        if ($id !== '') {
            $this->load->model('profesores_model');
            if ($this->profesores_model->check_delete($id)) {
                if ($this->profesores_model->delete($id)) {
                    echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'El profesor se elimin&oacute; correctamente.'));
                } else {
                    echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'ERROR 0x002: No se pudo eliminar el profesor elegido.'));
                }
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'ERROR 0x004: No se pudo eliminar el profesor porque ya tiene asignado talleres.'));
            }
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }

}

?>
