<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Archivos {

    public function __construct() {
        
    }

    public function create_folder($url) {
        if(!is_dir($url)){
            $url_array = explode('/' , $url);
            $count = count($url_array);
            for ( $i  = 1 ; $i < $count ; $i++){
                $str_url = '';
                for($j = 0 ; $j <= $i ; $j++){
                    $str_url .= $url_array[$j] .'/';
                }
                if(!is_dir($str_url)){
                    umask(0);
                    if(!mkdir($str_url)){
                        return false;
                    }else{
                        chmod($str_url , 0777);
                    }
                }
            }
            return true;
        }else{
            return true;
        }
    }
}

?>