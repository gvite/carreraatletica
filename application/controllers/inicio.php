<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inicio extends CI_Controller {

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
        $this->load->view('main/header_view', $data);
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

    public function check_status_talleres() {
        $this->load->model('semestres_model');
        $this->load->model('talleres_semestre_model');
        $this->load->model('baucher_model');

        $semestre_actual = $this->semestres_model->get_actual();
        if ($semestre_actual) {
            $talleres = $this->talleres_semestre_model->get_by_semestre($semestre_actual['id']);
            if (is_array($talleres)) {
                foreach ($talleres as $taller) {
                    $bauchers = $this->baucher_model->get_by_taller_status($taller['taller_id'], 2);
                    if (is_array($bauchers)) {
                        $now = mktime();
                        $termina_hora = 12;
                        foreach ($bauchers as $baucher) {
                            $date_aux = getdate(strtotime($baucher['fecha_expedicion']));
                            if ($date_aux['wday'] > 3) {
                                $date_termino_insc = mktime($termina_hora, 0, 0, $date_aux['mon'], $date_aux['mday'] + 5, $date_aux['year']);
                            } else if ($date_aux['wday'] == 0) {
                                $date_termino_insc = mktime($termina_hora, 0, 0, $date_aux['mon'], $date_aux['mday'] + 4, $date_aux['year']);
                            } else {
                                $date_termino_insc = mktime($termina_hora, 0, 0, $date_aux['mon'], $date_aux['mday'] + 3, $date_aux['year']);
                            }
                            if ($now > $date_termino_insc) {
                                $this->baucher_model->update_status($baucher['id'], 3);
                            }
                        }
                    }
                    $bauchers = $this->baucher_model->get_by_taller_status($taller['taller_id'], 0);
                    if (is_array($bauchers)) {
                        $now = mktime();
                        $termina_hora = 20;
                        foreach ($bauchers as $baucher) {
                            $date_aux = getdate(strtotime($baucher['fecha_expedicion']));
                            if ($date_aux['wday'] > 3) {
                                $date_termino_insc = mktime($termina_hora, 0, 0, $date_aux['mon'], $date_aux['mday'] + 4, $date_aux['year']);
                            } else if ($date_aux['wday'] == 0) {
                                $date_termino_insc = mktime($termina_hora, 0, 0, $date_aux['mon'], $date_aux['mday'] + 3, $date_aux['year']);
                            } else {
                                $date_termino_insc = mktime($termina_hora, 0, 0, $date_aux['mon'], $date_aux['mday'] + 2, $date_aux['year']);
                            }
                            if ($now > $date_termino_insc) {
                                $this->baucher_model->update_status($baucher['id'], 2);
                            }
                        }
                    }
                }
            }
        }
    }

}
