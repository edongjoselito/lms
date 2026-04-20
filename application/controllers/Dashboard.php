<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(array('User_model', 'Academic_model', 'Enrollment_model', 'School_model'));
    }

    public function index()
    {
        // Super admin without school context → show school selector
        if ($this->is_super_admin() && !$this->school_id) {
            redirect('schools');
        }

        // Students go straight to courses
        if ($this->is_student()) {
            redirect('courses');
            return;
        }

        $sy = $this->Academic_model->get_active_school_year($this->school_id);
        $data['title'] = 'Dashboard';
        $data['school_year'] = $sy;
        $data['total_users'] = $this->User_model->count_by_school($this->school_id);
        $data['total_students'] = $this->Enrollment_model->count_students(null, $this->school_id);
        $data['total_teachers'] = $this->User_model->count_by_role('teacher', $this->school_id);
        $data['total_enrolled'] = $sy ? $this->Enrollment_model->count_enrolled($sy->id, $this->school_id) : 0;
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();

        $this->render('dashboard/index', $data);
    }
}
