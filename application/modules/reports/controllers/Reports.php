<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('settings/settings_model');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $this->check_permission('reports.view');
    }

    public function index() {
        $data = array();
        $data['settings'] = $this->settings_model->getSettings();

        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date   = $this->input->get('end_date') ?: date('Y-m-d');
        $type       = $this->input->get('type') ?: 'sales';

        $data['start_date'] = $start_date;
        $data['end_date']   = $end_date;
        $data['type']       = $type;

        $tenant_id = $this->tenant_id;

        if ($type === 'sales') {
            $data['report_data'] = $this->db->query("SELECT * FROM sale WHERE sale_status = 1 AND tenant_id = ? AND DATE(created_at) BETWEEN ? AND ? ORDER BY id DESC", array($tenant_id, $start_date, $end_date))->result();
        } elseif ($type === 'expenses') {
            $data['report_data'] = $this->db->query("SELECT * FROM expense WHERE ex_status = 1 AND tenant_id = ? AND DATE(created_at) BETWEEN ? AND ? ORDER BY ex_id DESC", array($tenant_id, $start_date, $end_date))->result();
        } elseif ($type === 'livestock') {
            $data['report_data'] = $this->db->query("SELECT * FROM livestock WHERE ls_status = 1 AND tenant_id = ? ORDER BY ls_id DESC", array($tenant_id))->result();
        } else {
            $data['report_data'] = array();
        }

        $this->load->view('home/dashboard', $data);
        $this->load->view('reports/index', $data);
        $this->load->view('home/footer');
    }

    public function export_csv() {
        $this->check_permission('reports.export');
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date   = $this->input->get('end_date') ?: date('Y-m-d');
        $type       = $this->input->get('type') ?: 'sales';
        $tenant_id  = $this->tenant_id;

        filename_header("report_" . $type . "_" . date('Ymd') . ".csv");
        $output = fopen("php://output", "w");

        if ($type === 'sales') {
            fputcsv($output, array('Sale ID', 'Customer', 'Grand Total', 'Discount', 'Status', 'Date'));
            $rows = $this->db->query("SELECT * FROM sale WHERE sale_status = 1 AND tenant_id = ? AND DATE(created_at) BETWEEN ? AND ? ORDER BY id DESC", array($tenant_id, $start_date, $end_date))->result();
            foreach ($rows as $r) {
                fputcsv($output, array($r->id, $r->customer_name ?? 'N/A', $r->sale_grand_total, $r->discount, 'Completed', $r->created_at));
            }
        } else {
            fputcsv($output, array('Expense ID', 'Title', 'Category', 'Amount', 'Date'));
            $rows = $this->db->query("SELECT * FROM expense WHERE ex_status = 1 AND tenant_id = ? AND DATE(created_at) BETWEEN ? AND ? ORDER BY ex_id DESC", array($tenant_id, $start_date, $end_date))->result();
            foreach ($rows as $r) {
                fputcsv($output, array($r->ex_id, $r->ex_name, $r->ex_category, $r->amount, $r->created_at));
            }
        }
        fclose($output);
        exit();
    }
}

function filename_header($filename) {
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");
}
