<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cambio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('semestres_model');
        $this->load->model('talleres_semestre_model');
        $this->load->model('baucher_model');
        $this->load->model('taller_semestre_horario_model');
        $this->load->model('baucher_talleres_model');
        
        $this->load->helper(array('url', 'sesion', 'date'));
    }

    public function index($baucher_id = null , $taller_id = null) {
        if($baucher_id !== null && $taller_id !== null){
            
            
            
            $baucher_taller = $this->baucher_talleres_model->get_by_baucher_taller($baucher_id,$taller_id);
            if($baucher_taller){
                $data['active'] = 'validacion';
                $data['semestre_actual'] = $this->semestres_model->get_actual();
                if ($data['semestre_actual']) {
                    $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
                } else {
                    $data['puede_inscribir'] = false;
                }
                $data['js'] = 'js/cambia_taller.js';
                $this->load->view('main/header_view', $data);
                $data['talleres'] = $this->talleres_semestre_model->get_by_semestre($data['semestre_actual']['id']);
                $data['alumno'] = $this->baucher_model->get_user_by_baucher($baucher_id);
                if (is_array($data['talleres'])) {
                    //$this->load->model('baucher_model');
                    foreach ($data['talleres'] as $key => $taller) {
                        //$this->check_status_taller($taller['id']);
                        $count = $this->baucher_talleres_model->count_insc($taller['id']);
                        $data['talleres'][$key]['insc_count'] = $count;
                        $data['talleres'][$key]['percent'] = ($count * 100) / $taller['cupo'];
                        $data['talleres'][$key]['status'] = false;
                        if ($data['talleres'][$key]['percent'] < 100) {
                            $data['talleres'][$key]['status'] = $this->baucher_model->get_status_by_user($taller['id'], $data['alumno']['id']);
                        }
                        $data['talleres'][$key]['puede_mas'] = true;
                        if ($taller['taller_id'] == 11 && $this->baucher_talleres_model->count_taller_insc(11, $data['semestre_actual']['id'] , $data['alumno']['id']) > 0) {
                            $data['talleres'][$key]['puede_mas'] = false;
                        }
                        $data['talleres'][$key]['horarios'] = $this->taller_semestre_horario_model->get_by_taller_sem($taller['id']);
                        $data['talleres'][$key]['costo'] = '';
                        $data['talleres'][$key]['num_trabajador'] = 0;
                        switch ($data['alumno']['tipo_usuario_id']) {
                            case 2:
                                $data['talleres'][$key]['costo'] = $taller['costo_alumno'];
                                break;
                            case 3:
                                $data['talleres'][$key]['costo'] = $taller['costo_exalumno'];
                                break;
                            case 4:
                                $data['talleres'][$key]['num_trabajador'] = $this->baucher_talleres_model->count_trabajadores_insc($taller['id']);
                                $data['talleres'][$key]['costo'] = $taller['costo_trabajador'];
                                break;
                            case 5:
                                $data['talleres'][$key]['costo'] = $taller['costo_externo'];
                                break;
                        }
                    }
                }
                $baucher = $this->baucher_model->get($baucher_id);
                $data['taller_id'] = $taller_id;
                $data['baucher_id'] = $baucher_id;
                $data['baucher_folio'] = $baucher['folio'];
                $this->load->view('admin/cambio_view', $data);
                $this->load->view('main/footer_view', '');
            }else{
                show_404();
            }
        }else{
            show_404();
        }
    }
    public function cambiar(){
        
        $this->form_validation->set_rules("taller_ant", "Taller anterior", "xss|required");
        $this->form_validation->set_rules("taller_new", "Taller actual", "xss|required");
        $this->form_validation->set_rules("baucher_id", "Baucher", "xss|required");
        $this->form_validation->set_rules("user_id", "Usuario", "xss|required");
        $this->form_validation->set_rules("user_type", "Tipo de usuario", "xss|required");
        $this->form_validation->set_rules("baucher_folio", "Baucher folio", "xss|required");
        $this->form_validation->set_message("required", "Lo siento ocurri&oacute; un error, intenta refrescar la p&aacute;gina.");
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $baucher_id = $this->input->post('baucher_id');
            $taller_ant = $this->input->post('taller_ant');
            $taller_new = $this->input->post('taller_new');
            $user_id = $this->input->post('user_id');
            $user_type = $this->input->post('user_type');
            $baucher_folio = $this->input->post('baucher_folio');
            $exito = true;
            $errors = array();
            $status = $this->baucher_model->get_status_by_user($taller_new , $user_id);
            $taller = $this->talleres_semestre_model->get_with_name($taller_new);
            if (is_array($taller)) {
                if ($status == false || $status['status'] == 3) {
                    $count = $this->baucher_talleres_model->count_insc($taller_new);
                    if ($count >= $taller['cupo']) {
                        $errors[] = 'El cupo est&aacute; lleno para ' . $taller['taller'];
                        //$errors[count($errors) - 1]['id'] = $id;
                        $exito = false;
                    }
                    if ($user_type == 4 && $this->baucher_talleres_model->count_trabajadores_insc($taller_new) >= 2) {
                        $errors[] = 'Lo siento solo se pueden inscribir un maximo de 2 trabajadores en cada taller.';
                        //$errors[count($errors) - 1]['id'] = $id;
                        $exito = false;
                    }
                } else {
                    if ($status['status'] == 0) {
                        $errors[] = 'Materia inscrita anteriormente (Sin validaci&oacute;n): ' . $taller['taller'];
                    } else if ($status['status'] == 1) {
                        $errors[] = 'Materia inscrita anterior mente: ' . $taller['taller'];
                    } else {
                        $errors[] = 'Aun no puedes inscribir la materia, intentalo mas tarde: ' . $taller['taller'];
                    }
                    $exito = false;
                }
            } else {
                $errors [] = 'Taller no Encontrado. No Jueges con el sistema.';
                $exito = false;
            }
            if($exito){
                $this->load->model('baucher_talleres_model');
                if ($this->baucher_talleres_model->update_by_baucher_taller($baucher_id,$taller_ant,$taller_new)) {
                    $route = str_replace("\\", "/", FCPATH) . "uploads/comprobantes/" . $user_id . '/';
                    if (file_exists($route . 'pdf_' . $baucher_id . '.pdf')) {
                        unlink($route . 'pdf_' . $baucher_id . '.pdf');
                    }
                    echo json_encode(array('status' => 'OK' , 'folio' => $baucher_folio));
                } else {
                    echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'ERROR 0x001: No se pudieron guardar los datos correctamente.'));
                }
            }else{
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => implode('<br />', $errors)));
            }
        }
    }
}

?>
