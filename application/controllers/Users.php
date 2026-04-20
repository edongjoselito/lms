<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('auth');
        }
        $this->load->model('Admin_model');
    }

    public function index()
    {
        $data['title'] = 'Manage Users';
        $data['users'] = $this->Admin_model->get_all_users();

        $this->load->view('layouts/header', $data);
        $this->load->view('users/index', $data);
        $this->load->view('layouts/footer');
    }

    public function create()
    {
        $data['title'] = 'Add New User';
        $data['user'] = null;

        if ($this->input->method() === 'post') {
            $email = $this->input->post('email', TRUE);

            if ($this->Admin_model->email_exists($email)) {
                $this->session->set_flashdata('error', 'Email already exists.');
                redirect('users/create');
            }

            $user_data = array(
                'first_name' => $this->input->post('first_name', TRUE),
                'last_name'  => $this->input->post('last_name', TRUE),
                'email'      => $email,
                'password'   => $this->input->post('password'),
                'role'       => $this->input->post('role', TRUE),
                'status'     => $this->input->post('status') ? 1 : 0
            );

            if ($this->Admin_model->create_user($user_data)) {
                $this->session->set_flashdata('success', 'User created successfully.');
                redirect('users');
            } else {
                $this->session->set_flashdata('error', 'Failed to create user.');
                redirect('users/create');
            }
        }

        $this->load->view('layouts/header', $data);
        $this->load->view('users/form', $data);
        $this->load->view('layouts/footer');
    }

    public function edit($id)
    {
        $data['title'] = 'Edit User';
        $data['user'] = $this->Admin_model->get_user($id);

        if (!$data['user']) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $email = $this->input->post('email', TRUE);

            if ($this->Admin_model->email_exists($email, $id)) {
                $this->session->set_flashdata('error', 'Email already exists.');
                redirect('users/edit/' . $id);
            }

            $user_data = array(
                'first_name' => $this->input->post('first_name', TRUE),
                'last_name'  => $this->input->post('last_name', TRUE),
                'email'      => $email,
                'password'   => $this->input->post('password'),
                'role'       => $this->input->post('role', TRUE),
                'status'     => $this->input->post('status') ? 1 : 0
            );

            if ($this->Admin_model->update_user($id, $user_data)) {
                $this->session->set_flashdata('success', 'User updated successfully.');
                redirect('users');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user.');
                redirect('users/edit/' . $id);
            }
        }

        $this->load->view('layouts/header', $data);
        $this->load->view('users/form', $data);
        $this->load->view('layouts/footer');
    }

    public function delete($id)
    {
        $current_user_id = $this->session->userdata('user_id');

        if ($id == $current_user_id) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('users');
        }

        if ($this->Admin_model->delete_user($id)) {
            $this->session->set_flashdata('success', 'User deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        }
        redirect('users');
    }
}
