<?php
// Local dev check: notifications must only reach users of the tenant they belong to.
$_SERVER['HTTP_HOST'] = 'localhost:8080';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../index.php';
$_SERVER['REQUEST_URI'] = '/';
putenv('CI_DB_GROUP=default'); putenv('DB_HOST=127.0.0.1'); putenv('DB_PASS='); putenv('DB_NAME=livestock');
require_once __DIR__ . '/../index.php';
$CI =& get_instance();
$CI->load->library('ion_auth');
$CI->load->library('Tenant_notifier');
$db = $CI->db;

// Fixtures: tenant 91 with one admin-group user, tenant 92 with one admin-group user, plus a platform admin in 91
$db->insert('tenants', array('id' => 91, 'name' => 'ZZ Tenant A', 'slug' => 'zza', 'status' => 'active', 'plan_id' => 1, 'email' => 'a@t.l'));
$db->insert('tenants', array('id' => 92, 'name' => 'ZZ Tenant B', 'slug' => 'zzb', 'status' => 'active', 'plan_id' => 1, 'email' => 'b@t.l'));
$mk = function ($name, $tenant, $type) use ($db) {
    $db->insert('users', array('ip_address' => '127.0.0.1', 'username' => $name, 'password' => 'x', 'email' => $name . '@zz.test',
        'created_on' => time(), 'active' => 1, 'tenant_id' => $tenant, 'account_type' => $type));
    $id = $db->insert_id();
    $db->insert('users_groups', array('user_id' => $id, 'group_id' => 1));
    return $id;
};
$ua = $mk('zzusera', 91, 'tenant_user');
$ub = $mk('zzuserb', 92, 'tenant_user');
$up = $mk('zzplatform', 91, 'platform_admin');

$CI->tenant_notifier->notify(91, 'mortality_alert', 'ZZ test A', '<p>x</p>');
$CI->tenant_notifier->set_enabled(92, 0, 'mortality_alert', false);
$CI->tenant_notifier->notify(92, 'mortality_alert', 'ZZ test B (disabled)', '<p>x</p>');
$CI->tenant_notifier->set_enabled(92, 0, 'mortality_alert', true);
$CI->tenant_notifier->set_enabled(92, $ub, 'mortality_alert', false);
$CI->tenant_notifier->notify(92, 'mortality_alert', 'ZZ test B (user opted out)', '<p>x</p>');
$CI->tenant_notifier->notify(9999, 'mortality_alert', 'ZZ nonexistent tenant', '<p>x</p>');

echo "LOG:\n";
foreach ($db->query("SELECT tenant_id, recipient, subject, status FROM email_log WHERE subject LIKE 'ZZ%' ORDER BY id")->result() as $r) {
    echo "  tenant {$r->tenant_id} -> {$r->recipient} | {$r->subject} | {$r->status}\n";
}

// Cleanup
$db->query("DELETE FROM email_log WHERE subject LIKE 'ZZ%'");
$db->query("DELETE FROM notification_settings WHERE tenant_id IN (91, 92)");
$db->query("DELETE ug FROM users_groups ug JOIN users u ON u.id = ug.user_id WHERE u.email LIKE '%@zz.test'");
$db->query("DELETE FROM users WHERE email LIKE '%@zz.test'");
$db->query("DELETE FROM tenants WHERE id IN (91, 92)");
echo "cleaned\n";
