<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teachers extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Teachers_model');
    }

    public function index()
    {
        $data['title'] = 'Teachers';
        $search = $this->input->get('search', TRUE);
        $data['search'] = $search;
        $data['teachers'] = $this->Teachers_model->get_all($this->school_id, $search);
        $this->render('teachers/index', $data);
    }

    public function download_template()
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="teachers_template.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, array('First Name', 'Last Name', 'Email', 'Password'));
        
        // Add 25 sample records
        $first_names = array('John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'William', 'Jennifer', 'James', 'Amanda', 'Daniel', 'Jessica', 'Matthew', 'Ashley', 'Christopher', 'Stephanie', 'Andrew', 'Nicole', 'Joshua', 'Melissa', 'Ryan', 'Elizabeth', 'Brandon');
        $last_names = array('Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee', 'Perez', 'Thompson', 'White', 'Harris');
        
        for ($i = 0; $i < 25; $i++) {
            $first_name = $first_names[$i % count($first_names)];
            $last_name = $last_names[$i % count($last_names)];
            $email = strtolower($first_name . '.' . $last_name . ($i + 1) . '@domain.com');
            $password = 'Password' . str_pad($i + 1, 3, '0', STR_PAD_LEFT) . '!';
            fputcsv($output, array($first_name, $last_name, $email, $password));
        }
        
        fclose($output);
        exit;
    }

    public function bulk_upload()
    {
        if ($this->input->method() === 'post') {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'csv';
            $config['max_size'] = 2048;
            $this->load->library('upload', $config);
            
            if (!$this->upload->do_upload('csv_file')) {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('teachers/bulk_upload');
            } else {
                $file_data = $this->upload->data();
                $file_path = $file_data['full_path'];
                
                $handle = fopen($file_path, 'r');
                $header = fgetcsv($handle);
                $success_count = 0;
                $error_count = 0;
                $errors = array();
                
                while (($row = fgetcsv($handle)) !== FALSE) {
                    if (count($row) >= 3) {
                        $data = array(
                            'first_name' => $row[0],
                            'last_name' => $row[1],
                            'email' => $row[2],
                            'password' => isset($row[3]) ? $row[3] : 'Password123!',
                            'school_id' => $this->school_id
                        );
                        
                        try {
                            $this->Teachers_model->create($data);
                            $success_count++;
                        } catch (Exception $e) {
                            $error_count++;
                            $errors[] = 'Row ' . ($success_count + $error_count + 1) . ': ' . $e->getMessage();
                        }
                    }
                }
                
                fclose($handle);
                unlink($file_path);
                
                $this->session->set_flashdata('success', "Bulk upload completed. Success: $success_count, Errors: $error_count");
                if (!empty($errors)) {
                    $this->session->set_flashdata('errors', $errors);
                }
                redirect('teachers');
            }
        }
        $data['title'] = 'Bulk Upload Teachers';
        $this->render('teachers/bulk_upload', $data);
    }

    public function create()
    {
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

            if ($this->form_validation->run() === TRUE) {
                $data = array(
                    'first_name' => $this->input->post('first_name', TRUE),
                    'last_name' => $this->input->post('last_name', TRUE),
                    'email' => $this->input->post('email', TRUE),
                    'password' => $this->input->post('password', TRUE),
                    'school_id' => $this->school_id
                );
                $this->Teachers_model->create($data);
                $this->session->set_flashdata('success', 'Teacher created successfully.');
                redirect('teachers');
            }
        }
        $data['title'] = 'Add Teacher';
        $this->render('teachers/form', $data);
    }

    public function edit($id)
    {
        $data['teacher'] = $this->Teachers_model->get($id);
        if (!$data['teacher']) show_404();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_check_email[' . $data['teacher']->user_id . ']');

            if ($this->form_validation->run() === TRUE) {
                $update_data = array(
                    'first_name' => $this->input->post('first_name', TRUE),
                    'last_name' => $this->input->post('last_name', TRUE),
                    'email' => $this->input->post('email', TRUE),
                    'password' => $this->input->post('password', TRUE)
                );
                $result = $this->Teachers_model->update($id, $update_data);
                if ($result) {
                    $this->session->set_flashdata('success', 'Teacher updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'No changes made or update failed.');
                }
                redirect('teachers');
            }
        }
        $data['title'] = 'Edit Teacher';
        $this->render('teachers/form', $data);
    }

    public function delete($id)
    {
        $teacher = $this->Teachers_model->get($id);
        if (!$teacher) show_404();

        $this->Teachers_model->delete($id);
        $this->session->set_flashdata('success', 'Teacher deleted successfully.');
        redirect('teachers');
    }

    public function check_email($email, $user_id)
    {
        $this->db->where('email', $email);
        $this->db->where('id !=', $user_id);
        $this->db->from('users');
        $count = $this->db->count_all_results();
        if ($count > 0) {
            $this->form_validation->set_message('check_email', 'The {field} field must contain a unique value.');
            return FALSE;
        }
        return TRUE;
    }
}
