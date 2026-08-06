<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Insert Data
    public function insertData($table, $data)
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

    // Update Data Multiple Condition
    public function updateDataMultipleCondition($table, $condition, $data)
    {
        $this->scope_tenant($table);
        $this->db->where($condition);
        $this->db->update($table, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function insertPurchase($table, $data)
    {
        $data = $this->prepare_tenant_data($table, $data);
        $this->db->insert($table, $data);
        $returnValue = $this->db->insert_id();
        return $returnValue;
    }

    function getPurchaseByDate($date_from, $date_to)
    {
        $this->scope_tenant('livestock_purchase_summary');
        $this->db->select('*');
        $this->db->from('livestock_purchase_summary');
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $query = $this->db->get();
        return $query->result();
    }

    function getLivestockPurchase($from = '', $to = '')
    {
        $this->scope_tenant('livestock_purchase_summary');
        $this->db->order_by('purs_bill_no', 'desc');
        $this->db->where('purs_status', 1);
        if ($from !== '') $this->db->where('purs_date >=', $from);
        if ($to !== '')   $this->db->where('purs_date <=', $to);
        $query = $this->db->get('livestock_purchase_summary');
        return $query->result();
    }

    function getLivestockPurchaseById($id)
    {
        $this->scope_tenant('livestock_purchase_summary');
        $this->db->where('purs_status', 1);
        $this->db->where('purs_id', $id);
        $query = $this->db->get('livestock_purchase_summary');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function updateLivestockPurchase($id, $data)
    {
        $this->scope_tenant('livestock_purchase_summary');
        $this->db->where('purs_id', $id);
        $this->db->update('livestock_purchase_summary', $data);
    }

    // Purchase Last Bill No
    public function getLastPurchaseBillNo()
    {
        $this->scope_tenant('livestock_purchase_summary');
        $this->db->where('purs_status', 1);
        $this->db->select('MAX(purs_bill_no) AS maximum_number');
        $row = $this->db->get('livestock_purchase_summary')->row();
        return ($row && $row->maximum_number !== NULL) ? $row->maximum_number : 0;
    }

    function getPurchasedLivestockByLivestockAndType($ls_id, $lst_id)
    {
        $this->scope_tenant('livestock_purchase_value');
        $this->db->JOIN('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->where('purv_ls_id', $ls_id);
        $this->db->where('purv_lst_id', $lst_id);
        $this->db->select('SUM(purv_quantity) AS total_sum');
        $row = $this->db->get('livestock_purchase_value')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getLivestockPurchaseValueBySummaryById($id)
    {
        $this->scope_tenant('livestock_purchase_value');
        $this->db->where('purv_status', 1);
        $this->db->where('purv_purs_id', $id);
        $query = $this->db->get('livestock_purchase_value');
        return $query->result();
    }

    function getLivestockPurchaseValueById($purchase_value_id)
    {
        $this->scope_tenant('livestock_purchase_value');
        $this->db->where('purv_status', 1);
        $this->db->where('purv_id', $purchase_value_id);
        $query = $this->db->get('livestock_purchase_value');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function getLivestockByPurchaseValueId($id)
    {
        $this->scope_tenant('livestock');
        $this->db->where('ls_status', 1);
        $this->db->where('ls_id', $id);
        $query = $this->db->get('livestock');
        return $query->result();
    }

    // get Livestock Type Cascading Dropdown
    function getLivestockTypeCascadingDropdown($livestock_id, $livestock_type_id)
    {
        $this->scope_tenant('livestock_type');
        $this->db->where('lst_ls_id', $livestock_id);
        $this->db->where('lst_status', 1);
        $this->db->order_by('lst_title', "ASC");
        $livestock_type = $this->db->get('livestock_type');
        $result = '<option value=""> Please Select Variant</option>';
        if ($livestock_type) {
            foreach ($livestock_type->result() as $type) {
                if ($type->lst_id == $livestock_type_id) {
                    $is_selected = "selected";
                } else {
                    $is_selected = "";
                }
                $result .= '<option ' . "$is_selected" . ' value="' . $type->lst_id . '">' . $type->lst_title . '</option>';
            }
        }
        return $result;
    }

    // get Livestock Cascading Dropdown
    function getLivestockByAssignedIdCascadingDropdown($live_id, $live_id1)
    {
        $this->scope_tenant('livestock');
        $this->db->where('ls_id', $live_id);
        $this->db->where('ls_status', 1);
        $livestock = $this->db->get('livestock');
        $result = '';
        if ($livestock) {
            foreach ($livestock->result() as $livestock) {
                if ($livestock->ls_id == $live_id1) {
                    $is_selected = "selected";
                } else {
                    $is_selected = "";
                }
                $result .= '<option ' . "$is_selected" . ' value="' . $livestock->ls_id . '">' . $livestock->ls_name . '</option>';
            }
        }
        return $result;
    }

    // get Livestock Type Cascading Dropdown
    function getLivestockTypeByAssignedIdCascadingDropdown($live_type_id, $live_type_id1)
    {
        $this->scope_tenant('livestock_type');
        $this->db->where('lst_id', $live_type_id);
        $this->db->where('lst_status', 1);
        $livestockType = $this->db->get('livestock_type');
        $result = '';
        if ($livestockType) {
            foreach ($livestockType->result() as $livestockType) {
                if ($livestockType->lst_id == $live_type_id1) {
                    $is_selected = "selected";
                } else {
                    $is_selected = "";
                }
                $result .= '<option ' . "$is_selected" . ' value="' . $livestockType->lst_id . '">' . $livestockType->lst_title . '</option>';
            }
        }
        return $result;
    }

    // Get Existing Batches by shed id Cascading Dropdown
    function getExistingBatchesByShedId($existing_shed_id, $existing_ls_id, $existing_lst_id)
    {
        $tenant_id = $this->get_tenant_id();
        $this->scope_tenant('live_assigned_shed');
        $this->db->select('lsh_batch_id');
        $this->db->where('lsh_sh_id', $existing_shed_id);
        $this->db->where('lsh_purv_ls_id', $existing_ls_id);
        $this->db->where('lsh_purv_lst_id', $existing_lst_id);
        $this->db->where('lsh_status', 1);
        $this->db->order_by('lsh_batch_id', "ASC");
        $this->db->group_by('lsh_batch_id');
        $batches = $this->db->get('live_assigned_shed');
        $result = '<option value=""> Please Select Batch</option>';
        if ($batches) {
            foreach ($batches->result() as $batch) {
                $subQuery = $this->db->query(
                    "SELECT * FROM live_assigned_shed_summary WHERE lshs_sh_id = ? AND lshs_batch_id = ? AND lshs_status = 1 AND lshs_type = 1 AND tenant_id = ?",
                    array($existing_shed_id, $batch->lsh_batch_id, $tenant_id)
                )->row();

                if ($subQuery) {
                    $result .= '<option value="' . $batch->lsh_batch_id . '">' . $subQuery->lshs_batch_title . '</option>';
                }
            }
        }
        return $result;
    }

    function getAssignedSummaryIdById($shed_id, $batch_id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_status', 1);
        $this->db->where('lsh_sh_id', $shed_id);
        $this->db->where('lsh_batch_id', $batch_id);
        $query = $this->db->get('live_assigned_shed');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function getAssignedSummaryInfoById($id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_status', 1);
        $this->db->where('lshs_id', $id);
        $query = $this->db->get('live_assigned_shed_summary');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    /* =========================== Shed Query =========================== */
    function getPurchasedLivestockAndTypesByLiveAndTypeId($livestock_id, $type_id)
    {
        $this->scope_tenant('livestock_purchase_value');
        $this->db->where('purv_status', 1);
        $this->db->where('purv_ls_id', $livestock_id);
        $this->db->where('purv_lst_id', $type_id);
        $query = $this->db->get('livestock_purchase_value');
        return $query->result();
    }

    function getAssignedSummaryDataByShedAndBatchId($shed_id, $batch_id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_status', 1);
        $this->db->where('lshs_sh_id', $shed_id);
        $this->db->where('lshs_batch_id', $batch_id);
        $query = $this->db->get('live_assigned_shed_summary');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function getAssignedSummaryDataByShedId($shed_id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_status', 1);
        $this->db->where('lshs_sh_id', $shed_id);
        $query = $this->db->get('live_assigned_shed_summary');
        return $query->result();
    }

    function getAssignedLivestockAndTypesBySummaryId($assigned_summary_id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_status', 1);
        $this->db->where('lsh_lshs_id', $assigned_summary_id);
        $query = $this->db->get('live_assigned_shed');
        return $query->result();
    }

    function getAssignedLivestockShedId($shed_id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_status', 1);
        $this->db->where('lsh_sh_id', $shed_id);
        $query = $this->db->get('live_assigned_shed');
        return $query->result();
    }

    function getAssignedLivestockSummaryData()
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->order_by('lshs_id', 'desc');
        $this->db->where('lshs_status', 1);
        $query = $this->db->get('live_assigned_shed_summary');
        return $query->result();
    }

    function getAssignedLivestockValuesBySummaryId($summary_id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->order_by('lsh_id', 'desc');
        $this->db->where('lsh_status', 1);
        $this->db->where('lsh_lshs_id', $summary_id);
        $query = $this->db->get('live_assigned_shed');
        return $query->result();
    }

    function getAssignedLivestockValueDataBySummaryId($summary_id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_status', 1);
        $this->db->where('lsh_lshs_id', $summary_id);
        $query = $this->db->get('live_assigned_shed');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function getLiveAssignedToShed()
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_status', 1);
        $query = $this->db->get('live_assigned_shed');
        return $query->result();
    }

    function getLiveAssignedToShedValueByPurchaseSummaryId($purs_id, $ls_id, $lst_id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_status', 1);
        $this->db->where('lsh_purv_purs_id', $purs_id);
        $this->db->where('lsh_purv_ls_id', $ls_id);
        $this->db->where('lsh_purv_lst_id', $lst_id);
        $query = $this->db->get('live_assigned_shed');
        return $query->row();
    }

    function getAssignedLivestockSumByLivestockPurchaseSummaryId($ls_id, $lst_id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->JOIN('live_assigned_shed_summary', 'live_assigned_shed_summary.lshs_id = live_assigned_shed.lsh_lshs_id');
        $this->db->where('lshs_status', 1);
        $this->db->where('lsh_status', 1);
        $this->db->where('lshs_type', 1);
        $this->db->where('lsh_purv_ls_id', $ls_id);
        $this->db->where('lsh_purv_lst_id', $lst_id);
        $this->db->select('SUM(lsh_assign_quantity) AS total_sum');
        $row = $this->db->get('live_assigned_shed')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getAssignedShedsByLivestockPurchaseSummaryId($ls_id, $lst_id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->JOIN('live_assigned_shed_summary', 'live_assigned_shed_summary.lshs_id = live_assigned_shed.lsh_lshs_id');
        $this->db->where('lshs_status', 1);
        $this->db->where('lsh_status', 1);
        $this->db->where('lsh_purv_ls_id', $ls_id);
        $this->db->where('lsh_purv_lst_id', $lst_id);
        $query = $this->db->get('live_assigned_shed');
        return $query->result();
    }

    function getTotalLivestockSumByPurchaseSummaryId($purs_id)
    {
        $this->scope_tenant('livestock_purchase_value');
        $this->db->JOIN('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->where('purs_id', $purs_id);
        $this->db->select('SUM(purv_quantity) AS total_sum');
        $row = $this->db->get('livestock_purchase_value')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getTotalLivestockAssignedSumByPurchaseSummaryId($purs_id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_status', 1);
        $this->db->where('lsh_purv_purs_id', $purs_id);
        $this->db->select('SUM(lsh_assign_quantity) AS total_sum');
        $query = $this->db->get('live_assigned_shed');
        return $query->result();
    }

    function getTotalPurchasedLivestockQuantity()
    {
        $this->scope_tenant('livestock_purchase_value');
        $this->db->where('purv_status', 1);
        $this->db->select('SUM(purv_quantity) AS total_sum');
        $row = $this->db->get('livestock_purchase_value')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getTotalLivestockPurchasedAmount()
    {
        $this->scope_tenant('livestock_purchase_summary');
        $this->db->where('purs_status', 1);
        $this->db->select('SUM(purs_grand_total) AS total_sum');
        $row = $this->db->get('livestock_purchase_summary')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getTotalLivestockAssignedToShedQuantity()
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_status', 1);
        $this->db->select('SUM(lsh_assign_quantity) AS total_sum');
        $row = $this->db->get('live_assigned_shed')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getPurchaseBySupplierId($supplier_id)
    {
        $this->scope_tenant('livestock_purchase_summary');
        $this->db->where('purs_status', 1);
        $this->db->where('purs_supp_id', $supplier_id);
        $query = $this->db->get('livestock_purchase_summary');
        return $query->result();
    }

    function getSumSupplierWiseTotalPurchaseAmount($supplierId)
    {
        $this->scope_tenant('livestock_purchase_summary');
        $this->db->select('SUM(purs_grand_total) AS total');
        $this->db->where('purs_supp_id', $supplierId);
        $this->db->where('purs_status', 1);
        $row = $this->db->get('livestock_purchase_summary')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getBatchOrShedWiseTotalAssignedQuantity($index, $id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_status', 1);
        $this->db->where("$index", $id);
        $this->db->select('SUM(lshs_assign_total_quantity) AS total_sum');
        $row = $this->db->get('live_assigned_shed_summary')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    public function getLastBatchNumberShedWise($shed_id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_status', 1);
        $this->db->where('lshs_sh_id', $shed_id);
        $this->db->select('MAX(lshs_batch_id) AS maximum_number');
        $row = $this->db->get('live_assigned_shed_summary')->row();
        return ($row && $row->maximum_number !== NULL) ? $row->maximum_number : 0;
    }

    public function getAssignedShedDetailsByShedAndLivestockId($shed_id, $livestock_id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_status', 1);
        $this->db->where('lsh_sh_id', $shed_id);
        $this->db->where('lsh_purv_ls_id', $livestock_id);
        $this->db->select('SUM(lsh_assign_quantity) AS total_sum');
        $row = $this->db->get('live_assigned_shed')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getTotalAssignedLivestockQuantityByPurchaseValueId($purv_id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_status', 1);
        $this->db->where('lsh_purv_id', $purv_id);
        $this->db->select('SUM(lsh_assign_quantity) AS total_sum');
        $row = $this->db->get('live_assigned_shed')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }
}
