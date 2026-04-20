<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index()
    {
        $filters = array();
        if ($this->school_id) {
            $filters['school_id'] = $this->school_id;
        }
        $data['title'] = 'Manage Users';
        $data['users'] = $this->User_model->get_all($filters);
        $data['roles'] = $this->User_model->get_roles();
        $this->render('users/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Add New User';
        $data['user'] = null;
        $data['roles'] = $this->User_model->get_roles();

        if ($this->input->method() === 'post') {
            $email = $this->input->post('email', TRUE);

            if ($this->User_model->email_exists($email)) {
                $this->session->set_flashdata('error', 'Email already exists.');
                redirect('users/create');
            }

            $user_data = array(
                'first_name'  => $this->input->post('first_name', TRUE),
                'middle_name' => $this->input->post('middle_name', TRUE),
                'last_name'   => $this->input->post('last_name', TRUE),
                'suffix'      => $this->input->post('suffix', TRUE),
                'email'       => $email,
                'phone'       => $this->input->post('phone', TRUE),
                'password'    => $this->input->post('password'),
                'role_id'     => $this->input->post('role_id'),
                'school_id'   => $this->school_id,
                'status'      => $this->input->post('status') ? 1 : 0
            );

            if ($this->User_model->create($user_data)) {
                $this->session->set_flashdata('success', 'User created successfully.');
                redirect('users');
            } else {
                $this->session->set_flashdata('error', 'Failed to create user.');
                redirect('users/create');
            }
        }

        $this->render('users/form', $data);
    }

    public function edit($id)
    {
        $data['title'] = 'Edit User';
        $data['user'] = $this->User_model->get($id);
        $data['roles'] = $this->User_model->get_roles();

        if (!$data['user']) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $email = $this->input->post('email', TRUE);

            if ($this->User_model->email_exists($email, $id)) {
                $this->session->set_flashdata('error', 'Email already exists.');
                redirect('users/edit/' . $id);
            }

            $user_data = array(
                'first_name'  => $this->input->post('first_name', TRUE),
                'middle_name' => $this->input->post('middle_name', TRUE),
                'last_name'   => $this->input->post('last_name', TRUE),
                'suffix'      => $this->input->post('suffix', TRUE),
                'email'       => $email,
                'phone'       => $this->input->post('phone', TRUE),
                'password'    => $this->input->post('password'),
                'role_id'     => $this->input->post('role_id'),
                'status'      => $this->input->post('status') ? 1 : 0
            );

            if ($this->User_model->update($id, $user_data)) {
                $this->session->set_flashdata('success', 'User updated successfully.');
                redirect('users');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user.');
                redirect('users/edit/' . $id);
            }
        }

        $this->render('users/form', $data);
    }

    public function delete($id)
    {
        if ($id == $this->current_user->id) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('users');
        }

        if ($this->User_model->delete($id)) {
            $this->session->set_flashdata('success', 'User deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        }
        redirect('users');
    }
}
