<?php
$_SERVER['HTTP_HOST'] = '127.0.0.1';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/..';
chdir(__DIR__ . '/..');

ob_start();
require 'index.php';
ob_end_clean();

$CI =& get_instance();
$CI->load->database();

echo "=== TENANTS TABLE CONTENT ===\n";
$tenants = $CI->db->get('tenants')->result();
foreach ($tenants as $t) {
    var_dump($t);
}
