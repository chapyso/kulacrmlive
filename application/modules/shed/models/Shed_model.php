<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Shed_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function insertShed($table, $data)
    {
        $data = $this->prepare_tenant_data($table, $data);
        $this->db->insert($table, $data);
        $returnValue = $this->db->insert_id();
        return $returnValue;
    }

    function getShed()
    {
        $this->scope_tenant('shed');
        $this->db->where('sh_status', 1);
        $this->db->order_by("sh_no", "ASC");
        $query = $this->db->get('shed');
        return $query->result();
    }

    function getShedById($id)
    {
        $this->scope_tenant('shed');
        $this->db->where('sh_status', 1);
        $this->db->where('sh_id', $id);
        $query = $this->db->get('shed');
        return $query->row();
    }

    // Ajax duplicate number check
    function duplicateShedNumberCheckAjax($shed_serial_number)
    {
        $this->scope_tenant('shed');
        $this->db->where('sh_status', 1);
        $this->db->where('sh_no', $shed_serial_number);
        $query = $this->db->get('shed');
        return $query->row();
    }

    /* ============ Insert Function ============ */
    function insertData($table, $data)
    {
        $data = $this->prepare_tenant_data($table, $data);
        $this->db->insert($table, $data);
        $returnValue = $this->db->insert_id();
        return $returnValue;
    }

    // Update Data
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

    function updateShed($id, $data)
    {
        $this->scope_tenant('shed');
        $this->db->where('sh_id', $id);
        $this->db->update('shed', $data);
    }

    function getShedWiseLivestock($sh_id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_sh_id', $sh_id);
        $this->db->where('lshs_status', 1);
        $this->db->select('SUM(lshs_assign_total_quantity) AS total_sum');
        $query = $this->db->get('live_assigned_shed_summary');
        return $query->row()->total_sum;
    }

    function getAssignedBatchesByShedId($sh_id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_sh_id', $sh_id);
        $this->db->where('lshs_status', 1);
        $this->db->order_by('lshs_batch_id', 'DESC');
        $query = $this->db->get('live_assigned_shed_summary');
        return $query->result();
    }

    // Total Shed Count
    function getTotalShedCount()
    {
        $this->scope_tenant('shed');
        $this->db->where('sh_status', 1);
        $query = $this->db->count_all_results('shed');
        return $query;
    }

    // Shed Wise Assigned Batch Quantity
    function getCountShedWiseBatchAssignedQuantity($shed_id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_sh_id', $shed_id);
        $this->db->where('lshs_status', 1);
        $query = $this->db->count_all_results('live_assigned_shed_summary');
        return $query;
    }

    function getShedAssignedDateByLivestockAndTypeAndSummaryId($pur_summary, $ls_id, $lst_id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_purv_purs_id', $pur_summary);
        $this->db->where('lsh_purv_ls_id', $ls_id);
        $this->db->where('lsh_purv_lst_id', $lst_id);
        $this->db->where('lsh_status', 1);
        $query = $this->db->get('live_assigned_shed');
        return $query->row();
    }

    function getBatch()
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_status', 1);
        $this->db->order_by("lsh_batch_id", "DESC");
        $query = $this->db->get('live_assigned_shed');
        return $query->result();
    }

    function getAllBatch()
    {
        $tenant_id = $this->get_tenant_id();
        $query = $this->db->query("SELECT * FROM live_assigned_shed WHERE (lsh_status = 1 OR lsh_status = 2) AND tenant_id = ?", array($tenant_id));
        return $query->result();
    }

    /* ================================= Livestock Death =========================== */
    function getDeathLivestockSumByLivestockAssignId($shed_id, $lshs_id, $ls_id, $lst_id)
    {
        $this->scope_tenant('livestock_death_quantity');
        $this->db->where('ld_status', 1);
        $this->db->where('ld_sh_id', $shed_id);
        $this->db->where('ld_lshs_id', $lshs_id);
        $this->db->where('ld_purv_ls_id', $ls_id);
        $this->db->where('ld_purv_lst_id', $lst_id);
        $this->db->select('SUM(ld_death_quantity) AS total_sum');
        $query = $this->db->get('livestock_death_quantity');
        return $query->row()->total_sum;
    }

    function getDeathLivestockSumByShedAndBatch($shed_id, $batch_id)
    {
        $this->scope_tenant('livestock_death_quantity');
        $this->db->where('ld_status', 1);
        $this->db->where('ld_sh_id', $shed_id);
        $this->db->where('ld_batch_id', $batch_id);
        $this->db->select('SUM(ld_death_quantity) AS total_sum');
        $query = $this->db->get('livestock_death_quantity');
        return $query->row()->total_sum;
    }

    function getShedWiseDeathLivestock($shed_id)
    {
        $this->scope_tenant('livestock_death_quantity');
        $this->db->where('ld_status', 1);
        $this->db->where('ld_sh_id', $shed_id);
        $this->db->select('SUM(ld_death_quantity) AS total_sum');
        $query = $this->db->get('livestock_death_quantity');
        return $query->row()->total_sum;
    }

    function getTotalDeathLivestock()
    {
        $this->scope_tenant('livestock_death_quantity');
        $this->db->where('ld_status', 1);
        $this->db->select('SUM(ld_death_quantity) AS total_sum');
        $query = $this->db->get('livestock_death_quantity');
        return $query->row()->total_sum;
    }

    function getDeathLivestockByLivestockPurchaseShedId($shed_id, $lshs_id, $ls_id, $lst_id)
    {
        $this->scope_tenant('livestock_death_quantity');
        $this->db->where('ld_status', 1);
        $this->db->where('ld_sh_id', $shed_id);
        $this->db->where('ld_lshs_id', $lshs_id);
        $this->db->where('ld_purv_ls_id', $ls_id);
        $this->db->where('ld_purv_lst_id', $lst_id);
        $query = $this->db->get('livestock_death_quantity');
        return $query->result();
    }

    /* ================================= Transfer livestock one shed to another shed =========================== */
    function getTotalTransferLivestockSum()
    {
        $this->scope_tenant('livestock_transfer_quantity');
        $this->db->where('ltr_status', 1);
        $this->db->select('SUM(ltr_transfer_quantity) AS total_sum');
        $query = $this->db->get('livestock_transfer_quantity');
        return $query->row()->total_sum;
    }

    function getTransferLivestockByLivestockPurchaseShedId($shed_id, $ls_id, $lst_id)
    {
        $this->scope_tenant('livestock_transfer_quantity');
        $this->db->where('ltr_status', 1);
        $this->db->where('ltr_sh_id', $shed_id);
        $this->db->where('ltr_purv_ls_id', $ls_id);
        $this->db->where('ltr_purv_lst_id', $lst_id);
        $query = $this->db->get('livestock_transfer_quantity');
        return $query->result();
    }
}
