<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sale_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

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

    function getLivestockSale($from = '', $to = '')
    {
        $this->scope_tenant('livestock_sale_summary');
        $this->db->where('lsss_status', 1);
        if ($from !== '') $this->db->where('lsss_date >=', $from);
        if ($to !== '')   $this->db->where('lsss_date <=', $to);
        $this->db->order_by('lsss_id', 'desc');
        $query = $this->db->get('livestock_sale_summary');
        return $query->result();
    }

    function getLivestockSaleById($id)
    {
        $this->scope_tenant('livestock_sale_summary');
        $this->db->where('lsss_status', 1);
        $this->db->where('lsss_id', $id);
        $query = $this->db->get('livestock_sale_summary');
        return $query->row();
    }

    function getLivestockSaleValueBySummaryById($id)
    {
        $this->scope_tenant('livestock_sale_value');
        $this->db->where('lssv_status', 1);
        $this->db->where('lssv_lsss_id', $id);
        $query = $this->db->get('livestock_sale_value');
        return $query->result();
    }

    function getTotalLivestockSumBySaleSummaryId($lsss_id)
    {
        $this->scope_tenant('livestock_sale_value');
        $this->db->where('lssv_status', 1);
        $this->db->where('lssv_lsss_id', $lsss_id);
        $this->db->select('SUM(lssv_quantity) AS total_sum');
        $row = $this->db->get('livestock_sale_value')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getTotalLivestockSaleAmount()
    {
        $this->scope_tenant('livestock_sale_summary');
        $this->db->where('lsss_status', 1);
        $this->db->select('SUM(lsss_grand_total) AS total_sum');
        $row = $this->db->get('livestock_sale_summary')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getTotalProductSaleAmount()
    {
        $this->scope_tenant('product_sale_summary');
        $this->db->where('prss_status', 1);
        $this->db->select('SUM(prss_grand_total) AS total_sum');
        $row = $this->db->get('product_sale_summary')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getShedAndBatchWiseLivestockPurchaseQuantity($shed_id, $batch_id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_sh_id', $shed_id);
        $this->db->where('lshs_batch_id', $batch_id);
        $this->db->where('lshs_status', 1);
        $this->db->select('SUM(lshs_assign_total_quantity) AS total_sum');
        $row = $this->db->get('live_assigned_shed_summary')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getShedAndBatchWiseLivestockSaleQuantity($shed_id, $batch_id, $ls_id, $lst_id)
    {
        $this->scope_tenant('livestock_sale_value');
        $this->db->JOIN('livestock_sale_summary', 'livestock_sale_summary.lsss_id = livestock_sale_value.lssv_lsss_id');
        $this->db->where('lssv_shed_id', $shed_id);
        $this->db->where('lssv_batch_id', $batch_id);
        $this->db->where('lssv_ls_id', $ls_id);
        $this->db->where('lssv_lst_id', $lst_id);
        $this->db->where('lsss_status', 1);
        $this->db->where('lssv_status', 1);
        $this->db->select('SUM(lssv_quantity) AS total_sum');
        $row = $this->db->get('livestock_sale_value')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getShedAndBatchWiseLivestockDeathQuantity($ld_sh_id, $batch_id, $ls_id, $lst_id)
    {
        $this->scope_tenant('livestock_death_quantity');
        $this->db->where('ld_sh_id', $ld_sh_id);
        $this->db->where('ld_batch_id', $batch_id);
        $this->db->where('ld_purv_ls_id', $ls_id);
        $this->db->where('ld_purv_lst_id', $lst_id);
        $this->db->where('ld_status', 1);
        $this->db->select('SUM(ld_death_quantity) AS total_sum');
        $query = $this->db->get('livestock_death_quantity');
        $rows = $query->result();
        return (count($rows) > 0 && $rows[0]->total_sum !== NULL) ? $rows[0]->total_sum : 0;
    }

    function getSaleByClientId($client_id)
    {
        $this->scope_tenant('livestock_sale_summary');
        $this->db->where('lsss_status', 1);
        $this->db->where('lsss_c_id', $client_id);
        $query = $this->db->get('livestock_sale_summary');
        return $query->result();
    }

    function getProductSaleByClientId($client_id)
    {
        $this->scope_tenant('product_sale_summary');
        $this->db->where('prss_status', 1);
        $this->db->where('prss_c_id', $client_id);
        $query = $this->db->get('product_sale_summary');
        return $query->result();
    }

    function getSumClientWiseTotalSaleAmount($client_id)
    {
        $this->scope_tenant('livestock_sale_summary');
        $this->db->select('SUM(lsss_grand_total) AS total');
        $this->db->where('lsss_c_id', $client_id);
        $this->db->where('lsss_status', 1);
        $row = $this->db->get('livestock_sale_summary')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getSumClientWiseTotalProductSaleAmount($client_id)
    {
        $this->scope_tenant('product_sale_summary');
        $this->db->select('SUM(prss_grand_total) AS total');
        $this->db->where('prss_c_id', $client_id);
        $this->db->where('prss_status', 1);
        $row = $this->db->get('product_sale_summary')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    function getProductSoldQuantityByProductId($id, $column)
    {
        $this->scope_tenant('product_sale_value');
        $this->db->where('prsv_status', 1);
        $this->db->where('prsv_product_id', $id);
        $this->db->select("SUM($column) AS total_sum");
        $row = $this->db->get('product_sale_value')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getUniqueValueFromAssignedShed()
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->select('DISTINCT(lsh_batch_id)');
        $this->db->where('lsh_status', 1);
        $query = $this->db->get('live_assigned_shed');
        return $query->result();
    }

    function getUniqueValueFromAssignedLivestock()
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->select('DISTINCT(lsh_purv_ls_id)');
        $this->db->where('lsh_status', 1);
        $query = $this->db->get('live_assigned_shed');
        return $query->result();
    }

    function getTotalSaleLivestockQuantity()
    {
        $this->scope_tenant('livestock_sale_value');
        $this->db->where('lssv_status', 1);
        $this->db->select('SUM(lssv_quantity) AS total_sum');
        $row = $this->db->get('livestock_sale_value')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getProductSale($from = '', $to = '')
    {
        $this->scope_tenant('product_sale_summary');
        $this->db->where('prss_status', 1);
        if ($from !== '') $this->db->where('prss_date >=', $from);
        if ($to !== '')   $this->db->where('prss_date <=', $to);
        $this->db->order_by('prss_id', 'desc');
        $query = $this->db->get('product_sale_summary');
        return $query->result();
    }

    function getProductSaleById($id)
    {
        $this->scope_tenant('product_sale_summary');
        $this->db->where('prss_status', 1);
        $this->db->where('prss_id', $id);
        $query = $this->db->get('product_sale_summary');
        return $query->row();
    }

    function getSaleWiseSaleProductQuantity($summary_id, $column)
    {
        $this->scope_tenant('product_sale_value');
        $this->db->where('prsv_status', 1);
        $this->db->where('prsv_prss_id', $summary_id);
        $this->db->select("SUM($column) AS total_sum");
        $row = $this->db->get('product_sale_value')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getProductAssignedWiseProductSaleValue($assigned_id, $column)
    {
        $this->scope_tenant('product_sale_value');
        $this->db->where('prsv_status', 1);
        $this->db->where('prsv_pra_id', $assigned_id);
        $this->db->select("SUM($column) AS total_sum");
        $row = $this->db->get('product_sale_value')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getTotalSaleProductQuantity()
    {
        $this->scope_tenant('product_sale_value');
        $this->db->where('prsv_status', 1);
        $this->db->select('SUM(prsv_quantity) AS total_sum');
        $row = $this->db->get('product_sale_value')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getProductSaleValueByProductId($summary_id)
    {
        $this->scope_tenant('product_sale_value');
        $this->db->where('prsv_status', 1);
        $this->db->where('prsv_prss_id', $summary_id);
        $query = $this->db->get('product_sale_value');
        return $query->result();
    }

    function getProductSaleValueByProductIdClientInvoice($summary_id)
    {
        $this->scope_tenant('product_sale_value');
        $this->db->select('prsv_product_id, prsv_prss_id');
        $this->db->where('prsv_status', 1);
        $this->db->where('prsv_prss_id', $summary_id);
        $this->db->group_by('prsv_product_id');
        $this->db->group_by('prsv_prss_id');
        $query = $this->db->get('product_sale_value');
        return $query->result();
    }

    function getProductWiseTotalValue($pr_id, $summary_id, $column)
    {
        $this->scope_tenant('product_sale_value');
        $this->db->where('prsv_status', 1);
        $this->db->where('prsv_product_id', $pr_id);
        $this->db->where('prsv_prss_id', $summary_id);
        $this->db->select("SUM($column) AS total_sum");
        $row = $this->db->get('product_sale_value')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }

    function getShedWiseSoldLivestock($shed_id)
    {
        $this->scope_tenant('livestock_sale_value');
        $this->db->JOIN('livestock_sale_summary', 'livestock_sale_summary.lsss_id = livestock_sale_value.lssv_lsss_id');
        $this->db->where('lssv_shed_id', $shed_id);
        $this->db->where('lsss_status', 1);
        $this->db->where('lssv_status', 1);
        $this->db->select('SUM(lssv_quantity) AS total_sum');
        $row = $this->db->get('livestock_sale_value')->row();
        return ($row && $row->total_sum !== NULL) ? $row->total_sum : 0;
    }
}
