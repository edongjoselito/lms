<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enrollment extends Staff_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_school();
        $this->load->model(array('Enrollment_model', 'Academic_model', 'User_model'));
    }

    public function index()
    {
        $sy = $this->Academic_model->get_active_school_year($this->school_id);
        $filters = array();
        if ($sy) $filters['school_year_id'] = $sy->id;
        if ($this->school_id) $filters['school_id'] = $this->school_id;
        if ($this->input->get('system_type')) $filters['system_type'] = $this->input->get('system_type');

        $data['title'] = 'Enrollment';
        $data['school_year'] = $sy;
        $data['enrollments'] = $this->Enrollment_model->get_enrollments($filters);
        $data['filter_type'] = $this->input->get('system_type');
        $this->render('enrollment/index', $data);
    }

    public function students()
    {
        $filters = array();
        if ($this->school_id) $filters['school_id'] = $this->school_id;
        $data['title'] = 'Students';
        $data['students'] = $this->Enrollment_model->get_students($filters);
        $this->render('enrollment/students', $data);
    }

    public function create_student()
    {
        if ($this->input->method() === 'post') {
            $user_data = array(
                'first_name'  => $this->input->post('first_name', TRUE),
                'middle_name' => $this->input->post('middle_name', TRUE),
                'last_name'   => $this->input->post('last_name', TRUE),
                'suffix'      => $this->input->post('suffix', TRUE),
                'email'       => $this->input->post('email', TRUE),
                'phone'       => $this->input->post('phone', TRUE),
                'password'    => $this->input->post('password') ?: 'student123',
            );

            if ($this->User_model->email_exists($user_data['email'])) {
                $this->session->set_flashdata('error', 'Email already exists.');
                redirect('enrollment/create_student');
            }

            $student_data = array(
                'school_id'      => $this->school_id,
                'lrn'            => $this->input->post('lrn', TRUE) ?: NULL,
                'student_id'     => $this->input->post('student_id', TRUE) ?: NULL,
                'system_type'    => $this->input->post('system_type', TRUE),
                'gender'         => $this->input->post('gender', TRUE),
                'birthdate'      => $this->input->post('birthdate'),
                'address'        => $this->input->post('address', TRUE),
                'guardian_name'  => $this->input->post('guardian_name', TRUE),
                'guardian_contact'=> $this->input->post('guardian_contact', TRUE),
                'grade_level_id' => $this->input->post('grade_level_id') ?: NULL,
                'strand_id'      => $this->input->post('strand_id') ?: NULL,
                'program_id'     => $this->input->post('program_id') ?: NULL,
                'year_level'     => $this->input->post('year_level') ?: NULL,
                'admission_date' => date('Y-m-d'),
            );

            $this->Enrollment_model->create_student($user_data, $student_data);
            $this->session->set_flashdata('success', 'Student registered successfully.');
            redirect('enrollment/students');
        }

        $data['title'] = 'Register Student';
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();
        $data['strands'] = $this->Academic_model->get_strands();
        $this->render('enrollment/student_form', $data);
    }

    public function enroll()
    {
        $sy = $this->Academic_model->get_active_school_year($this->school_id);
        if ($this->input->method() === 'post') {
            $d = array(
                'student_id'     => $this->input->post('student_id'),
                'school_id'      => $this->school_id,
                'school_year_id' => $sy->id,
                'section_id'     => $this->input->post('section_id') ?: NULL,
                'system_type'    => $this->input->post('system_type', TRUE),
                'grade_level_id' => $this->input->post('grade_level_id') ?: NULL,
                'strand_id'      => $this->input->post('strand_id') ?: NULL,
                'program_id'     => $this->input->post('program_id') ?: NULL,
                'year_level'     => $this->input->post('year_level') ?: NULL,
                'semester_id'    => $this->input->post('semester_id') ?: NULL,
                'status'         => 'enrolled',
            );
            $this->Enrollment_model->create($d);
            $this->session->set_flashdata('success', 'Student enrolled successfully.');
            redirect('enrollment');
        }

        $data['title'] = 'Enroll Student';
        $data['school_year'] = $sy;
        $stud_filters = array('status' => 'active');
        if ($this->school_id) $stud_filters['school_id'] = $this->school_id;
        $data['students'] = $this->Enrollment_model->get_students($stud_filters);
        $sec_filters = array();
        if ($sy) $sec_filters['school_year_id'] = $sy->id;
        if ($this->school_id) $sec_filters['school_id'] = $this->school_id;
        $data['sections'] = $this->Academic_model->get_sections($sec_filters);
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();
        $data['strands'] = $this->Academic_model->get_strands();
        $data['semesters'] = $sy ? $this->Academic_model->get_semesters($sy->id) : array();
        $this->render('enrollment/enroll_form', $data);
    }
}
