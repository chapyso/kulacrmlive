<?php
define('BASEPATH', 'system/');
require_once 'index.php';
$CI = get_instance();
$CI->load->library('kula_ai/Ai_provider', null, 'ai_provider');
$res = $CI->ai_provider->generate('You are KulaAI.', 'Give me a full management summary of the farm.');
print_r($res);
