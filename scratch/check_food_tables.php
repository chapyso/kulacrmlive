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

echo "=== CHECKING food_distributed_summary TABLE ===\n";
if ($CI->db->table_exists('food_distributed_summary')) {
    $fields = $CI->db->field_data('food_distributed_summary');
    foreach ($fields as $f) {
        echo "- {$f->name} ({$f->type})\n";
    }
} else {
    echo "TABLE food_distributed_summary DOES NOT EXIST!\n";
}

echo "\n=== CHECKING food_distributed_value TABLE ===\n";
if ($CI->db->table_exists('food_distributed_value')) {
    $fields = $CI->db->field_data('food_distributed_value');
    foreach ($fields as $f) {
        echo "- {$f->name} ({$f->type})\n";
    }
} else {
    echo "TABLE food_distributed_value DOES NOT EXIST!\n";
}
