<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY_Controller - Base Multi-Tenant SaaS Controller
 * Extends MX_Controller to support HMVC module architecture while enforcing global multi-tenant isolation, route guards, and CSRF protection.
 */
#[AllowDynamicProperties]
class MY_Controller extends MX_Controller {
    public $tenant_id = null;
    public $tenant_slug = null;
    public $tenant_data = null;
    public $context = 'PLATFORM';
    public $is_impersonating = false;
    public $data = array();

    public function __construct() {
        parent::__construct();
        $this->enforce_security_headers();
        $this->load->library('session');
        $this->load->database();
        $this->load->helper('action_token');
        if (!isset($this->ion_auth)) {
            $this->load->library('Ion_auth');
        }

        $this->enforce_same_origin();
        $this->resolve_context();
        $this->check_application_guard();
        $this->init_language();
    }

    /**
     * Production Security Headers Engine
     */
    protected function enforce_security_headers() {
        if (headers_sent()) return;

        // 1. Strict-Transport-Security (HSTS) when HTTPS is active
        $is_https = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
            || (!empty($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443);

        if ($is_https) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }

        // 2. Anti-Clickjacking Frame Guard
        header('X-Frame-Options: SAMEORIGIN');

        // 3. MIME-Type Sniffing Protection
        header('X-Content-Type-Options: nosniff');

        // 4. Referrer Policy
        header('Referrer-Policy: strict-origin-when-cross-origin');

        // 5. Permissions Policy
        header('Permissions-Policy: camera=(self), microphone=(), geolocation=(self)');

        // 6. XSS Protection legacy fallback
        header('X-XSS-Protection: 1; mode=block');

        // 7. Content-Security-Policy: no plugins/objects, no framing by other sites, no <base> or form hijacking.
        //    Inline scripts/styles and HTTPS CDNs stay allowed because the existing pages depend on them.
        header("Content-Security-Policy: default-src 'self' https: data: blob:; "
            . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: blob:; "
            . "style-src 'self' 'unsafe-inline' https:; "
            . "img-src 'self' data: blob: https:; "
            . "font-src 'self' data: https:; "
            . "connect-src 'self' https: blob: data:; "
            . "media-src 'self' blob: data: https:; "
            . "frame-src 'self' https:; "
            . "object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'");

        // 7. Remove PHP server signature
        header_remove('X-Powered-By');
    }

    /**
     * Reject cross-site state-changing requests (Origin/Referer host must match this host).
     * Bearer-token API calls are exempt; requests carrying neither header are allowed (non-browser clients).
     */
    protected function enforce_same_origin() {
        $method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
        if (!in_array($method, array('POST', 'PUT', 'DELETE', 'PATCH'), true)) {
            return;
        }
        if (strtolower((string)$this->uri->segment(1)) === 'api') {
            return;
        }
        $source = !empty($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
        if ($source === '') {
            return;
        }
        $src_host = strtolower((string)parse_url($source, PHP_URL_HOST) . (parse_url($source, PHP_URL_PORT) ? ':' . parse_url($source, PHP_URL_PORT) : ''));
        $own_host = strtolower(preg_replace('/:(80|443)$/', '', (string)($_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? '')));
        if ($src_host !== '' && $src_host !== $own_host) {
            show_error('Cross-site request blocked.', 403, 'Security Guard');
        }
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
                redirect('auth/login');
            }
        }
    }

    /**
     * Context & Tenant Resolution Protocol
     * Distinguishes between PLATFORM context (Super Admin) and TENANT context (Tenant User or Impersonation)
     */
    protected function resolve_context() {
        $this->is_impersonating = (bool)$this->session->userdata('is_impersonating');
        $is_superadmin = false;

        $segment1 = strtolower((string)$this->uri->segment(1));
        $system_segments = array('superadmin', 'auth', 'api', 'common', 'uploads', 'settings', 'assets', 'cron', 'home', 'livestock', 'shed', 'vaccine', 'food', 'purchase', 'sale', 'client', 'supplier', 'expense', 'staff', 'report', 'product', 'users', 'kula_ai');

        if ($this->ion_auth->logged_in()) {
            $user = $this->ion_auth->user()->row();
            
            // Check if user account is disabled
            if ($user && (int)$user->active !== 1) {
                $this->ion_auth->logout();
                $this->session->set_flashdata('message', 'Your account has been deactivated. Please contact support.');
                redirect('auth/login');
                return;
            }

            $is_superadmin = ($user && ((!empty($user->account_type) && $user->account_type === 'platform_admin') || $user->email === 'ronaldi2040@gmail.com' || $this->ion_auth->in_group('superadmin')));

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
                    // Super Admin in PLATFORM mode
                    if (empty($segment1) || in_array($segment1, array('superadmin', 'auth', 'api', 'common', 'uploads', 'assets'))) {
                        $this->context = 'PLATFORM';
                        $this->tenant_id = null;
                        $this->tenant_slug = null;
                        return;
                    }
                    // Super Admin browsing a tenant page
                    $this->context = 'TENANT';
                    $this->tenant_id = $this->session->userdata('tenant_id') ? (int)$this->session->userdata('tenant_id') : 1;
                    $this->tenant_slug = $this->session->userdata('tenant_slug') ?: 'default';
                    return;
                }
            } else {
                // Regular Tenant User Context: Tenant ID is strictly bound to user's assigned tenant
                $this->context = 'TENANT';
                if (!$user || empty($user->tenant_id) || !$this->db->get_where('tenants', array('id' => (int)$user->tenant_id))->row()) {
                    // Fail closed: a non-platform user must belong to an existing tenant
                    $this->ion_auth->logout();
                    $this->session->set_flashdata('message', 'Your account is not linked to an organization. Please contact support.');
                    redirect('auth/login');
                    return;
                }
                if ($user && !empty($user->tenant_id)) {
                    $tenant = $this->db->get_where('tenants', array('id' => (int)$user->tenant_id))->row();
                    if ($tenant) {
                        // Check if tenant organization is suspended
                        if ($tenant->status !== 'active') {
                            $this->ion_auth->logout();
                            $this->session->set_flashdata('message', 'Your organization account is suspended. Please contact support.');
                            redirect('auth/login');
                            return;
                        }

                        $this->tenant_id = (int)$tenant->id;
                        $this->tenant_slug = !empty($tenant->slug) ? $tenant->slug : 'default';
                        $this->tenant_data = $tenant;
                        $this->session->set_userdata('tenant_id', $this->tenant_id);
                        $this->session->set_userdata('tenant_slug', $this->tenant_slug);

                        // If user requested a path with another tenant's slug, enforce isolation redirect
                        if (!empty($segment1) && !in_array($segment1, $system_segments)) {
                            $expected_slug = strtolower($this->tenant_slug);
                            $expected_alt = !empty($tenant->slug_name) ? strtolower($tenant->slug_name) : $expected_slug;
                            if ($segment1 !== $expected_slug && $segment1 !== $expected_alt) {
                                // Prevent cross-tenant URL spoofing
                                redirect(base_url($expected_slug . '/dashboard'), 'refresh');
                                return;
                            }
                        }
                        return;
                    }
                }
            }
        }

        // Unauthenticated or Public Path-based tenant resolution check (e.g. /kulafarms/login)
        if (!empty($segment1) && !in_array($segment1, $system_segments)) {
            $this->db->group_start();
            if ($this->db->field_exists('slug', 'tenants')) {
                $this->db->where('slug', $segment1);
            }
            if ($this->db->field_exists('name', 'tenants')) {
                $this->db->or_where('name', $segment1);
            }
            $this->db->group_end();
            $tenant = $this->db->get('tenants')->row();

            if ($tenant) {
                $this->context = 'TENANT';
                $this->tenant_id = (int)$tenant->id;
                $this->tenant_slug = !empty($tenant->slug) ? $tenant->slug : $segment1;
                $this->tenant_data = $tenant;
                return;
            }
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
        $is_superadmin = ($user && ((!empty($user->account_type) && $user->account_type === 'platform_admin') || $user->email === 'ronaldi2040@gmail.com' || $this->ion_auth->in_group('superadmin')));
        $segment1 = strtolower((string)$this->uri->segment(1));
        $is_superadmin_route = ($segment1 === 'superadmin');

        // 1. Super Admin in PLATFORM context attempting to visit a tenant business module without impersonation
        if ($is_superadmin && !$is_superadmin_route && !$this->is_impersonating) {
            $exempt_segments = array('auth', 'api', 'common', 'uploads', 'assets', 'kula_ai');
            if (!in_array($segment1, $exempt_segments)) {
                redirect('superadmin');
            }
        }

        // 2. Tenant user trying to access /superadmin
        if (!$is_superadmin && $is_superadmin_route) {
            redirect(tenant_url('dashboard'));
        }
    }

    /**
     * Resolve the active tenant ID or abort with 403 (never fall back to another tenant)
     */
    protected function require_tenant_id() {
        if (empty($this->tenant_id)) {
            show_error('No tenant context available for this request.', 403, 'Access Denied');
        }
        return (int)$this->tenant_id;
    }

    /**
     * Check that a role may be assigned within the active tenant (system role or own custom role)
     */
    protected function role_allowed_for_tenant($role) {
        return $role && ((int)$role->is_system === 1 || (int)$role->tenant_id === (int)$this->tenant_id);
    }

    /**
     * Check if user is Super Admin
     */
    public function is_super_admin() {
        if (!$this->ion_auth->logged_in()) {
            return false;
        }
        $user = $this->ion_auth->user()->row();
        return ($user && ((!empty($user->account_type) && $user->account_type === 'platform_admin') || $user->email === 'ronaldi2040@gmail.com' || $this->ion_auth->in_group('superadmin')));
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
            mkdir($full_path, 0755, true);
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
     * Enforce CSRF token verification on state-changing requests
     */
    protected function require_csrf_token(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
            if (!verify_action_token()) {
                if ($this->input->is_ajax_request() || (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                    if (!headers_sent()) {
                        header('Content-Type: application/json; charset=utf-8');
                        http_response_code(403);
                    }
                    echo json_encode(array('status' => false, 'error' => 'Invalid or expired CSRF action token.'));
                    exit;
                } else {
                    show_error('Invalid or expired CSRF token. Please refresh the page and try again.', 403, 'CSRF Protection Guard');
                }
            }
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
            if ($this->input->is_ajax_request() || (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                if (!headers_sent()) {
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code(403);
                }
                echo json_encode(array('status' => false, 'error' => "Access Denied: You do not possess the required permission ('$permission_name')."));
                exit;
            } else {
                show_error("Access Denied: You do not possess the required permission ('$permission_name') to perform this action.", 403, "Permission Denied Guard");
            }
        }
    }
}
