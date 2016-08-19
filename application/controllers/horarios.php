<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class horarios extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->helper(array('url', 'sesion'));
        $this->load->model('semestres_model');
        $this->load->helper('date');
        $data['semestre_actual'] = $this->semestres_model->get_actual();
        $data['active'] = 'horarios';
        $data['js'][] = 'js/horarios.js';
        if (!get_id()) {
            $data['js'][] = 'js/acceso.js';
        } else {
            if ($data['semestre_actual']) {
                $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
            } else {
                $data['puede_inscribir'] = false;
            }
        }
        $this->load->view('main/header_view', $data);
        $this->load->view("alumnos/horarios_view", $data);
        if (!get_id()) {
            $this->load->view('acceso/login_view', '');
        }
        $this->load->view('main/footer_view', '');
    }

    public function get_talleres_by_semestre($semestre_id = '') {
        if ($semestre_id != '') {
            $this->load->model('talleres_model');
            $talleres = $this->talleres_model->get_all_by_semestre($semestre_id);
            echo json_encode(array('status' => 'OK', 'talleres' => $talleres));
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }

}

?>
