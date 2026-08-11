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
$CI->load->model('shed/shed_model');
$CI->load->model('food/food_model');

echo "=== TESTING getShed() ===\n";
$sheds = $CI->shed_model->getShed();
echo "Sheds count: " . count($sheds) . "\n";
echo "Last Query: " . $CI->db->last_query() . "\n\n";

echo "=== TESTING getFood() ===\n";
$foods = $CI->food_model->getFood();
echo "Foods count: " . count($foods) . "\n";
echo "Last Query: " . $CI->db->last_query() . "\n\n";
