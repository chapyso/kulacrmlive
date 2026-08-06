<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sale extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->model('settings/settings_model');
        $settings = $this->settings_model->getSettings();
        $language = (!empty($settings) && !empty($settings->language)) ? $settings->language : 'english';
        $this->lang->load('system_syntax', $language);
        $this->load->model('sale_model');
        $this->load->model('purchase/purchase_model');
        $this->load->model('client/client_model');
        $this->load->model('product/product_model');
        $this->load->model('livestock/livestock_model');
        $this->load->model('report/report_model');
        $this->load->model('shed/shed_model');
        $this->load->model('payments/payments_model');
        $this->load->model('settings/settings_model');
        $data['settings'] = $this->settings_model->getSettings();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin', 'Accountant'))) {
            redirect('home/permission');
        }
    }




    /* ==================== New Code By Rimon ==================== */
    /* ==================== Add Sale form ==================== */

    function addNewSale()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['clients'] = $this->client_model->getClient();
        $data['batches'] = $this->shed_model->getBatch();
        $data['sheds'] = $this->shed_model->getShed();
        $data['livestockStoredShedsWithBatch'] = $this->sale_model->getUniqueValueFromAssignedShed();
        $data['lsStoredInSheds'] = $this->sale_model->getUniqueValueFromAssignedLivestock();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('add_new_sale', $data);
        $this->load->view('home/footer'); // just the footer fi
    }
    /* ==================== view livestock sale ==================== */
    public function viewLivestockSale()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $lsss_id = $this->input->get('lsss_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['livestock_sale_by_id'] = $this->sale_model->getLivestockSaleById($lsss_id);
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['LivestockSaleSummaryId'] = $this->sale_model->getLivestockSaleValueBySummaryById($lsss_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_livestock_sale', $data);
        $this->load->view('home/footer'); // just the header file
    }
    /* ==================== list sale ==================== */
    public function listSale()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $from = $this->_validDate($this->input->get('from'));
        $to   = $this->_validDate($this->input->get('to'));
        $data['clients'] = $this->client_model->getClient();
        $data['settings'] = $this->settings_model->getSettings();
        $data['getLivestockSales'] = $this->sale_model->getLivestockSale($from, $to);
        $data['filter_from'] = $from;
        $data['filter_to']   = $to;
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_sale', $data);
        $this->load->view('home/footer'); // just the header file
    }

    private function _validDate($raw)
    {
        $raw = (string) $raw;
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw) ? $raw : '';
    }
    /* ==================== insert sale ==================== */
    public function insertLivestockSale()
    {
        $this->form_validation->set_rules('lss_c_id', 'Client', 'trim|required|integer');
        $this->form_validation->set_rules('lss_date', 'Sale date', 'trim|required');
        $this->form_validation->set_rules('lss_grand_total', 'Grand total', 'trim|required|numeric');
        $this->form_validation->set_rules('lss_ls_id[]', 'Livestock line item', 'required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('sale/addNewSale');
        }
        // Summary
        $client_id = $this->input->post('lss_c_id');
        $date = $this->input->post('lss_date');
        $lss_date = date("Y-m-d", strtotime($date));
        $lss_sub_total = $this->input->post('lss_sub_total');
        $lss_grand_discount = $this->input->post('lss_grand_discount');
        $lss_grand_total = $this->input->post('lss_grand_total');
        $note = $this->input->post('lss_note');
        // Value
        $lss_batch_id = $this->input->post('lss_batch_id');
        $lss_shed_id = $this->input->post('lss_shed_id');
        $lss_ls_id = $this->input->post('lss_ls_id');
        $lss_lst_id = $this->input->post('lss_lst_id');
        $lss_unit_price = $this->input->post('lss_unit_price');
        $lss_quantity = $this->input->post('lss_quantity');
        $lss_discount = $this->input->post('lss_discount');
        $lss_total = $this->input->post('lss_total');

        // Stock availability check per line item: assigned - (sold + died) must cover the requested quantity.
        $requested_per_key = array();
        for ($i = 0; $i < count((array) $lss_unit_price); $i++) {
            $key = $lss_shed_id[$i] . '|' . $lss_batch_id[$i] . '|' . $lss_ls_id[$i] . '|' . $lss_lst_id[$i];
            if (!isset($requested_per_key[$key])) { $requested_per_key[$key] = 0; }
            $requested_per_key[$key] += (float) $lss_quantity[$i];
        }
        foreach ($requested_per_key as $key => $requested) {
            list($sh, $bt, $ls, $lst) = explode('|', $key);
            $assigned = (float) $this->sale_model->getShedAndBatchWiseLivestockPurchaseQuantity($sh, $bt);
            $sold     = (float) $this->sale_model->getShedAndBatchWiseLivestockSaleQuantity($sh, $bt, $ls, $lst);
            $died     = (float) $this->sale_model->getShedAndBatchWiseLivestockDeathQuantity($sh, $bt, $ls, $lst);
            $available = $assigned - ($sold + $died);
            if ($requested > $available) {
                $this->session->set_flashdata('error', 'Insufficient stock for shed ' . $sh . ' / batch ' . $bt . ': requested ' . $requested . ', available ' . $available . '.');
                redirect('sale/addNewSale');
            }
        }

        $this->db->trans_start();
        $summaryData = array(
            'lsss_c_id' => $client_id,
            'lsss_date' => $lss_date,
            'lsss_sub_total' => (empty($lss_sub_total) && $lss_sub_total !== '0') ? 0 : $lss_sub_total,
            'lsss_grand_discount' => (empty($lss_grand_discount) && $lss_grand_discount !== '0') ? 0 : $lss_grand_discount,
            'lsss_grand_total' => (empty($lss_grand_total) && $lss_grand_total !== '0') ? 0 : $lss_grand_total,
            'lsss_note' => $note,
            'lsss_status' => 1,
            'lsss_created_at' => get_current_time(),
            'lsss_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $saleSummaryId = $this->sale_model->insertData('livestock_sale_summary', $summaryData);
        for ($i = 0; $i < count($lss_unit_price); $i++) {
            $ValueData = array(
                'lssv_lsss_id' => $saleSummaryId,
                'lssv_batch_id' => $lss_batch_id[$i],
                'lssv_shed_id' => $lss_shed_id[$i],
                'lssv_ls_id' => $lss_ls_id[$i],
                'lssv_lst_id' => $lss_lst_id[$i],
                'lssv_unit_price' => $lss_unit_price[$i],
                'lssv_quantity' => $lss_quantity[$i],
                'lssv_discount' => (empty($lss_discount[$i]) && $lss_discount[$i] !== '0') ? 0 : $lss_discount[$i],
                'lssv_total' => $lss_total[$i],
                'lssv_status' => 1,
                'lssv_created_at' => get_current_time(),
                'lssv_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->sale_model->insertData('livestock_sale_value', $ValueData);
        }


        // Sale Payment
        $cp_received_amount = $this->input->post('cp_received_amount');
        $cp_paid_by = $this->input->post('cp_paid_by');
        $cp_cheque_no = $this->input->post('cp_cheque_no');
        $cp_reference = $this->input->post('cp_reference');
        $cp_description = $this->input->post('cp_description');
        if (!empty($saleSummaryId) && $cp_received_amount > 0) {
            $liveSalePaymentData = array(
                'cp_c_id' =>  $client_id,
                'cp_lsss_id' =>  $saleSummaryId,
                'cp_received_amount' =>  $cp_received_amount,
                'cp_paid_by' =>  $cp_paid_by,
                'cp_cheque_no' =>  $cp_cheque_no,
                'cp_reference' =>  $cp_reference,
                'cp_date' =>  $lss_date,
                'cp_description' =>  $cp_description,
                'cp_status' => 1,
                'cp_sale_type' => 1,
                'cp_created_at' => get_current_time(),
                'cp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('client_payment', $liveSalePaymentData);
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Sale added successfully.');
        redirect("sale/listSale");
    }

    /* =========================== Edit Livestock Sale form =========================== */
    public function editLivestockSale()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $lsss_id = $this->input->get('lsss_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['sheds'] = $this->shed_model->getShed();
        $data['livestockSaleById'] = $this->sale_model->getLivestockSaleById($lsss_id);
        $data['LivestockSaleSummaryId'] = $this->sale_model->getLivestockSaleValueBySummaryById($lsss_id);
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['clients'] = $this->client_model->getClient();
        $data['batches'] = $this->shed_model->getBatch();
        $data['sheds'] = $this->shed_model->getShed();
        $data['livestockStoredShedsWithBatch'] = $this->sale_model->getUniqueValueFromAssignedShed();
        $data['lsStoredInSheds'] = $this->sale_model->getUniqueValueFromAssignedLivestock();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('edit_livestock_sale', $data);
        $this->load->view('home/footer'); // just the header file
    }
    /* ==================== update livestock sale ==================== */
    public function updateLivestockSale()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app edit form.', 400);
        }
        $this->db->trans_start();
        $lss_id = $this->input->post('lss_id');
        // delete previous data       
        $deletePreviousDataValue = array(
            'lssv_status' => 0,
            'lssv_updated_at' => get_current_time(),
            'lssv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->sale_model->updateData('livestock_sale_value', 'lssv_lsss_id', $lss_id, $deletePreviousDataValue);

        // Summary
        $client_id = $this->input->post('lss_c_id');
        $date = $this->input->post('lss_date');
        $lss_date = date("Y-m-d", strtotime($date));
        $lss_sub_total = $this->input->post('lss_sub_total');
        $lss_grand_discount = $this->input->post('lss_grand_discount');
        $lss_grand_total = $this->input->post('lss_grand_total');
        $note = $this->input->post('lss_note');
        $summaryData = array(
            'lsss_c_id' => $client_id,
            'lsss_date' => $lss_date,
            'lsss_sub_total' => (empty($lss_sub_total) && $lss_sub_total !== '0') ? 0 : $lss_sub_total,
            'lsss_grand_discount' => (empty($lss_grand_discount) && $lss_grand_discount !== '0') ? 0 : $lss_grand_discount,
            'lsss_grand_total' => (empty($lss_grand_total) && $lss_grand_total !== '0') ? 0 : $lss_grand_total,
            'lsss_note' => $note,
            'lsss_updated_at' => get_current_time(),
            'lsss_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->sale_model->updateData('livestock_sale_summary', 'lsss_id', $lss_id, $summaryData);
        // Value
        $lss_batch_id = $this->input->post('lss_batch_id');
        $lss_shed_id = $this->input->post('lss_shed_id');
        $lss_ls_id = $this->input->post('lss_ls_id');
        $lss_lst_id = $this->input->post('lss_lst_id');
        $lss_unit_price = $this->input->post('lss_unit_price');
        $lss_quantity = $this->input->post('lss_quantity');
        $lss_discount = $this->input->post('lss_discount');
        $lss_total = $this->input->post('lss_total');
        for ($i = 0; $i < count($lss_unit_price); $i++) {
            $ValueData = array(
                'lssv_lsss_id' => $lss_id,
                'lssv_batch_id' => $lss_batch_id[$i],
                'lssv_shed_id' => $lss_shed_id[$i],
                'lssv_ls_id' => $lss_ls_id[$i],
                'lssv_lst_id' => $lss_lst_id[$i],
                'lssv_unit_price' => $lss_unit_price[$i],
                'lssv_quantity' => $lss_quantity[$i],
                'lssv_discount' => (empty($lss_discount[$i]) && $lss_discount[$i] !== '0') ? 0 : $lss_discount[$i],
                'lssv_total' => $lss_total[$i],
                'lssv_status' => 1,
                'lssv_created_at' => get_current_time(),
                'lssv_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->sale_model->insertData('livestock_sale_value', $ValueData);
        }



        // If there is any previous payment on this purchase then payments will be deleted. You can add new payment
        $deletePreviousPayments = array(
            'cp_status' => 0,
            'cp_updated_at' => get_current_time(),
            'cp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->payments_model->updateData('client_payment', 'cp_lsss_id', $lss_id, $deletePreviousPayments);


        // Sale Payment
        $cp_received_amount = $this->input->post('cp_received_amount');
        $cp_paid_by = $this->input->post('cp_paid_by');
        $cp_cheque_no = $this->input->post('cp_cheque_no');
        $cp_reference = $this->input->post('cp_reference');
        $cp_description = $this->input->post('cp_description');
        if (!empty($lss_id) && $cp_received_amount > 0) {
            $liveSalePaymentData = array(
                'cp_c_id' =>  $client_id,
                'cp_lsss_id' =>  $lss_id,
                'cp_received_amount' =>  $cp_received_amount,
                'cp_paid_by' =>  $cp_paid_by,
                'cp_cheque_no' =>  $cp_cheque_no,
                'cp_reference' =>  $cp_reference,
                'cp_date' =>  $lss_date,
                'cp_description' =>  $cp_description,
                'cp_status' => 1,
                'cp_sale_type' => 1,
                'cp_created_at' => get_current_time(),
                'cp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('client_payment', $liveSalePaymentData);
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Sale updated successfully.');
        redirect("sale/listSale");
    }

    /* ==================== Delete livestock sale ==================== */
    function deleteLivestockSale()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        $lsss_id = $this->input->post('lsss_id');
        $deleteSummary = array(
            'lsss_status' => 0,
            'lsss_updated_at' => get_current_time(),
            'lsss_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->sale_model->updateData('livestock_sale_summary', 'lsss_id', $lsss_id, $deleteSummary);
        $deleteValue = array(
            'lssv_status' => 0,
            'lssv_updated_at' => get_current_time(),
            'lssv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->sale_model->updateData('livestock_sale_value', 'lssv_lsss_id', $lsss_id, $deleteValue);

        // delete Payments Delete under this invoice
        $deletePayments = array(
            'cp_status' => 0,
            'cp_updated_at' => get_current_time(),
            'cp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->sale_model->updateData('client_payment', 'cp_lsss_id', $lsss_id, $deletePayments);

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Sale deleted successfully.');
        redirect("sale/listSale");
    }










    /* ======================================= Product Sale ======================================= */
    /* =================== List Product Sale =================== */
    public function listProductSale()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $from = $this->_validDate($this->input->get('from'));
        $to   = $this->_validDate($this->input->get('to'));
        $data['clients'] = $this->client_model->getClient();
        $data['settings'] = $this->settings_model->getSettings();
        $data['products'] = $this->product_model->getProduct();
        $data['productsSale'] = $this->sale_model->getProductSale($from, $to);
        $data['filter_from'] = $from;
        $data['filter_to']   = $to;
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_product_sale', $data);
        $this->load->view('home/footer'); // just the header file
    }
    /* ==================== list sale ==================== */
    public function addNewProductSale()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $data['clients'] = $this->client_model->getClient();
        $data['settings'] = $this->settings_model->getSettings();
        $data['products'] = $this->product_model->getProduct();
        $data['assignedProducts1'] = $this->product_model->getProductAssign();

        // Pass data to match with assign summary table and get only active batch data
        $assignedProducts = [];
        foreach ($data['assignedProducts1'] as $values) {
            $batchActiveStatus = $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($values->pra_shed_id, $values->pra_batch_id)->lshs_active_status;
            if ($batchActiveStatus == 0) {
                // Product In Stock
                $totalProduction = $this->product_model->getTotalProductionQuantityByProductAssignId($values->pra_id);
                $productSold = $this->sale_model->getProductAssignedWiseProductSaleValue($values->pra_id, 'prsv_quantity');
                $productWaste = $this->product_model->getTotalProductWastedQuantityByProductAssignId($values->pra_id);
                $productInStock = $totalProduction - ($productSold + $productWaste);
                if ($productInStock > 0) {
                    array_push($assignedProducts, $values);
                }
            }
        }
        $data['assignedProducts'] = $assignedProducts;

        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('add_new_product_sale', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ==================== insert sale ==================== */
    public function insertProductSale()
    {
        // Summary
        $client_id = $this->input->post('prs_c_id');
        $date = $this->input->post('prs_date');
        $prs_date = date("Y-m-d", strtotime($date));
        $prs_sub_total = $this->input->post('prs_sub_total');
        $prs_grand_discount = $this->input->post('prs_grand_discount');
        $prs_grand_total = $this->input->post('prs_grand_total');
        $note = $this->input->post('prs_note');
        // Value
        $prs_product_assign_id = $this->input->post('prs_pra_id');
        $prs_unit_price = $this->input->post('prs_unit_price');
        $prs_quantity = $this->input->post('prs_quantity');
        $prs_discount = $this->input->post('prs_discount');
        $prs_total = $this->input->post('prs_total');

        // Stock availability check per product_assign: production - (sold + waste) must cover requested quantity.
        $requested_per_pra = array();
        for ($i = 0; $i < count((array) $prs_unit_price); $i++) {
            $pra = $prs_product_assign_id[$i];
            if (!isset($requested_per_pra[$pra])) { $requested_per_pra[$pra] = 0; }
            $requested_per_pra[$pra] += (float) $prs_quantity[$i];
        }
        foreach ($requested_per_pra as $pra => $requested) {
            $produced = (float) $this->product_model->getTotalProductionQuantityByProductAssignId($pra);
            $sold     = (float) $this->sale_model->getProductAssignedWiseProductSaleValue($pra, 'prsv_quantity');
            $waste    = (float) $this->product_model->getTotalProductWastedQuantityByProductAssignId($pra);
            $available = $produced - ($sold + $waste);
            if ($requested > $available) {
                $this->session->set_flashdata('error', 'Insufficient product stock for assignment ID ' . $pra . ': requested ' . $requested . ', available ' . $available . '.');
                redirect('sale/addNewProductSale');
            }
        }

        $this->db->trans_start();
        $summaryData = array(
            'prss_c_id' => $client_id,
            'prss_date' => $prs_date,
            'prss_sub_total' => (empty($prs_sub_total) && $prs_sub_total !== '0') ? 0 : $prs_sub_total,
            'prss_grand_discount' => (empty($prs_grand_discount) && $prs_grand_discount !== '0') ? 0 : $prs_grand_discount,
            'prss_grand_total' => (empty($prs_grand_total) && $prs_grand_total !== '0') ? 0 : $prs_grand_total,
            'prss_note' => $note,
            'prss_status' => 1,
            'prss_created_at' => get_current_time(),
            'prss_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $saleSummaryId = $this->sale_model->insertData('product_sale_summary', $summaryData);
        for ($i = 0; $i < count($prs_unit_price); $i++) {
            $ValueData = array(
                'prsv_prss_id' => $saleSummaryId,
                'prsv_pra_id' => $prs_product_assign_id[$i],
                'prsv_product_id' => $this->product_model->getProductAssignById($prs_product_assign_id[$i])->pra_pr_id,
                'prsv_unit_price' => $prs_unit_price[$i],
                'prsv_quantity' => $prs_quantity[$i],
                'prsv_discount' => (empty($prs_discount[$i]) && $prs_discount[$i] !== '0') ? 0 : $prs_discount[$i],
                'prsv_total' => $prs_total[$i],
                'prsv_status' => 1,
                'prsv_created_at' => get_current_time(),
                'prsv_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->sale_model->insertData('product_sale_value', $ValueData);
        }

        // Sale Payment
        $cp_received_amount = $this->input->post('cp_received_amount');
        $cp_paid_by = $this->input->post('cp_paid_by');
        $cp_cheque_no = $this->input->post('cp_cheque_no');
        $cp_reference = $this->input->post('cp_reference');
        $cp_description = $this->input->post('cp_description');
        if (!empty($saleSummaryId) && $cp_received_amount > 0) {
            $productSalePaymentData = array(
                'cp_c_id' =>  $client_id,
                'cp_prss_id' =>  $saleSummaryId,
                'cp_received_amount' =>  $cp_received_amount,
                'cp_paid_by' =>  $cp_paid_by,
                'cp_cheque_no' =>  $cp_cheque_no,
                'cp_reference' =>  $cp_reference,
                'cp_date' =>  $prs_date,
                'cp_description' =>  $cp_description,
                'cp_status' => 1,
                'cp_sale_type' => 2,
                'cp_created_at' => get_current_time(),
                'cp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('client_payment', $productSalePaymentData);
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Product sale added successfully.');
        redirect("sale/listProductSale");
    }

    /* ==================== View Product Sale ==================== */
    public function editProductSale()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $prss_id = $this->input->get('prss_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['clients'] = $this->client_model->getClient();
        $data['products'] = $this->product_model->getProduct();
        $data['productSaleById'] = $this->sale_model->getProductSaleById($prss_id);
        $data['productsSaleSummaryId'] = $this->sale_model->getProductSaleValueByProductId($prss_id);
        $data['assignedProducts1'] = $this->product_model->getProductAssign();

        // Pass data to match with assign summary table and get only active batch data
        $assignedProducts = [];
        foreach ($data['assignedProducts1'] as $values) {
            $batchActiveStatus = $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($values->pra_shed_id, $values->pra_batch_id)->lshs_active_status;
            if ($batchActiveStatus == 0) {
                array_push($assignedProducts, $values);
            }
        }
        $data['assignedProducts'] = $assignedProducts;
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('edit_product_sale', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ==================== update sale ==================== */
    public function updateProductSale()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app edit form.', 400);
        }
        $this->db->trans_start();
        $prss_id = $this->input->post('prss_id');

        // Delete previous value data
        $deleteProductValue = array(
            'prsv_status' => 0,
            'prsv_updated_at' => get_current_time(),
            'prsv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->sale_model->updateData('product_sale_value', 'prsv_prss_id', $prss_id, $deleteProductValue);

        // Summary
        $client_id = $this->input->post('prs_c_id');
        $date = $this->input->post('prs_date');
        $prs_date = date("Y-m-d", strtotime($date));
        $prs_sub_total = $this->input->post('prs_sub_total');
        $prs_grand_discount = $this->input->post('prs_grand_discount');
        $prs_grand_total = $this->input->post('prs_grand_total');
        $note = $this->input->post('prs_note');
        $updateSummaryData = array(
            'prss_c_id' => $client_id,
            'prss_date' => $prs_date,
            'prss_sub_total' => (empty($prs_sub_total) && $prs_sub_total !== '0') ? 0 : $prs_sub_total,
            'prss_grand_discount' => (empty($prs_grand_discount) && $prs_grand_discount !== '0') ? 0 : $prs_grand_discount,
            'prss_grand_total' => (empty($prs_grand_total) && $prs_grand_total !== '0') ? 0 : $prs_grand_total,
            'prss_note' => $note,
            'prss_status' => 1,
            'prss_updated_at' => get_current_time(),
            'prss_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->sale_model->updateData('product_sale_summary', 'prss_id', $prss_id, $updateSummaryData);
        // Value
        $prs_product_assign_id = $this->input->post('prs_pra_id');
        $prs_unit_price = $this->input->post('prs_unit_price');
        $prs_quantity = $this->input->post('prs_quantity');
        $prs_discount = $this->input->post('prs_discount');
        $prs_total = $this->input->post('prs_total');
        for ($i = 0; $i < count($prs_unit_price); $i++) {
            $ValueData = array(
                'prsv_prss_id' => $prss_id,
                'prsv_pra_id' => $prs_product_assign_id[$i],
                'prsv_product_id' => $this->product_model->getProductAssignById($prs_product_assign_id[$i])->pra_pr_id,
                'prsv_unit_price' => $prs_unit_price[$i],
                'prsv_quantity' => $prs_quantity[$i],
                'prsv_discount' => (empty($prs_discount[$i]) && $prs_discount[$i] !== '0') ? 0 : $prs_discount[$i],
                'prsv_total' => $prs_total[$i],
                'prsv_status' => 1,
                'prsv_created_at' => get_current_time(),
                'prsv_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->sale_model->insertData('product_sale_value', $ValueData);
        }


        // If there is any previous payment on this purchase then payments will be deleted. You can add new payment
        $deletePreviousPayments = array(
            'cp_status' => 0,
            'cp_updated_at' => get_current_time(),
            'cp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->payments_model->updateData('client_payment', 'cp_prss_id', $prss_id, $deletePreviousPayments);


        // Sale Payment
        $cp_received_amount = $this->input->post('cp_received_amount');
        $cp_paid_by = $this->input->post('cp_paid_by');
        $cp_cheque_no = $this->input->post('cp_cheque_no');
        $cp_reference = $this->input->post('cp_reference');
        $cp_description = $this->input->post('cp_description');
        if (!empty($prss_id) && $cp_received_amount > 0) {
            $productSalePaymentData = array(
                'cp_c_id' =>  $client_id,
                'cp_prss_id' =>  $prss_id,
                'cp_received_amount' =>  $cp_received_amount,
                'cp_paid_by' =>  $cp_paid_by,
                'cp_cheque_no' =>  $cp_cheque_no,
                'cp_reference' =>  $cp_reference,
                'cp_date' =>  $prs_date,
                'cp_description' =>  $cp_description,
                'cp_status' => 1,
                'cp_sale_type' => 2,
                'cp_created_at' => get_current_time(),
                'cp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('client_payment', $productSalePaymentData);
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Product sale updated successfully.');
        redirect("sale/listProductSale");
    }

    /* ==================== View Product Sale ==================== */
    public function viewProductSale()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $prss_id = $this->input->get('prss_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['productSaleById'] = $this->sale_model->getProductSaleById($prss_id);
        $data['productsSaleSummaryId'] = $this->sale_model->getProductSaleValueByProductId($prss_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_product_sale', $data);
        $this->load->view('home/footer'); // just the header file
    }
    /* ==================== View Product Sale - Client Invoice ==================== */
    public function viewProductSaleClientInvoice()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $prss_id = $this->input->get('prss_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['productSaleById'] = $this->sale_model->getProductSaleById($prss_id);
        $data['productsSaleSummaryId'] = $this->sale_model->getProductSaleValueByProductIdClientInvoice($prss_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_product_sale_client_invoice', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ==================== Delete Product sale ==================== */
    function deleteProductSale()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        $prss_id = $this->input->post('prss_id');
        $deleteProductSummary = array(
            'prss_status' => 0,
            'prss_updated_at' => get_current_time(),
            'prss_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->sale_model->updateData('product_sale_summary', 'prss_id', $prss_id, $deleteProductSummary);
        $deleteProductValue = array(
            'prsv_status' => 0,
            'prsv_updated_at' => get_current_time(),
            'prsv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->sale_model->updateData('product_sale_value', 'prsv_prss_id', $prss_id, $deleteProductValue);

        // delete Payments Delete under this invoice
        $deletePayments = array(
            'cp_status' => 0,
            'cp_updated_at' => get_current_time(),
            'cp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->sale_model->updateData('client_payment', 'cp_prss_id', $prss_id, $deletePayments);

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Product sale deleted successfully.');
        redirect("sale/listProductSale");
    }

    /* ==================== Product Price ==================== */
    public function getProductPriceByProductIdByAjax()
    {
        $pra_id = $this->input->get('pra_id');
        $data['productAssignedById'] = $this->product_model->getProductAssignById($pra_id);
        $data['productById'] = $this->product_model->getProductById($data['productAssignedById']->pra_pr_id);

        $product_unit_id = $data['productById']->pr_unit_id;
        $unitData = $this->settings_model->getUnitById($product_unit_id);
        $data['unit_name'] = $unitData->un_name;

        // Product In Stock
        $totalProduction = $this->product_model->getTotalProductionQuantityByProductAssignId($pra_id);
        $productSold = $this->sale_model->getProductAssignedWiseProductSaleValue($pra_id, 'prsv_quantity');
        $productWaste = $this->product_model->getTotalProductWastedQuantityByProductAssignId($pra_id);
        $data['product_stock'] = $totalProduction - ($productSold + $productWaste);

        echo json_encode($data);
    }






    /* ========== PDF Invoice — Livestock Sale ========== */
    public function downloadLivestockSalePdf()
    {
        $lsss_id = $this->input->get('lsss_id');
        $summary = $this->sale_model->getLivestockSaleById($lsss_id);
        if (!$summary) { show_404(); }
        $values   = $this->sale_model->getLivestockSaleValueBySummaryById($lsss_id);
        $client   = $this->client_model->getClientById($summary->lsss_c_id);
        $settings = $this->settings_model->getSettings();
        $received = (float) $this->payments_model->getSumClientAndSaleWiseTotalReceivedAmount($summary->lsss_c_id, $lsss_id);

        $lines = array();
        foreach ($values as $v) {
            $ls   = $this->livestock_model->getLivestockById($v->lssv_ls_id);
            $lst  = $this->livestock_model->getLivestockTypeById($v->lssv_lst_id);
            $name = ($ls ? $ls->ls_name : '') . ($lst ? ' / ' . $lst->lst_title : '');
            $lines[] = array(
                'name'       => $name,
                'qty'        => $v->lssv_quantity,
                'unit_price' => $v->lssv_unit_price,
                'discount'   => $v->lssv_discount,
                'total'      => $v->lssv_total,
            );
        }
        $invoice = array(
            'title'          => 'Sale Invoice',
            'doc_number'     => $summary->lsss_id,
            'doc_date'       => $summary->lsss_date,
            'party_label'    => 'Client',
            'party_name'     => $client ? $client->c_name : '',
            'party_email'    => $client ? $client->c_email : '',
            'party_phone'    => $client ? $client->c_phone : '',
            'party_address'  => $client ? $client->c_address : '',
            'lines'          => $lines,
            'sub_total'      => $summary->lsss_sub_total,
            'grand_discount' => $summary->lsss_grand_discount,
            'grand_total'    => $summary->lsss_grand_total,
            'paid'           => $received,
            'note'           => $summary->lsss_note,
            'currency'       => $settings->currency,
            'company'        => array(
                'name'    => $settings->title,
                'address' => $settings->address,
                'phone'   => $settings->phone,
                'email'   => $settings->email,
            ),
        );
        $html = $this->load->view('pdf/_invoice_layout', array('invoice' => $invoice), TRUE);
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->download('livestock-sale-' . $summary->lsss_id . '.pdf');
    }

    /* ========== PDF Invoice — Product Sale ========== */
    public function downloadProductSalePdf()
    {
        $prss_id = $this->input->get('prss_id');
        $summary = $this->sale_model->getProductSaleById($prss_id);
        if (!$summary) { show_404(); }
        $values   = $this->sale_model->getProductSaleValueByProductId($prss_id);
        $client   = $this->client_model->getClientById($summary->prss_c_id);
        $settings = $this->settings_model->getSettings();
        $received = (float) $this->payments_model->getSumClientAndProductSaleWiseTotalReceivedAmount($summary->prss_c_id, $prss_id);

        $lines = array();
        foreach ($values as $v) {
            $pr   = $this->product_model->getProductById($v->prsv_product_id);
            $lines[] = array(
                'name'       => $pr ? $pr->pr_name : '',
                'qty'        => $v->prsv_quantity,
                'unit_price' => $v->prsv_unit_price,
                'discount'   => $v->prsv_discount,
                'total'      => $v->prsv_total,
            );
        }
        $invoice = array(
            'title'          => 'Product Sale Invoice',
            'doc_number'     => $summary->prss_id,
            'doc_date'       => $summary->prss_date,
            'party_label'    => 'Client',
            'party_name'     => $client ? $client->c_name : '',
            'party_email'    => $client ? $client->c_email : '',
            'party_phone'    => $client ? $client->c_phone : '',
            'party_address'  => $client ? $client->c_address : '',
            'lines'          => $lines,
            'sub_total'      => $summary->prss_sub_total,
            'grand_discount' => $summary->prss_grand_discount,
            'grand_total'    => $summary->prss_grand_total,
            'paid'           => $received,
            'note'           => $summary->prss_note,
            'currency'       => $settings->currency,
            'company'        => array(
                'name'    => $settings->title,
                'address' => $settings->address,
                'phone'   => $settings->phone,
                'email'   => $settings->email,
            ),
        );
        $html = $this->load->view('pdf/_invoice_layout', array('invoice' => $invoice), TRUE);
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->download('product-sale-' . $summary->prss_id . '.pdf');
    }

    /* ========== CSV Export — Livestock Sales ========== */
    public function exportCSV()
    {
        $from = $this->_validDate($this->input->get('from'));
        $to   = $this->_validDate($this->input->get('to'));
        $rows = $this->sale_model->getLivestockSale($from, $to);
        $out = array();
        foreach ($rows as $r) {
            $client = $this->client_model->getClientById($r->lsss_c_id);
            $clientName = $client ? $client->c_name : '';
            $received = (float) $this->payments_model->getSumClientAndSaleWiseTotalReceivedAmount($r->lsss_c_id, $r->lsss_id);
            $out[] = array(
                'Sale #'      => $r->lsss_id,
                'Date'        => $r->lsss_date,
                'Client'      => $clientName,
                'Sub Total'   => $r->lsss_sub_total,
                'Discount'    => $r->lsss_grand_discount,
                'Grand Total' => $r->lsss_grand_total,
                'Received'    => $received,
                'Balance'     => (float) $r->lsss_grand_total - $received,
                'Note'        => $r->lsss_note,
            );
        }
        csv_response('livestock-sales', array('Sale #','Date','Client','Sub Total','Discount','Grand Total','Received','Balance','Note'), $out);
    }

    // End
}

/* End of file sale.php */
/* Location: ./application/modules/sale/controllers/sale.php */