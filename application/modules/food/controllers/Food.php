<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Food extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('food_model');
        $this->load->library('upload');
        $this->load->model('settings/settings_model');
        $settings = $this->settings_model->getSettings();
        $language = (!empty($settings) && !empty($settings->language)) ? $settings->language : 'english';
        $this->lang->load('system_syntax', $language);
        $this->load->model('supplier/supplier_model');
        $this->load->model('purchase/purchase_model');
        $this->load->model('payments/payments_model');
        $this->load->model('report/report_model');
        $this->load->model('shed/shed_model');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin'))) {
            redirect('home/permission');
        }
    }
    /* ========================== Food List ========================== */
    public function listFood()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['units'] = $this->settings_model->getUnit();
        $data['foods'] = $this->food_model->getFood();
        $data['sheds'] = $this->shed_model->getShed();
        $data['batches'] = $this->shed_model->getBatch();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_food', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ========================== Get Batch By shed ========================== */
    // All Batch
    function getBatchByShedId()
    {
        $shed_id = $this->input->post('shed_id');
        $batch_id = $this->input->post('batch_id');
        if ($shed_id) {
            echo $this->food_model->getBatchByShedCascadingDropdown($shed_id, $batch_id);
        }
    }
    // Active Batch
    function getActiveBatchByShedId()
    {
        $shed_id = $this->input->post('shed_id');
        $batch_id = $this->input->post('batch_id');
        if ($shed_id) {
            echo $this->food_model->getActiveBatchByShedCascadingDropdown($shed_id, $batch_id);
        }
    }
    /* ========================== Insert Food ========================== */
    public function insertFood()
    {
        $fd_food_title = $this->input->post('fds_food_title');
        $fd_unit_id = $this->input->post('fds_unit_id');
        $fd_description = $this->input->post('fds_description');

        $dataSummary = array(
            'fds_food_title' => $fd_food_title,
            'fds_description' => $fd_description,
            'fds_unit_id' => $fd_unit_id,
            'fds_status' => 1,
            'fds_created_at' => get_current_time(),
            'fds_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->insertData('food_summary', $dataSummary);
        $this->session->set_flashdata('success', 'Food added successfully.');
        redirect('food/listFood');
    }

    /* ========================== delete Food ========================== */
    public function updateFood()
    {
        $fds_id = $this->input->post('fds_id');
        $fd_food_title = $this->input->post('fds_food_title');
        $fd_unit_id = $this->input->post('fds_unit_id');
        $fd_description = $this->input->post('fds_description');
        $updateData = array(
            'fds_food_title' => $fd_food_title,
            'fds_description' => $fd_description,
            'fds_unit_id' => $fd_unit_id,
            'fds_updated_at' => get_current_time(),
            'fds_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_summary', 'fds_id', $fds_id, $updateData);
        $this->session->set_flashdata('success', 'Food updated successfully.');
        redirect('food/listFood');
    }

    /* ========================== delete Food ========================== */
    function deleteFoodSummary()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $fds_id = $this->input->post('fds_id');
        $updateData = array(
            'fds_status' => 0,
            'fds_updated_at' => get_current_time(),
            'fds_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_summary', 'fds_id', $fds_id, $updateData);
        $this->session->set_flashdata('success', 'Food deleted successfully.');
        redirect('food/listFood');
    }

    /* ========================== Insert Food Value ========================== */
    function insertAssignFood()
    {
        $fdv_id = $this->input->post('fdv_id');
        $fds_id = $this->input->post('fdv_fds_id');

        $fd_weight = $this->input->post('fdv_weight');
        $fd_assigned_shed_id = $this->input->post('fdv_assigned_shed_id');
        $fd_assigned_batch_id = $this->input->post('fdv_assigned_batch_id');
        $fd_description = $this->input->post('fdv_description');

        $dataValue = array(
            'fdv_fds_id' => $fds_id,
            'fdv_weight' => $fd_weight,
            'fdv_assigned_shed_id' => $fd_assigned_shed_id,
            'fdv_assigned_batch_id' => $fd_assigned_batch_id,
            'fdv_description' => $fd_description,
            'fdv_status' => 1,
            'fdv_created_at' => get_current_time(),
            'fdv_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->insertData('food_value', $dataValue);
        $this->session->set_flashdata('success', 'Food assignment added successfully.');
        redirect('food/listFood');
    }

    /* ========================== Check Assigned if already have ========================== */
    function checkAssignedFoodBatchByAjax()
    {
        $batch_id = $this->input->post('batch_id');
        $shed_id = $this->input->post('shed_id');
        $fd_summary_id = $this->input->post('fd_summary_id');
        if ($fd_summary_id && $shed_id && $batch_id) {
            $returnValue = $this->food_model->getAssignedFoodBatchByAjax($fd_summary_id, $shed_id, $batch_id);
        }
        if ($returnValue) {
            echo $returnValue->fdv_id;
        } else {
            echo 0;
        }
    }
    /* ========================== update Food Value ========================== */
    function updateAssignedFoodValue()
    {
        $fdv_id = $this->input->post('fdv_id');
        $fds_id = $this->input->post('fdv_fds_id');
        $fd_weight = $this->input->post('fdv_weight');
        $fd_assigned_shed_id = $this->input->post('fdv_assigned_shed_id');
        $fd_assigned_batch_id = $this->input->post('fdv_assigned_batch_id');
        $fd_description = $this->input->post('fdv_description');

        $updateData = array(
            'fdv_weight' => $fd_weight,
            'fdv_assigned_shed_id' => $fd_assigned_shed_id,
            'fdv_assigned_batch_id' => $fd_assigned_batch_id,
            'fdv_description' => $fd_description,
            'fdv_updated_at' => get_current_time(),
            'fdv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_value', 'fdv_id', $fdv_id, $updateData);
        $this->session->set_flashdata('success', 'Food assignment updated successfully.');
        redirect("food/viewAssignedFood?fds_id=$fds_id");
    }

    /* ========================== delete Food Value ========================== */
    function deleteAssignedFoodValue()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $fdv_id = $this->input->post('fdv_id');
        $fds_id = $this->input->post('fdv_fds_id');
        $updateData = array(
            'fdv_status' => 0,
            'fdv_updated_at' => get_current_time(),
            'fdv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_value', 'fdv_id', $fdv_id, $updateData);
        $this->session->set_flashdata('success', 'Food assignment deleted successfully.');
        redirect("food/viewAssignedFood?fds_id=$fds_id");
    }


    /* ========================== edit Food Value ========================== */
    function editFoodSummaryByJason()
    {
        $fds_id = $this->input->get('fds_id');
        $data['foodSummary'] = $this->food_model->getFoodSummaryById($fds_id);
        $data['settings'] = $this->settings_model->getSettings();
        echo json_encode($data);
    }
    function editFoodValueByJason()
    {
        $fdv_id = $this->input->get('fdv_id');
        $data['foodValue'] = $this->food_model->getFoodValueById($fdv_id);
        $data['settings'] = $this->settings_model->getSettings();
        echo json_encode($data);
    }



    public function viewAssignedFood()
    {
        $fds_id = $this->input->get('fds_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['foodById'] = $this->food_model->getFoodSummaryById($fds_id);
        $data['shedsByFoodId'] = $this->food_model->getAssignedShedByFoodId($fds_id);
        $data['sheds'] = $this->shed_model->getShed();
        $data['batches'] = $this->shed_model->getBatch();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_assigned_food', $data);
        $this->load->view('home/footer'); // just the header file
    }


    /* ===================================== Food Purchase ===================================== */
    /* ====================== List Food Purchase ====================== */
    public function listFoodPurchase()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['foodPurchases'] = $this->food_model->getFoodPurchase();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_food_purchase', $data);
        $this->load->view('home/footer'); // just the header file
    }
    /* ====================== Add Food Purchase ====================== */
    public function addFoodPurchase()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['foods'] = $this->food_model->getFood();
        $data['suppliers'] = $this->supplier_model->getData('supplier', 's_status', 1)->result();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('add_food_purchase', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== Insert Food Purchase ====================== */
    public function insertFoodPurchase()
    {
        $this->form_validation->set_rules('fdp_s_id', 'Supplier', 'trim|required|integer');
        $this->form_validation->set_rules('fdp_date', 'Date', 'trim|required');
        $this->form_validation->set_rules('fdp_grand_total', 'Grand total', 'trim|required|numeric');
        $this->form_validation->set_rules('fdp_fd_id[]', 'Food line item', 'required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('food/addFoodPurchase');
        }
        $this->db->trans_start();
        // Summary
        $fdp_s_id = $this->input->post('fdp_s_id');
        $date = $this->input->post('fdp_date');
        $fdp_date = date("Y-m-d", strtotime($date));
        $fdp_sub_total = $this->input->post('fdp_sub_total');
        $fdp_grand_discount = $this->input->post('fdp_grand_discount');
        $fdp_grand_total = $this->input->post('fdp_grand_total');
        $fdp_description = $this->input->post('fdp_description');
        $summaryData = array(
            'fdps_s_id' => $fdp_s_id,
            'fdps_date' => $fdp_date,
            'fdps_sub_total' => (empty($fdp_sub_total) && $fdp_sub_total !== '0') ? 0 : $fdp_sub_total,
            'fdps_grand_discount' => (empty($fdp_grand_discount) && $fdp_grand_discount !== '0') ? 0 : $fdp_grand_discount,
            'fdps_grand_total' => (empty($fdp_grand_total) && $fdp_grand_total !== '0') ? 0 : $fdp_grand_total,
            'fdps_description' => $fdp_description,
            'fdps_status' => 1,
            'fdps_created_at' => get_current_time(),
            'fdps_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $purchaseSummaryId = $this->food_model->insertData('food_purchase_summary', $summaryData);
        // Value
        $fdp_fd_id = $this->input->post('fdp_fd_id');
        $fdp_unit_price = $this->input->post('fdp_unit_price');
        $fdp_quantity = $this->input->post('fdp_quantity');
        $fdp_discount = $this->input->post('fdp_discount');
        $fdp_total = $this->input->post('fdp_total');
        for ($i = 0; $i < count($fdp_unit_price); $i++) {
            $ValueData = array(
                'fdpv_fdps_id' => $purchaseSummaryId,
                'fdpv_fd_id' => $fdp_fd_id[$i],
                'fdpv_unit_price' => $fdp_unit_price[$i],
                'fdpv_quantity' => $fdp_quantity[$i],
                'fdpv_discount' => (empty($fdp_discount[$i]) && $fdp_discount[$i] !== '0') ? 0 : $fdp_discount[$i],
                'fdpv_total' => $fdp_total[$i],
                'fdpv_status' => 1,
                'fdpv_created_at' => get_current_time(),
                'fdpv_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->food_model->insertData('food_purchase_value', $ValueData);
        }

        // Purchase Payment
        $sp_payment_amount = $this->input->post('sp_payment_amount');
        $sp_paid_by = $this->input->post('sp_paid_by');
        $sp_cheque_no = $this->input->post('sp_cheque_no');
        $sp_reference = $this->input->post('sp_reference');
        $sp_description = $this->input->post('sp_description');
        if (!empty($purchaseSummaryId) && $sp_payment_amount > 0) {
            $foodPurchasePaymentData = array(
                'sp_s_id' =>  $fdp_s_id,
                'sp_fdps_id' =>  $purchaseSummaryId,
                'sp_payment_amount' =>  $sp_payment_amount,
                'sp_paid_by' =>  $sp_paid_by,
                'sp_cheque_no' =>  $sp_cheque_no,
                'sp_reference' =>  $sp_reference,
                'sp_date' =>  $fdp_date,
                'sp_description' =>  $sp_description,
                'sp_status' => 1,
                'sp_purchase_type' => 2,
                'sp_created_at' => get_current_time(),
                'sp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('supplier_payment', $foodPurchasePaymentData);
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Food purchase added successfully.');
        redirect("food/listFoodPurchase");
    }

    /* ====================== List Food Purchase ====================== */
    public function viewFoodPurchase()
    {
        $id = $this->input->get('fdps_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['foodPurchaseById'] = $this->food_model->getFoodPurchaseSummaryById($id);
        $data['foodPurchaseValueBySummaryById'] = $this->food_model->getFoodPurchaseValueBySummaryId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_food_purchase', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== Edit Food Purchase ====================== */
    public function editFoodPurchase()
    {
        $id = $this->input->get('fdps_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['foods'] = $this->food_model->getFood();
        $data['foodPurchaseById'] = $this->food_model->getFoodPurchaseSummaryById($id);
        $data['suppliers'] = $this->supplier_model->getData('supplier', 's_status', 1)->result();
        $data['foodPurchaseValueBySummaryById'] = $this->food_model->getFoodPurchaseValueBySummaryId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('edit_food_purchase', $data);
        $this->load->view('home/footer'); // just the header file
    }


    /* ====================== Update Food Purchase ====================== */
    public function updateFoodPurchase()
    {
        $this->db->trans_start();
        // Delete Previous Data
        $fdps_id = $this->input->post('fdps_id');

        // Delete Value
        $updateValueData = array(
            'fdpv_status' => 0,
            'fdpv_updated_at' => get_current_time(),
            'fdpv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_purchase_value', 'fdpv_fdps_id', $fdps_id, $updateValueData);
        // Delete Previous Data

        // New Insert
        // Summary
        $fdp_s_id = $this->input->post('fdp_s_id');
        $date = $this->input->post('fdp_date');
        $fdp_date = date("Y-m-d", strtotime($date));
        $fdp_sub_total = $this->input->post('fdp_sub_total');
        $fdp_grand_discount = $this->input->post('fdp_grand_discount');
        $fdp_grand_total = $this->input->post('fdp_grand_total');
        $fdp_description = $this->input->post('fdp_description');
        $updateSummaryData = array(
            'fdps_s_id' => $fdp_s_id,
            'fdps_date' => $fdp_date,
            'fdps_sub_total' => (empty($fdp_sub_total) && $fdp_sub_total !== '0') ? 0 : $fdp_sub_total,
            'fdps_grand_discount' => (empty($fdp_grand_discount) && $fdp_grand_discount !== '0') ? 0 : $fdp_grand_discount,
            'fdps_grand_total' => (empty($fdp_grand_total) && $fdp_grand_total !== '0') ? 0 : $fdp_grand_total,
            'fdps_description' => $fdp_description,
            'fdps_updated_at' => get_current_time(),
            'fdps_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_purchase_summary', 'fdps_id', $fdps_id, $updateSummaryData);
        // Value
        $fdp_fd_id = $this->input->post('fdp_fd_id');
        $fdp_unit_price = $this->input->post('fdp_unit_price');
        $fdp_quantity = $this->input->post('fdp_quantity');
        $fdp_discount = $this->input->post('fdp_discount');
        $fdp_total = $this->input->post('fdp_total');
        for ($i = 0; $i < count($fdp_unit_price); $i++) {
            $ValueData = array(
                'fdpv_fdps_id' => $fdps_id,
                'fdpv_fd_id' => $fdp_fd_id[$i],
                'fdpv_unit_price' => $fdp_unit_price[$i],
                'fdpv_quantity' => $fdp_quantity[$i],
                'fdpv_discount' => (empty($fdp_discount[$i]) && $fdp_discount[$i] !== '0') ? 0 : $fdp_discount[$i],
                'fdpv_total' => $fdp_total[$i],
                'fdpv_status' => 1,
                'fdpv_created_at' => get_current_time(),
                'fdpv_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->food_model->insertData('food_purchase_value', $ValueData);
        }


        // If there is any previous payment on this purchase then payments will be deleted. You can add new payment
        $deletePreviousPayments = array(
            'sp_status' => 0,
            'sp_updated_at' => get_current_time(),
            'sp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->payments_model->updateData('supplier_payment', 'sp_fdps_id', $fdps_id, $deletePreviousPayments);


        // Insert New Purchase Payment
        $sp_payment_amount = $this->input->post('sp_payment_amount');
        $sp_paid_by = $this->input->post('sp_paid_by');
        $sp_cheque_no = $this->input->post('sp_cheque_no');
        $sp_reference = $this->input->post('sp_reference');
        $sp_description = $this->input->post('sp_description');
        if (!empty($fdps_id) &&  $sp_payment_amount > 0) {
            $foodPurchasePaymentData = array(
                'sp_s_id' =>  $fdp_s_id,
                'sp_fdps_id' =>  $fdps_id,
                'sp_payment_amount' =>  $sp_payment_amount,
                'sp_paid_by' =>  $sp_paid_by,
                'sp_cheque_no' =>  $sp_cheque_no,
                'sp_reference' =>  $sp_reference,
                'sp_date' =>  $fdp_date,
                'sp_description' =>  $sp_description,
                'sp_status' => 1,
                'sp_purchase_type' => 2,
                'sp_created_at' => get_current_time(),
                'sp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('supplier_payment', $foodPurchasePaymentData);
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Food purchase updated successfully.');
        redirect("food/listFoodPurchase");
    }



    /* ====================== Delete Food Purchase ====================== */
    function deleteFoodPurchase()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        $fdps_id = $this->input->post('fdps_id');
        // Delete Summary
        $updateSummaryData = array(
            'fdps_status' => 0,
            'fdps_updated_at' => get_current_time(),
            'fdps_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_purchase_summary', 'fdps_id', $fdps_id, $updateSummaryData);
        // Delete Value
        $updateValueData = array(
            'fdpv_status' => 0,
            'fdpv_updated_at' => get_current_time(),
            'fdpv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_purchase_value', 'fdpv_fdps_id', $fdps_id, $updateValueData);

        // delete Payments Delete under this invoice
        $deletePayments = array(
            'sp_status' => 0,
            'sp_updated_at' => get_current_time(),
            'sp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('supplier_payment', 'sp_fdps_id', $fdps_id, $deletePayments);

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Food purchase deleted successfully.');
        redirect('food/listFoodPurchase');
    }





    /* ===================================== Food Stock ===================================== */
    /* ====================== List Food Stock ====================== */
    public function listFoodStock()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['foods'] = $this->food_model->getFood();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_food_stock', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== get Food Details By Ajax ====================== */
    function getFoodAssignedBatchByFoodSummaryIdByJason()
    {
        $fdv_fds_id = $this->input->get('fdv_fds_id');
        $data['newFoodValues'] = $this->food_model->getFoodAssignedBatchBySummaryId($fdv_fds_id);


        // Pass data to match with assign summary table
        $foodValues = [];
        foreach ($data['newFoodValues'] as $value1) {
            $batchActiveStatus = $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($value1->fdv_assigned_shed_id, $value1->fdv_assigned_batch_id)->lshs_active_status;
            if ($batchActiveStatus == 0) {
                array_push($foodValues, $value1);
            }
        }

        // Get conditional data after match assign summary table
        $data['foodValues'] = $foodValues;

        // Get values
        foreach ($data['foodValues'] as $value) {
            $shedNo[] = $this->shed_model->getShedById($value->fdv_assigned_shed_id)->sh_no;
            $shedTitle[] = $this->shed_model->getShedById($value->fdv_assigned_shed_id)->sh_title;
            $batchTitle[] = $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($value->fdv_assigned_shed_id, $value->fdv_assigned_batch_id)->lshs_batch_title;
        }

        // Pass data into array 
        $data['shedNo'] = $shedNo;
        $data['shedTitle'] = $shedTitle;
        $data['batchTitle'] = $batchTitle;

        echo json_encode($data);
    }
    /* ====================== Get Shed Details By Ajax ====================== */
    function getBatchDetailsByJason()
    {
        $batch_id = $this->input->get('batch_id');
        $data['batchNames'] = $this->shed_model->getBatch($batch_id);
        echo json_encode($data);
    }

    /* ====================== Food Distributed ====================== */
    /* ====================== Insert Food Distributed ====================== */

    public function insertFoodDistributedQuantity()
    {
        $this->db->trans_start();
        // Summary
        $fdd_fd_id = $this->input->post('fdd_fd_id');
        $date = $this->input->post('fdd_date');
        $fdd_date = date("Y-m-d", strtotime($date));
        $summaryData = array(
            'fdds_fd_id' => $fdd_fd_id,
            'fdds_date' => $fdd_date,
            'fdds_status' => 1,
            'fdds_created_at' => get_current_time(),
            'fdds_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $distributedSummaryId = $this->food_model->insertData('food_distributed_summary', $summaryData);
        // Value
        $fddv_shed_id = $this->input->post('fdd_shed_id');
        $fddv_batch_id = $this->input->post('fdd_batch_id');
        $fdd_need_quantity = $this->input->post('fdd_need_quantity');
        $fdd_distributed_quantity = $this->input->post('fdd_distributed_quantity');
        $fdd_description = $this->input->post('fdd_description');
        if (!is_array($fdd_description)) {
            $fdd_description = array();
        }
        for ($i = 0; $i < count($fddv_batch_id); $i++) {
            $ValueData = array(
                'fddv_fdds_id' => $distributedSummaryId,
                'fddv_shed_id' => $fddv_shed_id[$i],
                'fddv_batch_id' => $fddv_batch_id[$i],
                'fddv_need_quantity' => $fdd_need_quantity[$i],
                'fddv_distributed_quantity' => $fdd_distributed_quantity[$i],
                'fddv_description' => isset($fdd_description[$i]) ? $fdd_description[$i] : '',
                'fddv_status' => 1,
                'fddv_created_at' => get_current_time(),
                'fddv_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->food_model->insertData('food_distributed_value', $ValueData);
        }
        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Food distribution added successfully.');
        redirect("food/listFoodStock");
    }

    /* ====================== Insert Food Distributed ====================== */
    public function updateFoodDistributedQuantity()
    {
        $this->db->trans_start();
        // Summary
        $fds_id = $this->input->post('fds_id');
        $fdds_id = $this->input->post('fdds_id');
        $date = $this->input->post('fdds_date');
        $fdd_date = date("Y-m-d", strtotime($date));

        $fddv_id = $this->input->post('fddv_id');
        $fddv_shed_id = $this->input->post('fddv_shed_id');
        $fddv_batch_id = $this->input->post('fddv_batch_id');
        $fdd_need_quantity = $this->input->post('fddv_need_quantity');
        $fddv_distributed_quantity = $this->input->post('fddv_distributed_quantity');
        $fddv_description = $this->input->post('fddv_description');
        if (!is_array($fddv_description)) {
            $fddv_description = array();
        }

        $summaryDataUpdate = array(
            'fdds_date' => $fdd_date,
            'fdds_updated_at' => get_current_time(),
            'fdds_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_distributed_summary', 'fdds_id', $fdds_id, $summaryDataUpdate);

        // Delete Previous Value Data
        $ValueDataUpdate = array(
            'fddv_status' => 0,
            'fddv_updated_at' => get_current_time(),
            'fddv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_distributed_value', 'fddv_fdds_id', $fdds_id, $ValueDataUpdate);
        // Value

        for ($i = 0; $i < count($fddv_batch_id); $i++) {
            $ValueData = array(
                'fddv_fdds_id' => $fdds_id,
                'fddv_shed_id' => $fddv_shed_id[$i],
                'fddv_batch_id' => $fddv_batch_id[$i],
                'fddv_need_quantity' => $fdd_need_quantity[$i],
                'fddv_distributed_quantity' => $fddv_distributed_quantity[$i],
                'fddv_description' => isset($fddv_description[$i]) ? $fddv_description[$i] : '',
                'fddv_status' => 1,
                'fddv_created_at' => get_current_time(),
                'fddv_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->food_model->insertData('food_distributed_value', $ValueData);
        }
        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Food distribution updated successfully.');
        redirect("food/addFoodWiseDistributedReport?fds_id=$fds_id");
    }
    /* ====================== Delete Food Distributed ====================== */
    public function deleteFoodDistributedQuantity()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        // Summary
        $fdds_id = $this->input->post('fdds_id');
        $fds_id = $this->input->post('fds_id');
        $summaryDataUpdate = array(
            'fdds_status' => 0,
            'fdds_updated_at' => get_current_time(),
            'fdds_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_distributed_summary', 'fdds_id', $fdds_id, $summaryDataUpdate);
        // Value
        $ValueDataUpdate = array(
            'fddv_status' => 0,
            'fddv_updated_at' => get_current_time(),
            'fddv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_distributed_value', 'fddv_fdds_id', $fdds_id, $ValueDataUpdate);
        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Food distribution deleted successfully.');
        redirect("food/addFoodWiseDistributedReport?fds_id=$fds_id");
    }
    /* ====================== Food Wise Distributed Report ====================== */
    public function addFoodWiseDistributedReport()
    {
        $id = $this->input->get('fds_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['foodById'] = $this->food_model->getFoodSummaryById($id);
        $data['foodDistributedSummary'] = $this->food_model->getFoodDistributedSummaryByFoodId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('add_food_wise_distributed_report', $data);
        $this->load->view('home/footer'); // just the header file
    }

    function getFoodDistributedQuantityValuesByJason()
    {
        $fddv_fdds_id = $this->input->get('fddv_fdds_id');
        $data['editDistributedFoodValues'] = $this->food_model->getFoodDistributedValuesByFoodDistributedSummaryId($fddv_fdds_id);

        foreach ($data['editDistributedFoodValues'] as $value) {
            $shedNo[] = $this->shed_model->getShedById($value->fddv_shed_id)->sh_no;
            $shedTitle[] = $this->shed_model->getShedById($value->fddv_shed_id)->sh_title;
            $batchTitle[] = $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($value->fddv_shed_id, $value->fddv_batch_id)->lshs_batch_title;
        }
        $data['shedNo'] = $shedNo;
        $data['shedTitle'] = $shedTitle;
        $data['batchTitle'] = $batchTitle;


        echo json_encode($data);
    }



    /* ====================== Food Wasted Module ====================== */
    /* ====================== insert Wasted Food ====================== */
    public function insertWastedFood()
    {
        // Summary
        $fdw_fd_id = $this->input->post('fdw_fd_id');
        $fdw_quantity = $this->input->post('fdw_quantity');
        $fdw_description = $this->input->post('fdw_description');
        $data = array(
            'fdw_fd_id' => $fdw_fd_id,
            'fdw_quantity' => $fdw_quantity,
            'fdw_description' => $fdw_description,
            'fdw_status' => 1,
            'fdw_created_at' => get_current_time(),
            'fdw_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->insertData('food_wasted', $data);
        $this->session->set_flashdata('success', 'Wasted food recorded successfully.');
        redirect("food/listFoodStock");
    }
    /* ====================== add wasted food report ====================== */
    public function addWastedFoodReport()
    {
        $id = $this->input->get('fds_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['foodById'] = $this->food_model->getFoodSummaryById($id);
        $data['wastedFoodValues'] = $this->food_model->getFoodWastedValuesByFoodId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('add_wasted_food_report', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== update wasted food ====================== */
    public function updateWastedFood()
    {
        $fdw_fd_id = $this->input->post('fdw_fd_id');
        $fdw_id = $this->input->post('fdw_id');
        $fdw_quantity = $this->input->post('fdw_quantity');
        $fdw_description = $this->input->post('fdw_description');
        $ValueDataUpdate = array(
            'fdw_fd_id' => $fdw_fd_id,
            'fdw_quantity' => $fdw_quantity,
            'fdw_description' => $fdw_description,
            'fdw_updated_at' => get_current_time(),
            'fdw_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_wasted', 'fdw_id', $fdw_id, $ValueDataUpdate);
        $this->session->set_flashdata('success', 'Wasted food updated successfully.');
        redirect("food/addWastedFoodReport?fds_id=$fdw_fd_id");
    }


    public function deleteWastedFood()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $fdw_fd_id = $this->input->post('fdw_fd_id');
        $fdw_id = $this->input->post('fdw_id');
        $ValueDataUpdate = array(
            'fdw_status' => 0,
            'fdw_updated_at' => get_current_time(),
            'fdw_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->food_model->updateData('food_wasted', 'fdw_id', $fdw_id, $ValueDataUpdate);
        $this->session->set_flashdata('success', 'Wasted food deleted successfully.');
        redirect("food/addWastedFoodReport?fds_id=$fdw_fd_id");
    }



    // End
}

/* End of file food.php */
/* Location: ./application/modules/food/controllers/food.php */
