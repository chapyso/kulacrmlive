<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Staff extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('staff_model');
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

    /* =========================================== Staff =========================================== */
    public function listStaff()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['staffs'] = $this->staff_model->getStaff();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('list_staff', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertStaff()
    {
        $this->form_validation->set_rules('sf_name', 'Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('sf_email', 'Email', 'trim|max_length[255]|valid_email');
        $this->form_validation->set_rules('sf_phone', 'Phone', 'trim|required|max_length[255]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('staff/listStaff');
        }
        $sf_id = $this->input->post('sf_id');
        $name = $this->input->post('sf_name');

        $email = $this->input->post('sf_email');
        $address = $this->input->post('sf_address');
        $phone = $this->input->post('sf_phone');
        $description = $this->input->post('sf_description');


        // Sanitize filename: strip path components, keep only safe chars + extension.
        $rawName = isset($_FILES['sf_img_url']['name']) ? basename($_FILES['sf_img_url']['name']) : '';
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

        if (empty($sf_id)) {
            // insert
            $this->load->library('Upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('sf_img_url')) {
                $path = $this->upload->data();
                $img_url = $this->get_tenant_upload_relative_path($path['file_name']);
                $data = array();
                $data = array(
                    'sf_img_url' => $img_url,
                    'sf_name' => $name,
                    'sf_email' => $email,
                    'sf_address' => $address,
                    'sf_phone' => $phone,
                    'sf_description' => $description,
                    'sf_status' => 1,
                    'sf_created_at' => get_current_time(),
                    'sf_created_by' => $this->ion_auth->user()->row()->user_id
                );
            } else {
                $data = array();
                $data = array(
                    'sf_name' => $name,
                    'sf_email' => $email,
                    'sf_address' => $address,
                    'sf_phone' => $phone,
                    'sf_description' => $description,
                    'sf_status' => 1,
                    'sf_created_at' => get_current_time(),
                    'sf_created_by' => $this->ion_auth->user()->row()->user_id
                );
            }
            $this->staff_model->insertData('staff', $data);
            $this->session->set_flashdata('success', 'Staff added successfully.');
            redirect('staff/listStaff');
        } else {
            // Update
            $this->load->library('Upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('sf_img_url')) {
                $path = $this->upload->data();
                $img_url = $this->get_tenant_upload_relative_path($path['file_name']);
                $data = array();
                $data = array(
                    'sf_img_url' => $img_url,
                    'sf_name' => $name,
                    'sf_email' => $email,
                    'sf_address' => $address,
                    'sf_phone' => $phone,
                    'sf_description' => $description,
                    'sf_updated_at' => get_current_time(),
                    'sf_updated_by' => $this->ion_auth->user()->row()->user_id
                );
            } else {
                $data = array();
                $data = array(
                    'sf_name' => $name,
                    'sf_email' => $email,
                    'sf_address' => $address,
                    'sf_phone' => $phone,
                    'sf_description' => $description,
                    'sf_updated_at' => get_current_time(),
                    'sf_updated_by' => $this->ion_auth->user()->row()->user_id
                );
            }
            if (!empty($sf_id)) {
                $this->staff_model->updateData('staff', 'sf_id', $sf_id, $data);
            }
            $this->session->set_flashdata('success', 'Staff updated successfully.');
            redirect('staff/listStaff');
        }
    }

    function deleteStaff()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $sf_id = $this->input->post('sf_id');
        $data = array(
            'sf_status' => 0,
            'sf_updated_at' => get_current_time(),
            'sf_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->staff_model->updateData('staff', 'sf_id', $sf_id, $data);

        // Payments Delete
        $deletePayments = array(
            'sfp_status' => 0,
            'sfp_updated_at' => get_current_time(),
            'sfp_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->staff_model->updateData('staff_payment', 'sfp_sf_id', $sf_id, $deletePayments);

        $this->session->set_flashdata('success', 'Staff deleted successfully.');
        redirect('staff/listStaff');
    }

    function editStaffByJason()
    {
        $id = $this->input->get('sf_id');
        $data['staffs'] = $this->staff_model->getStaffById($id);
        echo json_encode($data);
    }

    /* =========================================== Staff Type =========================================== */
    public function listStaffType()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $this->db->where('sft_status', 1);
        $data['types'] = $this->db->get('staff_type')->result();
        $this->load->view('home/dashboard', $data);
        $this->load->view('list_staff_type', $data);
        $this->load->view('home/footer');
    }

    public function insertStaffType()
    {
        $this->form_validation->set_rules('sft_name', 'Staff type name', 'trim|required|max_length[255]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('staff/listStaffType');
        }
        $sft_id = $this->input->post('sft_id');
        $sft_name = $this->input->post('sft_name');
        $sft_description = $this->input->post('sft_description');
        if (empty($sft_id)) {
            $data = array(
                'sft_name' => $sft_name,
                'sft_description' => $sft_description,
                'sft_status' => 1,
                'sft_created_at' => get_current_time(),
                'sft_created_by' => $this->ion_auth->user()->row()->user_id,
            );
            $this->staff_model->insertData('staff_type', $data);
            $this->session->set_flashdata('success', 'Staff type added successfully.');
        } else {
            $data = array(
                'sft_name' => $sft_name,
                'sft_description' => $sft_description,
                'sft_updated_at' => get_current_time(),
                'sft_updated_by' => $this->ion_auth->user()->row()->user_id,
            );
            $this->staff_model->updateData('staff_type', 'sft_id', $sft_id, $data);
            $this->session->set_flashdata('success', 'Staff type updated successfully.');
        }
        redirect('staff/listStaffType');
    }

    function editStaffTypeByJason()
    {
        $id = $this->input->get('sft_id');
        $this->db->where('sft_id', $id);
        $data['types'] = $this->db->get('staff_type')->row();
        echo json_encode($data);
    }

    function deleteStaffType()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $sft_id = $this->input->post('sft_id');
        // Block delete while staff records still reference this type.
        $inUse = $this->settings_model->getCountRow('staff', 'sf_id', array('sf_sft_id' => $sft_id, 'sf_status' => 1));
        if ($inUse > 0) {
            $this->session->set_flashdata('error', 'Cannot delete: staff records are using this type.');
            redirect('staff/listStaffType');
        }
        $data = array(
            'sft_status' => 0,
            'sft_updated_at' => get_current_time(),
            'sft_updated_by' => $this->ion_auth->user()->row()->user_id,
        );
        $this->staff_model->updateData('staff_type', 'sft_id', $sft_id, $data);
        $this->session->set_flashdata('success', 'Staff type deleted successfully.');
        redirect('staff/listStaffType');
    }


    // End
}

/* End of file staff.php */
/* Location: ./application/modules/staff/controllers/staff.php */
