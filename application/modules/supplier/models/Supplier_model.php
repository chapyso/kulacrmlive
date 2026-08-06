<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Supplier_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* ========== Insert Data ========== */
    public function insertData($table, $data)
    {
        $data = $this->prepare_tenant_data($table, $data);
        $this->db->insert($table, $data);
        $returnValue = $this->db->insert_id();
        return $returnValue;
    }

    /* ========== Update Data ========== */
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

    /* ========== get Data ========== */
    public function getData($table, $index, $data)
    {
        $this->scope_tenant($table);
        $this->db->where($index, $data);
        $query = $this->db->get($table);
        return $query;
    }
}
