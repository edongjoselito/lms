<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
    }

    public function index()
    {
        if ($this->session->userdata('admin_logged_in')) {
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

        $user = $this->Admin_model->login($email, $password);

        if ($user) {
            $session_data = array(
                'user_id'          => $user->id,
                'first_name'       => $user->first_name,
                'last_name'        => $user->last_name,
                'email'            => $user->email,
                'role'             => $user->role,
                'admin_logged_in'  => TRUE
            );
            $this->session->set_userdata($session_data);
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
