<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Actividades extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->helper(array('url', 'sesion'));
        $this->load->model('semestres_model');
        $data['semestre_actual'] = $this->semestres_model->get_actual();
        if ($data['semestre_actual']) {
            $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
        } else {
            $data['puede_inscribir'] = false;
        }
        $data['active'] = 'actividades';
        $data['js'] = 'js/actividades.js';
        $this->load->view('main/header_view', $data);
        $this->load->helper('date');
        $data['semestres'] = $this->semestres_model->get_all();
        $this->load->view("admin/actividades_view", $data);
        $this->load->view('main/footer_view', '');
    }

    public function talleres() {
        
    }

}

?>
