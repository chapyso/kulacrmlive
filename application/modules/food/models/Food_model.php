<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Food_model extends MY_Model
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

    function getFood()
    {
        $this->scope_tenant('food_summary');
        $this->db->where('fds_status', 1);
        $this->db->order_by("fds_id", "desc");
        $query = $this->db->get('food_summary');
        return $query->result();
    }

    function getFoodSummaryById($id)
    {
        $this->scope_tenant('food_summary');
        $this->db->where('fds_id', $id);
        $query = $this->db->get('food_summary');
        return $query->row();
    }

    function getFoodValueById($id)
    {
        $this->scope_tenant('food_value');
        $this->db->where('fdv_id', $id);
        $query = $this->db->get('food_value');
        return $query->row();
    }

    function getAssignedShedByFoodId($id)
    {
        $this->scope_tenant('food_value');
        $this->db->where('fdv_status', 1);
        $this->db->where('fdv_fds_id', $id);
        $query = $this->db->get('food_value');
        return $query->result();
    }

    function getFoodAssignedShedCount($id)
    {
        $this->scope_tenant('food_value');
        $this->db->where('fdv_status', 1);
        $this->db->where('fdv_fds_id', $id);
        $this->db->select("COUNT(fdv_id) AS total");
        $query = $this->db->count_all_results('food_value');
        return $query;
    }

    function getAssignedFoodBatchByAjax($fd_summary_id, $shed_id, $batch_id)
    {
        $this->scope_tenant('food_value');
        $this->db->where('fdv_status', 1);
        $this->db->where('fdv_fds_id', $fd_summary_id);
        $this->db->where('fdv_assigned_shed_id', $shed_id);
        $this->db->where('fdv_assigned_batch_id', $batch_id);
        $query = $this->db->get('food_value');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    /* ============================================== Batch Cascading Dropdown ==================================================== */
    function getBatchByShedCascadingDropdown($shed_id, $batch_id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_sh_id', $shed_id);
        $this->db->where('lshs_status', 1);
        $this->db->order_by('lshs_batch_id', "ASC");
        $batches = $this->db->get('live_assigned_shed_summary');
        $result = '<option value="">' . lang('please_select_batch') . '</option>';
        if ($batches) {
            foreach ($batches->result() as  $value) {
                if ($value->lshs_batch_id == $batch_id) {
                    $isSelected = "selected";
                } else {
                    $isSelected = "";
                }
                $result .= '<option ' . "$isSelected" . ' value="' . $value->lshs_batch_id . '">' . $value->lshs_batch_id . ': ' . $value->lshs_batch_title . '</option>';
            }
        }
        return $result;
    }

    function getActiveBatchByShedCascadingDropdown($shed_id, $batch_id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_sh_id', $shed_id);
        $this->db->where('lshs_status', 1);
        $this->db->where('lshs_active_status', 0);
        $this->db->order_by('lshs_batch_id', "ASC");
        $batches = $this->db->get('live_assigned_shed_summary');
        $result = '<option value="">' . lang('please_select_batch') . '</option>';
        if ($batches) {
            foreach ($batches->result() as  $value) {
                if ($value->lshs_batch_id == $batch_id) {
                    $isSelected = "selected";
                } else {
                    $isSelected = "";
                }
                $result .= '<option ' . "$isSelected" . ' value="' . $value->lshs_batch_id . '">' . $value->lshs_batch_id . ':' . $value->lshs_batch_title . '</option>';
            }
        }
        return $result;
    }

    /* ======================= Food Purchase ======================= */
    function getFoodPurchase()
    {
        $this->scope_tenant('food_purchase_summary');
        $this->db->where('fdps_status', 1);
        $this->db->order_by("fdps_id", "desc");
        $query = $this->db->get('food_purchase_summary');
        return $query->result();
    }

    function getFoodPurchaseSummaryById($id)
    {
        $this->scope_tenant('food_purchase_summary');
        $this->db->where('fdps_id', $id);
        $this->db->where('fdps_status', 1);
        $query = $this->db->get('food_purchase_summary');
        return $query->row();
    }

    function getFoodPurchaseBySupplierId($supplier_id)
    {
        $this->scope_tenant('food_purchase_summary');
        $this->db->where('fdps_status', 1);
        $this->db->where('fdps_s_id', $supplier_id);
        $query = $this->db->get('food_purchase_summary');
        return $query->result();
    }

    function getFoodPurchaseValueBySummaryId($id)
    {
        $this->scope_tenant('food_purchase_value');
        $this->db->where('fdpv_status', 1);
        $this->db->where('fdpv_fdps_id', $id);
        $query = $this->db->get('food_purchase_value');
        return $query->result();
    }

    function getFoodPurchaseWeightByPurchaseId($id, $column)
    {
        $this->scope_tenant('food_purchase_value');
        $this->db->JOIN('food_purchase_summary', 'food_purchase_summary.fdps_id = food_purchase_value.fdpv_fdps_id');
        $this->db->where('fdps_status', 1);
        $this->db->where('fdpv_status', 1);
        $this->db->where('fdpv_fdps_id', $id);
        $this->db->select("SUM($column) AS total");
        $row = $this->db->get('food_purchase_value')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getTotalFoodPurchaseWeight($column)
    {
        $this->scope_tenant('food_purchase_value');
        $this->db->JOIN('food_purchase_summary', 'food_purchase_summary.fdps_id = food_purchase_value.fdpv_fdps_id');
        $this->db->where('fdps_status', 1);
        $this->db->where('fdpv_status', 1);
        $this->db->select("SUM($column) AS total");
        $row = $this->db->get('food_purchase_value')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getSumFoodSupplierWiseTotalPurchaseAmount($supplierId)
    {
        $this->scope_tenant('food_purchase_summary');
        $this->db->select('SUM(fdps_grand_total) AS total');
        $this->db->where('fdps_s_id', $supplierId);
        $this->db->where('fdps_status', 1);
        $row = $this->db->get('food_purchase_summary')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    /* ============================= Food Stock ============================= */
    function getFoodPurchaseWeightByFoodId($fd_id, $column)
    {
        $this->scope_tenant('food_purchase_value');
        $this->db->JOIN('food_purchase_summary', 'food_purchase_summary.fdps_id = food_purchase_value.fdpv_fdps_id');
        $this->db->where('fdps_status', 1);
        $this->db->where('fdpv_status', 1);
        $this->db->where('fdpv_fd_id', $fd_id);
        $this->db->select("SUM($column) AS total");
        $row = $this->db->get('food_purchase_value')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getFoodAssignedBatchBySummaryId($id)
    {
        $this->scope_tenant('food_value');
        $this->db->where('fdv_status', 1);
        $this->db->where('fdv_fds_id', $id);
        $query = $this->db->get('food_value');
        return $query->result();
    }

    function getFoodDistributedWeightByFoodId($fd_id, $column)
    {
        $this->scope_tenant('food_distributed_value');
        $this->db->JOIN('food_distributed_summary', 'food_distributed_summary.fdds_id = food_distributed_value.fddv_fdds_id');
        $this->db->where('fdds_status', 1);
        $this->db->where('fddv_status', 1);
        $this->db->where('fdds_fd_id', $fd_id);
        $this->db->select("SUM($column) AS total");
        $row = $this->db->get('food_distributed_value')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getFoodDistributedValueByFoodId($fd_id)
    {
        $this->scope_tenant('food_distributed_value');
        $this->db->JOIN('food_distributed_summary', 'food_distributed_summary.fdds_id = food_distributed_value.fddv_fdds_id');
        $this->db->order_by('fddv_fdds_id', 'desc');
        $this->db->where('fdds_status', 1);
        $this->db->where('fddv_status', 1);
        $this->db->where('fdds_fd_id', $fd_id);
        $query = $this->db->get('food_distributed_value');
        return $query->result();
    }

    function getFoodDistributedSummaryByFoodId($fd_id)
    {
        $this->scope_tenant('food_distributed_summary');
        $this->db->where('fdds_status', 1);
        $this->db->where('fdds_fd_id', $fd_id);
        $query = $this->db->get('food_distributed_summary');
        return $query->result();
    }

    function getFoodDistributedValuesByFoodDistributedSummaryId($fd_id)
    {
        $this->scope_tenant('food_distributed_value');
        $this->db->where('fddv_status', 1);
        $this->db->where('fddv_fdds_id', $fd_id);
        $query = $this->db->get('food_distributed_value');
        return $query->result();
    }

    function getCountFoodDistributedValueByFoodSummaryId($fdds_id)
    {
        $this->scope_tenant('food_distributed_value');
        $this->db->JOIN('food_distributed_summary', 'food_distributed_summary.fdds_id = food_distributed_value.fddv_fdds_id');
        $this->db->where('fdds_status', 1);
        $this->db->where('fddv_status', 1);
        $this->db->where('fddv_fdds_id', $fdds_id);
        $this->db->select("COUNT(fdv_id) AS total");
        $query = $this->db->count_all_results('food_distributed_value');
        return $query;
    }

    function getTotalFoodDistributedWeight($column)
    {
        $this->scope_tenant('food_distributed_value');
        $this->db->JOIN('food_distributed_summary', 'food_distributed_summary.fdds_id = food_distributed_value.fddv_fdds_id');
        $this->db->where('fdds_status', 1);
        $this->db->where('fddv_status', 1);
        $this->db->select("SUM($column) AS total");
        $row = $this->db->get('food_distributed_value')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    /* ======================= Wasted Food ======================= */
    function getFoodWastedByFoodId($fd_id, $column)
    {
        $this->scope_tenant('food_wasted');
        $this->db->where('fdw_status', 1);
        $this->db->where('fdw_fd_id', $fd_id);
        $this->db->select("SUM($column) AS total");
        $row = $this->db->get('food_wasted')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getFoodWastedValuesByFoodId($fd_id)
    {
        $this->scope_tenant('food_wasted');
        $this->db->where('fdw_status', 1);
        $this->db->where('fdw_fd_id', $fd_id);
        $this->db->order_by("fdw_id", "desc");
        $query = $this->db->get('food_wasted');
        return $query->result();
    }

    function getTotalFoodWasted($column)
    {
        $this->scope_tenant('food_wasted');
        $this->db->where('fdw_status', 1);
        $this->db->select("SUM($column) AS total");
        $row = $this->db->get('food_wasted')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }
}
