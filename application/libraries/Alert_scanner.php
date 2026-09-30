<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Alert_scanner
 * Computes per-tenant operational alerts. Every query is explicitly filtered by the given
 * tenant_id, so results can never mix tenants. Amounts and dates are stored as text in several
 * legacy tables, so they are parsed in PHP.
 *
 * Definitions (kept deliberately simple and documented):
 *  - Vaccination due: an active scheduled dose (vaccine_dose_assigned_quantity) dated on or before
 *    today + $lead_days (and not older than 60 days) with no completed vaccine_dose_status row.
 *  - Low stock: stock = purchased - distributed - wasted (active rows). Average daily use is taken
 *    from the last 14 days of distribution; alert when days of stock left <= $days_threshold
 *    (or stock is <= 0 while the item was used recently).
 *  - Overdue balance: sales with an unpaid balance older than $overdue_days, grouped by client.
 */
class Alert_scanner {

    protected $CI;
    protected $db;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->db = $this->CI->db;
    }

    public function vaccinations_due($tenant_id, $lead_days = 3) {
        $tenant_id = (int)$tenant_id;
        $rows = $this->db->query(
            "SELECT q.vdq_id, q.vdq_dose_serial, q.vdq_vaccination_date, v.vcc_name
               FROM vaccine_dose_assigned_quantity q
               LEFT JOIN vaccine v ON v.vcc_id = q.vdq_vcc_id AND v.tenant_id = q.tenant_id
              WHERE q.tenant_id = ? AND q.vdq_status = 1
                AND NOT EXISTS (SELECT 1 FROM vaccine_dose_status s
                                 WHERE s.vds_vdq_id = q.vdq_id AND s.tenant_id = q.tenant_id AND s.vds_status = 1)
              LIMIT 500",
            array($tenant_id)
        )->result();

        $today = strtotime(date('Y-m-d'));
        $limit = strtotime('+' . (int)$lead_days . ' days', $today);
        $oldest = strtotime('-60 days', $today);
        $out = array();
        foreach ($rows as $r) {
            $ts = strtotime($r->vdq_vaccination_date);
            if ($ts === false || $ts > $limit || $ts < $oldest) {
                continue;
            }
            $out[] = array(
                'vaccine' => $r->vcc_name ?: 'Scheduled vaccine',
                'dose'    => $r->vdq_dose_serial,
                'date'    => date('Y-m-d', $ts),
                'overdue' => $ts < $today,
            );
        }
        usort($out, function ($a, $b) { return strcmp($a['date'], $b['date']); });
        return $out;
    }

    public function low_stock($tenant_id, $days_threshold = 7) {
        $tenant_id = (int)$tenant_id;
        $foods = $this->db->query(
            "SELECT fds_id, fds_food_title FROM food_summary WHERE tenant_id = ? AND fds_status = 1 LIMIT 200",
            array($tenant_id)
        )->result();

        $out = array();
        foreach ($foods as $f) {
            $purchased = (float)$this->db->query(
                "SELECT COALESCE(SUM(v.fdpv_quantity),0) t FROM food_purchase_value v
                   JOIN food_purchase_summary s ON s.fdps_id = v.fdpv_fdps_id AND s.tenant_id = v.tenant_id
                  WHERE v.tenant_id = ? AND v.fdpv_fd_id = ? AND v.fdpv_status = 1 AND s.fdps_status = 1",
                array($tenant_id, (int)$f->fds_id)
            )->row()->t;
            $distributed = (float)$this->db->query(
                "SELECT COALESCE(SUM(v.fddv_distributed_quantity),0) t FROM food_distributed_value v
                   JOIN food_distributed_summary s ON s.fdds_id = v.fddv_fdds_id AND s.tenant_id = v.tenant_id
                  WHERE v.tenant_id = ? AND s.fdds_fd_id = ? AND v.fddv_status = 1 AND s.fdds_status = 1",
                array($tenant_id, (int)$f->fds_id)
            )->row()->t;
            $wasted = (float)$this->db->query(
                "SELECT COALESCE(SUM(fdw_quantity),0) t FROM food_wasted WHERE tenant_id = ? AND fdw_fd_id = ? AND fdw_status = 1",
                array($tenant_id, (int)$f->fds_id)
            )->row()->t;
            $recent = (float)$this->db->query(
                "SELECT COALESCE(SUM(v.fddv_distributed_quantity),0) t FROM food_distributed_value v
                   JOIN food_distributed_summary s ON s.fdds_id = v.fddv_fdds_id AND s.tenant_id = v.tenant_id
                  WHERE v.tenant_id = ? AND s.fdds_fd_id = ? AND v.fddv_status = 1 AND s.fdds_status = 1
                    AND s.fdds_created_at >= ?",
                array($tenant_id, (int)$f->fds_id, date('Y-m-d H:i:s', strtotime('-14 days')))
            )->row()->t;

            $stock = $purchased - $distributed - $wasted;
            $daily = $recent / 14;
            if ($daily <= 0) {
                continue; // not being used, nothing to warn about
            }
            $days_left = $stock / $daily;
            if ($stock <= 0 || $days_left <= $days_threshold) {
                $out[] = array(
                    'item'      => $f->fds_food_title ?: ('Feed item #' . $f->fds_id),
                    'stock'     => round($stock, 2),
                    'days_left' => max(0, (int)floor($days_left)),
                );
            }
        }
        return $out;
    }

    public function overdue_balances($tenant_id, $overdue_days = 30) {
        $tenant_id = (int)$tenant_id;
        $rows = $this->db->query(
            "SELECT s.client_id, s.date, s.balance, c.c_name
               FROM sale s LEFT JOIN client c ON c.c_id = s.client_id AND c.tenant_id = s.tenant_id
              WHERE s.tenant_id = ? AND s.payment_status <> 'paid' LIMIT 1000",
            array($tenant_id)
        )->result();

        $cutoff = strtotime('-' . (int)$overdue_days . ' days');
        $by_client = array();
        foreach ($rows as $r) {
            $balance = (float)$r->balance;
            $ts = strtotime($r->date);
            if ($balance <= 0 || $ts === false || $ts > $cutoff) {
                continue;
            }
            $k = (int)$r->client_id;
            if (!isset($by_client[$k])) {
                $by_client[$k] = array('client' => $r->c_name ?: 'Walk-in / unknown client', 'balance' => 0.0, 'invoices' => 0);
            }
            $by_client[$k]['balance'] += $balance;
            $by_client[$k]['invoices']++;
        }
        usort($by_client, function ($a, $b) { return $b['balance'] <=> $a['balance']; });
        return array_values($by_client);
    }

    public function digest($tenant_id) {
        $tenant_id = (int)$tenant_id;
        $since = date('Y-m-d H:i:s', strtotime('-24 hours'));
        $deaths = (int)$this->db->query(
            "SELECT COALESCE(SUM(ld_death_quantity),0) t FROM livestock_death_quantity WHERE tenant_id = ? AND ld_status = 1 AND ld_created_at >= ?",
            array($tenant_id, $since)
        )->row()->t;
        $sales = $this->db->query(
            "SELECT COUNT(*) n FROM sale WHERE tenant_id = ? AND sale_status <> 'cancelled' AND date >= ?",
            array($tenant_id, date('Y-m-d', strtotime('-1 day')))
        )->row()->n;
        return array(
            'deaths_24h'   => $deaths,
            'sales_24h'    => (int)$sales,
            'vaccinations' => count($this->vaccinations_due($tenant_id)),
            'low_stock'    => count($this->low_stock($tenant_id)),
            'overdue'      => count($this->overdue_balances($tenant_id)),
        );
    }
}
