<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profile extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('profile_model');
        $this->load->library('upload');
        $this->load->model('settings/settings_model');
        $settings = $this->settings_model->getSettings();
        $language = (!empty($settings) && !empty($settings->language)) ? $settings->language : 'english';
        $this->lang->load('system_syntax', $language);
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index()
    {
        if ($this->is_super_admin()) {
            redirect('superadmin/profile');
            return;
        }

        $data = array();
        $id = $this->ion_auth->get_user_id();
        $data['profile'] = $this->profile_model->getProfileById($id);
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data);
        $this->load->view('profile', $data);
        $this->load->view('home/footer');
    }

    public function addNew()
    {
        $ion_user_id = $this->ion_auth->get_user_id();
        $name = trim((string)$this->input->post('name'));
        $email = trim((string)$this->input->post('email'));
        $password = (string)$this->input->post('password');

        $is_super = $this->is_super_admin();
        $redirect_target = $is_super ? 'superadmin/profile' : 'profile';

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger mb-2">', '</div>');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[100]');

        if (!empty($password)) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[100]');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors() ? validation_errors() : 'Validation failed. Please check your inputs.');
            redirect($redirect_target);
            return;
        }

        // Check if email belongs to another user
        $email_exists = $this->db->where('email', $email)->where('id !=', $ion_user_id)->get('users')->row();
        if ($email_exists) {
            $this->session->set_flashdata('error', 'The email address "' . html_escape($email) . '" is already in use by another user.');
            redirect($redirect_target);
            return;
        }

        // Prepare data for Ion Auth update
        $update_data = array(
            'username' => $name,
            'email'    => $email,
        );

        if (!empty($password)) {
            $update_data['password'] = $password;
        }

        if ($this->ion_auth->update($ion_user_id, $update_data)) {
            // Sync role-specific profile table if present
            $profile_data = array(
                'name'  => $name,
                'email' => $email,
            );

            $user_groups_query = $this->profile_model->getUsersGroups($ion_user_id);
            if ($user_groups_query && $user_groups_query->num_rows() > 0) {
                foreach ($user_groups_query->result() as $ug) {
                    $group_query = $this->profile_model->getGroups($ug->group_id);
                    if ($group_query && $group_query->num_rows() > 0) {
                        $group_row = $group_query->row();
                        if (!empty($group_row->name)) {
                            $group_name = strtolower($group_row->name);
                            $this->profile_model->updateProfile($ion_user_id, $profile_data, $group_name);
                        }
                    }
                }
            }

            $this->session->set_flashdata('success', 'Profile updated successfully.');
        } else {
            $auth_errors = $this->ion_auth->errors();
            $this->session->set_flashdata('error', !empty($auth_errors) ? strip_tags($auth_errors) : 'Failed to update profile.');
        }

        redirect($redirect_target);
    }
}

/* End of file profile.php */
/* Location: ./application/modules/profile/controllers/profile.php */
