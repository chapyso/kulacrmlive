<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Finance_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function insertPayment($data) {
        $data = $this->prepare_tenant_data('payment', $data);
        $this->db->insert('payment', $data);
    }

    function getPayment() {
        $this->scope_tenant('payment');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('payment');
        return $query->result();
    }

    function getPaymentById($id) {
        $this->scope_tenant('payment');
        $this->db->where('id', $id);
        $query = $this->db->get('payment');
        return $query->row();
    }

    function getPaymentByPatientId($id) {
        $this->scope_tenant('payment');
        $this->db->order_by('id', 'desc');
        $this->db->where('patient', $id);
        $query = $this->db->get('payment');
        return $query->result();
    }

    function getPaymentByUserId($id) {
        $this->scope_tenant('payment');
        $this->db->order_by('id', 'desc');
        $this->db->where('user', $id);
        $query = $this->db->get('payment');
        return $query->result();
    }

    function getOtPaymentByPatientId($id) {
        $this->scope_tenant('ot_payment');
        $this->db->order_by('id', 'desc');
        $this->db->where('patient', $id);
        $query = $this->db->get('ot_payment');
        return $query->result();
    }

    function getOtPaymentByUserId($id) {
        $this->scope_tenant('ot_payment');
        $this->db->order_by('id', 'desc');
        $this->db->where('user', $id);
        $query = $this->db->get('ot_payment');
        return $query->result();
    }

    function insertDeposit($data) {
        $data = $this->prepare_tenant_data('patient_deposit', $data);
        $this->db->insert('patient_deposit', $data);
    }

    function getDeposit() {
        $this->scope_tenant('patient_deposit');
        $query = $this->db->get('patient_deposit');
        return $query->result();
    }

    function updateDeposit($id, $data) {
        $this->scope_tenant('patient_deposit');
        $this->db->where('id', $id);
        $this->db->update('patient_deposit', $data);
    }

    function getDepositById($id) {
        $this->scope_tenant('patient_deposit');
        $this->db->where('id', $id);
        $query = $this->db->get('patient_deposit');
        return $query->row();
    }

    function getDepositByPatientId($id) {
        $this->scope_tenant('patient_deposit');
        $this->db->order_by('id', 'desc');
        $this->db->where('patient', $id);
        $query = $this->db->get('patient_deposit');
        return $query->result();
    }

    function getDepositByUserId($id) {
        $this->scope_tenant('patient_deposit');
        $this->db->order_by('id', 'desc');
        $this->db->where('user', $id);
        $query = $this->db->get('patient_deposit');
        return $query->result();
    }

    function getDepositByAmountReceivedById($id) {
        $this->scope_tenant('patient_deposit');
        $this->db->order_by('id', 'desc');
        $this->db->where('amount_received_id', $id);
        $query = $this->db->get('patient_deposit');
        return $query->row();
    }

    function deleteDeposit($id) {
        $this->scope_tenant('patient_deposit');
        $this->db->where('id', $id);
        $this->db->delete('patient_deposit');
    }

    function deleteDepositByInvoiceId($id) {
        $this->scope_tenant('patient_deposit');
        $this->db->where('payment_id', $id);
        $this->db->delete('patient_deposit');
    }

    function getPaymentByPatientIdByStatus($id) {
        $this->scope_tenant('payment');
        $this->db->where('patient', $id);
        $this->db->where('status', 'unpaid');
        $query = $this->db->get('payment');
        return $query->result();
    }

    function getOtPaymentByPatientIdByStatus($id) {
        $this->scope_tenant('ot_payment');
        $this->db->where('patient', $id);
        $this->db->where('status', 'unpaid');
        $query = $this->db->get('ot_payment');
        return $query->result();
    }

    function updatePayment($id, $data) {
        $this->scope_tenant('payment');
        $this->db->where('id', $id);
        $this->db->update('payment', $data);
    }

    function insertOtPayment($data) {
        $data = $this->prepare_tenant_data('ot_payment', $data);
        $this->db->insert('ot_payment', $data);
    }

    function getOtPayment() {
        $this->scope_tenant('ot_payment');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('ot_payment');
        return $query->result();
    }

    function getOtPaymentById($id) {
        $this->scope_tenant('ot_payment');
        $this->db->where('id', $id);
        $query = $this->db->get('ot_payment');
        return $query->row();
    }

    function updateOtPayment($id, $data) {
        $this->scope_tenant('ot_payment');
        $this->db->where('id', $id);
        $this->db->update('ot_payment', $data);
    }

    function deleteOtPayment($id) {
        $this->scope_tenant('ot_payment');
        $this->db->where('id', $id);
        $this->db->delete('ot_payment');
    }

    function insertPaymentCategory($data) {
        $data = $this->prepare_tenant_data('payment_category', $data);
        $this->db->insert('payment_category', $data);
    }

    function getPaymentCategory() {
        $this->scope_tenant('payment_category');
        $query = $this->db->get('payment_category');
        return $query->result();
    }

    function getPaymentCategoryById($id) {
        $this->scope_tenant('payment_category');
        $this->db->where('id', $id);
        $query = $this->db->get('payment_category');
        return $query->row();
    }

    function getDoctorCommissionByCategory($data) {
        $this->scope_tenant('payment_category');
        $this->db->where('category', $data);
        $query = $this->db->get('payment_category');
        return $query->row();
    }

    function updatePaymentCategory($id, $data) {
        $this->scope_tenant('payment_category');
        $this->db->where('id', $id);
        $this->db->update('payment_category', $data);
    }

    function deletePayment($id) {
        $this->scope_tenant('payment');
        $this->db->where('id', $id);
        $this->db->delete('payment');
    }

    function deletePaymentCategory($id) {
        $this->scope_tenant('payment_category');
        $this->db->where('id', $id);
        $this->db->delete('payment_category');
    }

    function getDiscountType() {
        $this->scope_tenant('settings');
        $query = $this->db->get('settings');
        return $query->row() ? $query->row()->discount : null;
    }

    function getPaymentByDoctor($doctor) {
        $this->scope_tenant('payment');
        $this->db->select('*');
        $this->db->from('payment');
        $this->db->where('doctor', $doctor);
        $query = $this->db->get();
        return $query->result();
    }

    function getDepositAmountByPaymentId($payment_id) {
        $this->scope_tenant('patient_deposit');
        $this->db->select('*');
        $this->db->from('patient_deposit');
        $this->db->where('payment_id', $payment_id);
        $query = $this->db->get();
        $total = $query->result();
        $deposited_total = array();

        foreach ($total as $deposit) {
            $deposited_total[] = $deposit->deposited_amount;
        }

        if (!empty($deposited_total)) {
            $deposited_total = array_sum($deposited_total);
        } else {
            $deposited_total = 0;
        }

        return $deposited_total;
    }

    function getPaymentByDate($date_from, $date_to) {
        $this->scope_tenant('payment');
        $this->db->select('*');
        $this->db->from('payment');
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $query = $this->db->get();
        return $query->result();
    }

    function getPaymentByDoctorDate($doctor, $date_from, $date_to) {
        $this->scope_tenant('payment');
        $this->db->select('*');
        $this->db->from('payment');
        $this->db->where('doctor', $doctor);
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $query = $this->db->get();
        return $query->result();
    }

    function getDepositByPaymentId($payment_id) {
        $this->scope_tenant('patient_deposit');
        $this->db->select('*');
        $this->db->from('patient_deposit');
        $this->db->where('payment_id', $payment_id);
        $query = $this->db->get();
        return $query->result();
    }

    function getOtPaymentByDate($date_from, $date_to) {
        $this->scope_tenant('ot_payment');
        $this->db->select('*');
        $this->db->from('ot_payment');
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $query = $this->db->get();
        return $query->result();
    }

    function getDepositsByDate($date_from, $date_to) {
        $this->scope_tenant('patient_deposit');
        $this->db->select('*');
        $this->db->from('patient_deposit');
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $query = $this->db->get();
        return $query->result();
    }

    function getExpenseByDate($date_from, $date_to) {
        $this->scope_tenant('expense');
        $this->db->select('*');
        $this->db->from('expense');
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $query = $this->db->get();
        return $query->result();
    }
    
    function getPurchaseByDate($date_from, $date_to) {
        $this->scope_tenant('purchase');
        $this->db->select('*');
        $this->db->from('purchase');
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $query = $this->db->get();
        return $query->result();
    }

    function makeStatusPaid($id, $patient_id, $data, $data1) {
        $this->scope_tenant('payment');
        $this->db->where('patient', $patient_id);
        $this->db->where('status', 'paid-last');
        $this->db->update('payment', $data);
        $this->db->where('id', $id);
        $this->db->update('payment', $data1);
    }

    function makePaidByPatientIdByStatus($id, $data, $data1) {
        $this->scope_tenant('payment');
        $this->db->where('patient', $id);
        $this->db->where('status', 'paid-last');
        $this->db->update('payment', $data1);

        $this->scope_tenant('ot_payment');
        $this->db->where('patient', $id);
        $this->db->where('status', 'paid-last');
        $this->db->update('ot_payment', $data1);

        $this->scope_tenant('payment');
        $this->db->where('patient', $id);
        $this->db->where('status', 'unpaid');
        $this->db->update('payment', $data);

        $this->scope_tenant('ot_payment');
        $this->db->where('patient', $id);
        $this->db->where('status', 'unpaid');
        $this->db->update('ot_payment', $data);
    }

    function makeOtStatusPaid($id) {
        $this->scope_tenant('ot_payment');
        $this->db->where('id', $id);
        $this->db->update('ot_payment', array('status' => 'paid'));
    }

    function lastPaidInvoice($id) {
        $this->scope_tenant('payment');
        $this->db->where('patient', $id);
        $this->db->where('status', 'paid-last');
        $query = $this->db->get('payment');
        return $query->result();
    }

    function lastOtPaidInvoice($id) {
        $this->scope_tenant('ot_payment');
        $this->db->where('patient', $id);
        $this->db->where('status', 'paid-last');
        $query = $this->db->get('ot_payment');
        return $query->result();
    }

    function amountReceived($id, $data) {
        $this->scope_tenant('payment');
        $this->db->where('id', $id);
        $query = $this->db->update('payment', $data);
    }

    function otAmountReceived($id, $data) {
        $this->scope_tenant('ot_payment');
        $this->db->where('id', $id);
        $query = $this->db->update('ot_payment', $data);
    }

    function getPaymentByUserIdByDate($user, $date_from, $date_to) {
        $this->scope_tenant('payment');
        $this->db->order_by('id', 'desc');
        $this->db->select('*');
        $this->db->from('payment');
        $this->db->where('user', $user);
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $query = $this->db->get();
        return $query->result();
    }

    function getOtPaymentByUserIdByDate($user, $date_from, $date_to) {
        $this->scope_tenant('ot_payment');
        $this->db->order_by('id', 'desc');
        $this->db->select('*');
        $this->db->from('ot_payment');
        $this->db->where('user', $user);
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $query = $this->db->get();
        return $query->result();
    }

    function getDepositByUserIdByDate($user, $date_from, $date_to) {
        $this->scope_tenant('patient_deposit');
        $this->db->order_by('id', 'desc');
        $this->db->select('*');
        $this->db->from('patient_deposit');
        $this->db->where('user', $user);
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $query = $this->db->get();
        return $query->result();
    }

}
