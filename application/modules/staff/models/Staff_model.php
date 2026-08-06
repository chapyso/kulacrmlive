<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Staff_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insertData($table, $data)
    {
        $data = $this->prepare_tenant_data($table, $data);
        $this->db->insert($table, $data);
        $returnValue = $this->db->insert_id();
        return $returnValue;
    }

    public function updateData($table, $index, $identifier, $data)
    {
        $this->scope_tenant($table);
        $this->db->where($index, $identifier);
        $this->db->update($table, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getStaff()
    {
        $this->scope_tenant('staff');
        $this->db->where('sf_status', 1);
        $query = $this->db->get('staff');
        return $query;
    }

    function getStaffById($sf_id)
    {
        $this->scope_tenant('staff');
        $this->db->where('sf_status', 1);
        $this->db->where('sf_id', $sf_id);
        $query = $this->db->get('staff');
        return $query->row();
    }
}
