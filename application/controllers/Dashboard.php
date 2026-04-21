<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(array('User_model', 'Academic_model', 'School_model'));
    }

    public function index()
    {
        // Super admin without school context → show platform dashboard
        if ($this->is_super_admin() && !$this->school_id) {
            $data['title'] = 'Platform Dashboard';
            $data['total_schools'] = $this->School_model->count_all();
            $data['active_schools'] = $this->db->where('status', 1)->count_all_results('schools');
            $data['total_users'] = $this->db->where('status', 1)->count_all_results('users');
            $data['total_courses'] = $this->db->count_all_results('courses');
            $data['is_platform_view'] = true;
            $this->render('dashboard/index', $data);
            return;
        }

        // Students go to their dedicated dashboard
        if ($this->is_student()) {
            redirect('student');
            return;
        }

        // Course creators go to their dashboard
        if ($this->is_course_creator()) {
            redirect('course');
            return;
        }

        $sy = $this->Academic_model->get_active_school_year($this->school_id);
        
        // Get school type
        $school = $this->db->where('id', $this->school_id)->get('schools')->row();
        $school_type = $school ? $school->type : null;
        
        $data['title'] = 'Dashboard';
        $data['school_year'] = $sy;
        $data['school_type'] = $school_type;
        $data['total_users'] = $this->User_model->count_by_school($this->school_id);
        $data['total_teachers'] = $this->User_model->count_by_role('teacher', $this->school_id);
        $data['total_subjects'] = count($this->Academic_model->get_subjects());
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();
        $data['is_platform_view'] = false;

        $this->render('dashboard/index', $data);
    }
}
