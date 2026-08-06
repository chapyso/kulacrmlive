<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vaccine_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function insertVaccine($data)
    {
        $data = $this->prepare_tenant_data('vaccine', $data);
        $this->db->insert('vaccine', $data);
        $returnValue = $this->db->insert_id();
        return $returnValue;
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

    function getVaccine()
    {
        $this->scope_tenant('vaccine');
        $this->db->where('vcc_status', 1);
        $this->db->order_by("vcc_id", "desc");
        $query = $this->db->get('vaccine');
        return $query->result();
    }

    function getVaccineById($vcc_id)
    {
        $this->scope_tenant('vaccine');
        $this->db->where('vcc_id', $vcc_id);
        $this->db->where('vcc_status', 1);
        $query = $this->db->get('vaccine');
        return $query->row();
    }

    function updateVaccine($id, $data)
    {
        $this->scope_tenant('vaccine');
        $this->db->where('vcc_id', $id);
        $this->db->update('vaccine', $data);
    }

    function getVaccineByShedId($shed_id)
    {
        $this->scope_tenant('vaccine');
        $this->db->order_by('vcc_id', 'asc');
        $this->db->where('shed', $shed_id);
        $query = $this->db->get('vaccine');
        return $query->result();
    }

    function getAssignedVaccineQuantityByVaccineId($vcc_id)
    {
        $this->scope_tenant('vaccination');
        $this->db->where('vccn_status', 1);
        $this->db->where('vccn_vcc_id', $vcc_id);
        $query = $this->db->count_all_results('vaccination');
        if ($this->db->affected_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    /* ==================== Vaccination ==================== */
    function getVaccinationByVaccineId($vaccine_id)
    {
        $this->scope_tenant('vaccination');
        $this->db->where('vccn_status', 1);
        $this->db->order_by('vccn_id', 'ASC');
        $this->db->where('vccn_vcc_id', $vaccine_id);
        $query = $this->db->get('vaccination');
        return $query->result();
    }

    function getVaccinationDoseQuantityByVaccinationId($vaccination_id)
    {
        $this->scope_tenant('vaccine_dose_assigned_quantity');
        $this->db->where('vdq_status', 1);
        $this->db->where('vdq_vccn_id', $vaccination_id);
        $query = $this->db->count_all_results('vaccine_dose_assigned_quantity');
        if ($this->db->affected_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    function getAssignedVaccineCheckByLivestockAndTypeWise($vcc_id, $ls_id, $lst_id)
    {
        $this->scope_tenant('vaccination');
        $this->db->where('vccn_status', 1);
        $this->db->where('vccn_vcc_id', $vcc_id);
        $this->db->where('vccn_ls_id', $ls_id);
        $this->db->where('vccn_lst_id', $lst_id);
        $query = $this->db->get('vaccination');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    /* ==================== Vaccine Dose Assigned Quantity ==================== */
    function getVaccineAssignedDoseQuantityByVaccinationId($vaccination_id)
    {
        $this->scope_tenant('vaccine_dose_assigned_quantity');
        $this->db->JOIN('vaccination', 'vaccination.vccn_id = vaccine_dose_assigned_quantity.vdq_vccn_id');
        $this->db->where('vccn_status', 1);
        $this->db->where('vdq_status', 1);
        $this->db->where('vdq_vccn_id', $vaccination_id);
        $query = $this->db->get('vaccine_dose_assigned_quantity');
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function getVaccineDoseQuantity($vcc_id)
    {
        $this->scope_tenant('vaccine_dose_assigned_quantity');
        $this->db->where('vdq_status', 1);
        $this->db->where('vdq_vcc_id', $vcc_id);
        $query = $this->db->count_all_results('vaccine_dose_assigned_quantity');
        if ($this->db->affected_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    function getVaccineDoseCompleted($vcc_id)
    {
        $this->scope_tenant('vaccine_dose_status');
        $this->db->where('vds_status', 1);
        $this->db->where('vds_vcc_id', $vcc_id);
        $query = $this->db->count_all_results('vaccine_dose_status');
        if ($this->db->affected_rows() > 0) {
            return $query;
        } else {
            return FALSE;
        }
    }

    function getVaccineDosesByVaccinationId($vaccination_id)
    {
        $this->scope_tenant('vaccine_dose_assigned_quantity');
        $this->db->where('vdq_status', 1);
        $this->db->where('vdq_vccn_id', $vaccination_id);
        $query = $this->db->get('vaccine_dose_assigned_quantity');
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function getVaccineByBatchIdSingleData($ls_id, $lst_id)
    {
        $this->scope_tenant('vaccination');
        $this->db->where('vccn_status', 1);
        $this->db->where('vccn_ls_id', $ls_id);
        $this->db->where('vccn_lst_id', $lst_id);
        $query = $this->db->get('vaccination');
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function getAssignedBatchesBySheds($shed_id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_status', 1);
        $this->db->order_by('lshs_batch_id', 'DESC');
        $this->db->where('lshs_sh_id', $shed_id);
        $query = $this->db->get('live_assigned_shed_summary');
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function getLastVaccineIssuedDate($shed_id)
    {
        $tenant_id = $this->get_tenant_id();
        $query = $this->db->query(
            "SELECT MAX(vds_created_at) AS maximumDate FROM vaccine_dose_status WHERE vds_sh_id = ? AND vds_status = 1 AND tenant_id = ?",
            array($shed_id, $tenant_id)
        );
        $row = $query->row();
        if ($row && $row->maximumDate !== NULL) {
            return $row->maximumDate;
        }
        return FALSE;
    }

    /* ==================== Vaccine Dose Status ==================== */
    function getVaccineDoseStatusByPurchaseLivestockTypeVaccineQuantityId($vdq_id, $batch_id)
    {
        $this->scope_tenant('vaccine_dose_status');
        $this->db->where('vds_status', 1);
        $this->db->where('vds_vdq_id', $vdq_id);
        $this->db->where('vds_batch_id', $batch_id);
        $query = $this->db->get('vaccine_dose_status');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    /* ============================================== Vaccine Route ==================================================== */
    function getVaccineRoute()
    {
        $this->scope_tenant('vaccine_route');
        $this->db->where('vccr_status', 1);
        $query = $this->db->get('vaccine_route');
        return $query;
    }

    function getVaccineRouteById($vccr_id)
    {
        $this->scope_tenant('vaccine_route');
        $this->db->where('vccr_status', 1);
        $this->db->where('vccr_id', $vccr_id);
        $query = $this->db->get('vaccine_route');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function getVaccineRouteByIdAjax($vccr_id)
    {
        $this->scope_tenant('vaccine_route');
        $this->db->where('vccr_status', 1);
        $this->db->where('vccr_id', $vccr_id);
        $query = $this->db->get('vaccine_route');
        return $query->result();
    }

    /* ======================= Vaccine Purchase ======================= */
    function getVaccinePurchase()
    {
        $this->scope_tenant('vaccine_purchase_summary');
        $this->db->where('vps_status', 1);
        $this->db->order_by("vps_id", "desc");
        $query = $this->db->get('vaccine_purchase_summary');
        return $query->result();
    }

    function getVaccinePurchaseSummaryById($id)
    {
        $this->scope_tenant('vaccine_purchase_summary');
        $this->db->where('vps_id', $id);
        $this->db->where('vps_status', 1);
        $query = $this->db->get('vaccine_purchase_summary');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function getVaccinePurchaseBySupplierId($supplier_id)
    {
        $this->scope_tenant('vaccine_purchase_summary');
        $this->db->where('vps_status', 1);
        $this->db->where('vps_s_id', $supplier_id);
        $query = $this->db->get('vaccine_purchase_summary');
        return $query->result();
    }

    function getVaccinePurchaseValueBySummaryId($id)
    {
        $this->scope_tenant('vaccine_purchase_value');
        $this->db->where('vpv_status', 1);
        $this->db->where('vpv_vps_id', $id);
        $query = $this->db->get('vaccine_purchase_value');
        return $query->result();
    }

    function getVaccinePurchaseQuantityByPurchaseId($id, $column)
    {
        $this->scope_tenant('vaccine_purchase_value');
        $this->db->JOIN('vaccine_purchase_summary', 'vaccine_purchase_summary.vps_id = vaccine_purchase_value.vpv_vps_id');
        $this->db->where('vps_status', 1);
        $this->db->where('vpv_status', 1);
        $this->db->where('vpv_vps_id', $id);
        $this->db->select("SUM($column) AS total");
        $query = $this->db->get('vaccine_purchase_value');
        return $query->row() ? $query->row()->total : 0;
    }

    function getTotalVaccinePurchaseQuantity($column)
    {
        $this->scope_tenant('vaccine_purchase_value');
        $this->db->JOIN('vaccine_purchase_summary', 'vaccine_purchase_summary.vps_id = vaccine_purchase_value.vpv_vps_id');
        $this->db->where('vps_status', 1);
        $this->db->where('vpv_status', 1);
        $this->db->select("SUM($column) AS total");
        $query = $this->db->get('vaccine_purchase_value');
        return $query->row() ? $query->row()->total : 0;
    }

    function getVaccineWiseVaccinePurchaseQuantity($vacc_id, $column)
    {
        $this->scope_tenant('vaccine_purchase_value');
        $this->db->JOIN('vaccine_purchase_summary', 'vaccine_purchase_summary.vps_id = vaccine_purchase_value.vpv_vps_id');
        $this->db->where('vps_status', 1);
        $this->db->where('vpv_status', 1);
        $this->db->where('vpv_vcc_id', $vacc_id);
        $this->db->select("SUM($column) AS total");
        $query = $this->db->get('vaccine_purchase_value');
        return $query->row() ? $query->row()->total : 0;
    }

    function getSumVaccineSupplierWiseTotalPurchaseAmount($supplierId)
    {
        $this->scope_tenant('vaccine_purchase_summary');
        $this->db->select('SUM(vps_grand_total) AS total');
        $this->db->where('vps_s_id', $supplierId);
        $this->db->where('vps_status', 1);
        $query = $this->db->get('vaccine_purchase_summary');
        return $query->row() ? $query->row()->total : 0;
    }

    /* ======================= Vaccine Used ======================= */
    function getVaccineWiseVaccineUsedQuantity($vacc_id, $column)
    {
        $this->scope_tenant('vaccine_dose_status');
        $this->db->where('vds_status', 1);
        $this->db->where('vds_vcc_id', $vacc_id);
        $this->db->select("SUM($column) AS total");
        $query = $this->db->get('vaccine_dose_status');
        return $query->row() ? $query->row()->total : 0;
    }

    function getUsedVaccineFromDose()
    {
        $this->scope_tenant('vaccine_dose_status');
        $this->db->where('vds_status', 1);
        $this->db->order_by("vds_id", "DESC");
        $query = $this->db->get('vaccine_dose_status');
        return $query->result();
    }

    /* ======================= Wasted Vaccine ======================= */
    function getVaccineWastedByVaccineId($vcc_id, $column)
    {
        $this->scope_tenant('vaccine_wasted');
        $this->db->where('vccw_status', 1);
        $this->db->where('vccw_vcc_id', $vcc_id);
        $this->db->select("SUM($column) AS total");
        $query = $this->db->get('vaccine_wasted');
        return $query->row() ? $query->row()->total : 0;
    }

    function getVaccineWastedValuesByVaccineId($vcc_id)
    {
        $this->scope_tenant('vaccine_wasted');
        $this->db->where('vccw_status', 1);
        $this->db->where('vccw_vcc_id', $vcc_id);
        $this->db->order_by("vccw_id", "desc");
        $query = $this->db->get('vaccine_wasted');
        return $query->result();
    }

    function getTotalVaccineWasted($column)
    {
        $this->scope_tenant('vaccine_wasted');
        $this->db->where('vccw_status', 1);
        $this->db->select("SUM($column) AS total");
        $query = $this->db->get('vaccine_wasted');
        return $query->row() ? $query->row()->total : 0;
    }

    function getUsedVaccineByShedAndBatch($shed_id, $batch_id)
    {
        $this->scope_tenant('vaccine_dose_status');
        $this->db->select('vds_vcc_id');
        $this->db->where('vds_status', 1);
        $this->db->where('vds_sh_id', $shed_id);
        $this->db->where('vds_batch_id', $batch_id);
        $this->db->group_by('vds_vcc_id');
        $query = $this->db->get('vaccine_dose_status');
        return $query->result();
    }

    function getUsedVaccineByShed($shed_id)
    {
        $this->scope_tenant('vaccine_dose_status');
        $this->db->select('vds_vcc_id');
        $this->db->where('vds_status', 1);
        $this->db->where('vds_sh_id', $shed_id);
        $this->db->group_by('vds_vcc_id');
        $query = $this->db->get('vaccine_dose_status');
        return $query->result();
    }
}
