<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expense extends MY_Controller
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
        $this->load->model('report/report_model');
        $data['settings'] = $this->settings_model->getSettings();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin', 'Accountant'))) {
            redirect('home/permission');
        }
    }


    /* =========================================== Expense category =========================================== */
    /* ========== List data ========== */
    public function listExpenseCategory()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['expenseCategories'] = $this->expense_model->getExpenseCategory();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_expense_category', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ========== Insert and update data ========== */
    public function insertExpenseCategory()
    {
        $exc_id = $this->input->post('exc_id');
        $name = $this->input->post('exc_name');
        $description = $this->input->post('exc_description');
        if (empty($exc_id)) {
            $data = array(
                'exc_name' => $name,
                'exc_description' => $description,
                'exc_status' => 1,
                'exc_created_at' => get_current_time(),
                'exc_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->expense_model->insertData('expense_category', $data);
            $this->session->set_flashdata('success', 'Expense category added successfully.');
            redirect('expense/listExpenseCategory');
        } else {
            $updateData = array(
                'exc_name' => $name,
                'exc_description' => $description,
                'exc_updated_at' => get_current_time(),
                'exc_updated_by' => $this->ion_auth->user()->row()->user_id
            );
            if (!empty($exc_id)) {
                $this->expense_model->updateData('expense_category', 'exc_id', $exc_id, $updateData);
            }
            $this->session->set_flashdata('success', 'Expense category updated successfully.');
            redirect('expense/listExpenseCategory');
        }
    }

    /* ========== Update data by json ========== */
    function editExpenseCategoryByJason()
    {
        $id = $this->input->get('exc_id');
        $data['categories'] = $this->expense_model->getExpenseCategoryById($id);
        echo json_encode($data);
    }

    /* ========== delete data ========== */
    function deleteExpenseCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $exc_id = $this->input->post('exc_id');
        $data = array(
            'exc_status' => 0,
            'exc_updated_at' => get_current_time(),
            'exc_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->expense_model->updateData('expense_category', 'exc_id', $exc_id, $data);
        $this->session->set_flashdata('success', 'Expense category deleted successfully.');
        redirect('expense/listExpenseCategory');
    }

    /* =========================================== Expense =========================================== */
    /* ========== List data ========== */
    public function listExpense()
    {
        $from = $this->_validDate($this->input->get('from'));
        $to   = $this->_validDate($this->input->get('to'));
        $data['settings'] = $this->settings_model->getSettings();
        $data['expenses'] = $this->expense_model->getExpense($from, $to);
        $data['expenseCategories'] = $this->expense_model->getExpenseCategory();
        $data['filter_from'] = $from;
        $data['filter_to']   = $to;
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_expense', $data);
        $this->load->view('home/footer'); // just the header file
    }

    private function _validDate($raw)
    {
        $raw = (string) $raw;
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw) ? $raw : '';
    }

    /* ========== Insert data ========== */
    public function insertExpense()
    {
        $this->form_validation->set_rules('ex_name', 'Expense name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('ex_exc_id', 'Category', 'trim|required|integer');
        $this->form_validation->set_rules('ex_date', 'Date', 'trim|required');
        $this->form_validation->set_rules('ex_amount', 'Amount', 'trim|required|numeric|greater_than[0]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('expense/listExpense');
        }
        $this->db->trans_start();
        $ex_id = $this->input->post('ex_id');
        $ex_expense_id = $this->input->post('ex_expense_id');
        $ex_name = $this->input->post('ex_name');
        $ex_exc_id = $this->input->post('ex_exc_id');
        $date = $this->input->post('ex_date');
        $ex_date = date("Y-m-d", strtotime($date));
        $ex_amount = $this->input->post('ex_amount');
        $ex_description = $this->input->post('ex_description');

        $exp_paid_amount = $this->input->post('exp_paid_amount');
        $exp_paid_by = $this->input->post('exp_paid_by');
        $exp_cheque_no = $this->input->post('exp_cheque_no');

        if (empty($ex_id)) {
            $data = array(
                'ex_expense_id' => $ex_expense_id,
                'ex_name' => $ex_name,
                'ex_exc_id' => $ex_exc_id,
                'ex_date' => $ex_date,
                'ex_amount' => $ex_amount,
                'ex_description' => $ex_description,
                'ex_status' => 1,
                'ex_created_at' => get_current_time(),
                'ex_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $expenseId = $this->expense_model->insertData('expense', $data);

            if ($exp_paid_amount > 0) {
                $data = array(
                    'exp_ex_id' => $expenseId,
                    'exp_date' => $ex_date,
                    'exp_paid_amount' => $exp_paid_amount,
                    'exp_paid_by' => $exp_paid_by,
                    'exp_cheque_no' => $exp_cheque_no,
                    'exp_status' => 1,
                    'exp_created_at' => get_current_time(),
                    'exp_created_by' => $this->ion_auth->user()->row()->user_id
                );
                $expenseId = $this->expense_model->insertData('expense_payment', $data);
            }
            $this->db->trans_complete();
            $this->session->set_flashdata('success', 'Expense added successfully.');
            redirect('expense/listExpense');
        } else {
            $updateData = array(
                'ex_expense_id' => $ex_expense_id,
                'ex_name' => $ex_name,
                'ex_exc_id' => $ex_exc_id,
                'ex_date' => $ex_date,
                'ex_amount' => $ex_amount,
                'ex_description' => $ex_description,
                'ex_updated_at' => get_current_time(),
                'ex_updated_by' => $this->ion_auth->user()->row()->user_id
            );
            if (!empty($ex_id)) {
                $this->expense_model->updateData('expense', 'ex_id', $ex_id, $updateData);
            }
            $this->db->trans_complete();
            $this->session->set_flashdata('success', 'Expense updated successfully.');
            redirect('expense/listExpense');
        }
    }

    /* ========== Update data by json ========== */
    function editExpenseByJason()
    {
        $id = $this->input->get('ex_id');
        $data['expensesById'] = $this->expense_model->getExpenseById($id);
        echo json_encode($data);
    }

    /* ========== delete data ========== */
    function deleteExpense()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $this->db->trans_start();
        $ex_id = $this->input->post('ex_id');
        $data = array(
            'ex_status' => 0,
            'ex_updated_at' => get_current_time(),
            'ex_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->expense_model->updateData('expense', 'ex_id', $ex_id, $data);

        // Delete payments
        $data = array(
            'exp_status' => 0,
            'exp_updated_at' => get_current_time(),
            'exp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->expense_model->updateData('expense_payment', 'exp_ex_id', $ex_id, $data);
        $this->db->trans_complete();
        $this->session->set_flashdata('success', 'Expense deleted successfully.');
        redirect('expense/listExpense');
    }


    /* ===================================== Expense Payments =================================== */
    /* ========== View data ========== */
    public function viewExpensePayments()
    {
        $ex_id = $this->input->get('ex_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['expenseById'] = $this->expense_model->getExpenseById($ex_id);
        $data['paymentsByExpenseId'] = $this->expense_model->getExpensePaymentsByExpenseId($ex_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_expense_wise_payments', $data);
        $this->load->view('home/footer'); // just the header file
    }


    /* ========== Insert and update data ========== */
    public function insertExpensePayments()
    {
        $expense_id = $this->input->post('exp_ex_id');
        $exp_id = $this->input->post('exp_id');
        $date = $this->input->post('exp_date');
        $exp_date = date("Y-m-d", strtotime($date));
        $exp_paid_amount = $this->input->post('exp_paid_amount');
        $exp_paid_by = $this->input->post('exp_paid_by');
        $exp_cheque_no = $this->input->post('exp_cheque_no');
        $exp_description = $this->input->post('exp_description');
        if (empty($exp_id)) {
            $data = array(
                'exp_ex_id' => $expense_id,
                'exp_date' => $exp_date,
                'exp_paid_amount' => $exp_paid_amount,
                'exp_paid_by' => $exp_paid_by,
                'exp_cheque_no' => $exp_cheque_no,
                'exp_description' => $exp_description,
                'exp_status' => 1,
                'exp_created_at' => get_current_time(),
                'exp_created_by' => $this->ion_auth->user()->row()->user_id
            );
            $this->expense_model->insertData('expense_payment', $data);
            $this->session->set_flashdata('success', 'Expense payment added successfully.');
            redirect("expense/viewExpensePayments?ex_id=$expense_id");
        } else {
            $updateData = array(
                'exp_date' => $exp_date,
                'exp_paid_amount' => $exp_paid_amount,
                'exp_paid_by' => $exp_paid_by,
                'exp_cheque_no' => $exp_cheque_no,
                'exp_description' => $exp_description,
                'exp_updated_at' => get_current_time(),
                'exp_updated_by' => $this->ion_auth->user()->row()->user_id
            );
            if (!empty($exp_id)) {
                $this->expense_model->updateData('expense_payment', 'exp_id', $exp_id, $updateData);
            }
            $this->session->set_flashdata('success', 'Expense payment updated successfully.');
            redirect("expense/viewExpensePayments?ex_id=$expense_id");
        }
    }

    /* ========== delete data ========== */
    function deleteExpensePayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $exp_id = $this->input->post('exp_id');
        $exp_ex_id = $this->input->post('ex_id');
        $data = array(
            'exp_status' => 0,
            'exp_updated_at' => get_current_time(),
            'exp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->expense_model->updateData('expense_payment', 'exp_id', $exp_id, $data);
        $this->session->set_flashdata('success', 'Expense payment deleted successfully.');
        redirect("expense/viewExpensePayments?ex_id=$exp_ex_id");
    }

    // End
}

/* End of file expense.php */
/* Location: ./application/modules/expense/controllers/expense.php */