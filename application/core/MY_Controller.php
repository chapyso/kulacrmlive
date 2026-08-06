<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY_Controller - Base Multi-Tenant SaaS Controller
 * Extends MX_Controller to support HMVC module architecture while enforcing global multi-tenant isolation and route guards.
 */
class MY_Controller extends MX_Controller {
    public $tenant_id = null;
    public $tenant_slug = null;
    public $tenant_data = null;
    public $context = 'PLATFORM';
    public $is_impersonating = false;
    public $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
        if (!isset($this->ion_auth)) {
            $this->load->library('Ion_auth');
        }

        $this->resolve_context();
        $this->check_application_guard();
        $this->init_language();
    }

    /**
     * Language Resolution & System Syntax Auto-loading
     */
    protected function init_language() {
        $this->load->helper(array('url', 'language'));
        $this->load->model('settings/settings_model');
        
        $language = $this->get_language();

        $load_lang = $language;
        if (strtolower($language) === 'luganda' && is_dir(APPPATH . 'language/Luganda') && !is_dir(APPPATH . 'language/luganda')) {
            $load_lang = 'Luganda';
        }

        $this->lang->load('system_syntax', $load_lang);
        if (file_exists(APPPATH . 'language/' . $load_lang . '/auth_lang.php')) {
            $this->lang->load('auth', $load_lang);
        }
        if (file_exists(APPPATH . 'language/' . $load_lang . '/ion_auth_lang.php')) {
            $this->lang->load('ion_auth', $load_lang);
        }

        $this->data['active_language'] = strtolower($language);
    }

    /**
     * Get current active language for session/user
     */
    public function get_language() {
        $session_lang = $this->session->userdata('language');
        if (!empty($session_lang)) {
            return strtolower($session_lang);
        }
        if ($this->settings_model) {
            $settings = $this->settings_model->getSettings();
            if (!empty($settings) && !empty($settings->language)) {
                return strtolower($settings->language);
            }
        }
        return 'english';
    }

    /**
     * Switch language route action
     */
    public function switch_language($lang = 'english') {
        $allowed_languages = array(
            'english', 'swahili', 'luganda', 'runyankore', 'lusoga', 
            'arabic', 'spanish', 'french', 'portuguese', 'german', 
            'russian', 'zh_cn', 'bulgarian', 'italian', 'dutch', 'turkish'
        );

        $lang = strtolower(trim($lang));
        if (!in_array($lang, $allowed_languages)) {
            $lang = 'english';
        }

        $this->session->set_userdata('language', $lang);

        if ($this->ion_auth && $this->ion_auth->logged_in()) {
            if ($this->settings_model) {
                $settings = $this->settings_model->getSettings();
                if (!empty($settings) && !empty($settings->id)) {
                    $this->settings_model->updateSettings($settings->id, array('language' => $lang));
                }
            }
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode(array('status' => 'success', 'language' => $lang));
            return;
        }

        $referer = $this->input->server('HTTP_REFERER');
        if (!empty($referer)) {
            redirect($referer, 'refresh');
        } else {
            if ($this->ion_auth->logged_in()) {
                redirect(tenant_url('dashboard'), 'refresh');
            } else {
                redirect('auth/login', 'refresh');
            }
        }
    }

    /**
     * Context & Tenant Resolution Protocol
     * Distinguishes between PLATFORM context (Super Admin) and TENANT context (Tenant User or Impersonation)
     */
    protected function resolve_context() {
        $this->is_impersonating = (bool)$this->session->userdata('is_impersonating');
        
        if ($this->ion_auth->logged_in()) {
            $user = $this->ion_auth->user()->row();
            $is_superadmin = ($user && ($user->email === 'ronaldi2040@gmail.com' || strtolower($user->username) === 'superadmin'));

            if ($is_superadmin) {
                if ($this->is_impersonating && $this->session->userdata('tenant_id')) {
                    $this->context = 'TENANT';
                    $this->tenant_id = (int)$this->session->userdata('tenant_id');
                    $this->tenant_slug = $this->session->userdata('tenant_slug') ?: 'kulafarms';
                    $tenant = $this->db->get_where('tenants', array('id' => $this->tenant_id))->row();
                    if ($tenant) {
                        $this->tenant_data = $tenant;
                    }
                    return;
                } else {
                    // Super Admin in Platform Context (tenant_id = NULL)
                    $this->context = 'PLATFORM';
                    $this->tenant_id = null;
                    $this->tenant_slug = null;
                    $this->tenant_data = null;
                    return;
                }
            } else {
                // Regular Tenant User Context
                $this->context = 'TENANT';
                if ($user && !empty($user->tenant_id)) {
                    $tenant = $this->db->get_where('tenants', array('id' => (int)$user->tenant_id))->row();
                    if ($tenant) {
                        $this->tenant_id = (int)$tenant->id;
                        $this->tenant_slug = !empty($tenant->slug_name) ? $tenant->slug_name : $tenant->slug;
                        $this->tenant_data = $tenant;
                        $this->session->set_userdata('tenant_id', $this->tenant_id);
                        $this->session->set_userdata('tenant_slug', $this->tenant_slug);
                        return;
                    }
                }
            }
        }

        // Unauthenticated or Path-based tenant resolution
        $segment1 = strtolower($this->uri->segment(1));
        $system_segments = array('superadmin', 'auth', 'api', 'common', 'uploads', 'settings', 'assets', 'cron', 'home', 'livestock', 'shed', 'vaccine', 'food', 'purchase', 'sale', 'client', 'supplier', 'expense', 'staff', 'report', 'product', 'users');


        if (!empty($segment1) && !in_array($segment1, $system_segments)) {
            $tenant = $this->db->where('slug', $segment1)
                               ->where('status', 'active')
                               ->get('tenants')
                               ->row();
            if ($tenant) {
                $this->context = 'TENANT';
                $this->tenant_id = (int)$tenant->id;
                $this->tenant_slug = $tenant->slug;
                $this->tenant_data = $tenant;
                $this->session->set_userdata('tenant_id', $this->tenant_id);
                $this->session->set_userdata('tenant_slug', $this->tenant_slug);
                return;
            }
        }

        // Host Subdomain fallback
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $parts = explode('.', $host);
        if (count($parts) >= 2 && $parts[0] !== 'www' && $parts[0] !== 'localhost' && !is_numeric($parts[0])) {
            $slug = strtolower($parts[0]);
            $tenant = $this->db->where('slug', $slug)
                               ->where('status', 'active')
                               ->get('tenants')
                               ->row();
            if ($tenant) {
                $this->context = 'TENANT';
                $this->tenant_id = (int)$tenant->id;
                $this->tenant_slug = !empty($tenant->slug_name) ? $tenant->slug_name : $tenant->slug;
                $this->tenant_data = $tenant;
                $this->session->set_userdata('tenant_id', $this->tenant_id);
                $this->session->set_userdata('tenant_slug', $this->tenant_slug);
                return;
            }
        }

        // Default tenant fallback for tenant requests
        if ($this->context === 'TENANT' || empty($this->uri->segment(1)) || $this->uri->segment(1) === 'home') {
            $this->context = 'TENANT';
            $this->tenant_id = 1;
            $this->tenant_slug = 'kulafarms';
            $this->session->set_userdata('tenant_id', 1);
            $this->session->set_userdata('tenant_slug', 'kulafarms');
        }
    }

    /**
     * Route Guard: Enforce strict separation between SaaS Admin Platform and Tenant Application
     */
    protected function check_application_guard() {
        if (!$this->ion_auth->logged_in()) {
            return;
        }

        $user = $this->ion_auth->user()->row();
        $is_superadmin = ($user && ($user->email === 'ronaldi2040@gmail.com' || strtolower($user->username) === 'superadmin'));
        $segment1 = strtolower($this->uri->segment(1));
        $is_superadmin_route = ($segment1 === 'superadmin');

        // 1. Super Admin in PLATFORM context attempting to visit a tenant business module without impersonation
        if ($is_superadmin && !$is_superadmin_route && !$this->is_impersonating) {
            $exempt_segments = array('auth', 'api', 'common', 'uploads', 'assets');
            if (!in_array($segment1, $exempt_segments)) {
                redirect('superadmin', 'refresh');
            }
        }

        // 2. Tenant user trying to access /superadmin
        if (!$is_superadmin && $is_superadmin_route) {
            redirect(tenant_url('dashboard'), 'refresh');
        }
    }

    /**
     * Check if user is Super Admin
     */
    public function is_super_admin() {
        if (!$this->ion_auth->logged_in()) {
            return false;
        }
        $user = $this->ion_auth->user()->row();
        return ($user && ($user->email === 'ronaldi2040@gmail.com' || strtolower($user->username) === 'superadmin'));
    }

    /**
     * Check if active session is impersonating a tenant
     */
    public function is_impersonating() {
        return (bool)$this->session->userdata('is_impersonating');
    }

    /**
     * Resolve isolated filesystem upload directory path for active tenant context
     */
    public function get_tenant_upload_path($subfolder = '') {
        $folder = (!empty($this->tenant_id) && $this->context === 'TENANT') ? 'tenant_' . $this->tenant_id : 'tenant_platform';
        $full_path = FCPATH . 'uploads/' . $folder . '/';
        if (!empty($subfolder)) {
            $full_path .= trim($subfolder, '/') . '/';
        }
        if (!is_dir($full_path)) {
            mkdir($full_path, 0777, true);
        }
        return $full_path;
    }

    /**
     * Resolve relative upload path string for DB storage
     */
    public function get_tenant_upload_relative_path($filename, $subfolder = '') {
        $folder = (!empty($this->tenant_id) && $this->context === 'TENANT') ? 'tenant_' . $this->tenant_id : 'tenant_platform';
        $rel = 'uploads/' . $folder . '/';
        if (!empty($subfolder)) {
            $rel .= trim($subfolder, '/') . '/';
        }
        return $rel . ltrim($filename, '/');
    }

    /**
     * Record sensitive platform & security audit logs
     */
    public function log_audit($action, $target_tenant_id = null, $details = null) {
        $user_id = null;
        $user_email = null;
        if ($this->ion_auth && $this->ion_auth->logged_in()) {
            $u = $this->ion_auth->user()->row();
            if ($u) {
                $user_id = $u->id;
                $user_email = $u->email;
            }
        }
        $ip = $this->input->ip_address();
        $audit_data = array(
            'tenant_id'        => $this->tenant_id,
            'user_id'          => $user_id,
            'user_email'       => $user_email,
            'action'           => $action,
            'target_tenant_id' => $target_tenant_id,
            'ip_address'       => $ip,
            'details'          => is_array($details) || is_object($details) ? json_encode($details) : $details,
            'created_at'       => date('Y-m-d H:i:s')
        );
        if ($this->db->table_exists('audit_logs')) {
            $this->db->insert('audit_logs', $audit_data);
        }
    }

    /**
     * Evaluate if active logged-in user possesses a permission
     */
    public function has_permission($permission_name) {
        if ($this->is_super_admin()) {
            return true;
        }
        if (!$this->ion_auth->logged_in()) {
            return false;
        }
        $user = $this->ion_auth->user()->row();
        if (!$user) {
            return false;
        }
        $this->load->model('Rbac_model');
        return $this->Rbac_model->hasPermission($user->id, $permission_name);
    }

    /**
     * Check if active logged-in user has specific role slug
     */
    public function has_role($role_slug) {
        if ($this->is_super_admin()) {
            return true;
        }
        if (!$this->ion_auth->logged_in()) {
            return false;
        }
        $user = $this->ion_auth->user()->row();
        if (!$user) {
            return false;
        }
        $this->load->model('Rbac_model');
        return $this->Rbac_model->hasRole($user->id, $role_slug);
    }

    /**
     * Enforce access control guard for a specific permission
     */
    public function check_permission($permission_name) {
        if (!$this->has_permission($permission_name)) {
            show_error("Access Denied: You do not possess the required permission ('$permission_name') to perform this action.", 403, "Permission Denied Guard");
        }
    }
}

