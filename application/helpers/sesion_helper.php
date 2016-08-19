<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('get_user')) {
    function get_user() {
        @session_start();
        return (isset($_SESSION['username_deportivas'])) ? $_SESSION['username_deportivas'] : FALSE;
    }
}
if (!function_exists('set_user')) {
    function set_user($user) {
        @session_start();
        $_SESSION['username_deportivas'] = $user;
    }

}
if (!function_exists('get_type')) {
    function get_type() {
        @session_start();
        return (isset($_SESSION['usertype_deportivas'])) ? $_SESSION['usertype_deportivas'] : FALSE;
    }

}
if (!function_exists('set_type')) {
    function set_type($type) {
        @session_start();
        $_SESSION['usertype_deportivas'] = floor($type);
    }

}
if (!function_exists('get_type_user')) {
    function get_type_user() {
        @session_start();
        return (isset($_SESSION['typeuser_deportivas'])) ? $_SESSION['typeuser_deportivas'] : FALSE;
    }
}
if (!function_exists('set_type_user')) {
    function set_type_user($type) {
        @session_start();
        $_SESSION['typeuser_deportivas'] = floor($type);
    }

}
if (!function_exists('get_name')) {
    function get_name() {
        @session_start();
        return (isset($_SESSION['usercomplete_deportivas'])) ? $_SESSION['usercomplete_deportivas'] : FALSE;
    }
}
if (!function_exists('set_name')) {
    function set_name($name) {
        @session_start();
        $_SESSION['usercomplete_deportivas'] = $name;
    }
}
if (!function_exists('get_id')) {
    function get_id() {
        @session_start();
        return (isset($_SESSION['id_user_deportivas'])) ? $_SESSION['id_user_deportivas'] : FALSE;
    }
}
if (!function_exists('set_id')) {
    function set_id($id) {
        @session_start();
        $_SESSION['id_user_deportivas'] = $id;
    }
}
if (!function_exists('get_date')) {
    function get_date() {
        @session_start();
        return (isset($_SESSION['date_user_deportivas'])) ? $_SESSION['date_user_deportivas'] : FALSE;
    }
}
if (!function_exists('get_user_years')) {
    function get_user_years() {
        @session_start();
        $years = false;
        if(isset($_SESSION['id_user_deportivas'])){
            $d1 = new DateTime($_SESSION["date_user_deportivas"]);
            $d2 = new DateTime('now');
            $diff = $d2->diff($d1);
            $years = $diff->y;
        }
        return $years;
    }
}
if (!function_exists('set_date')) {
    function set_date($date) {
        @session_start();
        $_SESSION['date_user_deportivas'] = $date;
    }
}
if (!function_exists('clean_session')) {
    function clean_session() {
        @session_start();
        unset($_SESSION['id_user_deportivas']);
        unset($_SESSION['usercomplete_deportivas']);
        unset($_SESSION['usertype_deportivas']);
        unset($_SESSION['username_deportivas']);
        session_destroy();
    }
}
?>