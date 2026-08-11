<?php
$_SERVER['HTTP_HOST'] = 'localhost:8000';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../index.php';
$_SERVER['REQUEST_URI'] = '/superadmin/users';

require_once __DIR__ . '/../index.php';
$CI =& get_instance();
$CI->load->library('ion_auth');

// Log in as Super Admin
$CI->ion_auth->login('ronaldi2040@gmail.com', 'Baale@256');

ob_start();
$CI->load->module('superadmin');
$CI->superadmin->users();
$html = ob_get_clean();

if (strpos($html, 'SaaS Control') !== false && strpos($html, 'SaaS Platform Global Users') !== false) {
    echo "SUCCESS: /superadmin/users renders SaaS Control header & Superadmin layout!\n";
} else {
    echo "FAILED to render Superadmin layout.\n";
    echo "Snippet:\n" . substr($html, 0, 500) . "\n";
}
