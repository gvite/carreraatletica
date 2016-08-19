<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class pagos_registro extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('semestres_model');
        $data['semestres'] = $this->semestres_model->get_all();
        $content = $this->load->view("admin/pagos_registro_view", $data, true);
        echo json_encode(array('status' => 'OK', 'content' => $content));
    }

}

?>
