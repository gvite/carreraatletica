<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Semestres extends CI_Controller {

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
        $data['js'] = 'js/semestres.js';
        $this->load->view('main/header_view', $data);
        $this->load->helper('date');
        $data['semestres'] = $this->semestres_model->get_all();
        $this->load->view("admin/semestres_view", $data);
        $this->load->view('main/footer_view', '');
    }

    public function insert() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("nombre", "Nombre", "xss|required");
        $this->form_validation->set_rules("inicio", "Fecha de Inicio", 'xss|required|callback_date_form|callback_check_date_form[' . $this->input->post('termino') . ']');
        $this->form_validation->set_rules("termino", "Fecha de Termino", "xss|required|callback_date_form");
        $this->form_validation->set_rules("termino", "Fecha de inicio de inscripcion", "xss|required");
        $this->form_validation->set_rules("termino", "Fecha de termino  de inscripcion", "xss|required");
        $this->form_validation->set_rules("validacion", "Fecha de termino  de validación", "xss|required");
        $this->form_validation->set_message("required", "Introduce %s");
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $this->load->helper('date');
            $data = array(
                'semestre' => $this->input->post('nombre'),
                'ini_sem' => exchange_date($this->input->post('inicio')),
                'fin_sem' => exchange_date($this->input->post('termino')),
                'ini_insc' => exchange_date_time($this->input->post('ini_insc')),
                'fin_insc' => exchange_date_time($this->input->post('fin_insc')),
                'fin_validacion' => exchange_date_time($this->input->post('validacion'))
            );
            $this->load->model('semestres_model');
            $data['id'] = $this->semestres_model->insert($data);
            if ($data['id']) {
                $data["ini_sem"] = exchange_date($data["ini_sem"]);
                $data["fin_sem"] = exchange_date($data["fin_sem"]);
                $data["ini_insc"] = exchange_date_time($data["ini_insc"]);
                $data["fin_insc"] = exchange_date_time($data["fin_insc"]);
                $data["fin_validacion"] = exchange_date_time($data["fin_validacion"]);
                echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.', 'semestre' => $data, 'tipo' => 0));
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se pudieron guardar los datos'));
            }
        }
    }

    public function update($id = '') {
        if ($id != '') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules("nombre", "Nombre", "xss|required");
            $this->form_validation->set_rules("inicio", "Fecha de Inicio", 'xss|required|callback_date_form');
            $this->form_validation->set_rules("termino", "Fecha de Termino", "xss|required|callback_date_form");
            $this->form_validation->set_rules("validacion", "Fecha de termino  de validación", "xss|required");
            $this->form_validation->set_message("required", "Introduce %s");
            if ($this->form_validation->run() === FALSE) {
                $errors = validation_errors();
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
            } else {
                $this->load->helper('date');
                $data = array(
                    'semestre' => $this->input->post('nombre'),
                    'ini_sem' => exchange_date($this->input->post('inicio')),
                    'fin_sem' => exchange_date($this->input->post('termino')),
                    'ini_insc' => exchange_date_time($this->input->post('ini_insc')),
                    'fin_insc' => exchange_date_time($this->input->post('fin_insc')),
                    'fin_validacion' => exchange_date_time($this->input->post('validacion'))
                );
                $this->load->model('semestres_model');
                if ($this->semestres_model->update($id, $data)) {
                    $data['id'] = $id;
                    $data["ini_sem"] = exchange_date($data["ini_sem"]);
                    $data["fin_sem"] = exchange_date($data["fin_sem"]);
                    $data["ini_insc"] = exchange_date_time($data["ini_insc"]);
                    $data["fin_insc"] = exchange_date_time($data["fin_insc"]);
                    $data["fin_validacion"] = exchange_date_time($data["fin_validacion"]);
                    echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.', 'semestre' => $data, 'tipo' => 1));
                } else {
                    echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se pudieron guardar los datos'));
                }
            }
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }

    public function delete($id = '') {
        if ($id !== '') {
            $this->load->model('semestres_model');
            if ($this->semestres_model->check_delete($id)) {
                if ($this->semestres_model->delete($id)) {
                    echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'El semestre se elimin&oacute; correctamente.'));
                } else {
                    echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'ERROR 0x002: No se pudo eliminar el semestre elegido.'));
                }
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se puede eliminar el semestre porque ya tiene asignado talleres.'));
            }
        } else {
            echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'ERROR 0x003: No se envi&oacute; informaci&oacute;n.'));
        }
    }

    function date_form($fecha) {
        $datos = explode('-', $fecha);
        if (count($datos) === 3 &&
                is_numeric($datos[1]) &&
                is_numeric($datos[0]) &&
                is_numeric($datos[2]) &&
                checkdate($datos[1], $datos[0], $datos[2])) {
            return true;
        } else {
            $this->form_validation->set_message('date_form', 'Ingresa %s en formato v&aacute;lido (dd-mm-aaaa)');
            return FALSE;
        }
    }

    function check_date_form($fecha1, $fecha2) {
        $this->load->helper('date');
        $fecha_aux1 = strtotime(exchange_date($fecha1));
        $fecha_aux2 = strtotime(exchange_date($fecha2));
        if ($fecha_aux1 < $fecha_aux2) {
            $this->load->model('semestres_model');
            $periodos = $this->semestres_model->get_all('ini_sem,fin_sem,semestre');
            $exito = true;
            if (is_array($periodos)) {
                $periodos_message = '';
                foreach ($periodos as $periodo) {
                    $period_aux1 = strtotime($periodo['ini_sem']);
                    $period_aux2 = strtotime($periodo['fin_sem']);
                    if (($fecha_aux1 > $period_aux1 && $fecha_aux1 < $period_aux2) || ($fecha_aux2 > $period_aux1 && $fecha_aux2 < $period_aux2)) {
                        $periodos_message .= $periodo['semestre'] . ', ';
                        $exito = false;
                    }
                }
            }
            if ($exito) {
                return true;
            } else {
                $periodos_message = substr($periodos_message, 0, strlen($periodos_message) - 2);
                $this->form_validation->set_message('check_date_form', 'Una de las fechas se encuetra dentro de estos periodos: ' . $periodos_message);
                return FALSE;
            }
        } else {
            $this->form_validation->set_message('check_date_form', 'La fecha de Termino debe ser mayor a la de Inicio');
            return FALSE;
        }
    }

}

?>
