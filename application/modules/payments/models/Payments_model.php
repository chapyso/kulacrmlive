<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payments_model extends MY_Model
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

    function getSupplierPaymentsByPurchaseId($purchaseId)
    {
        $this->scope_tenant('supplier_payment');
        $this->db->where('sp_status', 1);
        $this->db->where('sp_purs_id', $purchaseId);
        $query = $this->db->get('supplier_payment');
        return $query->result();
    }

    function getFoodSupplierPaymentsByPurchaseId($foodPurchaseId)
    {
        $this->scope_tenant('supplier_payment');
        $this->db->where('sp_status', 1);
        $this->db->where('sp_fdps_id', $foodPurchaseId);
        $query = $this->db->get('supplier_payment');
        return $query->result();
    }

    function getVaccineSupplierPaymentsByPurchaseId($vaccinePurchaseId)
    {
        $this->scope_tenant('supplier_payment');
        $this->db->where('sp_status', 1);
        $this->db->where('sp_vps_id', $vaccinePurchaseId);
        $query = $this->db->get('supplier_payment');
        return $query->result();
    }

    function getSupplierWiseSupplierPayments($supplier_id)
    {
        $this->scope_tenant('supplier_payment');
        $this->db->where('sp_status', 1);
        $this->db->where('sp_s_id', $supplier_id);
        $query = $this->db->get('supplier_payment');
        return $query->result();
    }

    function getSumSupplierAndPurchaseWiseTotalPaidAmount($supplierId, $purchaseId)
    {
        $this->scope_tenant('supplier_payment');
        $this->db->select('SUM(sp_payment_amount) AS total');
        $this->db->where('sp_s_id', $supplierId);
        $this->db->where('sp_purs_id', $purchaseId);
        $this->db->where('sp_status', 1);
        $row = $this->db->get('supplier_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getSumSupplierAndFoodPurchaseWiseTotalPaidAmount($supplierId, $foodPurchaseId)
    {
        $this->scope_tenant('supplier_payment');
        $this->db->select('SUM(sp_payment_amount) AS total');
        $this->db->where('sp_s_id', $supplierId);
        $this->db->where('sp_fdps_id', $foodPurchaseId);
        $this->db->where('sp_status', 1);
        $row = $this->db->get('supplier_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getSumSupplierAndVaccinePurchaseWiseTotalPaidAmount($supplierId, $vaccinePurchaseId)
    {
        $this->scope_tenant('supplier_payment');
        $this->db->select('SUM(sp_payment_amount) AS total');
        $this->db->where('sp_s_id', $supplierId);
        $this->db->where('sp_vps_id', $vaccinePurchaseId);
        $this->db->where('sp_status', 1);
        $row = $this->db->get('supplier_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getSumSupplierWiseTotalPaidAmount($supplierId)
    {
        $this->scope_tenant('supplier_payment');
        $this->db->select('SUM(sp_payment_amount) AS total');
        $this->db->where('sp_s_id', $supplierId);
        $this->db->where('sp_status', 1);
        $row = $this->db->get('supplier_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getSupplierPurchaseWisePaymentCountRow($supplierId, $purs_id)
    {
        $this->scope_tenant('supplier_payment');
        $this->db->select('COUNT(sp_payment_amount) AS total', FALSE);
        $this->db->where('sp_s_id', $supplierId);
        $this->db->where('sp_purs_id', $purs_id);
        $this->db->where('sp_status', 1);
        $row = $this->db->get('supplier_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getSupplierFoodPurchaseWisePaymentCountRow($supplierId, $fdps_id)
    {
        $this->scope_tenant('supplier_payment');
        $this->db->select('COUNT(sp_payment_amount) AS total', FALSE);
        $this->db->where('sp_s_id', $supplierId);
        $this->db->where('sp_fdps_id', $fdps_id);
        $this->db->where('sp_status', 1);
        $row = $this->db->get('supplier_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getSupplierVaccinePurchaseWisePaymentCountRow($supplierId, $vps_id)
    {
        $this->scope_tenant('supplier_payment');
        $this->db->select('COUNT(sp_payment_amount) AS total', FALSE);
        $this->db->where('sp_s_id', $supplierId);
        $this->db->where('sp_vps_id', $vps_id);
        $this->db->where('sp_status', 1);
        $row = $this->db->get('supplier_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getSumSupplierTotalPaidAmount()
    {
        $this->scope_tenant('supplier_payment');
        $this->db->select('SUM(sp_payment_amount) AS total');
        $this->db->where('sp_status', 1);
        $row = $this->db->get('supplier_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    /* =========================== Client Part =========================== */
    function getClientPaymentsBySaleId($saleId)
    {
        $this->scope_tenant('client_payment');
        $this->db->where('cp_status', 1);
        $this->db->where('cp_lsss_id', $saleId);
        $query = $this->db->get('client_payment');
        return $query->result();
    }

    function getClientProductPaymentsBySaleId($saleId)
    {
        $this->scope_tenant('client_payment');
        $this->db->where('cp_status', 1);
        $this->db->where('cp_prss_id', $saleId);
        $query = $this->db->get('client_payment');
        return $query->result();
    }

    function getClientWiseClientPayments($client_id)
    {
        $this->scope_tenant('client_payment');
        $this->db->where('cp_status', 1);
        $this->db->where('cp_c_id', $client_id);
        $query = $this->db->get('client_payment');
        return $query->result();
    }

    function getSumClientAndSaleWiseTotalReceivedAmount($clientId, $purchaseId)
    {
        $this->scope_tenant('client_payment');
        $this->db->select('SUM(cp_received_amount) AS total');
        $this->db->where('cp_c_id', $clientId);
        $this->db->where('cp_lsss_id', $purchaseId);
        $this->db->where('cp_status', 1);
        $row = $this->db->get('client_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getSumClientAndProductSaleWiseTotalReceivedAmount($clientId, $purchaseId)
    {
        $this->scope_tenant('client_payment');
        $this->db->select('SUM(cp_received_amount) AS total');
        $this->db->where('cp_c_id', $clientId);
        $this->db->where('cp_prss_id', $purchaseId);
        $this->db->where('cp_status', 1);
        $row = $this->db->get('client_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getSumClientWiseTotalReceivedAndPaymentAmount($clientId, $column)
    {
        $this->scope_tenant('client_payment');
        $this->db->select("SUM($column) AS total");
        $this->db->where('cp_c_id', $clientId);
        $this->db->where('cp_status', 1);
        $row = $this->db->get('client_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getClientSaleWisePaymentCountRow($clientId, $lsss_id)
    {
        $this->scope_tenant('client_payment');
        $this->db->select('COUNT(cp_received_amount) AS total', FALSE);
        $this->db->where('cp_c_id', $clientId);
        $this->db->where('cp_lsss_id', $lsss_id);
        $this->db->where('cp_status', 1);
        $row = $this->db->get('client_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getClientProductSaleWisePaymentCountRow($clientId, $prss_id)
    {
        $this->scope_tenant('client_payment');
        $this->db->select('COUNT(cp_received_amount) AS total', FALSE);
        $this->db->where('cp_c_id', $clientId);
        $this->db->where('cp_prss_id', $prss_id);
        $this->db->where('cp_status', 1);
        $row = $this->db->get('client_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getSumClientTotalPaidAmount()
    {
        $this->scope_tenant('client_payment');
        $this->db->select('SUM(cp_received_amount) AS total');
        $this->db->where('cp_status', 1);
        $row = $this->db->get('client_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    /* =========================== Staff Part =========================== */
    function getStaffWisePayments($staff_id)
    {
        $this->scope_tenant('staff_payment');
        $this->db->where('sfp_status', 1);
        $this->db->where('sfp_sf_id', $staff_id);
        $query = $this->db->get('staff_payment');
        return $query->result();
    }

    function getStaffWiseTotalPaymentAndReceivedAmountSum($staff_id, $column)
    {
        $this->scope_tenant('staff_payment');
        $this->db->select("SUM($column) AS total");
        $this->db->where('sfp_sf_id', $staff_id);
        $this->db->where('sfp_status', 1);
        $row = $this->db->get('staff_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getTotalStaffPaymentAmount($column)
    {
        $this->scope_tenant('staff_payment');
        $this->db->select("SUM($column) AS total");
        $this->db->where('sfp_status', 1);
        $row = $this->db->get('staff_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getStaffWisePaymentAndReceivedCountRow($staff_id, $column)
    {
        $this->scope_tenant('staff_payment');
        $allowed = array('sfp_payment_amount', 'sfp_received_amount', 'sfp_date');
        if (!in_array($column, $allowed, TRUE)) {
            return 0;
        }
        $this->db->select("COUNT($column) AS total", FALSE);
        $this->db->where('sfp_sf_id', $staff_id);
        $this->db->where('sfp_status', 1);
        $row = $this->db->get('staff_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getLastPaymentDate($staff_id, $column)
    {
        $this->scope_tenant('staff_payment');
        $allowed = array('sfp_date', 'sfp_payment_amount', 'sfp_received_amount');
        if (!in_array($column, $allowed, TRUE)) {
            return NULL;
        }
        $this->db->select("MAX($column) AS total", FALSE);
        $this->db->where('sfp_sf_id', $staff_id);
        $this->db->where('sfp_status', 1);
        $row = $this->db->get('staff_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : NULL;
    }
}
