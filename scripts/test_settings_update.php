<?php
$_SERVER['HTTP_HOST'] = 'localhost:8000';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../index.php';
$_SERVER['REQUEST_URI'] = '/settings/update';

$_POST['id'] = '1';
$_POST['name'] = 'KulaCRM SaaS';
$_POST['title'] = 'KulaCRM Enterprise';
$_POST['email'] = 'admin@kulacrm.com';
$_POST['address'] = 'Plot 42 Innovation Way, Kampala';
$_POST['phone'] = '+256766751727';
$_POST['currency'] = 'UGX';
$_POST['unit'] = 'head';
$_POST['date_format'] = 'dd-mm-yyyy';
$_POST['login_title'] = 'KulaCRM SaaS Portal';
$_POST['timezone'] = 'Africa/Kampala';
$_POST['low_stock_threshold'] = '15';
$_POST['overdue_payment_days'] = '10';

require_once __DIR__ . '/../index.php';
$CI =& get_instance();
$CI->load->library('ion_auth');
$CI->ion_auth->login('ronaldi2040@gmail.com', 'Baale@256');

$CI->load->module('settings');
$CI->settings->update();

// Re-fetch to verify persistence
$CI->load->model('settings/settings_model');
$fresh = $CI->settings_model->getSettings();
echo "=== PERSISTENCE CHECK ===\n";
echo "System Name: " . $fresh->system_vendor . "\n";
echo "Title: " . $fresh->title . "\n";
echo "Email: " . $fresh->email . "\n";
echo "Currency: " . $fresh->currency . "\n";
