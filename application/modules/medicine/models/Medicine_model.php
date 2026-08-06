<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Medicine_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function insertMedicine($data) {
        $data = $this->prepare_tenant_data('medicine', $data);
        $this->db->insert('medicine', $data);
    }

    function getMedicine() {
        $this->scope_tenant('medicine');
        $this->db->order_by("id", "desc");
        $query = $this->db->get('medicine');
        return $query->result();
    }

    function getMedicineById($id) {
        $this->scope_tenant('medicine');
        $this->db->where('id', $id);
        $query = $this->db->get('medicine');
        return $query->row();
    }

    function updateMedicine($id, $data) {
        $this->scope_tenant('medicine');
        $this->db->where('id', $id);
        $this->db->update('medicine', $data);
    }

    function deleteMedicine($id) {
        $this->scope_tenant('medicine');
        $this->db->where('id', $id);
        $this->db->delete('medicine');
    }

}
