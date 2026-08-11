<?php
$_SERVER['HTTP_HOST'] = 'localhost:8000';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../index.php';
$_SERVER['REQUEST_URI'] = '/superadmin/plans';

require_once __DIR__ . '/../index.php';
$CI =& get_instance();
$CI->load->library('ion_auth');
$CI->ion_auth->login('ronaldi2040@gmail.com', 'Baale@256');

ob_start();
$CI->load->module('superadmin');
$CI->superadmin->plans();
$html = ob_get_clean();

if (strpos($html, 'UGX') !== false) {
    echo "SUCCESS: Plans page renders UGX currency symbol!\n";
    preg_match_all('/UGX\s*[\d,]+/', $html, $matches);
    print_r(array_unique($matches[0]));
} else {
    echo "FAILED: UGX symbol not found on plans page.\n";
}
