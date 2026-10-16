<?php
$_SERVER['HTTP_HOST'] = 'localhost:8000';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../index.php';
$_SERVER['REQUEST_URI'] = '/kula_ai/chat';

$prompt_arg = $argv[1] ?? 'ARE YOU STILL CONNECTED ?';
$_POST['prompt'] = $prompt_arg;
$_SERVER['argv'] = array('index.php', 'kula_ai', 'chat');
$_SERVER['argc'] = 3;

require_once __DIR__ . '/../index.php';
$CI =& get_instance();
$CI->load->library('ion_auth');
$CI->ion_auth->login('ronaldi2040@gmail.com', (getenv('KULA_TEST_PASS') ?: ''));

ob_start();
$CI->load->module('kula_ai');
$CI->kula_ai->chat();
$response = ob_get_clean();

echo "=== KULAAI CHAT ENDPOINT RESPONSE ===\n";
echo $response . "\n";
