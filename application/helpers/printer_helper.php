<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('substrwords'))
{
    function substrwords($text, $maxchar = 30, $end='') {
        if (strlen($text) > $maxchar || $text == '') {
            $words = preg_split('/\s/', $text);
            $output = '';
            $i      = 0;
            while (1) {
                $length = strlen($output)+strlen($words[$i]);
                if ($length > $maxchar) {
                    break;
                }
                else {
                    $output .= " " . $words[$i];
                    ++$i;
                }
            }
            $output .= $end;
        }
        else {
            $output = $text;
        }
        return $output;
    }
}

if ( ! function_exists('pos_print'))
{
    function pos_print() {
        $CI =& get_instance();
        $CI->load->model('settings_model');
        $status = $CI->settings_model->get_posprinter_status();

        return $status == 1 ? true:false;

    }
}




