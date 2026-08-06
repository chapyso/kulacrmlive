<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function getCountRow($table, $column, $condition)
    {
        $this->scope_tenant($table);
        $this->db->where($condition);
        $this->db->select("COUNT($column) AS total");
        $query = $this->db->count_all_results($table);
        return $query;
    }

    function getSumData($table, $column, $condition)
    {
        $this->scope_tenant($table);
        $this->db->where($condition);
        $this->db->select("SUM($column) AS total");
        $query = $this->db->get($table);
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getSingleData($table, $condition)
    {
        $this->scope_tenant($table);
        $this->db->where($condition);
        $query = $this->db->get($table);
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function getData($table, $data)
    {
        $this->scope_tenant($table);
        $this->db->where($data);
        $query = $this->db->get($table);
        return $query;
    }

    /* ============================= Purchase Reports =============================s */
    /* ==================== Total Purchase ==================== */
    function getTotalPurchase($column)
    {
        $this->db->where('purs_status', 1);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_purchase_summary');
        $query = $this->db->get('livestock_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getTotalPurchaseSubTotal($column)
    {
        $this->db->JOIN('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('livestock_purchase_value');
        $query = $this->db->get('livestock_purchase_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }
    function getTotalPurchaseBill($column)
    {
        $this->db->where('purs_status', 1);
        $this->db->select("COUNT($column) AS total");
        $this->scope_tenant('livestock_purchase_summary');
        $query = $this->db->count_all_results('livestock_purchase_summary');
        return $query;
    }

    /* ==================== Today Purchase ==================== */
    function getTodayPurchase($today, $column)
    {
        $this->db->where('purs_status', 1);
        $this->db->where('DATE(purs_date)', $today);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_purchase_summary');
        $query = $this->db->get('livestock_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getTodayPurchaseSubTotal($today, $column)
    {
        $this->db->JOIN('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->where('DATE(purs_date)', $today);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_purchase_value');
        $query = $this->db->get('livestock_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getTodayPurchaseBill($today, $column)
    {
        $this->db->where('purs_status', 1);
        $this->db->where('DATE(purs_date)', $today);
        $this->db->select("COUNT($column) AS total");
        $this->scope_tenant('livestock_purchase_summary');
        $query = $this->db->count_all_results('livestock_purchase_summary');
        return $query;
    }
    function getTodayLivestockPurchaseQuantityByLivestockId($today, $ls_id, $column)
    {
        $this->db->JOIN('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->where('purv_ls_id', $ls_id);
        $this->db->where('DATE(purs_date)', $today);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('livestock_purchase_value');
        $query = $this->db->get('livestock_purchase_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }


    /* ==================== Year Month Wise Purchase ==================== */
    function getYearMonthWisePurchase($year, $month, $column)
    {
        $this->db->JOIN('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->where('YEAR(purs_date)', $year);
        $this->db->where('MONTH(purs_date)', $month);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_purchase_value');
        $query = $this->db->get('livestock_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getYearMonthWisePurchaseBill($year, $month, $column)
    {
        $this->db->where('purs_status', 1);
        $this->db->where('YEAR(purs_date)', $year);
        $this->db->where('MONTH(purs_date)', $month);
        $this->db->select("COUNT($column) AS total");
        $this->scope_tenant('livestock_purchase_summary');
        $query = $this->db->count_all_results('livestock_purchase_summary');
        return $query;
    }

    function getYearMonthWiseLivestockPurchaseQuantityByLivestockId($year, $month, $ls_id, $column)
    {
        $this->db->JOIN('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->where('purv_ls_id', $ls_id);
        $this->db->where('YEAR(purs_date)', $year);
        $this->db->where('MONTH(purs_date)', $month);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('livestock_purchase_value');
        $query = $this->db->get('livestock_purchase_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    /* ==================== Year Wise Purchase Reports ==================== */
    function getYearWisePurchase($year, $column)
    {
        $this->db->JOIN('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->where('YEAR(purs_date)', $year);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_purchase_value');
        $query = $this->db->get('livestock_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getYearWisePurchaseBill($year, $column)
    {
        $this->db->where('purs_status', 1);
        $this->db->where('YEAR(purs_date)', $year);
        $this->db->select("COUNT($column) AS total");
        $this->scope_tenant('livestock_purchase_summary');
        $query = $this->db->count_all_results('livestock_purchase_summary');
        return $query;
    }



    /* ==================== Start date, end date wise filtering ==================== */
    function getStartDateEndDateWisePurchaseByLivestockId($start_date, $end_date, $ls_id, $column)
    {
        $this->db->JOIN('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->where('purv_ls_id', $ls_id);
        $this->db->where('DATE(purs_date)>=', $start_date);
        $this->db->where('DATE(purs_date)<=', $end_date);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_purchase_value');
        $query = $this->db->get('livestock_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getStartDateEndDateWisePurchase($start_date, $end_date, $column)
    {
        $this->db->JOIN('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->where('DATE(purs_date)>=', $start_date);
        $this->db->where('DATE(purs_date)<=', $end_date);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_purchase_value');
        $query = $this->db->get('livestock_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getStartDateEndDatePurchaseBill($start_date, $end_date, $column)
    {
        $this->db->where('purs_status', 1);
        $this->db->where('DATE(purs_date)>=', $start_date);
        $this->db->where('DATE(purs_date)<=', $end_date);
        $this->db->select("COUNT($column) AS total");
        $this->scope_tenant('livestock_purchase_summary');
        $query = $this->db->count_all_results('livestock_purchase_summary');
        return $query;
    }






    /* ============================= Sale Reports ============================= */
    /* ==================== Today Sale quantity, Amount, Bill ==================== */
    function getTodaySaleQuantity($today, $livestock_id, $column)
    {
        $this->db->JOIN('livestock_sale_summary', 'livestock_sale_summary.lsss_id = livestock_sale_value.lssv_lsss_id');
        $this->db->where('lsss_status', 1);
        $this->db->where('lssv_status', 1);
        $this->db->where('lssv_ls_id', $livestock_id);
        $this->db->where('DATE(lsss_date)', $today);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_sale_value');
        $query = $this->db->get('livestock_sale_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getTodayGrandTotalAmount($today, $column)
    {
        $this->db->where('lsss_status', 1);
        $this->db->where('DATE(lsss_date)', $today);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_sale_summary');
        $query = $this->db->get('livestock_sale_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    /* ==================== Total Sale ==================== */
    function getTotalSaleQuantity($livestock_id, $column)
    {
        $this->db->JOIN('livestock_sale_summary', 'livestock_sale_summary.lsss_id = livestock_sale_value.lssv_lsss_id');
        $this->db->where('lsss_status', 1);
        $this->db->where('lssv_status', 1);
        $this->db->where('lssv_ls_id', $livestock_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_sale_value');
        $query = $this->db->get('livestock_sale_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getTotalLivestockSaleQuantity($column)
    {
        $this->db->JOIN('livestock_sale_summary', 'livestock_sale_summary.lsss_id = livestock_sale_value.lssv_lsss_id');
        $this->db->where('lsss_status', 1);
        $this->db->where('lssv_status', 1);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_sale_value');
        $query = $this->db->get('livestock_sale_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getTotalGrandTotalAmount($column)
    {
        $this->db->where('lsss_status', 1);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_sale_summary');
        $query = $this->db->get('livestock_sale_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }


    /* ==================== Year Month Wise Sale ==================== */
    function getYearMonthWiseSaleQuantity($year, $month, $livestock_id, $column)
    {
        $this->db->JOIN('livestock_sale_summary', 'livestock_sale_summary.lsss_id = livestock_sale_value.lssv_lsss_id');
        $this->db->where('lsss_status', 1);
        $this->db->where('lssv_status', 1);
        $this->db->where('YEAR(lsss_date)', $year);
        $this->db->where('MONTH(lsss_date)', $month);
        $this->db->where('lssv_ls_id', $livestock_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_sale_value');
        $query = $this->db->get('livestock_sale_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getYearMonthWiseSaleAmount($year, $month, $column)
    {
        $this->db->where('lsss_status', 1);
        $this->db->where('YEAR(lsss_date)', $year);
        $this->db->where('MONTH(lsss_date)', $month);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_sale_summary');
        $query = $this->db->get('livestock_sale_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }


    /* ==================== Start date, end date wise filtering ==================== */
    function getStartDateEndDateWiseSaleQuantity($start_date, $end_date, $livestock_id, $column)
    {
        $this->db->JOIN('livestock_sale_summary', 'livestock_sale_summary.lsss_id = livestock_sale_value.lssv_lsss_id');
        $this->db->where('lsss_status', 1);
        $this->db->where('lssv_status', 1);
        $this->db->where('DATE(lsss_date)>=', $start_date);
        $this->db->where('DATE(lsss_date)<=', $end_date);
        $this->db->where('lssv_ls_id', $livestock_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_sale_value');
        $query = $this->db->get('livestock_sale_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getStartDateEndDateWiseSaleAmount($start_date, $end_date, $column)
    {
        $this->db->where('lsss_status', 1);
        $this->db->where('DATE(lsss_date)>=', $start_date);
        $this->db->where('DATE(lsss_date)<=', $end_date);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_sale_summary');
        $query = $this->db->get('livestock_sale_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getStartDateEndDateSaleBill($start_date, $end_date, $column)
    {
        $this->db->where('lsss_status', 1);
        $this->db->where('DATE(lsss_date)>=', $start_date);
        $this->db->where('DATE(lsss_date)<=', $end_date);
        $this->db->select("COUNT($column) AS total");
        $this->scope_tenant('livestock_sale_summary');
        $query = $this->db->count_all_results('livestock_sale_summary');
        return $query;
    }

    function getYearWiseSaleQuantity($year, $month, $column)
    {
        $this->db->JOIN('livestock_sale_summary', 'livestock_sale_summary.lsss_id = livestock_sale_value.lssv_lsss_id');
        $this->db->where('lsss_status', 1);
        $this->db->where('lssv_status', 1);
        $this->db->where('YEAR(lsss_date)', $year);
        $this->db->where('MONTH(lsss_date)', $month);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_sale_value');
        $query = $this->db->get('livestock_sale_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    /* ==================== Shed Batch Wise ==================== */
    function getShedAndBatchWiseSale($shed_id, $batch_id, $column)
    {
        $this->db->JOIN('livestock_sale_summary', 'livestock_sale_summary.lsss_id = livestock_sale_value.lssv_lsss_id');
        $this->db->where('lsss_status', 1);
        $this->db->where('lssv_status', 1);
        $this->db->where('lssv_shed_id', $shed_id);
        $this->db->where('lssv_batch_id', $batch_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_sale_value');
        $query = $this->db->get('livestock_sale_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }





    /* ============================= Death Reports ============================= */
    /* ==================== Today death quantity ==================== */
    function getTodayDeath($today, $ls_id, $column)
    {
        $this->db->where('ld_status', 1);
        $this->db->where('DATE(ld_death_date)', $today);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_death_quantity');
        $query = $this->db->get('livestock_death_quantity');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    /* ==================== Year Month Wise Sale ==================== */
    function getYearMonthWiseDeath($year, $month, $column)
    {
        $this->db->where('ld_status', 1);
        $this->db->where('YEAR(ld_death_date)', $year);
        $this->db->where('MONTH(ld_death_date)', $month);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_death_quantity');
        $query = $this->db->get('livestock_death_quantity');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    /* ==================== Year Wise Death Reports ==================== */
    function getYearWiseDeath($year, $column)
    {
        $this->db->where('ld_status', 1);
        $this->db->where('YEAR(ld_death_date)', $year);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_death_quantity');
        $query = $this->db->get('livestock_death_quantity');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }


    /* ==================== Total Death ==================== */
    function getTotalDeath($column)
    {
        $this->db->where('ld_status', 1);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_death_quantity');
        $query = $this->db->get('livestock_death_quantity');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    /* ==================== Start date, end date wise filtering ==================== */
    function getStartDateEndDateWiseDeath($start_date, $end_date, $column)
    {
        $this->db->where('ld_status', 1);
        $this->db->where('DATE(ld_created_at)>=', $start_date);
        $this->db->where('DATE(ld_created_at)<=', $end_date);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('livestock_death_quantity');
        $query = $this->db->get('livestock_death_quantity');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }






    /* ============================= Food Reports ============================= */
    /* ==================== Today Food purchase, waste, distribute, Amount ==================== */
    function getTodayFoodWisePurchaseQuantity($today, $food_id, $column)
    {
        $this->db->JOIN('food_purchase_summary', 'food_purchase_summary.fdps_id = food_purchase_value.fdpv_fdps_id');
        $this->db->where('fdpv_status', 1);
        $this->db->where('fdps_status', 1);
        $this->db->where('DATE(fdps_date)', $today);
        $this->db->where('fdpv_fd_id', $food_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_purchase_value');
        $query = $this->db->get('food_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getTodayFoodWisePurchaseAmount($today, $food_id, $column)
    {
        $this->db->JOIN('food_purchase_summary', 'food_purchase_summary.fdps_id = food_purchase_value.fdpv_fdps_id');
        $this->db->where('fdpv_status', 1);
        $this->db->where('fdps_status', 1);
        $this->db->where('DATE(fdps_date)', $today);
        $this->db->where('fdpv_fd_id', $food_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_purchase_value');
        $query = $this->db->get('food_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getTodayFoodWiseDistributeQuantity($today, $food_id)
    {
        $this->db->JOIN('food_distributed_summary', 'food_distributed_summary.fdds_id = food_distributed_value.fddv_fdds_id');
        $this->db->where('fdds_status', 1);
        $this->db->where('fddv_status', 1);
        $this->db->where('DATE(fdds_date)', $today);
        $this->db->where('fdds_fd_id', $food_id);
        $this->db->select("SUM(fddv_distributed_quantity) AS total");
        $this->scope_tenant('food_distributed_value');
        $query = $this->db->get('food_distributed_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getTodayFoodWiseWasteQuantity($today, $food_id)
    {
        $this->db->where('fdw_status', 1);
        $this->db->where('DATE(fdw_created_at)', $today);
        $this->db->where('fdw_fd_id', $food_id);
        $this->db->select("SUM(fdw_quantity) AS total");
        $query = $this->db->get('food_wasted');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    /* ==================== Total Food purchase, waste, distribute, Amount ==================== */
    function getTotalFoodWisePurchaseQuantity($food_id, $column)
    {
        $this->db->JOIN('food_purchase_summary', 'food_purchase_summary.fdps_id = food_purchase_value.fdpv_fdps_id');
        $this->db->where('fdpv_status', 1);
        $this->db->where('fdps_status', 1);
        $this->db->where('fdpv_fd_id', $food_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_purchase_value');
        $query = $this->db->get('food_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getTotalFoodWisePurchaseAmount($food_id, $column)
    {
        $this->db->JOIN('food_purchase_summary', 'food_purchase_summary.fdps_id = food_purchase_value.fdpv_fdps_id');
        $this->db->where('fdpv_status', 1);
        $this->db->where('fdps_status', 1);
        $this->db->where('fdpv_fd_id', $food_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_purchase_value');
        $query = $this->db->get('food_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getTotalFoodWiseDistributeQuantity($food_id)
    {
        $this->db->JOIN('food_distributed_summary', 'food_distributed_summary.fdds_id = food_distributed_value.fddv_fdds_id');
        $this->db->where('fdds_status', 1);
        $this->db->where('fddv_status', 1);
        $this->db->where('fdds_fd_id', $food_id);
        $this->db->select("SUM(fddv_distributed_quantity) AS total");
        $this->scope_tenant('food_distributed_value');
        $query = $this->db->get('food_distributed_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getTotalFoodWiseWasteQuantity($food_id)
    {
        $this->db->where('fdw_status', 1);
        $this->db->where('fdw_fd_id', $food_id);
        $this->db->select("SUM(fdw_quantity) AS total");
        $query = $this->db->get('food_wasted');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    /* ==================== Year Month Wise food purchase ==================== */
    function getYearMonthWiseFoodPurchase($year, $month, $column)
    {
        $this->db->where('fdps_status', 1);
        $this->db->where('YEAR(fdps_date)', $year);
        $this->db->where('MONTH(fdps_date)', $month);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_purchase_summary');
        $query = $this->db->get('food_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    /* ==================== Year Month Wise food Distribute to shed ==================== */
    function getYearMonthWiseFoodDistributeQuantity($year, $month, $food_id, $column)
    {
        $this->db->JOIN('food_distributed_summary', 'food_distributed_summary.fdds_id = food_distributed_value.fddv_id');
        $this->db->where('fdds_status', 1);
        $this->db->where('fddv_status', 1);
        $this->db->where('YEAR(fdds_date)', $year);
        $this->db->where('MONTH(fdds_date)', $month);
        $this->db->where('fdds_fd_id', $food_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_distributed_value');
        $query = $this->db->get('food_distributed_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    /* ==================== Year Month Wise Wasted Food ==================== */
    function getYearMonthWiseWastedFood($year, $month, $food_id, $column)
    {
        $this->db->where('fdw_status', 1);
        $this->db->where('YEAR(fdw_created_at)', $year);
        $this->db->where('MONTH(fdw_created_at)', $month);
        $this->db->where('fdw_fd_id', $food_id);
        $this->db->select("SUM($column) AS total");
        $query = $this->db->get('food_wasted');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    /* ==================== Year Wise food purchase ==================== */
    function getYearWiseFoodPurchase($year, $column)
    {
        $this->db->where('fdps_status', 1);
        $this->db->where('YEAR(fdps_date)', $year);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_purchase_summary');
        $query = $this->db->get('food_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    /* ==================== Year Month Wise food purchase by food id ==================== */
    function getYearMonthWiseFoodPurchaseQuantity($year, $month, $food_id, $column)
    {
        $this->db->JOIN('food_purchase_summary', 'food_purchase_summary.fdps_id = food_purchase_value.fdpv_fdps_id');
        $this->db->where('fdpv_status', 1);
        $this->db->where('fdps_status', 1);
        $this->db->where('YEAR(fdps_date)', $year);
        $this->db->where('MONTH(fdps_date)', $month);
        $this->db->where('fdpv_fd_id', $food_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_purchase_value');
        $query = $this->db->get('food_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    /* ==================== Year Month Wise Sale Bill Count ==================== */
    function getYearMonthWiseFoodPurchaseBill($year, $month, $column)
    {
        $this->db->where('fdps_status', 1);
        $this->db->where('YEAR(fdps_date)', $year);
        $this->db->where('MONTH(fdps_date)', $month);
        $this->db->select("COUNT($column) AS total");
        $this->scope_tenant('food_purchase_summary');
        $query = $this->db->count_all_results('food_purchase_summary');
        return $query;
    }

    /* ==================== Month Wise Sale Bill Count ==================== */
    function getYearWiseFoodPurchaseBill($year, $column)
    {
        $this->db->where('fdps_status', 1);
        $this->db->where('YEAR(fdps_date)', $year);
        $this->db->select("COUNT($column) AS total");
        $this->scope_tenant('food_purchase_summary');
        $query = $this->db->count_all_results('food_purchase_summary');
        return $query;
    }

    /* ==================== Start date, end date wise filtering ==================== */
    function getStartDateEndDateWiseFoodPurchase($start_date, $end_date, $food_id, $column)
    {
        $this->db->JOIN('food_purchase_summary', 'food_purchase_summary.fdps_id = food_purchase_value.fdpv_fdps_id');
        $this->db->where('fdpv_status', 1);
        $this->db->where('fdps_status', 1);
        $this->db->where('DATE(fdps_date)>=', $start_date);
        $this->db->where('DATE(fdps_date)<=', $end_date);
        $this->db->where('fdpv_fd_id', $food_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_purchase_value');
        $query = $this->db->get('food_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getStartDateEndDateWiseFoodDistribute($start_date, $end_date, $food_id, $column)
    {
        $this->db->JOIN('food_distributed_summary', 'food_distributed_summary.fdds_id = food_distributed_value.fddv_id');
        $this->db->where('fdds_status', 1);
        $this->db->where('fddv_status', 1);
        $this->db->where('DATE(fdds_date)>=', $start_date);
        $this->db->where('DATE(fdds_date)<=', $end_date);
        $this->db->where('fdds_fd_id', $food_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_distributed_value');
        $query = $this->db->get('food_distributed_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getStartDateEndDateWiseWastedFood($start_date, $end_date, $food_id, $column)
    {
        $this->db->where('fdw_status', 1);
        $this->db->where('DATE(fdw_created_at)>=', $start_date);
        $this->db->where('DATE(fdw_created_at)<=', $end_date);
        $this->db->where('fdw_fd_id', $food_id);
        $this->db->select("SUM($column) AS total");
        $query = $this->db->get('food_wasted');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getStartDateEndDateWiseFoodPurchaseTotalAmount($start_date, $end_date, $column)
    {
        $this->db->where('fdps_status', 1);
        $this->db->where('DATE(fdps_date)>=', $start_date);
        $this->db->where('DATE(fdps_date)<=', $end_date);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_purchase_summary');
        $query = $this->db->get('food_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }


    /* ============================= Vaccine Reports ============================= */
    // Today Report
    function getTodayVaccinePurchase($today, $vacc_id, $column)
    {
        $this->db->JOIN('vaccine_purchase_summary', 'vaccine_purchase_summary.vps_id = vaccine_purchase_value.vpv_vps_id');
        $this->db->where('vps_status', 1);
        $this->db->where('vpv_status', 1);
        $this->db->where('vpv_vcc_id', $vacc_id);
        $this->db->where('DATE(vps_date)', $today);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_purchase_value');
        $query = $this->db->get('vaccine_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getTodayVaccineUsed($today, $vacc_id, $column)
    {
        $this->db->where('vds_status', 1);
        $this->db->where('vds_vcc_id', $vacc_id);
        $this->db->where('DATE(vds_created_at)', $today);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_dose_status');
        $query = $this->db->get('vaccine_dose_status');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getTodayVaccineWaste($today, $vacc_id, $column)
    {
        $this->db->where('vccw_status', 1);
        $this->db->where('vccw_vcc_id', $vacc_id);
        $this->db->where('DATE(vccw_created_at)', $today);
        $this->db->select("SUM($column) AS total");
        $query = $this->db->get('vaccine_wasted');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    // Yearly - Monthly
    function getYearMonthWiseVaccinePurchase($year, $month, $column)
    {
        $this->db->where('vps_status', 1);
        $this->db->where('YEAR(vps_date)', $year);
        $this->db->where('MONTH(vps_date)', $month);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_purchase_summary');
        $query = $this->db->get('vaccine_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getYearMonthWiseVaccinePurchaseBill($year, $month, $column)
    {
        $this->db->where('vps_status', 1);
        $this->db->where('YEAR(vps_date)', $year);
        $this->db->where('MONTH(vps_date)', $month);
        $this->db->select("COUNT($column) AS total");
        $this->scope_tenant('vaccine_purchase_summary');
        $query = $this->db->count_all_results('vaccine_purchase_summary');
        return $query;
    }

    // Yearly
    function getYearWiseVaccinePurchase($year, $column)
    {
        $this->db->where('vps_status', 1);
        $this->db->where('YEAR(vps_date)', $year);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_purchase_summary');
        $query = $this->db->get('vaccine_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getYearWiseVaccinePurchaseBill($year, $column)
    {
        $this->db->where('vps_status', 1);
        $this->db->where('YEAR(vps_date)', $year);
        $this->db->select("COUNT($column) AS total");
        $this->scope_tenant('vaccine_purchase_summary');
        $query = $this->db->count_all_results('vaccine_purchase_summary');
        return $query;
    }

    // Monthly
    function getMonthWiseVaccinePurchaseQuantity($year, $month, $vacc_id, $column)
    {
        $this->db->JOIN('vaccine_purchase_summary', 'vaccine_purchase_summary.vps_id = vaccine_purchase_value.vpv_vps_id');
        $this->db->where('vps_status', 1);
        $this->db->where('vpv_status', 1);
        $this->db->where('YEAR(vps_date)', $year);
        $this->db->where('MONTH(vps_date)', $month);
        $this->db->where('vpv_vcc_id', $vacc_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_purchase_value');
        $query = $this->db->get('vaccine_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getMonthWiseVaccinePurchaseAmount($year, $month, $column)
    {
        $this->db->where('vps_status', 1);
        $this->db->where('YEAR(vps_date)', $year);
        $this->db->where('MONTH(vps_date)', $month);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_purchase_summary');
        $query = $this->db->get('vaccine_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getMonthWiseVaccineUsed($year, $month, $vacc_id, $column)
    {
        $this->db->where('vds_status', 1);
        $this->db->where('YEAR(vds_created_at)', $year);
        $this->db->where('MONTH(vds_created_at)', $month);
        $this->db->where('vds_vcc_id', $vacc_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_dose_status');
        $query = $this->db->get('vaccine_dose_status');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getMonthWiseVaccineWaste($year, $month, $vacc_id, $column)
    {
        $this->db->where('vccw_status', 1);
        $this->db->where('YEAR(vccw_created_at)', $year);
        $this->db->where('MONTH(vccw_created_at)', $month);
        $this->db->where('vccw_vcc_id', $vacc_id);
        $this->db->select("SUM($column) AS total");
        $query = $this->db->get('vaccine_wasted');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    // Date to date reporting
    function getStartDateEndDateWiseVaccinePurchaseQuantity($start_date, $end_date, $vacc_id, $column)
    {
        $this->db->JOIN('vaccine_purchase_summary', 'vaccine_purchase_summary.vps_id = vaccine_purchase_value.vpv_vps_id');
        $this->db->where('vps_status', 1);
        $this->db->where('vpv_status', 1);
        $this->db->where('DATE(vps_date)>=', $start_date);
        $this->db->where('DATE(vps_date)<=', $end_date);
        $this->db->where('vpv_vcc_id', $vacc_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_purchase_value');
        $query = $this->db->get('vaccine_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getStartDateEndDateWiseVaccinePurchaseAmount($start_date, $end_date, $vacc_id, $column)
    {
        $this->db->JOIN('vaccine_purchase_summary', 'vaccine_purchase_summary.vps_id = vaccine_purchase_value.vpv_vps_id');
        $this->db->where('vps_status', 1);
        $this->db->where('vpv_status', 1);
        $this->db->where('DATE(vps_date)>=', $start_date);
        $this->db->where('DATE(vps_date)<=', $end_date);
        $this->db->where('vpv_vcc_id', $vacc_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_purchase_value');
        $query = $this->db->get('vaccine_purchase_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getStartDateEndDateWiseVaccineUsed($start_date, $end_date, $vacc_id, $column)
    {
        $this->db->where('vds_status', 1);
        $this->db->where('DATE(vds_created_at)>=', $start_date);
        $this->db->where('DATE(vds_created_at)<=', $end_date);
        $this->db->where('vds_vcc_id', $vacc_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_dose_status');
        $query = $this->db->get('vaccine_dose_status');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getStartDateEndDateWiseVaccineWaste($start_date, $end_date, $vacc_id, $column)
    {
        $this->db->where('vccw_status', 1);
        $this->db->where('DATE(vccw_created_at)>=', $start_date);
        $this->db->where('DATE(vccw_created_at)<=', $end_date);
        $this->db->where('vccw_vcc_id', $vacc_id);
        $this->db->select("SUM($column) AS total");
        $query = $this->db->get('vaccine_wasted');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getStartDateEndDateWiseVaccinePurchaseAmountTotal($start_date, $end_date, $column)
    {
        $this->db->where('vps_status', 1);
        $this->db->where('YEAR(vps_date)>=', $start_date);
        $this->db->where('MONTH(vps_date)<=', $end_date);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_purchase_summary');
        $query = $this->db->get('vaccine_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    /* ============================= Production Reports ============================= */
    // Total
    function getProductWiseTotalAmount($pr_id, $column)
    {
        $this->db->where('prsv_status', 1);
        $this->db->where('prsv_product_id', $pr_id);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_value');
        $query = $this->db->get('product_sale_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }
    function getTotalSubTotalProductSoldAmount($column)
    {
        $this->db->where('prsv_status', 1);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_value');
        $query = $this->db->get('product_sale_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    function getTotalProductSoldAmount($column)
    {
        $this->db->where('prss_status', 1);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_summary');
        $query = $this->db->get('product_sale_summary');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }


    // Today
    function getTodayProductWiseTotalAmount($today, $pr_id, $column)
    {
        $this->db->JOIN('product_sale_summary', 'product_sale_summary.prss_id = product_sale_value.prsv_prss_id');
        $this->db->where('prss_status', 1);
        $this->db->where('prsv_status', 1);
        $this->db->where('DATE(prss_date)', $today);
        $this->db->where('prsv_product_id', $pr_id);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_value');
        $query = $this->db->get('product_sale_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }
    function getTodaySubTotalProductSoldAmount($today, $column)
    {
        $this->db->JOIN('product_sale_summary', 'product_sale_summary.prss_id = product_sale_value.prsv_prss_id');
        $this->db->where('prss_status', 1);
        $this->db->where('prsv_status', 1);
        $this->db->where('DATE(prss_date)', $today);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_value');
        $query = $this->db->get('product_sale_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    function getTodayProductSoldAmount($today, $column)
    {
        $this->db->where('prss_status', 1);
        $this->db->where('DATE(prss_date)', $today);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_summary');
        $query = $this->db->get('product_sale_summary');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    function getTodayProductionQuantityByProductId($today, $product_id)
    {
        $this->db->where('prs_status', 1);
        $this->db->where('DATE(prs_date)', $today);
        $this->db->where('prs_pr_id', $product_id);
        $this->db->select('SUM(prs_production_quantity) AS total_sum');
        $this->scope_tenant('product_stock');
        $query = $this->db->get('product_stock');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    //  Sale
    function getTodayProductSoldQuantityByProductId($today, $pr_id, $column)
    {
        $this->db->JOIN('product_sale_summary', 'product_sale_summary.prss_id = product_sale_value.prsv_prss_id');
        $this->db->where('prss_status', 1);
        $this->db->where('prsv_status', 1);
        $this->db->where('prsv_product_id', $pr_id);
        $this->db->where('DATE(prss_date)', $today);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_value');
        $query = $this->db->get('product_sale_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }
    // Waste 
    function getTodayProductWastedQuantityByProductId($today, $pr_id)
    {
        $this->db->where('prw_status', 1);
        $this->db->where('DATE(prw_date)', $today);
        $this->db->where('prw_pr_id', $pr_id);
        $this->db->select('SUM(prw_wasted_quantity) AS total_sum');
        $this->scope_tenant('product_wasted');
        $query = $this->db->get('product_wasted');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }



    // Monthly calculation
    function getYearMonthWiseProductionQuantityByProductId($year, $month, $product_id)
    {
        $this->db->where('prs_status', 1);
        $this->db->where('YEAR(prs_date)', $year);
        $this->db->where('MONTH(prs_date)', $month);
        $this->db->where('prs_pr_id', $product_id);
        $this->db->select('SUM(prs_production_quantity) AS total_sum');
        $this->scope_tenant('product_stock');
        $query = $this->db->get('product_stock');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    function getYearMonthWiseProductWiseSoldBill($year, $month, $column)
    {
        $this->db->where('prss_status', 1);
        $this->db->where('YEAR(prss_date)', $year);
        $this->db->where('MONTH(prss_date)', $month);
        $this->db->select("COUNT($column) AS total");
        $this->scope_tenant('product_sale_summary');
        $query = $this->db->count_all_results('product_sale_summary');
        return $query;
    }

    function getYearMonthWiseSubTotalProductSoldAmount($year, $month, $column)
    {
        $this->db->JOIN('product_sale_summary', 'product_sale_summary.prss_id = product_sale_value.prsv_prss_id');
        $this->db->where('prss_status', 1);
        $this->db->where('prsv_status', 1);
        $this->db->where('YEAR(prss_date)', $year);
        $this->db->where('MONTH(prss_date)', $month);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_value');
        $query = $this->db->get('product_sale_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    function getYearMonthWiseProductSoldAmount($year, $month, $column)
    {
        $this->db->where('prss_status', 1);
        $this->db->where('YEAR(prss_date)', $year);
        $this->db->where('MONTH(prss_date)', $month);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_summary');
        $query = $this->db->get('product_sale_summary');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    function getYearMonthWiseProductSoldQuantityByProductId($year, $month, $pr_id, $column)
    {
        $this->db->JOIN('product_sale_summary', 'product_sale_summary.prss_id = product_sale_value.prsv_prss_id');
        $this->db->where('prss_status', 1);
        $this->db->where('prsv_status', 1);
        $this->db->where('YEAR(prss_date)', $year);
        $this->db->where('MONTH(prss_date)', $month);
        $this->db->where('prsv_product_id', $pr_id);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_value');
        $query = $this->db->get('product_sale_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    function getYearMonthWiseProductWastedQuantityByProductId($year, $month, $pr_id)
    {
        $this->db->where('prw_status', 1);
        $this->db->where('YEAR(prw_date)', $year);
        $this->db->where('MONTH(prw_date)', $month);
        $this->db->where('prw_pr_id', $pr_id);
        $this->db->select('SUM(prw_wasted_quantity) AS total_sum');
        $this->scope_tenant('product_wasted');
        $query = $this->db->get('product_wasted');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }


    // Date to date calculation
    function getDateToDateWiseProductionQuantityByProductId($start_date, $end_date, $product_id)
    {
        $this->db->where('prs_status', 1);
        $this->db->where('DATE(prs_date)>=', $start_date);
        $this->db->where('DATE(prs_date)<=', $end_date);
        $this->db->where('prs_pr_id', $product_id);
        $this->db->select('SUM(prs_production_quantity) AS total_sum');
        $this->scope_tenant('product_stock');
        $query = $this->db->get('product_stock');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    function getDateToDateWiseProductWiseTotalAmount($start_date, $end_date, $pr_id, $column)
    {
        $this->db->JOIN('product_sale_summary', 'product_sale_summary.prss_id = product_sale_value.prsv_prss_id');
        $this->db->where('prss_status', 1);
        $this->db->where('prsv_status', 1);
        $this->db->where('DATE(prss_date)>=', $start_date);
        $this->db->where('DATE(prss_date)<=', $end_date);
        $this->db->where('prsv_product_id', $pr_id);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_value');
        $query = $this->db->get('product_sale_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }
    function getDateToDateWiseSubTotalProductSoldAmount($start_date, $end_date, $column)
    {
        $this->db->JOIN('product_sale_summary', 'product_sale_summary.prss_id = product_sale_value.prsv_prss_id');
        $this->db->where('prss_status', 1);
        $this->db->where('prsv_status', 1);
        $this->db->where('DATE(prss_date)>=', $start_date);
        $this->db->where('DATE(prss_date)<=', $end_date);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_value');
        $query = $this->db->get('product_sale_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    function getDateToDateWiseProductSoldAmount($start_date, $end_date, $column)
    {
        $this->db->where('prss_status', 1);
        $this->db->where('DATE(prss_date)>=', $start_date);
        $this->db->where('DATE(prss_date)<=', $end_date);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_summary');
        $query = $this->db->get('product_sale_summary');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    function getDateToDateWiseProductSoldQuantityByProductId($start_date, $end_date, $pr_id, $column)
    {
        $this->db->JOIN('product_sale_summary', 'product_sale_summary.prss_id = product_sale_value.prsv_prss_id');
        $this->db->where('prss_status', 1);
        $this->db->where('prsv_status', 1);
        $this->db->where('DATE(prss_date)>=', $start_date);
        $this->db->where('DATE(prss_date)<=', $end_date);
        $this->db->where('prsv_product_id', $pr_id);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_value');
        $query = $this->db->get('product_sale_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    function getDateToDateWiseProductWastedQuantityByProductId($start_date, $end_date, $pr_id)
    {
        $this->db->where('prw_status', 1);
        $this->db->where('DATE(prw_date)>=', $start_date);
        $this->db->where('DATE(prw_date)<=', $end_date);
        $this->db->where('prw_pr_id', $pr_id);
        $this->db->select('SUM(prw_wasted_quantity) AS total_sum');
        $this->scope_tenant('product_wasted');
        $query = $this->db->get('product_wasted');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    // Yearly calculation
    function getYearWiseProductWiseSoldBill($year, $column)
    {
        $this->db->where('prss_status', 1);
        $this->db->where('YEAR(prss_date)', $year);
        $this->db->select("COUNT($column) AS total");
        $this->scope_tenant('product_sale_summary');
        $query = $this->db->count_all_results('product_sale_summary');
        return $query;
    }
    function getYearWiseProductSoldAmount($year, $column)
    {
        $this->db->where('prss_status', 1);
        $this->db->where('YEAR(prss_date)', $year);
        $this->db->select("SUM($column) AS total_sum");
        $this->scope_tenant('product_sale_summary');
        $query = $this->db->get('product_sale_summary');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }



    /* ============================= Supplier Reports ============================= */
    // Today - supplier wise total purchase
    function getTodaySupplierWiseLivestockPurchaseAmount($today, $supplierId)
    {
        $this->db->select('SUM(purs_grand_total) AS total');
        $this->db->where('DATE(purs_date)', $today);
        $this->db->where('purs_supp_id', $supplierId);
        $this->db->where('purs_status', 1);
        $this->scope_tenant('livestock_purchase_summary');
        $query = $this->db->get('livestock_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getTodaySupplierWiseFoodPurchaseAmount($today, $supplierId)
    {
        $this->db->select('SUM(fdps_grand_total) AS total');
        $this->db->where('DATE(fdps_date)', $today);
        $this->db->where('fdps_s_id', $supplierId);
        $this->db->where('fdps_status', 1);
        $this->scope_tenant('food_purchase_summary');
        $query = $this->db->get('food_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getTodaySupplierWiseVaccinePurchaseAmount($today, $supplierId)
    {
        $this->db->select('SUM(vps_grand_total) AS total');
        $this->db->where('DATE(vps_date)', $today);
        $this->db->where('vps_s_id', $supplierId);
        $this->db->where('vps_status', 1);
        $this->scope_tenant('vaccine_purchase_summary');
        $query = $this->db->get('vaccine_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getTodaySupplierWiseTotalPaidAmount($today, $supplierId)
    {
        $this->db->select('SUM(sp_payment_amount) AS total');
        $this->db->where('DATE(sp_date)', $today);
        $this->db->where('sp_s_id', $supplierId);
        $this->db->where('sp_status', 1);
        $this->scope_tenant('supplier_payment');
        $query = $this->db->get('supplier_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getTodayLivestockPurchasedAmount($today)
    {
        $this->db->where('purs_status', 1);
        $this->db->where('DATE(purs_date)', $today);
        $this->db->select('SUM(purs_grand_total) AS total_sum');
        $this->scope_tenant('livestock_purchase_summary');
        $query = $this->db->get('livestock_purchase_summary');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }

    function getTodayTotalFoodPurchaseAmount($today, $column)
    {
        $this->db->where('fdps_status', 1);
        $this->db->where('DATE(fdps_date)', $today);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_purchase_summary');
        $query = $this->db->get('food_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getTodayTotalVaccinePurchase($today, $column)
    {
        $this->db->where('vps_status', 1);
        $this->db->where('DATE(vps_date)', $today);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_purchase_summary');
        $query = $this->db->get('vaccine_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    // Year Month Wise Payment
    function getYearMonthSupplierWiseLivestockPurchaseAmount($year, $month, $supplierId)
    {
        $this->db->select('SUM(purs_grand_total) AS total');
        $this->db->where('YEAR(purs_date)', $year);
        $this->db->where('MONTH(purs_date)', $month);
        $this->db->where('purs_supp_id', $supplierId);
        $this->db->where('purs_status', 1);
        $this->scope_tenant('livestock_purchase_summary');
        $query = $this->db->get('livestock_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getYearMonthSupplierWiseFoodPurchaseAmount($year, $month, $supplierId)
    {
        $this->db->select('SUM(fdps_grand_total) AS total');
        $this->db->where('YEAR(fdps_date)', $year);
        $this->db->where('MONTH(fdps_date)', $month);
        $this->db->where('fdps_s_id', $supplierId);
        $this->db->where('fdps_status', 1);
        $this->scope_tenant('food_purchase_summary');
        $query = $this->db->get('food_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getYearMonthSupplierWiseVaccinePurchaseAmount($year, $month, $supplierId)
    {
        $this->db->select('SUM(vps_grand_total) AS total');
        $this->db->where('YEAR(vps_date)', $year);
        $this->db->where('MONTH(vps_date)', $month);
        $this->db->where('vps_s_id', $supplierId);
        $this->db->where('vps_status', 1);
        $this->scope_tenant('vaccine_purchase_summary');
        $query = $this->db->get('vaccine_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getYearMonthSupplierWiseTotalPaidAmount($year, $month, $supplierId)
    {
        $this->db->select('SUM(sp_payment_amount) AS total');
        $this->db->where('YEAR(sp_date)', $year);
        $this->db->where('MONTH(sp_date)', $month);
        $this->db->where('sp_s_id', $supplierId);
        $this->db->where('sp_status', 1);
        $this->scope_tenant('supplier_payment');
        $query = $this->db->get('supplier_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    // Year Month Wise Total Payment
    function getYearMonthWiseLivestockPurchaseAmount($year, $month)
    {
        $this->db->select('SUM(purs_grand_total) AS total');
        $this->db->where('YEAR(purs_date)', $year);
        $this->db->where('MONTH(purs_date)', $month);
        $this->db->where('purs_status', 1);
        $this->scope_tenant('livestock_purchase_summary');
        $query = $this->db->get('livestock_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getYearMonthWiseFoodPurchaseAmount($year, $month)
    {
        $this->db->select('SUM(fdps_grand_total) AS total');
        $this->db->where('YEAR(fdps_date)', $year);
        $this->db->where('MONTH(fdps_date)', $month);
        $this->db->where('fdps_status', 1);
        $this->scope_tenant('food_purchase_summary');
        $query = $this->db->get('food_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getYearMonthWiseVaccinePurchaseAmount($year, $month)
    {
        $this->db->select('SUM(vps_grand_total) AS total');
        $this->db->where('YEAR(vps_date)', $year);
        $this->db->where('MONTH(vps_date)', $month);
        $this->db->where('vps_status', 1);
        $this->scope_tenant('vaccine_purchase_summary');
        $query = $this->db->get('vaccine_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getYearMonthWiseTotalPaidAmount($year, $month)
    {
        $this->db->select('SUM(sp_payment_amount) AS total');
        $this->db->where('YEAR(sp_date)', $year);
        $this->db->where('MONTH(sp_date)', $month);
        $this->db->where('sp_status', 1);
        $this->scope_tenant('supplier_payment');
        $query = $this->db->get('supplier_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    // Date to Date
    // Year Month Wise Payment
    function getDateToDateSupplierWiseLivestockPurchaseAmount($start_date, $end_date, $supplierId)
    {
        $this->db->select('SUM(purs_grand_total) AS total');
        $this->db->where('DATE(purs_date)>=', $start_date);
        $this->db->where('DATE(purs_date)<=', $end_date);
        $this->db->where('purs_supp_id', $supplierId);
        $this->db->where('purs_status', 1);
        $this->scope_tenant('livestock_purchase_summary');
        $query = $this->db->get('livestock_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getDateToDateSupplierWiseFoodPurchaseAmount($start_date, $end_date, $supplierId)
    {
        $this->db->select('SUM(fdps_grand_total) AS total');
        $this->db->where('DATE(fdps_date)>=', $start_date);
        $this->db->where('DATE(fdps_date)<=', $end_date);
        $this->db->where('fdps_s_id', $supplierId);
        $this->db->where('fdps_status', 1);
        $this->scope_tenant('food_purchase_summary');
        $query = $this->db->get('food_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getDateToDateSupplierWiseVaccinePurchaseAmount($start_date, $end_date, $supplierId)
    {
        $this->db->select('SUM(vps_grand_total) AS total');
        $this->db->where('DATE(vps_date)>=', $start_date);
        $this->db->where('DATE(vps_date)<=', $end_date);
        $this->db->where('vps_s_id', $supplierId);
        $this->db->where('vps_status', 1);
        $this->scope_tenant('vaccine_purchase_summary');
        $query = $this->db->get('vaccine_purchase_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getDateToDateSupplierWiseTotalPaidAmount($start_date, $end_date, $supplierId)
    {
        $this->db->select('SUM(sp_payment_amount) AS total');
        $this->db->where('DATE(sp_date)>=', $start_date);
        $this->db->where('DATE(sp_date)<=', $end_date);
        $this->db->where('sp_s_id', $supplierId);
        $this->db->where('sp_status', 1);
        $this->scope_tenant('supplier_payment');
        $query = $this->db->get('supplier_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }


    /* ============================= Client Reports ============================= */
    // Today - supplier wise total purchase
    function getTodayClientWiseLivestockSaleAmount($today, $client_id)
    {
        $this->db->select('SUM(lsss_grand_total) AS total');
        $this->db->where('DATE(lsss_date)', $today);
        $this->db->where('lsss_c_id', $client_id);
        $this->db->where('lsss_status', 1);
        $this->scope_tenant('livestock_sale_summary');
        $query = $this->db->get('livestock_sale_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getTodayClientWiseProductSaleAmount($today, $client_id)
    {
        $this->db->select('SUM(prss_grand_total) AS total');
        $this->db->where('DATE(prss_date)', $today);
        $this->db->where('prss_c_id', $client_id);
        $this->db->where('prss_status', 1);
        $this->scope_tenant('product_sale_summary');
        $query = $this->db->get('product_sale_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getSumClientWiseTotalReceivedAndPaymentAmount($today, $clientId, $column)
    {
        $this->db->select("SUM($column) AS total");
        $this->db->where('DATE(cp_date)', $today);
        $this->db->where('cp_c_id', $clientId);
        $this->db->where('cp_status', 1);
        $this->scope_tenant('client_payment');
        $query = $this->db->get('client_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    // Year Month Wise Payment
    function getYearMonthClientWiseLivestockSaleAmount($year, $month,  $client_id)
    {
        $this->db->select('SUM(lsss_grand_total) AS total');
        $this->db->where('YEAR(lsss_date)', $year);
        $this->db->where('MONTH(lsss_date)', $month);
        $this->db->where('lsss_c_id', $client_id);
        $this->db->where('lsss_status', 1);
        $this->scope_tenant('livestock_sale_summary');
        $query = $this->db->get('livestock_sale_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getYearMonthClientWiseProductSaleAmount($year, $month,  $client_id)
    {
        $this->db->select('SUM(prss_grand_total) AS total');
        $this->db->where('YEAR(prss_date)', $year);
        $this->db->where('MONTH(prss_date)', $month);
        $this->db->where('prss_c_id', $client_id);
        $this->db->where('prss_status', 1);
        $this->scope_tenant('product_sale_summary');
        $query = $this->db->get('product_sale_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getYearMonthClientWiseTotalReceivedAndPaymentAmount($year, $month, $clientId, $column)
    {
        $this->db->select("SUM($column) AS total");
        $this->db->where('YEAR(cp_date)', $year);
        $this->db->where('MONTH(cp_date)', $month);
        $this->db->where('cp_c_id', $clientId);
        $this->db->where('cp_status', 1);
        $this->scope_tenant('client_payment');
        $query = $this->db->get('client_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    // Date to date payment
    function getDateToDateClientWiseLivestockSaleAmount($start_date, $end_date,  $client_id)
    {
        $this->db->select('SUM(lsss_grand_total) AS total');
        $this->db->where('DATE(lsss_date)>=', $start_date);
        $this->db->where('DATE(lsss_date)<=', $end_date);
        $this->db->where('lsss_c_id', $client_id);
        $this->db->where('lsss_status', 1);
        $this->scope_tenant('livestock_sale_summary');
        $query = $this->db->get('livestock_sale_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getDateToDateClientWiseProductSaleAmount($start_date, $end_date,  $client_id)
    {
        $this->db->select('SUM(prss_grand_total) AS total');
        $this->db->where('DATE(prss_date)>=', $start_date);
        $this->db->where('DATE(prss_date)<=', $end_date);
        $this->db->where('prss_c_id', $client_id);
        $this->db->where('prss_status', 1);
        $this->scope_tenant('product_sale_summary');
        $query = $this->db->get('product_sale_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getDateToDateClientWiseTotalReceivedAndPaymentAmount($start_date, $end_date, $clientId, $column)
    {
        $this->db->select("SUM($column) AS total");
        $this->db->where('DATE(cp_date)>=', $start_date);
        $this->db->where('DATE(cp_date)<=', $end_date);
        $this->db->where('cp_c_id', $clientId);
        $this->db->where('cp_status', 1);
        $this->scope_tenant('client_payment');
        $query = $this->db->get('client_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    //Yearly month total
    function getYearMonthWiseLivestockSaleAmount($year, $month)
    {
        $this->db->select('SUM(lsss_grand_total) AS total');
        $this->db->where('YEAR(lsss_date)', $year);
        $this->db->where('MONTH(lsss_date)', $month);
        $this->db->where('lsss_status', 1);
        $this->scope_tenant('livestock_sale_summary');
        $query = $this->db->get('livestock_sale_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getYearMonthWiseProductSaleAmount($year, $month)
    {
        $this->db->select('SUM(prss_grand_total) AS total');
        $this->db->where('YEAR(prss_date)', $year);
        $this->db->where('MONTH(prss_date)', $month);
        $this->db->where('prss_status', 1);
        $this->scope_tenant('product_sale_summary');
        $query = $this->db->get('product_sale_summary');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getYearMonthWiseTotalReceivedAndPaymentAmount($year, $month, $column)
    {
        $this->db->select("SUM($column) AS total");
        $this->db->where('YEAR(cp_date)', $year);
        $this->db->where('MONTH(cp_date)', $month);
        $this->db->where('cp_status', 1);
        $this->scope_tenant('client_payment');
        $query = $this->db->get('client_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }


    /* ============================= Staff Reports ============================= */
    // Today - staff wise total purchase
    function getTodayStaffWisePayment($today, $staff_id, $column)
    {
        $this->db->where('sfp_sf_id', $staff_id);
        $this->db->where('DATE(sfp_date)', $today);
        $this->db->select("SUM($column) AS total");
        $this->db->where('sfp_status', 1);
        $this->scope_tenant('staff_payment');
        $query = $this->db->get('staff_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    // Year Month
    function getYearMonthStaffWisePayment($year, $month, $staff_id, $column)
    {
        $this->db->where('sfp_sf_id', $staff_id);
        $this->db->where('YEAR(sfp_date)', $year);
        $this->db->where('MONTH(sfp_date)', $month);
        $this->db->select("SUM($column) AS total");
        $this->db->where('sfp_status', 1);
        $this->scope_tenant('staff_payment');
        $query = $this->db->get('staff_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getYearMonthWisePayment($year, $month, $column)
    {
        $this->db->where('YEAR(sfp_date)', $year);
        $this->db->where('MONTH(sfp_date)', $month);
        $this->db->select("SUM($column) AS total");
        $this->db->where('sfp_status', 1);
        $this->scope_tenant('staff_payment');
        $query = $this->db->get('staff_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    // Date to date
    function getDateToDateStaffWisePayment($start_date, $end_date, $staff_id, $column)
    {
        $this->db->where('sfp_sf_id', $staff_id);
        $this->db->where('DATE(sfp_date)>=', $start_date);
        $this->db->where('DATE(sfp_date)<=', $end_date);
        $this->db->select("SUM($column) AS total");
        $this->db->where('sfp_status', 1);
        $this->scope_tenant('staff_payment');
        $query = $this->db->get('staff_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    /* ============================= Expense Reports ============================= */
    // Today
    function getTodayExpenseAmount($today)
    {
        $this->db->where('DATE(ex_date)', $today);
        $this->db->select('SUM(ex_amount) AS total');
        $this->db->where('ex_status', 1);
        $this->scope_tenant('expense');
        $query = $this->db->get('expense');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getTodayExpensePaidAmount($today)
    {
        $this->db->where('DATE(exp_date)', $today);
        $this->db->select('SUM(exp_paid_amount) AS total');
        $this->db->where('exp_status', 1);
        $this->scope_tenant('expense_payment');
        $query = $this->db->get('expense_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    // Year Month
    function getYearMonthExpenseAmount($year, $month)
    {
        $this->db->where('YEAR(ex_date)', $year);
        $this->db->where('MONTH(ex_date)', $month);
        $this->db->select('SUM(ex_amount) AS total');
        $this->db->where('ex_status', 1);
        $this->scope_tenant('expense');
        $query = $this->db->get('expense');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getYearMonthExpensePaidAmount($year, $month)
    {
        $this->db->where('YEAR(exp_date)', $year);
        $this->db->where('MONTH(exp_date)', $month);
        $this->db->select('SUM(exp_paid_amount) AS total');
        $this->db->where('exp_status', 1);
        $this->scope_tenant('expense_payment');
        $query = $this->db->get('expense_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    // Date to date
    function getDateToDateExpenseAmount($start_date, $end_date)
    {
        $this->db->where('DATE(ex_date)>=', $start_date);
        $this->db->where('DATE(ex_date)<=', $end_date);
        $this->db->select('SUM(ex_amount) AS total');
        $this->db->where('ex_status', 1);
        $this->scope_tenant('expense');
        $query = $this->db->get('expense');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getDateToDateExpensePaidAmount($start_date, $end_date)
    {
        $this->db->where('DATE(exp_date)>=', $start_date);
        $this->db->where('DATE(exp_date)<=', $end_date);
        $this->db->select('SUM(exp_paid_amount) AS total');
        $this->db->where('exp_status', 1);
        $this->scope_tenant('expense_payment');
        $query = $this->db->get('expense_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    /* ============================= Expense Category Reports ============================= */
    // Total
    function getTotalCategoryWiseExpenseAmount($category_id)
    {
        $this->db->where('ex_exc_id', $category_id);
        $this->db->select('SUM(ex_amount) AS total');
        $this->db->where('ex_status', 1);
        $this->scope_tenant('expense');
        $query = $this->db->get('expense');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getTotalCategoryWiseExpensePaidAmount($category_id)
    {
        $this->db->JOIN('expense', 'expense.ex_id = expense_payment.exp_ex_id');
        $this->db->where('ex_exc_id', $category_id);
        $this->db->where('ex_status', 1);
        $this->db->where('exp_status', 1);
        $this->db->select('SUM(exp_paid_amount) AS total');
        $this->scope_tenant('expense_payment');
        $query = $this->db->get('expense_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    // Today
    function getTodayCategoryWiseExpenseAmount($today, $category_id)
    {
        $this->db->where('ex_exc_id', $category_id);
        $this->db->where('DATE(ex_date)', $today);
        $this->db->select('SUM(ex_amount) AS total');
        $this->db->where('ex_status', 1);
        $this->scope_tenant('expense');
        $query = $this->db->get('expense');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getTodayCategoryWiseExpensePaidAmount($today, $category_id)
    {
        $this->db->JOIN('expense', 'expense.ex_id = expense_payment.exp_ex_id');
        $this->db->where('ex_exc_id', $category_id);
        $this->db->where('DATE(exp_date)', $today);
        $this->db->where('ex_status', 1);
        $this->db->where('exp_status', 1);
        $this->db->select('SUM(exp_paid_amount) AS total');
        $this->scope_tenant('expense_payment');
        $query = $this->db->get('expense_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    // Year Month
    function getYearMonthCategoryWiseExpenseAmount($year, $month, $category_id)
    {
        $this->db->where('YEAR(ex_date)', $year);
        $this->db->where('MONTH(ex_date)', $month);
        $this->db->where('ex_exc_id', $category_id);
        $this->db->select('SUM(ex_amount) AS total');
        $this->db->where('ex_status', 1);
        $this->scope_tenant('expense');
        $query = $this->db->get('expense');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getYearMonthCategoryWiseExpensePaidAmount($year, $month, $category_id)
    {
        $this->db->JOIN('expense', 'expense.ex_id = expense_payment.exp_ex_id');
        $this->db->where('ex_exc_id', $category_id);
        $this->db->where('YEAR(exp_date)', $year);
        $this->db->where('MONTH(exp_date)', $month);
        $this->db->select('SUM(exp_paid_amount) AS total');
        $this->db->where('ex_status', 1);
        $this->db->where('exp_status', 1);
        $this->scope_tenant('expense_payment');
        $query = $this->db->get('expense_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    // Date to date
    function getDateToDateCategoryWiseExpenseAmount($start_date, $end_date, $category_id)
    {
        $this->db->where('DATE(ex_date)>=', $start_date);
        $this->db->where('DATE(ex_date)<=', $end_date);
        $this->db->where('ex_exc_id', $category_id);
        $this->db->select('SUM(ex_amount) AS total');
        $this->db->where('ex_status', 1);
        $this->scope_tenant('expense');
        $query = $this->db->get('expense');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }
    function getDateToDateCategoryWiseExpensePaidAmount($start_date, $end_date, $category_id)
    {
        $this->db->JOIN('expense', 'expense.ex_id = expense_payment.exp_ex_id');
        $this->db->where('DATE(exp_date)>=', $start_date);
        $this->db->where('DATE(exp_date)<=', $end_date);
        $this->db->where('ex_exc_id', $category_id);
        $this->db->select('SUM(exp_paid_amount) AS total');
        $this->db->where('ex_status', 1);
        $this->db->where('exp_status', 1);
        $this->scope_tenant('expense_payment');
        $query = $this->db->get('expense_payment');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    /* ============================= Batch Reports ============================= */
    // Sold Livestock
    function getLivestockSoldValueByShedAndBatchId($shed_id, $batch_id)
    {
        $this->db->where('lssv_status', 1);
        $this->db->where('lssv_shed_id', $shed_id);
        $this->db->where('lssv_batch_id', $batch_id);
        $this->scope_tenant('livestock_sale_value');
        $query = $this->db->get('livestock_sale_value');
        return $query->result();
    }
    // Food Info 
    function getAssignedFoodByShedAndBatch($shed_id, $batch_id)
    {
        $this->db->where('fdv_status', 1);
        $this->db->where('fdv_assigned_shed_id', $shed_id);
        $this->db->where('fdv_assigned_batch_id', $batch_id);
        $query = $this->db->get('food_value');
        return $query->result();
    }

    function getDistributedFoodByShedAndBatch($shed_id, $batch_id, $food_id, $column)
    {
        $this->db->JOIN('food_distributed_summary', 'food_distributed_summary.fdds_id = food_distributed_value.fddv_fdds_id');
        $this->db->where('fdds_status', 1);
        $this->db->where('fddv_status', 1);
        $this->db->where('fddv_shed_id', $shed_id);
        $this->db->where('fddv_batch_id', $batch_id);
        $this->db->where('fdds_fd_id', $food_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_distributed_value');
        $query = $this->db->get('food_distributed_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    // Vaccine Info 
    function getVaccinatedQuantityByVaccineShedBatchId($shed_id, $batch_id, $vaccine_id, $column)
    {
        $this->db->where('vds_status', 1);
        $this->db->where('vds_sh_id', $shed_id);
        $this->db->where('vds_batch_id', $batch_id);
        $this->db->where('vds_vcc_id', $vaccine_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_dose_status');
        $query = $this->db->get('vaccine_dose_status');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }


    // Product Info
    function getProductionQuantityByProductShedBatchId($shed_id, $batch_id, $product_id, $column)
    {
        $this->db->where('prs_status', 1);
        $this->db->where('prs_shed_id', $shed_id);
        $this->db->where('prs_batch_id', $batch_id);
        $this->db->where('prs_pr_id', $product_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('product_stock');
        $query = $this->db->get('product_stock');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    function getSoldQuantityByProductShedBatchId($shed_id, $batch_id, $product_id, $column)
    {
        $this->db->JOIN('product_sale_summary', 'product_sale_summary.prss_id = product_sale_value.prsv_prss_id');
        $this->db->where('lssv_shed_id', $shed_id);
        $this->db->where('lssv_batch_id', $batch_id);
        $this->db->where('prsv_product_id', $product_id);
        $this->db->where('prss_status', 1);
        $this->db->where('lssv_status', 1);
        $this->db->select("SUM($column) AS total");
        $this->db->select('SUM(lssv_quantity) AS total_sum');
        $this->scope_tenant('product_sale_value');
        $query = $this->db->get('product_sale_value');
        return ($query->row() && $query->row()->total_sum !== NULL) ? $query->row()->total_sum : 0;
    }



    /* ============================= Shed Reports ============================= */
    // Sold Livestock
    function getLivestockSoldValueByShedId($shed_id)
    {
        $this->db->where('lssv_status', 1);
        $this->db->where('lssv_shed_id', $shed_id);
        $this->scope_tenant('livestock_sale_value');
        $query = $this->db->get('livestock_sale_value');
        return $query->result();
    }

    // Food Info 
    function getAssignedFoodByShed($shed_id)
    {
        $this->db->where('fdv_status', 1);
        $this->db->where('fdv_assigned_shed_id', $shed_id);
        $query = $this->db->get('food_value');
        return $query->result();
    }


    function getDistributedFoodByShed($shed_id, $food_id, $column)
    {
        $this->db->JOIN('food_distributed_summary', 'food_distributed_summary.fdds_id = food_distributed_value.fddv_fdds_id');
        $this->db->where('fdds_status', 1);
        $this->db->where('fddv_status', 1);
        $this->db->where('fddv_shed_id', $shed_id);
        $this->db->where('fdds_fd_id', $food_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('food_distributed_value');
        $query = $this->db->get('food_distributed_value');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }

    // Vaccine Info 
    function getVaccinatedQuantityByVaccineShedId($shed_id, $vaccine_id, $column)
    {
        $this->db->where('vds_status', 1);
        $this->db->where('vds_sh_id', $shed_id);
        $this->db->where('vds_vcc_id', $vaccine_id);
        $this->db->select("SUM($column) AS total");
        $this->scope_tenant('vaccine_dose_status');
        $query = $this->db->get('vaccine_dose_status');
        return ($query->row() && $query->row()->total !== NULL) ? $query->row()->total : 0;
    }































    // End
}
