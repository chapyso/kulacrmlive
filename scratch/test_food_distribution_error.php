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
$CI->load->library('ion_auth');

echo "=== TESTING ION_AUTH USER OBJECT ===\n";
$user = $CI->ion_auth->user()->row();
var_dump($user);
if ($user) {
    echo "user->id: " . ($user->id ?? 'NULL') . "\n";
    echo "user->user_id: " . ($user->user_id ?? 'UNDEFINED') . "\n";
}

echo "\n=== TESTING COUNT(NULL) ON PHP " . PHP_VERSION . " ===\n";
try {
    $null_val = null;
    $cnt = count($null_val);
    echo "Count: $cnt\n";
} catch (Throwable $e) {
    echo "FATAL ERROR CAUGHT: " . $e->getMessage() . "\n";
}
