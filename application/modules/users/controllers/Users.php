<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->load->helper(array('url', 'language'));
        $this->load->model('settings/settings_model');
        $this->load->model('Tenant_user_model');
        $this->load->model('Rbac_model');
        $this->load->model('Department_model');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $settings = $this->settings_model->getSettings();
        $language = (!empty($settings) && !empty($settings->language)) ? $settings->language : 'english';
        $this->lang->load('system_syntax', $language);
        $this->lang->load('auth', $language);
    }

    /**
     * User Directory Page
     */
    public function index() {
        $this->check_permission('users.view');

        $tenant_id = $this->require_tenant_id();
        $filters = array(
            'department_id' => $this->input->get('department_id'),
            'status'        => $this->input->get('status'),
            'search'        => $this->input->get('search')
        );

        $data['users'] = $this->Tenant_user_model->getTenantUsers($tenant_id, $filters);
        $data['departments'] = $this->Department_model->getDepartments($tenant_id);
        $data['roles'] = $this->Rbac_model->getRoles($tenant_id);
        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard', $data);
        $this->load->view('users_list', $data);
        $this->load->view('home/footer');
    }

    /**
     * Invite User Action
     */
    public function invite() {
        $this->check_permission('users.invite');

        $email = trim($this->input->post('email'));
        $role_id = (int)$this->input->post('role_id');
        $department_id = $this->input->post('department_id') ? (int)$this->input->post('department_id') : null;
        $tenant_id = $this->require_tenant_id();
        $current_user_id = $this->ion_auth->user()->row()->id;

        if (empty($email) || empty($role_id)) {
            $this->session->set_flashdata('error', 'Email and Role are required.');
            redirect('users');
        }

        if (!$this->role_allowed_for_tenant($this->Rbac_model->getRoleById($role_id))) {
            $this->session->set_flashdata('error', 'Invalid role selected.');
            redirect('users');
        }

        if (strtolower($email) === 'ronaldi2040@gmail.com') {
            $this->session->set_flashdata('error', 'This email address is reserved for Platform Super Admin.');
            redirect('users');
        }

        // Check if email already registered in system
        if ($this->ion_auth->email_check($email)) {
            $this->session->set_flashdata('error', 'A user with this email address already exists.');
            redirect('users');
        }

        $token = $this->Tenant_user_model->createInvitation($tenant_id, $email, $role_id, $department_id, $current_user_id);
        $invite_url = base_url('users/accept_invitation?token=' . $token);

        $this->log_audit('USER_INVITE', $tenant_id, array('email' => $email, 'role_id' => $role_id));

        // Email the invitation to the invitee (tenant name as sender, logged against this tenant)
        $this->load->model('Email_service_model');
        $this->load->library('Tenant_notifier');
        $inviter = $this->ion_auth->user()->row();
        $inviter_name = trim(($inviter->first_name ?? '') . ' ' . ($inviter->last_name ?? '')) ?: ($inviter->username ?? 'A colleague');
        $tenant_name = !empty($this->tenant_data->name) ? $this->tenant_data->name : 'your organization';
        $sent = $this->Email_service_model->send_invitation_email($email, $tenant_name, $inviter_name, $invite_url, date('Y-m-d H:i:s', strtotime('+48 hours')));
        $this->tenant_notifier->log($tenant_id, null, 'invitation', $email, 'Invitation to ' . $tenant_name, $sent ? 'sent' : 'failed');

        $this->session->set_flashdata('success', 'Invitation generated successfully! Share link: ' . $invite_url);
        redirect('users');
    }

    /**
     * Direct User Creation (Manual Fallback)
     */
    public function create() {
        $this->check_permission('users.create');

        $username = trim($this->input->post('username'));
        $email = trim($this->input->post('email'));
        $password = $this->input->post('password');
        $phone = trim($this->input->post('phone'));
        $role_id = (int)$this->input->post('role_id');
        $department_id = $this->input->post('department_id') ? (int)$this->input->post('department_id') : null;
        $tenant_id = $this->require_tenant_id();

        if (empty($username) || empty($email) || empty($password) || empty($role_id)) {
            $this->session->set_flashdata('error', 'Username, Email, Password, and Role are required.');
            redirect('users');
        }

        if (!$this->role_allowed_for_tenant($this->Rbac_model->getRoleById($role_id))) {
            $this->session->set_flashdata('error', 'Invalid role selected.');
            redirect('users');
        }

        if (strtolower($username) === 'superadmin') {
            $this->session->set_flashdata('error', 'This username is reserved.');
            redirect('users');
        }

        if (strtolower($email) === 'ronaldi2040@gmail.com') {
            $this->session->set_flashdata('error', 'This email address is reserved for Platform Super Admin.');
            redirect('users');
        }

        $additional_data = array(
            'tenant_id' => $tenant_id,
            'phone'     => $phone
        );

        $user_id = $this->ion_auth->register($username, $password, $email, $additional_data);
        if ($user_id) {
            $tu_data = array(
                'tenant_id'     => $tenant_id,
                'user_id'       => $user_id,
                'department_id' => $department_id,
                'status'        => 'active',
                'phone'         => $phone,
                'created_at'    => date('Y-m-d H:i:s')
            );
            $this->db->insert('tenant_users', $tu_data);
            $this->Rbac_model->assignRole($user_id, $role_id);

            $this->log_audit('USER_CREATE', $tenant_id, array('user_id' => $user_id, 'email' => $email));
            $this->session->set_flashdata('success', 'User account created successfully.');
        } else {
            $this->session->set_flashdata('error', $this->ion_auth->errors());
        }

        redirect('users');
    }

    /**
     * Email notification preferences: tenant defaults (settings.update) and the user's own opt-outs
     */
    public function notifications() {
        $tenant_id = $this->require_tenant_id();
        $this->load->library('Tenant_notifier');
        $user_id = (int)$this->ion_auth->get_user_id();

        $data['categories'] = Tenant_notifier::categories();
        $data['can_manage_tenant'] = $this->has_permission('settings.update');
        $data['tenant_enabled'] = array();
        $data['user_enabled'] = array();
        foreach ($data['categories'] as $key => $cat) {
            $data['tenant_enabled'][$key] = $this->tenant_notifier->is_enabled($tenant_id, 0, $key, (int)$cat['default']);
            $data['user_enabled'][$key] = $this->tenant_notifier->is_enabled($tenant_id, $user_id, $key, 1);
        }
        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard', $data);
        $this->load->view('notifications', $data);
        $this->load->view('home/footer');
    }

    public function save_notifications() {
        $this->require_csrf_token_post();
        $tenant_id = $this->require_tenant_id();
        $this->load->library('Tenant_notifier');
        $user_id = (int)$this->ion_auth->get_user_id();
        $categories = Tenant_notifier::categories();

        $mine = (array)$this->input->post('mine');
        foreach ($categories as $key => $cat) {
            $this->tenant_notifier->set_enabled($tenant_id, $user_id, $key, !empty($mine[$key]));
        }
        if ($this->has_permission('settings.update')) {
            $tenant = (array)$this->input->post('tenant');
            foreach ($categories as $key => $cat) {
                $this->tenant_notifier->set_enabled($tenant_id, 0, $key, !empty($tenant[$key]));
            }
            $this->log_audit('NOTIFICATION_SETTINGS_UPDATE', $tenant_id, array('by' => $user_id));
        }
        $this->session->set_flashdata('success', 'Notification preferences saved.');
        redirect('users/notifications');
    }

    protected function require_csrf_token_post() {
        if (!verify_action_token()) {
            show_error('Invalid or expired security token. Go back, refresh the page and try again.', 403, 'CSRF Protection Guard');
        }
    }

    /**
     * Toggle User Status (Active / Suspended / Inactive)
     */
    public function update_status() {
        $this->check_permission('users.update');

        $user_id = (int)$this->input->post('user_id');
        $status = trim($this->input->post('status'));
        $tenant_id = $this->require_tenant_id();

        if ($user_id && $status) {
            $this->Tenant_user_model->updateUserStatus($user_id, $tenant_id, $status);
            $this->log_audit('USER_STATUS_UPDATE', $tenant_id, array('user_id' => $user_id, 'status' => $status));
            $this->session->set_flashdata('success', 'User status updated to ' . ucfirst($status) . '.');
        }

        redirect('users');
    }

    /**
     * Role Management & Creation Page
     */
    public function roles() {
        $this->check_permission('roles.view');

        $tenant_id = $this->require_tenant_id();
        $data['roles'] = $this->Rbac_model->getRoles($tenant_id);
        $data['permissions_grouped'] = $this->Rbac_model->getAllPermissionsGrouped();
        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard', $data);
        $this->load->view('roles_list', $data);
        $this->load->view('home/footer');
    }

    /**
     * Create Custom Role
     */
    public function create_role() {
        $this->check_permission('roles.manage');

        $name = trim($this->input->post('name'));
        $description = trim($this->input->post('description'));
        $permission_ids = $this->input->post('permissions') ?: array();
        $tenant_id = $this->require_tenant_id();

        if (!empty($name)) {
            $role_id = $this->Rbac_model->createRole($tenant_id, $name, $description, $permission_ids);
            if ($role_id) {
                $this->log_audit('ROLE_CREATE', $tenant_id, array('role_id' => $role_id, 'name' => $name));
                $this->session->set_flashdata('success', 'Custom role "' . $name . '" created successfully.');
            }
        }
        redirect('users/roles');
    }

    /**
     * Permission Matrix Grid Page
     */
    public function permission_matrix() {
        $this->check_permission('roles.view');

        $tenant_id = $this->require_tenant_id();
        $data['roles'] = $this->Rbac_model->getRoles($tenant_id);
        $data['permissions_grouped'] = $this->Rbac_model->getAllPermissionsGrouped();

        $role_perms = array();
        foreach ($data['roles'] as $role) {
            $role_perms[$role->id] = $this->Rbac_model->getRolePermissionIds($role->id);
        }
        $data['role_permissions'] = $role_perms;
        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard', $data);
        $this->load->view('permission_matrix', $data);
        $this->load->view('home/footer');
    }

    /**
     * Save Permission Matrix Grid
     */
    public function save_permission_matrix() {
        $this->check_permission('roles.manage');

        $matrix = $this->input->post('matrix') ?: array();
        $tenant_id = $this->require_tenant_id();

        foreach ($matrix as $role_id => $pids) {
            $role = $this->Rbac_model->getRoleById($role_id);
            // System roles or custom roles belonging to this tenant
            if ($role && ($role->is_system == 1 || (int)$role->tenant_id === (int)$tenant_id)) {
                if ($role->id == 1) continue; // Owner role retains all system permissions
                $this->Rbac_model->updateRolePermissions($role_id, $pids);
            }
        }

        $this->log_audit('PERMISSION_MATRIX_UPDATE', $tenant_id, array('updated_roles' => array_keys($matrix)));
        $this->session->set_flashdata('success', 'Permission matrix saved successfully.');
        redirect('users/permission_matrix');
    }

    /**
     * Delete Custom Tenant Role
     */
    public function delete_role($role_id) {
        $this->check_permission('roles.manage');
        $tenant_id = $this->require_tenant_id();

        $role = $this->Rbac_model->getRoleById($role_id);
        if ($role && $role->is_system == 0 && (int)$role->tenant_id === (int)$tenant_id) {
            $this->Rbac_model->deleteRole($role_id, $tenant_id);
            $this->log_audit('ROLE_DELETE', $tenant_id, array('role_id' => $role_id, 'name' => $role->name));
            $this->session->set_flashdata('success', 'Custom role "' . $role->name . '" deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Unable to delete role or permission denied.');
        }
        redirect('users/roles');
    }

    /**
     * Departments & Job Titles Management
     */
    public function departments() {
        $this->check_permission('settings.view');

        $tenant_id = $this->require_tenant_id();
        $data['departments'] = $this->Department_model->getDepartments($tenant_id);
        $data['job_titles'] = $this->Department_model->getJobTitles($tenant_id);
        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard', $data);
        $this->load->view('departments_list', $data);
        $this->load->view('home/footer');
    }

    /**
     * Add Department Action
     */
    public function add_department() {
        $this->check_permission('settings.update');

        $tenant_id = $this->require_tenant_id();
        $name = trim($this->input->post('name'));

        if (!empty($name)) {
            $data = array(
                'name'        => $name,
                'code'        => $this->input->post('code'),
                'description' => $this->input->post('description')
            );
            $this->Department_model->addDepartment($tenant_id, $data);
            $this->session->set_flashdata('success', 'Department added successfully.');
        }

        redirect('users/departments');
    }

    /**
     * Activity & Audit Logs Page
     */
    public function activity_logs() {
        $this->check_permission('users.view');

        $tenant_id = $this->require_tenant_id();
        $data['audit_logs'] = $this->db->table_exists('audit_logs')
            ? $this->db->where('tenant_id', $tenant_id)->order_by('created_at', 'DESC')->limit(100)->get('audit_logs')->result()
            : array();
        $data['login_history'] = $this->db->where('tenant_id', $tenant_id)
                                          ->order_by('login_at', 'DESC')
                                          ->limit(100)
                                          ->get('login_history')
                                          ->result();
        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard', $data);
        $this->load->view('activity_logs', $data);
        $this->load->view('home/footer');
    }
}
