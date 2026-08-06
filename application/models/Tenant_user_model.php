<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tenant_user_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('ion_auth');
    }

    /**
     * Get list of users inside a tenant with filters
     */
    public function getTenantUsers($tenant_id, $filters = array()) {
        $this->db->select('tu.*, u.username, u.email, u.active as ion_active, d.name as department_name, jt.title as job_title, r.id as role_id, r.name as role_name, r.slug as role_slug');
        $this->db->from('tenant_users tu');
        $this->db->join('users u', 'u.id = tu.user_id', 'inner');
        $this->db->join('departments d', 'd.id = tu.department_id', 'left');
        $this->db->join('job_titles jt', 'jt.id = tu.job_title_id', 'left');
        $this->db->join('user_roles ur', 'ur.user_id = u.id', 'left');
        $this->db->join('roles r', 'r.id = ur.role_id', 'left');
        $this->db->where('tu.tenant_id', (int)$tenant_id);

        if (!empty($filters['department_id'])) {
            $this->db->where('tu.department_id', (int)$filters['department_id']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('tu.status', $filters['status']);
        }
        if (!empty($filters['search'])) {
            $s = trim($filters['search']);
            $this->db->group_start();
            $this->db->like('u.username', $s);
            $this->db->or_like('u.email', $s);
            $this->db->or_like('tu.employee_number', $s);
            $this->db->or_like('tu.phone', $s);
            $this->db->group_end();
        }

        $this->db->order_by('tu.created_at', 'DESC');
        $query = $this->db->get();
        return $query ? $query->result() : array();
    }

    /**
     * Get single tenant user by user_id
     */
    public function getTenantUserByUserId($user_id, $tenant_id) {
        $this->db->select('tu.*, u.username, u.email, u.active as ion_active, d.name as department_name, jt.title as job_title, r.id as role_id, r.name as role_name, r.slug as role_slug');
        $this->db->from('tenant_users tu');
        $this->db->join('users u', 'u.id = tu.user_id', 'inner');
        $this->db->join('departments d', 'd.id = tu.department_id', 'left');
        $this->db->join('job_titles jt', 'jt.id = tu.job_title_id', 'left');
        $this->db->join('user_roles ur', 'ur.user_id = u.id', 'left');
        $this->db->join('roles r', 'r.id = ur.role_id', 'left');
        $this->db->where('tu.user_id', (int)$user_id);
        $this->db->where('tu.tenant_id', (int)$tenant_id);
        return $this->db->get()->row();
    }

    /**
     * Create user invitation
     */
    public function createInvitation($tenant_id, $email, $role_id, $department_id = null, $invited_by = null) {
        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', strtotime('+48 hours'));

        $data = array(
            'tenant_id'     => (int)$tenant_id,
            'email'         => strtolower(trim($email)),
            'role_id'       => (int)$role_id,
            'department_id' => !empty($department_id) ? (int)$department_id : null,
            'token'         => $token,
            'invited_by'    => (int)$invited_by,
            'status'        => 'pending',
            'expires_at'    => $expires_at,
            'created_at'    => date('Y-m-d H:i:s')
        );
        $this->db->insert('user_invitations', $data);
        return $token;
    }

    /**
     * Get active invitation by token
     */
    public function getInvitationByToken($token) {
        $this->db->where('token', $token);
        $this->db->where('status', 'pending');
        $this->db->where('expires_at >', date('Y-m-d H:i:s'));
        return $this->db->get('user_invitations')->row();
    }

    /**
     * Complete invitation acceptance
     */
    public function acceptInvitation($token, $password, $name, $phone = '') {
        $invitation = $this->getInvitationByToken($token);
        if (!$invitation) {
            return false;
        }

        // Register user via Ion Auth
        $email = $invitation->email;
        $username = !empty($name) ? $name : explode('@', $email)[0];
        $additional_data = array(
            'tenant_id' => $invitation->tenant_id,
            'phone'     => $phone
        );

        $user_id = $this->ion_auth->register($username, $password, $email, $additional_data);
        if (!$user_id) {
            return false;
        }

        // Create tenant_users entry
        $tu_data = array(
            'tenant_id'     => $invitation->tenant_id,
            'user_id'       => $user_id,
            'department_id' => $invitation->department_id,
            'status'        => 'active',
            'phone'         => $phone,
            'created_at'    => date('Y-m-d H:i:s')
        );
        $this->db->insert('tenant_users', $tu_data);

        // Assign Role
        $this->load->model('Rbac_model');
        $this->Rbac_model->assignRole($user_id, $invitation->role_id);

        // Update invitation status
        $this->db->where('id', $invitation->id)->update('user_invitations', array('status' => 'accepted'));

        return $user_id;
    }

    /**
     * Log login history
     */
    public function logLoginHistory($tenant_id, $user_id, $ip_address, $user_agent, $status = 'success') {
        $data = array(
            'tenant_id'  => (int)$tenant_id,
            'user_id'    => (int)$user_id,
            'ip_address' => $ip_address,
            'user_agent' => substr($user_agent, 0, 250),
            'status'     => $status,
            'login_at'   => date('Y-m-d H:i:s')
        );
        $this->db->insert('login_history', $data);

        if ($status === 'success') {
            $this->db->where('tenant_id', (int)$tenant_id)
                     ->where('user_id', (int)$user_id)
                     ->update('tenant_users', array(
                         'last_login_at' => date('Y-m-d H:i:s'),
                         'last_login_ip' => $ip_address
                     ));
        }
    }

    /**
     * Update tenant user status
     */
    public function updateUserStatus($user_id, $tenant_id, $status) {
        $valid_statuses = array('active', 'inactive', 'suspended', 'pending');
        if (!in_array($status, $valid_statuses)) {
            return false;
        }

        $this->db->where('user_id', (int)$user_id)
                 ->where('tenant_id', (int)$tenant_id)
                 ->update('tenant_users', array('status' => $status));

        // Update ion_auth users table active flag
        $ion_active = ($status === 'active') ? 1 : 0;
        $this->db->where('id', (int)$user_id)->update('users', array('active' => $ion_active));
        return true;
    }
}
