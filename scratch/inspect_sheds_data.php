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

echo "=== ALL SHEDS IN DB ===\n";
$sheds = $CI->db->get('shed')->result();
echo "Total sheds in table: " . count($sheds) . "\n";
foreach ($sheds as $s) {
    echo "ID: {$s->sh_id} | No: {$s->sh_no} | Title: {$s->sh_title} | Status: {$s->sh_status} | Tenant ID: " . ($s->tenant_id ?? 'NULL') . "\n";
}

echo "\n=== ALL FOOD_SUMMARY IN DB ===\n";
$foods = $CI->db->get('food_summary')->result();
echo "Total foods in table: " . count($foods) . "\n";
foreach ($foods as $f) {
    echo "ID: {$f->fds_id} | Title: {$f->fds_food_title} | Status: {$f->fds_status} | Tenant ID: " . ($f->tenant_id ?? 'NULL') . "\n";
}
