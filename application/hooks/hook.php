<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hook {
    public function seguridad() {
        $CI = & get_instance();
        $lista = array('', 
            'inicio' , 
            'acceso/login/*' , 
            'acceso/registro' , 
            'horarios' , 
            'horarios/get_talleres_by_semestre/*', 
            'acceso/registro/insert' , 
            'alumnos/inscripcion/get_pdf/*' , 
            'admin/taller_semestre_horario/get_by_semestre/*',
            'admin/talleres/get_info/*',
            'administrador');
        $CI->load->helper(array('sesion' , 'url'));
        if (!get_id()) {
            $redirect = true;
            foreach($lista as $list){
                if(strpos($list , '*') !== false){
                    if(strpos($CI->uri->uri_string() , trim($list , '*')) !== false){
                        $redirect = false;
                    }
                }else{
                    if(in_array($CI->uri->uri_string(), $lista)){
                        $redirect = false;
                    }
                }
            }
            if($redirect){
                redirect('inicio','refresh');
            }
        }else{
            $urls_acceso = array(
                0 => 'inicio',
                1 => 'horarios',
                2 => 'acceso/login/logout',
                3 => 'acceso/cambia_contra',
                4 => 'perfil'
            );
            $urls_denegados = array();
            switch(get_type()){
                case 1:
                    $urls_acceso[] = 'admin/*';
                    $urls_acceso[] = 'alumnos/*';
                    switch(get_type_user()){
                        case 6:
                            $urls_denegados[] = 'admin/cambio/*';
                            break;
                    }
                    break;
                case 2:
                    $urls_acceso[] = 'alumnos/*';
                    $urls_acceso[] = 'admin/talleres/get_info/';
                    $urls_acceso[] = 'admin/taller_semestre_horario/get_by_semestre/';
                    break;
            }
            $show_404 = true;
            foreach ($urls_acceso as $url){
                if(strpos($url , '*') !== false){
                    if(strpos($CI->uri->uri_string() , trim($url , '*')) !== false){
                        $show_404 = false;
                    }
                }else{
                    if(strpos($CI->uri->uri_string() , $url) !== false){
                        $show_404 = false;
                    }
                }
            }
            foreach ($urls_denegados as $url){
                if(strpos($url , '*') !== false){
                    if(strpos($CI->uri->uri_string() , trim($url , '*')) !== false){
                        $show_404 = true;
                    }
                }else{
                    if(strpos($CI->uri->uri_string() , $url) !== false){
                        $show_404 = true;
                    }
                }
            }
            if($show_404 && $CI->uri->uri_string() !== ''){
                show_404();
            }
            if(strpos($CI->uri->uri_string() , 'acceso/registro') !== false){
                redirect('inicio','refresh');
            }
        }
    }

}

?>