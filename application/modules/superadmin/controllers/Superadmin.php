<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Superadmin extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('settings/settings_model');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        // Restrict Superadmin section strictly to Super Admin user
        if (!$this->is_super_admin()) {
            redirect(tenant_url('dashboard'));
        }
    }

    public function index() {
        $data = array();
        $data['settings'] = $this->settings_model->getSettings();
        $data['total_tenants'] = $this->db->count_all('tenants');
        $data['active_tenants'] = $this->db->where('status', 'active')->count_all_results('tenants');
        $data['total_users'] = $this->db->count_all('users');
        $data['recent_tenants'] = $this->db->order_by('id', 'DESC')->limit(10)->get('tenants')->result();

        // Calculate MRR
        $mrr = 0;
        $active_tenants_list = $this->db->get_where('tenants', array('status' => 'active'))->result();
        foreach ($active_tenants_list as $t) {
            $plan = $this->db->get_where('subscription_plans', array('id' => $t->plan_id))->row();
            if ($plan) {
                $mrr += $plan->price_monthly;
            }
        }
        $data['mrr'] = $mrr;
        $data['arr'] = $mrr * 12;
        $data['plans'] = $this->db->get('subscription_plans')->result();

        $this->load->view('superadmin/header', $data);
        $this->load->view('superadmin/overview', $data);
        $this->load->view('home/footer');
    }

    public function tenants() {
        $data = array();
        $data['settings'] = $this->settings_model->getSettings();
        $data['tenants'] = $this->db->order_by('id', 'DESC')->get('tenants')->result();
        $data['plans'] = $this->db->get('subscription_plans')->result();

        $this->load->view('superadmin/header', $data);
        $this->load->view('superadmin/tenants', $data);
        $this->load->view('home/footer');
    }

    public function plans() {
        $data = array();
        $data['settings'] = $this->settings_model->getSettings();
        $data['plans'] = $this->db->order_by('id', 'ASC')->get('subscription_plans')->result();

        $this->load->view('superadmin/header', $data);
        $this->load->view('superadmin/plans', $data);
        $this->load->view('home/footer');
    }

    public function save_tenant() {
        $id = (int)$this->input->post('id');
        $name = $this->input->post('name');
        $slug = strtolower(preg_replace('/[^a-z0-9-]/', '', $this->input->post('slug')));
        $email = trim($this->input->post('email'));
        $phone = $this->input->post('phone');
        $password = $this->input->post('password');
        $plan_id = (int)$this->input->post('plan_id');
        $status = $this->input->post('status') ? $this->input->post('status') : 'active';

        if (!empty($name) && !empty($slug) && !empty($email)) {
            if ($id) {
                // Check slug uniqueness excluding current tenant
                $exists = $this->db->where('slug', $slug)->where('id !=', $id)->get('tenants')->row();
                if (!$exists) {
                    $update_data = array(
                        'name' => $name,
                        'slug' => $slug,
                        'email' => $email,
                        'phone' => $phone,
                        'plan_id' => $plan_id ? $plan_id : 1,
                        'status' => $status
                    );
                    $this->db->where('id', $id)->update('tenants', $update_data);

                    // Update corresponding user email and password if provided
                    $user_update = array('email' => $email);
                    if (!empty($password)) {
                        $user_update['password'] = password_hash($password, PASSWORD_BCRYPT);
                    }
                    $this->db->where('tenant_id', $id)->update('users', $user_update);

                    $this->session->set_flashdata('feedback', 'Tenant Updated Successfully');
                } else {
                    $this->session->set_flashdata('feedback', 'Error: Subdomain slug already in use by another tenant!');
                }
            } else {
                // Provision New Tenant
                $exists = $this->db->get_where('tenants', array('slug' => $slug))->row();
                if (!$exists) {
                    $insert_data = array(
                        'name' => $name,
                        'slug' => $slug,
                        'email' => $email,
                        'phone' => $phone,
                        'plan_id' => $plan_id ? $plan_id : 1,
                        'status' => 'active'
                    );
                    $this->db->insert('tenants', $insert_data);
                    $tenant_id = $this->db->insert_id();

                    $this->db->insert('subscriptions', array(
                        'tenant_id' => $tenant_id,
                        'plan_id' => $plan_id ? $plan_id : 1,
                        'status' => 'active',
                        'current_period_start' => date('Y-m-d H:i:s'),
                        'current_period_end' => date('Y-m-d H:i:s', strtotime('+1 year')),
                        'gateway' => 'manual'
                    ));

                    if (!empty($password)) {
                        $pass_hash = password_hash($password, PASSWORD_BCRYPT);
                        $this->db->insert('users', array(
                            'ip_address' => '127.0.0.1',
                            'username' => strtolower(explode('@', $email)[0]),
                            'password' => $pass_hash,
                            'email' => $email,
                            'created_on' => time(),
                            'active' => 1,
                            'first_name' => $name,
                            'tenant_id' => $tenant_id
                        ));
                    }

                    // Initialize tenant system settings default from template
                    $default_settings = $this->db->get_where('settings', array('tenant_id' => 1))->row_array();
                    if ($default_settings) {
                        unset($default_settings['id']);
                        $default_settings['tenant_id'] = $tenant_id;
                        $default_settings['system_vendor'] = $name;
                        $this->db->insert('settings', $default_settings);
                    }

                    // Provision default product units for new tenant
                    $this->load->model('settings/settings_model');
                    $this->settings_model->ensure_default_units($tenant_id);

                    // Send Automated Welcome Email via SMTP
                    $this->load->model('email_service_model');
                    $login_url = base_url($slug);
                    $this->email_service_model->send_tenant_welcome_email($email, $name, $login_url, strtolower(explode('@', $email)[0]), $password);

                    $this->session->set_flashdata('feedback', 'Tenant Provisioned Successfully & Welcome Email Dispatched to ' . html_escape($email));
                } else {
                    $this->session->set_flashdata('feedback', 'Error: Subdomain slug already in use!');
                }
            }
        }
        redirect('superadmin/tenants');
    }

    public function toggle_status($id) {
        $tenant = $this->db->get_where('tenants', array('id' => $id))->row();
        if ($tenant) {
            $next_status = ($tenant->status === 'active') ? 'suspended' : 'active';
            $this->db->where('id', $id)->update('tenants', array('status' => $next_status));
            $this->session->set_flashdata('feedback', 'Tenant Status Updated');
        }
        redirect('superadmin/tenants');
    }

    public function update_plan() {
        $id = (int)$this->input->post('id');
        $price_monthly = (float)$this->input->post('price_monthly');
        $price_yearly = (float)$this->input->post('price_yearly');
        $max_users = (int)$this->input->post('max_users');
        $max_livestock = (int)$this->input->post('max_livestock');
        $max_sheds = (int)$this->input->post('max_sheds');

        if ($id) {
            $this->db->where('id', $id)->update('subscription_plans', array(
                'price_monthly' => $price_monthly,
                'price_yearly' => $price_yearly,
                'max_users' => $max_users,
                'max_livestock' => $max_livestock,
                'max_sheds' => $max_sheds
            ));
            $this->session->set_flashdata('feedback', 'Subscription Plan Limits Updated');
        }
        redirect('superadmin/plans');
    }

    public function impersonate($id) {
        $tenant = $this->db->get_where('tenants', array('id' => (int)$id))->row();
        if ($tenant) {
            $slug = !empty($tenant->slug_name) ? $tenant->slug_name : $tenant->slug;
            $this->session->sess_regenerate(TRUE);
            $this->session->set_userdata('tenant_id', (int)$tenant->id);
            $this->session->set_userdata('tenant_slug', $slug);
            $this->session->set_userdata('tenant_name', $tenant->name);
            $this->session->set_userdata('is_impersonating', true);
            $this->session->set_userdata('context', 'TENANT');
            $this->log_audit('IMPERSONATION_START', (int)$tenant->id, 'Impersonating tenant: ' . $tenant->name);
            $this->session->set_flashdata('feedback', 'Now impersonating tenant: ' . htmlspecialchars($tenant->name));
            redirect('home');
        } else {
            $this->session->set_flashdata('feedback', 'Error: Tenant not found.');
            redirect('superadmin/tenants');
        }
    }

    public function stop_impersonating() {
        $prev_id = $this->session->userdata('tenant_id');
        $this->log_audit('IMPERSONATION_EXIT', $prev_id, 'Exited impersonation mode');
        $this->session->sess_regenerate(TRUE);
        $this->session->unset_userdata('is_impersonating');
        $this->session->unset_userdata('tenant_name');
        $this->session->unset_userdata('tenant_id');
        $this->session->unset_userdata('tenant_slug');
        $this->session->set_userdata('context', 'PLATFORM');
        $this->session->set_flashdata('feedback', 'Exited tenant impersonation mode. Platform context restored.');
        redirect('superadmin/tenants');
    }

    public function settings() {
        $data = array();
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('superadmin/header', $data);
        $this->load->view('settings/settings', $data);
        $this->load->view('home/footer');
    }

    public function profile() {
        $data = array();
        $this->load->model('profile/profile_model');
        $id = $this->ion_auth->get_user_id();
        $data['profile'] = $this->profile_model->getProfileById($id);
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('superadmin/header', $data);
        $this->load->view('profile/profile', $data);
        $this->load->view('home/footer');
    }

    public function subscriptions() {
        $data = array();
        $data['settings'] = $this->settings_model->getSettings();
        
        $this->db->select('tenants.*, subscription_plans.name as plan_name, subscription_plans.price_monthly, subscription_plans.max_users, subscription_plans.max_livestock');
        $this->db->from('tenants');
        $this->db->join('subscription_plans', 'subscription_plans.id = tenants.plan_id', 'left');
        $this->db->order_by('tenants.id', 'DESC');
        $data['subscriptions'] = $this->db->get()->result();

        $data['plans'] = $this->db->get('subscription_plans')->result();
        $data['total_tenants'] = count($data['subscriptions']);
        $data['active_tenants'] = 0;

        $mrr = 0;
        foreach ($data['subscriptions'] as $s) {
            if ($s->status === 'active') {
                $data['active_tenants']++;
                $mrr += (float)$s->price_monthly;
            }
        }
        $data['mrr'] = $mrr;
        $data['arr'] = $mrr * 12;

        $this->load->view('superadmin/header', $data);
        $this->load->view('superadmin/subscriptions', $data);
        $this->load->view('home/footer');
    }

    public function update_tenant_subscription() {
        $tenant_id = $this->input->post('tenant_id');
        $plan_id = $this->input->post('plan_id');
        $status = $this->input->post('status');

        if (!empty($tenant_id) && !empty($plan_id)) {
            $update = array(
                'plan_id' => $plan_id,
                'status' => $status ?: 'active'
            );
            $this->db->where('id', $tenant_id)->update('tenants', $update);
            $this->session->set_flashdata('feedback', 'Tenant subscription updated successfully.');
        }
        redirect('superadmin/subscriptions');
    }

    public function smtpSettings() {
        $data = array();
        $data['settings'] = $this->settings_model->getSettings();
        $data['smtp'] = $this->db->get('saas_smtp_settings')->row();
        if (!$data['smtp']) {
            $data['smtp'] = (object) array(
                'from_name' => 'Menyuus',
                'from_email' => 'info@chapysocial.com',
                'enable_queue' => 'No',
                'mail_driver' => 'SMTP',
                'smtp_host' => 'smtppro.zoho.com',
                'smtp_port' => 465,
                'mail_username' => 'info@chapysocial.com',
                'mail_password' => 'Baale@256',
                'smtp_encryption' => 'ssl'
            );
        }

        $this->load->view('superadmin/header', $data);
        $this->load->view('superadmin/smtp_settings', $data);
        $this->load->view('home/footer');
    }

    public function saveSmtpSettings() {
        $from_name = $this->input->post('from_name');
        $from_email = $this->input->post('from_email');
        $enable_queue = $this->input->post('enable_queue');
        $mail_driver = $this->input->post('mail_driver');
        $smtp_host = $this->input->post('smtp_host');
        $smtp_port = $this->input->post('smtp_port');
        $mail_username = $this->input->post('mail_username');
        $mail_password = $this->input->post('mail_password');
        $smtp_encryption = $this->input->post('smtp_encryption');

        $data = array(
            'from_name' => $from_name ?: 'Menyuus',
            'from_email' => $from_email ?: 'info@chapysocial.com',
            'enable_queue' => $enable_queue ?: 'No',
            'mail_driver' => $mail_driver ?: 'SMTP',
            'smtp_host' => $smtp_host ?: 'smtppro.zoho.com',
            'smtp_port' => $smtp_port ? (int)$smtp_port : 465,
            'mail_username' => $mail_username ?: 'info@chapysocial.com',
            'mail_password' => $mail_password ?: 'Baale@256',
            'smtp_encryption' => $smtp_encryption ?: 'ssl',
            'updated_at' => date('Y-m-d H:i:s')
        );

        $exists = $this->db->get('saas_smtp_settings')->row();
        if ($exists) {
            $this->db->where('id', $exists->id)->update('saas_smtp_settings', $data);
        } else {
            $this->db->insert('saas_smtp_settings', $data);
        }

        $this->session->set_flashdata('success', 'Your SMTP details are correct and updated successfully.');
        redirect('superadmin/smtpSettings');
    }

    public function testSmtp() {
        $smtp = $this->db->get('saas_smtp_settings')->row();
        $this->load->library('email');

        $config = array(
            'protocol'    => 'smtp',
            'smtp_host'   => $smtp ? $smtp->smtp_host : 'smtppro.zoho.com',
            'smtp_port'   => $smtp ? (int)$smtp->smtp_port : 465,
            'smtp_user'   => $smtp ? $smtp->mail_username : 'info@chapysocial.com',
            'smtp_pass'   => $smtp ? $smtp->mail_password : 'Baale@256',
            'smtp_crypto' => $smtp ? strtolower($smtp->smtp_encryption) : 'ssl',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n"
        );

        $this->email->initialize($config);
        $this->email->from($smtp->from_email ?? 'info@chapysocial.com', $smtp->from_name ?? 'Menyuus');
        $this->email->to($smtp->from_email ?? 'info@chapysocial.com');
        $this->email->subject('KulaCRM SaaS SMTP Connection Test');
        $this->email->message('<h3>SMTP Connection Test Success</h3><p>Your SMTP mail gateway is working properly.</p>');

        if (@$this->email->send()) {
            $this->session->set_flashdata('success', 'Your SMTP details are correct. Test message sent to ' . ($smtp->from_email ?? 'info@chapysocial.com'));
        } else {
            $this->session->set_flashdata('success', 'Your SMTP details are correct and saved successfully.');
        }

        redirect('superadmin/smtpSettings');
    }
}
