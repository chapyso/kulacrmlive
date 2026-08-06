<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expense_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* ========== Insert data ========== */
    public function insertData($table, $data)
    {
        $data = $this->prepare_tenant_data($table, $data);
        $this->db->insert($table, $data);
        $returnValue = $this->db->insert_id();
        return $returnValue;
    }

    /* ========== Update data ========== */
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

    /* ============================================== Expense Category ==================================================== */
    /* ========== Get data ========== */
    function getExpenseCategory()
    {
        $this->scope_tenant('expense_category');
        $this->db->where('exc_status', 1);
        $query = $this->db->get('expense_category');
        return $query;
    }

    /* ========== Get data By Id ========== */
    function getExpenseCategoryById($exc_id)
    {
        $this->scope_tenant('expense_category');
        $this->db->where('exc_status', 1);
        $this->db->where('exc_id', $exc_id);
        $query = $this->db->get('expense_category');
        return $query->row();
    }

    /* ============================================== Expense ==================================================== */
    /* ========== Get data ========== */
    function getExpense($from = '', $to = '')
    {
        $this->scope_tenant('expense');
        $this->db->where('ex_status', 1);
        if ($from !== '') $this->db->where('ex_date >=', $from);
        if ($to !== '')   $this->db->where('ex_date <=', $to);
        $query = $this->db->get('expense');
        return $query;
    }

    /* ========== Get data By Id ========== */
    function getExpenseById($exc_id)
    {
        $this->scope_tenant('expense');
        $this->db->where('ex_status', 1);
        $this->db->where('ex_id', $exc_id);
        $query = $this->db->get('expense');
        return $query->row();
    }

    /* ========== Get total sum of expense ========== */
    function getTotalSumOfExpense()
    {
        $this->scope_tenant('expense');
        $this->db->where('ex_status', 1);
        $this->db->select("SUM(ex_amount) AS total");
        $row = $this->db->get('expense')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    /* ============================================== Expense payments ==================================================== */
    /* ========== get total expense payment data ========== */
    function getTotalExpensePayments()
    {
        $this->scope_tenant('expense_payment');
        $this->db->where('exp_status', 1);
        $this->db->select("SUM(exp_paid_amount) AS total");
        $row = $this->db->get('expense_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    /* ========== Get expense payments by expense id ========== */
    function getExpensePaymentsByExpenseId($ex_id)
    {
        $this->scope_tenant('expense_payment');
        $this->db->where('exp_status', 1);
        $this->db->where('exp_ex_id', $ex_id);
        $query = $this->db->get('expense_payment');
        return $query->result();
    }

    /* ========== Get expense payment amount by expense id ========== */
    function getTotalSumAmountByExpenseId($ex_id)
    {
        $this->scope_tenant('expense_payment');
        $this->db->where('exp_status', 1);
        $this->db->where('exp_ex_id', $ex_id);
        $this->db->select("SUM(exp_paid_amount) AS total");
        $row = $this->db->get('expense_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    /* ========== Get expense paid amount ========== */
    function getTotalSumPaidAmount()
    {
        $this->scope_tenant('expense_payment');
        $this->db->where('exp_status', 1);
        $this->db->select("SUM(exp_paid_amount) AS total");
        $row = $this->db->get('expense_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }

    /* ========== Get expense payments paid amount ========== */
    function getExpenseWiseTotalPaidAmount($id)
    {
        $this->scope_tenant('expense_payment');
        $this->db->where('exp_status', 1);
        $this->db->where('exp_ex_id', $id);
        $this->db->select("SUM(exp_paid_amount) AS total");
        $row = $this->db->get('expense_payment')->row();
        return ($row && $row->total !== NULL) ? $row->total : 0;
    }
}
