<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tenant_notifier
 * Single, tenant-scoped entry point for notification emails.
 *  - Recipients are always users of the given tenant (never platform admins, never other tenants).
 *  - A recipient must hold the category's permission (or be a tenant "admin" group member).
 *  - Tenant defaults and per-user opt-outs live in notification_settings.
 *  - Every attempt is written to email_log with the tenant_id.
 * Platform-level mail (billing, broadcasts) stays in Email_service_model / Superadmin.
 */
class Tenant_notifier {

    protected $CI;

    /**
     * category => label, permission required to receive it, default on/off for a tenant
     */
    public static function categories() {
        return array(
            'mortality_alert'   => array('label' => 'Mortality alerts (deaths recorded)',  'permission' => 'livestock.view', 'default' => 1),
            'vaccination_due'   => array('label' => 'Vaccinations due or overdue',         'permission' => 'vaccine.view',   'default' => 1),
            'low_stock'         => array('label' => 'Low feed / inventory warnings',       'permission' => 'food.view',      'default' => 1),
            'overdue_balances'  => array('label' => 'Overdue customer balances',           'permission' => 'client.view',    'default' => 1),
            'daily_digest'      => array('label' => 'Daily farm digest',                   'permission' => 'reports.view',   'default' => 0),
            'purchase_activity' => array('label' => 'Purchase confirmations',              'permission' => 'purchase.view',  'default' => 0),
        );
    }

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
    }

    /**
     * Notify the eligible users of ONE tenant.
     *
     * @return array counts: sent, failed, skipped
     */
    public function notify($tenant_id, $category, $subject, $body_html) {
        $result = array('sent' => 0, 'failed' => 0, 'skipped' => 0);
        $tenant_id = (int)$tenant_id;
        $cats = self::categories();
        if ($tenant_id <= 0 || !isset($cats[$category])) {
            return $result;
        }

        $tenant = $this->CI->db->get_where('tenants', array('id' => $tenant_id))->row();
        if (!$tenant || $tenant->status !== 'active') {
            return $result;
        }
        if (!$this->is_enabled($tenant_id, 0, $category, (int)$cats[$category]['default'])) {
            return $result;
        }

        $this->CI->load->model('Email_service_model');
        $this->CI->load->model('Rbac_model');

        foreach ($this->recipients($tenant_id, $cats[$category]['permission']) as $user) {
            if (!$this->is_enabled($tenant_id, (int)$user->id, $category, 1)) {
                $result['skipped']++;
                continue;
            }
            $sent = $this->CI->Email_service_model->send_generic($user->email, $subject, $body_html, $tenant->name);
            $this->log($tenant_id, (int)$user->id, $category, $user->email, $subject, $sent ? 'sent' : 'failed');
            $result[$sent ? 'sent' : 'failed']++;
        }
        return $result;
    }

    /**
     * Platform-originated mail (billing, plan, suspension) to the ADMINS of one tenant.
     * Not affected by tenant/user notification settings. Never reaches platform admins or other tenants.
     */
    public function notify_admins($tenant_id, $category, $subject, $body_html) {
        $result = array('sent' => 0, 'failed' => 0, 'skipped' => 0);
        $tenant_id = (int)$tenant_id;
        $tenant = $tenant_id > 0 ? $this->CI->db->get_where('tenants', array('id' => $tenant_id))->row() : null;
        if (!$tenant) {
            return $result;
        }
        $this->CI->load->model('Email_service_model');

        $this->CI->db->where('tenant_id', $tenant_id)->where('active', 1)->where('email !=', '');
        if ($this->CI->db->field_exists('account_type', 'users')) {
            $this->CI->db->where('account_type !=', 'platform_admin');
        }
        foreach ($this->CI->db->get('users')->result() as $u) {
            if (!$this->CI->ion_auth->in_group('admin', (int)$u->id)) {
                continue;
            }
            $sent = $this->CI->Email_service_model->send_generic($u->email, $subject, $body_html, 'KulaCRM');
            $this->log($tenant_id, (int)$u->id, $category, $u->email, $subject, $sent ? 'sent' : 'failed');
            $result[$sent ? 'sent' : 'failed']++;
        }
        return $result;
    }

    public function recently_logged($tenant_id, $category, $hours) {
        if (!$this->CI->db->table_exists('email_log')) {
            return false;
        }
        return $this->CI->db->where('tenant_id', (int)$tenant_id)->where('category', $category)->where('status', 'sent')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-' . (int)$hours . ' hours')))
            ->count_all_results('email_log') > 0;
    }

    /**
     * Active, non-platform users of this tenant who hold the permission
     */
    protected function recipients($tenant_id, $permission) {
        $this->CI->db->where('tenant_id', $tenant_id);
        $this->CI->db->where('active', 1);
        $this->CI->db->where('email !=', '');
        if ($this->CI->db->field_exists('account_type', 'users')) {
            $this->CI->db->where('account_type !=', 'platform_admin');
        }
        $users = $this->CI->db->get('users')->result();

        $out = array();
        foreach ($users as $u) {
            $is_admin = $this->CI->ion_auth->in_group('admin', (int)$u->id);
            if ($is_admin || $this->CI->Rbac_model->hasPermission((int)$u->id, $permission)) {
                $out[] = $u;
            }
        }
        return $out;
    }

    /**
     * Setting lookup: user row overrides tenant default row, which overrides the category default
     */
    public function is_enabled($tenant_id, $user_id, $category, $default = 1) {
        if (!$this->CI->db->table_exists('notification_settings')) {
            return (bool)$default;
        }
        $row = $this->CI->db->get_where('notification_settings', array(
            'tenant_id' => (int)$tenant_id, 'user_id' => (int)$user_id, 'category' => $category
        ))->row();
        return $row ? (bool)$row->enabled : (bool)$default;
    }

    public function set_enabled($tenant_id, $user_id, $category, $enabled) {
        $cats = self::categories();
        if (!isset($cats[$category])) {
            return false;
        }
        $this->CI->db->query(
            'INSERT INTO notification_settings (tenant_id, user_id, category, enabled) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE enabled = VALUES(enabled)',
            array((int)$tenant_id, (int)$user_id, $category, $enabled ? 1 : 0)
        );
        return true;
    }

    protected function log($tenant_id, $user_id, $category, $recipient, $subject, $status) {
        if ($this->CI->db->table_exists('email_log')) {
            $this->CI->db->insert('email_log', array(
                'tenant_id' => $tenant_id, 'user_id' => $user_id, 'category' => $category,
                'recipient' => $recipient, 'subject' => mb_substr($subject, 0, 255), 'status' => $status,
                'created_at' => date('Y-m-d H:i:s'),
            ));
        }
    }
}
