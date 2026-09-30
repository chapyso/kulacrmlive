<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Cron - command-line only jobs.
 *   php index.php cron notifications           run every category
 *   php index.php cron notifications daily_digest
 * Loops over ACTIVE tenants one at a time; each tenant's alerts are computed with that tenant's id
 * and sent only to that tenant's eligible users (see Tenant_notifier).
 */
class Cron extends CI_Controller {

    /** Minimum hours between two sends of the same category to the same tenant */
    protected $cooldown_hours = array(
        'vaccination_due'  => 24,
        'low_stock'        => 24,
        'overdue_balances' => 168,
        'daily_digest'     => 23,
    );

    public function __construct() {
        parent::__construct();
        if (!is_cli()) {
            show_404();
        }
        $this->db = $this->load->database('', TRUE); // explicit handle, as in Api_v1
        $this->load->library('ion_auth');
        $this->load->library('Tenant_notifier');
        $this->load->library('Alert_scanner');
    }

    public function notifications($only = null) {
        $categories = array_keys($this->cooldown_hours);
        if ($only !== null) {
            if (!in_array($only, $categories, true)) {
                echo "Unknown category '{$only}'. Use one of: " . implode(', ', $categories) . "\n";
                return;
            }
            $categories = array($only);
        }

        $tenants = $this->db->get_where('tenants', array('status' => 'active'))->result();
        foreach ($tenants as $t) {
            foreach ($categories as $cat) {
                $r = $this->run_category((int)$t->id, $t->name, $cat);
                echo sprintf("tenant %d %-16s %s\n", $t->id, $cat, $r);
            }
        }
    }

    protected function run_category($tenant_id, $tenant_name, $cat) {
        if ($this->recently_sent($tenant_id, $cat)) {
            return 'skipped (sent recently)';
        }

        switch ($cat) {
            case 'vaccination_due':
                $items = $this->alert_scanner->vaccinations_due($tenant_id);
                if (!$items) return 'nothing to report';
                $rows = '';
                foreach (array_slice($items, 0, 25) as $i) {
                    $rows .= '<li>' . html_escape($i['vaccine']) . ' - dose ' . html_escape($i['dose']) . ' - '
                        . html_escape($i['date']) . ($i['overdue'] ? ' <strong style="color:#b91c1c;">(overdue)</strong>' : '') . '</li>';
                }
                $subject = 'Vaccinations due - ' . count($items) . ' item(s)';
                $body = '<h3 style="margin:0 0 8px 0;">Vaccinations due or overdue</h3><ul>' . $rows . '</ul>'
                    . '<p><a href="' . base_url('vaccine') . '">Open vaccination schedule</a></p>';
                break;

            case 'low_stock':
                $items = $this->alert_scanner->low_stock($tenant_id);
                if (!$items) return 'nothing to report';
                $rows = '';
                foreach ($items as $i) {
                    $rows .= '<li>' . html_escape($i['item']) . ' - about ' . (int)$i['days_left'] . ' day(s) left (stock '
                        . html_escape($i['stock']) . ')</li>';
                }
                $subject = 'Low feed warning - ' . count($items) . ' item(s)';
                $body = '<h3 style="margin:0 0 8px 0;">Feed running low</h3><p>Estimated from the last 14 days of use.</p><ul>' . $rows . '</ul>';
                break;

            case 'overdue_balances':
                $items = $this->alert_scanner->overdue_balances($tenant_id);
                if (!$items) return 'nothing to report';
                $rows = '';
                foreach (array_slice($items, 0, 25) as $i) {
                    $rows .= '<li>' . html_escape($i['client']) . ' - ' . number_format($i['balance'], 2) . ' across '
                        . (int)$i['invoices'] . ' invoice(s)</li>';
                }
                $subject = 'Overdue customer balances - ' . count($items) . ' client(s)';
                $body = '<h3 style="margin:0 0 8px 0;">Customers with overdue balances (30+ days)</h3><ul>' . $rows . '</ul>';
                break;

            case 'daily_digest':
                $d = $this->alert_scanner->digest($tenant_id);
                $subject = 'Daily farm digest - ' . $tenant_name;
                $body = '<h3 style="margin:0 0 8px 0;">Last 24 hours</h3><ul>'
                    . '<li>Deaths recorded: ' . (int)$d['deaths_24h'] . '</li>'
                    . '<li>Sales recorded: ' . (int)$d['sales_24h'] . '</li>'
                    . '<li>Vaccinations due: ' . (int)$d['vaccinations'] . '</li>'
                    . '<li>Feed items running low: ' . (int)$d['low_stock'] . '</li>'
                    . '<li>Clients with overdue balances: ' . (int)$d['overdue'] . '</li></ul>';
                break;

            default:
                return 'unknown';
        }

        $res = $this->tenant_notifier->notify($tenant_id, $cat, $subject, $body);
        return "sent={$res['sent']} failed={$res['failed']} skipped={$res['skipped']}";
    }

    protected function recently_sent($tenant_id, $cat) {
        if (!$this->db->table_exists('email_log')) {
            return false;
        }
        $since = date('Y-m-d H:i:s', strtotime('-' . (int)$this->cooldown_hours[$cat] . ' hours'));
        return $this->db->where('tenant_id', $tenant_id)->where('category', $cat)
            ->where('status', 'sent')->where('created_at >=', $since)->count_all_results('email_log') > 0;
    }
}
