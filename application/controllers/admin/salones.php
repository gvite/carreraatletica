<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Salones extends CI_Controller {

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
        $data['js'] = 'js/salones.js';
        $this->load->view('main/header_view', $data);
        $this->load->model('salones_model');
        $data['salones'] = $this->salones_model->get_all();
        $this->load->view("admin/salones_view", $data);
        $this->load->view('main/footer_view', '');
    }

    public function insert() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("salon", "Lugar", "xss|required");
        $this->form_validation->set_message("required", "Introduce %s");
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $this->load->helper('date');
            $data = array(
                'salon' => $this->input->post('salon'),
                'cupo' => $this->input->post('cupo')
            );
            $this->load->model('salones_model');
            $data['id'] = $this->salones_model->insert($data);
            if ($data['id']) {
                echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.', 'salon' => $data, 'tipo' => 0));
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se pudieron guardar los datos'));
            }
        }
    }

    public function update($id = '') {
        if ($id != '') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules("salon", "Lugar", "xss|required");
            $this->form_validation->set_message("required", "Introduce %s");
            if ($this->form_validation->run() === FALSE) {
                $errors = validation_errors();
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
            } else {
                $this->load->helper('date');
                $data = array(
                    'salon' => $this->input->post('salon'),
                    'cupo' => $this->input->post('cupo')
                );
                $this->load->model('salones_model');
                if ($this->salones_model->update($id, $data)) {
                    $data['id'] = $id;
                    echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.', 'salon' => $data, 'tipo' => 1));
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
            $this->load->model('salones_model');
            if ($this->salones_model->check_delete($id)) {
                if ($this->salones_model->delete($id)) {
                    echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'El sal&oacute;n se elimin&oacute; correctamente.'));
                } else {
                    echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'ERROR 0x002: No se pudo eliminar el sal&oacute;n elegido.'));
                }
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'ERROR 0x004: No se pudo eliminar el sal&oacute;n porque ya tiene asignado talleres.'));
            }
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }

}

?>
