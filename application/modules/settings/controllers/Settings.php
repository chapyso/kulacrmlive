<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('settings_model');
        $language = $this->get_language();
        $load_lang = (strtolower($language) === 'luganda' && is_dir(APPPATH . 'language/Luganda')) ? 'Luganda' : $language;
        $this->lang->load('system_syntax', $load_lang);
        $this->load->library('upload');
        $this->load->helper('url');
        $this->load->helper('image_compressor');


        //$language = $this->db->get('settings')->row()->language;
        $this->load->model('ion_auth_model');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group('admin')) {
            redirect('home/permission');
        }
    }

    public function index()
    {
        $user = $this->ion_auth->user()->row();
        if ($user && ($user->email === 'ronaldi2040@gmail.com' || strtolower($user->username) === 'superadmin')) {
            redirect('superadmin/settings');
        }

        $data = array();
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('settings', $data);
        $this->load->view('home/footer'); // just the footer file
    }

    public function update()
    {
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $title = $this->input->post('title');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $currency = $this->input->post('currency');
        $unit = $this->input->post('unit');
        $date_format = $this->input->post('date_format');
        $login_title = $this->input->post('login_title');
        $discount = $this->input->post('discount');
        if ($discount === FALSE || $discount === NULL || $discount === '') {
            $discount = 0;
        }
        $buyer  = $this->input->post('buyer') !== null ? (string)$this->input->post('buyer') : '';
        $p_code = $this->input->post('p_code') !== null ? (string)$this->input->post('p_code') : '';
        $timezone = $this->input->post('timezone');
        $low_stock_threshold = $this->input->post('low_stock_threshold');
        $overdue_payment_days = $this->input->post('overdue_payment_days');

        // Sanitize timezone against PHP's identifier list; fall back silently to existing value.
        $current_settings = $this->settings_model->getSettings();
        if (!$timezone || !in_array($timezone, timezone_identifiers_list(), true)) {
            $timezone = $current_settings->timezone ?: 'Africa/Kampala';
        }
        if (!is_numeric($low_stock_threshold) || $low_stock_threshold < 0) {
            $low_stock_threshold = (int) ($current_settings->low_stock_threshold ?? 10);
        }
        if (!is_numeric($overdue_payment_days) || $overdue_payment_days < 0) {
            $overdue_payment_days = (int) ($current_settings->overdue_payment_days ?? 7);
        }

        $user = $this->ion_auth->user()->row();
        $is_superadmin = ($user && ($user->email === 'ronaldi2040@gmail.com' || strtolower($user->username) === 'superadmin')) || $this->ion_auth->in_group('superadmin');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" style="border-radius: 10px; font-weight: 600; margin-bottom: 10px;"><i class="fa-solid fa-triangle-exclamation"></i> ', '</div>');
        
        // Flexible Validation Rules
        $this->form_validation->set_rules('name', 'System Name', 'trim|required|min_length[1]|max_length[100]');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[1]|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[100]');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[1]|max_length[500]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|min_length[1]|max_length[50]');
        $this->form_validation->set_rules('currency', 'Currency', 'trim|required|min_length[1]|max_length[20]');
        $this->form_validation->set_rules('unit', 'Unit', 'trim|required|min_length[1]|max_length[20]');
        $this->form_validation->set_rules('date_format', 'Date Format', 'trim|required|min_length[1]|max_length[20]');
        
        if ($is_superadmin) {
            $this->form_validation->set_rules('login_title', 'Login Title', 'trim|required|min_length[1]|max_length[100]');
        }

        $upload_dir = $this->get_tenant_upload_path();
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }

        if ($this->form_validation->run() == FALSE) {
            if ($this->input->is_ajax_request()) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array(
                        'status' => 'error',
                        'message' => strip_tags(validation_errors()) ?: 'Validation failed. Please check your input.'
                    )));
            }
            $data = array();
            $data['settings'] = $this->settings_model->getSettings();
            $user = $this->ion_auth->user()->row();
            if ($user && ($user->email === 'ronaldi2040@gmail.com' || strtolower($user->username) === 'superadmin')) {
                $this->load->view('superadmin/header', $data);
            } else {
                $this->load->view('home/dashboard', $data);
            }
            $this->load->view('settings/settings', $data);
            $this->load->view('home/footer');
        } else {
            $user = $this->ion_auth->user()->row();
            $is_superadmin = ($user && ($user->email === 'ronaldi2040@gmail.com' || strtolower($user->username) === 'superadmin')) || $this->ion_auth->in_group('superadmin');

            $data = array(
                'title' => $title,
                'address' => $address,
                'phone' => $phone,
                'email' => $email,
                'currency' => $currency,
                'unit' => $unit,
                'date_format' => $date_format,
                'discount' => $discount,
                'codec_username' => $buyer,
                'codec_purchase_code' => $p_code,
                'timezone' => $timezone,
                'low_stock_threshold' => (int) $low_stock_threshold,
                'overdue_payment_days' => (int) $overdue_payment_days,
            );

            $data['system_vendor'] = $name;
            if (!empty($login_title)) {
                $data['login_title'] = $login_title;
            }

            if ($this->input->post('support_phone') !== null) {
                $data['support_phone'] = trim($this->input->post('support_phone'));
            }
            if ($this->input->post('support_email') !== null) {
                $data['support_email'] = trim($this->input->post('support_email'));
            }
            if ($this->input->post('support_whatsapp') !== null) {
                $data['support_whatsapp'] = trim($this->input->post('support_whatsapp'));
            }
            if ($this->input->post('about_us_url') !== null) {
                $data['about_us_url'] = trim($this->input->post('about_us_url'));
            }
            if ($this->input->post('about_us_heading') !== null) {
                $data['about_us_heading'] = trim($this->input->post('about_us_heading'));
            }
            if ($this->input->post('about_us_subheading') !== null) {
                $data['about_us_subheading'] = trim($this->input->post('about_us_subheading'));
            }
            if ($this->input->post('about_us_vision') !== null) {
                $data['about_us_vision'] = trim($this->input->post('about_us_vision'));
            }
            if ($this->input->post('about_us_mission') !== null) {
                $data['about_us_mission'] = trim($this->input->post('about_us_mission'));
            }
            if ($this->input->post('about_us_purpose') !== null) {
                $data['about_us_purpose'] = trim($this->input->post('about_us_purpose'));
            }
            if ($this->input->post('about_us_commitment') !== null) {
                $data['about_us_commitment'] = trim($this->input->post('about_us_commitment'));
            }

            // Process Light / Primary Logo Upload
            if (!empty($_FILES['img_url']['name'])) {
                $rawName = basename($_FILES['img_url']['name']);
                $ext     = pathinfo($rawName, PATHINFO_EXTENSION);
                $base    = pathinfo($rawName, PATHINFO_FILENAME);
                $base    = preg_replace('/[^A-Za-z0-9._-]+/', '', $base);
                if ($base === '') { $base = 'logo_' . time(); }
                $new_file_name = $base . '_' . time() . ($ext ? '.' . preg_replace('/[^A-Za-z0-9]/', '', $ext) : '');

                $config = array(
                    'file_name'     => $new_file_name,
                    'upload_path'   => $upload_dir,
                    'allowed_types' => 'gif|jpg|png|jpeg|webp|svg|ico',
                    'overwrite'     => FALSE,
                    'max_size'      => 102400, // 100 MB max limit
                );
                $this->load->library('upload', $config);
                $this->upload->initialize($config, TRUE);
                if ($this->upload->do_upload('img_url')) {
                    $path = $this->upload->data();
                    compress_uploaded_image($path['full_path'], 1600, 1600, 85);
                    $data['img_url'] = $this->get_tenant_upload_relative_path($path['file_name']);
                } else {
                    $upload_error = strip_tags($this->upload->display_errors());
                    $this->session->set_flashdata('error', 'Light logo upload error: ' . $upload_error);
                }
            }

            // Process Dark Mode Logo Upload
            if (!empty($_FILES['dark_img_url']['name'])) {
                $rawName = basename($_FILES['dark_img_url']['name']);
                $ext     = pathinfo($rawName, PATHINFO_EXTENSION);
                $base    = pathinfo($rawName, PATHINFO_FILENAME);
                $base    = preg_replace('/[^A-Za-z0-9._-]+/', '', $base);
                if ($base === '') { $base = 'dark_logo_' . time(); }
                $new_file_name = $base . '_dark_' . time() . ($ext ? '.' . preg_replace('/[^A-Za-z0-9]/', '', $ext) : '');

                $config_dark = array(
                    'file_name'     => $new_file_name,
                    'upload_path'   => $upload_dir,
                    'allowed_types' => 'gif|jpg|png|jpeg|webp|svg|ico',
                    'overwrite'     => FALSE,
                    'max_size'      => 102400, // 100 MB max limit
                );
                $this->load->library('upload', $config_dark);
                $this->upload->initialize($config_dark, TRUE);
                if ($this->upload->do_upload('dark_img_url')) {
                    $path = $this->upload->data();
                    compress_uploaded_image($path['full_path'], 1600, 1600, 85);
                    $data['dark_img_url'] = $this->get_tenant_upload_relative_path($path['file_name']);
                } else {
                    $upload_error = strip_tags($this->upload->display_errors());
                    $this->session->set_flashdata('error', 'Dark logo upload error: ' . $upload_error);
                }
            }

            // Process Favicon Upload
            if (!empty($_FILES['favicon_url']['name'])) {
                $rawName = basename($_FILES['favicon_url']['name']);
                $ext     = pathinfo($rawName, PATHINFO_EXTENSION);
                $base    = pathinfo($rawName, PATHINFO_FILENAME);
                $base    = preg_replace('/[^A-Za-z0-9._-]+/', '', $base);
                if ($base === '') { $base = 'favicon_' . time(); }
                $new_file_name = $base . '_fav_' . time() . ($ext ? '.' . preg_replace('/[^A-Za-z0-9]/', '', $ext) : '');

                $config_fav = array(
                    'file_name'     => $new_file_name,
                    'upload_path'   => $upload_dir,
                    'allowed_types' => 'gif|jpg|png|jpeg|webp|svg|ico',
                    'overwrite'     => FALSE,
                    'max_size'      => 102400, // 100 MB max limit
                );
                $this->load->library('upload', $config_fav);
                $this->upload->initialize($config_fav, TRUE);
                if ($this->upload->do_upload('favicon_url')) {
                    $path = $this->upload->data();
                    compress_uploaded_image($path['full_path'], 512, 512, 90);
                    $data['favicon_url'] = $this->get_tenant_upload_relative_path($path['file_name']);
                } else {
                    $upload_error = strip_tags($this->upload->display_errors());
                    $this->session->set_flashdata('error', 'Favicon upload error: ' . $upload_error);
                }
            }

            if ($this->input->post('mtn_momo_status') !== null || $this->input->post('airtel_money_status') !== null || $this->input->post('flutterwave_status') !== null) {
                $payment_data = array(
                    'mtn_momo_status' => $this->input->post('mtn_momo_status') ?: 'disabled',
                    'mtn_momo_subscription_key' => $this->input->post('mtn_momo_subscription_key'),
                    'mtn_momo_user_id' => $this->input->post('mtn_momo_user_id'),
                    'mtn_momo_api_secret' => $this->input->post('mtn_momo_api_secret'),
                    'mtn_momo_environment' => $this->input->post('mtn_momo_environment') ?: 'sandbox',

                    'airtel_money_status' => $this->input->post('airtel_money_status') ?: 'disabled',
                    'airtel_money_client_id' => $this->input->post('airtel_money_client_id'),
                    'airtel_money_client_secret' => $this->input->post('airtel_money_client_secret'),
                    'airtel_money_merchant_id' => $this->input->post('airtel_money_merchant_id'),
                    'airtel_money_environment' => $this->input->post('airtel_money_environment') ?: 'sandbox',

                    'flutterwave_status' => $this->input->post('flutterwave_status') ?: 'disabled',
                    'flutterwave_public_key' => $this->input->post('flutterwave_public_key'),
                    'flutterwave_secret_key' => $this->input->post('flutterwave_secret_key'),
                    'flutterwave_environment' => $this->input->post('flutterwave_environment') ?: 'sandbox',

                    'stripe_status' => $this->input->post('stripe_status') ?: 'disabled',
                    'stripe_publishable_key' => $this->input->post('stripe_publishable_key'),
                    'stripe_secret_key' => $this->input->post('stripe_secret_key'),

                    'mpesa_status' => $this->input->post('mpesa_status') ?: 'disabled',
                    'mpesa_consumer_key' => $this->input->post('mpesa_consumer_key'),
                    'mpesa_consumer_secret' => $this->input->post('mpesa_consumer_secret'),
                    'mpesa_shortcode' => $this->input->post('mpesa_shortcode'),
                    'mpesa_passkey' => $this->input->post('mpesa_passkey'),
                );
                $data = array_merge($data, $payment_data);
            }

            $this->settings_model->updateSettings($id, $data);

            if (!$this->session->flashdata('error')) {
                $this->session->set_flashdata('success', 'Settings updated successfully.');
            }

            if ($this->input->is_ajax_request()) {
                $fresh_settings = $this->settings_model->getSettings();
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array(
                        'status' => 'success',
                        'message' => 'Settings updated successfully.',
                        'light_logo_url' => get_light_logo_url($fresh_settings),
                        'dark_logo_url' => get_dark_logo_url($fresh_settings),
                        'favicon_url' => get_favicon_url($fresh_settings)
                    )));
            }

            $referer = $this->input->server('HTTP_REFERER');
            $user = $this->ion_auth->user()->row();
            if (!empty($referer) && strpos($referer, base_url()) !== false) {
                redirect($referer);
            } else if ($user && ($user->email === 'ronaldi2040@gmail.com' || strtolower($user->username) === 'superadmin')) {
                redirect('superadmin/settings');
            } else {
                redirect(tenant_url('settings'));
            }
        }
    }

    public function language()
    {
        $id = $this->input->post('id');
        $language = $this->input->post('language');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        // Validating Name Field
        $this->form_validation->set_rules('language', 'Language', 'trim|required|min_length[2]|max_length[100]');


        if ($this->form_validation->run() == FALSE) {
            $data = array();
            $data['settings'] = $this->settings_model->getSettings();
            $this->load->view('home/dashboard', $data); // just the header file
            $this->load->view('language', $data);
            $this->load->view('home/footer'); // just the footer file
        } else {

            //$error = array('error' => $this->upload->display_errors());
            $data = array();
            $data = array(
                'language' => $language
            );

            $this->settings_model->updateSettings($id, $data);
            $this->session->set_userdata('language', $language);

            // Loading View
            $this->session->set_flashdata('feedback', 'Updated');
            redirect('settings/languageSettings');
        }
    }

    public function switch_language($lang = 'english')
    {
        parent::switch_language($lang);
    }

    function languageSettings()
    {
        $data = array();
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data);
        $this->load->view('language', $data);
        $this->load->view('home/footer');
    }

    function backups()
    {
        $data['files'] = glob('./files/backups/*.zip', GLOB_BRACE);
        $data['dbs'] = glob('./files/backups/*.txt', GLOB_BRACE);
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data);
        $this->load->view('backups', $data);
        $this->load->view('home/footer');
    }

    function backup_database()
    {
        if (!$this->ion_auth->in_group('admin')) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("settings/permission");
        }
        $this->load->dbutil();
        $prefs = array(
            'format' => 'txt',
            'filename' => 'spos_db_backup.sql'
        );
        $back = $this->dbutil->backup($prefs);
        $backup = &$back;
        $db_name = 'db-backup-on-' . date("Y-m-d-H-i-s") . '.txt';
        $save = './files/backups/' . $db_name;
        $this->load->helper('file');
        write_file($save, $backup);
        $this->session->set_flashdata('message', 'Database backup Successfully!');
        redirect("settings/backups");
    }

    private function _safe_backup_path($dbfile)
    {
        if (!preg_match('/^[A-Za-z0-9_\-]+$/', $dbfile)) {
            show_error('Invalid backup file name.', 400);
        }
        $path = realpath('./files/backups/' . $dbfile . '.txt');
        $base = realpath('./files/backups/');
        if ($path === FALSE || $base === FALSE || strpos($path, $base) !== 0) {
            show_error('Backup file not found.', 404);
        }
        return $path;
    }

    function restore_database($dbfile = NULL)
    {
        if (!$this->ion_auth->in_group('admin')) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("settings/permission");
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app restore button.', 400);
        }
        $dbfile = $this->input->post('dbfile');
        $path = $this->_safe_backup_path($dbfile);
        $file = file_get_contents($path);
        if ($this->db->conn_id->multi_query($file)) {
            do {
                if ($result = $this->db->conn_id->store_result()) {
                    $result->free();
                }
            } while ($this->db->conn_id->more_results() && $this->db->conn_id->next_result());
        }
        $this->db->conn_id->close();
        $this->session->set_flashdata('message', 'Restoring of Backup Successfully');
        redirect('settings/backups');
    }

    function download_database($dbfile)
    {
        if (!$this->ion_auth->in_group('admin')) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("settings/permission");
        }
        $path = $this->_safe_backup_path($dbfile);
        $this->load->library('zip');
        $this->zip->read_file($path);
        $name = 'db_backup_' . date('Y_m_d_H_i_s') . '.zip';
        $this->zip->download($name);
        exit();
    }

    function delete_database($dbfile = NULL)
    {
        if (!$this->ion_auth->in_group('admin')) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("settings/permission");
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $dbfile = $this->input->post('dbfile');
        $path = $this->_safe_backup_path($dbfile);
        unlink($path);
        $this->session->set_flashdata('info', 'Deleting of Database Successfully');
        redirect("settings/backups");
    }

    function permission()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data);
        $this->load->view('permission', $data);
        $this->load->view('home/footer');
    }




    /* =========================================== Unit =========================================== */

    public function listUnit()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['units'] = $this->settings_model->getUnit();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_unit', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertUnit()
    {
        $un_id = $this->input->post('un_id');
        $name = $this->input->post('un_name');
        $description = $this->input->post('un_description');
        if (empty($un_id)) {
            $data = array(
                'un_name' => $name,
                'un_description' => $description,
                'un_status' => 1,
                'un_created_at' => get_current_time(),
                'un_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->settings_model->insertData('unit', $data);
            $this->session->set_flashdata('success', lang('unit') . ' ' . lang('added') . ' ' . lang('successfully') . '.');
            redirect('settings/listUnit');
        } else {
            $updateData = array(
                'un_name' => $name,
                'un_description' => $description,
                'un_updated_at' => get_current_time(),
                'un_updated_by' => $this->ion_auth->user()->row()->user_id
            );
            if (!empty($un_id)) {
                $this->settings_model->updateData('unit', 'un_id', $un_id, $updateData);
            }
            $this->session->set_flashdata('success', lang('unit') . ' ' . lang('updated') . ' ' . lang('successfully') . '.');
            redirect('settings/listUnit');
        }
    }

    function editUnitByJason()
    {
        $id = $this->input->get('un_id');
        $data['units'] = $this->settings_model->getUnitById($id);
        echo json_encode($data);
    }

    function deleteUnit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $un_id = $this->input->post('un_id');
        $data = array(
            'un_status' => 0,
            'un_updated_at' => get_current_time(),
            'un_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->settings_model->updateData('unit', 'un_id', $un_id, $data);
        $this->session->set_flashdata('success', lang('unit') . ' ' . lang('deleted') . ' ' . lang('successfully') . '.');
        redirect('settings/listUnit');
    }

    /* ===================== Trash bin (v1.3.0) ===================== */

    /**
     * Whitelist of tables surfaced in the trash bin. Adding a row here
     * exposes its soft-deleted records on the trash page.
     *
     * Each entry: id_col, status_col, updated_at_col, updated_by_col,
     * label_col (or null to fall back to id), and a human module label.
     */
    private function _trashTables()
    {
        return array(
            'livestock' => array(
                'id' => 'ls_id', 'status' => 'ls_status',
                'updated_at' => 'ls_updated_at', 'updated_by' => 'ls_updated_by',
                'label' => 'ls_name', 'module' => 'Livestock',
            ),
            'client' => array(
                'id' => 'c_id', 'status' => 'c_status',
                'updated_at' => 'c_updated_at', 'updated_by' => 'c_updated_by',
                'label' => 'c_name', 'module' => 'Clients',
            ),
            'supplier' => array(
                'id' => 's_id', 'status' => 's_status',
                'updated_at' => 's_updated_at', 'updated_by' => 's_updated_by',
                'label' => 's_name', 'module' => 'Suppliers',
            ),
            'staff' => array(
                'id' => 'sf_id', 'status' => 'sf_status',
                'updated_at' => 'sf_updated_at', 'updated_by' => 'sf_updated_by',
                'label' => 'sf_name', 'module' => 'Staff',
            ),
            'shed' => array(
                'id' => 'sh_id', 'status' => 'sh_status',
                'updated_at' => 'sh_updated_at', 'updated_by' => 'sh_updated_by',
                'label' => 'sh_title', 'module' => 'Sheds',
            ),
            'livestock_purchase_summary' => array(
                'id' => 'purs_id', 'status' => 'purs_status',
                'updated_at' => 'purs_updated_at', 'updated_by' => 'purs_updated_by',
                'label' => 'purs_bill_no', 'module' => 'Livestock Purchases',
            ),
            'livestock_sale_summary' => array(
                'id' => 'lsss_id', 'status' => 'lsss_status',
                'updated_at' => 'lsss_updated_at', 'updated_by' => 'lsss_updated_by',
                'label' => null, 'module' => 'Livestock Sales',
            ),
            'product_sale_summary' => array(
                'id' => 'prss_id', 'status' => 'prss_status',
                'updated_at' => 'prss_updated_at', 'updated_by' => 'prss_updated_by',
                'label' => null, 'module' => 'Product Sales',
            ),
            'expense' => array(
                'id' => 'ex_id', 'status' => 'ex_status',
                'updated_at' => 'ex_updated_at', 'updated_by' => 'ex_updated_by',
                'label' => 'ex_name', 'module' => 'Expenses',
            ),
        );
    }

    public function trash()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['trash_tables'] = $this->_trashTables();
        $data['trash_rows'] = array();
        foreach ($data['trash_tables'] as $table => $meta) {
            $this->db->where($meta['status'], 0);
            $this->db->order_by($meta['updated_at'], 'desc');
            $this->db->limit(50);
            $rows = $this->db->get($table)->result();
            $data['trash_rows'][$table] = $rows;
        }
        $this->load->view('home/dashboard', $data);
        $this->load->view('trash', $data);
        $this->load->view('home/footer');
    }

    public function restore()
    {
        // CSRF: per-session action token, matching v1.1 hardened endpoints.
        if (!verify_action_token()) {
            show_error('Invalid request.', 400);
            return;
        }
        $table = (string) $this->input->post('table');
        $id    = (int) $this->input->post('id');
        $whitelist = $this->_trashTables();
        if (!isset($whitelist[$table]) || $id <= 0) {
            show_error('Invalid request.', 400);
            return;
        }
        $meta = $whitelist[$table];
        $this->db->where($meta['id'], $id)->update($table, array(
            $meta['status']     => 1,
            $meta['updated_at'] => get_current_time(),
            $meta['updated_by'] => $this->ion_auth->user()->row()->user_id,
        ));
        $this->session->set_flashdata('feedback', lang('restored'));
        redirect('settings/trash');
    }
}

/* End of file settings.php */
/* Location: ./application/modules/settings/controllers/settings.php */
