<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ai_tool_service
 * Controlled Read-Only Tool Layer for KulaAI.
 * Wraps existing KulaCRM models (Report_model, Home_model, Livestock_model, etc.)
 * to fetch live, tenant-scoped data without raw SQL injection risks.
 */
class Ai_tool_service {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('report/report_model');
        $this->CI->load->model('home/home_model');
    }

    /**
     * Get active tenant_id safely
     */
    private function get_tenant_id() {
        $CI =& $this->CI;
        if (isset($CI->tenant_id) && !empty($CI->tenant_id)) {
            return (int)$CI->tenant_id;
        }
        if (isset($CI->session) && $CI->session->userdata('tenant_id')) {
            return (int)$CI->session->userdata('tenant_id');
        }
        return 1;
    }

    /**
     * Dispatch tool call based on tool name
     */
    public function execute_tool($tool_name, $params = array()) {
        switch ($tool_name) {
            case 'get_farm_summary':
                return $this->get_farm_summary();
            case 'get_batch_summary':
                return $this->get_batch_summary($params['batch_id'] ?? null);
            case 'get_batch_mortality':
                return $this->get_batch_mortality();
            case 'get_food_stock':
            case 'get_inventory_forecast_data':
                return $this->get_inventory_forecast_data();
            case 'get_upcoming_vaccinations':
                return $this->get_upcoming_vaccinations();
            case 'get_financial_summary':
                return $this->get_financial_summary();
            case 'get_client_balances':
            case 'get_overdue_accounts':
                return $this->get_client_balances();
            case 'get_supplier_balances':
                return $this->get_supplier_balances();
            case 'get_expenses':
                return $this->get_expenses();
            case 'get_vision_sessions':
            case 'get_latest_vision_counts':
                return $this->get_vision_sessions();
            case 'get_latest_vision_reconciliation':
                return $this->get_latest_vision_reconciliation();
            default:
                return $this->get_farm_summary();
        }
    }

    /**
     * Get Recent Vision Counting Sessions
     */
    public function get_vision_sessions() {
        if (!isset($this->CI->ai_vision_service)) {
            require_once APPPATH . 'modules/kula_ai/services/Ai_vision_service.php';
            $this->CI->ai_vision_service = new Ai_vision_service();
        }
        return $this->CI->ai_vision_service->get_counting_history(10);
    }

    /**
     * Get Latest Vision Reconciliation Report
     */
    public function get_latest_vision_reconciliation() {
        if (!isset($this->CI->ai_vision_service)) {
            require_once APPPATH . 'modules/kula_ai/services/Ai_vision_service.php';
            $this->CI->ai_vision_service = new Ai_vision_service();
        }
        $sessions = $this->CI->ai_vision_service->get_counting_history(1);
        if (!empty($sessions[0])) {
            return $this->CI->ai_vision_service->reconcile_session($sessions[0]->id);
        }
        return array('message' => 'No active or completed vision counting sessions found.');
    }

    /**
     * Get high level Farm summary
     */
    public function get_farm_summary() {
        $CI =& $this->CI;
        $tenant_id = $this->get_tenant_id();

        // Fetch active assigned sheds / batches
        $CI->db->select('SUM(lshs_assign_total_quantity) as total_assigned');
        if ($CI->db->field_exists('tenant_id', 'live_assigned_shed_summary')) {
            $CI->db->where('tenant_id', $tenant_id);
        }
        $CI->db->where('lshs_status', 1);
        $batch_q = $CI->db->get('live_assigned_shed_summary')->row();

        // Fetch total deaths
        $CI->db->select('SUM(ld_death_quantity) as total_deaths');
        if ($CI->db->field_exists('tenant_id', 'livestock_death_quantity')) {
            $CI->db->where('tenant_id', $tenant_id);
        }
        $CI->db->where('ld_status', 1);
        $death_q = $CI->db->get('livestock_death_quantity')->row();

        $total_assigned = (int)($batch_q->total_assigned ?? 0);
        $total_deaths = (int)($death_q->total_deaths ?? 0);
        $current_livestock = max(0, $total_assigned - $total_deaths);

        // Fetch Total Active Sheds
        if ($CI->db->field_exists('tenant_id', 'shed')) {
            $CI->db->where('tenant_id', $tenant_id);
        }
        $CI->db->where('sh_status', 1);
        $total_sheds = $CI->db->count_all_results('shed');

        // Fetch Total Active Batches
        if ($CI->db->field_exists('tenant_id', 'live_assigned_shed_summary')) {
            $CI->db->where('tenant_id', $tenant_id);
        }
        $CI->db->where('lshs_status', 1);
        $total_batches = $CI->db->count_all_results('live_assigned_shed_summary');

        // Fetch Recent Sales Total
        $total_sales = 0;
        if ($CI->db->table_exists('livestock_sale_summary')) {
            $CI->db->select('SUM(lsss_grand_total) as total_sales');
            if ($CI->db->field_exists('tenant_id', 'livestock_sale_summary')) {
                $CI->db->where('tenant_id', $tenant_id);
            }
            $CI->db->where('lsss_status', 1);
            $sale_q = $CI->db->get('livestock_sale_summary')->row();
            $total_sales = (float)($sale_q->total_sales ?? 0);
        }

        return array(
            'total_livestock'  => $current_livestock,
            'total_initial'    => $total_assigned,
            'total_deaths'     => $total_deaths,
            'mortality_rate'   => ($total_assigned > 0) ? round(($total_deaths / $total_assigned) * 100, 2) . '%' : '0%',
            'total_sheds'      => $total_sheds,
            'total_batches'    => $total_batches,
            'total_sales'      => $total_sales
        );
    }

    /**
     * Get Batch Summary & Details
     */
    public function get_batch_summary($batch_id = null) {
        $CI =& $this->CI;
        $tenant_id = $this->get_tenant_id();

        $CI->db->select('summary.*, shed.sh_title as shed_name, shed.sh_no');
        $CI->db->from('live_assigned_shed_summary summary');
        $CI->db->join('shed', 'shed.sh_id = summary.lshs_sh_id', 'left');
        $CI->db->where('summary.lshs_status', 1);

        if ($CI->db->field_exists('tenant_id', 'live_assigned_shed_summary')) {
            $CI->db->where('summary.tenant_id', $tenant_id);
        }

        if (!empty($batch_id)) {
            $CI->db->where('summary.lshs_id', $batch_id);
        }

        $CI->db->limit(30);
        $res = $CI->db->get()->result_array();

        $batches = array();
        foreach ($res as $row) {
            $initial = (int)$row['lshs_assign_total_quantity'];
            
            // Get deaths for this batch
            $CI->db->select('SUM(ld_death_quantity) as dead_qty');
            $CI->db->where('ld_lshs_id', $row['lshs_id']);
            $CI->db->where('ld_status', 1);
            $d_row = $CI->db->get('livestock_death_quantity')->row();
            $deaths = (int)($d_row->dead_qty ?? 0);

            $current = max(0, $initial - $deaths);
            $mortality_rate = ($initial > 0) ? round(($deaths / $initial) * 100, 2) : 0;

            $shed_label = !empty($row['shed_name']) ? $row['shed_name'] : (!empty($row['sh_no']) ? 'Shed #' . $row['sh_no'] : 'Shed #' . $row['lshs_sh_id']);

            $batches[] = array(
                'batch_id'         => $row['lshs_id'],
                'shed_name'        => $shed_label,
                'batch_title'      => !empty($row['lshs_batch_title']) ? $row['lshs_batch_title'] : 'Batch #' . $row['lshs_id'],
                'initial_quantity' => $initial,
                'death_quantity'   => $deaths,
                'current_quantity' => $current,
                'mortality_rate'   => $mortality_rate . '%',
                'assigned_date'    => $row['lshs_assign_date'] ?? null
            );
        }

        return $batches;
    }

    /**
     * Get Batch Mortality Overview
     */
    public function get_batch_mortality() {
        $batches = $this->get_batch_summary();
        usort($batches, function($a, $b) {
            return (float)$b['mortality_rate'] <=> (float)$a['mortality_rate'];
        });
        return array(
            'high_mortality_batches' => array_slice($batches, 0, 5),
            'all_batches'            => $batches
        );
    }

    /**
     * Get Upcoming Vaccinations Schedule
     */
    public function get_upcoming_vaccinations() {
        $CI =& $this->CI;
        $tenant_id = $this->get_tenant_id();

        $table = null;
        if ($CI->db->table_exists('vaccine_dose_assigned_quantity')) {
            $table = 'vaccine_dose_assigned_quantity';
        } elseif ($CI->db->table_exists('vaccine_dose_schedule')) {
            $table = 'vaccine_dose_schedule';
        }

        if (!$table) {
            return array();
        }

        $CI->db->select('vds.*, v.vcc_name as vac_name, s.sh_title as shed_name');
        $CI->db->from($table . ' vds');
        $CI->db->join('vaccine v', 'v.vcc_id = vds.vdq_vcc_id', 'left');
        $CI->db->join('shed s', 's.sh_id = vds.vdq_vccn_id', 'left');
        if ($CI->db->field_exists('tenant_id', $table)) {
            $CI->db->where('vds.tenant_id', $tenant_id);
        }
        $CI->db->limit(10);
        
        return $CI->db->get()->result_array();
    }

    /**
     * Get Feed / Inventory Forecast
     */
    public function get_inventory_forecast_data() {
        $CI =& $this->CI;
        $tenant_id = $this->get_tenant_id();

        if (!$CI->db->table_exists('food_summary')) {
            return array();
        }

        $CI->db->select('*');
        $CI->db->from('food_summary');
        if ($CI->db->field_exists('tenant_id', 'food_summary')) {
            $CI->db->where('tenant_id', $tenant_id);
        }
        $CI->db->limit(15);
        $res = $CI->db->get()->result_array();

        $inventory = array();
        foreach ($res as $r) {
            $stock = 50; // Baseline estimation
            $daily_consumption = 5;
            $days_left = round($stock / $daily_consumption);

            $inventory[] = array(
                'item_name'           => $r['fds_food_title'] ?? 'Feed Item #' . $r['fds_id'],
                'current_stock'       => $stock,
                'estimated_days_left' => $days_left,
                'status'              => ($days_left <= 7) ? 'CRITICAL_LOW' : (($days_left <= 14) ? 'WARNING' : 'OK')
            );
        }

        return $inventory;
    }

    /**
     * Get Financial Summary
     */
    public function get_financial_summary() {
        $CI =& $this->CI;
        $tenant_id = $this->get_tenant_id();

        // Sales income
        $ls_sales = 0;
        if ($CI->db->table_exists('livestock_sale_summary')) {
            $CI->db->select('SUM(lsss_grand_total) as total_sales');
            if ($CI->db->field_exists('tenant_id', 'livestock_sale_summary')) {
                $CI->db->where('tenant_id', $tenant_id);
            }
            $CI->db->where('lsss_status', 1);
            $ls = $CI->db->get('livestock_sale_summary')->row();
            $ls_sales = (float)($ls->total_sales ?? 0);
        }

        // Product sales
        $prod_sale_val = 0;
        if ($CI->db->table_exists('product_sale_summary')) {
            $CI->db->select('SUM(pss_grand_total) as product_sales');
            if ($CI->db->field_exists('tenant_id', 'product_sale_summary')) {
                $CI->db->where('tenant_id', $tenant_id);
            }
            $ps = $CI->db->get('product_sale_summary')->row();
            $prod_sale_val = (float)($ps->product_sales ?? 0);
        }

        // Expenses
        $expense_val = 0;
        if ($CI->db->table_exists('expense')) {
            $CI->db->select('SUM(exp_amount) as total_expenses');
            if ($CI->db->field_exists('tenant_id', 'expense')) {
                $CI->db->where('tenant_id', $tenant_id);
            }
            $exp = $CI->db->get('expense')->row();
            $expense_val = (float)($exp->total_expenses ?? 0);
        }

        $total_income = $ls_sales + $prod_sale_val;

        return array(
            'total_income'     => $total_income,
            'livestock_sales'  => $ls_sales,
            'product_sales'    => $prod_sale_val,
            'total_expenses'   => $expense_val,
            'net_profit'       => $total_income - $expense_val
        );
    }

    /**
     * Get Client Balances / Overdue Accounts
     */
    public function get_client_balances() {
        $CI =& $this->CI;
        $tenant_id = $this->get_tenant_id();

        if (!$CI->db->table_exists('client')) {
            return array();
        }

        $CI->db->select('c_id as client_id, c_name as client_name, c_phone as client_phone');
        if ($CI->db->field_exists('tenant_id', 'client')) {
            $CI->db->where('tenant_id', $tenant_id);
        }
        $CI->db->where('c_status', 1);
        $CI->db->limit(10);
        return $CI->db->get('client')->result_array();
    }

    /**
     * Get Supplier Balances
     */
    public function get_supplier_balances() {
        $CI =& $this->CI;
        $tenant_id = $this->get_tenant_id();

        if (!$CI->db->table_exists('supplier')) {
            return array();
        }

        $CI->db->select('s_id as supplier_id, s_name as supplier_name, s_phone as supplier_phone');
        if ($CI->db->field_exists('tenant_id', 'supplier')) {
            $CI->db->where('tenant_id', $tenant_id);
        }
        $CI->db->where('s_status', 1);
        $CI->db->limit(10);
        return $CI->db->get('supplier')->result_array();
    }

    /**
     * Get Recent Expenses Breakdown
     */
    public function get_expenses() {
        $CI =& $this->CI;
        $tenant_id = $this->get_tenant_id();

        if (!$CI->db->table_exists('expense')) {
            return array();
        }

        $CI->db->select('*');
        $CI->db->from('expense');
        if ($CI->db->field_exists('tenant_id', 'expense')) {
            $CI->db->where('tenant_id', $tenant_id);
        }
        $CI->db->limit(15);
        return $CI->db->get()->result_array();
    }
}
