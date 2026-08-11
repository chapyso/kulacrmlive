<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('ion_auth_model');
        $this->load->library('upload');
        $this->load->model('client/client_model');
        $this->load->model('supplier/supplier_model');
        $this->load->model('purchase/purchase_model');
        $this->load->model('settings/settings_model');
        $language = $this->get_language();
        $load_lang = (strtolower($language) === 'luganda' && is_dir(APPPATH . 'language/Luganda')) ? 'Luganda' : $language;
        $this->lang->load('system_syntax', $load_lang);
        $this->load->model('shed/shed_model');
        $this->load->model('sale/sale_model');
        $this->load->model('expense/expense_model');
        $this->load->model('livestock/livestock_model');
        $this->load->model('food/food_model');
        $this->load->model('report/report_model');
        $this->load->model('settings/settings_model');
        $this->load->model('notification_model');
        $this->load->model('home_model');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index()
    {
        $data = array();
        try {
            $data['total_livestock_purchased_amount'] = (isset($this->purchase_model) && method_exists($this->purchase_model, 'getTotalLivestockPurchasedAmount')) ? $this->purchase_model->getTotalLivestockPurchasedAmount() : 0;
        } catch (Throwable $e) { $data['total_livestock_purchased_amount'] = 0; }
        
        try {
            $data['foods'] = (isset($this->food_model) && method_exists($this->food_model, 'getFood')) ? $this->food_model->getFood() : array();
        } catch (Throwable $e) { $data['foods'] = array(); }
        
        try {
            $data['settings'] = (isset($this->settings_model) && method_exists($this->settings_model, 'getSettings')) ? $this->settings_model->getSettings() : null;
        } catch (Throwable $e) { $data['settings'] = null; }
        
        try {
            $data['clients'] = (isset($this->client_model) && method_exists($this->client_model, 'getClient')) ? $this->client_model->getClient() : array();
        } catch (Throwable $e) { $data['clients'] = array(); }
        
        try {
            $data['sheds'] = (isset($this->shed_model) && method_exists($this->shed_model, 'getShed')) ? $this->shed_model->getShed() : array();
        } catch (Throwable $e) { $data['sheds'] = array(); }
        
        try {
            $data['suppliers'] = (isset($this->supplier_model) && method_exists($this->supplier_model, 'getData')) ? $this->supplier_model->getData('supplier', 's_status', 1) : array();
        } catch (Throwable $e) { $data['suppliers'] = array(); }
        
        try {
            $data['recent_activity'] = (isset($this->home_model) && method_exists($this->home_model, 'getRecentActivity')) ? $this->home_model->getRecentActivity(10) : array();
        } catch (Throwable $e) { $data['recent_activity'] = array(); }

        // Auto scan farm alerts
        try {
            if (isset($this->notification_model) && method_exists($this->notification_model, 'auto_generate_farm_alerts')) {
                $this->notification_model->auto_generate_farm_alerts();
            }
        } catch (Throwable $e) {}

        $this->load->view('dashboard', $data); // just the header file
        $this->load->view('home', $data);
        $this->load->view('footer');
    }

    public function getNotifications()
    {
        $this->notification_model->auto_generate_farm_alerts();
        $unread_count = $this->notification_model->get_unread_count();
        $notifications = $this->notification_model->get_recent_notifications();

        echo json_encode(array(
            'status' => 'success',
            'unread_count' => $unread_count,
            'notifications' => $notifications
        ));
    }

    public function markNotificationAsRead()
    {
        $id = $this->input->post('id');
        if ($id) {
            $this->notification_model->mark_as_read($id);
        }
        echo json_encode(array('status' => 'success'));
    }

    public function markAllNotificationsAsRead()
    {
        $this->notification_model->mark_all_as_read();
        echo json_encode(array('status' => 'success'));
    }

    public function permission()
    {
        $this->load->view('permission');
    }

    public function switch_language($lang = 'english')
    {
        parent::switch_language($lang);
    }
}

/* End of file home.php */
/* Location: ./application/modules/home/controllers/home.php */
