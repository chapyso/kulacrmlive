<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payments extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('payments_model');
        $this->load->library('upload');
        $this->load->model('settings/settings_model');
        $settings = $this->settings_model->getSettings();
        $language = (!empty($settings) && !empty($settings->language)) ? $settings->language : 'english';
        $this->lang->load('system_syntax', $language);
        $this->load->model('supplier/supplier_model');
        $this->load->model('vaccine/vaccine_model');
        $this->load->model('client/client_model');
        $this->load->model('food/food_model');
        $this->load->model('staff/staff_model');
        $this->load->model('purchase/purchase_model');
        $this->load->model('report/report_model');
        $this->load->model('sale/sale_model');
        $this->load->model('home/home_model');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin'))) {
            redirect('home/permission');
        }
    }

    /* =========================== Supplier Part =========================== */
    public function listSupplierPayments()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $data['settings'] = $this->settings_model->getSettings();
        $data['suppliers'] = $this->supplier_model->getData('supplier', 's_status', 1);

        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_supplier_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function viewSupplierWisePayment()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $id = $this->input->get('id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['supplierById'] = $this->supplier_model->getData('supplier', 's_id', $id)->row();
        $data['purchaseBySupplierId'] = $this->purchase_model->getPurchaseBySupplierId($id);
        $data['foodPurchaseBySupplierId'] = $this->food_model->getFoodPurchaseBySupplierId($id);
        $data['vaccinePurchaseBySupplierId'] = $this->vaccine_model->getVaccinePurchaseBySupplierId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_supplier_wise_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertSupplierPayment()
    {

        $foodPurchaseId = $this->input->post('sp_fdps_id');

        $vaccinePurchaseId = $this->input->post('sp_vps_id');

        $sp_purs_id = $this->input->post('sp_purs_id');

        $sp_s_id = $this->input->post('sp_s_id');
        $sp_payment_amount = (float) $this->input->post('sp_payment_amount');

        // Overpayment guard: the new payment + already-paid total must not exceed the invoice grand total.
        if ($sp_payment_amount <= 0) {
            $this->session->set_flashdata('error', 'Payment amount must be greater than zero.');
            redirect('payments/viewSupplierWisePayment?id=' . $sp_s_id);
        }
        $invoice_total = 0; $already_paid = 0; $redirect_id = $sp_s_id;
        if (!empty($sp_purs_id)) {
            $row = $this->db->select('purs_grand_total')->where('purs_id', $sp_purs_id)->get('livestock_purchase_summary')->row();
            $invoice_total = $row ? (float) $row->purs_grand_total : 0;
            $already_paid  = (float) $this->payments_model->getSumSupplierAndPurchaseWiseTotalPaidAmount($sp_s_id, $sp_purs_id);
        } elseif (!empty($foodPurchaseId)) {
            $row = $this->db->select('fdps_grand_total')->where('fdps_id', $foodPurchaseId)->get('food_purchase_summary')->row();
            $invoice_total = $row ? (float) $row->fdps_grand_total : 0;
            $already_paid  = (float) $this->payments_model->getSumSupplierAndFoodPurchaseWiseTotalPaidAmount($sp_s_id, $foodPurchaseId);
        } elseif (!empty($vaccinePurchaseId)) {
            $row = $this->db->select('vps_grand_total')->where('vps_id', $vaccinePurchaseId)->get('vaccine_purchase_summary')->row();
            $invoice_total = $row ? (float) $row->vps_grand_total : 0;
            $already_paid  = (float) $this->payments_model->getSumSupplierAndVaccinePurchaseWiseTotalPaidAmount($sp_s_id, $vaccinePurchaseId);
        }
        if ($invoice_total > 0 && ($already_paid + $sp_payment_amount) > $invoice_total) {
            $remaining = $invoice_total - $already_paid;
            $this->session->set_flashdata('error', 'Payment exceeds invoice balance. Remaining: ' . number_format_currency($remaining, 2) . '.');
            redirect('payments/viewSupplierWisePayment?id=' . $sp_s_id);
        }

        $sp_paid_by = $this->input->post('sp_paid_by');
        $sp_cheque_no = $this->input->post('sp_cheque_no');
        $sp_reference = $this->input->post('sp_reference');
        $sp_date = $this->input->post('sp_date');
        $sp_date = date("Y-m-d", strtotime($sp_date));
        $sp_description = $this->input->post('sp_description');
        // Live Purchase
        if (!empty($sp_purs_id)) {
            $livePurchaseData = array(
                'sp_s_id' =>  $sp_s_id,
                'sp_purs_id' =>  $sp_purs_id,
                'sp_payment_amount' =>  $sp_payment_amount,
                'sp_paid_by' =>  $sp_paid_by,
                'sp_cheque_no' =>  $sp_cheque_no,
                'sp_reference' =>  $sp_reference,
                'sp_date' =>  $sp_date,
                'sp_description' =>  $sp_description,
                'sp_status' => 1,
                'sp_purchase_type' => 1,
                'sp_created_at' => get_current_time(),
                'sp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('supplier_payment', $livePurchaseData);

            // Food Purchase
        } elseif (!empty($foodPurchaseId)) {
            $foodPurchaseData = array(
                'sp_s_id' =>  $sp_s_id,
                'sp_fdps_id' =>  $foodPurchaseId,
                'sp_payment_amount' =>  $sp_payment_amount,
                'sp_paid_by' =>  $sp_paid_by,
                'sp_cheque_no' =>  $sp_cheque_no,
                'sp_reference' =>  $sp_reference,
                'sp_date' =>  $sp_date,
                'sp_description' =>  $sp_description,
                'sp_status' => 1,
                'sp_purchase_type' => 2,
                'sp_created_at' => get_current_time(),
                'sp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('supplier_payment', $foodPurchaseData);

            // Vaccine Purchase
        } elseif (!empty($vaccinePurchaseId)) {
            $vaccinePurchaseData = array(
                'sp_s_id' =>  $sp_s_id,
                'sp_vps_id' =>  $vaccinePurchaseId,
                'sp_payment_amount' =>  $sp_payment_amount,
                'sp_paid_by' =>  $sp_paid_by,
                'sp_cheque_no' =>  $sp_cheque_no,
                'sp_reference' =>  $sp_reference,
                'sp_date' =>  $sp_date,
                'sp_description' =>  $sp_description,
                'sp_status' => 1,
                'sp_purchase_type' => 3,
                'sp_created_at' => get_current_time(),
                'sp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('supplier_payment', $vaccinePurchaseData);
        }

        $this->session->set_flashdata('success', 'Supplier payment added successfully.');
        redirect("payments/viewSupplierWisePayment?id=$sp_s_id");
    }


    public function updateSupplierPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app edit form.', 400);
        }
        $sp_purs_id = $this->input->post('sp_purs_id');
        $sp_fdps_id = $this->input->post('sp_fdps_id');
        $sp_vps_id = $this->input->post('sp_vps_id');

        $sp_id = $this->input->post('sp_id');
        $sp_payment_amount = (float) $this->input->post('sp_payment_amount');

        // Resolve which redirect to use on error AND which supplier this payment belongs to.
        $existing = $this->db->select('sp_s_id')->where('sp_id', $sp_id)->get('supplier_payment')->row();
        $sp_s_id = $existing ? (int) $existing->sp_s_id : 0;
        $redirectBack = function () use ($sp_purs_id, $sp_fdps_id, $sp_vps_id) {
            if (!empty($sp_purs_id)) return "payments/viewSupplierPurchaseWisePayments?sp_purs_id=$sp_purs_id";
            if (!empty($sp_fdps_id)) return "payments/viewSupplierFoodPurchaseWisePayments?sp_fdps_id=$sp_fdps_id";
            return "payments/viewSupplierVaccinePurchaseWisePayments?sp_vps_id=$sp_vps_id";
        };

        // Overpayment guard: same rule as insert, but exclude THIS payment from already-paid total.
        if ($sp_payment_amount <= 0) {
            $this->session->set_flashdata('error', 'Payment amount must be greater than zero.');
            redirect($redirectBack());
        }
        $invoice_total = 0; $already_paid_others = 0;
        if (!empty($sp_purs_id)) {
            $row = $this->db->select('purs_grand_total')->where('purs_id', $sp_purs_id)->get('livestock_purchase_summary')->row();
            $invoice_total = $row ? (float) $row->purs_grand_total : 0;
            $already_paid_total = (float) $this->payments_model->getSumSupplierAndPurchaseWiseTotalPaidAmount($sp_s_id, $sp_purs_id);
        } elseif (!empty($sp_fdps_id)) {
            $row = $this->db->select('fdps_grand_total')->where('fdps_id', $sp_fdps_id)->get('food_purchase_summary')->row();
            $invoice_total = $row ? (float) $row->fdps_grand_total : 0;
            $already_paid_total = (float) $this->payments_model->getSumSupplierAndFoodPurchaseWiseTotalPaidAmount($sp_s_id, $sp_fdps_id);
        } else {
            $row = $this->db->select('vps_grand_total')->where('vps_id', $sp_vps_id)->get('vaccine_purchase_summary')->row();
            $invoice_total = $row ? (float) $row->vps_grand_total : 0;
            $already_paid_total = (float) $this->payments_model->getSumSupplierAndVaccinePurchaseWiseTotalPaidAmount($sp_s_id, $sp_vps_id);
        }
        // Subtract THIS payment's current amount from already-paid so the new value can replace it.
        $current = $this->db->select('sp_payment_amount')->where('sp_id', $sp_id)->get('supplier_payment')->row();
        $current_amount = $current ? (float) $current->sp_payment_amount : 0;
        $already_paid_others = $already_paid_total - $current_amount;
        if ($invoice_total > 0 && ($already_paid_others + $sp_payment_amount) > $invoice_total) {
            $remaining = $invoice_total - $already_paid_others;
            $this->session->set_flashdata('error', 'Payment exceeds invoice balance. Remaining: ' . number_format_currency($remaining, 2) . '.');
            redirect($redirectBack());
        }

        $sp_paid_by = $this->input->post('sp_paid_by');
        $sp_cheque_no = $this->input->post('sp_cheque_no');
        $sp_reference = $this->input->post('sp_reference');
        $sp_date = $this->input->post('sp_date');
        $sp_date = date("Y-m-d", strtotime($sp_date));
        $sp_description = $this->input->post('sp_description');

        $data = array(
            'sp_payment_amount' =>  $sp_payment_amount,
            'sp_paid_by' =>  $sp_paid_by,
            'sp_cheque_no' =>  $sp_cheque_no,
            'sp_reference' =>  $sp_reference,
            'sp_date' =>  $sp_date,
            'sp_description' =>  $sp_description,
            'sp_updated_at' => get_current_time(),
            'sp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->payments_model->updateData('supplier_payment', 'sp_id', $sp_id, $data);
        $this->session->set_flashdata('success', 'Supplier payment updated successfully.');
        redirect($redirectBack());
    }

    public function deleteSupplierPayments()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $sp_purs_id = $this->input->post('sp_purs_id');
        $sp_fdps_id = $this->input->post('sp_fdps_id');
        $sp_vps_id = $this->input->post('sp_vps_id');
        $sp_id = $this->input->post('sp_id');
        $data = array(
            'sp_status' => 0,
            'sp_updated_at' => get_current_time(),
            'sp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->payments_model->updateData('supplier_payment', 'sp_id', $sp_id, $data);

        $this->session->set_flashdata('success', 'Supplier payment deleted successfully.');
        if (!empty($sp_purs_id)) {
            redirect("payments/viewSupplierPurchaseWisePayments?sp_purs_id=$sp_purs_id");
        } elseif (!empty($sp_fdps_id)) {
            redirect("payments/viewSupplierFoodPurchaseWisePayments?sp_fdps_id=$sp_fdps_id");
        } else {
            redirect("payments/viewSupplierVaccinePurchaseWisePayments?sp_vps_id=$sp_vps_id");
        }
    }

    // View Supplier Purchase Wise Payments
    public function viewSupplierPurchaseWisePayments()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $id = $this->input->get('sp_purs_id');
        $data['purchaseInformationById'] = $this->report_model->getSingleData('livestock_purchase_summary', ['purs_id' => $id, 'purs_status' => 1]);
        $data['settings'] = $this->settings_model->getSettings();
        $data['paymentsByPurchaseId'] = $this->payments_model->getSupplierPaymentsByPurchaseId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_supplier_purchase_wise_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }

    // View Food Supplier Purchase Wise Payments
    public function viewSupplierFoodPurchaseWisePayments()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $id = $this->input->get('sp_fdps_id');
        $data['purchaseInformationById'] = $this->report_model->getSingleData('food_purchase_summary', ['fdps_id' => $id, 'fdps_status' => 1]);
        $data['settings'] = $this->settings_model->getSettings();
        $data['paymentsByFoodPurchaseId'] = $this->payments_model->getFoodSupplierPaymentsByPurchaseId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_food_supplier_purchase_wise_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }

    // View Vaccine Supplier Purchase Wise Payments
    public function viewSupplierVaccinePurchaseWisePayments()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $id = $this->input->get('sp_vps_id');
        $data['purchaseInformationById'] = $this->report_model->getSingleData('vaccine_purchase_summary', ['vps_id' => $id, 'vps_status' => 1]);
        $data['settings'] = $this->settings_model->getSettings();
        $data['paymentsByVaccinePurchaseId'] = $this->payments_model->getVaccineSupplierPaymentsByPurchaseId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_vaccine_supplier_purchase_wise_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }

    // View Supplier Wise Supplier Payments
    public function viewSupplierWiseSupplierPayments()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $id = $this->input->get('id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['paymentsBySupplier'] = $this->payments_model->getSupplierWiseSupplierPayments($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_supplier_wise_supplier_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }


    /* =========================== Client Part =========================== */
    /* =========================== Client Part =========================== */

    public function listClientPayments()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $data['settings'] = $this->settings_model->getSettings();
        $data['clients'] = $this->client_model->getClient();

        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_client_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function viewClientWisePayment()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $id = $this->input->get('c_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['clientById'] = $this->client_model->getClientById($id);
        $data['saleByClientId'] = $this->sale_model->getSaleByClientId($id);
        $data['saleProductsByClientId'] = $this->sale_model->getProductSaleByClientId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_client_wise_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }


    public function insertClientPayment()
    {
        $cp_prss_id = $this->input->post('cp_prss_id');
        $cp_lsss_id = $this->input->post('cp_lsss_id');

        $cp_c_id = $this->input->post('cp_c_id');
        $cp_received_amount = (float) $this->input->post('cp_received_amount');

        // Overpayment guard: receipt + already-received total must not exceed invoice grand total.
        if ($cp_received_amount <= 0) {
            $this->session->set_flashdata('error', 'Received amount must be greater than zero.');
            redirect('payments/viewClientWisePayment?c_id=' . $cp_c_id);
        }
        $invoice_total = 0; $already_received = 0;
        if (!empty($cp_lsss_id)) {
            $row = $this->db->select('lsss_grand_total')->where('lsss_id', $cp_lsss_id)->get('livestock_sale_summary')->row();
            $invoice_total = $row ? (float) $row->lsss_grand_total : 0;
            $already_received = (float) $this->payments_model->getSumClientAndSaleWiseTotalReceivedAmount($cp_c_id, $cp_lsss_id);
        } elseif (!empty($cp_prss_id)) {
            $row = $this->db->select('prss_grand_total')->where('prss_id', $cp_prss_id)->get('product_sale_summary')->row();
            $invoice_total = $row ? (float) $row->prss_grand_total : 0;
            $already_received = (float) $this->payments_model->getSumClientAndProductSaleWiseTotalReceivedAmount($cp_c_id, $cp_prss_id);
        }
        if ($invoice_total > 0 && ($already_received + $cp_received_amount) > $invoice_total) {
            $remaining = $invoice_total - $already_received;
            $this->session->set_flashdata('error', 'Receipt exceeds invoice balance. Remaining: ' . number_format_currency($remaining, 2) . '.');
            redirect('payments/viewClientWisePayment?c_id=' . $cp_c_id);
        }

        $cp_paid_by = $this->input->post('cp_paid_by');
        $cp_cheque_no = $this->input->post('cp_cheque_no');
        $cp_reference = $this->input->post('cp_reference');
        $cp_date = $this->input->post('cp_date');
        $cp_date = date("Y-m-d", strtotime($cp_date));
        $cp_description = $this->input->post('cp_description');
        if (!empty($cp_lsss_id)) {
            $data = array(
                'cp_c_id' =>  $cp_c_id,
                'cp_lsss_id' =>  $cp_lsss_id,
                'cp_received_amount' =>  $cp_received_amount,
                'cp_paid_by' =>  $cp_paid_by,
                'cp_cheque_no' =>  $cp_cheque_no,
                'cp_reference' =>  $cp_reference,
                'cp_date' =>  $cp_date,
                'cp_description' =>  $cp_description,
                'cp_status' => 1,
                'cp_sale_type' => 1,
                'cp_created_at' => get_current_time(),
                'cp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('client_payment', $data);
        } elseif (!empty($cp_prss_id)) {
            $data = array(
                'cp_c_id' =>  $cp_c_id,
                'cp_prss_id' =>  $cp_prss_id,
                'cp_received_amount' =>  $cp_received_amount,
                'cp_paid_by' =>  $cp_paid_by,
                'cp_cheque_no' =>  $cp_cheque_no,
                'cp_reference' =>  $cp_reference,
                'cp_date' =>  $cp_date,
                'cp_description' =>  $cp_description,
                'cp_status' => 1,
                'cp_sale_type' => 2,
                'cp_created_at' => get_current_time(),
                'cp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('client_payment', $data);
        }

        $this->session->set_flashdata('success', 'Client payment added successfully.');
        redirect("payments/viewClientWisePayment?c_id=$cp_c_id");
    }

    public function updateClientPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app edit form.', 400);
        }
        $cp_lsss_id = $this->input->post('cp_lsss_id');
        $cp_prss_id = $this->input->post('cp_prss_id');
        $cp_id = $this->input->post('cp_id');
        $cp_received_amount = (float) $this->input->post('cp_received_amount');

        $existing = $this->db->select('cp_c_id, cp_received_amount')->where('cp_id', $cp_id)->get('client_payment')->row();
        $cp_c_id = $existing ? (int) $existing->cp_c_id : 0;
        $current_amount = $existing ? (float) $existing->cp_received_amount : 0;
        $redirectBack = function () use ($cp_lsss_id, $cp_prss_id) {
            if (!empty($cp_lsss_id)) return "payments/viewClientSaleWisePayments?cp_lsss_id=$cp_lsss_id";
            return "payments/viewClientProductSaleWisePayments?cp_prss_id=$cp_prss_id";
        };

        // Overpayment guard: same rule as insert, but exclude THIS payment from already-received total.
        if ($cp_received_amount <= 0) {
            $this->session->set_flashdata('error', 'Received amount must be greater than zero.');
            redirect($redirectBack());
        }
        $invoice_total = 0; $already_received_total = 0;
        if (!empty($cp_lsss_id)) {
            $row = $this->db->select('lsss_grand_total')->where('lsss_id', $cp_lsss_id)->get('livestock_sale_summary')->row();
            $invoice_total = $row ? (float) $row->lsss_grand_total : 0;
            $already_received_total = (float) $this->payments_model->getSumClientAndSaleWiseTotalReceivedAmount($cp_c_id, $cp_lsss_id);
        } elseif (!empty($cp_prss_id)) {
            $row = $this->db->select('prss_grand_total')->where('prss_id', $cp_prss_id)->get('product_sale_summary')->row();
            $invoice_total = $row ? (float) $row->prss_grand_total : 0;
            $already_received_total = (float) $this->payments_model->getSumClientAndProductSaleWiseTotalReceivedAmount($cp_c_id, $cp_prss_id);
        }
        $already_received_others = $already_received_total - $current_amount;
        if ($invoice_total > 0 && ($already_received_others + $cp_received_amount) > $invoice_total) {
            $remaining = $invoice_total - $already_received_others;
            $this->session->set_flashdata('error', 'Receipt exceeds invoice balance. Remaining: ' . number_format_currency($remaining, 2) . '.');
            redirect($redirectBack());
        }

        $cp_paid_by = $this->input->post('cp_paid_by');
        $cp_cheque_no = $this->input->post('cp_cheque_no');
        $cp_reference = $this->input->post('cp_reference');
        $cp_date = $this->input->post('cp_date');
        $cp_date = date("Y-m-d", strtotime($cp_date));
        $cp_description = $this->input->post('cp_description');

        $data = array(
            'cp_received_amount' =>  $cp_received_amount,
            'cp_paid_by' =>  $cp_paid_by,
            'cp_cheque_no' =>  $cp_cheque_no,
            'cp_reference' =>  $cp_reference,
            'cp_date' =>  $cp_date,
            'cp_description' =>  $cp_description,
            'cp_updated_at' => get_current_time(),
            'cp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->payments_model->updateData('client_payment', 'cp_id', $cp_id, $data);
        $this->session->set_flashdata('success', 'Client payment updated successfully.');
        redirect($redirectBack());
    }

    public function deleteClientPayments()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $cp_lsss_id = $this->input->post('cp_lsss_id');
        $cp_prss_id = $this->input->post('cp_prss_id');
        $cp_id = $this->input->post('cp_id');
        $data = array(
            'cp_status' => 0,
            'cp_updated_at' => get_current_time(),
            'cp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->payments_model->updateData('client_payment', 'cp_id', $cp_id, $data);
        $this->session->set_flashdata('success', 'Client payment deleted successfully.');
        if (!empty($cp_lsss_id)) {
            redirect("payments/viewClientSaleWisePayments?cp_lsss_id=$cp_lsss_id");
        } elseif (!empty($cp_prss_id)) {
            redirect("payments/viewClientProductSaleWisePayments?cp_prss_id=$cp_prss_id");
        }
    }


    // View Client Sale Wise Payments
    public function viewClientSaleWisePayments()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $id = $this->input->get('cp_lsss_id');
        $data['saleInformationById'] = $this->report_model->getSingleData('livestock_sale_summary', ['lsss_id' => $id, 'lsss_status' => 1]);
        $data['settings'] = $this->settings_model->getSettings();
        $data['paymentsBySaleId'] = $this->payments_model->getClientPaymentsBySaleId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_client_sale_wise_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }

    // View Client product Sale Wise Payments
    public function viewClientProductSaleWisePayments()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $id = $this->input->get('cp_prss_id');
        $data['saleInformationById'] = $this->report_model->getSingleData('product_sale_summary', ['prss_id' => $id, 'prss_status' => 1]);
        $data['settings'] = $this->settings_model->getSettings();
        $data['paymentsProductsBySaleId'] = $this->payments_model->getClientProductPaymentsBySaleId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_product_client_sale_wise_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }


    // View Client Wise Client Payments
    public function viewClientWiseClientPayments()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $id = $this->input->get('id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['paymentsByClient'] = $this->payments_model->getClientWiseClientPayments($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_client_wise_client_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }



    /* =========================== Staff Part =========================== */

    public function listStaffPayments()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $data['settings'] = $this->settings_model->getSettings();
        $data['staffs'] = $this->staff_model->getStaff();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_staff_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function viewStaffWisePayment()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $id = $this->input->get('sf_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['staffPayments'] = $this->payments_model->getStaffWisePayments($id);
        $data['staffById'] = $this->staff_model->getStaffById($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_staff_wise_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertStaffPayment()
    {
        $sfp_sf_id = $this->input->post('sfp_sf_id');
        $sfp_payment_amount = $this->input->post('sfp_payment_amount');
        $sfp_paid_by = $this->input->post('sfp_paid_by');
        $sfp_cheque_no = $this->input->post('sfp_cheque_no');
        $sfp_reference = $this->input->post('sfp_reference');
        $sfp_date = $this->input->post('sfp_date');
        $sfp_date = date("Y-m-d", strtotime($sfp_date));
        $sfp_description = $this->input->post('sfp_description');

        $data = array(
            'sfp_sf_id' =>  $sfp_sf_id,
            'sfp_payment_amount' =>  $sfp_payment_amount,
            'sfp_paid_by' =>  $sfp_paid_by,
            'sfp_cheque_no' =>  $sfp_cheque_no,
            'sfp_reference' =>  $sfp_reference,
            'sfp_date' =>  $sfp_date,
            'sfp_description' =>  $sfp_description,
            'sfp_status' => 1,
            'sfp_created_at' => get_current_time(),
            'sfp_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->payments_model->insertData('staff_payment', $data);
        $this->session->set_flashdata('success', 'Staff payment added successfully.');
        redirect("payments/viewStaffWisePayment?sf_id=$sfp_sf_id");
    }

    public function updateStaffPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app edit form.', 400);
        }
        $sfp_sf_id = $this->input->post('sfp_sf_id');
        $sfp_id = $this->input->post('sfp_id');
        $sfp_payment_amount = $this->input->post('sfp_payment_amount');
        $sfp_paid_by = $this->input->post('sfp_paid_by');
        $sfp_cheque_no = $this->input->post('sfp_cheque_no');
        $sfp_reference = $this->input->post('sfp_reference');
        $sfp_date = $this->input->post('sfp_date');
        $sfp_date = date("Y-m-d", strtotime($sfp_date));
        $sfp_description = $this->input->post('sfp_description');

        $updateData = array(
            'sfp_payment_amount' =>  $sfp_payment_amount,
            'sfp_paid_by' =>  $sfp_paid_by,
            'sfp_cheque_no' =>  $sfp_cheque_no,
            'sfp_reference' =>  $sfp_reference,
            'sfp_date' =>  $sfp_date,
            'sfp_description' =>  $sfp_description,
            'sfp_updated_at' => get_current_time(),
            'sfp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->payments_model->updateData('staff_payment', 'sfp_id', $sfp_id, $updateData);
        $this->session->set_flashdata('success', 'Staff payment updated successfully.');
        redirect("payments/viewStaffWisePayment?sf_id=$sfp_sf_id");
    }

    public function deleteStaffPayments()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $sfp_sf_id = $this->input->post('sfp_sf_id');
        $sfp_id = $this->input->post('sfp_id');
        $data = array(
            'sfp_status' => 0,
            'sfp_updated_at' => get_current_time(),
            'sfp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->payments_model->updateData('staff_payment', 'sfp_id', $sfp_id, $data);
        $this->session->set_flashdata('success', 'Staff payment deleted successfully.');
        redirect("payments/viewStaffWisePayment?sf_id=$sfp_sf_id");
    }





    // End
}
