<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_model extends MY_Model
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

    function getProduct()
    {
        $this->scope_tenant('product');
        $this->db->where('pr_status', 1);
        $query = $this->db->get('product');
        return $query;
    }

    function getProductById($pr_id)
    {
        $this->scope_tenant('product');
        $this->db->where('pr_status', 1);
        $this->db->where('pr_id', $pr_id);
        $query = $this->db->get('product');
        return $query->row();
    }

    function getTotalProductionQuantityByProductId($product_id)
    {
        $this->scope_tenant('product_stock');
        $this->db->where('prs_status', 1);
        $this->db->where('prs_pr_id', $product_id);
        $this->db->select('SUM(prs_production_quantity) AS total_sum');
        $query = $this->db->get('product_stock');
        return $query->row() ? $query->row()->total_sum : 0;
    }

    function getTotalProductionQuantityByShedAndBatchId($shed_id, $batch_id)
    {
        $this->scope_tenant('product_stock');
        $this->db->where('prs_status', 1);
        $this->db->where('prs_shed_id', $shed_id);
        $this->db->where('prs_batch_id', $batch_id);
        $this->db->select('SUM(prs_production_quantity) AS total_sum');
        $query = $this->db->get('product_stock');
        return $query->row() ? $query->row()->total_sum : 0;
    }

    function getTotalProductionQuantityByShedId($shed_id)
    {
        $this->scope_tenant('product_stock');
        $this->db->where('prs_status', 1);
        $this->db->where('prs_shed_id', $shed_id);
        $this->db->select('SUM(prs_production_quantity) AS total_sum');
        $query = $this->db->get('product_stock');
        return $query->row() ? $query->row()->total_sum : 0;
    }

    function getTotalProductionQuantityByProductAssignId($assign_id)
    {
        $this->scope_tenant('product_stock');
        $this->db->where('prs_status', 1);
        $this->db->where('prs_pra_id', $assign_id);
        $this->db->select('SUM(prs_production_quantity) AS total_sum');
        $query = $this->db->get('product_stock');
        return $query->row() ? $query->row()->total_sum : 0;
    }

    function getStockProductionByProductId($pr_id)
    {
        $this->scope_tenant('product_stock');
        $this->db->where('prs_status', 1);
        $this->db->where('prs_pr_id', $pr_id);
        $query = $this->db->get('product_stock');
        return $query->result();
    }

    function getProductAssign()
    {
        $this->scope_tenant('product_assign');
        $this->db->where('pra_status', 1);
        $this->db->order_by('pra_id', 'desc');
        $query = $this->db->get('product_assign');
        return $query->result();
    }

    function getStockProductionByProductAssignedId($pra_id)
    {
        $this->scope_tenant('product_stock');
        $this->db->where('prs_status', 1);
        $this->db->where('prs_pra_id', $pra_id);
        $query = $this->db->get('product_stock');
        return $query->result();
    }

    function getProductAssignById($pra_id)
    {
        $this->scope_tenant('product_assign');
        $this->db->where('pra_status', 1);
        $this->db->where('pra_id', $pra_id);
        $query = $this->db->get('product_assign');
        return $query->row();
    }

    function getAssignedProductsByProductId($pr_id)
    {
        $this->scope_tenant('product_assign');
        $this->db->where('pra_status', 1);
        $this->db->where('pra_pr_id', $pr_id);
        $query = $this->db->get('product_assign');
        return $query->result();
    }

    function getAssignedProductBatchByAjax($product_id, $shed_id, $batch_id)
    {
        $this->scope_tenant('product_assign');
        $this->db->where('pra_status', 1);
        $this->db->where('pra_pr_id', $product_id);
        $this->db->where('pra_shed_id', $shed_id);
        $this->db->where('pra_batch_id', $batch_id);
        $query = $this->db->get('product_assign');
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    function getAssignedProductsShedBatchId($shed_id, $batch_id)
    {
        $this->scope_tenant('product_assign');
        $this->db->where('pra_status', 1);
        $this->db->where('pra_shed_id', $shed_id);
        $this->db->where('pra_batch_id', $batch_id);
        $query = $this->db->get('product_assign');
        return $query->result();
    }

    function getAssignedProductsShedId($shed_id)
    {
        $this->scope_tenant('product_assign');
        $this->db->where('pra_status', 1);
        $this->db->where('pra_shed_id', $shed_id);
        $query = $this->db->get('product_assign');
        return $query->result();
    }

    function getTotalProductWastedQuantityByProductAssignId($assign_id)
    {
        $this->scope_tenant('product_wasted');
        $this->db->where('prw_status', 1);
        $this->db->where('prw_pra_id', $assign_id);
        $this->db->select('SUM(prw_wasted_quantity) AS total_sum');
        $query = $this->db->get('product_wasted');
        return $query->row() ? $query->row()->total_sum : 0;
    }

    function getWastedProductionByProductAssignedId($pra_id)
    {
        $this->scope_tenant('product_wasted');
        $this->db->where('prw_status', 1);
        $this->db->where('prw_pra_id', $pra_id);
        $query = $this->db->get('product_wasted');
        return $query->result();
    }

    function getTotalProductWastedQuantityByProductId($pr_id)
    {
        $this->scope_tenant('product_wasted');
        $this->db->where('prw_status', 1);
        $this->db->where('prw_pr_id', $pr_id);
        $this->db->select('SUM(prw_wasted_quantity) AS total_sum');
        $query = $this->db->get('product_wasted');
        return $query->row() ? $query->row()->total_sum : 0;
    }

    function getProductCategory()
    {
        $this->scope_tenant('product_category');
        $this->db->where('prc_status', 1);
        $query = $this->db->get('product_category');
        return $query;
    }

    function getProductCategoryById($prc_id)
    {
        $this->scope_tenant('product_category');
        $this->db->where('prc_status', 1);
        $this->db->where('prc_id', $prc_id);
        $query = $this->db->get('product_category');
        return $query->row();
    }

    function getLivestockReproduction()
    {
        $this->scope_tenant('livestock_reproduction');
        $this->db->where('lrp_status', 1);
        $query = $this->db->get('livestock_reproduction');
        return $query->result();
    }

    function getLivestockReproductionById($pr_id)
    {
        $this->scope_tenant('livestock_reproduction');
        $this->db->where('lrp_status', 1);
        $this->db->where('lrp_id', $pr_id);
        $query = $this->db->get('livestock_reproduction');
        return $query->row();
    }

    function getLivestockAssignedDataByReproductionId($id)
    {
        $this->scope_tenant('live_assigned_shed');
        $this->db->where('lsh_status', 1);
        $this->db->where('lsh_reproduction_id', $id);
        $query = $this->db->get('live_assigned_shed');
        return $query->row();
    }

    function getLivestockAssignedSummaryDataByReproductionId($id)
    {
        $this->scope_tenant('live_assigned_shed_summary');
        $this->db->where('lshs_status', 1);
        $this->db->where('lshs_reproduction_id', $id);
        $query = $this->db->get('live_assigned_shed_summary');
        return $query->row();
    }
}
