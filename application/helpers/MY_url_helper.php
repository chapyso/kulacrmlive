<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('current_full_url')) {
    function current_full_url()
    {
        $CI =& get_instance();
        $url = $CI->config->site_url($CI->uri->uri_string());
        $qs = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
        return $qs !== '' ? $url . '?' . $qs : $url;
    }
}
