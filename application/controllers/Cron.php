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

    /**
     * Platform emails to tenant admins: renewal, expiry, trial ending, plan limit warnings.
     *   php index.php cron platform
     */
    public function platform() {
        $tenants = $this->db->get_where('tenants', array('status' => 'active'))->result();
        foreach ($tenants as $t) {
            $tid = (int)$t->id;
            $plan = $this->db->get_where('subscription_plans', array('id' => (int)$t->plan_id))->row();
            $sent = array();

            // Subscription period end (latest active subscription for this tenant)
            $sub = $this->db->where('tenant_id', $tid)->where('status', 'active')->order_by('current_period_end', 'DESC')->limit(1)->get('subscriptions')->row();
            if ($sub && !empty($sub->current_period_end)) {
                $days = (int)floor((strtotime($sub->current_period_end) - time()) / 86400);
                $end = date('j M Y', strtotime($sub->current_period_end));
                if ($days < 0) {
                    $sent[] = $this->platform_mail($tid, 'subscription_expired', 72, 'Your KulaCRM subscription has expired',
                        '<p>Your subscription for <strong>' . html_escape($t->name) . '</strong> ended on ' . html_escape($end) . '. Please renew to keep full access.</p>');
                } elseif ($days <= 1) {
                    $sent[] = $this->platform_mail($tid, 'renewal_1d', 24, 'Your KulaCRM subscription renews or ends tomorrow',
                        '<p>Your subscription for <strong>' . html_escape($t->name) . '</strong> reaches its period end on ' . html_escape($end) . '.</p>');
                } elseif ($days <= 7) {
                    $sent[] = $this->platform_mail($tid, 'renewal_7d', 96, 'Your KulaCRM subscription ends in ' . $days . ' days',
                        '<p>Your subscription for <strong>' . html_escape($t->name) . '</strong> reaches its period end on ' . html_escape($end) . '.</p>');
                }
            }

            // Trial ending
            if (!empty($t->trial_ends_at)) {
                $days = (int)floor((strtotime($t->trial_ends_at) - time()) / 86400);
                if ($days >= 0 && $days <= 3) {
                    $sent[] = $this->platform_mail($tid, 'trial_ending', 24, 'Your KulaCRM trial ends in ' . $days . ' day(s)',
                        '<p>The trial for <strong>' . html_escape($t->name) . '</strong> ends on ' . html_escape(date('j M Y', strtotime($t->trial_ends_at))) . '. Choose a plan to continue.</p>');
                }
            }

            // Plan limits (80%+)
            if ($plan) {
                $usage = array(
                    'users'  => array((int)$this->db->where('tenant_id', $tid)->count_all_results('users'), (int)$plan->max_users),
                    'sheds'  => array((int)$this->db->where('tenant_id', $tid)->count_all_results('shed'), (int)$plan->max_sheds),
                );
                foreach ($usage as $what => $uv) {
                    if ($uv[1] > 0 && $uv[1] < 9000 && $uv[0] >= 0.8 * $uv[1]) {
                        $sent[] = $this->platform_mail($tid, 'limit_' . $what, 168, 'You are close to your plan limit (' . $what . ')',
                            '<p><strong>' . html_escape($t->name) . '</strong> is using ' . $uv[0] . ' of ' . $uv[1] . ' ' . html_escape($what)
                            . ' allowed on the <strong>' . html_escape($plan->name) . '</strong> plan. Upgrade before you hit the limit.</p>');
                    }
                }
            }
            echo sprintf("tenant %d platform mails: %s\n", $tid, $sent ? implode(', ', array_filter($sent)) : 'none');
        }
    }

    protected function platform_mail($tenant_id, $category, $cooldown_hours, $subject, $body) {
        if ($this->tenant_notifier->recently_logged($tenant_id, $category, $cooldown_hours)) {
            return '';
        }
        $r = $this->tenant_notifier->notify_admins($tenant_id, $category, $subject, $body);
        return $category . '(' . $r['sent'] . '/' . ($r['sent'] + $r['failed']) . ')';
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
