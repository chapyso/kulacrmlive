<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Livestock_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function insertLivestock($data)
    {
        $data = $this->prepare_tenant_data('livestock', $data);
        $this->db->insert('livestock', $data);
    }

    function getLivestock()
    {
        $this->scope_tenant('livestock');
        $this->db->where('ls_status', 1);
        $this->db->order_by("ls_id", "desc");
        $query = $this->db->get('livestock');
        return $query->result();
    }

    function getLivestockById($id)
    {
        $this->scope_tenant('livestock');
        $this->db->where('ls_status', 1);
        $this->db->where('ls_id', $id);
        $query = $this->db->get('livestock');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function updateLivestock($id, $data)
    {
        $this->scope_tenant('livestock');
        $this->db->where('ls_id', $id);
        $this->db->update('livestock', $data);
    }

    function deleteLivestock($id)
    {
        $this->scope_tenant('livestock');
        $this->db->where('ls_id', $id);
        $this->db->delete('livestock');
    }

    public function get_count($ls_lst_type_id)
    {
        $this->scope_tenant('livestock');
        $this->db->where('ls_status', 1);
        $this->db->where('ls_lst_type_id', $ls_lst_type_id);
        $query = $this->db->count_all_results('livestock');
        return $query;
    }

    function getLivestockTypeByLivestockId($id)
    {
        $this->scope_tenant('livestock_type');
        $this->db->where('lst_status', 1);
        $this->db->where('lst_ls_id', $id);
        $query = $this->db->get('livestock_type');
        return $query->result();
    }

    function countLivestockVariantByLivestockId($id)
    {
        $this->scope_tenant('livestock_type');
        $this->db->where('lst_status', 1);
        $this->db->where('lst_ls_id', $id);
        $query = $this->db->count_all_results('livestock_type');
        return $query;
    }

    function getLivestockPurchaseQuantityByLivestockId($ls_id, $column)
    {
        $this->scope_tenant('livestock_purchase_value');
        $this->db->JOIN('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->where('purv_ls_id', $ls_id);
        $this->db->select("SUM($column) AS total_sum");
        $query = $this->db->get('livestock_purchase_value');
        return $query->row()->total_sum;
    }

    function getLivestockPurchaseQuantityByVariantId($lst_id)
    {
        $this->scope_tenant('livestock_purchase_value');
        $this->db->JOIN('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->where('purv_lst_id', $lst_id);
        $this->db->select('SUM(purv_quantity) AS total_sum');
        $query = $this->db->get('livestock_purchase_value');
        return $query->row()->total_sum;
    }

    /* ======================== Type ======================== */
    function insertLivestockType($data)
    {
        $data = $this->prepare_tenant_data('livestock_type', $data);
        $this->db->insert('livestock_type', $data);
    }

    function getLivestockType()
    {
        $this->scope_tenant('livestock_type');
        $this->db->where('lst_status', 1);
        $this->db->order_by("lst_id", "desc");
        $query = $this->db->get('livestock_type');
        return $query->result();
    }

    function deleteLivestockType($id)
    {
        $this->scope_tenant('livestock_type');
        $this->db->where('lst_id', $id);
        $this->db->delete('livestock_type');
    }

    function getLivestockTypeById($id)
    {
        $this->scope_tenant('livestock_type');
        $this->db->where('lst_id', $id);
        $query = $this->db->get('livestock_type');
        return $query->row();
    }

    function updateLivestockType($id, $data)
    {
        $this->scope_tenant('livestock_type');
        $this->db->where('lst_id', $id);
        $this->db->update('livestock_type', $data);
    }

    function getLivestockTypeIncreasingByTypeId($lst_id)
    {
        if ($this->db->table_exists('livestock_type_increasing')) {
            $this->scope_tenant('livestock_type_increasing');
            $this->db->where('lstin_lst_id', $lst_id);
            $query = $this->db->get('livestock_type_increasing');
            return $query->row();
        }
        return null;
    }
}
