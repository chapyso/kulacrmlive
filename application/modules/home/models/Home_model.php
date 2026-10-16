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

    /**
     * Get 6-month historical monthly trend for a given table and column.
     * Guaranteed tenant-isolated. Returns array of 6 floats [oldest ... current].
     */
    public function getMonthlyHistoricalTrend($table, $sum_col, $date_col, $status_col = null, $status_val = 1)
    {
        $points = array();
        for ($i = 5; $i >= 0; $i--) {
            $m_start = date('Y-m-01', strtotime("-$i months"));
            $m_end   = date('Y-m-t', strtotime("-$i months"));
            
            $this->db->select("SUM($sum_col) as total");
            $this->db->from($table);
            $this->scope_tenant($table);
            if ($status_col !== null) {
                $this->db->where($status_col, $status_val);
            }
            $this->db->where("DATE($date_col) >=", $m_start);
            $this->db->where("DATE($date_col) <=", $m_end);
            $row = $this->db->get()->row();
            $points[] = ($row && $row->total !== null) ? (float)$row->total : 0.0;
        }
        return $points;
    }

    /**
     * Get 6-month historical operating expenses (feed + vaccine + general expenses).
     */
    public function getMonthlyExpensesTrend()
    {
        $feed = $this->getMonthlyHistoricalTrend('food_purchase_summary', 'fdps_grand_total', 'fdps_date', 'fdps_status', 1);
        $vacc = $this->getMonthlyHistoricalTrend('vaccine_purchase_summary', 'vps_grand_total', 'vps_date', 'vps_status', 1);
        $exp  = $this->getMonthlyHistoricalTrend('expense', 'ex_amount', 'ex_date', 'ex_status', 1);
        
        $total = array();
        for ($i = 0; $i < 6; $i++) {
            $total[$i] = ($feed[$i] ?? 0.0) + ($vacc[$i] ?? 0.0) + ($exp[$i] ?? 0.0);
        }
        return $total;
    }

    /**
     * Get 7-day daily historical trend for a table and date column.
     */
    public function getDailyHistoricalTrend($table, $sum_col, $date_col, $status_col = null, $status_val = 1)
    {
        $points = array();
        for ($i = 6; $i >= 0; $i--) {
            $day = date('Y-m-d', strtotime("-$i days"));
            $this->db->select("SUM($sum_col) as total");
            $this->db->from($table);
            $this->scope_tenant($table);
            if ($status_col !== null) {
                $this->db->where($status_col, $status_val);
            }
            $this->db->where("DATE($date_col)", $day);
            $row = $this->db->get()->row();
            $points[] = ($row && $row->total !== null) ? (float)$row->total : 0.0;
        }
        return $points;
    }

    /**
     * Get 7-day daily purchases trend (livestock + food + vaccine + expense).
     */
    public function getDailyPurchasesTrend()
    {
        $live = $this->getDailyHistoricalTrend('livestock_purchase_summary', 'purs_sub_total', 'purs_date', 'purs_status', 1);
        $food = $this->getDailyHistoricalTrend('food_purchase_summary', 'fdps_grand_total', 'fdps_date', 'fdps_status', 1);
        $vacc = $this->getDailyHistoricalTrend('vaccine_purchase_summary', 'vps_grand_total', 'vps_date', 'vps_status', 1);
        $exp  = $this->getDailyHistoricalTrend('expense', 'ex_amount', 'ex_date', 'ex_status', 1);

        $total = array();
        for ($i = 0; $i < 7; $i++) {
            $total[$i] = ($live[$i] ?? 0.0) + ($food[$i] ?? 0.0) + ($vacc[$i] ?? 0.0) + ($exp[$i] ?? 0.0);
        }
        return $total;
    }

    /**
     * Get 7-day daily sales trend (livestock + product).
     */
    public function getDailySalesTrend()
    {
        $live = $this->getDailyHistoricalTrend('livestock_sale_summary', 'lsss_grand_total', 'lsss_date', 'lsss_status', 1);
        $prod = $this->getDailyHistoricalTrend('product_sale_summary', 'prss_grand_total', 'prss_date', 'prss_status', 1);

        $total = array();
        for ($i = 0; $i < 7; $i++) {
            $total[$i] = ($live[$i] ?? 0.0) + ($prod[$i] ?? 0.0);
        }
        return $total;
    }

    /**
     * Get daily livestock quantity purchased for a specific day.
     */
    public function getDailyLivestockQuantity($day)
    {
        $this->db->select("SUM(purv_quantity) AS total");
        $this->db->from('livestock_purchase_value');
        $this->db->join('livestock_purchase_summary', 'livestock_purchase_summary.purs_id = livestock_purchase_value.purv_purs_id');
        $this->db->where('purs_status', 1);
        $this->db->where('purv_status', 1);
        $this->db->where('DATE(purs_date)', $day);
        $this->scope_tenant('livestock_purchase_value');
        $row = $this->db->get()->row();
        return ($row && $row->total !== null) ? (float)$row->total : 0.0;
    }

    /**
     * Get 7-day forward vaccination schedule count (from today to +6 days).
     */
    public function getVaccination7DaySchedule()
    {
        $tenant_id = $this->get_tenant_id();
        $counts = array();
        for ($i = 0; $i < 7; $i++) {
            $day = date('Y-m-d', strtotime("+$i days"));
            $this->db->from('vaccine_dose_assigned_quantity vdq');
            $this->db->where('vdq.vdq_status', 1);
            $this->db->where('vdq.tenant_id', $tenant_id);
            $this->db->where('DATE(vdq.vdq_vaccination_date)', $day);
            $counts[] = (int)$this->db->count_all_results();
        }
        return $counts;
    }

    /**
     * Calculate comparison between current and previous period.
     * Returns an array with change, text, badge_type ('green', 'red', 'neutral'),
     * and icon name.
     */
    public function computePeriodComparison($current, $previous, $period_label = 'vs last month', $is_cost_or_debt = false)
    {
        $current = (float)$current;
        $previous = (float)$previous;

        if ($previous > 0) {
            $pct = round((($current - $previous) / $previous) * 100, 1);
            $sign = $pct > 0 ? '+' : '';
            $text = "{$sign}{$pct}% {$period_label}";
            
            if ($pct > 0) {
                // If it's a cost/debt, an increase is negative (red)
                $badge_class = $is_cost_or_debt ? 'badge-red' : 'badge-green';
                $icon = 'fa-arrow-trend-up';
            } elseif ($pct < 0) {
                // If it's a cost/debt, a decrease is positive (green)
                $badge_class = $is_cost_or_debt ? 'badge-green' : 'badge-red';
                $icon = 'fa-arrow-trend-down';
            } else {
                $badge_class = 'badge-green';
                $icon = 'fa-minus';
                $text = "0.0% {$period_label}";
            }
            return array('text' => $text, 'badge_class' => $badge_class, 'icon' => $icon, 'has_data' => true);
        } elseif ($current > 0) {
            $badge_class = $is_cost_or_debt ? 'badge-red' : 'badge-green';
            return array('text' => "+100% {$period_label}", 'badge_class' => $badge_class, 'icon' => 'fa-arrow-trend-up', 'has_data' => true);
        } else {
            return array('text' => 'No comparison data', 'badge_class' => 'badge-neutral', 'icon' => 'fa-clock', 'has_data' => false);
        }
    }

    /**
     * Master method: Fetch all KPI trends, comparisons, and sparkline data.
     */
    public function getModernKpiTrendsData()
    {
        try {
            // 1. Monthly historical data for financial cards (6 months)
            $purPoints = $this->getMonthlyHistoricalTrend('livestock_purchase_summary', 'purs_grand_total', 'purs_date', 'purs_status', 1);
            $expPoints = $this->getMonthlyExpensesTrend();
            $liveSalePoints = $this->getMonthlyHistoricalTrend('livestock_sale_summary', 'lsss_grand_total', 'lsss_date', 'lsss_status', 1);
            $prodSalePoints = $this->getMonthlyHistoricalTrend('product_sale_summary', 'prss_grand_total', 'prss_date', 'prss_status', 1);

            // Comparisons: month-0 (current) vs month-1 (previous)
            $currPur = $purPoints[5] ?? 0.0;
            $prevPur = $purPoints[4] ?? 0.0;
            $purComp = $this->computePeriodComparison($currPur, $prevPur, 'vs last month', false);

            $currExp = $expPoints[5] ?? 0.0;
            $prevExp = $expPoints[4] ?? 0.0;
            $expComp = $this->computePeriodComparison($currExp, $prevExp, 'vs last month', true);

            $currSale = $liveSalePoints[5] ?? 0.0;
            $prevSale = $liveSalePoints[4] ?? 0.0;
            $saleComp = $this->computePeriodComparison($currSale, $prevSale, 'vs last month', false);

            $currProd = $prodSalePoints[5] ?? 0.0;
            $prevProd = $prodSalePoints[4] ?? 0.0;
            $prodComp = $this->computePeriodComparison($currProd, $prevProd, 'vs last month', false);

            // Daily historical data (7 days)
            $dailyPurchases = $this->getDailyPurchasesTrend();
            $dailySales = $this->getDailySalesTrend();
            
            $dailyNewLive = array();
            for ($i = 6; $i >= 0; $i--) {
                $day = date('Y-m-d', strtotime("-$i days"));
                $dailyNewLive[] = (float)$this->getDailyLivestockQuantity($day);
            }

            $vaccSchedule = $this->getVaccination7DaySchedule();

            return array(
                'pur_points'       => $purPoints,
                'exp_points'       => $expPoints,
                'live_sale_points' => $liveSalePoints,
                'prod_sale_points' => $prodSalePoints,
                'pur_comp'         => $purComp,
                'exp_comp'         => $expComp,
                'sale_comp'        => $saleComp,
                'prod_comp'        => $prodComp,
                'daily_purchases'  => $dailyPurchases,
                'daily_sales'      => $dailySales,
                'daily_new_live'   => $dailyNewLive,
                'vacc_schedule'    => $vaccSchedule
            );
        } catch (Throwable $e) {
            log_message('error', 'Error in getModernKpiTrendsData: ' . $e->getMessage());
            return array(
                'pur_points'       => array(0,0,0,0,0,0),
                'exp_points'       => array(0,0,0,0,0,0),
                'live_sale_points' => array(0,0,0,0,0,0),
                'prod_sale_points' => array(0,0,0,0,0,0),
                'pur_comp'         => array('text' => 'No comparison data', 'badge_class' => 'badge-neutral', 'icon' => 'fa-clock', 'has_data' => false),
                'exp_comp'         => array('text' => 'No comparison data', 'badge_class' => 'badge-neutral', 'icon' => 'fa-clock', 'has_data' => false),
                'sale_comp'        => array('text' => 'No comparison data', 'badge_class' => 'badge-neutral', 'icon' => 'fa-clock', 'has_data' => false),
                'prod_comp'        => array('text' => 'No comparison data', 'badge_class' => 'badge-neutral', 'icon' => 'fa-clock', 'has_data' => false),
                'daily_purchases'  => array(0,0,0,0,0,0,0),
                'daily_sales'      => array(0,0,0,0,0,0,0),
                'daily_new_live'   => array(0,0,0,0,0,0,0),
                'vacc_schedule'    => array(0,0,0,0,0,0,0)
            );
        }
    }
}
