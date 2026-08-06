<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Department_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Fetch tenant departments with total user counts
     */
    public function getDepartments($tenant_id) {
        $this->db->select('d.*, COUNT(tu.id) as user_count, u.username as head_user_name');
        $this->db->from('departments d');
        $this->db->join('tenant_users tu', 'tu.department_id = d.id', 'left');
        $this->db->join('users u', 'u.id = d.head_user_id', 'left');
        $this->db->where('d.tenant_id', (int)$tenant_id);
        $this->db->group_by('d.id');
        $this->db->order_by('d.name', 'ASC');
        $query = $this->db->get();
        return $query ? $query->result() : array();
    }

    /**
     * Get department by ID
     */
    public function getDepartmentById($id, $tenant_id) {
        return $this->db->get_where('departments', array('id' => (int)$id, 'tenant_id' => (int)$tenant_id))->row();
    }

    /**
     * Add new department
     */
    public function addDepartment($tenant_id, $data) {
        $insert_data = array(
            'tenant_id'    => (int)$tenant_id,
            'name'         => trim($data['name']),
            'code'         => strtoupper(trim($data['code'] ?? '')),
            'description'  => trim($data['description'] ?? ''),
            'head_user_id' => !empty($data['head_user_id']) ? (int)$data['head_user_id'] : null,
            'status'       => 1,
            'created_at'   => date('Y-m-d H:i:s')
        );
        $this->db->insert('departments', $insert_data);
        return $this->db->insert_id();
    }

    /**
     * Update department
     */
    public function updateDepartment($id, $tenant_id, $data) {
        $update_data = array(
            'name'         => trim($data['name']),
            'code'         => strtoupper(trim($data['code'] ?? '')),
            'description'  => trim($data['description'] ?? ''),
            'head_user_id' => !empty($data['head_user_id']) ? (int)$data['head_user_id'] : null,
            'status'       => isset($data['status']) ? (int)$data['status'] : 1
        );
        $this->db->where('id', (int)$id);
        $this->db->where('tenant_id', (int)$tenant_id);
        return $this->db->update('departments', $update_data);
    }

    /**
     * Delete department
     */
    public function deleteDepartment($id, $tenant_id) {
        // Unset department from tenant_users first
        $this->db->where('department_id', (int)$id)->update('tenant_users', array('department_id' => null));
        $this->db->where('id', (int)$id)->where('tenant_id', (int)$tenant_id)->delete('departments');
        return true;
    }

    /**
     * Get job titles by department or tenant
     */
    public function getJobTitles($tenant_id, $department_id = null) {
        $this->db->select('jt.*, d.name as department_name');
        $this->db->from('job_titles jt');
        $this->db->join('departments d', 'd.id = jt.department_id', 'left');
        $this->db->where('jt.tenant_id', (int)$tenant_id);
        if (!empty($department_id)) {
            $this->db->where('jt.department_id', (int)$department_id);
        }
        $this->db->order_by('jt.title', 'ASC');
        $query = $this->db->get();
        return $query ? $query->result() : array();
    }

    /**
     * Add job title
     */
    public function addJobTitle($tenant_id, $data) {
        $insert_data = array(
            'tenant_id'     => (int)$tenant_id,
            'department_id' => !empty($data['department_id']) ? (int)$data['department_id'] : null,
            'title'         => trim($data['title']),
            'description'   => trim($data['description'] ?? ''),
            'created_at'    => date('Y-m-d H:i:s')
        );
        $this->db->insert('job_titles', $insert_data);
        return $this->db->insert_id();
    }
}
