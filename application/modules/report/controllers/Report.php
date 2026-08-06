<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report extends MY_Controller
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
        $this->load->model('report_model');
        $this->load->model('food/food_model');
        $this->load->model('shed/shed_model');
        $this->load->model('purchase/purchase_model');
        $this->load->model('vaccine/vaccine_model');
        $this->load->model('product/product_model');
        $this->load->model('livestock/livestock_model');
        $this->load->model('sale/sale_model');
        $this->load->model('supplier/supplier_model');
        $this->load->model('client/client_model');
        $this->load->model('staff/staff_model');
        $this->load->model('payments/payments_model');
        $this->load->model('expense/expense_model');
        $this->load->model('settings/settings_model');
        $data['settings'] = $this->settings_model->getSettings();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin', 'Accountant'))) {
            redirect('home/permission');
        }
    }

    /* ======================= Purchase Report ======================= */
    public function viewLivestockPurchaseReport()
    {
        $data['settings'] = $this->settings_model->getSettings();
        // Filter by year
        $year = $this->input->post('year_value');
        if ($year == "") {
            $data['year_value'] = date('Y');
        } else {
            $data['year_value'] = $year;
        }

        // Filter by start date and end date
        $start_date = $this->input->post('start_date');
        $data['start_date_value'] = $start_date;
        $data['start_date_value_format'] = date("Y-m-d", strtotime($start_date));

        $end_date = $this->input->post('end_date');
        $data['end_date_value'] = $end_date;
        $data['end_date_value_format'] = date("Y-m-d", strtotime($end_date));

        // Month Wise Filtering
        $yearValue = $this->input->post('year');
        $monthValue = $this->input->post('month');
        $data['yearMonth_wise_filer_year'] = $yearValue;
        $data['yearMonth_wise_filer_month'] = $monthValue;

        $data['livestocks'] = $this->livestock_model->getLivestock();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_livestock_purchase_report', $data);
        $this->load->view('home/footer'); // just the footer file
    }

    /* ======================= Sale Report ======================= */
    public function viewLivestockSaleReport()
    {
        $data['settings'] = $this->settings_model->getSettings();
        // Filter by year
        $year = $this->input->post('year_value');
        if ($year == "") {
            $data['year_value'] = date('Y');
        } else {
            $data['year_value'] = $year;
        }

        // Filter by start date and end date
        $start_date = $this->input->post('start_date');
        $data['start_date_value'] = $start_date;
        $data['start_date_value_format'] = date("Y-m-d", strtotime($start_date));

        $end_date = $this->input->post('end_date');
        $data['end_date_value'] = $end_date;
        $data['end_date_value_format'] = date("Y-m-d", strtotime($end_date));

        // Month Wise Filtering
        $yearValue = $this->input->post('year');
        $monthValue = $this->input->post('month');
        $data['yearMonth_wise_filer_year'] = $yearValue;
        $data['yearMonth_wise_filer_month'] = $monthValue;

        // Shed and batch
        $shed_id = $this->input->post('shed_id');
        $batch_id = $this->input->post('batch_id');
        $data['shed_id'] = $shed_id;
        $data['batch_id'] = $batch_id;

        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['sheds'] = $this->shed_model->getShed();
        $data['batches'] = $this->shed_model->getBatch();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_livestock_sale_report', $data);
        $this->load->view('home/footer'); // just the footer file
    }

    /* ======================= Death Report ======================= */
    public function viewLivestockDeathReport()
    {
        $data['settings'] = $this->settings_model->getSettings();
        // Filter by year
        $year = $this->input->post('year_value');
        if ($year == "") {
            $data['year_value'] = date('Y');
        } else {
            $data['year_value'] = $year;
        }

        // Filter by start date and end date
        $start_date = $this->input->post('start_date');
        $data['start_date_value'] = $start_date;
        $data['start_date_value_format'] = date("Y-m-d", strtotime($start_date));

        $end_date = $this->input->post('end_date');
        $data['end_date_value'] = $end_date;
        $data['end_date_value_format'] = date("Y-m-d", strtotime($end_date));

        // Month Wise Filtering
        $yearValue = $this->input->post('year');
        $monthValue = $this->input->post('month');
        $data['yearMonth_wise_filer_year'] = $yearValue;
        $data['yearMonth_wise_filer_month'] = $monthValue;

        $data['livestocks'] = $this->livestock_model->getLivestock();

        // Shed and batch
        $shed_id = $this->input->post('shed_id');
        $batch_id = $this->input->post('batch_id');
        $data['shed_id'] = $shed_id;
        $data['batch_id'] = $batch_id;

        $data['sheds'] = $this->shed_model->getShed();
        $data['batches'] = $this->shed_model->getBatch();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_livestock_death_report', $data);
        $this->load->view('home/footer'); // just the footer file
    }

    /* ======================= Livestock Stock Report ======================= */
    public function viewLivestockStockReport()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_livestock_stock_report', $data);
        $this->load->view('home/footer'); // just the footer file
    }

    /* ====================== Food Stock Report ====================== */
    public function viewFoodStockReport()
    {
        $data['settings'] = $this->settings_model->getSettings();

        // Filter by year
        $year = $this->input->post('year_value');
        if ($year == "") {
            $data['year_value'] = date('Y');
        } else {
            $data['year_value'] = $year;
        }

        // Filter by start date and end date
        $start_date = $this->input->post('start_date');
        $data['start_date_value'] = $start_date;
        $data['start_date_value_format'] = date("Y-m-d", strtotime($start_date));

        $end_date = $this->input->post('end_date');
        $data['end_date_value'] = $end_date;
        $data['end_date_value_format'] = date("Y-m-d", strtotime($end_date));

        // Month Wise Filtering
        $yearValue = $this->input->post('year');
        $monthValue = $this->input->post('month');
        $data['yearMonth_wise_filer_year'] = $yearValue;
        $data['yearMonth_wise_filer_month'] = $monthValue;

        $data['units'] = $this->settings_model->getUnit();
        $data['foods'] = $this->food_model->getFood();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_food_stock_report', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== Vaccine Stock Report ====================== */
    public function viewVaccineStockReport()
    {
        $data['settings'] = $this->settings_model->getSettings();

        // Filter by year
        $year = $this->input->post('year_value');
        if ($year == "") {
            $data['year_value'] = date('Y');
        } else {
            $data['year_value'] = $year;
        }

        // Filter by start date and end date
        $start_date = $this->input->post('start_date');
        $data['start_date_value'] = $start_date;
        $data['start_date_value_format'] = date("Y-m-d", strtotime($start_date));

        $end_date = $this->input->post('end_date');
        $data['end_date_value'] = $end_date;
        $data['end_date_value_format'] = date("Y-m-d", strtotime($end_date));

        // Month Wise Filtering
        $yearValue = $this->input->post('year');
        $monthValue = $this->input->post('month');
        $data['yearMonth_wise_filer_year'] = $yearValue;
        $data['yearMonth_wise_filer_month'] = $monthValue;

        $data['units'] = $this->settings_model->getUnit();
        $data['vaccines'] = $this->vaccine_model->getVaccine();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_vaccine_stock_report', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== Product Stock Report ====================== */
    public function viewProductStockReport()
    {
        $data['settings'] = $this->settings_model->getSettings();

        // Filter by year
        $year = $this->input->post('year_value');
        if ($year == "") {
            $data['year_value'] = date('Y');
        } else {
            $data['year_value'] = $year;
        }

        // Filter by start date and end date
        $start_date = $this->input->post('start_date');
        $data['start_date_value'] = $start_date;
        $data['start_date_value_format'] = date("Y-m-d", strtotime($start_date));

        $end_date = $this->input->post('end_date');
        $data['end_date_value'] = $end_date;
        $data['end_date_value_format'] = date("Y-m-d", strtotime($end_date));

        // Month Wise Filtering
        $yearValue = $this->input->post('year');
        $monthValue = $this->input->post('month');
        $data['yearMonth_wise_filer_year'] = $yearValue;
        $data['yearMonth_wise_filer_month'] = $monthValue;

        $data['vaccines'] = $this->vaccine_model->getVaccine();

        $data['products'] = $this->product_model->getProduct();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_product_report', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== Supplier Report ====================== */
    public function viewSupplierReport()
    {
        $data['settings'] = $this->settings_model->getSettings();

        // Filter by year
        $year = $this->input->post('year_value');
        if ($year == "") {
            $data['year_value'] = date('Y');
        } else {
            $data['year_value'] = $year;
        }

        // Filter by start date and end date
        $start_date = $this->input->post('start_date');
        $data['start_date_value'] = $start_date;
        $data['start_date_value_format'] = date("Y-m-d", strtotime($start_date));

        $end_date = $this->input->post('end_date');
        $data['end_date_value'] = $end_date;
        $data['end_date_value_format'] = date("Y-m-d", strtotime($end_date));

        // Month Wise Filtering
        $yearValue = $this->input->post('year');
        $monthValue = $this->input->post('month');
        $data['yearMonth_wise_filer_year'] = $yearValue;
        $data['yearMonth_wise_filer_month'] = $monthValue;

        $data['suppliers'] = $this->supplier_model->getData('supplier', 's_status', 1);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_supplier_report', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== Client Report ====================== */
    public function viewClientReport()
    {
        $data['settings'] = $this->settings_model->getSettings();

        // Filter by year
        $year = $this->input->post('year_value');
        if ($year == "") {
            $data['year_value'] = date('Y');
        } else {
            $data['year_value'] = $year;
        }

        // Filter by start date and end date
        $start_date = $this->input->post('start_date');
        $data['start_date_value'] = $start_date;
        $data['start_date_value_format'] = date("Y-m-d", strtotime($start_date));

        $end_date = $this->input->post('end_date');
        $data['end_date_value'] = $end_date;
        $data['end_date_value_format'] = date("Y-m-d", strtotime($end_date));

        // Month Wise Filtering
        $yearValue = $this->input->post('year');
        $monthValue = $this->input->post('month');
        $data['yearMonth_wise_filer_year'] = $yearValue;
        $data['yearMonth_wise_filer_month'] = $monthValue;


        $data['clients'] = $this->client_model->getClient();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_client_report', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== Staff Report ====================== */
    public function viewStaffReport()
    {
        $data['settings'] = $this->settings_model->getSettings();

        // Filter by year
        $year = $this->input->post('year_value');
        if ($year == "") {
            $data['year_value'] = date('Y');
        } else {
            $data['year_value'] = $year;
        }

        // Filter by start date and end date
        $start_date = $this->input->post('start_date');
        $data['start_date_value'] = $start_date;
        $data['start_date_value_format'] = date("Y-m-d", strtotime($start_date));

        $end_date = $this->input->post('end_date');
        $data['end_date_value'] = $end_date;
        $data['end_date_value_format'] = date("Y-m-d", strtotime($end_date));

        // Month Wise Filtering
        $yearValue = $this->input->post('year');
        $monthValue = $this->input->post('month');
        $data['yearMonth_wise_filer_year'] = $yearValue;
        $data['yearMonth_wise_filer_month'] = $monthValue;

        $data['staffs'] = $this->staff_model->getStaff();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_staff_report', $data);
        $this->load->view('home/footer'); // just the header file
    }


    /* ====================== Expense Report ====================== */
    public function viewExpenseReport()
    {
        $data['settings'] = $this->settings_model->getSettings();

        // Filter by year
        $year = $this->input->post('year_value');
        if ($year == "") {
            $data['year_value'] = date('Y');
        } else {
            $data['year_value'] = $year;
        }

        // Filter by start date and end date
        $start_date = $this->input->post('start_date');
        $data['start_date_value'] = $start_date;
        $data['start_date_value_format'] = date("Y-m-d", strtotime($start_date));

        $end_date = $this->input->post('end_date');
        $data['end_date_value'] = $end_date;
        $data['end_date_value_format'] = date("Y-m-d", strtotime($end_date));

        // Month Wise Filtering
        $yearValue = $this->input->post('year');
        $monthValue = $this->input->post('month');
        $data['yearMonth_wise_filer_year'] = $yearValue;
        $data['yearMonth_wise_filer_month'] = $monthValue;

        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_expense_report', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== Expense Category Report ====================== */
    public function viewExpenseCategoryReport()
    {
        $data['settings'] = $this->settings_model->getSettings();

        // Filter by year
        $year = $this->input->post('year_value');
        if ($year == "") {
            $data['year_value'] = date('Y');
        } else {
            $data['year_value'] = $year;
        }

        // Filter by start date and end date
        $start_date = $this->input->post('start_date');
        $data['start_date_value'] = $start_date;
        $data['start_date_value_format'] = date("Y-m-d", strtotime($start_date));

        $end_date = $this->input->post('end_date');
        $data['end_date_value'] = $end_date;
        $data['end_date_value_format'] = date("Y-m-d", strtotime($end_date));

        // Month Wise Filtering
        $yearValue = $this->input->post('year');
        $monthValue = $this->input->post('month');
        $data['yearMonth_wise_filer_year'] = $yearValue;
        $data['yearMonth_wise_filer_month'] = $monthValue;


        $data['expenseCategories'] = $this->expense_model->getExpenseCategory();

        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_expense_category_report', $data);
        $this->load->view('home/footer'); // just the header file
    }


    /* ====================== Batch Analysis Report ====================== */
    public function viewBatchAnalysisReport()
    {
        $data['settings'] = $this->settings_model->getSettings();
        // Shed & batch
        $shed_id = $this->input->post('shed_id');
        $batch_id = $this->input->post('batch_id');
        $data['shed_id'] = $shed_id;
        $data['batch_id'] = $batch_id;
        // x_debug($batch_id);
        // Get lives information
        $live_assigned_summary_id = $this->purchase_model->getAssignedSummaryDataByShedAndBatchId($shed_id, $batch_id);
        if ($live_assigned_summary_id) {
            $live_assigned_summary_id = $live_assigned_summary_id->lshs_id;
        }
        if (isset($live_assigned_summary_id)) {
            $live_id = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($live_assigned_summary_id);
            if ($live_id) {
                $live_id = $live_id->lsh_purv_ls_id;
            }
            $data['LivestockInfo'] = $this->livestock_model->getLivestockById($live_id);
        } else {
            $data['LivestockInfo'] = "";
        }

        if (isset($live_assigned_summary_id)) {
            $live_type_id = $this->purchase_model->getAssignedLivestockValueDataBySummaryId($live_assigned_summary_id);
            if ($live_type_id) {
                $live_type_id = $live_type_id->lsh_purv_lst_id;
            }
            $data['LivestockTypeInfo'] = $this->livestock_model->getLivestockTypeById($live_type_id);
        } else {
            $data['LivestockTypeInfo'] = "";
        }
        // Sold Value
        $data['livestockSoldValueByShedBatch'] = $this->report_model->getLivestockSoldValueByShedAndBatchId($shed_id, $batch_id);
        // Food List
        $data['assignedFoodList'] = $this->report_model->getAssignedFoodByShedAndBatch($shed_id, $batch_id);
        // vaccine 
        $data['vaccines'] = $this->vaccine_model->getUsedVaccineByShedAndBatch($shed_id, $batch_id);
        // Product
        $data['assignedProducts'] = $this->product_model->getAssignedProductsShedBatchId($shed_id, $batch_id);
        // Assigned Live
        $data['assignedLiveStocks'] = $this->purchase_model->getAssignedLivestockAndTypesBySummaryId($live_assigned_summary_id);
        // Reproduction and assign to another shed/batch
        $data['livestockReproduction'] = $this->report_model->getData('livestock_reproduction', ['lrp_sh_id' => $shed_id, 'lrp_batch_id' => $batch_id, 'lrp_status' => 1])->result();

        $data['sheds'] = $this->shed_model->getShed();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_batch_report', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ====================== Shed Analysis Report ====================== */
    public function viewShedAnalysisReport()
    {
        $data['settings'] = $this->settings_model->getSettings();
        // Shed & batch
        $shed_id = $this->input->post('shed_id');
        if ($shed_id) {
            $data['shed_id'] = $shed_id;
        } else {
            $data['shed_id'] = "";
        }
        // Get lives information
        $data['liveAssignedSummaryData'] = $this->purchase_model->getAssignedSummaryDataByShedId($shed_id);
        $data['totalBatch'] = count($data['liveAssignedSummaryData']);
        // x_debug($data['liveAssignedSummaryData']);

        $data['liveAssignedValueData'] = $this->purchase_model->getAssignedLivestockShedId($shed_id);
        // Sold Value
        $data['livestockSoldValueByShed'] = $this->report_model->getLivestockSoldValueByShedId($shed_id);
        // Food List
        $data['assignedFoodList'] = $this->report_model->getAssignedFoodByShed($shed_id);
        // vaccine 
        $data['vaccines'] = $this->vaccine_model->getUsedVaccineByShed($shed_id);
        // Product
        $data['assignedProducts'] = $this->product_model->getAssignedProductsShedId($shed_id);
        // Reproduction and assign to another shed
        $data['livestockReproduction'] = $this->report_model->getData('livestock_reproduction', ['lrp_sh_id' => $shed_id, 'lrp_status' => 1])->result();

        $data['sheds'] = $this->shed_model->getShed();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_shed_report', $data);
        $this->load->view('home/footer'); // just the header file
    }


    /* ====================== Financial Reports ====================== */
    public function viewFinancialReport()
    {
        // Filter by start date and end date
        $start_date = $this->input->post('start_date');
        $data['start_date_value'] = $start_date;
        $data['start_date_value_format'] = date("Y-m-d", strtotime($start_date));

        $end_date = $this->input->post('end_date');
        $data['end_date_value'] = $end_date;
        $data['end_date_value_format'] = date("Y-m-d", strtotime($end_date));


        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_financial_report', $data);
        $this->load->view('home/footer'); // just the header file
    }











    // End
}
