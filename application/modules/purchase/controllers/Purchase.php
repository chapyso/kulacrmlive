<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase extends MY_Controller
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
        $this->load->model('purchase_model');
        $this->load->model('client/client_model');
        $this->load->model('supplier/supplier_model');
        $this->load->model('sale/sale_model');
        $this->load->model('product/product_model');
        $this->load->model('livestock/livestock_model');
        $this->load->model('shed/shed_model');
        $this->load->model('staff/staff_model');
        $this->load->model('payments/payments_model');
        $this->load->model('report/report_model');
        $this->load->model('settings/settings_model');
        $data['settings'] = $this->settings_model->getSettings();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin', 'Accountant'))) {
            redirect('home/permission');
        }
    }

    public function index()
    {
        $from = $this->_validDate($this->input->get('from'));
        $to   = $this->_validDate($this->input->get('to'));
        $data['settings'] = $this->settings_model->getSettings();
        $data['livestock_purchases'] = $this->purchase_model->getLivestockPurchase($from, $to);
        $data['sheds'] = $this->shed_model->getShed();
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['batches'] = $this->shed_model->getBatch();
        $data['filter_from'] = $from;
        $data['filter_to']   = $to;
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('purchase', $data);
        $this->load->view('home/footer'); // just the footer file
    }

    private function _validDate($raw)
    {
        $raw = (string) $raw;
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw) ? $raw : '';
    }

    public function purchase()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $data['settings'] = $this->settings_model->getSettings();
        $data['purchases'] = $this->purchase_model->getLivestockPurchase();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('purchase', $data);
        $this->load->view('home/footer'); // just the header file
    }
    public function viewLivestockPurchase()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $purs_id = $this->input->get('purs_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['livestock_purchase_by_id'] = $this->purchase_model->getLivestockPurchaseById($purs_id);
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['LivestockSummaryId'] = $this->purchase_model->getLivestockPurchaseValueBySummaryById($purs_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_livestock_purchase', $data);
        $this->load->view('home/footer'); // just the header file
    }


    public function addNewPurchase()
    {
        $data = array();
        $data['settings'] = $this->settings_model->getSettings();
        $data['suppliers'] = $this->supplier_model->getData('supplier', 's_status', 1)->result();
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['livestock_types'] = $this->livestock_model->getLivestockType();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('add_new_purchase', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertPurchase()
    {
        $this->form_validation->set_rules('pur_supp_id', 'Supplier', 'trim|required|integer');
        $this->form_validation->set_rules('pur_date', 'Purchase date', 'trim|required');
        $this->form_validation->set_rules('pur_grand_total', 'Grand total', 'trim|required|numeric');
        $this->form_validation->set_rules('pur_ls_id[]', 'Livestock line item', 'required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('purchase/addNewPurchase');
        }
        $this->db->trans_start();
        // Summary
        $supplier_id = $this->input->post('pur_supp_id');
        $date = $this->input->post('pur_date');
        $pur_date = date("Y-m-d", strtotime($date));
        $pur_sub_total = $this->input->post('pur_sub_total');
        $pur_grand_discount = $this->input->post('pur_grand_discount');
        $pur_grand_total = $this->input->post('pur_grand_total');
        $pur_bill_no = $this->input->post('pur_bill_no');
        $note = $this->input->post('pur_note');

        $summaryData = array(
            'purs_supp_id' => $supplier_id,
            'purs_bill_no' => $pur_bill_no,
            'purs_date' => $pur_date,
            'purs_sub_total' => (empty($pur_sub_total) && $pur_sub_total !== '0') ? 0 : $pur_sub_total,
            'purs_grand_discount' => (empty($pur_grand_discount) && $pur_grand_discount !== '0') ? 0 : $pur_grand_discount,
            'purs_grand_total' => (empty($pur_grand_total) && $pur_grand_total !== '0') ? 0 : $pur_grand_total,
            'purs_note' => $note,
            'purs_status' => 1,
            'purs_created_at' => get_current_time(),
            'purs_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $purchaseSummaryId = $this->purchase_model->insertPurchase('livestock_purchase_summary', $summaryData);
        // Value
        $pur_ls_id = $this->input->post('pur_ls_id');
        $pur_lst_id = $this->input->post('pur_lst_id');
        $pur_unit_price = $this->input->post('pur_unit_price');
        $pur_quantity = $this->input->post('pur_quantity');
        $pur_discount = $this->input->post('pur_discount');
        $pur_total = $this->input->post('pur_total');
        for ($i = 0; $i < count($pur_unit_price); $i++) {
            $ValueData = array(
                'purv_purs_id' => $purchaseSummaryId,
                'purv_ls_id' => $pur_ls_id[$i],
                'purv_lst_id' => $pur_lst_id[$i],
                'purv_unit_price' => $pur_unit_price[$i],
                'purv_quantity' => $pur_quantity[$i],
                'purv_discount' => (empty($pur_discount[$i]) && $pur_discount[$i] !== '0') ? 0 : $pur_discount[$i],
                'purv_total' => $pur_total[$i],
                'purv_status' => 1,
                'purv_created_at' => get_current_time(),
                'purv_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->purchase_model->insertPurchase('livestock_purchase_value', $ValueData);
        }


        // Purchase Payment
        $sp_payment_amount = $this->input->post('sp_payment_amount');
        $sp_paid_by = $this->input->post('sp_paid_by');
        $sp_cheque_no = $this->input->post('sp_cheque_no');
        $sp_reference = $this->input->post('sp_reference');
        $sp_description = $this->input->post('sp_description');
        if (!empty($purchaseSummaryId) && $sp_payment_amount > 0) {
            $livePurchasePaymentData = array(
                'sp_s_id' =>  $supplier_id,
                'sp_purs_id' =>  $purchaseSummaryId,
                'sp_payment_amount' =>  $sp_payment_amount,
                'sp_paid_by' =>  $sp_paid_by,
                'sp_cheque_no' =>  $sp_cheque_no,
                'sp_reference' =>  $sp_reference,
                'sp_date' =>  $pur_date,
                'sp_description' =>  $sp_description,
                'sp_status' => 1,
                'sp_purchase_type' => 1,
                'sp_created_at' => get_current_time(),
                'sp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('supplier_payment', $livePurchasePaymentData);
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Purchase added successfully.');
        redirect("purchase/purchase");
    }



    // Update Livestock Purchase
    public function editLivestockPurchase()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $purs_id = $this->input->get('purs_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['suppliers'] = $this->supplier_model->getData('supplier', 's_status', 1)->result();
        $data['livestockPurchaseById'] = $this->purchase_model->getLivestockPurchaseById($purs_id);
        $data['LivestockSummaryId'] = $this->purchase_model->getLivestockPurchaseValueBySummaryById($purs_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('edit_livestock_purchase', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function updateLivestockPurchase()
    {
        $this->db->trans_start();
        // Delete previous purchase value data
        $purs_id = $this->input->post('purs_id');
        $deleteValue = array(
            'purv_status' => 0,
            'purv_updated_at' => get_current_time(),
            'purv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('livestock_purchase_value', 'purv_purs_id', $purs_id, $deleteValue);

        // Summary
        $supplier_id = $this->input->post('pur_supp_id');
        $date = $this->input->post('pur_date');
        $pur_date = date("Y-m-d", strtotime($date));
        $pur_sub_total = $this->input->post('pur_sub_total');
        $pur_grand_discount = $this->input->post('pur_grand_discount');
        $pur_grand_total = $this->input->post('pur_grand_total');
        $note = $this->input->post('pur_note');
        $summaryData = array(
            'purs_supp_id' => $supplier_id,
            'purs_date' => $pur_date,
            'purs_sub_total' => (empty($pur_sub_total) && $pur_sub_total !== '0') ? 0 : $pur_sub_total,
            'purs_grand_discount' => (empty($pur_grand_discount) && $pur_grand_discount !== '0') ? 0 : $pur_grand_discount,
            'purs_grand_total' => (empty($pur_grand_total) && $pur_grand_total !== '0') ? 0 : $pur_grand_total,
            'purs_note' => $note,
            'purs_updated_at' => get_current_time(),
            'purs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('livestock_purchase_summary', 'purs_id', $purs_id, $summaryData);

        // Value
        $pur_ls_id = $this->input->post('pur_ls_id');
        $pur_lst_id = $this->input->post('pur_lst_id');
        $pur_unit_price = $this->input->post('pur_unit_price');
        $pur_quantity = $this->input->post('pur_quantity');
        $pur_discount = $this->input->post('pur_discount');
        $pur_total = $this->input->post('pur_total');
        for ($i = 0; $i < count($pur_unit_price); $i++) {
            $ValueData = array(
                'purv_purs_id' => $purs_id,
                'purv_ls_id' => $pur_ls_id[$i],
                'purv_lst_id' => $pur_lst_id[$i],
                'purv_unit_price' => $pur_unit_price[$i],
                'purv_quantity' => $pur_quantity[$i],
                'purv_discount' => (empty($pur_discount[$i]) && $pur_discount[$i] !== '0') ? 0 : $pur_discount[$i],
                'purv_total' => $pur_total[$i],
                'purv_status' => 1,
                'purv_created_at' => get_current_time(),
                'purv_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->purchase_model->insertPurchase('livestock_purchase_value', $ValueData);
        }


        // If there is any previous payment on this purchase then payments will be deleted. You can add new payment
        $deletePreviousPayments = array(
            'sp_status' => 0,
            'sp_updated_at' => get_current_time(),
            'sp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->payments_model->updateData('supplier_payment', 'sp_purs_id', $purs_id, $deletePreviousPayments);


        // Insert New Purchase Payment
        $sp_payment_amount = $this->input->post('sp_payment_amount');
        $sp_paid_by = $this->input->post('sp_paid_by');
        $sp_cheque_no = $this->input->post('sp_cheque_no');
        $sp_reference = $this->input->post('sp_reference');
        $sp_description = $this->input->post('sp_description');
        if (!empty($purs_id) &&  $sp_payment_amount > 0) {
            $livePurchasePaymentData = array(
                'sp_s_id' =>  $supplier_id,
                'sp_purs_id' =>  $purs_id,
                'sp_payment_amount' =>  $sp_payment_amount,
                'sp_paid_by' =>  $sp_paid_by,
                'sp_cheque_no' =>  $sp_cheque_no,
                'sp_reference' =>  $sp_reference,
                'sp_date' =>  $pur_date,
                'sp_description' =>  $sp_description,
                'sp_status' => 1,
                'sp_purchase_type' => 1,
                'sp_created_at' => get_current_time(),
                'sp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('supplier_payment', $livePurchasePaymentData);
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Purchase updated successfully.');
        redirect("purchase/purchase");
    }

    // Delete Summary Data
    function deleteLivestockPurchase()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        $purs_id = $this->input->post('purs_id');
        $deleteSummary = array(
            'purs_status' => 0,
            'purs_updated_at' => get_current_time(),
            'purs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('livestock_purchase_summary', 'purs_id', $purs_id, $deleteSummary);
        $deleteValue = array(
            'purv_status' => 0,
            'purv_updated_at' => get_current_time(),
            'purv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('livestock_purchase_value', 'purv_purs_id', $purs_id, $deleteValue);

        // delete Payments Delete under this invoice
        $deletePayments = array(
            'sp_status' => 0,
            'sp_updated_at' => get_current_time(),
            'sp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('supplier_payment', 'sp_purs_id', $purs_id, $deletePayments);

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Purchase deleted successfully.');
        redirect("purchase/purchase");
    }



    // Cascading Dropdown
    function getLivestockTypeCascadingDropdown()
    {
        $livestock_id = $this->input->post('livestock_id');
        $livestock_type_id = $this->input->post('livestock_type_id');
        if ($livestock_id) {
            echo $this->purchase_model->getLivestockTypeCascadingDropdown($livestock_id, $livestock_type_id);
        }
    }
    // Livestock By Shed And Batch
    function getLivestockByShedAndBatchCascadingDropdown()
    {
        $shed_id = $this->input->post('shed_id');
        $batch_id = $this->input->post('batch_id');

        $live_assigned_summary_id = $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($shed_id, $batch_id)->lshs_id;
        $live_id = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($live_assigned_summary_id)->lsh_purv_ls_id;
        $live_type_id = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($live_assigned_summary_id)->lsh_purv_lst_id;

        $data['livestock'] =  $this->purchase_model->getLivestockByAssignedIdCascadingDropdown($live_id, $live_id);

        $data['livestock_type'] = $this->purchase_model->getLivestockTypeByAssignedIdCascadingDropdown($live_type_id, $live_type_id);


        /* =========================== Get shed wise livestock quantity by ajax =========================== */
        $purchaseQuantity = $this->sale_model->getShedAndBatchWiseLivestockPurchaseQuantity($shed_id, $batch_id);
        $saleQuantity = $this->sale_model->getShedAndBatchWiseLivestockSaleQuantity($shed_id, $batch_id, $live_id, $live_type_id);
        $deathQuantity = $this->sale_model->getShedAndBatchWiseLivestockDeathQuantity($shed_id, $batch_id, $live_id, $live_type_id);

        if (!empty($purchaseQuantity)) {
            $purchaseQuantity = $purchaseQuantity;
        } else {
            $purchaseQuantity = 0;
        }

        if (!empty($saleQuantity)) {
            $saleQuantity = $saleQuantity;
        } else {
            $saleQuantity = 0;
        }

        if (!empty($deathQuantity)) {
            $deathQuantity = $deathQuantity;
        } else {
            $deathQuantity = 0;
        }

        $availableQuantity =  $purchaseQuantity - ($saleQuantity + $deathQuantity);
        if (!empty($availableQuantity)) {
            $data['availableLivestockQuantity'] = $availableQuantity;
        } else {
            $data['availableLivestockQuantity'] = 0;
        }
        echo json_encode($data);
    }

    /* ======================= Livestock Assign To Shed ======================= */

    public function livestockAssignToShed()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $data['settings'] = $this->settings_model->getSettings();
        $data['sheds'] = $this->shed_model->getShed();
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['assignedSummaryData'] = $this->purchase_model->getAssignedLivestockSummaryData();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('livestock_assign_to_shed', $data);
        $this->load->view('home/footer'); // just the header file
    }

    // Get Data by ls and type id
    function getLivestockAndTypesById()
    {
        // Purchase Information
        $livestock_id = $this->input->get('livestock_id');
        $type_id = $this->input->get('type_id');
        $data['livestockPurchasedInfo'] = $this->purchase_model->getPurchasedLivestockAndTypesByLiveAndTypeId($livestock_id, $type_id);

        // Purchase Summary Information
        $purchaseSummaryInformation = [];
        foreach ($data['livestockPurchasedInfo'] as $purchaseValueIId) {
            $data2['purchaseSummaryInfo'] = $this->purchase_model->getLivestockPurchaseById($purchaseValueIId->purv_purs_id)->purs_bill_no;
            array_push($purchaseSummaryInformation, $data2['purchaseSummaryInfo']);
        }

        // Assigned Quantity Information
        $assignInfo = [];
        foreach ($data['livestockPurchasedInfo'] as $purchaseValueId) {
            $data1['assignedQuantity'] = $this->purchase_model->getTotalAssignedLivestockQuantityByPurchaseValueId($purchaseValueId->purv_id);
            array_push($assignInfo, $data1['assignedQuantity']);
        }
        // x_debug($assignInfo);

        // Pass data to view page by json encode
        echo json_encode([
            'livePurInfo' => $data,
            'assiQuantity' => $assignInfo,
            'livePurSummaryInfo' => $purchaseSummaryInformation
        ]);
    }

    // Get Last Batch Number By Shed
    function getLastBatchNumberShedWiseByAjax()
    {
        $shed_id = $this->input->post('shed_id');
        if ($shed_id) {
            $lastBatchNo = $this->purchase_model->getLastBatchNumberShedWise($shed_id);
        }
        if (!empty($lastBatchNo)) {
            $lastBatchNumber = $lastBatchNo;
        } else {
            $lastBatchNumber = 0;
        }
        echo $lastBatchNumber  + 1;
    }


    // Get Existing Batch By Shed
    function getExistingBatchByShedIdByAjax()
    {
        $existing_shed_id = $this->input->post('existing_shed_id');
        $existing_ls_id = $this->input->post('existing_ls_id');
        $existing_lst_id = $this->input->post('existing_lst_id');
        if ($existing_shed_id) {
            $data['batches'] = $this->purchase_model->getExistingBatchesByShedId($existing_shed_id, $existing_ls_id, $existing_lst_id);
        }
        echo json_encode($data);
    }


    // Get Existing Batch By Shed In Edit
    function getExistingBatchByShedIdByAjaxInEdit()
    {
        $existing_shed_id = $this->input->post('existing_shed_id');
        $existing_ls_id = $this->input->post('existing_ls_id');
        $existing_lst_id = $this->input->post('existing_lst_id');
        $live_batch_id = $this->input->post('live_batch_id');
        if ($existing_shed_id) {
            $data['batches'] = $this->purchase_model->getExistingBatchesByShedIdInEdit($existing_shed_id, $existing_ls_id, $existing_lst_id, $live_batch_id);
        }
        echo json_encode($data);
    }


    public function insertLivestockToShed()
    {
        $this->db->trans_start();
        $existing_batch_id = $this->input->post('existing_batch_id');

        $lsh_purs_id = $this->input->post('lsh_purs_id');
        $lsh_purv_id = $this->input->post('lsh_purv_id');
        $lsh_ls_id = $this->input->post('lsh_ls_id');
        $lsh_lst_id = $this->input->post('lsh_lst_id');
        $lsh_assign_quantity = $this->input->post('lsh_assign_quantity');
        $lsh_assign_total_quantity =  array_sum($lsh_assign_quantity);
        $lsh_batch_title = $this->input->post('lsh_batch_title');

        $lsh_sh_id = $this->input->post('lsh_sh_id');
        $lsh_batch_number = $this->input->post('lsh_batch_number');
        $date = $this->input->post('lsh_assign_date');
        $lsh_assign_date = date("y-m-d", strtotime($date));
        $lsh_description = $this->input->post('lsh_description');



        if (!empty($existing_batch_id)) {

            $assignedSummaryId = $this->purchase_model->getAssignedSummaryIdById($lsh_sh_id, $existing_batch_id)->lsh_lshs_id;
            $assignedSummaryInfo = $this->purchase_model->getAssignedSummaryInfoById($assignedSummaryId);
            $newTotal = $assignedSummaryInfo->lshs_assign_total_quantity + $lsh_assign_total_quantity;

            $updateSummaryData = array(
                'lshs_assign_total_quantity' => $newTotal,
                'lshs_updated_at' => get_current_time(),
                'lshs_updated_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->purchase_model->updateData('live_assigned_shed_summary', 'lshs_id', $assignedSummaryId, $updateSummaryData);

            if ($lsh_assign_quantity != NULL) {
                for ($i = 0; $i < count($lsh_purs_id); $i++) {
                    $valueDataNew = array(
                        'lsh_lshs_id' => $assignedSummaryId,
                        'lsh_sh_id' => $lsh_sh_id,
                        'lsh_batch_id' => $existing_batch_id,
                        'lsh_purs_id' => $lsh_purs_id[$i],
                        'lsh_purv_id' => $lsh_purv_id[$i],
                        'lsh_purv_ls_id' => $lsh_ls_id[$i],
                        'lsh_purv_lst_id' => $lsh_lst_id[$i],
                        'lsh_assign_quantity' => $lsh_assign_quantity[$i],
                        'lsh_status' => 1,
                        'lsh_created_at' => get_current_time(),
                        'lsh_created_by' => $this->ion_auth->user()->row()->user_id
                    );
                    $this->purchase_model->insertData('live_assigned_shed', $valueDataNew);
                }
            }
        } else {
            $summaryData = array(
                'lshs_sh_id' => $lsh_sh_id,
                'lshs_batch_id' => $lsh_batch_number,
                'lshs_assign_date' => $lsh_assign_date,
                'lshs_assign_total_quantity' => $lsh_assign_total_quantity,
                'lshs_batch_title' => $lsh_batch_title,
                'lshs_description' => $lsh_description,
                'lshs_type' => 1, //Purchase Assign Type
                'lshs_status' => 1,
                'lshs_created_at' => get_current_time(),
                'lshs_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $summaryId = $this->purchase_model->insertData('live_assigned_shed_summary', $summaryData);
            if ($lsh_assign_quantity != NULL) {
                for ($i = 0; $i < count($lsh_purs_id); $i++) {
                    $ValueData = array(
                        'lsh_lshs_id' => $summaryId,
                        'lsh_sh_id' => $lsh_sh_id,
                        'lsh_batch_id' => $lsh_batch_number,
                        'lsh_purs_id' => $lsh_purs_id[$i],
                        'lsh_purv_id' => $lsh_purv_id[$i],
                        'lsh_purv_ls_id' => $lsh_ls_id[$i],
                        'lsh_purv_lst_id' => $lsh_lst_id[$i],
                        'lsh_assign_quantity' => $lsh_assign_quantity[$i],
                        'lsh_status' => 1,
                        'lsh_created_at' => get_current_time(),
                        'lsh_created_by' => $this->ion_auth->user()->row()->user_id
                    );
                    $this->purchase_model->insertData('live_assigned_shed', $ValueData);
                }
            }
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Livestock assigned to shed successfully.');
        redirect("purchase/livestockAssignToShed");
    }



    public function viewLivestockAssignToShed()
    {
        $lshs_id = $this->input->get('lshs_id');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $data['settings'] = $this->settings_model->getSettings();
        $data['sheds'] = $this->shed_model->getShed();
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['assignedValuesData'] = $this->purchase_model->getAssignedLivestockValuesBySummaryId($lshs_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_livestock_assign_to_shed', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function updateLivestockToShed()
    {
        $this->db->trans_start();
        // insert value
        $lsh_lshs_id = $this->input->post('lsh_lshs_id');
        $lsh_id = $this->input->post('lsh_id');

        $lsh_assign_quantity = $this->input->post('lsh_assign_quantity');
        $lsh_assign_total_quantity =  array_sum($lsh_assign_quantity);

        $lsh_purs_id = $this->input->post('lsh_purs_id');
        $lsh_purv_id = $this->input->post('lsh_purv_id');
        $lsh_ls_id = $this->input->post('lsh_ls_id');
        $lsh_lst_id = $this->input->post('lsh_lst_id');
        $batch_id = $this->input->post('batch_id');

        $lsh_sh_id = $this->input->post('lsh_sh_id');
        $date = $this->input->post('lsh_assign_date');
        $lsh_assign_date = date("y-m-d", strtotime($date));
        $lsh_description = $this->input->post('lsh_description');
        $summaryData = array(
            'lshs_sh_id' => $lsh_sh_id,
            'lshs_batch_id' => $batch_id,
            'lshs_assign_date' => $lsh_assign_date,
            'lshs_assign_total_quantity' => $lsh_assign_total_quantity,
            'lshs_description' => $lsh_description,
            'lshs_updated_at' => get_current_time(),
            'lshs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('live_assigned_shed_summary', 'lshs_id', $lsh_lshs_id, $summaryData);

        $deletePreviousValue = array(
            'lsh_status' => 0,
            'lsh_updated_at' => get_current_time(),
            'lsh_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('live_assigned_shed', 'lsh_lshs_id', $lsh_lshs_id, $deletePreviousValue);

        for ($i = 0; $i < count($lsh_id); $i++) {
            $ValueData = array(
                'lsh_lshs_id' => $lsh_lshs_id,
                'lsh_assign_quantity' => $lsh_assign_quantity[$i],
                'lsh_purs_id' => $lsh_purs_id[$i],
                'lsh_purv_id' => $lsh_purv_id[$i],
                'lsh_purv_ls_id' => $lsh_ls_id[$i],
                'lsh_purv_lst_id' => $lsh_lst_id[$i],
                'lsh_batch_id' => $batch_id,
                'lsh_sh_id' => $lsh_sh_id,
                'lsh_status' => 1,
                'lsh_created_at' => get_current_time(),
                'lsh_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->purchase_model->insertData('live_assigned_shed', $ValueData);
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Livestock shed assignment updated successfully.');
        redirect("purchase/livestockAssignToShed");
    }


    // Delete Assigned Data
    function deleteLivestockToShed()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        $lshs_id = $this->input->post('lshs_id');
        // Summary Table 
        $updateData = array(
            'lshs_status' => 0,
            'lshs_updated_at' => get_current_time(),
            'lshs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('live_assigned_shed_summary', 'lshs_id', $lshs_id, $updateData);
        // Value Table
        $updateData = array(
            'lsh_status' => 0,
            'lsh_updated_at' => get_current_time(),
            'lsh_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('live_assigned_shed', 'lsh_lshs_id', $lshs_id, $updateData);

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Livestock shed assignment deleted successfully.');
        redirect("purchase/livestockAssignToShed");
    }

    // Get Data by ls and type id
    function getAssignedLivestockAndTypesBySummaryId()
    {
        $assigned_summary_id = $this->input->get('assigned_summary_id');
        $livestock_id = $this->input->get('purv_ls_id');
        $type_id = $this->input->get('purv_lst_id');
        $data['assignedLiveStocks'] = $this->purchase_model->getAssignedLivestockAndTypesBySummaryId($assigned_summary_id);

        $data1['livestockPurchasedInfo'] = $this->purchase_model->getPurchasedLivestockAndTypesByLiveAndTypeId($livestock_id, $type_id);

        // Purchase Summary Information
        $purchaseSummaryInformation = [];
        $purchaseValueInfo = [];
        foreach ($data1['livestockPurchasedInfo'] as $purchaseValueIId) {
            $data2['purchaseSummaryInfo'] = $this->purchase_model->getLivestockPurchaseById($purchaseValueIId->purv_purs_id)->purs_bill_no;
            array_push($purchaseSummaryInformation, $data2['purchaseSummaryInfo']);

            array_push($purchaseValueInfo, $purchaseValueIId->purv_quantity);
        }

        echo json_encode([
            'assignInfo' => $data,
            'livePurSummaryInfo' => $purchaseSummaryInformation,
            'livePurValueInfo' => $purchaseValueInfo
        ]);
    }

    /* ========== PDF Invoice ========== */
    public function downloadPurchasePdf()
    {
        $purs_id = $this->input->get('purs_id');
        $summary = $this->purchase_model->getLivestockPurchaseById($purs_id);
        if (!$summary) { show_404(); }
        $values   = $this->purchase_model->getLivestockPurchaseValueBySummaryById($purs_id);
        $supplier = $this->supplier_model->getData('supplier', 's_id', $summary->purs_supp_id)->row();
        $settings = $this->settings_model->getSettings();
        $paid     = (float) $this->payments_model->getSumSupplierAndPurchaseWiseTotalPaidAmount($summary->purs_supp_id, $purs_id);

        $lines = array();
        foreach ($values as $v) {
            $ls   = $this->livestock_model->getLivestockById($v->purv_ls_id);
            $lst  = $this->livestock_model->getLivestockTypeById($v->purv_lst_id);
            $name = ($ls ? $ls->ls_name : '') . ($lst ? ' / ' . $lst->lst_title : '');
            $lines[] = array(
                'name'       => $name,
                'qty'        => $v->purv_quantity,
                'unit_price' => $v->purv_unit_price,
                'discount'   => $v->purv_discount,
                'total'      => $v->purv_total,
            );
        }
        $invoice = array(
            'title'          => 'Purchase Invoice',
            'doc_number'     => $summary->purs_bill_no,
            'doc_date'       => $summary->purs_date,
            'party_label'    => 'Supplier',
            'party_name'     => $supplier ? $supplier->s_name : '',
            'party_email'    => $supplier ? $supplier->s_email : '',
            'party_phone'    => $supplier ? $supplier->s_phone : '',
            'party_address'  => $supplier ? $supplier->s_address : '',
            'lines'          => $lines,
            'sub_total'      => $summary->purs_sub_total,
            'grand_discount' => $summary->purs_grand_discount,
            'grand_total'    => $summary->purs_grand_total,
            'paid'           => $paid,
            'note'           => $summary->purs_note,
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
        $this->pdf->download('purchase-' . $summary->purs_bill_no . '.pdf');
    }

    /* ========== CSV Export ========== */
    public function exportCSV()
    {
        $from = $this->_validDate($this->input->get('from'));
        $to   = $this->_validDate($this->input->get('to'));
        $rows = $this->purchase_model->getLivestockPurchase($from, $to);
        $out = array();
        foreach ($rows as $r) {
            $supplier = $this->supplier_model->getData('supplier', 's_id', $r->purs_supp_id)->row();
            $supplierName = $supplier ? $supplier->s_name : '';
            $paid = (float) $this->payments_model->getSumSupplierAndPurchaseWiseTotalPaidAmount($r->purs_supp_id, $r->purs_id);
            $out[] = array(
                'Bill #'        => $r->purs_bill_no,
                'Date'          => $r->purs_date,
                'Supplier'      => $supplierName,
                'Sub Total'     => $r->purs_sub_total,
                'Discount'      => $r->purs_grand_discount,
                'Grand Total'   => $r->purs_grand_total,
                'Paid Amount'   => $paid,
                'Balance'       => (float) $r->purs_grand_total - $paid,
                'Note'          => $r->purs_note,
            );
        }
        csv_response('livestock-purchases', array('Bill #','Date','Supplier','Sub Total','Discount','Grand Total','Paid Amount','Balance','Note'), $out);
    }
}

/* End of file purchase.php */
/* Location: ./application/modules/purchase/controllers/purchase.php */