<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Livestock extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('livestock_model');
        $this->load->model('purchase/purchase_model');
        $this->load->model('report/report_model');
        $this->load->library('upload');
        $this->load->model('ion_auth_model');
        $this->load->model('settings/settings_model');
        $settings = $this->settings_model->getSettings();
        $language = (!empty($settings) && !empty($settings->language)) ? $settings->language : 'english';
        $this->lang->load('system_syntax', $language);
        $this->load->model('home/home_model');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin'))) {
            redirect('home/permission');
        }
    }

    public function addLivestock()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $data['types'] = $this->livestock_model->getLivestockType();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('livestock', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertLivestock()
    {
        $this->form_validation->set_rules('ls_name', 'Livestock name', 'trim|required|max_length[500]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('livestock/addLivestock');
        }
        $ls_name = $this->input->post('ls_name');
        $ls_description = $this->input->post('ls_description');
        $ls_notes = $this->input->post('ls_notes');
        $data = array(
            'ls_name' => $ls_name,
            'ls_description' => $ls_description,
            'ls_notes' => $ls_notes,
            'ls_status' => 1,
            'ls_created_at' => get_current_time(),
            'ls_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->livestock_model->insertLivestock($data);
        $this->session->set_flashdata('success', 'Livestock breed added successfully.');
        redirect('livestock/addLivestock');
    }

    public function updateLivestock()
    {
        $ls_id = $this->input->post('ls_id');
        $ls_name = $this->input->post('ls_name');
        $ls_description = $this->input->post('ls_description');
        $ls_notes = $this->input->post('ls_notes');
        $data = array(
            'ls_name' => $ls_name,
            'ls_description' => $ls_description,
            'ls_notes' => $ls_notes,
            'ls_updated_at' => get_current_time(),
            'ls_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->livestock_model->updateLivestock($ls_id, $data);
        $this->session->set_flashdata('success', 'Livestock breed updated successfully.');
        redirect('livestock/addLivestock');
    }

    function editLivestockByJason()
    {
        $id = $this->input->get('ls_id');
        $data['livestock'] = $this->livestock_model->getLivestockById($id);
        $data['settings'] = $this->settings_model->getSettings();
        echo json_encode($data);
    }

    function deleteLivestock()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $ls_id = $this->input->post('ls_id');
        $data = array(
            'ls_status' => 0,
            'ls_updated_at' => get_current_time(),
            'ls_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->livestock_model->updateLivestock($ls_id, $data);
        $this->session->set_flashdata('success', 'Livestock breed deleted successfully.');
        redirect('livestock/addLivestock');
    }




    /* ===================== Type ===================== */
    public function addLivestockType()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['types'] = $this->livestock_model->getLivestockType();
        $data['livestocks'] = $this->livestock_model->getLivestock();
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('add_livestock_type', $data);
        $this->load->view('home/footer'); // just the header file
    }

    public function insertLivestockType()
    {
        $lst_title = $this->input->post('lst_title');
        $lst_ls_id = $this->input->post('lst_ls_id');
        $lst_description = $this->input->post('lst_description');
        $data = array(
            'lst_title' => $lst_title,
            'lst_ls_id' => $lst_ls_id,
            'lst_description' => $lst_description,
            'lst_status' => 1,
            'lst_created_at' => get_current_time(),
            'lst_created_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->livestock_model->insertLivestockType($data);
        $this->session->set_flashdata('success', 'Livestock type added successfully.');
        redirect('livestock/addLivestock');
    }

    function editLivestockTypeByJason()
    {
        $id = $this->input->get('lst_id');
        $data['livestockType'] = $this->livestock_model->getLivestockTypeById($id);
        $data['settings'] = $this->settings_model->getSettings();
        echo json_encode($data);
    }

    public function updateLivestockType()
    {
        $lst_id = $this->input->post('lst_id');
        $lst_ls_id = $this->input->post('lst_ls_id');
        $lst_title = $this->input->post('lst_title');

        $lst_description = $this->input->post('lst_description');
        $data = array(
            'lst_title' => $lst_title,
            'lst_description' => $lst_description,
            'lst_updated_at' => get_current_time(),
            'lst_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->livestock_model->updateLivestockType($lst_id, $data);
        $this->session->set_flashdata('success', 'Livestock type updated successfully.');
        redirect("livestock/viewVariant?ls_id=$lst_ls_id");
    }

    public function deleteLivestockType()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_action_token()) {
            show_error('Invalid request. Please use the in-app delete button.', 400);
        }
        $lst_id = $this->input->post('lst_id');
        $ls_id = $this->input->post('ls_id');
        $data = array(
            'lst_status' => 0,
            'lst_updated_at' => get_current_time(),
            'lst_updated_by' => $this->ion_auth->user()->row()->user_id
        );
        $this->livestock_model->updateLivestockType($lst_id, $data);
        $this->session->set_flashdata('success', 'Livestock type deleted successfully.');
        redirect("livestock/viewVariant?ls_id=$ls_id");
    }

    function viewLivestockTypeByLivestockIdByJson()
    {
        $ls_id = $this->input->get('ls_id');
        $data['typeByLivestock'] = $this->livestock_model->getLivestockTypeByLivestockId($ls_id);
        echo json_encode($data);
    }

    public function viewVariant()
    {
        $ls_id = $this->input->get('ls_id');
        $data['settings'] = $this->settings_model->getSettings();
        $data['types'] = $this->livestock_model->getLivestockType();
        $data['livestockById'] = $this->livestock_model->getLivestockById($ls_id);
        $data['variantByLivestocks'] = $this->livestock_model->getLivestockTypeByLivestockId($ls_id);
        $this->load->view('home/dashboard', $data); // just the header file
        $this->load->view('view_variant', $data);
        $this->load->view('home/footer'); // just the header file
    }






    /* ===================== CSV Export ===================== */
    public function exportCSV()
    {
        $rows = $this->livestock_model->getLivestock();
        $out = array();
        foreach ($rows as $r) {
            $out[] = array(
                'ID'          => $r->ls_id,
                'Name'        => $r->ls_name,
                'Description' => $r->ls_description,
                'Created At'  => $r->ls_created_at,
            );
        }
        csv_response('livestock', array('ID','Name','Description','Created At'), $out);
    }

    //End
}

/* End of file livestock.php */
/* Location: ./application/modules/livestock/controllers/livestock.php */
