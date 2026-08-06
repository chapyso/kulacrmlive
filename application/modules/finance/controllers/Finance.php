<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Finance extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->model('finance_model');
        $this->load->model('expense/expense_model');
        $this->load->model('settings/settings_model');
        $settings = $this->settings_model->getSettings();
        $language = (!empty($settings) && !empty($settings->language)) ? $settings->language : 'english';
        $this->lang->load('system_syntax', $language);
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin'))) {
            redirect('home/permission');
        }
    }

    public function index()
    {

        redirect('finance/financial_report');
    }

    function financialReport()
    {
        $date_from = strtotime($this->input->post('date_from'));
        $date_to = strtotime($this->input->post('date_to'));
        if (!empty($date_to)) {
            $date_to = $date_to + 86399;
        }
        $data = array();
        $data['products'] = $this->product_model->getProduct();
        $data['categories'] = $this->expense_model->getExpenseCategory();

        $data['purchases'] = $this->purchase_model->getPurchaseByDate($date_from, $date_to);
        $data['sales'] = $this->sale_model->getSaleByDate($date_from, $date_to);
        $data['expenses'] = $this->expense_model->getExpenseByDate($date_from, $date_to);
        $data['from'] = $this->input->post('date_from');
        $data['to'] = $this->input->post('date_to');
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('financial_report', $data);
        $this->load->view('home/footer'); // just the footer fi
    }
}

/* End of file finance.php */
/* Location: ./application/modules/finance/controllers/finance.php */