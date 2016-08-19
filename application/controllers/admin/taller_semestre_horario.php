<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Taller_semestre_horario extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function insert() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("id", "Nombre", "xss|required");
        $this->form_validation->set_rules("dia", "Apellido Paterno", "xss|required");
        $this->form_validation->set_rules("inicio", "Apellido Paterno", "xss|required");
        $this->form_validation->set_rules("termino", "Apellido Paterno", "xss|required");
        $this->form_validation->set_message("required", "Ocurrio un error de configuracion.");
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $data = array(
                'taller_semestre_id' => $this->input->post('id'),
                'dia' => $this->input->post('dia'),
                'inicio' => $this->input->post('inicio'),
                'termino' => $this->input->post('termino')
            );
            $this->load->model('taller_semestre_horario_model');
            $id = $this->taller_semestre_horario_model->insert($data);
            if ($id) {
                echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.', 'id' => $id));
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se pudieron guardar los datos'));
            }
        }
    }

    public function update($id = '') {
        if ($id != '') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules("id", "Nombre", "xss|required");
            $this->form_validation->set_rules("dia", "Apellido Paterno", "xss|required");
            $this->form_validation->set_rules("inicio", "Apellido Paterno", "xss|required");
            $this->form_validation->set_rules("termino", "Apellido Paterno", "xss|required");
            $this->form_validation->set_message("required", "Ocurrio un error de configuracion.");
            if ($this->form_validation->run() === FALSE) {
                $errors = validation_errors();
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
            } else {
                $data = array(
                    'taller_semestre_id' => $this->input->post('id'),
                    'dia' => $this->input->post('dia'),
                    'inicio' => $this->input->post('inicio'),
                    'termino' => $this->input->post('termino')
                );
                $this->load->model('taller_semestre_horario_model');
                if ($this->taller_semestre_horario_model->update($id , $data)) {
                    echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.', 'id' => $id));
                } else {
                    echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se pudieron guardar los datos'));
                }
            }
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }
    public function delete($id = ''){
        if($id != ''){
            $this->load->model('taller_semestre_horario_model');
                if ($this->taller_semestre_horario_model->delete($id)) {
                    echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se borraron correctamente.', 'id' => $id));
                } else {
                    echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se pudieron borrar los datos'));
                }
        }else{
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }
    public function get_by_semestre($semestre_id){
        if($semestre_id != ''){
            $this->load->model('taller_semestre_horario_model');
            $talleres = $this->taller_semestre_horario_model->get_by_semestre_talleres($semestre_id);
            echo json_encode(array('status' => 'OK', 'talleres' => $talleres));
        }else{
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }
}

?>
