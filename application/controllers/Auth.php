<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $this->load->view('auth/login');
    }

    public function login()
    {
        if ($this->input->method() !== 'post') {
            redirect('auth');
        }

        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password');

        $user = $this->User_model->authenticate($email, $password);

        if ($user) {
            $session_data = array(
                'user_id'    => $user->id,
                'role_id'    => $user->role_id,
                'role_slug'  => $user->role_slug,
                'role_name'  => $user->role_name,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'school_id'  => $user->school_id,
                'logged_in'  => TRUE
            );

            // Load school name if user belongs to a school
            if ($user->school_id) {
                $school = $this->db->where('id', $user->school_id)->get('schools')->row();
                $session_data['school_name'] = $school ? $school->name : '';
            }

            $this->session->set_userdata($session_data);
            $this->User_model->update_last_login($user->id);

            // Super admin without school → go to school selection
            if ($user->role_slug === 'super_admin' && !$user->school_id) {
                redirect('schools/select');
            }

            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Invalid email or password.');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
