<?php
$_SERVER['HTTP_HOST'] = 'kulacrm.com';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
require_once __DIR__ . '/index.php';
$CI = get_instance();
$CI->load->library('ion_auth');
$CI->ion_auth->login('kula@gmail.com', (getenv('KULA_TEST_PASS') ?: ''));
$CI->load->library('kula_ai/Ai_provider', null, 'ai_provider');
$res = $CI->ai_provider->generate_vision('test prompt', 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'image/png');
echo "GENERATE VISION RESULT:\n";
var_export($res);
echo "\n";
