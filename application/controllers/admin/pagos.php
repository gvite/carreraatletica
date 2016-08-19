<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pagos extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $content = $this->load->view("admin/pagos_view", '', true);
        echo json_encode(array('status' => 'OK', 'content' => $content));
    }

}

?>
