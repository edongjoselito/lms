<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model('User_model');
    }

    public function index()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->User_model->get($this->current_user->id);
        $this->render('profile/index', $data);
    }

    public function update()
    {
        if ($this->input->method() !== 'post') {
            redirect('profile');
        }

        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('profile');
            return;
        }

        $email = $this->input->post('email', TRUE);
        if ($this->User_model->email_exists($email, $this->current_user->id)) {
            $this->session->set_flashdata('error', 'Email is already in use by another user.');
            redirect('profile');
            return;
        }

        $data = array(
            'first_name' => $this->input->post('first_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
            'email' => $email,
        );

        $this->User_model->update_profile($this->current_user->id, $data);
        $this->session->set_flashdata('success', 'Profile updated successfully.');

        redirect('profile');
    }

    public function upload_avatar()
    {
        if ($this->input->method() !== 'post') {
            redirect('profile');
        }

        $user_id = $this->current_user->id;
        $upload_path = FCPATH . 'uploads/avatars/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
        $config['max_size']      = 2048;
        $config['file_name']     = 'avatar_' . $user_id . '_' . time();
        $config['overwrite']     = FALSE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('avatar')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('profile');
            return;
        }

        $upload_data = $this->upload->data();
        $avatar_path = 'uploads/avatars/' . $upload_data['file_name'];

        // Remove old avatar if exists
        $user = $this->User_model->get($user_id);
        if (!empty($user->avatar) && file_exists(FCPATH . $user->avatar)) {
            unlink(FCPATH . $user->avatar);
        }

        $this->User_model->update_profile($user_id, array('avatar' => $avatar_path));
        $this->session->set_userdata('avatar', $avatar_path);
        $this->session->set_flashdata('success', 'Avatar updated successfully.');
        redirect('profile');
    }

    public function remove_avatar()
    {
        $user_id = $this->current_user->id;
        $user = $this->User_model->get($user_id);

        if (!empty($user->avatar) && file_exists(FCPATH . $user->avatar)) {
            unlink(FCPATH . $user->avatar);
        }

        $this->User_model->update_profile($user_id, array('avatar' => NULL));
        $this->session->unset_userdata('avatar');
        $this->session->set_flashdata('success', 'Avatar removed.');
        redirect('profile');
    }

    public function change_password()
    {
        $data['title'] = 'Change Password';
        $this->render('profile/change_password', $data);
    }

    public function update_password()
    {
        if ($this->input->method() !== 'post') {
            redirect('profile/change_password');
        }

        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('profile/change_password');
            return;
        }

        $current_password = $this->input->post('current_password');
        $new_password = $this->input->post('new_password');

        if ($this->User_model->change_password($this->current_user->id, $current_password, $new_password)) {
            $this->session->set_flashdata('success', 'Password changed successfully.');
        } else {
            $this->session->set_flashdata('error', 'Current password is incorrect.');
        }

        redirect('profile/change_password');
    }
}
