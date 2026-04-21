<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Student_model');
        $this->load->model('Lesson_model');
    }

    private function get_or_create_student($user_id)
    {
        $student = $this->Student_model->get_student_by_user_id($user_id);
        
        if (!$student) {
            $user = $this->db->where('id', $user_id)->get('users')->row();
            // Get the first school_id from subjects to ensure student can access content
            $subject_school = $this->db->select('school_id')->limit(1)->get('subjects')->row();
            $school_id = $subject_school ? $subject_school->school_id : ($user->school_id ?: 1);
            
            $student_id = $this->Student_model->create_student($user_id, $school_id);
            $student = $this->Student_model->get_student($student_id);
        } else {
            // Force update student's school_id to match the first subject's school_id
            $subject_school = $this->db->select('school_id')->limit(1)->get('subjects')->row();
            if ($subject_school) {
                $this->Student_model->update_student_school_id($student->id, $subject_school->school_id);
                $student->school_id = $subject_school->school_id;
            }
        }
        
        return $student;
    }

    public function index()
    {
        $this->require_student();
        
        $user_id = $this->session->userdata('user_id');
        $student = $this->get_or_create_student($user_id);
        
        if (!$student) {
            show_error('Failed to create student profile. Please contact administrator.', 500);
            return;
        }
        
        $subjects = $this->Student_model->get_subjects($student->id);
        foreach ($subjects as &$subject) {
            $subject->requires_key = false;
        }
        unset($subject);
        
        $data['title'] = 'Student Dashboard';
        $data['subjects'] = $subjects;
        $data['enrolled_subjects'] = array();
        $data['available_subjects'] = $subjects;
        
        $this->render('student/dashboard', $data);
    }

    public function subjects()
    {
        $this->require_student();
        
        $user_id = $this->session->userdata('user_id');
        $student = $this->get_or_create_student($user_id);
        
        if (!$student) {
            show_error('Failed to create student profile. Please contact administrator.', 500);
            return;
        }
        
        $filters = array();
        if ($this->input->get('system_type')) {
            $filters['system_type'] = $this->input->get('system_type');
        }
        
        $subjects = $this->Student_model->get_subjects($student->id, $filters);
        foreach ($subjects as &$subject) {
            $subject->requires_key = false;
        }
        unset($subject);
        
        // Group subjects
        $grouped = array('General' => array(
            'program_code' => 'General',
            'program_name' => 'All Subjects',
            'subjects' => $subjects
        ));
        
        $data['title'] = 'Subjects';
        $data['subjects'] = $grouped;
        $data['enrolled_subjects'] = array();
        $data['filter_type'] = $this->input->get('system_type');
        
        $this->render('student/subjects', $data);
    }

    public function enroll($subject_id)
    {
        $this->require_student();
        
        $user_id = $this->session->userdata('user_id');
        $student = $this->get_or_create_student($user_id);
        
        if (!$student) {
            show_error('Failed to create student profile. Please contact administrator.', 500);
            return;
        }
        
        $subject = $this->Student_model->get_subject($subject_id);
        
        if (!$subject) {
            show_404();
        }
        
        if ($this->input->method() === 'post') {
            $enrollment_key = $this->input->post('enrollment_key', TRUE);
            
            // For now, allow any enrollment since we don't have enrollment keys set up
            // Mark the first lesson as complete to indicate enrollment
            $modules = $this->Student_model->get_modules_by_subject($subject_id);
            if (!empty($modules)) {
                $lessons = $this->Student_model->get_lessons($modules[0]->id);
                if (!empty($lessons)) {
                    $this->Student_model->mark_lesson_completed($student->id, $lessons[0]->id);
                }
            }
            
            $this->session->set_flashdata('success', 'Successfully enrolled in ' . htmlspecialchars($subject->name));
            redirect('student/content/' . $subject_id);
        }
        
        $data['title'] = 'Enroll in Course';
        $data['subject'] = $subject;
        
        $this->render('student/enroll', $data);
    }

    public function content($subject_id = null)
    {
        $this->require_student();
        
        $user_id = $this->session->userdata('user_id');
        $student = $this->get_or_create_student($user_id);
        
        if (!$student) {
            show_error('Failed to create student profile. Please contact administrator.', 500);
            return;
        }
        
        if (!$subject_id) {
            redirect('student/subjects');
        }
        
        $subject = $this->Student_model->get_subject($subject_id);
        if (!$subject) {
            show_404();
        }
        
        // Get modules for this subject
        $modules = $this->Student_model->get_modules_by_subject($subject_id);
        foreach ($modules as $key => &$module) {
            $module->lessons = $this->Student_model->get_lessons($module->id);
        }
        unset($module);
        
        // Get lesson completions
        $completed_lesson_ids = $this->Student_model->get_completed_lesson_ids($student->id, $subject_id);
        $total_lessons = $this->Student_model->get_total_lessons($subject_id);
        $progress_percent = $total_lessons > 0 
            ? round((count($completed_lesson_ids) / $total_lessons) * 100) 
            : 0;
        
        $data['title'] = $subject->code . ' - ' . $subject->description;
        $data['subject'] = $subject;
        $data['modules'] = $modules;
        $data['completed_lesson_ids'] = $completed_lesson_ids;
        $data['progress_percent'] = $progress_percent;
        
        $this->render('student/content', $data);
    }

    public function lesson($subject_id, $lesson_id)
    {
        $this->require_student();
        
        $user_id = $this->session->userdata('user_id');
        $student = $this->get_or_create_student($user_id);
        
        if (!$student) {
            show_error('Failed to create student profile. Please contact administrator.', 500);
            return;
        }
        
        $subject = $this->Student_model->get_subject($subject_id);
        if (!$subject) {
            show_404();
        }
        
        // Check if student is enrolled (has any lesson completions for this subject)
        $completed_lesson_ids = $this->Student_model->get_completed_lesson_ids($student->id, $subject_id);
        if (empty($completed_lesson_ids)) {
            $this->session->set_flashdata('error', 'You need to enroll in this course first.');
            redirect('student/enroll/' . $subject_id);
        }
        
        // Get lesson details
        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) {
            show_404();
        }
        
        // Check if lesson is sequential and previous lesson is completed
        $module_lessons = $this->Student_model->get_lessons($lesson->module_id);
        $lesson_index = array_search($lesson_id, array_column($module_lessons, 'id'));
        
        if ($lesson_index > 0) {
            $previous_lesson_id = $module_lessons[$lesson_index - 1]->id;
            if (!in_array($previous_lesson_id, $completed_lesson_ids)) {
                $this->session->set_flashdata('error', 'You must complete the previous lesson first.');
                redirect('student/content/' . $subject_id);
            }
        }
        
        $is_completed = in_array($lesson_id, $completed_lesson_ids);
        
        $data['title'] = $lesson->title;
        $data['subject'] = $subject;
        $data['lesson'] = $lesson;
        $data['is_completed'] = $is_completed;
        
        $this->render('student/lesson', $data);
    }

    public function mark_lesson($subject_id, $lesson_id)
    {
        $this->require_student();
        
        $user_id = $this->session->userdata('user_id');
        $student = $this->get_or_create_student($user_id);
        
        if (!$student) {
            echo json_encode(array('success' => false));
            return;
        }
        
        // Mark lesson as complete
        $this->Student_model->mark_lesson_completed($student->id, $lesson_id);
        
        echo json_encode(array('success' => true));
    }

    public function unenroll($subject_id)
    {
        $this->require_student();
        
        $user_id = $this->session->userdata('user_id');
        $student = $this->get_or_create_student($user_id);
        
        if (!$student) {
            show_error('Failed to get student profile. Please contact administrator.', 500);
            return;
        }
        
        $subject = $this->Student_model->get_subject($subject_id);
        
        if (!$subject) {
            show_404();
        }
        
        if ($this->input->method() === 'post') {
            // Remove lesson completions for this subject
            $this->Student_model->remove_lesson_completions($student->id, $subject_id);
            
            $this->session->set_flashdata('success', 'Successfully unenrolled from ' . htmlspecialchars($subject->name));
            redirect('student/subjects');
        }
        
        $data['title'] = 'Unenroll from Course';
        $data['subject'] = $subject;
        
        $this->render('student/unenroll', $data);
    }

    private function require_student()
    {
        $role_slug = $this->session->userdata('role_slug');
        
        if ($role_slug !== 'student') {
            show_error('Student access required. You do not have permission to access this page.', 403);
            return;
        }
    }
}
