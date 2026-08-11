<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

#[AllowDynamicProperties]
class Home_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Active batches whose remaining stock has dropped below the configured threshold.
     * remaining = assigned - (sold + died)
     *
     * Returns an array of objects with shed_id, batch_id, ls_id, lst_id, remaining.
     */
    function getLowStockBatches($threshold)
    {
        $tenant_id = $this->get_tenant_id();
        $threshold = (int) $threshold;
        $sql = "
            SELECT  lshs.lshs_sh_id   AS shed_id,
                    lshs.lshs_batch_id AS batch_id,
                    lsh.lsh_purv_ls_id  AS ls_id,
                    lsh.lsh_purv_lst_id AS lst_id,
                    SUM(lsh.lsh_assign_quantity)
                        - IFNULL((SELECT SUM(lssv_quantity) FROM livestock_sale_value
                                  WHERE lssv_shed_id = lshs.lshs_sh_id
                                    AND lssv_batch_id = lshs.lshs_batch_id
                                    AND lssv_ls_id = lsh.lsh_purv_ls_id
                                    AND lssv_lst_id = lsh.lsh_purv_lst_id
                                    AND lssv_status = 1
                                    AND tenant_id = ?), 0)
                        - IFNULL((SELECT SUM(ld_death_quantity) FROM livestock_death_quantity
                                  WHERE ld_sh_id = lshs.lshs_sh_id
                                    AND ld_batch_id = lshs.lshs_batch_id
                                    AND ld_purv_ls_id = lsh.lsh_purv_ls_id
                                    AND ld_purv_lst_id = lsh.lsh_purv_lst_id
                                    AND ld_status = 1
                                    AND tenant_id = ?), 0) AS remaining
            FROM live_assigned_shed_summary lshs
            JOIN live_assigned_shed lsh ON lsh.lsh_lshs_id = lshs.lshs_id
            WHERE lshs.lshs_status = 1
              AND lshs.lshs_active_status = 0
              AND lsh.lsh_status = 1
              AND lshs.tenant_id = ?
              AND lsh.tenant_id = ?
            GROUP BY lshs.lshs_sh_id, lshs.lshs_batch_id, lsh.lsh_purv_ls_id, lsh.lsh_purv_lst_id
            HAVING remaining > 0 AND remaining <= ?";
        return $this->db->query($sql, array($tenant_id, $tenant_id, $tenant_id, $tenant_id, $threshold))->result();
    }

    /**
     * Supplier purchase invoices whose remaining balance is > 0 and whose date
     * is older than $days days. Returns an array of objects with
     * purchase_type, summary_id, supplier_id, date, total, paid, due.
     */
    function getOverdueSupplierPayments($days)
    {
        $tenant_id = $this->get_tenant_id();
        $days = (int) $days;
        $cutoff = date('Y-m-d', strtotime("-{$days} days"));
        $sql = "
            (SELECT 'livestock' AS purchase_type, p.purs_id AS summary_id, p.purs_supp_id AS supplier_id,
                    p.purs_date AS doc_date, p.purs_grand_total AS total,
                    IFNULL((SELECT SUM(sp_payment_amount) FROM supplier_payment
                            WHERE sp_purs_id = p.purs_id AND sp_status = 1 AND sp_purchase_type = 1 AND tenant_id = ?), 0) AS paid
             FROM livestock_purchase_summary p WHERE p.purs_status = 1 AND p.purs_date <= ? AND p.tenant_id = ?)
            UNION ALL
            (SELECT 'food', f.fdps_id, f.fdps_s_id, f.fdps_date, f.fdps_grand_total,
                    IFNULL((SELECT SUM(sp_payment_amount) FROM supplier_payment
                            WHERE sp_fdps_id = f.fdps_id AND sp_status = 1 AND sp_purchase_type = 2 AND tenant_id = ?), 0)
             FROM food_purchase_summary f WHERE f.fdps_status = 1 AND f.fdps_date <= ? AND f.tenant_id = ?)
            UNION ALL
            (SELECT 'vaccine', v.vps_id, v.vps_s_id, v.vps_date, v.vps_grand_total,
                    IFNULL((SELECT SUM(sp_payment_amount) FROM supplier_payment
                            WHERE sp_vps_id = v.vps_id AND sp_status = 1 AND sp_purchase_type = 3 AND tenant_id = ?), 0)
             FROM vaccine_purchase_summary v WHERE v.vps_status = 1 AND v.vps_date <= ? AND v.tenant_id = ?)";
        $rows = $this->db->query($sql, array($tenant_id, $cutoff, $tenant_id, $tenant_id, $cutoff, $tenant_id, $tenant_id, $cutoff, $tenant_id))->result();
        $out = array();
        foreach ($rows as $r) {
            $r->due = (float) $r->total - (float) $r->paid;
            if ($r->due > 0) {
                $out[] = $r;
            }
        }
        return $out;
    }

    /**
     * Same shape, for client sale invoices.
     */
    function getOverdueClientPayments($days)
    {
        $tenant_id = $this->get_tenant_id();
        $days = (int) $days;
        $cutoff = date('Y-m-d', strtotime("-{$days} days"));
        $sql = "
            (SELECT 'livestock' AS sale_type, s.lsss_id AS summary_id, s.lsss_c_id AS client_id,
                    s.lsss_date AS doc_date, s.lsss_grand_total AS total,
                    IFNULL((SELECT SUM(cp_received_amount) FROM client_payment
                            WHERE cp_lsss_id = s.lsss_id AND cp_status = 1 AND cp_sale_type = 1 AND tenant_id = ?), 0) AS paid
             FROM livestock_sale_summary s WHERE s.lsss_status = 1 AND s.lsss_date <= ? AND s.tenant_id = ?)
            UNION ALL
            (SELECT 'product', p.prss_id, p.prss_c_id, p.prss_date, p.prss_grand_total,
                    IFNULL((SELECT SUM(cp_received_amount) FROM client_payment
                            WHERE cp_prss_id = p.prss_id AND cp_status = 1 AND cp_sale_type = 2 AND tenant_id = ?), 0)
             FROM product_sale_summary p WHERE p.prss_status = 1 AND p.prss_date <= ? AND p.tenant_id = ?)";
        $rows = $this->db->query($sql, array($tenant_id, $cutoff, $tenant_id, $tenant_id, $cutoff, $tenant_id))->result();
        $out = array();
        foreach ($rows as $r) {
            $r->due = (float) $r->total - (float) $r->paid;
            if ($r->due > 0) {
                $out[] = $r;
            }
        }
        return $out;
    }

    /**
     * Latest records created across business tables, merged + sorted by
     * created_at desc.
     */
    function getRecentActivity($limit = 10)
    {
        $tenant_id = $this->get_tenant_id();
        $limit = max(1, min(50, (int) $limit));
        $sources = array(
            array('livestock',                  'ls_id',   'ls_name',     'ls_created_at',   'ls_created_by',   'ls_status',   'livestock/addLivestock',                      'Livestock breed: '),
            array('client',                     'c_id',    'c_name',      'c_created_at',    'c_created_by',    'c_status',    'client/listClient',                           'Client: '),
            array('supplier',                   's_id',    's_name',      's_created_at',    's_created_by',    's_status',    'supplier/listSupplier',                       'Supplier: '),
            array('livestock_purchase_summary', 'purs_id', 'purs_bill_no','purs_created_at', 'purs_created_by', 'purs_status', 'purchase/viewLivestockPurchase?purs_id=',     'Purchase #'),
            array('livestock_sale_summary',    'lsss_id',  null,          'lsss_created_at', 'lsss_created_by', 'lsss_status', 'sale/viewLivestockSale?lsss_id=',             'Livestock sale #'),
            array('product_sale_summary',      'prss_id',  null,          'prss_created_at', 'prss_created_by', 'prss_status', 'sale/viewProductSale?prss_id=',               'Product sale #'),
            array('expense',                    'ex_id',   'ex_name',     'ex_created_at',   'ex_created_by',   'ex_status',   'expense/listExpense',                         'Expense: '),
        );

        $rows = array();
        foreach ($sources as $s) {
            list($table, $idCol, $labelCol, $atCol, $byCol, $statusCol, $urlBase, $kind_prefix) = $s;
            if (!$this->db->table_exists($table)) {
                continue;
            }
            try {
                $cols = array($idCol, $atCol, $byCol);
                if ($labelCol !== null) { $cols[] = $labelCol; }
                $this->db->select(implode(', ', $cols))
                    ->from($table)
                    ->where($statusCol, 1)
                    ->where('tenant_id', $tenant_id)
                    ->where($atCol . ' IS NOT NULL', null, false)
                    ->order_by($atCol, 'desc')
                    ->limit($limit);
                foreach ($this->db->get()->result() as $r) {
                    $idVal    = $r->$idCol;
                    $atVal    = $r->$atCol;
                    $byVal    = $r->$byCol;
                    $labelVal = ($labelCol !== null && !empty($r->$labelCol)) ? $r->$labelCol : ('#' . $idVal);
                    $url      = (strpos($urlBase, '?') !== false || strpos($urlBase, '=') !== false)
                              ? $urlBase . $idVal
                              : $urlBase;
                    $rows[] = (object) array(
                        'kind'  => trim($kind_prefix),
                        'rid'   => $idVal,
                        'label' => $kind_prefix . $labelVal,
                        'at'    => $atVal,
                        'uid'   => $byVal,
                        'url'   => $url,
                    );
                }
            } catch (Throwable $e) {
                log_message('error', "getRecentActivity error for table $table: " . $e->getMessage());
            }
        }

        usort($rows, function ($a, $b) {
            return strcmp($b->at, $a->at);
        });
        return array_slice($rows, 0, $limit);
    }

    /**
     * Vaccination doses scheduled to be administered between today and +N days,
     * across active batches in active sheds.
     */
    function getVaccinationsDueWithin($days)
    {
        $tenant_id = $this->get_tenant_id();
        $days = (int) $days;
        $today = date('Y-m-d');
        $until = date('Y-m-d', strtotime("+{$days} days"));
        $this->db->select('vdq.vdq_id, vdq.vdq_vccn_id, vdq.vdq_dose_serial, vdq.vdq_vaccination_date');
        $this->db->where('vdq.vdq_status', 1);
        $this->db->where('vdq.tenant_id', $tenant_id);
        $this->db->where('vdq.vdq_vaccination_date >=', $today);
        $this->db->where('vdq.vdq_vaccination_date <=', $until);
        $query = $this->db->get('vaccine_dose_assigned_quantity vdq');
        return $query->result();
    }

    /**
     * BI Executive Financial KPIs: Revenue Today, This Month, This Year, Total Expenses, Net Profit
     */
    public function getTenantFinancialMetrics()
    {
        $tenant_id = $this->get_tenant_id();
        $today = date('Y-m-d');
        $this_month_start = date('Y-m-01');
        $this_year_start = date('Y-01-01');

        // Revenue Today
        $r_today = $this->db->query("SELECT SUM(sale_grand_total) as total FROM sale WHERE sale_status = 1 AND tenant_id = ? AND DATE(created_at) = ?", array($tenant_id, $today))->row();
        $rev_today = $r_today ? (float)$r_today->total : 0.0;

        // Revenue This Month
        $r_month = $this->db->query("SELECT SUM(sale_grand_total) as total FROM sale WHERE sale_status = 1 AND tenant_id = ? AND DATE(created_at) >= ?", array($tenant_id, $this_month_start))->row();
        $rev_month = $r_month ? (float)$r_month->total : 0.0;

        // Revenue This Year
        $r_year = $this->db->query("SELECT SUM(sale_grand_total) as total FROM sale WHERE sale_status = 1 AND tenant_id = ? AND DATE(created_at) >= ?", array($tenant_id, $this_year_start))->row();
        $rev_year = $r_year ? (float)$r_year->total : 0.0;

        // Total Expenses
        $r_exp = $this->db->query("SELECT SUM(amount) as total FROM expense WHERE ex_status = 1 AND tenant_id = ?", array($tenant_id))->row();
        $total_expenses = $r_exp ? (float)$r_exp->total : 0.0;

        // Net Profit
        $net_profit = $rev_year - $total_expenses;

        return array(
            'rev_today' => $rev_today,
            'rev_month' => $rev_month,
            'rev_year' => $rev_year,
            'total_expenses' => $total_expenses,
            'net_profit' => $net_profit
        );
    }

    /**
     * BI Monthly Revenue & Expense Trend (Past 6 Months)
     */
    public function getMonthlyRevenueTrend()
    {
        $tenant_id = $this->get_tenant_id();
        $months = array();
        $revenues = array();
        $expenses = array();

        for ($i = 5; $i >= 0; $i--) {
            $m_start = date('Y-m-01', strtotime("-$i months"));
            $m_end   = date('Y-m-t', strtotime("-$i months"));
            $m_label = date('M Y', strtotime("-$i months"));

            $r_sales = $this->db->query("SELECT SUM(sale_grand_total) as total FROM sale WHERE sale_status = 1 AND tenant_id = ? AND DATE(created_at) BETWEEN ? AND ?", array($tenant_id, $m_start, $m_end))->row();
            $r_exp   = $this->db->query("SELECT SUM(amount) as total FROM expense WHERE ex_status = 1 AND tenant_id = ? AND DATE(created_at) BETWEEN ? AND ?", array($tenant_id, $m_start, $m_end))->row();

            $months[]   = $m_label;
            $revenues[] = $r_sales ? (float)$r_sales->total : 0.0;
            $expenses[] = $r_exp ? (float)$r_exp->total : 0.0;
        }

        return array(
            'months' => $months,
            'revenues' => $revenues,
            'expenses' => $expenses
        );
    }
}
