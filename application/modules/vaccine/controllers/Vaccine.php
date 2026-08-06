<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vaccine extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('vaccine_model');
        $this->load->model('purchase/purchase_model');
        $this->load->model('livestock/livestock_model');
        $this->load->model('supplier/supplier_model');
        $this->load->model('payments/payments_model');
        $this->load->model('report/report_model');
        $this->load->model('sale/sale_model');
        $this->load->model('shed/shed_model');
        $this->load->library('upload');
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
        $data['settings'] = $this->settings_model->getSettings();
        $data['units'] = $this->settings_model->getUnit();
        $data['vaccines'] = $this->vaccine_model->getVaccine();
        $data['vaccineRoutes'] = $this->vaccine_model->getVaccineRoute();
        $data['batches'] = $this->shed_model->getBatch();
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['livestock_types'] = $this->livestock_model->getLivestockType();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('vaccine', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ==================== Insert & Edit Vaccine ==================== */
    public function insertVaccine()
    {
        $vcc_id = $this->input->post('vcc_id');
        $vcc_name = $this->input->post('vcc_name');
        $vcc_unit_id = $this->input->post('vcc_unit_id');
        $vcc_description = $this->input->post('vcc_description');
        if (empty($vaccine_id)) {
            $vaccine_id = rand(10000, 1000000);
        }
        if (empty($vcc_id)) {
            $data = array(
                'vcc_vaccine_id' => $vaccine_id,
                'vcc_name' => $vcc_name,
                'vcc_unit_id' => $vcc_unit_id,
                'vcc_description' => $vcc_description,
                'vcc_status' => 1,
                'vcc_created_at' => get_current_time(),
                'vcc_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->vaccine_model->insertData('vaccine', $data);
            $this->session->set_flashdata('success', 'Vaccine added successfully.');
        } else {
            $updateData = array(
                'vcc_name' => $vcc_name,
                'vcc_unit_id' => $vcc_unit_id,
                'vcc_description' => $vcc_description,
                'vcc_updated_at' => get_current_time(),
                'vcc_updated_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->vaccine_model->updateData('vaccine', 'vcc_id', $vcc_id, $updateData);
            $this->session->set_flashdata('success', 'Vaccine updated successfully.');
        }
        redirect('vaccine');
    }

    /* ==================== Delete Vaccine ==================== */
    function deleteVaccine()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        // summary table
        $vcc_id = $this->input->post("vcc_id");
        $data = array(
            'vcc_status' => 0,
            'vcc_updated_at' => get_current_time(),
            'vcc_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('vaccine', 'vcc_id', $vcc_id, $data);
        $this->session->set_flashdata('success', 'Vaccine deleted successfully.');
        redirect('vaccine');
    }




    /* =========================================== Vaccine Doses =========================================== */
    /* ==================== insert Vaccine Doses ==================== */
    public function insertVaccination()
    {
        $this->db->trans_start();
        $vccn_id = $this->input->post('vccn_id');
        $vcc_id = $this->input->post('vcc_id');

        $vaccine_id = $this->input->post('vaccine_id');

        $vccn_ls_id = $this->input->post('vccn_ls_id');
        $vccn_lst_id = $this->input->post('vccn_lst_id');

        $vdq_date = $this->input->post('vdq_vaccination_date');
        $vdq_date_for_sorted = $this->input->post('vdq_vaccination_date');
        $vdq_vaccine_route = $this->input->post('vdq_vaccine_route');
        $vdq_description = $this->input->post('vdq_description');

        if (empty($vaccine_id)) {
            $vaccine_id = rand(10000, 1000000);
        }

        // exit();



        if (empty($vccn_id)) {

            // Sorting Vaccination Day smallest to largest
            sort($vdq_date);

            if ($vdq_date_for_sorted === $vdq_date) {

                $data = array(
                    'vccn_vcc_id' => $vaccine_id,
                    'vccn_ls_id' => $vccn_ls_id,
                    'vccn_lst_id' => $vccn_lst_id,
                    'vccn_status' => 1,
                    'vccn_created_at' => get_current_time(),
                    'vccn_created_by' => $this->ion_auth->user()->row()->user_id
                );
                $vaccinationId = $this->vaccine_model->insertData('vaccination', $data);

                for ($i = 0; $i < count($vdq_vaccine_route); $i++) {
                    $dataV = array(
                        'vdq_vccn_id' => $vaccinationId,
                        'vdq_dose_serial' => $i + 1,
                        'vdq_vaccination_date' => $vdq_date[$i],
                        'vdq_vaccine_route' => $vdq_vaccine_route[$i],
                        'vdq_description' => $vdq_description[$i],
                        'vdq_status' => 1,
                        'vdq_created_at' => get_current_time(),
                        'vdq_created_by' => $this->ion_auth->user()->row()->user_id,
                    );
                    $this->vaccine_model->insertData('vaccine_dose_assigned_quantity', $dataV);
                }
            } else {
                $this->session->set_flashdata('error', 'Please enter vaccination days smallest day to largest day.');
            }

            $this->db->trans_complete();
            $this->session->set_flashdata('success', 'Vaccination schedule added successfully.');
            redirect('vaccine');
        } else {

            // Sorting Vaccination Day smallest to largest
            sort($vdq_date);

            if ($vdq_date_for_sorted === $vdq_date) {

                // Delete Previous Doses
                $deleteDosesDoseUnderVaccine = array(
                    'vdq_status' => 0,
                    'vdq_updated_at' => get_current_time(),
                    'vdq_updated_by' => $this->ion_auth->user()->row()->user_id
                );
                $this->vaccine_model->updateData('vaccine_dose_assigned_quantity', 'vdq_vccn_id', $vccn_id, $deleteDosesDoseUnderVaccine);

                // Update vaccine
                $updateSummaryData = array(
                    'vccn_ls_id' => $vccn_ls_id,
                    'vccn_lst_id' => $vccn_lst_id,
                    'vccn_updated_at' => get_current_time(),
                    'vccn_updated_by' => $this->ion_auth->user()->row()->user_id
                );
                $this->vaccine_model->updateData('vaccination', 'vccn_id', $vccn_id, $updateSummaryData);

                for ($i = 0; $i < count($vdq_vaccine_route); $i++) {
                    $dataNew = array(
                        'vdq_vccn_id' => $vccn_id,
                        'vdq_dose_serial' => $i + 1,
                        'vdq_vaccination_date' => $vdq_date[$i],
                        'vdq_vaccine_route' => $vdq_vaccine_route[$i],
                        'vdq_description' => $vdq_description[$i],
                        'vdq_status' => 1,
                        'vdq_created_at' => get_current_time(),
                        'vdq_created_by' => $this->ion_auth->user()->row()->user_id,
                    );
                    $this->vaccine_model->insertData('vaccine_dose_assigned_quantity', $dataNew);
                }
            } else {
                $this->session->set_flashdata('error', 'Please enter vaccination days smallest day to largest day.');
            }
            $this->db->trans_complete();
            $this->session->set_flashdata('success', 'Vaccination schedule updated successfully.');
            redirect("vaccine/viewVaccinationList?vcc_id=$vcc_id");
        }
    }

    //Get Assigned Vaccine By Livestock and type and vaccine
    function getAssignedVaccineByLivestockAndTypeWise()
    {
        $lst_id = $this->input->post('lst_id');
        $ls_id = $this->input->post('ls_id');
        $vcc_id = $this->input->post('vcc_id');
        if ($vcc_id && $ls_id && $lst_id) {
            $returnValue = $this->vaccine_model->getAssignedVaccineCheckByLivestockAndTypeWise($vcc_id, $ls_id, $lst_id);
        }
        if ($returnValue) {
            echo $returnValue->vccn_id;
        } else {
            echo 0;
        }
    }
    /* ==================== View Vaccine Doses ==================== */
    public function viewVaccinationList()
    {
        $vaccine_id = $this->input->get('vcc_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['vaccineRoutes'] = $this->vaccine_model->getVaccineRoute();
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['livestock_types'] = $this->livestock_model->getLivestockType();
        $data['vaccinationsByVaccineId'] = $this->vaccine_model->getVaccinationByVaccineId($vaccine_id);
        $data['vaccineById'] = $this->vaccine_model->getVaccineById($vaccine_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_vaccination_list', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ==================== Get Vaccination Doses with popup by ajax  ==================== */
    function getVaccineDosesByJason()
    {
        $vaccination_id = $this->input->get('vdq_vccn_id');
        $data['dosesById'] = $this->vaccine_model->getVaccineDosesByVaccinationId($vaccination_id);
        echo json_encode($data);
    }

    /* ==================== Delete Vaccine Doses  ==================== */
    function deleteVaccinationDoses()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        $vccn_id = $this->input->post('vccn_id');
        $vccn_vcc_id = $this->input->post('vccn_vcc_id');

        $updateSummaryData = array(
            'vccn_status' => 0,
            'vccn_updated_at' => get_current_time(),
            'vccn_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('vaccination', 'vccn_id', $vccn_id, $updateSummaryData);

        $data = array(
            'vdq_status' => 0,
            'vdq_updated_at' => get_current_time(),
            'vdq_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('vaccine_dose_assigned_quantity', 'vdq_vccn_id', $vccn_id, $data);

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Vaccination deleted successfully.');
        redirect("vaccine/viewVaccinationList?vcc_id=$vccn_vcc_id");
    }



    /* ===================== Vaccine Dose Status And Used Vaccine add ===================== */
    public function listVaccinatedShed()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['sheds'] = $this->shed_model->getShed();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_vaccinated_shed', $data);
        $this->load->view('home/footer'); // just the header file
    }

    function viewBatchWiseVaccinationSchedule()
    {
        $shed_id = $this->input->get('shed_id');
        $data['shedById'] =  $this->shed_model->getShedById($shed_id);
        $data['settings'] = $this->settings_model->getSettings();
        $data['batchesByShed'] = $this->vaccine_model->getAssignedBatchesBySheds($shed_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_vaccination_schedule', $data);
        $this->load->view('home/footer'); // just the header file
    }

    function insertVaccinationDoseStatus()
    {
        $vdq_id = $this->input->post('vdq_id');
        $vcc_id = $this->input->post('vcc_id');
        $vccn_id = $this->input->post('vccn_id');
        $batch_id = $this->input->post('batch_id');
        $lshs_id = $this->input->post('lshs_id');
        $sh_id = $this->input->post('sh_id');
        $quantity = $this->input->post('vaccine_used_quantity');
        $description = $this->input->post('description');
        $data = array(
            'vds_vdq_id' => $vdq_id,
            'vds_vcc_id' => $vcc_id,
            'vds_vccn_id' => $vccn_id,
            'vds_batch_id' => $batch_id,
            'vds_lshs_id' => $lshs_id,
            'vds_sh_id' => $sh_id,
            'vds_vaccine_used_quantity' => $quantity,
            'vds_description' => $description,
            'vds_status' => 1,
            'vds_created_at' => get_current_time(),
            'vds_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->insertData('vaccine_dose_status', $data);
        $this->session->set_flashdata('success', 'Vaccine dose status added successfully.');
        redirect("vaccine/viewBatchWiseVaccinationSchedule?shed_id=$sh_id");
    }

    function updateVaccinationDoseStatus()
    {
        $vds_id = $this->input->get('vds_id');
        $batch_id = $this->input->get('batch_id');
        $sh_id = $this->input->get('sh_id');
        $data = array(
            'vds_status' => 0,
            'vds_updated_at' => get_current_time(),
            'vds_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('vaccine_dose_status', 'vds_id', $vds_id, $data);
        $this->session->set_flashdata('success', 'Vaccine dose status updated successfully.');
        redirect("vaccine/viewBatchWiseVaccinationSchedule?shed_id=$sh_id");
    }



    /* =========================================== Vaccine Routing =========================================== */

    public function listVaccineRoute()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['vaccineRoutes'] = $this->vaccine_model->getVaccineRoute();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_vaccine_route', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertVaccineRoute()
    {
        $vccr_id = $this->input->post('vccr_id');
        $name = $this->input->post('vccr_name');
        $description = $this->input->post('vccr_description');
        if (empty($vccr_id)) {
            $data = array(
                'vccr_name' => $name,
                'vccr_description' => $description,
                'vccr_status' => 1,
                'vccr_created_at' => get_current_time(),
                'vccr_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->vaccine_model->insertData('vaccine_route', $data);
            $this->session->set_flashdata('success', 'Vaccine route added successfully.');
            redirect('vaccine/listVaccineRoute');
        } else {
            $updateData = array(
                'vccr_name' => $name,
                'vccr_description' => $description,
                'vccr_updated_at' => get_current_time(),
                'vccr_updated_by' => $this->ion_auth->user()->row()->user_id
            );
            if (!empty($vccr_id)) {
                $this->vaccine_model->updateData('vaccine_route', 'vccr_id', $vccr_id, $updateData);
            }
            $this->session->set_flashdata('success', 'Vaccine route updated successfully.');
            redirect('vaccine/listVaccineRoute');
        }
    }

    function editVaccineRouteByJason()
    {
        $id = $this->input->get('vccr_id');
        $data['routes'] = $this->vaccine_model->getVaccineRouteById($id);
        echo json_encode($data);
    }

    function deleteVaccineRoute()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $vccr_id = $this->input->post('vccr_id');
        $data = array(
            'vccr_status' => 0,
            'vccr_updated_at' => get_current_time(),
            'vccr_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('vaccine_route', 'vccr_id', $vccr_id, $data);
        $this->session->set_flashdata('success', 'Vaccine route deleted successfully.');
        redirect('vaccine/listVaccineRoute');
    }

    // Get Vaccine Route Names By AJAX
    function getVaccineRouteNamesByJason()
    {
        $vccr_id = $this->input->get('vccr_id');
        $data['routeNamesById'] = $this->vaccine_model->getVaccineRouteByIdAjax($vccr_id);
        echo json_encode($data);
    }






    /* =========================================== Vaccine Purchase =========================================== */
    /* =================== List Vaccine Purchase =================== */
    public function listVaccinePurchase()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['vaccinePurchases'] = $this->vaccine_model->getVaccinePurchase();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_vaccine_purchase', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* =================== List Vaccine Purchase =================== */
    public function addVaccinePurchase()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['vaccines'] = $this->vaccine_model->getVaccine();
        $data['suppliers'] = $this->supplier_model->getData('supplier', 's_status', 1)->result();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('add_vaccine_purchase', $data);
        $this->load->view('home/footer'); // just the header file
    }


    /* ====================== Insert vaccine Purchase ====================== */
    public function insertVaccinePurchase()
    {
        $this->form_validation->set_rules('vp_s_id', 'Supplier', 'trim|required|integer');
        $this->form_validation->set_rules('vp_date', 'Date', 'trim|required');
        $this->form_validation->set_rules('vp_grand_total', 'Grand total', 'trim|required|numeric');
        $this->form_validation->set_rules('vp_vcc_id[]', 'Vaccine line item', 'required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('vaccine/addVaccinePurchase');
        }
        $this->db->trans_start();
        // Summary
        $vp_s_id = $this->input->post('vp_s_id');
        $date = $this->input->post('vp_date');
        $vp_date = date("Y-m-d", strtotime($date));
        $vp_sub_total = $this->input->post('vp_sub_total');
        $vp_grand_discount = $this->input->post('vp_grand_discount');
        $vp_grand_total = $this->input->post('vp_grand_total');
        $vp_description = $this->input->post('vp_description');
        $summaryData = array(
            'vps_s_id' => $vp_s_id,
            'vps_date' => $vp_date,
            'vps_sub_total' => (empty($vp_sub_total) && $vp_sub_total !== '0') ? 0 : $vp_sub_total,
            'vps_grand_discount' => (empty($vp_grand_discount) && $vp_grand_discount !== '0') ? 0 : $vp_grand_discount,
            'vps_grand_total' => (empty($vp_grand_total) && $vp_grand_total !== '0') ? 0 : $vp_grand_total,
            'vps_description' => $vp_description,
            'vps_status' => 1,
            'vps_created_at' => get_current_time(),
            'vps_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $purchaseSummaryId = $this->vaccine_model->insertData('vaccine_purchase_summary', $summaryData);
        // Value
        $vp_vcc_id = $this->input->post('vp_vcc_id');
        $vp_unit_price = $this->input->post('vp_unit_price');
        $vp_quantity = $this->input->post('vp_quantity');
        $vp_discount = $this->input->post('vp_discount');
        $vp_total = $this->input->post('vp_total');
        for ($i = 0; $i < count($vp_unit_price); $i++) {
            $ValueData = array(
                'vpv_vps_id' => $purchaseSummaryId,
                'vpv_vcc_id' => $vp_vcc_id[$i],
                'vpv_unit_price' => $vp_unit_price[$i],
                'vpv_quantity' => $vp_quantity[$i],
                'vpv_discount' => (empty($vp_discount[$i]) && $vp_discount[$i] !== '0') ? 0 : $vp_discount[$i],
                'vpv_total' => $vp_total[$i],
                'vpv_status' => 1,
                'vpv_created_at' => get_current_time(),
                'vpv_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->vaccine_model->insertData('vaccine_purchase_value', $ValueData);
        }


        // Purchase Payment
        $sp_payment_amount = $this->input->post('sp_payment_amount');
        $sp_paid_by = $this->input->post('sp_paid_by');
        $sp_cheque_no = $this->input->post('sp_cheque_no');
        $sp_reference = $this->input->post('sp_reference');
        $sp_description = $this->input->post('sp_description');
        if (!empty($purchaseSummaryId) && $sp_payment_amount > 0) {
            $vaccinePurchasePaymentData = array(
                'sp_s_id' =>  $vp_s_id,
                'sp_vps_id' =>  $purchaseSummaryId,
                'sp_payment_amount' =>  $sp_payment_amount,
                'sp_paid_by' =>  $sp_paid_by,
                'sp_cheque_no' =>  $sp_cheque_no,
                'sp_reference' =>  $sp_reference,
                'sp_date' =>  $vp_date,
                'sp_description' =>  $sp_description,
                'sp_status' => 1,
                'sp_purchase_type' => 3,
                'sp_created_at' => get_current_time(),
                'sp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('supplier_payment', $vaccinePurchasePaymentData);
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Vaccine purchase added successfully.');
        redirect("vaccine/listVaccinePurchase");
    }

    /* ====================== List vaccine Purchase ====================== */
    public function viewVaccinePurchase()
    {
        $id = $this->input->get('vps_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['vaccinePurchaseById'] = $this->vaccine_model->getVaccinePurchaseSummaryById($id);
        $data['vaccinePurchaseValueBySummaryById'] = $this->vaccine_model->getVaccinePurchaseValueBySummaryId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_vaccine_purchase', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== Edit vaccine Purchase ====================== */
    public function editVaccinePurchase()
    {
        $id = $this->input->get('vps_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['vaccines'] = $this->vaccine_model->getVaccine();
        $data['vaccinePurchaseById'] = $this->vaccine_model->getVaccinePurchaseSummaryById($id);
        $data['suppliers'] = $this->supplier_model->getData('supplier', 's_status', 1)->result();
        $data['vaccinePurchaseValueBySummaryById'] = $this->vaccine_model->getVaccinePurchaseValueBySummaryId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('edit_vaccine_purchase', $data);
        $this->load->view('home/footer'); // just the header file
    }


    /* ====================== Update vaccine Purchase ====================== */
    public function updateVaccinePurchase()
    {
        $this->db->trans_start();
        // Delete Previous Data
        $vps_id = $this->input->post('vps_id');
        // Delete Value
        $updateValueData = array(
            'vpv_status' => 0,
            'vpv_updated_at' => get_current_time(),
            'vpv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('vaccine_purchase_value', 'vpv_vps_id', $vps_id, $updateValueData);
        // Delete Previous Data

        // Summary
        $vp_s_id = $this->input->post('vp_s_id');
        $date = $this->input->post('vp_date');
        $vp_date = date("Y-m-d", strtotime($date));
        $vp_sub_total = $this->input->post('vp_sub_total');
        $vp_grand_discount = $this->input->post('vp_grand_discount');
        $vp_grand_total = $this->input->post('vp_grand_total');
        $vp_description = $this->input->post('vp_description');
        $updateSummaryData = array(
            'vps_s_id' => $vp_s_id,
            'vps_date' => $vp_date,
            'vps_sub_total' => (empty($vp_sub_total) && $vp_sub_total !== '0') ? 0 : $vp_sub_total,
            'vps_grand_discount' => (empty($vp_grand_discount) && $vp_grand_discount !== '0') ? 0 : $vp_grand_discount,
            'vps_grand_total' => (empty($vp_grand_total) && $vp_grand_total !== '0') ? 0 : $vp_grand_total,
            'vps_description' => $vp_description,
            'vps_updated_at' => get_current_time(),
            'vps_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('vaccine_purchase_summary', 'vps_id', $vps_id, $updateSummaryData);
        // Value
        $vp_vcc_id = $this->input->post('vp_vcc_id');
        $vp_unit_price = $this->input->post('vp_unit_price');
        $vp_quantity = $this->input->post('vp_quantity');
        $vp_discount = $this->input->post('vp_discount');
        $vp_total = $this->input->post('vp_total');
        for ($i = 0; $i < count($vp_unit_price); $i++) {
            $ValueData = array(
                'vpv_vps_id' => $vps_id,
                'vpv_vcc_id' => $vp_vcc_id[$i],
                'vpv_unit_price' => $vp_unit_price[$i],
                'vpv_quantity' => $vp_quantity[$i],
                'vpv_discount' => (empty($vp_discount[$i]) && $vp_discount[$i] !== '0') ? 0 : $vp_discount[$i],
                'vpv_total' => $vp_total[$i],
                'vpv_status' => 1,
                'vpv_created_at' => get_current_time(),
                'vpv_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->vaccine_model->insertData('vaccine_purchase_value', $ValueData);
        }


        // If there is any previous payment on this purchase then payments will be deleted. You can add new payment
        $deletePreviousPayments = array(
            'sp_status' => 0,
            'sp_updated_at' => get_current_time(),
            'sp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->payments_model->updateData('supplier_payment', 'sp_vps_id', $vps_id, $deletePreviousPayments);


        // Insert New Purchase Payment
        $sp_payment_amount = $this->input->post('sp_payment_amount');
        $sp_paid_by = $this->input->post('sp_paid_by');
        $sp_cheque_no = $this->input->post('sp_cheque_no');
        $sp_reference = $this->input->post('sp_reference');
        $sp_description = $this->input->post('sp_description');
        if (!empty($vps_id) &&  $sp_payment_amount > 0) {
            $vaccinePurchasePaymentData = array(
                'sp_s_id' =>  $vp_s_id,
                'sp_vps_id' =>  $vps_id,
                'sp_payment_amount' =>  $sp_payment_amount,
                'sp_paid_by' =>  $sp_paid_by,
                'sp_cheque_no' =>  $sp_cheque_no,
                'sp_reference' =>  $sp_reference,
                'sp_date' =>  $vp_date,
                'sp_description' =>  $sp_description,
                'sp_status' => 1,
                'sp_purchase_type' => 3,
                'sp_created_at' => get_current_time(),
                'sp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->payments_model->insertData('supplier_payment', $vaccinePurchasePaymentData);
        }

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Vaccine purchase updated successfully.');
        redirect("vaccine/listVaccinePurchase");
    }



    /* ====================== Delete vaccine Purchase ====================== */
    function deleteVaccinePurchase()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        $vps_id = $this->input->post('vps_id');
        // Delete Summary
        $updateSummaryData = array(
            'vps_status' => 0,
            'vps_updated_at' => get_current_time(),
            'vps_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('vaccine_purchase_summary', 'vps_id', $vps_id, $updateSummaryData);
        // Delete Value
        $updateValueData = array(
            'vpv_status' => 0,
            'vpv_updated_at' => get_current_time(),
            'vpv_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('vaccine_purchase_value', 'vpv_vps_id', $vps_id, $updateValueData);

        // delete Payments Delete under this invoice
        $deletePayments = array(
            'sp_status' => 0,
            'sp_updated_at' => get_current_time(),
            'sp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('supplier_payment', 'sp_vps_id', $vps_id, $deletePayments);

        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Vaccine purchase deleted successfully.');
        redirect('vaccine/listVaccinePurchase');
    }



    /* ====================== Used Vaccine calculation ====================== */
    /* ==================== Insert & Edit Used Vaccine ==================== */
    public function updateUsedVaccine()
    {
        $vds_id = $this->input->post('vds_id');
        $vds_vaccine_used_quantity = $this->input->post('vds_vaccine_used_quantity');
        $vds_description = $this->input->post('vds_description');
        $updateData = array(
            'vds_vaccine_used_quantity' => $vds_vaccine_used_quantity,
            'vds_description' => $vds_description,
            'vds_updated_at' => get_current_time(),
            'vds_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('vaccine_dose_status', 'vds_id', $vds_id, $updateData);
        $this->session->set_flashdata('success', 'Used vaccine updated successfully.');
        redirect('vaccine/listUsedVaccine');
    }

    /* ==================== Delete Used Vaccine ==================== */
    public function listUsedVaccine()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['usedVaccines'] = $this->vaccine_model->getUsedVaccineFromDose();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_used_vaccine', $data);
        $this->load->view('home/footer'); // just the header file
    }



    // get vaccine in stock
    function getVaccineStockByJason()
    {
        $vcc_id = $this->input->get('vcc_id');
        $data['vaccineWiseStock'] =  $this->vaccine_model->getVaccineWiseVaccinePurchaseQuantity($vcc_id, 'vpv_quantity') - $this->vaccine_model->getVaccineWiseVaccineUsedQuantity($vcc_id, 'vccu_used_quantity');
        echo json_encode($data);
    }




    /* ====================== Vaccine Wasted Module ====================== */
    /* ====================== Vaccine Wasted Vaccine ====================== */
    public function insertWastedVaccine()
    {
        // Summary
        $vccw_vcc_id = $this->input->post('vccw_vcc_id');
        $vccw_quantity = $this->input->post('vccw_quantity');
        $vccw_description = $this->input->post('vccw_description');
        $data = array(
            'vccw_vcc_id' => $vccw_vcc_id,
            'vccw_quantity' => $vccw_quantity,
            'vccw_description' => $vccw_description,
            'vccw_status' => 1,
            'vccw_created_at' => get_current_time(),
            'vccw_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->insertData('vaccine_wasted', $data);
        $this->session->set_flashdata('success', 'Wasted vaccine recorded successfully.');
        redirect("vaccine");
    }
    /* ====================== add wasted Vaccine report ====================== */
    public function addWastedVaccineReport()
    {
        $id = $this->input->get('vcc_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['vaccineById'] = $this->vaccine_model->getVaccineById($id);
        $data['wastedVaccineValues'] = $this->vaccine_model->getVaccineWastedValuesByVaccineId($id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('add_wasted_vaccine_report', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== update wasted Vaccine ====================== */
    public function updateWastedVaccine()
    {
        $vccw_vcc_id = $this->input->post('vccw_vcc_id');
        $vccw_id = $this->input->post('vccw_id');
        $vccw_quantity = $this->input->post('vccw_quantity');
        $vccw_description = $this->input->post('vccw_description');
        $ValueDataUpdate = array(
            'vccw_vcc_id' => $vccw_vcc_id,
            'vccw_quantity' => $vccw_quantity,
            'vccw_description' => $vccw_description,
            'vccw_updated_at' => get_current_time(),
            'vccw_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('vaccine_wasted', 'vccw_id', $vccw_id, $ValueDataUpdate);
        $this->session->set_flashdata('success', 'Wasted vaccine updated successfully.');
        redirect("vaccine/addWastedVaccineReport?vcc_id=$vccw_vcc_id");
    }


    public function deleteWastedVaccine()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $vccw_vcc_id = $this->input->post('vccw_vcc_id');
        $vccw_id = $this->input->post('vccw_id');
        $ValueDataUpdate = array(
            'vccw_status' => 0,
            'vccw_updated_at' => get_current_time(),
            'vccw_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->vaccine_model->updateData('vaccine_wasted', 'vccw_id', $vccw_id, $ValueDataUpdate);
        $this->session->set_flashdata('success', 'Wasted vaccine deleted successfully.');
        redirect("vaccine/addWastedVaccineReport?vcc_id=$vccw_vcc_id");
    }
}

/* End of file vaccine.php */
/* Location: ./application/modules/vaccine/controllers/vaccine.php */
