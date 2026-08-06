<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rbac_model extends MY_Model {
    private static $permissions_cache = array();
    private static $roles_cache = array();

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all permission names assigned to a user (with caching)
     */
    public function getUserPermissions($user_id) {
        if (empty($user_id)) {
            return array();
        }

        if (isset(self::$permissions_cache[$user_id])) {
            return self::$permissions_cache[$user_id];
        }

        // Check session cache if available
        $CI =& get_instance();
        if (isset($CI->session) && $CI->session->userdata('user_id') == $user_id && $CI->session->userdata('user_permissions')) {
            $cached = $CI->session->userdata('user_permissions');
            if (is_array($cached)) {
                self::$permissions_cache[$user_id] = $cached;
                return $cached;
            }
        }

        // Check if user has Owner system role (role_id = 1 or slug = 'owner')
        if ($this->hasRole($user_id, 'owner') || $this->hasRole($user_id, 'admin')) {
            $all = $this->getAllPermissionNames();
            self::$permissions_cache[$user_id] = $all;
            if (isset($CI->session) && $CI->session->userdata('user_id') == $user_id) {
                $CI->session->set_userdata('user_permissions', $all);
            }
            return $all;
        }

        $this->db->select('p.name');
        $this->db->from('permissions p');
        $this->db->join('role_permissions rp', 'rp.permission_id = p.id');
        $this->db->join('user_roles ur', 'ur.role_id = rp.role_id');
        $this->db->where('ur.user_id', (int)$user_id);
        $query = $this->db->get();

        $permissions = array();
        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $permissions[] = $row->name;
            }
        }
        $permissions = array_values(array_unique($permissions));

        self::$permissions_cache[$user_id] = $permissions;
        if (isset($CI->session) && $CI->session->userdata('user_id') == $user_id) {
            $CI->session->set_userdata('user_permissions', $permissions);
        }

        return $permissions;
    }

    /**
     * Get all master permission names
     */
    public function getAllPermissionNames() {
        $query = $this->db->select('name')->get('permissions');
        $names = array();
        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $names[] = $row->name;
            }
        }
        return $names;
    }

    /**
     * Check if user possesses specific permission
     */
    public function hasPermission($user_id, $permission_name) {
        if (empty($user_id) || empty($permission_name)) {
            return false;
        }

        $CI =& get_instance();
        if (isset($CI->is_superadmin) && $CI->is_superadmin) {
            return true;
        }

        $user_perms = $this->getUserPermissions($user_id);
        return in_array($permission_name, $user_perms, true);
    }

    /**
     * Get user assigned roles
     */
    public function getUserRoles($user_id) {
        if (empty($user_id)) {
            return array();
        }

        if (isset(self::$roles_cache[$user_id])) {
            return self::$roles_cache[$user_id];
        }

        $this->db->select('r.*');
        $this->db->from('roles r');
        $this->db->join('user_roles ur', 'ur.role_id = r.id');
        $this->db->where('ur.user_id', (int)$user_id);
        $query = $this->db->get();

        $roles = array();
        if ($query && $query->num_rows() > 0) {
            $roles = $query->result();
        }
        self::$roles_cache[$user_id] = $roles;
        return $roles;
    }

    /**
     * Check if user has specific role slug
     */
    public function hasRole($user_id, $role_slug) {
        $roles = $this->getUserRoles($user_id);
        if (empty($roles)) {
            return false;
        }
        foreach ($roles as $role) {
            if (strtolower($role->slug) === strtolower($role_slug) || strtolower($role->name) === strtolower($role_slug)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Fetch all available permissions grouped by category
     */
    public function getAllPermissionsGrouped() {
        $this->db->order_by('category, name');
        $query = $this->db->get('permissions');
        $grouped = array();
        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $grouped[$row->category][] = $row;
            }
        }
        return $grouped;
    }

    /**
     * Get system roles and tenant-specific custom roles
     */
    public function getRoles($tenant_id = null) {
        $this->db->group_start();
        $this->db->where('is_system', 1);
        if (!empty($tenant_id)) {
            $this->db->or_where('tenant_id', (int)$tenant_id);
        }
        $this->db->group_end();
        $this->db->order_by('is_system DESC, name ASC');
        $query = $this->db->get('roles');
        return $query ? $query->result() : array();
    }

    /**
     * Get role by ID
     */
    public function getRoleById($role_id) {
        return $this->db->get_where('roles', array('id' => (int)$role_id))->row();
    }

    /**
     * Get permission IDs assigned to a role
     */
    public function getRolePermissionIds($role_id) {
        $this->db->select('permission_id');
        $this->db->where('role_id', (int)$role_id);
        $query = $this->db->get('role_permissions');
        $ids = array();
        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $ids[] = (int)$row->permission_id;
            }
        }
        return $ids;
    }

    /**
     * Update permissions for a role
     */
    public function updateRolePermissions($role_id, array $permission_ids) {
        $this->db->where('role_id', (int)$role_id)->delete('role_permissions');
        if (!empty($permission_ids)) {
            $data = array();
            foreach ($permission_ids as $pid) {
                $data[] = array(
                    'role_id' => (int)$role_id,
                    'permission_id' => (int)$pid
                );
            }
            $this->db->insert_batch('role_permissions', $data);
        }
        $this->clearCache();
        return true;
    }

    /**
     * Create custom tenant role
     */
    public function createRole($tenant_id, $name, $description = '', array $permission_ids = array()) {
        $slug = url_title(strtolower($name), '_', true);
        $data = array(
            'tenant_id' => (int)$tenant_id,
            'name' => trim($name),
            'slug' => $slug,
            'description' => trim($description),
            'is_system' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('roles', $data);
        $role_id = $this->db->insert_id();

        if ($role_id && !empty($permission_ids)) {
            $this->updateRolePermissions($role_id, $permission_ids);
        }
        return $role_id;
    }

    /**
     * Delete custom tenant role
     */
    public function deleteRole($role_id, $tenant_id) {
        $role = $this->getRoleById($role_id);
        if (!$role || $role->is_system == 1 || $role->tenant_id != $tenant_id) {
            return false;
        }
        $this->db->where('role_id', (int)$role_id)->delete('role_permissions');
        $this->db->where('role_id', (int)$role_id)->delete('user_roles');
        $this->db->where('id', (int)$role_id)->delete('roles');
        $this->clearCache();
        return true;
    }

    /**
     * Assign role to user
     */
    public function assignRole($user_id, $role_id) {
        $this->db->where('user_id', (int)$user_id);
        $this->db->delete('user_roles');
        $res = $this->db->insert('user_roles', array(
            'user_id' => (int)$user_id,
            'role_id' => (int)$role_id
        ));
        $this->clearCache();
        return $res;
    }

    /**
     * Clear memory and session caches
     */
    public function clearCache() {
        self::$permissions_cache = array();
        self::$roles_cache = array();
        $CI =& get_instance();
        if (isset($CI->session)) {
            $CI->session->unset_userdata('user_permissions');
            $CI->session->unset_userdata('user_roles');
        }
    }
}

