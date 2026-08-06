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
        $data['total_livestock_purchased_amount'] = $this->purchase_model->getTotalLivestockPurchasedAmount();
        $data['foods'] = $this->food_model->getFood();
        $data['settings'] = $this->settings_model->getSettings();
        $data['clients'] = $this->client_model->getClient();
        $data['sheds'] = $this->shed_model->getShed();
        $data['suppliers'] = $this->supplier_model->getData('supplier', 's_status', 1);
        $data['recent_activity'] = $this->home_model->getRecentActivity(10);

        // Auto scan farm alerts
        $this->notification_model->auto_generate_farm_alerts();

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
