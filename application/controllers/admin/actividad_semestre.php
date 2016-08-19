<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Actividad_semestre extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
    public function insert($id_actividad = '' , $id_semestre = '') {
        if($id_actividad !== '' && $id_semestre !== ''){
            $data = array(
                'semestre_id' => $id_semestre,
                'actividad_id' => $id_actividad
            );
            $this->load->model('actividades_semestre_model');
            $id = $this->actividades_semestre_model->insert($data);
            if($id){
                echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Los datos se guardaron correctamente.' , 'id' => $id));
            }else{
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se guardaron bien los datos'));
            }
        }else{
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se recolectaron bien los datos'));
        }
    }
    public function remove($id){
        if($id !== ''){
            $this->load->model('actividades_semestre_model');
            $id = $this->actividades_semestre_model->delete($id);
            if($id){
                echo json_encode(array('status' => 'MSG', 'type' => 'success', "message" => 'Se removio correctamente.'));
            }else{
                echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se guardaron bien los datos'));
            }
        }else{
            echo json_encode(array('status' => 'MSG', 'type' => 'warning', "message" => 'No se recolectaron bien los datos'));
        }
    }
}

?>
