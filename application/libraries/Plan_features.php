<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Plan_features
 * Which product features each subscription plan (tier) includes.
 *  - The Super Admin edits this list per plan (Plan Builder); it is stored in subscription_plans.features_json.
 *  - A feature that is missing from a plan's JSON falls back to the catalog default, so existing plans keep
 *    working exactly as before until a super admin changes them.
 *  - Enforcement is server-side: MY_Controller::require_plan_feature(), Api_v1, Tenant_notifier.
 *
 * To add a feature: add it to catalog(), then call require_plan_feature('key') where it should be enforced.
 */
class Plan_features {

    protected $CI;
    protected static $cache = array();

    /**
     * key => label, description, group, default (bool | 'ai' = follow the legacy has_ai_access flag)
     */
    public static function catalog() {
        return array(
            'kula_ai' => array(
                'label' => 'KulaAI Intelligence',
                'desc' => 'AI chat assistant, insights and predictive analysis',
                'group' => 'KulaAI', 'icon' => 'fa-wand-magic-sparkles', 'default' => 'ai',
            ),
            'kula_ai_vision' => array(
                'label' => 'KulaAI Vision',
                'desc' => 'Camera-based livestock counting, validation and accuracy tracking',
                'group' => 'KulaAI', 'icon' => 'fa-eye', 'default' => 'ai',
            ),
            'kula_ai_documents' => array(
                'label' => 'AI Document Import',
                'desc' => 'Upload records and let AI read and import them',
                'group' => 'KulaAI', 'icon' => 'fa-file-import', 'default' => 'ai',
            ),
            'reports' => array(
                'label' => 'Advanced Reports',
                'desc' => 'Stock, financial and mortality reports',
                'group' => 'Business', 'icon' => 'fa-chart-pie', 'default' => true,
            ),
            'email_alerts' => array(
                'label' => 'Automated Email Alerts',
                'desc' => 'Vaccination, low-feed, overdue-balance alerts and daily digest',
                'group' => 'Business', 'icon' => 'fa-envelope-open-text', 'default' => true,
            ),
            'api_access' => array(
                'label' => 'REST API Access',
                'desc' => 'Integrate with other systems using API tokens',
                'group' => 'Integrations', 'icon' => 'fa-plug', 'default' => true,
            ),
        );
    }

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
    }

    /**
     * Resolve every catalog feature for a plan row (object with features_json and has_ai_access)
     * @return array key => bool
     */
    public static function resolve($plan) {
        $stored = array();
        if ($plan && !empty($plan->features_json)) {
            $decoded = json_decode($plan->features_json, true);
            if (is_array($decoded)) {
                $stored = $decoded;
            }
        }
        $ai = $plan && isset($plan->has_ai_access) ? (bool)$plan->has_ai_access : true;

        $out = array();
        foreach (self::catalog() as $key => $def) {
            if (array_key_exists($key, $stored)) {
                $out[$key] = (bool)$stored[$key];
            } else {
                $out[$key] = ($def['default'] === 'ai') ? $ai : (bool)$def['default'];
            }
        }
        return $out;
    }

    /**
     * Merge posted checkbox values into the plan's stored JSON. Unknown keys already stored are preserved.
     * @return array (features_json string, has_ai_access int)
     */
    public static function build_json($posted, $existing_json = null) {
        $existing = array();
        if (!empty($existing_json)) {
            $decoded = json_decode($existing_json, true);
            if (is_array($decoded)) {
                $existing = $decoded;
            }
        }
        $posted = is_array($posted) ? $posted : array();
        foreach (array_keys(self::catalog()) as $key) {
            $existing[$key] = !empty($posted[$key]);
        }
        return array(json_encode($existing), !empty($existing['kula_ai']) ? 1 : 0);
    }

    /**
     * Features available to a tenant according to its current plan
     */
    public function for_tenant($tenant_id) {
        $tenant_id = (int)$tenant_id;
        if (isset(self::$cache[$tenant_id])) {
            return self::$cache[$tenant_id];
        }
        $plan = $this->CI->db->select('subscription_plans.features_json, subscription_plans.has_ai_access')
            ->from('tenants')->join('subscription_plans', 'subscription_plans.id = tenants.plan_id', 'left')
            ->where('tenants.id', $tenant_id)->get()->row();
        // No plan found: do not lock a tenant out because of missing data
        $features = $plan ? self::resolve($plan) : array_fill_keys(array_keys(self::catalog()), true);
        return self::$cache[$tenant_id] = $features;
    }

    public function has($tenant_id, $feature) {
        $f = $this->for_tenant($tenant_id);
        return isset($f[$feature]) ? $f[$feature] : true;
    }

    public static function label($feature) {
        $c = self::catalog();
        return isset($c[$feature]) ? $c[$feature]['label'] : $feature;
    }
}
