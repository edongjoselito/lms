<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

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
            $data['title'] = 'System Overview';

            // Platform metrics
            $data['total_schools'] = $this->School_model->count_all();
            $data['active_schools'] = $this->db->where('status', 1)->count_all_results('schools');
            $data['total_users'] = $this->db->where('status', 1)->count_all_results('users');
            $data['total_courses'] = $this->db->count_all_results('courses');

            // New metrics for Super Admin dashboard
            $data['total_students'] = $this->db->join('users u', 'u.id = s.user_id')->where('u.status', 1)->count_all_results('students s');
            $data['active_sessions'] = $this->db->where('login_time >=', date('Y-m-d H:i:s', strtotime('-30 minutes')))->count_all_results('attendance');

            // School type distribution
            $data['school_types'] = array(
                'deped' => $this->db->where('type', 'deped')->where('status', 1)->count_all_results('schools'),
                'ched' => $this->db->where('type', 'ched')->where('status', 1)->count_all_results('schools'),
                'tesda' => $this->db->where('type', 'tesda')->where('status', 1)->count_all_results('schools')
            );

            // Recent schools
            $data['recent_schools'] = $this->School_model->get_all(10, 0);

            // All schools for switcher
            $data['all_schools'] = $this->School_model->get_all();

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

        // Teachers get their own dashboard
        if ($this->role_slug === 'teacher') {
            $this->Academic_model->ensure_subject_teachers_table();
            $subjects = $this->Academic_model->get_subjects_by_teacher_user($this->current_user->id);

            $total_sections = 0;
            $total_students = 0;
            foreach ($subjects as &$s) {
                $sections          = $this->Academic_model->get_subject_sections($s->id);
                $s->section_list   = $sections;
                $s->section_count  = count($sections);
                $total_sections   += $s->section_count;
                $s->student_count  = 0;
                foreach ($sections as $sec) {
                    $cnt = count($this->Academic_model->get_section_students($sec->id));
                    $s->student_count += $cnt;
                    $total_students   += $cnt;
                }
            }
            unset($s);

            $hour = (int) date('G');
            if ($hour < 12)     $greeting = 'Good morning';
            elseif ($hour < 18) $greeting = 'Good afternoon';
            else                $greeting = 'Good evening';

            $data['title']           = 'Teacher Dashboard';
            $data['greeting']        = $greeting;
            $data['subjects']        = $subjects;
            $data['total_sections']  = $total_sections;
            $data['total_students']  = $total_students;
            $data['school_year']     = $this->Academic_model->get_active_school_year($this->school_id);
            $data['is_teacher_view'] = true;
            $data['is_platform_view'] = false;
            $this->render('dashboard/index', $data);
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
        $data['total_subjects'] = count($this->Academic_model->get_subjects(array('school_id' => $this->school_id)));
        $data['grade_levels'] = $this->Academic_model->get_grade_levels(null, $this->school_id);
        $data['programs'] = $this->Academic_model->get_programs($this->school_id);
        $data['is_platform_view'] = false;

        $this->render('dashboard/index', $data);
    }
}
