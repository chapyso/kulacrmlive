<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Supplier extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('supplier_model');
        $this->load->library('upload');
        $this->load->model('settings/settings_model');
        $settings = $this->settings_model->getSettings();
        $language = (!empty($settings) && !empty($settings->language)) ? $settings->language : 'english';
        $this->lang->load('system_syntax', $language);
        $this->load->model('report/report_model');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin'))) {
            redirect('home/permission');
        }
    }

    public function listSupplier()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['suppliers'] = $this->supplier_model->getData('supplier', 's_status', 1);
        // $data['supplierTypes'] = $this->supplier_model->getData('supplier_type', 'st_status', 1);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_supplier', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertSupplier()
    {
        $this->form_validation->set_rules('s_name', 'Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('s_email', 'Email', 'trim|max_length[255]|valid_email');
        $this->form_validation->set_rules('s_phone', 'Phone', 'trim|required|max_length[255]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('supplier/listSupplier');
        }
        $s_id = $this->input->post('s_id');
        $name = $this->input->post('s_name');

        $email = $this->input->post('s_email');
        $address = $this->input->post('s_address');
        $phone = $this->input->post('s_phone');
        $description = $this->input->post('s_description');


        // Sanitize filename: strip path components, keep only safe chars + extension.
        $rawName = isset($_FILES['s_img_url']['name']) ? basename($_FILES['s_img_url']['name']) : '';
        $ext     = pathinfo($rawName, PATHINFO_EXTENSION);
        $base    = pathinfo($rawName, PATHINFO_FILENAME);
        $base    = preg_replace('/[^A-Za-z0-9._-]+/', '', $base);
        if ($base === '') { $base = 'upload_' . time(); }
        $new_file_name = $base . ($ext ? '.' . preg_replace('/[^A-Za-z0-9]/', '', $ext) : '');
        $config = array(
            'file_name' => $new_file_name,
            'upload_path' => $this->get_tenant_upload_path(),
            'allowed_types' => "gif|jpg|png|jpeg|pdf|webp",
            'overwrite' => False,
            'max_size' => "20480", // 20 MB max
        );

        if (empty($s_id)) {
            // insert
            $this->load->library('Upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('s_img_url')) {
                $path = $this->upload->data();
                $img_url = $this->get_tenant_upload_relative_path($path['file_name']);
                $data = array();
                $data = array(
                    's_img_url' => $img_url,
                    's_name' => $name,
                    's_email' => $email,
                    's_address' => $address,
                    's_phone' => $phone,
                    's_description' => $description,
                    's_status' => 1,
                    's_created_at' => get_current_time(),
                    's_created_by' => $this->ion_auth->user()->row()->user_id
                );
            } else {
                $data = array();
                $data = array(
                    's_name' => $name,
                    's_email' => $email,
                    's_address' => $address,
                    's_phone' => $phone,
                    's_description' => $description,
                    's_status' => 1,
                    's_created_at' => get_current_time(),
                    's_created_by' => $this->ion_auth->user()->row()->user_id
                );
            }
            $this->supplier_model->insertData('supplier', $data);
            $this->session->set_flashdata('success', 'Supplier added successfully.');
            redirect('supplier/listSupplier');
        } else {
            // Update
            $this->load->library('Upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('s_img_url')) {
                $path = $this->upload->data();
                $img_url = $this->get_tenant_upload_relative_path($path['file_name']);
                $data = array();
                $data = array(
                    's_img_url' => $img_url,
                    's_name' => $name,
                    's_email' => $email,
                    's_address' => $address,
                    's_phone' => $phone,
                    's_description' => $description,
                    's_updated_at' => get_current_time(),
                    's_updated_by' => $this->ion_auth->user()->row()->user_id
                );
            } else {
                $data = array();
                $data = array(
                    's_name' => $name,
                    's_email' => $email,
                    's_address' => $address,
                    's_phone' => $phone,
                    's_description' => $description,
                    's_updated_at' => get_current_time(),
                    's_updated_by' => $this->ion_auth->user()->row()->user_id
                );
            }
            if (!empty($s_id)) {
                $this->supplier_model->updateData('supplier', 's_id', $s_id, $data);
            }
            $this->session->set_flashdata('success', 'Supplier updated successfully.');
            redirect('supplier/listSupplier');
        }
    }

    function deleteSupplier()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $s_id = $this->input->post('s_id');
        $data = array(
            's_status' => 0,
            's_updated_at' => get_current_time(),
            's_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->supplier_model->updateData('supplier', 's_id', $s_id, $data);
        $this->session->set_flashdata('success', 'Supplier deleted successfully.');
        redirect('supplier/listSupplier');
    }

    function editSupplierByJason()
    {
        $id = $this->input->get('s_id');
        $data['suppliers'] = $this->supplier_model->getData('supplier', 's_id', $id)->row();
        echo json_encode($data);
    }

    /* =========================================== Supplier Type =========================================== */
    public function listSupplierType()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['types'] = $this->supplier_model->getData('supplier_type', 'st_status', 1)->result();
        $this->load->view('home/dashboard', $data);
        $this->load->view('list_supplier_type', $data);
        $this->load->view('home/footer');
    }

    public function insertSupplierType()
    {
        $this->form_validation->set_rules('st_name', 'Supplier type name', 'trim|required|max_length[255]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('supplier/listSupplierType');
        }
        $st_id = $this->input->post('st_id');
        $st_name = $this->input->post('st_name');
        $st_description = $this->input->post('st_description');
        if (empty($st_id)) {
            $data = array(
                'st_name' => $st_name,
                'st_description' => $st_description,
                'st_status' => 1,
                'st_created_at' => get_current_time(),
                'st_created_by' => $this->ion_auth->user()->row()->user_id,
            );
            $this->supplier_model->insertData('supplier_type', $data);
            $this->session->set_flashdata('success', 'Supplier type added successfully.');
        } else {
            $data = array(
                'st_name' => $st_name,
                'st_description' => $st_description,
                'st_updated_at' => get_current_time(),
                'st_updated_by' => $this->ion_auth->user()->row()->user_id,
            );
            $this->supplier_model->updateData('supplier_type', 'st_id', $st_id, $data);
            $this->session->set_flashdata('success', 'Supplier type updated successfully.');
        }
        redirect('supplier/listSupplierType');
    }

    function editSupplierTypeByJason()
    {
        $id = $this->input->get('st_id');
        $data['types'] = $this->supplier_model->getData('supplier_type', 'st_id', $id)->row();
        echo json_encode($data);
    }

    function deleteSupplierType()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $st_id = $this->input->post('st_id');
        // Block delete while supplier records still reference this type.
        $inUse = $this->settings_model->getCountRow('supplier', 's_id', array('s_st_id' => $st_id, 's_status' => 1));
        if ($inUse > 0) {
            $this->session->set_flashdata('error', 'Cannot delete: supplier records are using this type.');
            redirect('supplier/listSupplierType');
        }
        $data = array(
            'st_status' => 0,
            'st_updated_at' => get_current_time(),
            'st_updated_by' => $this->ion_auth->user()->row()->user_id,
        );
        $this->supplier_model->updateData('supplier_type', 'st_id', $st_id, $data);
        $this->session->set_flashdata('success', 'Supplier type deleted successfully.');
        redirect('supplier/listSupplierType');
    }

    /* ========== CSV Export ========== */
    public function exportCSV()
    {
        $rows = $this->supplier_model->getData('supplier', 's_status', 1)->result();
        $out = array();
        foreach ($rows as $r) {
            $out[] = array(
                'ID'         => $r->s_id,
                'Name'       => $r->s_name,
                'Email'      => $r->s_email,
                'Phone'      => $r->s_phone,
                'Address'    => $r->s_address,
                'Created At' => $r->s_created_at,
            );
        }
        csv_response('suppliers', array('ID','Name','Email','Phone','Address','Created At'), $out);
    }


    // End
}

/* End of file supplier.php */
/* Location: ./application/modules/supplier/controllers/supplier.php */
