<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Kula_ai_model
 * Manages KulaAI metadata, audit logging, and tenant-scoped AI interaction history.
 * Extends MY_Model to maintain strict multi-tenant scoping and context isolation.
 */
class Kula_ai_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Audit log an AI request/interaction
     */
    public function log_interaction($prompt_summary, $tools_used = array(), $action_type = 'query', $status = 'success', $approval_status = 'not_applicable') {
        $CI =& get_instance();
        $tenant_id = $this->get_tenant_id();
        $user_id = ($CI->ion_auth && $CI->ion_auth->logged_in()) ? $CI->ion_auth->get_user_id() : null;

        $data = array(
            'tenant_id'        => $tenant_id,
            'user_id'          => $user_id,
            'prompt_summary'   => mb_substr($prompt_summary, 0, 1000),
            'tools_used'       => is_array($tools_used) ? json_encode($tools_used) : $tools_used,
            'action_type'      => $action_type,
            'execution_status' => $status,
            'approval_status'  => $approval_status,
            'ip_address'       => $CI->input->ip_address(),
            'created_at'       => date('Y-m-d H:i:s')
        );

        if ($this->db->table_exists('ai_audit_logs')) {
            $this->db->insert('ai_audit_logs', $data);
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Retrieve audit log entries for current tenant
     */
    public function get_audit_logs($limit = 50) {
        if (!$this->db->table_exists('ai_audit_logs')) {
            return array();
        }
        $this->scope_tenant('ai_audit_logs');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('ai_audit_logs')->result();
    }

    /**
     * Retrieve recent prompt history for current user & tenant (ChatGPT style)
     */
    public function get_chat_history($limit = 30) {
        if (!$this->db->table_exists('ai_audit_logs')) {
            return array();
        }

        $tenant_id = $this->get_tenant_id();
        $CI =& get_instance();
        $user_id = ($CI->ion_auth && $CI->ion_auth->logged_in()) ? $CI->ion_auth->get_user_id() : null;

        $this->db->select('id, prompt_summary as prompt, created_at, tools_used, action_type');
        $this->db->from('ai_audit_logs');
        if ($this->db->field_exists('tenant_id', 'ai_audit_logs')) {
            $this->db->where('tenant_id', $tenant_id);
        }
        if ($user_id && $this->db->field_exists('user_id', 'ai_audit_logs')) {
            $this->db->where('user_id', $user_id);
        }
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result_array();
    }
}
