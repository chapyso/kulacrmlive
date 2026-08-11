<?php
$_SERVER['HTTP_HOST'] = 'localhost:8000';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../index.php';
$_SERVER['REQUEST_URI'] = '/kula_ai/chat';

$_POST['prompt'] = 'Write a comprehensive business plan for a 1,000 layer poultry farm including ROI and financial projections.';

require_once __DIR__ . '/../index.php';
$CI =& get_instance();
$CI->load->library('ion_auth');
$CI->ion_auth->login('ronaldi2040@gmail.com', 'Baale@256');

ob_start();
$CI->load->module('kula_ai');
$CI->kula_ai->chat();
$response = ob_get_clean();

echo "=== KULAAI CHAT ENDPOINT RESPONSE ===\n";
echo $response . "\n";
