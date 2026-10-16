<?php
// Local dev check: Ion_auth email config must follow the saas_smtp_settings row.
$_SERVER['HTTP_HOST'] = 'localhost:8080';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../index.php';
$_SERVER['REQUEST_URI'] = '/';
putenv('CI_ENV=development'); putenv('CI_DB_GROUP=default'); putenv('DB_HOST=127.0.0.1'); putenv('DB_PASS='); putenv('DB_NAME=livestock');
require_once __DIR__ . '/../index.php';
$CI =& get_instance();
$CI->load->database();

$CI->db->query("DELETE FROM saas_smtp_settings");
$CI->db->insert('saas_smtp_settings', array('from_name' => 'ZZ', 'from_email' => 'zz@override.test', 'smtp_host' => 'smtp.override.test',
    'smtp_port' => 587, 'mail_username' => 'zz-user', 'mail_password' => 'zz-pass', 'smtp_encryption' => 'TLS'));

require_once APPPATH . "libraries/Ion_auth.php"; $fresh = new Ion_auth();
echo "\nRESULT host=" . $CI->email->smtp_host . " port=" . $CI->email->smtp_port . " user=" . $CI->email->smtp_user
    . " crypto=" . $CI->email->smtp_crypto . " pass_set=" . (($CI->email->smtp_pass === 'zz-pass') ? 'yes' : 'no')
    . " admin_email=" . $CI->config->item('admin_email', 'ion_auth') . "\n";

$CI->db->query("DELETE FROM saas_smtp_settings WHERE from_name = 'ZZ'");
echo "cleaned\n";
