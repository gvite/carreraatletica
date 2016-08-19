<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Limpiar extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('taller_semestre_horario_model');
        $this->load->model('semestres_model');
        $this->load->model('baucher_model');
        $this->load->model('baucher_talleres_model');
        $this->load->library('archivos');
    }

    public function index() {
        $data['semestre_actual'] = $this->semestres_model->get_actual();
        $data['active'] = 'limpiar';
        $data['js'][] = 'js/limpiar.js';
        if ($data['semestre_actual']) {
            $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
            $data['bauchers'] = $this->baucher_model->get_by_user_semestre_fake($data['semestre_actual']['id']);
            if(is_array($data['bauchers'])){
                foreach($data['bauchers'] as $key => $baucher){
                    $data['bauchers'][$key]['talleres'] = $this->baucher_talleres_model->get_by_baucher($baucher['id']);
                }
            }
        } else {
            $data['puede_inscribir'] = false;
            $data['bauchers'] = array();
        }
        $this->load->view('main/header_view', $data);
        $this->load->view("admin/limpiar_view", $data);
        $this->load->view('main/footer_view', '');
    }
    public function delete(){
        $id = $this->input->post('id');
        if($this->baucher_talleres_model->delete_by_baucher($id)){
            $this->baucher_model->delete($id);
        }
        echo json_encode(array('status' => 'MSG', 'type' => 'success', 'message' => 'Inscripción eliminada.'));
    }
}

?>
