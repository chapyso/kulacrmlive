<?php
$_SERVER['HTTP_HOST'] = 'localhost:8000';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../index.php';
$_SERVER['REQUEST_URI'] = '/';

require_once __DIR__ . '/../index.php';
$CI =& get_instance();
$CI->load->library('ion_auth');

echo "=== TESTING SUPER ADMIN AUTHENTICATION ===\n";
$login = $CI->ion_auth->login('ronaldi2040@gmail.com', 'Baale@256');
echo "Login Status: " . ($login ? "SUCCESS [PASS]" : "FAILED") . "\n";

if ($login) {
    $user = $CI->ion_auth->user()->row();
    echo "User ID: {$user->id}\n";
    echo "Email: {$user->email}\n";
    echo "Account Type: {$user->account_type}\n";
    echo "DB Tenant ID: " . var_export($user->tenant_id, true) . "\n";
    
    // Simulate Auth controller login redirect logic
    if ($user && (!empty($user->account_type) && $user->account_type === 'platform_admin' || $user->email === 'ronaldi2040@gmail.com')) {
        $CI->session->unset_userdata('tenant_id');
        $CI->session->unset_userdata('tenant_slug');
        $CI->session->set_userdata('account_type', 'platform_admin');
    }
    
    echo "Session Tenant ID: " . var_export($CI->session->userdata('tenant_id'), true) . "\n";
    echo "Context: " . $CI->context . "\n";
    echo "Is Super Admin Check: " . var_export($CI->is_super_admin(), true) . "\n";
}
