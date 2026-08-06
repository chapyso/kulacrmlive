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
            redirect('auth/login', 'refresh');
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

        $tenant_id = $this->tenant_id ?: 1;
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
        $tenant_id = $this->tenant_id ?: 1;
        $current_user_id = $this->ion_auth->user()->row()->id;

        if (empty($email) || empty($role_id)) {
            $this->session->set_flashdata('error', 'Email and Role are required.');
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
        $tenant_id = $this->tenant_id ?: 1;

        if (empty($username) || empty($email) || empty($password) || empty($role_id)) {
            $this->session->set_flashdata('error', 'Username, Email, Password, and Role are required.');
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
     * Toggle User Status (Active / Suspended / Inactive)
     */
    public function update_status() {
        $this->check_permission('users.update');

        $user_id = (int)$this->input->post('user_id');
        $status = trim($this->input->post('status'));
        $tenant_id = $this->tenant_id ?: 1;

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

        $tenant_id = $this->tenant_id ?: 1;
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
        $tenant_id = $this->tenant_id ?: 1;

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

        $tenant_id = $this->tenant_id ?: 1;
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
        $tenant_id = $this->tenant_id ?: 1;

        foreach ($matrix as $role_id => $pids) {
            $role = $this->Rbac_model->getRoleById($role_id);
            if ($role && ($role->is_system == 0 || $role->id != 1)) { // Do not restrict Owner role
                $this->Rbac_model->updateRolePermissions($role_id, $pids);
            }
        }

        $this->log_audit('PERMISSION_MATRIX_UPDATE', $tenant_id, array('updated_roles' => array_keys($matrix)));
        $this->session->set_flashdata('success', 'Permission matrix saved successfully.');
        redirect('users/permission_matrix');
    }

    /**
     * Departments & Job Titles Management
     */
    public function departments() {
        $this->check_permission('settings.view');

        $tenant_id = $this->tenant_id ?: 1;
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

        $tenant_id = $this->tenant_id ?: 1;
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

        $tenant_id = $this->tenant_id ?: 1;
        $data['audit_logs'] = $this->db->where('tenant_id', $tenant_id)
                                      ->order_by('created_at', 'DESC')
                                      ->limit(100)
                                      ->get('audit_logs')
                                      ->result();
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
