<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Client extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('client_model');
        $this->load->library('upload');
        $this->load->model('settings/settings_model');
        $settings = $this->settings_model->getSettings();
        $language = (!empty($settings) && !empty($settings->language)) ? $settings->language : 'english';
        $this->lang->load('system_syntax', $language);
        $this->load->model('report/report_model');

        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin'))) {
            redirect('home/permission');
        }
    }

    /* ========== List Client ========== */
    public function listClient()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['clients'] = $this->client_model->getClient();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_client', $data);
        $this->load->view('home/footer'); // just the header file
    }

    /* ========== Insert and update date ========== */
    public function insertClient()
    {
        $this->form_validation->set_rules('c_name', 'Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('c_email', 'Email', 'trim|max_length[255]|valid_email');
        $this->form_validation->set_rules('c_phone', 'Phone', 'trim|required|max_length[255]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('client/listClient');
        }
        $c_id = $this->input->post('c_id');
        $name = $this->input->post('c_name');

        $email = $this->input->post('c_email');
        $address = $this->input->post('c_address');
        $phone = $this->input->post('c_phone');
        $description = $this->input->post('c_description');
        $type = $this->input->post('c_ct_id') ?: 0;


        // Sanitize filename: strip path components, keep only safe chars + extension.
        $rawName = isset($_FILES['c_img_url']['name']) ? basename($_FILES['c_img_url']['name']) : '';
        $ext     = pathinfo($rawName, PATHINFO_EXTENSION);
        $base    = pathinfo($rawName, PATHINFO_FILENAME);
        $base    = preg_replace('/[^A-Za-z0-9._-]+/', '', $base);
        if ($base === '') { $base = 'upload_' . time(); }
        $new_file_name = $base . ($ext ? '.' . preg_replace('/[^A-Za-z0-9]/', '', $ext) : '');
        $config = array(
            'file_name' => $new_file_name,
            'upload_path' => "./uploads/",
            'allowed_types' => "gif|jpg|png|jpeg|pdf",
            'overwrite' => False,
            'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
            'max_height' => "1768",
            'max_width' => "2024"
        );

        if (empty($c_id)) {
            // insert
            $this->load->library('Upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('c_img_url')) {
                $path = $this->upload->data();
                $img_url = "uploads/" . $path['file_name'];
                $data = array();
                $data = array(
                    'c_img_url' => $img_url,
                    'c_ct_id' => $type,
                    'c_name' => $name,
                    'c_email' => $email,
                    'c_address' => $address,
                    'c_phone' => $phone,
                    'c_description' => $description,
                    'c_status' => 1,
                    'c_created_at' => get_current_time(),
                    'c_created_by' => $this->ion_auth->user()->row()->user_id
                );
            } else {
                $data = array();
                $data = array(
                    'c_ct_id' => $type,
                    'c_name' => $name,
                    'c_email' => $email,
                    'c_address' => $address,
                    'c_phone' => $phone,
                    'c_description' => $description,
                    'c_status' => 1,
                    'c_created_at' => get_current_time(),
                    'c_created_by' => $this->ion_auth->user()->row()->user_id
                );
            }
            $this->client_model->insertData('client', $data);
            $this->session->set_flashdata('success', 'Client added successfully.');
            redirect('client/listClient');
        } else {
            // Update
            $this->load->library('Upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('c_img_url')) {
                $path = $this->upload->data();
                $img_url = "uploads/" . $path['file_name'];
                $data = array();
                $data = array(
                    'c_img_url' => $img_url,
                    'c_ct_id' => $type,
                    'c_name' => $name,
                    'c_email' => $email,
                    'c_address' => $address,
                    'c_phone' => $phone,
                    'c_description' => $description,
                    'c_updated_at' => get_current_time(),
                    'c_updated_by' => $this->ion_auth->user()->row()->user_id
                );
            } else {
                $data = array();
                $data = array(
                    'c_ct_id' => $type,
                    'c_name' => $name,
                    'c_email' => $email,
                    'c_address' => $address,
                    'c_phone' => $phone,
                    'c_description' => $description,
                    'c_updated_at' => get_current_time(),
                    'c_updated_by' => $this->ion_auth->user()->row()->user_id
                );
            }
            if (!empty($c_id)) {
                $this->client_model->updateData('client', 'c_id', $c_id, $data);
            }
            $this->session->set_flashdata('success', 'Client updated successfully.');
            redirect('client/listClient');
        }
    }
    /* ========== Delete Client Data ========== */
    function deleteClient()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $c_id = $this->input->post('c_id');
        $data = array(
            'c_status' => 0,
            'c_updated_at' => get_current_time(),
            'c_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->client_model->updateData('client', 'c_id', $c_id, $data);
        $this->session->set_flashdata('success', 'Client deleted successfully.');
        redirect('client/listClient');
    }

    /* ========== Edit Client Data ========== */
    function editClientByJason()
    {
        $id = $this->input->get('c_id');
        $data['clients'] = $this->client_model->getClientById($id);
        echo json_encode($data);
    }

    /* ========== CSV Export ========== */
    public function exportCSV()
    {
        $rows = $this->client_model->getClient()->result();
        $out = array();
        foreach ($rows as $r) {
            $out[] = array(
                'ID'         => $r->c_id,
                'Name'       => $r->c_name,
                'Email'      => $r->c_email,
                'Phone'      => $r->c_phone,
                'Address'    => $r->c_address,
                'Created At' => $r->c_created_at,
            );
        }
        csv_response('clients', array('ID','Name','Email','Phone','Address','Created At'), $out);
    }
}

/* End of file client.php */
/* Location: ./application/modules/client/controllers/client.php */
