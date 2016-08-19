<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Alumnos extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('semestres_model');
        $this->load->helper(array('url', 'sesion', 'date'));
        $data['active'] = 'alumnos';
        $data['semestres'] = $this->semestres_model->get_all();
        $data['semestre_actual'] = $this->semestres_model->get_actual();
        if ($data['semestre_actual']) {
            $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
        } else {
            $data['puede_inscribir'] = false;
        }
        $data['js'][] = 'js/plugins/jquery.dataTables.min.js';
        $data['js'][] = 'js/alumnos.js';
        $this->load->view('main/header_view', $data);
        $this->load->view("admin/alumnos_view", $data);
        $this->load->view('main/footer_view', '');
    }

    public function busqueda() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("alumnos", "tipo busqueda.", "xss|required");
        $alumno = $this->input->post('alumnos');
        if ($alumno == '1') {
            $this->form_validation->set_rules("tipo_alumnos", "tipo de alumno(s).", "xss|required");
            $this->form_validation->set_rules("semestres", "Semestre(s).", "xss|required");
            $tipo_alumnos = $this->input->post('tipo_alumnos');
            if (is_array($tipo_alumnos)) {
                foreach ($tipo_alumnos as $tipo) {
                    switch ($tipo) {
                        case 2:
                            $this->form_validation->set_rules("alumnos_data", "campos de alumnos.", "xss|required");
                            break;
                        case 3:
                            $this->form_validation->set_rules("exalumnos_data", "campos de ex-alumnos.", "xss|required");
                            break;
                        case 4:
                            $this->form_validation->set_rules("trabajadores_data", "campos de trabajadores.", "xss|required");
                            break;
                        case 5:
                            $this->form_validation->set_rules("externos_data", "campos de externos.", "xss|required");
                            break;
                    }
                }
            }
        }
        $this->form_validation->set_message("required", "Selecciona %s");
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $this->load->model('usuarios_model');
            $this->load->model('baucher_talleres_model');
            if ($alumno == '0') {
                $data['alumnos'] = $this->usuarios_model->get_all_alumnos();
                if (is_array($data['alumnos'])) {
                    foreach ($data['alumnos'] as $key => $alumno) {
                        $data['alumnos'][$key]['materias'] = $this->baucher_talleres_model->count_validadas_by_usuario($alumno['id']);
                        $data['alumnos'][$key]['materias_sin'] = $this->baucher_talleres_model->count_no_validadas_by_usuario($alumno['id']);
                    }
                }
                $data['exalumnos'] = $this->usuarios_model->get_all_exalumnos();
                if (is_array($data['exalumnos'])) {
                    foreach ($data['exalumnos'] as $key => $alumno) {
                        $data['exalumnos'][$key]['materias'] = $this->baucher_talleres_model->count_validadas_by_usuario($alumno['id']);
                        $data['exalumnos'][$key]['materias_sin'] = $this->baucher_talleres_model->count_no_validadas_by_usuario($alumno['id']);
                    }
                }
                $data['trabajadores'] = $this->usuarios_model->get_all_trabajadores();
                if (is_array($data['trabajadores'])) {
                    foreach ($data['trabajadores'] as $key => $alumno) {
                        $data['trabajadores'][$key]['materias'] = $this->baucher_talleres_model->count_validadas_by_usuario($alumno['id']);
                        $data['trabajadores'][$key]['materias_sin'] = $this->baucher_talleres_model->count_no_validadas_by_usuario($alumno['id']);
                    }
                }
                $data['externos'] = $this->usuarios_model->get_all_externos();
                if (is_array($data['externos'])) {
                    foreach ($data['externos'] as $key => $alumno) {
                        $data['externos'][$key]['materias'] = $this->baucher_talleres_model->count_validadas_by_usuario($alumno['id']);
                        $data['externos'][$key]['materias_sin'] = $this->baucher_talleres_model->count_no_validadas_by_usuario($alumno['id']);
                    }
                }
                $data['tipo_alumnos'] = array(2, 3, 4, 5);
                $data['alumnos_data'] = array('nombre', 'no_cuenta', 'ingreso', 'carrera', 'materias', 'materias_sin');
                $data['exalumnos_data'] = array('nombre', 'no_cuenta', 'egreso', 'carrera', 'materias', 'materias_sin');
                $data['trabajadores_data'] = array('nombre', 'no_trabajador', 'turno', 'area', 'materias', 'materias_sin');
                $data['externos_data'] = array('nombre', 'ocupacion', 'direccion', 'telefono', 'materias', 'materias_sin');
            } else {
                foreach ($tipo_alumnos as $tipo) {
                    switch ($tipo) {
                        case 2:
                            $data['alumnos'] = $this->usuarios_model->get_alumnos_by_query();
                            if (is_array($data['alumnos'])) {
                                foreach ($data['alumnos'] as $key => $alumno) {
                                    $data['alumnos'][$key]['materias'] = $this->baucher_talleres_model->count_validadas_by_usuario($alumno['id']);
                                    $data['alumnos'][$key]['materias_sin'] = $this->baucher_talleres_model->count_no_validadas_by_usuario($alumno['id']);
                                }
                            }
                            $data['alumnos_data'] = $this->input->post('alumnos_data');
                            break;
                        case 3:
                            $data['exalumnos'] = $this->usuarios_model->get_exalumnos_by_query();
                            if (is_array($data['exalumnos'])) {
                                foreach ($data['exalumnos'] as $key => $alumno) {
                                    $data['exalumnos'][$key]['materias'] = $this->baucher_talleres_model->count_validadas_by_usuario($alumno['id']);
                                    $data['exalumnos'][$key]['materias_sin'] = $this->baucher_talleres_model->count_no_validadas_by_usuario($alumno['id']);
                                }
                            }
                            $data['exalumnos_data'] = $this->input->post('exalumnos_data');
                            break;
                        case 4:
                            $data['trabajadores'] = $this->usuarios_model->get_trabajadores_by_query();
                            if (is_array($data['trabajadores'])) {
                                foreach ($data['trabajadores'] as $key => $alumno) {
                                    $data['trabajadores'][$key]['materias'] = $this->baucher_talleres_model->count_validadas_by_usuario($alumno['id']);
                                    $data['trabajadores'][$key]['materias_sin'] = $this->baucher_talleres_model->count_no_validadas_by_usuario($alumno['id']);
                                }
                            }
                            $data['trabajadores_data'] = $this->input->post('trabajadores_data');
                            break;
                        case 5:
                            $data['externos'] = $this->usuarios_model->get_externos_by_query();
                            if (is_array($data['externos'])) {
                                foreach ($data['externos'] as $key => $alumno) {
                                    $data['externos'][$key]['materias'] = $this->baucher_talleres_model->count_validadas_by_usuario($alumno['id']);
                                    $data['externos'][$key]['materias_sin'] = $this->baucher_talleres_model->count_no_validadas_by_usuario($alumno['id']);
                                }
                            }
                            $data['externos_data'] = $this->input->post('externos_data');
                            break;
                    }
                }
                $data['tipo_alumnos'] = $this->input->post('tipo_alumnos');
            }
            $content = $this->load->view('admin/lista_busqueda_view', $data, true);
            echo json_encode(array('status' => 'OK', 'content' => $content));
        }
    }

}

?>
