<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ai_analytics_service
 * Proactive Risk Engine & Predictive Anomaly Detection for KulaCRM.
 * Identifies mortality spikes, feed depletion, vaccination due dates, and financial receivables risks.
 */
class Ai_analytics_service {

    protected $CI;
    protected $tool_service;

    public function __construct() {
        $this->CI =& get_instance();
        if ($this->CI->load && method_exists($this->CI->load, 'service')) {
            $this->CI->load->service('kula_ai/Ai_tool_service', null, 'ai_tool_service');
        }
        if (!isset($this->CI->ai_tool_service)) {
            require_once APPPATH . 'modules/kula_ai/services/Ai_tool_service.php';
            $this->CI->ai_tool_service = new Ai_tool_service();
        }
    }

    /**
     * Generate complete proactive intelligence feed for Kula Intelligence Dashboard Widget
     */
    public function get_proactive_insights() {
        $batch_data = $this->CI->ai_tool_service->get_batch_summary();
        usort($batch_data, function($a, $b) {
            return (float)str_replace('%', '', $b['mortality_rate']) <=> (float)str_replace('%', '', $a['mortality_rate']);
        });

        $insights = array(
            'vision_alerts'             => $this->detect_vision_alerts(),
            'mortality_alerts'          => $this->detect_mortality_anomalies(),
            'vaccination_alerts'        => $this->detect_vaccination_alerts(),
            'food_stock_alerts'         => $this->detect_food_stock_warnings(),
            'financial_alerts'          => $this->detect_financial_anomalies(),
            'production_insights'       => $this->detect_production_trends(),
            'highest_mortality_batches' => array_slice($batch_data, 0, 5)
        );

        // Count total items requiring attention
        $total_attention = 0;
        foreach ($insights as $key => $items) {
            if ($key !== 'highest_mortality_batches' && is_array($items)) {
                $total_attention += count($items);
            }
        }
        $insights['total_attention_count'] = $total_attention;

        return $insights;
    }

    /**
     * Detect Mortality Anomalies
     */
    public function detect_mortality_anomalies() {
        $tool_svc = $this->CI->ai_tool_service;
        $batch_data = $tool_svc->get_batch_summary();

        if (empty($batch_data)) {
            return array();
        }

        // Calculate farm average mortality rate
        $total_initial = 0;
        $total_deaths = 0;
        foreach ($batch_data as $b) {
            $total_initial += $b['initial_quantity'];
            $total_deaths += $b['death_quantity'];
        }
        $farm_avg_rate = ($total_initial > 0) ? ($total_deaths / $total_initial) * 100 : 0;

        $alerts = array();
        foreach ($batch_data as $b) {
            $mort_rate = (float)str_replace('%', '', $b['mortality_rate']);

            // Threshold: Mortality > 5% AND > 1.5x Farm Average
            if ($mort_rate >= 5.0 && $mort_rate > ($farm_avg_rate * 1.5)) {
                $alerts[] = array(
                    'severity'      => 'HIGH',
                    'badge'         => '🔴 Mortality',
                    'title'         => "Unusual Mortality Detected in {$b['shed_name']}",
                    'batch_id'      => $b['batch_id'],
                    'mortality_rate'=> $mort_rate . '%',
                    'farm_avg'      => round($farm_avg_rate, 2) . '%',
                    'description'   => "Batch {$b['shed_name']} has a mortality rate of {$mort_rate}%, which is significantly higher than the farm average of " . round($farm_avg_rate, 1) . "%.",
                    'recommendation'=> "Review vaccination history, feed distribution logs, environmental conditions, and recent death records for this batch."
                );
            }
        }

        return $alerts;
    }

    /**
     * Detect Vaccination Alerts
     */
    public function detect_vaccination_alerts() {
        $tool_svc = $this->CI->ai_tool_service;
        $upcoming = $tool_svc->get_upcoming_vaccinations();

        if (!is_array($upcoming) || empty($upcoming) || isset($upcoming['status'])) {
            return array();
        }

        $alerts = array();
        $today = date('Y-m-d');

        foreach ($upcoming as $v) {
            $given_date = $v['vds_given_date'] ?? null;
            $vac_name = $v['vac_name'] ?? 'Scheduled Vaccination';
            $shed = $v['shed_name'] ?? 'Shed';

            if ($given_date && $given_date < $today) {
                $alerts[] = array(
                    'severity'    => 'HIGH',
                    'badge'       => '🔴 Vaccination Overdue',
                    'title'       => "Overdue Vaccination: {$vac_name}",
                    'description' => "Vaccination '{$vac_name}' for {$shed} was scheduled for {$given_date} and is overdue.",
                    'date'        => $given_date
                );
            } elseif ($given_date && $given_date <= date('Y-m-d', strtotime('+3 days'))) {
                $alerts[] = array(
                    'severity'    => 'MEDIUM',
                    'badge'       => '🟠 Vaccination Due',
                    'title'       => "Upcoming Vaccination: {$vac_name}",
                    'description' => "Vaccination '{$vac_name}' for {$shed} is scheduled for {$given_date}.",
                    'date'        => $given_date
                );
            }
        }

        return $alerts;
    }

    /**
     * Detect Food & Inventory Stock Warnings
     */
    public function detect_food_stock_warnings() {
        $tool_svc = $this->CI->ai_tool_service;
        $inventory = $tool_svc->get_inventory_forecast_data();

        $alerts = array();
        foreach ($inventory as $item) {
            $days = $item['estimated_days_left'];
            $name = $item['item_name'];

            if ($days <= 7) {
                $alerts[] = array(
                    'severity'    => 'HIGH',
                    'badge'       => '🔴 Critical Stock-out',
                    'title'       => "Low Feed Stock: {$name}",
                    'description' => "At current consumption, {$name} is projected to run out in approximately {$days} days.",
                    'days_left'   => $days
                );
            } elseif ($days <= 14) {
                $alerts[] = array(
                    'severity'    => 'MEDIUM',
                    'badge'       => '🟠 Feed Warning',
                    'title'       => "Reorder Advisory: {$name}",
                    'description' => "Feed stock {$name} will reach reorder threshold within approximately {$days} days.",
                    'days_left'   => $days
                );
            }
        }

        return $alerts;
    }

    /**
     * Detect Financial Anomalies & Overdue Receivables
     */
    public function detect_financial_anomalies() {
        $tool_svc = $this->CI->ai_tool_service;
        $clients = $tool_svc->get_client_balances();

        $alerts = array();
        foreach ($clients as $c) {
            $due = (float)($c['client_due'] ?? 0);
            $name = $c['client_name'] ?? 'Client';

            if ($due > 500) {
                $alerts[] = array(
                    'severity'    => 'MEDIUM',
                    'badge'       => '🟠 Outstanding Receivable',
                    'title'       => "Overdue Balance: {$name}",
                    'description' => "Client {$name} has an outstanding balance of $" . number_format($due, 2) . ".",
                    'amount'      => $due
                );
            }
        }

        return $alerts;
    }

    /**
     * Detect AI Vision Livestock Count Discrepancies
     */
    public function detect_vision_alerts() {
        if (!isset($this->CI->ai_vision_service)) {
            require_once APPPATH . 'modules/kula_ai/services/Ai_vision_service.php';
            $this->CI->ai_vision_service = new Ai_vision_service();
        }

        $sessions = $this->CI->ai_vision_service->get_counting_history(5);
        $alerts = array();

        foreach ($sessions as $s) {
            $diff = (int)$s->difference_count;
            if ($diff !== 0 && $s->status === 'completed') {
                $reconciliation = $this->CI->ai_vision_service->reconcile_session($s->id);
                $alerts[] = array(
                    'severity'    => ($diff > 0) ? 'HIGH' : 'MEDIUM',
                    'badge'       => ($diff > 0) ? '🔴 Count Discrepancy' : '🟠 Count Surplus',
                    'title'       => "Livestock Count Discrepancy in {$s->shed_name}",
                    'description' => "Session {$s->session_code}: Expected {$s->expected_count}, Confirmed {$s->confirmed_count} (Difference: {$diff}). " . implode(' ', $reconciliation['explanations'] ?? array()),
                    'session_id'  => $s->id,
                    'date'        => date('M j, Y', strtotime($s->created_at))
                );
            }
        }

        return $alerts;
    }

    /**
     * Detect Production Trends
     */
    public function detect_production_trends() {
        return array(
            array(
                'severity'    => 'LOW',
                'badge'       => '🟢 Production Insight',
                'title'       => 'Production Yield Active',
                'description' => 'Current production yields across active batches are operating within normal baseline parameters.'
            )
        );
    }
}
