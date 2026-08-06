<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Shed extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('shed_model');
        $this->load->library('upload');
        $this->load->model('settings/settings_model');
        $settings = $this->settings_model->getSettings();
        $language = (!empty($settings) && !empty($settings->language)) ? $settings->language : 'english';
        $this->lang->load('system_syntax', $language);
        $this->load->model('home/home_model');
        $this->load->model('livestock/livestock_model');
        $this->load->model('report/report_model');
        $this->load->model('sale/sale_model');
        $this->load->model('vaccine/vaccine_model');
        $this->load->model('purchase/purchase_model');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin'))) {
            redirect('home/permission');
        }
    }

    public function addShed()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['sheds'] = $this->shed_model->getShed();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('sheds', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertShed()
    {
        $this->form_validation->set_rules('sh_no', 'Shed number', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('sh_title', 'Shed title', 'trim|required|max_length[255]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('shed/addShed');
        }
        $sh_id = $this->input->post('sh_id');
        $sh_no = $this->input->post('sh_no');
        $sh_title = $this->input->post('sh_title');
        $sh_description = $this->input->post('sh_description');

        if (empty($sh_id)) {
            $data = array(
                'sh_no' => $sh_no,
                'sh_title' => $sh_title,
                'sh_description' => $sh_description,
                'sh_status' => 1,
                'sh_created_at' => get_current_time(),
                'sh_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->shed_model->insertShed('shed', $data);
            $this->session->set_flashdata('success', 'Shed added successfully.');
            redirect('shed/addShed');
        } else {
            $data = array(
                'sh_no' => $sh_no,
                'sh_title' => $sh_title,
                'sh_description' => $sh_description,
                'sh_updated_at' => get_current_time(),
                'sh_updated_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->shed_model->updateShed($sh_id, $data);
            $this->session->set_flashdata('success', 'Shed updated successfully.');
            redirect('shed/addShed');
        }
    }

    function deleteShed()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        $sh_id = $this->input->post('sh_id');
        $data = array(
            'sh_status' => 0,
            'sh_updated_at' => get_current_time(),
            'sh_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->shed_model->updateShed($sh_id, $data);

        $updateData = array(
            'lsh_status' => 0,
            'lsh_updated_at' => get_current_time(),
            'lsh_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('live_assigned_shed', 'lsh_sh_id', $sh_id, $updateData);

        // Cascade soft-delete to live_assigned_shed_summary so the shed and its batches stay in sync
        $updateSummary = array(
            'lshs_status' => 0,
            'lshs_updated_at' => get_current_time(),
            'lshs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('live_assigned_shed_summary', 'lshs_sh_id', $sh_id, $updateSummary);

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Shed deleted successfully.');
        redirect('shed/addShed');
    }



    function editShedByJason()
    {
        $id = $this->input->get('sh_id');
        $data['shed'] = $this->shed_model->getShedById($id);
        $data['settings'] = $this->settings_model->getSettings();
        echo json_encode($data);
    }

    function viewShedWiseAssignedLivestock()
    {
        $sh_id = $this->input->get('sh_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['assignedBatchesByShedId'] = $this->shed_model->getAssignedBatchesByShedId($sh_id);
        $data['shedById'] = $this->shed_model->getShedById($sh_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_livestock_shed_wise', $data);
        $this->load->view('home/footer'); // just the header file
    }



    // Check existing shed number 
    function duplicateShedNumberCheckAjax()
    {
        $shed_serial_number = $this->input->get('shed_serial_number');
        $data = $this->shed_model->duplicateShedNumberCheckAjax($shed_serial_number)->sh_no;
        echo json_encode($data);
    }



    /* ============================== Livestock Death From Shed ============================== */
    /* ================ list of Sheds ================ */
    public function listDeath()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['sheds'] = $this->shed_model->getShed();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_death', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ================ Shed Wise Livestock Death ================ */
    function addShedWiseLivestockDeath()
    {
        $sh_id = $this->input->get('sh_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['assignedLivestockByShedId'] = $this->shed_model->getAssignedBatchesByShedId($sh_id);
        $data['shedById'] = $this->shed_model->getShedById($sh_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_shed_wise_livestock_death', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ================ Insert death livestock ================ */
    public function insertLivestockToDeath()
    {
        $ld_lshs_id = $this->input->post('ld_lshs_id');
        $ld_purv_ls_id = $this->input->post('ld_purv_ls_id');
        $ld_purv_lst_id = $this->input->post('ld_purv_lst_id');
        $ld_death_quantity = $this->input->post('ld_death_quantity');
        $ld_shed_id = $this->input->post('ld_shed_id');
        $ld_batch_id = $this->input->post('ld_batch_id');
        $ld_date = $this->input->post('ld_death_date');
        $ld_death_date = date("Y-m-d", strtotime($ld_date));
        $ld_death_reason = $this->input->post('ld_death_reason');
        $ValueData = array(
            'ld_lshs_id' => $ld_lshs_id,
            'ld_purv_ls_id' => $ld_purv_ls_id,
            'ld_purv_lst_id' => $ld_purv_lst_id,
            'ld_death_quantity' => $ld_death_quantity,
            'ld_sh_id' => $ld_shed_id,
            'ld_batch_id' => $ld_batch_id,
            'ld_death_date' => $ld_death_date,
            'ld_death_reason' => $ld_death_reason,
            'ld_status' => 1,
            'ld_created_at' => get_current_time(),
            'ld_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->insertPurchase('livestock_death_quantity', $ValueData);
        $this->session->set_flashdata('success', 'Death record added successfully.');
        redirect("shed/addShedWiseLivestockDeath?sh_id=$ld_shed_id");
    }

    /* ================ Update death livestock ================ */
    public function updateLivestockToDeath()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app edit form.', 400);
        }
        $ld_id = $this->input->post('ld_id');
        $ld_death_quantity = $this->input->post('ld_death_quantity');
        $ld_shed_id = $this->input->post('ld_sh_id');
        $ld_date = $this->input->post('ld_death_date');
        $ld_death_date = date("Y-m-d", strtotime($ld_date));
        $ld_death_reason = $this->input->post('ld_death_reason');
        $ValueData = array(
            'ld_death_quantity' => $ld_death_quantity,
            'ld_death_date' => $ld_death_date,
            'ld_death_reason' => $ld_death_reason,
            'ld_updated_at' => get_current_time(),
            'ld_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->shed_model->updateData('livestock_death_quantity', 'ld_id', $ld_id, $ValueData);
        $this->session->set_flashdata('success', 'Death record updated successfully.');
        redirect("shed/addShedWiseLivestockDeath?sh_id=$ld_shed_id");
    }

    /* ================ Delete death livestock ================ */
    function deleteLivestockToDeath()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $ld_shed_id = $this->input->post('ld_sh_id');
        $ld_id = $this->input->post('ld_id');
        $deleteData = array(
            'ld_status' => 0,
            'ld_updated_at' => get_current_time(),
            'ld_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->shed_model->updateData('livestock_death_quantity', 'ld_id', $ld_id, $deleteData);
        $this->session->set_flashdata('success', 'Death record deleted successfully.');
        redirect("shed/addShedWiseLivestockDeath?sh_id=$ld_shed_id");
    }

    /* ============================== Batch ============================== */
    // Active inactive status
    public function batchInactiveStatus()
    {
        $shed_id = $this->input->get('shed_id');
        $lshs_id = $this->input->get('lshs_id');

        $summaryData = array(
            'lshs_active_status' => 1,
            'lshs_updated_at' => get_current_time(),
            'lshs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('live_assigned_shed_summary', 'lshs_id', $lshs_id, $summaryData);
        $this->session->set_flashdata('success', 'Batch status set to inactive.');
        redirect("shed/viewShedWiseAssignedLivestock?sh_id=$shed_id");
    }

    public function batchActiveStatus()
    {
        $shed_id = $this->input->get('shed_id');
        $lshs_id = $this->input->get('lshs_id');

        $summaryData = array(
            'lshs_active_status' => 0,
            'lshs_updated_at' => get_current_time(),
            'lshs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('live_assigned_shed_summary', 'lshs_id', $lshs_id, $summaryData);
        $this->session->set_flashdata('success', 'Batch status set to active.');
        redirect("shed/viewShedWiseAssignedLivestock?sh_id=$shed_id");
    }



    /* ============================== Shed to she livestock Transfer ============================== */
    /* ================ list of Sheds ================ */
    public function listShedWiseLivestockTransfer()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['sheds'] = $this->shed_model->getShed();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_livestock_transfer', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ================ Add livestock one shed to another shed  ================ */
    function addShedWiseLivestockTransfer()
    {
        $sh_id = $this->input->get('sh_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['assignedLivestockByShedId'] = $this->shed_model->getAssignedBatchesByShedId($sh_id);
        $data['sheds'] = $this->shed_model->getShed();
        $data['shedById'] = $this->shed_model->getShedById($sh_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('add_shed_wise_livestock_transfer', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ================ Insert livestock Transfer to assign table ================ */
    public function insertLivestockTransfer()
    {
        $this->db->trans_start();
        $assigned_summary_id = $this->input->post('assigned_summary_id');
        $lsh_sh_id = $this->input->post('lsh_sh_id');
        $lsh_batch_number = $this->input->post('lsh_batch_number');
        $lsh_batch_title = $this->input->post('lsh_batch_title');
        $lshs_description = $this->input->post('lshs_description');

        // Transfer Table
        $ltr_purv_ls_id = $this->input->post('ltr_purv_ls_id');
        $ltr_purv_lst_id = $this->input->post('ltr_purv_lst_id');
        $ltr_transfer_quantity = $this->input->post('ltr_transfer_quantity');

        $ltr_shed_id = $this->input->post('ltr_shed_id');
        $ltr_batch_id = $this->input->post('ltr_batch_id');

        $ld_date = $this->input->post('ltr_transfer_date');
        $ltr_transfer_date = date("Y-m-d", strtotime($ld_date));
        $ltr_description = $this->input->post('ltr_description');

        // Store data in transfer table to track transfer shed and batches
        $ValueData = array(
            'ltr_purv_ls_id' => $ltr_purv_ls_id,
            'ltr_purv_lst_id' => $ltr_purv_lst_id,
            'ltr_transfer_quantity' => $ltr_transfer_quantity,

            'ltr_sh_id' => $ltr_shed_id,
            'ltr_batch_id' => $ltr_batch_id,

            'ltr_target_sh_id' => $lsh_sh_id,
            'ltr_target_batch_id' => $lsh_batch_number,

            'ltr_transfer_date' => $ltr_transfer_date,
            'ltr_description' => $ltr_description,
            'ltr_status' => 1,
            'ltr_created_at' => get_current_time(),
            'ltr_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->shed_model->insertData('livestock_transfer_quantity', $ValueData);

        // Summary
        $updateSummaryData = array(
            'lshs_description' => $lshs_description,
            'lshs_sh_id' => $lsh_sh_id,
            'lshs_batch_id' => $lsh_batch_number,
            'lshs_batch_title' => $lsh_batch_title,
            'lshs_transfer_date' => $ltr_transfer_date,
            'lshs_updated_at' => get_current_time(),
            'lshs_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('live_assigned_shed_summary', 'lshs_id', $assigned_summary_id, $updateSummaryData);
        // Value
        $updateValueData = array(
            'lsh_sh_id' => $lsh_sh_id,
            'lsh_batch_id' => $lsh_batch_number,
            'lsh_updated_at' => get_current_time(),
            'lsh_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('live_assigned_shed', 'lsh_lshs_id', $assigned_summary_id, $updateValueData);



        // Update Vaccine modules vaccine dose status 
        // Vaccine dose status table
        $updateVaccineDoseStatus = array(
            'vds_sh_id' => $lsh_sh_id,
            'vds_batch_id' => $lsh_batch_number,
            'vds_updated_at' => get_current_time(),
            'vds_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateData('vaccine_dose_status', 'vds_lshs_id', $assigned_summary_id, $updateVaccineDoseStatus);


        // Update Food Batches in food module assign and distribute food module
        // Food Value Table
        $updateAssignedFoodValueData = array(
            'fdv_assigned_shed_id' => $lsh_sh_id,
            'fdv_assigned_batch_id' => $lsh_batch_number,
            'fdv_updated_at' => get_current_time(),
            'fdv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateDataMultipleCondition('food_value', ['fdv_assigned_shed_id' => $ltr_shed_id, 'fdv_assigned_batch_id' => $ltr_batch_id], $updateAssignedFoodValueData);

        // Food Distribute Table
        $updateDistributedFoodValueData = array(
            'fddv_shed_id' => $lsh_sh_id,
            'fddv_batch_id' => $lsh_batch_number,
            'fddv_updated_at' => get_current_time(),
            'fddv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->purchase_model->updateDataMultipleCondition('food_distributed_value', ['fddv_shed_id' => $ltr_shed_id, 'fddv_batch_id' => $ltr_batch_id], $updateDistributedFoodValueData);

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Livestock transferred successfully.');
        redirect("shed/addShedWiseLivestockTransfer?sh_id=$ltr_shed_id");
    }


    /* ================ Insert livestock Transfer ================ */
    public function insertShedWiseLivestockTransfer()
    {
        $ltr_purv_ls_id = $this->input->post('ltr_purv_ls_id');
        $ltr_purv_lst_id = $this->input->post('ltr_purv_lst_id');
        $ltr_transfer_quantity = $this->input->post('ltr_transfer_quantity');
        $ltr_target_sh_id = $this->input->post('ltr_target_sh_id');
        $ltr_shed_id = $this->input->post('ltr_shed_id');
        $ltr_batch_id = $this->input->post('ltr_batch_id');
        $ld_date = $this->input->post('ltr_transfer_date');
        $ltr_transfer_date = date("Y-m-d", strtotime($ld_date));
        $ltr_description = $this->input->post('ltr_description');
        $ValueData = array(
            'ltr_purv_ls_id' => $ltr_purv_ls_id,
            'ltr_purv_lst_id' => $ltr_purv_lst_id,
            'ltr_target_sh_id' => $ltr_target_sh_id,
            'ltr_transfer_quantity' => $ltr_transfer_quantity,
            'ltr_sh_id' => $ltr_shed_id,
            'ltr_batch_id' => $ltr_batch_id,
            'ltr_transfer_date' => $ltr_transfer_date,
            'ltr_description' => $ltr_description,
            'ltr_status' => 1,
            'ltr_created_at' => get_current_time(),
            'ltr_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->shed_model->insertData('livestock_transfer_quantity', $ValueData);
        $this->session->set_flashdata('success', 'Livestock transfer added successfully.');
        redirect("shed/addShedWiseLivestockTransfer?sh_id=$ltr_shed_id");
    }

    /* ================ Update transfer quantity ================ */
    public function updateShedWiseLivestockTransfer()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app edit form.', 400);
        }
        $ltr_id = $this->input->post('ltr_id');
        $ltr_target_sh_id = $this->input->post('ltr_target_sh_id');
        $ltr_transfer_quantity = $this->input->post('ltr_transfer_quantity');
        $ltr_shed_id = $this->input->post('ltr_sh_id');
        $ltr_batch_id = $this->input->post('ltr_batch_id');
        $ld_date = $this->input->post('ltr_transfer_date');
        $ltr_transfer_date = date("Y-m-d", strtotime($ld_date));
        $ltr_description = $this->input->post('ltr_description');
        $ValueData = array(
            'ltr_target_sh_id' => $ltr_target_sh_id,
            'ltr_transfer_quantity' => $ltr_transfer_quantity,
            'ltr_batch_id' => $ltr_batch_id,
            'ltr_transfer_date' => $ltr_transfer_date,
            'ltr_description' => $ltr_description,
            'ltr_updated_at' => get_current_time(),
            'ltr_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->shed_model->updateData('livestock_transfer_quantity', 'ltr_id', $ltr_id, $ValueData);
        $this->session->set_flashdata('success', 'Livestock transfer updated successfully.');
        redirect("shed/addShedWiseLivestockTransfer?sh_id=$ltr_shed_id");
    }

    /* ================ Delete Transfer Livestock ================ */
    function deleteShedWiseLivestockTransfer()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $ltr_shed_id = $this->input->post('ltr_sh_id');
        $ltr_id = $this->input->post('ltr_id');
        $deleteData = array(
            'ltr_status' => 0,
            'ltr_updated_at' => get_current_time(),
            'ltr_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->shed_model->updateData('livestock_transfer_quantity', 'ltr_id', $ltr_id, $deleteData);
        $this->session->set_flashdata('success', 'Livestock transfer deleted successfully.');
        redirect("shed/addShedWiseLivestockTransfer?sh_id=$ltr_shed_id");
    }

    // Cascading Dropdown for add
    function getTargetBatchByTargetShedId()
    {
        $categoryId = $this->input->post('expense_category_id');
        $subCategoryId = $this->input->post('expense_subcategory_id');
        if ($categoryId) {
            echo $this->expense_model->getExpenseCategoryCascadingDropdown($categoryId, $subCategoryId);
        }
    }



    // End
}


/* End of file shed.php */
/* Location: ./application/modules/shed/controllers/shed.php */
