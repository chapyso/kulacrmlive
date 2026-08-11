<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Seed Controller
 * Web migration & Super Admin provisioning endpoint.
 * Protected by secret key parameter.
 */
class Seed extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function superadmin() {
        header('Content-Type: application/json');
        
        $key = $this->input->get('key') ?: $this->input->post('key');
        if ($key !== 'kula2026seed') {
            echo json_encode(array('status' => false, 'error' => 'Invalid authorization key.'));
            return;
        }

        // 1. Modify users.tenant_id to allow NULL
        $this->db->query("ALTER TABLE `users` MODIFY COLUMN `tenant_id` INT(11) NULL DEFAULT NULL");

        // 2. Add account_type column if missing
        $check_col = $this->db->query("SHOW COLUMNS FROM `users` LIKE 'account_type'");
        if ($check_col && $check_col->num_rows() == 0) {
            $this->db->query("ALTER TABLE `users` ADD COLUMN `account_type` ENUM('platform_admin', 'tenant_user') NOT NULL DEFAULT 'tenant_user' AFTER `phone`");
        }

        // 3. Clear login attempts / lockouts
        if ($this->db->table_exists('login_attempts')) {
            $this->db->empty_table('login_attempts');
        }

        // 4. Seed/Update Superadmin Account ronaldi2040@gmail.com
        $pass_hash = password_hash('Baale@256', PASSWORD_BCRYPT);
        $time = time();

        $existing = $this->db->get_where('users', array('email' => 'ronaldi2040@gmail.com'))->row();
        if ($existing) {
            $this->db->where('id', $existing->id)->update('users', array(
                'username'     => 'ronaldi2040',
                'first_name'   => 'Platform',
                'last_name'    => 'SuperAdmin',
                'password'     => $pass_hash,
                'account_type' => 'platform_admin',
                'tenant_id'    => NULL,
                'active'       => 1
            ));
        } else {
            $this->db->insert('users', array(
                'ip_address'   => '127.0.0.1',
                'username'     => 'ronaldi2040',
                'email'        => 'ronaldi2040@gmail.com',
                'password'     => $pass_hash,
                'created_on'   => $time,
                'active'       => 1,
                'first_name'   => 'Platform',
                'last_name'    => 'SuperAdmin',
                'phone'        => '0',
                'account_type' => 'platform_admin',
                'tenant_id'    => NULL
            ));
        }

        // 5. Seed/Update admin@kulacrm.com
        $existing_admin = $this->db->get_where('users', array('email' => 'admin@kulacrm.com'))->row();
        if ($existing_admin) {
            $this->db->where('id', $existing_admin->id)->update('users', array(
                'password'     => $pass_hash,
                'account_type' => 'platform_admin',
                'tenant_id'    => NULL,
                'active'       => 1
            ));
        } else {
            $this->db->insert('users', array(
                'ip_address'   => '127.0.0.1',
                'username'     => 'kulafarms',
                'email'        => 'admin@kulacrm.com',
                'password'     => $pass_hash,
                'created_on'   => $time,
                'active'       => 1,
                'first_name'   => 'kulafarms',
                'last_name'    => 'SUPER ADMIN',
                'phone'        => '0',
                'account_type' => 'platform_admin',
                'tenant_id'    => NULL
            ));
        }

        // 6. Ensure superadmin group assignment
        $grp = $this->db->get_where('groups', array('name' => 'superadmin'))->row();
        $group_id = $grp ? $grp->id : null;
        if (!$group_id) {
            $this->db->insert('groups', array('name' => 'superadmin', 'description' => 'SaaS Platform Super Admin'));
            $group_id = $this->db->insert_id();
        }

        $all_sa = $this->db->get_where('users', array('account_type' => 'platform_admin'))->result();
        foreach ($all_sa as $sa_user) {
            $this->db->where('user_id', $sa_user->id)->delete('users_groups');
            $this->db->insert('users_groups', array('user_id' => $sa_user->id, 'group_id' => $group_id));
        }

        // 7. Ensure `admin` group exists and assign ALL active tenant users to `admin` group in `users_groups`
        $admin_grp = $this->db->get_where('groups', array('name' => 'admin'))->row();
        $admin_grp_id = $admin_grp ? $admin_grp->id : null;
        if (!$admin_grp_id) {
            $this->db->insert('groups', array('name' => 'admin', 'description' => 'Administrator'));
            $admin_grp_id = $this->db->insert_id();
        }

        $all_tenant_users = $this->db->get_where('users', array('active' => 1))->result();
        foreach ($all_tenant_users as $tu) {
            $check_ug = $this->db->get_where('users_groups', array('user_id' => $tu->id, 'group_id' => $admin_grp_id))->row();
            if (!$check_ug) {
                $this->db->insert('users_groups', array('user_id' => $tu->id, 'group_id' => $admin_grp_id));
            }
        }

        // 8. Sync password Baale@256 for all active tenant users
        $this->db->query("UPDATE `users` SET `password` = '$pass_hash' WHERE `active` = 1");

        // 9. Sync Tenant #1 & slug parity (ensure slug 'default' and 'kulafarms' exist)
        if ($this->db->table_exists('tenants')) {
            $t1 = $this->db->get_where('tenants', array('id' => 1))->row();
            if ($t1) {
                $this->db->where('id', 1)->update('tenants', array(
                    'name'      => 'Kula Demo Farm',
                    'slug'      => 'default',
                    'slug_name' => 'kulafarms',
                    'status'    => 'active',
                    'plan_id'   => 4
                ));
            } else {
                $this->db->insert('tenants', array(
                    'id'        => 1,
                    'name'      => 'Kula Demo Farm',
                    'slug'      => 'default',
                    'slug_name' => 'kulafarms',
                    'email'     => 'admin@kulacrm.com',
                    'status'    => 'active',
                    'plan_id'   => 4
                ));
            }

            // Ensure tenant_id = 1 on active tenant users
            $this->db->query("UPDATE `users` SET `tenant_id` = 1 WHERE `account_type` = 'tenant_user' OR `account_type` IS NULL");
        }

        $all_users = $this->db->get('users')->result();
        $user_summary = array();
        foreach ($all_users as $tu) {
            $user_summary[] = array('id' => $tu->id, 'email' => $tu->email, 'username' => $tu->username, 'account_type' => $tu->account_type ?? 'tenant_user', 'tenant_id' => $tu->tenant_id ?? null);
        }

        $all_tenants = $this->db->table_exists('tenants') ? $this->db->get('tenants')->result() : array();

        echo json_encode(array(
            'status'  => true,
            'message' => 'Super Admin accounts, tenant group permissions, tenant passwords, and tenant #1 plan parity seeded successfully!',
            'users'   => $user_summary,
            'tenants' => $all_tenants
        ));
    }
}
