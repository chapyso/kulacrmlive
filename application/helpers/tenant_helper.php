<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('tenant_url')) {
    /**
     * Generate path-based tenant URL: http://localhost:8080/{slug_name}/$uri
     */
    function tenant_url($uri = '') {
        $CI =& get_instance();
        $slug = !empty($CI->tenant_slug) ? $CI->tenant_slug : 'kulafarms';
        if ($slug === 'default') {
            $slug = 'kulafarms';
        }
        return base_url($slug . '/' . ltrim($uri, '/'));
    }
}

if (!function_exists('superadmin_url')) {
    /**
     * Generate Super Admin platform URL: http://localhost:8080/superadmin/$uri
     */
    function superadmin_url($uri = '') {
        return base_url('superadmin/' . ltrim($uri, '/'));
    }
}

if (!function_exists('has_permission')) {
    /**
     * Evaluate permission name globally in view templates and controllers
     */
    function has_permission($permission_name) {
        $CI =& get_instance();
        if (method_exists($CI, 'has_permission')) {
            return $CI->has_permission($permission_name);
        }
        return true;
    }
}
