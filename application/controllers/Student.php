<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model('Student_model');
        $this->load->model('Lesson_model');
        $this->load->model('Quiz_model');
    }

    private function get_or_create_student($user_id)
    {
        $student = $this->Student_model->get_student_by_user_id($user_id);
        
        $user = $this->db->where('id', $user_id)->get('users')->row();
        $school_id = $user->school_id ?: 1;
        
        if (!$student) {
            // Use the user's school_id, default to 1 if not set
            $student_id = $this->Student_model->create_student($user_id, $school_id);
            $student = $this->Student_model->get_student($student_id);
        } else {
            // Update student's school_id to match user's school_id if different
            if ($student->school_id != $school_id) {
                $this->db->where('id', $student->id)->update('students', array('school_id' => $school_id));
                $student->school_id = $school_id;
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

        $enrolled_subjects = $this->Student_model->get_enrolled_subjects($student->id);
        $enrolled_ids = array();
        foreach ($enrolled_subjects as $subject) {
            $enrolled_ids[] = (int) $subject->id;
        }

        $available_subjects = array();
        foreach ($subjects as $subject) {
            if (!in_array((int) $subject->id, $enrolled_ids, true)) {
                $available_subjects[] = $subject;
            }
        }
        
        $data['title'] = 'Student Dashboard';
        $data['subjects'] = $subjects;
        $data['enrolled_subjects'] = $enrolled_subjects;
        $data['available_subjects'] = $available_subjects;
        
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

        // Get enrolled subjects
        $enrolled_subjects = $this->Student_model->get_enrolled_subjects($student->id);

        // Group subjects
        $grouped = array('General' => array(
            'program_code' => 'General',
            'program_name' => 'All Subjects',
            'subjects' => $subjects
        ));

        $data['title'] = 'Subjects';
        $data['subjects'] = $grouped;
        $data['enrolled_subjects'] = $enrolled_subjects;
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

        if ($this->Student_model->is_subject_enrolled($student->id, $subject_id)) {
            redirect('student/content/' . $subject_id);
            return;
        }
        
        if ($this->input->method() === 'post') {
            $enrollment_key = $this->input->post('enrollment_key', TRUE);
            
            // Get sections for this subject to check enrollment keys
            $this->load->model('Academic_model');
            $sections = $this->Academic_model->get_subject_sections($subject_id);
            
            $requires_key = false;
            $key_valid = false;
            
            foreach ($sections as $section) {
                if (!empty($section->enrollment_key)) {
                    $requires_key = true;
                    if ($enrollment_key === $section->enrollment_key) {
                        $key_valid = true;
                        break;
                    }
                }
            }
            
            // If enrollment key is required but not provided or invalid
            if ($requires_key && !$key_valid) {
                $this->session->set_flashdata('error', 'Invalid enrollment key. Please contact your instructor for the correct key.');
                redirect('student/enroll/' . $subject_id);
                return;
            }

            // Create enrollment record
            $existing_enrollment = $this->db->where('user_id', $user_id)
                                             ->where('course_id', $subject_id)
                                             ->where('role', 'student')
                                             ->get('course_enrollments')
                                             ->row();

            if (!$existing_enrollment) {
                $this->db->insert('course_enrollments', array(
                    'user_id' => $user_id,
                    'course_id' => $subject_id,
                    'role' => 'student',
                    'status' => 'active'
                ));
            } elseif ($existing_enrollment->status !== 'active') {
                $this->db->where('id', $existing_enrollment->id)
                         ->update('course_enrollments', array('status' => 'active'));
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

        if (!$this->Student_model->is_subject_enrolled($student->id, $subject_id)) {
            $this->session->set_flashdata('error', 'You need to enroll in this course first.');
            redirect('student/enroll/' . $subject_id);
            return;
        }

        // Log course access
        $this->db->insert('activity_logs', array(
            'user_id' => $user_id,
            'action' => 'view_course',
            'module' => 'student',
            'description' => 'Viewed course content for subject ID: ' . $subject_id,
            'ip_address' => $this->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s')
        ));

        // Get modules for this subject
        $modules = $this->Student_model->get_modules_by_subject($subject_id);
        
        // Debug: Check module order
        log_message('debug', 'Modules for subject ' . $subject_id . ': ' . json_encode($modules));
        
        foreach ($modules as $key => &$module) {
            $module->lessons = $this->Student_model->get_lessons($module->id);
            $module->activities = $this->Student_model->get_activities($module->id);
            foreach ($module->activities as $activity_key => &$activity) {
                if ($activity->type !== 'quiz') {
                    continue;
                }

                $activity->quiz = $this->Quiz_model->get_quiz_by_activity($activity->id);
                if (!$activity->quiz || empty($activity->quiz->is_published)) {
                    unset($module->activities[$activity_key]);
                    continue;
                }

                $activity->question_count = $this->Quiz_model->count_questions($activity->quiz->id);
            }
            unset($activity);
            $module->activities = array_values($module->activities);
        }
        unset($module);
        
        // Get lesson completions using the same published lesson set shown on this page.
        $ordered_lessons = $this->Student_model->get_ordered_lessons_by_subject($subject_id);
        $ordered_lesson_ids = array_map(function($lesson) {
            return (int) $lesson->id;
        }, $ordered_lessons);
        $completed_lesson_ids = $this->Student_model->get_completed_lesson_ids($student->id, $subject_id);
        $completed_lesson_ids = array_values(array_intersect($ordered_lesson_ids, array_map('intval', $completed_lesson_ids)));
        $total_lessons = count($ordered_lesson_ids);
        $progress_percent = $total_lessons > 0 
            ? round((count($completed_lesson_ids) / $total_lessons) * 100) 
            : 0;

        $accessible_lesson_ids = array();
        foreach ($ordered_lesson_ids as $lesson_id) {
            $accessible_lesson_ids[] = $lesson_id;
            if (!in_array($lesson_id, $completed_lesson_ids, true)) {
                break;
            }
        }
        
        $data['title'] = $subject->code . ' - ' . $subject->description;
        $data['subject'] = $subject;
        $data['modules'] = $modules;
        $data['completed_lesson_ids'] = $completed_lesson_ids;
        $data['accessible_lesson_ids'] = $accessible_lesson_ids;
        $data['total_lessons'] = $total_lessons;
        $data['progress_percent'] = max(0, min(100, $progress_percent));
        
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
        
        if (!$this->Student_model->is_subject_enrolled($student->id, $subject_id)) {
            $this->session->set_flashdata('error', 'You need to enroll in this course first.');
            redirect('student/enroll/' . $subject_id);
            return;
        }

        $ordered_lessons = $this->Student_model->get_ordered_lessons_by_subject($subject_id);
        $lesson_ids = array_map(function($lesson) {
            return (int) $lesson->id;
        }, $ordered_lessons);
        $lesson_index = array_search((int) $lesson_id, $lesson_ids, true);

        if ($lesson_index === false) {
            show_404();
        }

        $lesson = $ordered_lessons[$lesson_index];
        $completed_lesson_ids = $this->Student_model->get_completed_lesson_ids($student->id, $subject_id);
        $completed_lesson_ids = array_values(array_intersect($lesson_ids, array_map('intval', $completed_lesson_ids)));
        
        if ($lesson_index > 0) {
            $previous_lesson_id = (int) $lesson_ids[$lesson_index - 1];
            if (!in_array($previous_lesson_id, $completed_lesson_ids, true)) {
                $this->session->set_flashdata('error', 'You must complete the previous lesson first.');
                redirect('student/content/' . $subject_id);
                return;
            }
        }
        
        $is_completed = in_array((int) $lesson_id, $completed_lesson_ids, true);

        // Auto-mark lesson as complete when opened
        if (!$is_completed) {
            $this->Student_model->mark_lesson_completed($student->id, $lesson_id);
            $is_completed = true;
        }

        // Get previous and next lessons
        $previous_lesson = null;
        $next_lesson = null;

        if ($lesson_index > 0) {
            $previous_lesson = $ordered_lessons[$lesson_index - 1];
        }

        if ($lesson_index < count($ordered_lessons) - 1) {
            $next_lesson = $ordered_lessons[$lesson_index + 1];
        }

        $data['title'] = $lesson->title;
        $data['subject'] = $subject;
        $data['lesson'] = $lesson;
        $data['is_completed'] = $is_completed;
        $data['previous_lesson'] = $previous_lesson;
        $data['next_lesson'] = $next_lesson;

        $this->render('student/lesson', $data);
    }

    public function mark_lesson($subject_id, $lesson_id)
    {
        $this->require_student();
        $this->output->set_content_type('application/json');
        
        $user_id = $this->session->userdata('user_id');
        $student = $this->get_or_create_student($user_id);
        
        if (!$student) {
            $this->output->set_output(json_encode(array(
                'success' => false,
                'message' => 'Failed to get student profile. Please contact administrator.',
                'type' => 'error'
            )));
            return;
        }
        
        // Mark lesson as complete
        $this->Student_model->mark_lesson_completed($student->id, $lesson_id);
        notify_success('Lesson marked as complete.');
        
        $this->output->set_output(json_encode(array(
            'success' => true,
            'message' => 'Lesson marked as complete.',
            'type' => 'success'
        )));
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
        if ($this->role_slug !== 'student') {
            show_error('Student access required. You do not have permission to access this page.', 403);
            return;
        }
    }
}
