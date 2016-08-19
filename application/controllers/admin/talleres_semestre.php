<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Talleres_semestre extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->helper(array('url', 'sesion', 'date'));
        $this->load->model('semestres_model');
        $this->load->model('talleres_model');
        $this->load->model('salones_model');
        $this->load->model('profesores_model');
        $data['js'][] = 'js/plugins/jquery.qtip.min.js';
        $data['js'][] = 'js/talleres_semestre.js';
        $data['semestre_actual'] = $this->semestres_model->get_actual();
        if ($data['semestre_actual']) {
            $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
        } else {
            $data['puede_inscribir'] = false;
        }
        $data['active'] = 'actividades';
        $this->load->view('main/header_view', $data);
        $data['semestres'] = $this->semestres_model->get_all();
        $data['talleres'] = $this->talleres_model->get_all();
        $data['salones'] = $this->salones_model->get_all();
        $data['profesores'] = $this->profesores_model->get_all();
        $this->load->view("admin/talleres_semestre_view", $data);
        $this->load->view('main/footer_view', '');
    }

    public function get_talleres($semestre_id = '') {
        if ($semestre_id !== '') {
            $this->load->model('talleres_semestre_model');
            $data['talleres'] = $this->talleres_semestre_model->get_by_semestre($semestre_id);
            $content = $this->load->view("admin/talleres_semestre_lista_view", $data, true);
            echo json_encode(array('status' => 'OK', 'content' => $content, 'talleres' => $data['talleres']));
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'Se encontro una inconsistencia'));
        }
    }
    
    public function get_talleres_group($semestre_id = '') {
        if ($semestre_id !== '') {
            $this->load->model('talleres_semestre_model');
            $data['talleres'] = $this->talleres_semestre_model->get_by_semestre_group($semestre_id);
            //$content = $this->load->view("admin/talleres_semestre_lista_view", $data, true);
            echo json_encode(array('status' => 'OK', 'talleres' => $data['talleres']));
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'Se encontro una inconsistencia'));
        }
    }

    public function insert() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("semestre", "Semestre", "xss|required|is_natural_no_zero");
        $this->form_validation->set_rules("taller", "Actividad", "xss|is_natural_no_zero");
        $this->form_validation->set_rules("profesor", "Profesor", "xss|is_natural_no_zero");
        $this->form_validation->set_rules("salon", "Lugar", "xss|is_natural_no_zero");
        $this->form_validation->set_rules("cupo", "Cupo", "xss|required|is_natural_no_zero");
        $this->form_validation->set_rules("grupo", "Grupo", "xss|required");
        $this->form_validation->set_message("required", "Introduce %s");
        $this->form_validation->set_message("is_natural_no_zero", "Valor no valido: %s");
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $data = array(
                'semestre_id' => $this->input->post('semestre'),
                'taller_id' => $this->input->post('taller'),
                'profesor_id' => $this->input->post('profesor'),
                'salon_id' => $this->input->post('salon'),
                'cupo' => $this->input->post('cupo'),
                'grupo' => $this->input->post('grupo')
            );
            $this->load->model('talleres_semestre_model');
            $id = $this->talleres_semestre_model->insert($data);
            if ($id) {
                $datos = $this->talleres_semestre_model->get_with_name($id);
                echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.', 'id' => $id, 'datos' => $datos, 'tipo' => 0));
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se pudieron guardar los datos'));
            }
        }
    }

    public function update($id) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("semestre", "Semestre", "xss|required|is_natural_no_zero");
        $this->form_validation->set_rules("taller", "Actividad", "xss|is_natural_no_zero");
        $this->form_validation->set_rules("profesor", "Profesor", "xss|is_natural_no_zero");
        $this->form_validation->set_rules("salon", "Lugar", "xss|is_natural_no_zero");
        $this->form_validation->set_rules("cupo", "Cupo", "xss|required|is_natural_no_zero");
        $this->form_validation->set_rules("grupo", "Grupo", "xss|required");
        $this->form_validation->set_message("required", "Introduce %s");
        $this->form_validation->set_message("is_natural_no_zero", "Valor no valido: %s");
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $data = array(
                'semestre_id' => $this->input->post('semestre'),
                'taller_id' => $this->input->post('taller'),
                'profesor_id' => $this->input->post('profesor'),
                'salon_id' => $this->input->post('salon'),
                'cupo' => $this->input->post('cupo'),
                'grupo' => $this->input->post('grupo')
            );
            $this->load->model('talleres_semestre_model');
            if ($this->talleres_semestre_model->update($id, $data)) {
                $datos = $this->talleres_semestre_model->get_with_name($id);
                echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.', 'id' => $id, 'datos' => $datos, 'tipo' => 1));
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se pudieron guardar los datos'));
            }
        }
    }

    function delete($id = '') {
        if ($id != '') {
            $this->load->model('talleres_semestre_model');
            if ($this->talleres_semestre_model->check_tsh_delete($id) && $this->talleres_semestre_model->check_bt_delete($id)) {
                if ($this->talleres_semestre_model->delete($id)) {
                    echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Se borro con &eacute;xito.'));
                } else {
                    echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se pud&oacute; borrar el registro.'));
                }
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se pud&oacute; borrar el registro, porque ya esta asigando a un horario o alguna inscripcion.'));
            }
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'Se encontro una inconsistencia'));
        }
    }

}

?>
