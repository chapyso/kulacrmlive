<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cookie_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->ensure_tables_exist();
    }

    /**
     * Initialize required tables and seed initial audited data if empty
     */
    public function ensure_tables_exist() {
        // 1. cookie_settings table
        if (!$this->db->table_exists('cookie_settings')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `cookie_settings` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `banner_enabled` TINYINT(1) DEFAULT 1,
                `mode` VARCHAR(30) DEFAULT 'auto',
                `banner_title_essential` VARCHAR(255) DEFAULT 'Essential cookies',
                `banner_desc_essential` TEXT,
                `btn_got_it_label` VARCHAR(100) DEFAULT 'Got it',
                `banner_title_optional` VARCHAR(255) DEFAULT 'Your privacy matters',
                `banner_desc_optional` TEXT,
                `btn_accept_all_label` VARCHAR(100) DEFAULT 'Accept optional cookies',
                `btn_reject_all_label` VARCHAR(100) DEFAULT 'Reject optional cookies',
                `btn_manage_label` VARCHAR(100) DEFAULT 'Manage preferences',
                `btn_save_preferences_label` VARCHAR(100) DEFAULT 'Save preferences',
                `btn_cookie_policy_label` VARCHAR(100) DEFAULT 'Cookie Policy',
                `enable_preferences_category` TINYINT(1) DEFAULT 1,
                `enable_analytics_category` TINYINT(1) DEFAULT 0,
                `enable_marketing_category` TINYINT(1) DEFAULT 0,
                `preferences_category_desc` TEXT,
                `analytics_category_desc` TEXT,
                `marketing_category_desc` TEXT,
                `cookie_policy_content` LONGTEXT,
                `consent_lifetime_days` INT DEFAULT 365,
                `policy_version` VARCHAR(50) DEFAULT '1.0.0',
                `is_published` TINYINT(1) DEFAULT 1,
                `last_published_at` DATETIME NULL,
                `last_modified_by` VARCHAR(255) DEFAULT 'System',
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        // 2. cookie_inventory table
        if (!$this->db->table_exists('cookie_inventory')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `cookie_inventory` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `cookie_name` VARCHAR(255) NOT NULL,
                `category` ENUM('essential', 'preferences', 'analytics', 'marketing') NOT NULL DEFAULT 'essential',
                `purpose` TEXT NOT NULL,
                `provider` VARCHAR(255) NOT NULL DEFAULT 'KULACRM',
                `duration` VARCHAR(100) NOT NULL DEFAULT 'Session',
                `type` VARCHAR(50) NOT NULL DEFAULT 'HTTP Cookie',
                `is_active` TINYINT(1) DEFAULT 1,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        // 3. cookie_consent_logs table (privacy-conscious, pseudonymous UUIDv4)
        if (!$this->db->table_exists('cookie_consent_logs')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `cookie_consent_logs` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `consent_uuid` VARCHAR(64) NOT NULL,
                `tenant_id` INT NULL,
                `policy_version` VARCHAR(50) NOT NULL DEFAULT '1.0.0',
                `action` VARCHAR(50) NOT NULL,
                `consent_data` TEXT NOT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX (`consent_uuid`),
                INDEX (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        // 4. cookie_audit_logs table (Super Admin actions)
        if (!$this->db->table_exists('cookie_audit_logs')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `cookie_audit_logs` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT NOT NULL,
                `username` VARCHAR(255) NOT NULL,
                `action` VARCHAR(100) NOT NULL,
                `details` TEXT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }

        $this->seed_default_data();
    }

    /**
     * Seed initial configuration and audited cookies if tables are empty
     */
    protected function seed_default_data() {
        // Seed settings if empty
        $settings_count = $this->db->count_all('cookie_settings');
        if ($settings_count === 0) {
            $default_policy = <<<HTML
<h3>1. What Are Cookies?</h3>
<p>Cookies and local browser storage are small text files and data records stored on your computer or mobile device when you visit KULACRM. They are widely used to make web platforms function properly, enhance security, and remember your preferences across visits.</p>

<h3>2. How KULACRM Uses Cookies and Storage</h3>
<p>KULACRM uses first-party cookies and modern local storage mechanisms to provide a seamless, secure livestock farm management experience. We categorize our cookies and browser storage into the following purposes:</p>

<ul>
    <li><strong>Essential Cookies (Strictly Necessary):</strong> These cookies are required for the technical operation, security, and authentication of KULACRM. They keep you securely signed in, protect against Cross-Site Request Forgery (CSRF) attacks, and maintain session integrity. They cannot be deactivated.</li>
    <li><strong>Preferences &amp; Functionality:</strong> These cookies and local storage entries allow KULACRM to remember choices you make—such as your chosen interface language, dark/light theme mode, sidebar collapsed state, and table pagination settings—to provide an enhanced, personalized experience.</li>
    <li><strong>Analytics &amp; Performance:</strong> If enabled, these cookies collect anonymous, aggregated statistics to help us understand how features are used and improve platform speed and usability.</li>
    <li><strong>Marketing:</strong> If enabled, these cookies assist in delivering relevant platform updates and measuring campaign effectiveness.</li>
</ul>

<h3>3. Managing Your Preferences and Withdrawing Consent</h3>
<p>You can review or change your cookie preferences at any time by clicking the <strong>Cookie Preferences</strong> link in the footer or by clicking the persistent privacy icon at the bottom of the screen. When you withdraw consent or reject optional categories, any previously stored optional cookies under our control are deactivated and cleared.</p>

<h3>4. Contact Us</h3>
<p>If you have any questions regarding our Cookie Notice or privacy practices, please contact our Platform Administration support team.</p>
HTML;

            $this->db->insert('cookie_settings', array(
                'banner_enabled' => 1,
                'mode' => 'auto',
                'banner_title_essential' => 'Essential cookies',
                'banner_desc_essential' => 'KULACRM uses essential cookies to keep you signed in and help the platform work securely.',
                'btn_got_it_label' => 'Got it',
                'banner_title_optional' => 'Your privacy matters',
                'banner_desc_optional' => 'We use essential cookies to keep KULACRM secure. Optional cookies are used only with your permission. Change your preferences anytime.',
                'btn_accept_all_label' => 'Accept optional',
                'btn_reject_all_label' => 'Reject optional',
                'btn_manage_label' => 'Preferences',
                'btn_save_preferences_label' => 'Save preferences',
                'btn_cookie_policy_label' => 'Cookie Policy',
                'enable_preferences_category' => 1,
                'enable_analytics_category' => 0,
                'enable_marketing_category' => 0,
                'preferences_category_desc' => 'These cookies allow KULACRM to remember choices you make (such as language, theme, and UI layout) to provide enhanced features.',
                'analytics_category_desc' => 'These cookies help us understand how visitors interact with the platform by collecting aggregated usage information.',
                'marketing_category_desc' => 'These cookies are used to measure campaign effectiveness and deliver relevant platform updates.',
                'cookie_policy_content' => $default_policy,
                'consent_lifetime_days' => 365,
                'policy_version' => '1.0.0',
                'is_published' => 1,
                'last_published_at' => date('Y-m-d H:i:s'),
                'last_modified_by' => 'System'
            ));
        }

        // Seed cookie inventory if empty
        $inventory_count = $this->db->count_all('cookie_inventory');
        if ($inventory_count === 0) {
            $audited_items = array(
                array(
                    'cookie_name' => 'ci_session',
                    'category' => 'essential',
                    'purpose' => 'Maintains authenticated user session, CSRF state, and flash data securely across requests.',
                    'provider' => 'KULACRM (First-party)',
                    'duration' => '2 hours',
                    'type' => 'HTTP Cookie',
                    'is_active' => 1
                ),
                array(
                    'cookie_name' => 'identity',
                    'category' => 'essential',
                    'purpose' => 'Ion_Auth remember-me identity credential used for persistent login authentication when chosen by user.',
                    'provider' => 'KULACRM (First-party)',
                    'duration' => '2 years',
                    'type' => 'HTTP Cookie',
                    'is_active' => 1
                ),
                array(
                    'cookie_name' => 'remember_code',
                    'category' => 'essential',
                    'purpose' => 'Ion_Auth secure authentication salt token to validate persistent remember-me logins.',
                    'provider' => 'KULACRM (First-party)',
                    'duration' => '2 years',
                    'type' => 'HTTP Cookie',
                    'is_active' => 1
                ),
                array(
                    'cookie_name' => 'csrf_cookie_name',
                    'category' => 'essential',
                    'purpose' => 'Anti-CSRF security token preventing Cross-Site Request Forgery attacks on POST submissions.',
                    'provider' => 'KULACRM (First-party)',
                    'duration' => '2 hours',
                    'type' => 'HTTP Cookie',
                    'is_active' => 1
                ),
                array(
                    'cookie_name' => 'kula_consent',
                    'category' => 'essential',
                    'purpose' => 'Stores the visitor\'s privacy consent choices, accepted categories, and policy version.',
                    'provider' => 'KULACRM (First-party)',
                    'duration' => '1 year',
                    'type' => 'HTTP Cookie',
                    'is_active' => 1
                ),
                array(
                    'cookie_name' => 'lang_code',
                    'category' => 'preferences',
                    'purpose' => 'Stores the visitor\'s selected interface language preference (e.g., English, Swahili, Luganda).',
                    'provider' => 'KULACRM (First-party)',
                    'duration' => '2 years',
                    'type' => 'HTTP Cookie',
                    'is_active' => 1
                ),
                array(
                    'cookie_name' => 'dcjq-accordion',
                    'category' => 'preferences',
                    'purpose' => 'Remembers the expanded and collapsed states of the sidebar navigation menu.',
                    'provider' => 'KULACRM (First-party)',
                    'duration' => 'Session',
                    'type' => 'HTTP Cookie',
                    'is_active' => 1
                ),
                array(
                    'cookie_name' => 'kula_theme',
                    'category' => 'preferences',
                    'purpose' => 'Stores UI light or dark mode theme selection in the browser.',
                    'provider' => 'KULACRM (First-party)',
                    'duration' => 'Persistent',
                    'type' => 'Local Storage',
                    'is_active' => 1
                ),
                array(
                    'cookie_name' => 'kula_sidebar_collapsed',
                    'category' => 'preferences',
                    'purpose' => 'Stores desktop sidebar collapsed or expanded layout state.',
                    'provider' => 'KULACRM (First-party)',
                    'duration' => 'Persistent',
                    'type' => 'Local Storage',
                    'is_active' => 1
                ),
                array(
                    'cookie_name' => 'DataTables_*',
                    'category' => 'preferences',
                    'purpose' => 'Stores interactive table pagination, sorting, and filter state per page.',
                    'provider' => 'KULACRM (First-party)',
                    'duration' => 'Persistent',
                    'type' => 'Local Storage',
                    'is_active' => 1
                )
            );

            $this->db->insert_batch('cookie_inventory', $audited_items);
        }
    }

    /**
     * Get active cookie settings
     */
    public function get_settings() {
        $row = $this->db->order_by('id', 'ASC')->limit(1)->get('cookie_settings')->row();
        if (!$row) {
            $this->seed_default_data();
            $row = $this->db->order_by('id', 'ASC')->limit(1)->get('cookie_settings')->row();
        }
        return $row;
    }

    /**
     * Update cookie settings
     */
    public function update_settings($data, $user_id, $username) {
        $existing = $this->get_settings();
        $data['last_modified_by'] = $username;
        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($existing && !empty($existing->id)) {
            $this->db->where('id', $existing->id)->update('cookie_settings', $data);
        } else {
            $this->db->insert('cookie_settings', $data);
        }

        $this->log_audit($user_id, $username, 'updated_settings', 'Updated Cookie Notice and Consent settings.');
        return true;
    }

    /**
     * Publish configuration and optionally bump policy version
     */
    public function publish_settings($bump_version, $user_id, $username) {
        $existing = $this->get_settings();
        $update = array(
            'is_published' => 1,
            'last_published_at' => date('Y-m-d H:i:s'),
            'last_modified_by' => $username,
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($bump_version) {
            $current_ver = $existing->policy_version ?? '1.0.0';
            $parts = explode('.', $current_ver);
            if (count($parts) === 3) {
                $parts[1] = (int)$parts[1] + 1;
                $parts[2] = 0;
                $update['policy_version'] = implode('.', $parts);
            } else {
                $update['policy_version'] = '1.' . (time()) . '.0';
            }
        }

        $this->db->where('id', $existing->id)->update('cookie_settings', $update);
        $this->log_audit($user_id, $username, 'published_settings', 'Published cookie configuration. Policy Version: ' . ($update['policy_version'] ?? $existing->policy_version));
        return $update['policy_version'] ?? $existing->policy_version;
    }

    /**
     * Get inventory list
     */
    public function get_inventory($active_only = false) {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->order_by('category', 'ASC')->order_by('cookie_name', 'ASC')->get('cookie_inventory')->result();
    }

    /**
     * Get inventory grouped by category
     */
    public function get_inventory_grouped($active_only = true) {
        $items = $this->get_inventory($active_only);
        $grouped = array(
            'essential' => array(),
            'preferences' => array(),
            'analytics' => array(),
            'marketing' => array()
        );
        foreach ($items as $item) {
            $cat = $item->category;
            if (!isset($grouped[$cat])) {
                $grouped[$cat] = array();
            }
            $grouped[$cat][] = $item;
        }
        return $grouped;
    }

    /**
     * Get single inventory item
     */
    public function get_inventory_item($id) {
        return $this->db->get_where('cookie_inventory', array('id' => (int)$id))->row();
    }

    /**
     * Save inventory item (insert or update)
     */
    public function save_inventory_item($data, $user_id, $username) {
        $id = isset($data['id']) ? (int)$data['id'] : 0;
        unset($data['id']);

        if ($id > 0) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->update('cookie_inventory', $data);
            $this->log_audit($user_id, $username, 'updated_inventory_item', 'Updated cookie: ' . ($data['cookie_name'] ?? 'ID ' . $id));
            return $id;
        } else {
            $this->db->insert('cookie_inventory', $data);
            $new_id = $this->db->insert_id();
            $this->log_audit($user_id, $username, 'created_inventory_item', 'Added cookie: ' . ($data['cookie_name'] ?? 'New Item'));
            return $new_id;
        }
    }

    /**
     * Delete inventory item
     */
    public function delete_inventory_item($id, $user_id, $username) {
        $item = $this->get_inventory_item($id);
        $name = $item ? $item->cookie_name : 'ID ' . $id;
        $this->db->where('id', (int)$id)->delete('cookie_inventory');
        $this->log_audit($user_id, $username, 'deleted_inventory_item', 'Deleted cookie: ' . $name);
        return true;
    }

    /**
     * Record privacy-conscious pseudonymous consent log
     */
    public function log_consent($uuid, $action, $categories_json, $policy_version, $tenant_id = null) {
        if (empty($uuid)) {
            $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
        }

        $log_data = array(
            'consent_uuid' => substr(trim($uuid), 0, 64),
            'tenant_id' => $tenant_id ? (int)$tenant_id : null,
            'policy_version' => substr(trim($policy_version), 0, 50) ?: '1.0.0',
            'action' => substr(trim($action), 0, 50) ?: 'saved_preferences',
            'consent_data' => is_string($categories_json) ? $categories_json : json_encode($categories_json),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('cookie_consent_logs', $log_data);
        return $uuid;
    }

    /**
     * Get aggregate consent statistics (No PII)
     */
    public function get_consent_stats() {
        $total_records = $this->db->count_all('cookie_consent_logs');
        $accepted_all = $this->db->where('action', 'accepted_all')->count_all_results('cookie_consent_logs');
        $rejected_all = $this->db->where('action', 'rejected_all')->count_all_results('cookie_consent_logs');
        $custom_prefs = $this->db->where('action', 'saved_preferences')->count_all_results('cookie_consent_logs');
        $essential_only = $this->db->where('action', 'acknowledged_essential')->count_all_results('cookie_consent_logs');
        $withdrawn = $this->db->where('action', 'withdrawn')->count_all_results('cookie_consent_logs');

        // Recent 10 consent records
        $recent_logs = $this->db->order_by('id', 'DESC')->limit(10)->get('cookie_consent_logs')->result();

        return array(
            'total' => $total_records,
            'accepted_all' => $accepted_all,
            'rejected_all' => $rejected_all,
            'custom_prefs' => $custom_prefs,
            'essential_only' => $essential_only,
            'withdrawn' => $withdrawn,
            'recent' => $recent_logs
        );
    }

    /**
     * Log Super Admin configuration changes
     */
    public function log_audit($user_id, $username, $action, $details = null) {
        $data = array(
            'user_id' => (int)$user_id,
            'username' => trim($username),
            'action' => trim($action),
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('cookie_audit_logs', $data);
    }

    /**
     * Get recent audit logs
     */
    public function get_audit_logs($limit = 50) {
        return $this->db->order_by('id', 'DESC')->limit((int)$limit)->get('cookie_audit_logs')->result();
    }
}
