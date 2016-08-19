<?php

class Reportes extends CI_Controller {

    private $vista_pdf = '';
    private $css_pdf = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('talleres_semestre_model');
        $this->load->helper('date');
        $this->load->model('taller_semestre_horario_model');
        //$this->load->library('archivos');
    }

    public function carrera() {
        $this->load->model('semestres_model');
        $this->load->model('carreras_model');
        $this->load->helper(array('url', 'sesion', 'date'));
        $data['active'] = 'reportes';
        //$this->insert_aportacion();
        $data['semestre_actual'] = $this->semestres_model->get_actual();
        if ($data['semestre_actual']) {
            $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
        } else {
            $data['puede_inscribir'] = false;
        }
        $data['js'] = 'js/reporte1.js';
        $this->load->view('main/header_view', $data);
        $data['carreras'] = $this->carreras_model->get_all();
        $data['semestres'] = $this->semestres_model->get_all_order_by_inicio();
        $this->load->view('admin/reportes/reporte1_view', $data);
        $this->load->view('main/footer_view', '');
    }

    public function presupuesto1() {
        $this->load->model('semestres_model');
        $this->load->model('carreras_model');
        $this->load->helper(array('url', 'sesion', 'date'));
        $data['active'] = 'reportes';
        $data['semestre_actual'] = $this->semestres_model->get_actual();
        if ($data['semestre_actual']) {
            $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
        } else {
            $data['puede_inscribir'] = false;
        }
        $data['js'] = 'js/presupuesto1.js';
        $this->load->view('main/header_view', $data);
        $this->load->helper('date');
        $data['semestres'] = $this->semestres_model->get_all_order_by_inicio();
        $this->load->view('admin/reportes/reporte2_view', $data);
        $this->load->view('main/footer_view', '');
    }

    public function presupuesto2() {
        $this->load->model('semestres_model');
        $this->load->model('carreras_model');
        $this->load->helper(array('url', 'sesion', 'date'));
        $data['active'] = 'reportes';
        $data['semestre_actual'] = $this->semestres_model->get_actual();
        if ($data['semestre_actual']) {
            $data['puede_inscribir'] = $this->semestres_model->puede_insc($data['semestre_actual']['id']);
        } else {
            $data['puede_inscribir'] = false;
        }
        $data['js'] = 'js/presupuesto2.js';
        $this->load->view('main/header_view', $data);
        $this->load->helper('date');
        $data['semestres'] = $this->semestres_model->get_all_order_by_inicio();
        $this->load->view('admin/reportes/reporte3_view', $data);
        $this->load->view('main/footer_view', '');
    }

    public function genera_carrera() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("tipo_alumno", "Tipo de Alumno", "xss|required");
        $this->form_validation->set_rules("carrera", "Carrera", "xss|required");
        $this->form_validation->set_rules("semestre", "Semestre", "xss|required");
        $this->form_validation->set_message("required", "Introduce %s");
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $tipo_alumno = $this->input->post('tipo_alumno');
            $carrera = $this->input->post('carrera');
            $semestre = $this->input->post('semestre');
            $this->load->model('reportes_model');
            $data['talleres'] = $this->reportes_model->get_alumnos_talleres($tipo_alumno, $carrera, $semestre);
            if ($carrera == 0) {
                $data['carrera']['carrera'] = 'Todas las carreras';
            } else {
                $this->load->model('carreras_model');
                $data['carrera'] = $this->carreras_model->get($carrera);
            }
            if($semestre != 0){
                $this->load->model('semestres_model');
                $data["semestre"] = $this->semestres_model->get($semestre);
            }else{
                $data["semestre"] = false;
            }
            $this->vista_pdf = $this->load->view('admin/reportes/reporte1_pdf', $data, true);
            $this->css_pdf = $this->load->view('admin/reportes/reporte1_css', '', true);
            $file = $this->genera_pdf('reporte1');
            if ($file !== false) {
                echo json_encode(array('status' => 'OK', "file" => $file));
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'No se pudo crear el archivo.'));
            }
        }
    }

    public function genera_presupuesto1() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("fecha_inicio", "Fecha de Inicio", "xss|required");
        $this->form_validation->set_rules("fecha_termino", "Fecha de Termino", "xss|required");
        $this->form_validation->set_rules("semestre", "Semestre", "xss|required");
        $this->form_validation->set_rules("tamanio_fuente", "Tama&ntilde;o de fuente", "xss|integer");
        $this->form_validation->set_rules("no_reg", "No. de registros", "xss|integer");
        $this->form_validation->set_message("required", "Introduce %s");
        $this->form_validation->set_message("integer", "%s debe de ser numerico.");
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $inicio = $this->input->post('fecha_inicio');
            $termino = $this->input->post('fecha_termino');
            $this->load->model('reportes_model');
            $this->load->model('semestres_model');
            $data['semestre'] = $this->semestres_model->get($this->input->post('semestre'));
            $alumnos = $this->reportes_model->getPresupuesto1(exchange_date($inicio), exchange_date($termino));
            $tamanio = $this->input->post('tamanio_fuente');
            $no_reg = $this->input->post('no_reg');
            if ($no_reg != '') {
                $limite = $no_reg;
            } else {
                $limite = 50;
            }
            $fuentes = array(
                'helvetica',
                'courier',
                'times',
                'franklin'
            );
            $data['font'] = ($this->input->post('fuente') != '' && isset($fuentes[(int) $this->input->post('fuente')])) ? $fuentes[(int) $this->input->post('fuente')] : false;
            if (is_array($alumnos) && count($alumnos) > $limite) {
                $this->vista_pdf = array();
                $new_alumnos = array_chunk($alumnos, $limite);
                $total = 0;
                foreach ($alumnos as $alumno) {
                    $total += $alumno['aportacion'];
                }
                foreach ($new_alumnos as $key => $alumno_aux) {
                    $data['alumnos'] = $alumno_aux;
                    if ($key == (count($new_alumnos) - 1)) {
                        $data['total'] = $total;
                    }
                    $data['pagina'] = $key + 1;

                    $this->vista_pdf[] = $this->load->view('admin/reportes/reporte2_pdf', $data, true);
                }
            } else {
                $data['alumnos'] = $alumnos;
                $data['pagina'] = 1;
                $this->vista_pdf = $this->load->view('admin/reportes/reporte2_pdf', $data, true);
            }
            $data = array(
                'font_size' => ($tamanio != '') ? $tamanio : 8
            );
            $this->css_pdf = $this->load->view('admin/reportes/reporte2_css', $data, true);
            //echo json_encode(array('status' => 'OK' , 'd' => $this->vista_pdf));
            $file = $this->genera_pdf('reporte2');
            if ($file !== false) {
                echo json_encode(array('status' => 'OK', "file" => $file, 'a' => $this->vista_pdf));
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'No se pudo crear el archivo.'));
            }
        }
    }

    public function genera_presupuesto2() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("semestre", "Semestre", "xss|required");
        $this->form_validation->set_rules("fecha_inicio", "Fecha de Inicio", "xss|required");
        $this->form_validation->set_rules("fecha_termino", "Fecha de Termino", "xss|required");
        $this->form_validation->set_message("required", "Introduce %s");
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $this->load->model('talleres_model');
            $this->load->model('semestres_model');
            $this->load->model('reportes_model');
            $semestre = $this->input->post('semestre');
            $inicio = exchange_date($this->input->post('fecha_inicio'));
            $termino = exchange_date($this->input->post('fecha_termino'));
            $data['semestre'] = $this->semestres_model->get($semestre);
            $data['talleres'] = $this->talleres_model->get_all_by_semestre_order($semestre);
            //$data['talleres'] = false;
            if (is_array($data['talleres'])) {
                foreach ($data['talleres'] as $key => $taller) {
                    $data['talleres'][$key]['uni'] = $this->reportes_model->getUsuarioByTaller($taller['id'], 2, $semestre, $inicio,$termino);
                    $data['talleres'][$key]['exuni'] = $this->reportes_model->getUsuarioByTaller($taller['id'], 3, $semestre, $inicio,$termino);
                    $data['talleres'][$key]['traba'] = $this->reportes_model->getUsuarioByTaller($taller['id'], 4, $semestre, $inicio,$termino);
                    $data['talleres'][$key]['exter'] = $this->reportes_model->getUsuarioByTaller($taller['id'], 5, $semestre, $inicio,$termino);
                }
                $data['meses'] = $this->reportes_model->getSumBySemestreMonth($semestre ,$inicio,$termino );
            }
            $this->load->helper('date');
            $this->vista_pdf = $this->load->view('admin/reportes/reporte3_pdf', $data, true);
            $this->css_pdf = $this->load->view('admin/reportes/reporte3_css', '', true);
            $header = '<img src="images/logo_pdf.jpg" style="top:-15px;" />';
            $file = $this->genera_pdf('reporte3', '', $header);
            if ($file !== false) {
                echo json_encode(array('status' => 'OK', "file" => $file,'lq' => $data['meses']));
            } else {
                echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'No se pudo crear el archivo.'));
            }
        }
    }

    private function genera_pdf($folder = '', $name = '', $header = '', $position = 'P') {
        $this->load->helper(array('url', 'sesion', 'date'));
        $this->load->library(array('mpdf', 'archivos'));
        ini_set("memory_limit", "1024M");
        $mpdf = new mPDF();
        $mpdf->SetProtection(array('copy', 'print'));
        if ($header !== '') {
            $mpdf->SetHTMLHeader($header);
        }
        $mpdf->WriteHTML($this->css_pdf, 1);
        if (is_array($this->vista_pdf)) {
            foreach ($this->vista_pdf as $vista) {
                $mpdf->AddPage($position); // L - landscape, P - portrait
                $mpdf->WriteHTML($vista, 2);
            }
        } else {
            $mpdf->AddPage($position); // L - landscape, P - portrait
            $mpdf->WriteHTML($this->vista_pdf, 2);
        }
        //$footer = $this->load->view('alumnos/comprobante_footer_view' , $data1 , true);
        //$mpdf->SetHTMLFooter($footer);
        $route = str_replace("\\", "/", FCPATH) . "uploads/reportes/" . $folder . '/';
        if ($this->archivos->create_folder($route)) {
            $file = $name . date('d_m_y') . '.pdf';
            $mpdf->Output($route . $file, 'F');
            return base_url() . 'uploads/reportes/' . $folder . '/' . $file;
        } else {
            ///echo json_encode(array('status' => 'MSG', 'type' => 'error', "message" => 'No se pudo crear la carpeta de usuario'));
            return false;
        }
    }

    private function insert_aportacion() {
        $this->load->model('baucher_talleres_model');
        $inscripciones = $this->baucher_talleres_model->get_by_semestre(4);
        if (is_array($inscripciones)) {
            foreach ($inscripciones as $ins) {
                $costo = false;
                if ($ins['tipo_usuario_id'] == 2) {
                    $costo = $ins['costo_alumno'];
                } else if ($ins['tipo_usuario_id'] == 3) {
                    $costo = $ins['costo_exalumno'];
                } else if ($ins['tipo_usuario_id'] == 4) {
                    $costo = $ins['costo_trabajador'];
                } else {
                    $costo = $ins['costo_externo'];
                }
                $data = array('aportacion' => $costo);
                $this->baucher_talleres_model->update($ins['id'], $data);
            }
        }
    }

    public function get_registros_reportes() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("fecha_inicio", "Fecha de Inicio", "xss|required");
        $this->form_validation->set_rules("fecha_termino", "Fecha de Termino", "xss|required");
        $this->form_validation->set_rules("semestre", "Semestre", "xss|required");
        $this->form_validation->set_message("required", "Introduce %s");
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => $errors));
        } else {
            $inicio = $this->input->post('fecha_inicio');
            $termino = $this->input->post('fecha_termino');
            $this->load->model('reportes_model');
            $this->load->model('semestres_model');
            $data['semestre'] = $this->semestres_model->get($this->input->post('semestre'));
            $alumnos = $this->reportes_model->getPresupuesto1(exchange_date($inicio), exchange_date($termino));
            $data['alumnos'] = $alumnos;
            $this->load->helper('url');
            $container = $this->load->view('admin/reportes/reporte2_preview_pdf', $data, true);
            echo json_encode(array('status' => 'OK', "container" => $container));
        }
    }

}
