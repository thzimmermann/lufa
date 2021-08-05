<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('link_js')) {
    function link_js($js) {
        $CI =& get_instance();
        $base_principal = $CI->config->slash_item('base_url');

        return "<script src='".$base_principal."{$js}' language='javascript' type='text/javascript'></script>";
    }
}

if (!function_exists('link_ng')) {
    function link_ng($js) {
        $CI =& get_instance();
        $base_principal = $CI->config->slash_item('base_url');

        return "<script src='".$base_principal."application/views/{$js}' language='javascript' type='text/javascript'></script>";
    }
}
