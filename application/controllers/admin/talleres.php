<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Talleres extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->helper(array('url', 'sesion'));
        $this->load->model('semestres_model');
        $this->load->model('talleres_model');
        $data['semestre_actual'] = $this->semestres_model->get_actual();
        if ($data['semestre_actual']) {
            $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
        } else {
            $data['puede_inscribir'] = false;
        }
        $data['active'] = 'actividades';
        $data['js'] = 'js/talleres.js';
        $this->load->view('main/header_view', $data);
        $this->load->helper('date');
        $data['talleres'] = $this->talleres_model->get_all();
        $this->load->view("admin/talleres_view", $data);
        $this->load->view('main/footer_view', '');
    }

    public function insert() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("taller", "Nombre", "xss|required");
        $this->form_validation->set_rules("costo_a", "Costo Alumno", "xss|required");
        $this->form_validation->set_rules("costo_e", "Costo Ex-Alumno", "xss|required");
        $this->form_validation->set_rules("costo_t", "Costo Trabajador", "xss|required");
        $this->form_validation->set_rules("costo_ex", "Costo Externo", "xss|required");
        $this->form_validation->set_message("required", "Introduce %s");
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $this->load->helper('date');
            $data = array(
                'taller' => $this->input->post('taller'),
                'costo_alumno' => $this->input->post('costo_a'),
                'costo_exalumno' => $this->input->post('costo_e'),
                'costo_trabajador' => $this->input->post('costo_t'),
                'costo_externo' => $this->input->post('costo_ex'),
                'objetivo' => $this->input->post('objetivo'),
                'requisitos' => $this->input->post('requisitos'),
                'informacion' => $this->input->post('informacion')
            );
            $this->load->model('talleres_model');
            $data['id'] = $this->talleres_model->insert($data);
            if ($data['id']) {
                echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.', 'taller' => $data, 'tipo' => 0));
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'ERROR 0x001: No se pudieron guardar los datos correctamente.'));
            }
        }
    }

    public function update($id = '') {
        if ($id !== '') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules("taller", "Nombre", "xss|required");
            $this->form_validation->set_rules("costo_a", "Costo Alumno", "xss|required");
            $this->form_validation->set_rules("costo_e", "Costo Ex-Alumno", "xss|required");
            $this->form_validation->set_rules("costo_t", "Costo Trabajador", "xss|required");
            $this->form_validation->set_rules("costo_ex", "Costo Externo", "xss|required");
            $this->form_validation->set_message("required", "Introduce %s");
            if ($this->form_validation->run() === FALSE) {
                $errors = validation_errors();
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
            } else {
                $this->load->helper('date');
                $data = array(
                    'taller' => $this->input->post('taller'),
                    'costo_alumno' => $this->input->post('costo_a'),
                    'costo_exalumno' => $this->input->post('costo_e'),
                    'costo_trabajador' => $this->input->post('costo_t'),
                    'costo_externo' => $this->input->post('costo_ex'),
                    'objetivo' => $this->input->post('objetivo'),
                    'requisitos' => $this->input->post('requisitos'),
                    'informacion' => $this->input->post('informacion')
                );
                $this->load->model('talleres_model');
                if ($this->talleres_model->update($id, $data)) {
                    $data['id'] = $id;
                    echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.', 'taller' => $data, 'tipo' => 1));
                } else {
                    echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'ERROR 0x001: No se pudieron guardar los datos correctamente.'));
                }
            }
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }

    public function delete($id = '') {
        if ($id !== '') {
            $this->load->model('talleres_model');
            if ($this->talleres_model->check_delete($id)) {
                if ($this->talleres_model->delete($id)) {
                    echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'El taller se elimin&oacute; correctamente.'));
                } else {
                    echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'ERROR 0x002: No se pudo eliminar el taller elegido.'));
                }
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se puede eliminar el taller porque ya esta asignado a un semestre.'));
            }
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }

    public function get_info($id = '') {
        if ($id != '') {
            $this->load->model('talleres_model');
            $data['taller'] = $this->talleres_model->get($id);
            if (is_array($data['taller'])) {
                $this->load->model('talleres_semestre_model');
                $this->load->model('taller_semestre_horario_model');
                $this->load->model('semestres_model');
                $semestre_actual = $this->semestres_model->get_actual();

                $data['taller']['talleres_semestre'] = $this->talleres_semestre_model->get_by_taller_semestre($data['taller']['id'], $semestre_actual['id']);
                if (is_array($data['taller']['talleres_semestre'])) {
                    foreach ($data['taller']['talleres_semestre'] as $key2 => $taller_semestre) {
                        $data['taller']['talleres_semestre'][$key2]['horarios'] = $this->taller_semestre_horario_model->get_by_taller_sem($taller_semestre['id']);
                    }
                }
            }
            $content = $this->load->view('main/info_view', $data, true);
            echo json_encode(array('status' => 'OK', 'content' => $content));
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }
    
    public function get_by_semestre($semestre = ''){
        if($semestre !== ''){
            
        }else{
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }

}

?>
