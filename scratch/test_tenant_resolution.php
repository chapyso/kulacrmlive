<?php
$_SERVER['HTTP_HOST'] = 'kulacrm.com';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['REQUEST_URI'] = '/kulafarms/dashboard';
$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/..';
chdir(__DIR__ . '/..');

ob_start();
require 'index.php';
ob_end_clean();

$CI =& get_instance();
$CI->load->database();

echo "=== TENANT RESOLUTION TEST ===\n";
echo "Current CI Tenant ID: " . ($CI->tenant_id ?? 'NONE') . "\n";
echo "Current CI Tenant Slug: " . ($CI->tenant_slug ?? 'NONE') . "\n";
echo "Session Tenant ID: " . ($CI->session->userdata('tenant_id') ?? 'NONE') . "\n\n";

echo "=== CHECKING TENANTS TABLE ===\n";
$tenants = $CI->db->get('tenants')->result();
foreach ($tenants as $t) {
    echo "Tenant ID: {$t->id} | Slug: {$t->domain_slug} | Name: {$t->company_name}\n";
}

echo "\n=== CHECKING FOOD_SUMMARY BY TENANT ===\n";
$foods_all = $CI->db->get('food_summary')->result();
foreach ($foods_all as $f) {
    echo "Food ID: {$f->fds_id} | Title: {$f->fds_food_title} | Tenant ID: " . ($f->tenant_id ?? 'NULL') . "\n";
}

echo "\n=== CHECKING SHED BY TENANT ===\n";
$sheds_all = $CI->db->get('shed')->result();
foreach ($sheds_all as $s) {
    echo "Shed ID: {$s->sh_id} | Title: {$s->sh_title} | Tenant ID: " . ($s->tenant_id ?? 'NULL') . "\n";
}
