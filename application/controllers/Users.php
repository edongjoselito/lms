<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('User_model', 'Audit_model'));
    }

    public function index()
    {
        $filters = array();
        if ($this->school_id) {
            $filters['school_id'] = $this->school_id;
        }

        // Pagination
        $per_page = 15;
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $offset = ($page - 1) * $per_page;

        $total_users = $this->User_model->count_all($filters);
        $users = $this->User_model->get_all($filters, $per_page, $offset);

        $data['title'] = 'Manage Users';
        $data['users'] = $users;
        $data['roles'] = $this->User_model->get_roles();
        $data['total_users'] = $total_users;
        $data['per_page'] = $per_page;
        $data['current_page'] = $page;
        $data['total_pages'] = ceil($total_users / $per_page);
        $this->render('users/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Add New User';
        $data['user'] = null;
        // Filter roles to show teacher, school_admin, student, registrar, and course_creator
        $all_roles = $this->User_model->get_roles();
        $data['roles'] = array_filter($all_roles, function ($role) {
            return in_array($role->slug, array('teacher', 'school_admin', 'student', 'registrar', 'course_creator'));
        });

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
                $user_id = $this->db->insert_id();
                $user_name = $user_data['first_name'] . ' ' . $user_data['last_name'];

                // Audit log
                $this->Audit_model->log('create', 'user', $user_id, $user_name, 'Created user: ' . $user_name . ' (' . $email . ')');

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
                $user_name = $user_data['first_name'] . ' ' . $user_data['last_name'];

                // Audit log
                $this->Audit_model->log('update', 'user', $id, $user_name, 'Updated user: ' . $user_name . ' (' . $email . ')');

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

        $user = $this->User_model->get($id);
        $user_name = $user ? $user->first_name . ' ' . $user->last_name : 'Unknown';

        if ($this->User_model->delete($id)) {
            // Audit log
            $this->Audit_model->log('delete', 'user', $id, $user_name, 'Deleted user: ' . $user_name);

            $this->session->set_flashdata('success', 'User deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        }
        redirect('users');
    }
}
