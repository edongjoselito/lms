<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    private function format_grade_level_label($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '—';
        }

        if (is_numeric($value)) {
            return 'Grade ' . str_pad((int) $value, 2, '0', STR_PAD_LEFT);
        }

        return $value;
    }

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
            $this->Academic_model->ensure_section_teachers_table_public();
            $subjects = $this->Academic_model->get_subjects_by_teacher_user($this->current_user->id);
            $school_year = $this->Academic_model->get_active_school_year($this->school_id);
            $advisory_sections = $this->Academic_model->get_advisory_sections_by_user(
                $this->current_user->id,
                $this->school_id,
                $school_year ? (int) $school_year->id : null
            );

            // Get sections where teacher is assigned (via section_teachers or as adviser)
            $staff = $this->db->where('user_id', (int) $this->current_user->id)->get('staff')->row();
            $assigned_section_ids = array();

            if ($staff) {
                // Get sections from section_teachers
                $section_teacher_rows = $this->db->select('section_id')
                    ->distinct()
                    ->where('staff_id', $staff->IDNumber)
                    ->get('section_teachers')
                    ->result();
                foreach ($section_teacher_rows as $row) {
                    $assigned_section_ids[] = (int) $row->section_id;
                }

                // Get sections where teacher is adviser
                $adviser_rows = $this->db->select('id')
                    ->where('adviser_id', (int) $this->current_user->id)
                    ->where('school_id', $this->school_id)
                    ->get('sections')
                    ->result();
                foreach ($adviser_rows as $row) {
                    $assigned_section_ids[] = (int) $row->id;
                }
            }

            $assigned_section_ids = array_unique($assigned_section_ids);

            $section_counts = array();
            $student_counts = array();
            $total_sections = count($assigned_section_ids);
            $total_students = 0;

            foreach ($advisory_sections as $advisory_section) {
                $grade_level_value = '';
                if (isset($advisory_section->grade_level_name) && trim((string) $advisory_section->grade_level_name) !== '') {
                    $grade_level_value = trim((string) $advisory_section->grade_level_name);
                } elseif (isset($advisory_section->year_level) && trim((string) $advisory_section->year_level) !== '') {
                    $grade_level_value = trim((string) $advisory_section->year_level);
                }

                $advisory_section->grade_level_label = $this->format_grade_level_label($grade_level_value);
                $advisory_section->student_count = count($this->Academic_model->get_section_students($advisory_section->id));
            }

            // Count sections by year level (only assigned sections)
            if (!empty($assigned_section_ids)) {
                $this->db->select('year_level, COUNT(*) AS total_sections', FALSE)
                    ->from('sections')
                    ->where('school_id', $this->school_id)
                    ->where_in('id', $assigned_section_ids);
                if ($school_year && $this->db->field_exists('school_year_id', 'sections')) {
                    $this->db->where('school_year_id', $school_year->id);
                }
                $section_rows = $this->db->group_by('year_level')
                    ->get()
                    ->result();
                foreach ($section_rows as $row) {
                    $section_counts[(string) $row->year_level] = (int) $row->total_sections;
                }

                $enrollment_join = 'enrollments.section_id = sections.id AND enrollments.status = "enrolled"';
                if ($school_year && $this->db->field_exists('school_year_id', 'enrollments')) {
                    $enrollment_join .= ' AND enrollments.school_year_id = ' . (int) $school_year->id;
                }

                $student_rows = $this->db->select('sections.year_level, COUNT(enrollments.id) AS total_students', FALSE)
                    ->from('sections')
                    ->join('enrollments', $enrollment_join, 'left')
                    ->where('sections.school_id', $this->school_id)
                    ->where_in('sections.id', $assigned_section_ids);
                if ($school_year && $this->db->field_exists('school_year_id', 'sections')) {
                    $this->db->where('sections.school_year_id', $school_year->id);
                }
                $student_rows = $this->db->group_by('sections.year_level')
                    ->get()
                    ->result();
                foreach ($student_rows as $row) {
                    $student_counts[(string) $row->year_level] = (int) $row->total_students;
                }

                $this->db->from('enrollments')
                    ->where_in('section_id', $assigned_section_ids)
                    ->group_start()
                        ->where('status', 'enrolled')
                        ->or_where('status', 1)
                    ->group_end();
                if ($school_year && $this->db->field_exists('school_year_id', 'enrollments')) {
                    $this->db->where('school_year_id', $school_year->id);
                }
                $total_students = (int) $this->db->count_all_results();
            }

            foreach ($subjects as &$s) {
                $grade_level_value = '';
                if (isset($s->program_year_level) && trim((string) $s->program_year_level) !== '') {
                    $grade_level_value = trim((string) $s->program_year_level);
                } elseif (isset($s->year_level) && trim((string) $s->year_level) !== '') {
                    $grade_level_value = trim((string) $s->year_level);
                }

                $s->grade_level_label = $this->format_grade_level_label($grade_level_value);
                $s->section_count = isset($section_counts[$grade_level_value]) ? $section_counts[$grade_level_value] : 0;
                $s->student_count = isset($student_counts[$grade_level_value]) ? $student_counts[$grade_level_value] : 0;
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
            $data['advisory_sections'] = $advisory_sections;
            $data['school_year']     = $school_year;
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
