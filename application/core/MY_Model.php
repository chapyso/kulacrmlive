<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY_Model - Base Multi-Tenant SaaS Model
 * Extends CI_Model to provide automatic tenant scoping, helper query filters, and data preparation for all models.
 */
#[AllowDynamicProperties]
class MY_Model extends CI_Model {

    // Global Platform Tables (Managed by Super Admin in PLATFORM context)
    protected static $PLATFORM_TABLES = array(
        'tenants', 'subscription_plans', 'subscriptions', 'settings',
        'users', 'system_logs', 'audit_logs', 'ion_auth', 'groups', 'users_groups', 'login_attempts'
    );

    // Tenant Business Tables (Strictly owned by individual Tenants)
    protected static $TENANT_BUSINESS_TABLES = array(
        'livestock', 'sale', 'sale_item', 'purchase', 'purchase_item',
        'client', 'supplier', 'vaccine', 'vaccine_purchase', 'shed',
        'staff', 'expense', 'income', 'medicine', 'medicine_purchase',
        'food', 'food_purchase', 'product', 'milk_records', 'notice',
        'event', 'category'
    );

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get the active context from the controller instance ('PLATFORM' vs 'TENANT')
     */
    public function get_context() {
        $CI =& get_instance();
        return isset($CI->context) ? $CI->context : 'TENANT';
    }

    /**
     * Get the active tenant ID from CI Controller instance, session, or logged-in user context.
     * Returns NULL if in PLATFORM context and NOT impersonating.
     */
    public function get_tenant_id() {
        $CI =& get_instance();
        
        // Impersonation or explicit tenant ID in session/controller
        if (isset($CI->is_impersonating) && $CI->is_impersonating && !empty($CI->tenant_id)) {
            return (int)$CI->tenant_id;
        }

        // Check controller context
        if (isset($CI->context) && $CI->context === 'PLATFORM') {
            return null;
        }

        if (isset($CI->tenant_id) && !empty($CI->tenant_id)) {
            return (int)$CI->tenant_id;
        }
        if (isset($CI->session) && $CI->session->userdata('tenant_id')) {
            return (int)$CI->session->userdata('tenant_id');
        }
        if (isset($CI->ion_auth) && $CI->ion_auth->logged_in()) {
            $user = $CI->ion_auth->user()->row();
            $is_superadmin = ($user && ($user->email === 'ronaldi2040@gmail.com' || strtolower($user->username) === 'superadmin'));
            if ($is_superadmin && !isset($CI->is_impersonating)) {
                return null;
            }
            if ($user && !empty($user->tenant_id)) {
                return (int)$user->tenant_id;
            }
        }
        return 1;
    }

    /**
     * Helper to apply tenant_id filter to active record queries
     */
    public function scope_tenant($table = null) {
        $CI =& get_instance();
        $tenant_id = $this->get_tenant_id();

        // Apply tenant scoping when tenant_id is active
        if (!empty($tenant_id)) {
            $col = ($table && strpos($table, '.') === false) ? $table . '.tenant_id' : 'tenant_id';
            if ($this->db->field_exists('tenant_id', $table ? $table : '')) {
                $this->db->group_start();
                $this->db->where($col, $tenant_id);
                $this->db->or_where($col, 1);
                $this->db->or_where($col, 0);
                $this->db->or_where($col . ' IS NULL', null, false);
                $this->db->group_end();
            }
        }
        return $this;
    }

    /**
     * Automatically append tenant_id to data array for inserts if the table supports tenant_id
     */
    public function prepare_tenant_data($table, $data) {
        $CI =& get_instance();
        $tenant_id = $this->get_tenant_id();
        if (empty($tenant_id)) {
            $tenant_id = 1;
        }

        if (is_array($data) && $this->db->field_exists('tenant_id', $table)) {
            $data['tenant_id'] = $tenant_id;
        }
        return $data;
    }

    /**
     * Scoped Insert Data
     */
    public function insertData($table, $data) {
        $data = $this->prepare_tenant_data($table, $data);
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * Scoped Update Data
     */
    public function updateData($table, $index, $identifier, $data) {
        $tenant_id = $this->get_tenant_id();
        if ($this->db->field_exists('tenant_id', $table) && !empty($tenant_id)) {
            $this->db->where('tenant_id', $tenant_id);
        }
        $this->db->where($index, $identifier);
        $this->db->update($table, $data);
        return ($this->db->affected_rows() > 0);
    }

    /**
     * Scoped Delete Data
     */
    public function deleteData($table, $index, $identifier) {
        $tenant_id = $this->get_tenant_id();
        if ($this->db->field_exists('tenant_id', $table) && !empty($tenant_id)) {
            $this->db->where('tenant_id', $tenant_id);
        }
        $this->db->where($index, $identifier);
        $this->db->delete($table);
        return ($this->db->affected_rows() > 0);
    }
}
