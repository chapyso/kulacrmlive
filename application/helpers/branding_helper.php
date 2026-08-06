<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Get Light / Primary Logo URL with cache-busting timestamp parameter
 */
if (!function_exists('get_light_logo_url')) {
    function get_light_logo_url($settings = null) {
        $ci =& get_instance();
        if (empty($settings)) {
            if (isset($ci->settings_model)) {
                $settings = $ci->settings_model->getSettings();
            }
        }

        $raw_url = '';
        if (!empty($settings->img_url)) {
            $clean = ltrim($settings->img_url, '/');
            if (file_exists(FCPATH . $clean) || strpos($clean, 'http') === 0) {
                $raw_url = $settings->img_url;
            }
        }

        if (empty($raw_url)) {
            $fallbacks = ['uploads/logo.png', 'logo.png', 'uploads/logo11.png', 'uploads/avatar/logo11.png'];
            foreach ($fallbacks as $fb) {
                if (file_exists(FCPATH . $fb) || file_exists($fb)) {
                    $raw_url = $fb;
                    break;
                }
            }
        }

        if (empty($raw_url)) {
            return base_url('uploads/logo.png');
        }

        if (strpos($raw_url, 'http://') === 0 || strpos($raw_url, 'https://') === 0) {
            return $raw_url;
        }

        $clean_path = ltrim($raw_url, '/');
        $full_path  = FCPATH . $clean_path;
        $v = file_exists($full_path) ? filemtime($full_path) : time();

        return base_url($clean_path) . '?v=' . $v;
    }
}

/**
 * Get Dark Mode Logo URL with cache-busting timestamp parameter
 */
if (!function_exists('get_dark_logo_url')) {
    function get_dark_logo_url($settings = null) {
        $ci =& get_instance();
        if (empty($settings)) {
            if (isset($ci->settings_model)) {
                $settings = $ci->settings_model->getSettings();
            }
        }

        $raw_url = '';
        if (!empty($settings->dark_img_url)) {
            $clean = ltrim($settings->dark_img_url, '/');
            if (file_exists(FCPATH . $clean) || strpos($clean, 'http') === 0) {
                $raw_url = $settings->dark_img_url;
            }
        }

        if (empty($raw_url)) {
            $fallbacks = ['uploads/dark_logo.png', 'uploads/logo_dark.png', 'uploads/logo.png'];
            foreach ($fallbacks as $fb) {
                if (file_exists(FCPATH . $fb) || file_exists($fb)) {
                    $raw_url = $fb;
                    break;
                }
            }
        }

        if (empty($raw_url)) {
            return get_light_logo_url($settings);
        }

        if (strpos($raw_url, 'http://') === 0 || strpos($raw_url, 'https://') === 0) {
            return $raw_url;
        }

        $clean_path = ltrim($raw_url, '/');
        $full_path  = FCPATH . $clean_path;
        $v = file_exists($full_path) ? filemtime($full_path) : time();

        return base_url($clean_path) . '?v=' . $v;
    }
}

/**
 * Get Favicon URL with cache-busting timestamp parameter
 */
if (!function_exists('get_favicon_url')) {
    function get_favicon_url($settings = null) {
        $ci =& get_instance();
        if (empty($settings)) {
            if (isset($ci->settings_model)) {
                $settings = $ci->settings_model->getSettings();
            }
        }

        $raw_url = '';
        if (!empty($settings->favicon_url)) {
            $raw_url = $settings->favicon_url;
        }

        if (empty($raw_url)) {
            $fallbacks = ['uploads/favicon.ico', 'uploads/logo.png', 'uploads/dark_logo.png'];
            foreach ($fallbacks as $fb) {
                if (file_exists(FCPATH . $fb) || file_exists($fb)) {
                    $raw_url = $fb;
                    break;
                }
            }
        }

        if (empty($raw_url)) {
            return base_url('uploads/logo.png');
        }

        if (strpos($raw_url, 'http://') === 0 || strpos($raw_url, 'https://') === 0) {
            return $raw_url;
        }

        $clean_path = ltrim($raw_url, '/');
        $full_path  = FCPATH . $clean_path;
        $v = file_exists($full_path) ? filemtime($full_path) : time();

        return base_url($clean_path) . '?v=' . $v;
    }
}
