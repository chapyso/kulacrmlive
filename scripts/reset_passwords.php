<?php
$_SERVER['HTTP_HOST'] = 'kulacrm.com';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
require_once '/var/www/html/index.php';
$CI =& get_instance();
$CI->load->library('ion_auth');

// Reset admin@example.com password to 'password'
$CI->ion_auth->update(1, array('password' => 'password'));
echo "Updated admin@example.com (ID 1) password to 'password'\n";

// Reset client@example.com password to 'password'
$CI->ion_auth->update(244, array('password' => 'password'));
echo "Updated client@example.com (ID 244) password to 'password'\n";

// Ensure superadmin role assignment
$CI->db->where('user_id', 1)->delete('users_groups');
$supergroup = $CI->db->get_where('groups', array('name' => 'superadmin'))->row();
if (!$supergroup) {
    $CI->db->insert('groups', array('name' => 'superadmin', 'description' => 'Super Administrator'));
    $gid = $CI->db->insert_id();
} else {
    $gid = $supergroup->id;
}
$CI->db->insert('users_groups', array('user_id' => 1, 'group_id' => $gid));
echo "Ensured user ID 1 is in superadmin group\n";
