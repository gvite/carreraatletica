<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Listas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('talleres_semestre_model');
        $this->load->model('semestres_model');
        $this->load->model('taller_semestre_horario_model');
        $this->load->library('archivos');
    }

    public function index() {
        $this->load->helper(array('url', 'sesion', 'date'));
        $data['active'] = 'listas';
        $data['semestres'] = $this->semestres_model->get_all();
        $data['semestre_actual'] = $this->semestres_model->get_actual();
        if ($data['semestre_actual']) {
            $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
        } else {
            $data['puede_inscribir'] = false;
        }
        $data['js'] = 'js/listas.js';
        $this->load->view('main/header_view', $data);
        $this->load->view('admin/listas_view', $data);
        $this->load->view('main/footer_view', '');
    }

    public function get_lista_talleres($semestre_id = '') {
        if ($semestre_id != '') {
            $data['talleres'] = $this->talleres_semestre_model->get_by_semestre($semestre_id);
            if (is_array($data['talleres'])) {
                $this->load->model('baucher_talleres_model');
                foreach ($data['talleres'] as $key => $taller) {
                    $count = $this->baucher_talleres_model->count_insc($taller['id']);
                    $data['talleres'][$key]['count'] = $count;
                }
            }
            $content = $this->load->view('admin/lista_semestre_view', $data, true);
            echo json_encode(array('status' => 'OK', 'content' => $content));
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', 'message' => 'Ocurrio un error inesperado o estas jugando con el sistema.'));
        }
    }

    public function get_lista_taller($taller_id = '') {
        if ($taller_id != '') {
            $this->load->model('baucher_talleres_model');
            $data['taller'] = $this->talleres_semestre_model->get_with_name($taller_id);
            if ($data['taller']) {
                $this->load->helper(array('url', 'sesion', 'date'));
                $data['active'] = 'listas';
                $data['semestre_actual'] = $this->semestres_model->get_actual();
                if ($data['semestre_actual']) {
                    $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
                } else {
                    $data['puede_inscribir'] = false;
                }
                $this->load->view('main/header_view', $data);
                $data['alumnos'] = $this->baucher_talleres_model->get_by_taller($taller_id);
                $this->load->view('admin/lista_taller_view', $data);
                $this->load->view('main/footer_view', '');
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    public function get_lista_asistencia($taller_id = '') {
        if ($taller_id != '') {
            $this->load->model('baucher_talleres_model');
            $data['taller'] = $this->talleres_semestre_model->get_with_name($taller_id);
            if ($data['taller']) {
                $this->load->helper(array('url', 'sesion', 'date'));
                $data['active'] = 'listas';
                $data['js'] = 'js/lista_main.js';
                $data['semestre_actual'] = $this->semestres_model->get_actual();
                if ($data['semestre_actual']) {
                    $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
                } else {
                    $data['puede_inscribir'] = false;
                }
                $this->load->view('main/header_view', $data);
                //$data['alumnos'] = $this->baucher_talleres_model->get_by_taller($taller_id);
                $data['id'] = $taller_id;
                $this->load->view('admin/lista_asistencia_main_view', $data);
                $this->load->view('main/footer_view', '');
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    public function get_pdf_lista($taller_id = '') {
        if ($taller_id != '') {
            ini_set("memory_limit","1024M");
            $this->load->model('baucher_talleres_model');
            $data['taller'] = $this->talleres_semestre_model->get_with_name($taller_id);
            if (is_array($data['taller'])) {
                $data['taller']['horarios'] = $this->taller_semestre_horario_model->get_by_taller_sem($data['taller']['id']);
                $data['taller']['inscritos'] = $this->baucher_talleres_model->count_insc_validados($data['taller']['id']);
                $this->load->helper(array('url', 'sesion', 'date'));
                $dias = $this->get_dias_taller($data['taller']['ini_sem'], $data['taller']['fin_sem'], $data['taller']['horarios']);
                $data['alumnos'] = $this->baucher_talleres_model->get_by_taller_insc($taller_id);
                if (is_array($dias)) {
                    $meses = array(
                        '1' => 'Enero',
                        '2' => 'Febrero',
                        '3' => 'Marzo',
                        '4' => 'Abril',
                        '5' => 'Mayo',
                        '6' => 'Junio',
                        '7' => 'Julio',
                        '8' => 'Agosto',
                        '9' => 'Septiembre',
                        '10' => 'Octubre',
                        '11' => 'Noviembre',
                        '12' => 'Diciembre'
                    );
                    $css = $this->load->view('admin/lista_asistencia_css', $data, true);
                    $this->load->library('mpdf');
                    $mpdf = new mPDF('c', 'A4-L');
                    $mpdf->SetProtection(array('copy', 'print'));
                    //$mpdf->SetDirectionality('RTL');
                    $mpdf->WriteHTML($css, 1);
                    foreach ($dias as $mes => $dia) {
                        $data['mes'] = $meses[$mes];
                        $data['dias'] = $dia;
                        $content = $this->load->view('admin/lista_asistencia_view', $data, true);
                        $mpdf->AddPage();
                        $mpdf->WriteHTML($content , 0);
                    }
                    $route = str_replace("\\", "/", FCPATH) . "uploads/listas/";
                    if (file_exists($route . "asistencia_" . $data['taller']['id'] . '.pdf')) {
                        unlink($route . "asistencia_" . $data['taller']['id'] . '.pdf');
                    }
                    if ($this->archivos->create_folder($route)) {
                        $mpdf->Output($route . "asistencia_" . $data['taller']['id'] . '.pdf', 'F');
                        echo json_encode(array('status' => 'OK', 'url' => base_url() . 'uploads/listas/asistencia_' . $data['taller']['id'] . '.pdf'));
                        //redirect( base_url() . 'uploads/listas/asistencia_' . $data['taller']['id'] . '.pdf');
                    } else {
                        echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'No se pudo crear la carpeta de usuario'));
                    }
                } else {
                    echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'Error 1.'));
                }
                //$this->load->view('main/footer_view', '');
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'Error 2.'));
            }
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'Error 3.'));
        }
    }

    private function get_dias_taller($inicio, $termino, $horarios) {
        $inicio_array = explode('-', $inicio);
        $termino_array = explode('-', $termino);
        $dias = array();
        $inicio_int = mktime(0, 0, 0, $inicio_array[1], $inicio_array[2], $inicio_array[0]);
        $termino_int = mktime(0, 0, 0, $termino_array[1], $termino_array[2], $termino_array[0]);
        //$dias_aux = array('0', '1', '2', '3', '4', '5', '6');
//        if (is_array($horarios)) {
//            $dias_aux = array();
//            foreach ($horarios as $horario) {
//                $dias_aux[] = $horario['dia'];
//            }
//        }
        if ($inicio_int < $termino_int) {
            while ($inicio_int < $termino_int) {
//                if (in_array(date('w', $inicio_int), $dias_aux)) {
                $dias[date('n', $inicio_int)][] = array('n' => date('d', $inicio_int) , 'd' => date('w', $inicio_int));
//                }
                $inicio_int += 86400;
            }
        }
        return $dias;
    }

}

?>
