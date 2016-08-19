<?php

if (!function_exists('exchange_date')) {

    function exchange_date($fecha) {
        $array = explode("-", $fecha);
        if (sizeof($array) == 3) {
            return $array[2] . "-" . $array[1] . "-" . $array[0];
        } else {
            return false;
        }
    }

}
if (!function_exists('exchange_date_time')) {

    function exchange_date_time($fecha) {
        $array_aux = explode(" ", $fecha);
        $array = explode("-", $array_aux[0]);
        if (sizeof($array) == 3) {
            return $array[2] . "-" . $array[1] . "-" . $array[0] . ' ' . $array_aux[1];
        } else {
            return false;
        }
    }

}
if (!function_exists('date_time_to_date')) {

    function date_time_to_date($fecha) {
        $array_aux = explode(" ", $fecha);
        $array = explode("-", $array_aux[0]);
        if (sizeof($array) == 3) {
            return $array[2] . "-" . $array[1] . "-" . $array[0];
        } else {
            return false;
        }
    }

}
if (!function_exists('get_years_users')) {
    function get_years_users($date) {
        $d1 = new DateTime($date);
        $d2 = new DateTime('now');
        $diff = $d2->diff($d1);
        $years = $diff->y;
        return $years;
    }
}
?>
