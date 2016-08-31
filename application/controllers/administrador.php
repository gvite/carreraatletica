<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administrador extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
    }

    public function index() {
        $this->load->helper(array('url', 'sesion'));
        $this->load->model('talleres_model');
        $this->load->model('semestres_model');
        $semestre_actual = $this->semestres_model->get_actual();
        $tiempo_res = strtotime($semestre_actual['ini_insc']) - mktime();
        if ($tiempo_res > 0) {
            $tiempo_res = array(
                'dias' => (int) ((int) $tiempo_res / 86400),
                'horas' => (int) (((int) $tiempo_res % 86400) / 3600),
                'minutos' => (int) (((int) $tiempo_res % 86400) % 3600 / 60),
                'segundos' => (int) ((((int) $tiempo_res % 86400) % 3600) % 60)
            );
            if ($tiempo_res['dias'] < 10) {
                $dias = '0' . $tiempo_res['dias'];
            } else {
                $dias = $tiempo_res['dias'];
            }
            if ($tiempo_res['horas'] < 10) {
                $horas = '0' . $tiempo_res['horas'];
            } else {
                $horas = $tiempo_res['horas'];
            }
            if ($tiempo_res['minutos'] < 10) {
                $minutos = '0' . $tiempo_res['minutos'];
            } else {
                $minutos = $tiempo_res['minutos'];
            }
            if ($tiempo_res['segundos'] < 10) {
                $segundos = '0' . $tiempo_res['segundos'];
            } else {
                $segundos = $tiempo_res['segundos'];
            }
            $tiempo = $dias . ':' . $horas . ":" . $minutos . ":" . $segundos;
        } else {
            $tiempo = false;
        }
        $data = array(
            'semestre_actual' => $semestre_actual,
            'tiempo' => $tiempo,
            'active' => 'inicio',
            'js' => array('js/inicio.js')
        );
        $talleres = false;
        if (get_id()) {
            if ($data['semestre_actual']) {
                $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
            } else {
                $data['puede_inscribir'] = false;
            }
        } else {
            $data['js'][] = 'js/acceso.js';
        }
        if ($data['semestre_actual']) {
            $talleres = $this->talleres_model->get_all_by_semestre($data['semestre_actual']['id']);
        }
        $this->load->view('main/header_admin_view', $data);
        if ($talleres === false) {
            $data['talleres'] = false;
        } else {
            $data['talleres'] = $talleres;
        }
        $this->load->view('main/inicio_view', $data);
        if (!get_id()) {
            $this->load->view('acceso/login_view', $data);
        }
        $this->load->view('main/footer_view', '');
    }
}

?>
