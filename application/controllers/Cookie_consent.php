<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cookie_consent extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Cookie_model');
        $this->load->helper(array('url', 'security'));
    }

    /**
     * Public JSON endpoint for active cookie configuration & inventory
     */
    public function get_config() {
        $settings = $this->Cookie_model->get_settings();
        $inventory = $this->Cookie_model->get_inventory_grouped(true);

        $response = array(
            'status' => 'success',
            'settings' => array(
                'banner_enabled' => (int)($settings->banner_enabled ?? 1),
                'mode' => $settings->mode ?? 'auto',
                'banner_title_essential' => $settings->banner_title_essential ?? 'Essential cookies',
                'banner_desc_essential' => $settings->banner_desc_essential ?? '',
                'btn_got_it_label' => $settings->btn_got_it_label ?? 'Got it',
                'banner_title_optional' => $settings->banner_title_optional ?? 'Your privacy matters',
                'banner_desc_optional' => $settings->banner_desc_optional ?? '',
                'btn_accept_all_label' => $settings->btn_accept_all_label ?? 'Accept optional cookies',
                'btn_reject_all_label' => $settings->btn_reject_all_label ?? 'Reject optional cookies',
                'btn_manage_label' => $settings->btn_manage_label ?? 'Manage preferences',
                'btn_save_preferences_label' => $settings->btn_save_preferences_label ?? 'Save preferences',
                'btn_cookie_policy_label' => $settings->btn_cookie_policy_label ?? 'Cookie Policy',
                'enable_preferences_category' => (int)($settings->enable_preferences_category ?? 1),
                'enable_analytics_category' => (int)($settings->enable_analytics_category ?? 0),
                'enable_marketing_category' => (int)($settings->enable_marketing_category ?? 0),
                'preferences_category_desc' => $settings->preferences_category_desc ?? '',
                'analytics_category_desc' => $settings->analytics_category_desc ?? '',
                'marketing_category_desc' => $settings->marketing_category_desc ?? '',
                'consent_lifetime_days' => (int)($settings->consent_lifetime_days ?? 365),
                'policy_version' => $settings->policy_version ?? '1.0.0',
            ),
            'inventory' => $inventory
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Privacy-conscious endpoint to record pseudonymous consent logs
     */
    public function log() {
        // Read JSON payload or POST fields
        $raw_input = file_get_contents('php://input');
        $payload = json_decode($raw_input, true);

        if (!$payload && !empty($_POST)) {
            $payload = $_POST;
        }

        $uuid = isset($payload['consent_uuid']) ? $payload['consent_uuid'] : '';
        $action = isset($payload['action']) ? $payload['action'] : 'saved_preferences';
        $policy_version = isset($payload['policy_version']) ? $payload['policy_version'] : '1.0.0';
        $categories = isset($payload['categories']) ? $payload['categories'] : array('essential' => true);

        $tenant_id = $this->tenant_id ?: null;

        $saved_uuid = $this->Cookie_model->log_consent(
            $uuid,
            $action,
            $categories,
            $policy_version,
            $tenant_id
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => 'success',
                'consent_uuid' => $saved_uuid
            )));
    }

    /**
     * Dedicated Public Cookie Policy Page
     */
    public function policy() {
        $data = array();
        $this->load->model('settings/settings_model');
        $data['settings'] = $this->settings_model->getSettings();
        $data['cookie_settings'] = $this->Cookie_model->get_settings();
        $data['inventory_grouped'] = $this->Cookie_model->get_inventory_grouped(true);

        $this->load->view('cookie_consent/policy_page', $data);
    }
}
