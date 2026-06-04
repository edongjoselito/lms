<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student extends MY_Controller
{

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

    private function get_student_current_level($student)
    {
        $level = array(
            'grade_level_id' => null,
            'year_level' => null,
        );

        if (!$student) {
            return $level;
        }

        $enrollment_student_ids = array();
        if (!empty($student->id)) {
            $enrollment_student_ids[] = (int) $student->id;
        }
        if (!empty($student->user_id)) {
            $enrollment_student_ids[] = (int) $student->user_id;
        }
        $enrollment_student_ids = array_values(array_unique($enrollment_student_ids));

        $enrollment = null;
        if (!empty($enrollment_student_ids)) {
            $enrollment = $this->db->select('e.grade_level_id, e.year_level, sections.year_level as section_year_level, programs.year_level as program_year_level')
                ->from('enrollments e')
                ->join('sections', 'sections.id = e.section_id', 'left')
                ->join('programs', 'programs.id = e.program_id', 'left')
                ->where_in('e.student_id', $enrollment_student_ids)
                ->where('e.status', 'enrolled')
                ->order_by('e.enrollment_date', 'DESC')
                ->order_by('e.id', 'DESC')
                ->limit(1);

            if (!empty($student->school_id)) {
                $this->db->where('e.school_id', (int) $student->school_id);
            }

            $enrollment = $this->db->get()->row();
        }

        if ($enrollment) {
            if (!empty($enrollment->grade_level_id)) {
                $level['grade_level_id'] = (int) $enrollment->grade_level_id;
            }
            if (isset($enrollment->year_level) && $enrollment->year_level !== null && $enrollment->year_level !== '') {
                $level['year_level'] = (string) $enrollment->year_level;
            } elseif (isset($enrollment->section_year_level) && $enrollment->section_year_level !== null && $enrollment->section_year_level !== '') {
                $level['year_level'] = (string) $enrollment->section_year_level;
            } elseif (isset($enrollment->program_year_level) && $enrollment->program_year_level !== null && $enrollment->program_year_level !== '') {
                $level['year_level'] = (string) $enrollment->program_year_level;
            }
        }

        if (empty($level['grade_level_id']) && !empty($student->grade_level_id)) {
            $level['grade_level_id'] = (int) $student->grade_level_id;
        }
        if (($level['year_level'] === null || $level['year_level'] === '') && isset($student->year_level) && $student->year_level !== null && $student->year_level !== '') {
            $level['year_level'] = (string) $student->year_level;
        }

        return $level;
    }

    private function get_subject_year_level($subject)
    {
        if (!$subject) {
            return null;
        }

        if (isset($subject->year_level) && $subject->year_level !== null && $subject->year_level !== '') {
            return (string) $subject->year_level;
        }

        if (empty($subject->program_id)) {
            return null;
        }

        $check_year_level = $this->db->query("SHOW COLUMNS FROM programs LIKE 'year_level'")->num_rows();
        if ($check_year_level === 0) {
            return null;
        }

        $program = $this->db->select('year_level')
            ->where('id', (int) $subject->program_id)
            ->get('programs')
            ->row();

        if ($program && isset($program->year_level) && $program->year_level !== null && $program->year_level !== '') {
            return (string) $program->year_level;
        }

        return null;
    }

    private function can_auto_enroll_subject($student, $subject)
    {
        if (!$student || !$subject) {
            return false;
        }

        if (!empty($subject->school_id) && !empty($student->school_id) && (int) $subject->school_id !== (int) $student->school_id) {
            return false;
        }

        $student_level = $this->get_student_current_level($student);

        if (!empty($subject->grade_level_id) && !empty($student_level['grade_level_id']) && (int) $subject->grade_level_id === (int) $student_level['grade_level_id']) {
            return true;
        }

        $subject_year_level = $this->get_subject_year_level($subject);
        if ($subject_year_level !== null && $subject_year_level !== '' && $student_level['year_level'] !== null && $student_level['year_level'] !== '') {
            return (string) $subject_year_level === (string) $student_level['year_level'];
        }

        return false;
    }

    private function ensure_student_course_enrollment($student, $subject_id)
    {
        if (!$student || !$subject_id) {
            return false;
        }

        $existing_enrollment = $this->db->where('user_id', $student->user_id)
            ->where('course_id', $subject_id)
            ->where('role', 'student')
            ->get('course_enrollments')
            ->row();

        if (!$existing_enrollment) {
            $this->db->insert('course_enrollments', array(
                'user_id' => $student->user_id,
                'course_id' => $subject_id,
                'role' => 'student',
                'status' => 'active',
                'enrolled_at' => date('Y-m-d H:i:s')
            ));
            return true;
        }

        if ($existing_enrollment->status !== 'active') {
            $this->db->where('id', $existing_enrollment->id)
                ->update('course_enrollments', array(
                    'status' => 'active',
                    'enrolled_at' => date('Y-m-d H:i:s')
                ));
        }

        return true;
    }

    private function auto_enroll_student_in_subject_if_eligible($student, $subject)
    {
        if (!$this->can_auto_enroll_subject($student, $subject)) {
            return false;
        }

        return $this->ensure_student_course_enrollment($student, (int) $subject->id);
    }

    private function auto_enroll_student_subjects_for_current_level($student)
    {
        if (!$student) {
            return;
        }

        $student_level = $this->get_student_current_level($student);
        if (empty($student_level['grade_level_id']) && ($student_level['year_level'] === null || $student_level['year_level'] === '')) {
            return;
        }

        $school_subjects = $this->db->where('school_id', $student->school_id)
            ->where('status', 1)
            ->get('subjects')
            ->result();

        foreach ($school_subjects as $subject) {
            $this->auto_enroll_student_in_subject_if_eligible($student, $subject);
        }
    }

    private function subject_requires_enrollment_key($subject_id)
    {
        $this->load->model('Academic_model');
        return $this->Academic_model->subject_has_enrollment_keys((int) $subject_id);
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

        $this->auto_enroll_student_subjects_for_current_level($student);

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

        $student_level = $this->get_student_current_level($student);
        $year_level = $student_level['year_level'];

        // Auto-enroll student in subjects that match their current grade level/year level.
        $this->auto_enroll_student_subjects_for_current_level($student);

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
        $data['year_level'] = $year_level;

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

        $subject_title = trim(
            !empty($subject->description)
                ? (string) $subject->description
                : (!empty($subject->name) ? (string) $subject->name : (string) $subject->code)
        );

        if ($this->Student_model->is_subject_enrolled($student->id, $subject_id)) {
            redirect('student/content/' . $subject_id);
            return;
        }

        if ($this->auto_enroll_student_in_subject_if_eligible($student, $subject)) {
            $this->session->set_flashdata('success', 'Automatically enrolled in ' . htmlspecialchars($subject_title) . ' based on your current grade level.');
            redirect('student/content/' . $subject_id);
            return;
        }

        if (!$this->subject_requires_enrollment_key($subject_id)) {
            $this->ensure_student_course_enrollment($student, $subject_id);
            $this->session->set_flashdata('success', 'Successfully enrolled in ' . htmlspecialchars($subject_title));
            redirect('student/content/' . $subject_id);
            return;
        }

        if ($this->input->method() === 'post') {
            $enrollment_key = trim($this->input->post('enrollment_key', TRUE));

            // Get sections for this subject to check enrollment keys
            $this->load->model('Academic_model');
            $sections = $this->Academic_model->get_subject_sections($subject_id);

            $requires_key = false;
            $key_valid = false;

            foreach ($sections as $section) {
                if (!empty($section->enrollment_key)) {
                    $requires_key = true;
                    if ($enrollment_key === trim($section->enrollment_key)) {
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
            $this->ensure_student_course_enrollment($student, $subject_id);

            $this->session->set_flashdata('success', 'Successfully enrolled in ' . htmlspecialchars($subject_title));
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

        if (
            !$this->Student_model->is_subject_enrolled($student->id, $subject_id)
            && !$this->auto_enroll_student_in_subject_if_eligible($student, $subject)
        ) {
            if (!$this->subject_requires_enrollment_key($subject_id)) {
                $this->ensure_student_course_enrollment($student, $subject_id);
            } else {
                $this->session->set_flashdata('error', 'You need to enroll in this course first.');
                redirect('student/enroll/' . $subject_id);
                return;
            }
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
                
                // Check if student has attempted this quiz
                $activity->attempts = $this->Quiz_model->get_student_attempts($activity->quiz->id, $user_id);
                $activity->has_attempt = !empty($activity->attempts);
                if ($activity->has_attempt) {
                    $activity->latest_attempt = $activity->attempts[0];
                }
            }
            unset($activity);
            $module->activities = array_values($module->activities);
        }
        unset($module);

        // Get lesson completions using the same published lesson set shown on this page.
        $ordered_lessons = $this->Student_model->get_ordered_lessons_by_subject($subject_id);
        $ordered_lesson_ids = array_map(function ($lesson) {
            return (int) $lesson->id;
        }, $ordered_lessons);
        $completed_lesson_ids = $this->Student_model->get_completed_lesson_ids($student->id, $subject_id);
        $completed_lesson_ids = array_values(array_intersect($ordered_lesson_ids, array_map('intval', $completed_lesson_ids)));
        $total_lessons = count($ordered_lesson_ids);
        $progress_percent = $total_lessons > 0
            ? round((count($completed_lesson_ids) / $total_lessons) * 100)
            : 0;

        // Build ordered list of all items (lessons and activities) for sequential access and progress
        $all_ordered_items = array();
        foreach ($modules as $module) {
            foreach ($module->lessons as $lesson) {
                $all_ordered_items[] = (object) array(
                    'id' => (int) $lesson->id,
                    'type' => 'lesson',
                    'is_completed' => in_array((int) $lesson->id, $completed_lesson_ids, true)
                );
            }
            foreach ($module->activities as $activity) {
                $all_ordered_items[] = (object) array(
                    'id' => (int) $activity->id,
                    'type' => $activity->type,
                    'is_completed' => isset($activity->has_attempt) && $activity->has_attempt
                );
            }
        }

        // Calculate progress based on all items (lessons + activities)
        $total_items = count($all_ordered_items);
        $completed_items = 0;
        foreach ($all_ordered_items as $item) {
            if ($item->is_completed) {
                $completed_items++;
            }
        }
        $progress_percent = $total_items > 0
            ? round(($completed_items / $total_items) * 100)
            : 0;

        // Build accessible items list - items are accessible until first incomplete item
        $accessible_item_ids = array();
        foreach ($all_ordered_items as $item) {
            $accessible_item_ids[] = $item->id;
            if (!$item->is_completed) {
                break;
            }
        }

        $data['title'] = $subject->code . ' - ' . $subject->description;
        $data['subject'] = $subject;
        $data['modules'] = $modules;
        $data['completed_lesson_ids'] = $completed_lesson_ids;
        $data['accessible_lesson_ids'] = $accessible_item_ids;
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
        $lesson_ids = array_map(function ($lesson) {
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
        $is_video_lesson = !empty($lesson->content_type) && $lesson->content_type === 'video';
        $is_pdf_lesson = !empty($lesson->content_type)
            && $lesson->content_type === 'file'
            && !empty($lesson->file_path)
            && preg_match('/\.pdf(\?.*)?$/i', (string) $lesson->file_path);

        // Auto-mark only plain lessons; video and PDF lessons must be completed in the viewer.
        if (!$is_completed && !$is_video_lesson && !$is_pdf_lesson) {
            $this->Student_model->mark_lesson_completed($student->id, $lesson_id);
            $is_completed = true;
            // Recalculate completed lessons after marking
            $completed_lesson_ids = $this->Student_model->get_completed_lesson_ids($student->id, $subject_id);
            $completed_lesson_ids = array_values(array_intersect($lesson_ids, array_map('intval', $completed_lesson_ids)));
        }

        // Calculate progress percentage
        $total_lessons = count($lesson_ids);
        $progress_percent = $total_lessons > 0
            ? round((count($completed_lesson_ids) / $total_lessons) * 100)
            : 0;

        // Build ordered list of all items (lessons and activities) for navigation
        $modules = $this->Student_model->get_modules_by_subject($subject_id);
        $all_ordered_items = array();
        foreach ($modules as $module) {
            $module->lessons = $this->Student_model->get_lessons($module->id);
            $module->activities = $this->Student_model->get_activities($module->id);
            
            foreach ($module->lessons as $l) {
                $all_ordered_items[] = (object) array(
                    'id' => (int) $l->id,
                    'type' => 'lesson',
                    'title' => $l->title,
                    'url' => site_url('student/lesson/' . $subject_id . '/' . $l->id)
                );
            }
            foreach ($module->activities as $activity) {
                $has_attempt = $this->Quiz_model->get_student_attempts($activity->quiz_id ?? 0, $this->session->userdata('user_id'));
                $url = $activity->type === 'quiz' 
                    ? (!empty($has_attempt) && isset($has_attempt[0]) 
                        ? site_url('course/assessment_result/' . $has_attempt[0]->id)
                        : site_url('course/assessment/' . $activity->id))
                    : site_url('course/activity/' . $activity->id);
                $all_ordered_items[] = (object) array(
                    'id' => (int) $activity->id,
                    'type' => $activity->type,
                    'title' => $activity->title,
                    'url' => $url
                );
            }
        }

        // Find current lesson position in all items
        $current_item_index = null;
        foreach ($all_ordered_items as $index => $item) {
            if ($item->type === 'lesson' && $item->id === (int) $lesson_id) {
                $current_item_index = $index;
                break;
            }
        }

        // Get previous and next items
        $previous_item = null;
        $next_item = null;

        if ($current_item_index !== null && $current_item_index > 0) {
            $previous_item = $all_ordered_items[$current_item_index - 1];
        }

        if ($current_item_index !== null && $current_item_index < count($all_ordered_items) - 1) {
            $next_item = $all_ordered_items[$current_item_index + 1];
        }

        $data['title'] = $lesson->title;
        $data['subject'] = $subject;
        $data['lesson'] = $lesson;
        $data['is_completed'] = $is_completed;
        $data['is_video_lesson'] = $is_video_lesson;
        $data['is_pdf_lesson'] = $is_pdf_lesson;
        $data['previous_item'] = $previous_item;
        $data['next_item'] = $next_item;
        $data['progress_percent'] = $progress_percent;
        $data['total_lessons'] = $total_lessons;
        $data['completed_lessons'] = count($completed_lesson_ids);

        $this->render('student/lesson', $data);
    }

    public function mark_lesson($subject_id, $lesson_id)
    {
        $this->require_student();
        $this->output->set_content_type('application/json');

        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Invalid request method.',
                'type' => 'error',
                'csrf_token_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            )));
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $student = $this->get_or_create_student($user_id);

        if (!$student) {
            $this->output->set_output(json_encode(array(
                'success' => false,
                'message' => 'Failed to get student profile. Please contact administrator.',
                'type' => 'error',
                'csrf_token_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            )));
            return;
        }

        $subject = $this->Student_model->get_subject($subject_id);
        if (!$subject) {
            $this->output->set_status_header(404)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Subject not found.',
                'type' => 'error',
                'csrf_token_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            )));
            return;
        }

        if (!$this->Student_model->is_subject_enrolled($student->id, $subject_id)) {
            $this->output->set_status_header(403)->set_output(json_encode(array(
                'success' => false,
                'message' => 'You need to enroll in this course first.',
                'type' => 'error',
                'csrf_token_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            )));
            return;
        }

        $ordered_lessons = $this->Student_model->get_ordered_lessons_by_subject($subject_id);
        $lesson_ids = array_map(function ($lesson) {
            return (int) $lesson->id;
        }, $ordered_lessons);
        $lesson_index = array_search((int) $lesson_id, $lesson_ids, true);

        if ($lesson_index === false) {
            $this->output->set_status_header(404)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Lesson not found.',
                'type' => 'error',
                'csrf_token_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            )));
            return;
        }

        $completed_lesson_ids = $this->Student_model->get_completed_lesson_ids($student->id, $subject_id);
        $completed_lesson_ids = array_values(array_intersect($lesson_ids, array_map('intval', $completed_lesson_ids)));

        if ($lesson_index > 0) {
            $previous_lesson_id = (int) $lesson_ids[$lesson_index - 1];
            if (!in_array($previous_lesson_id, $completed_lesson_ids, true)) {
                $this->output->set_status_header(422)->set_output(json_encode(array(
                    'success' => false,
                    'message' => 'You must complete the previous lesson first.',
                    'type' => 'error',
                    'csrf_token_name' => $this->security->get_csrf_token_name(),
                    'csrf_hash' => $this->security->get_csrf_hash()
                )));
                return;
            }
        }

        $lesson = $ordered_lessons[$lesson_index];
        $is_video_lesson = !empty($lesson->content_type) && $lesson->content_type === 'video';
        $is_pdf_lesson = !empty($lesson->content_type)
            && $lesson->content_type === 'file'
            && !empty($lesson->file_path)
            && preg_match('/\.pdf(\?.*)?$/i', (string) $lesson->file_path);
        $video_completed = $this->input->post('video_completed', TRUE);
        $pdf_scrolled = $this->input->post('pdf_scrolled', TRUE);

        if ($is_video_lesson && $video_completed !== '1') {
            $this->output->set_status_header(422)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Finish the video before completing this lesson.',
                'type' => 'error',
                'csrf_token_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            )));
            return;
        }

        if ($is_pdf_lesson && $pdf_scrolled !== '1') {
            $this->output->set_status_header(422)->set_output(json_encode(array(
                'success' => false,
                'message' => 'Scroll to the end of the PDF before completing this lesson.',
                'type' => 'error',
                'csrf_token_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            )));
            return;
        }

        // Mark lesson as complete
        $this->Student_model->mark_lesson_completed($student->id, $lesson_id);

        $updated_completed_lesson_ids = $this->Student_model->get_completed_lesson_ids($student->id, $subject_id);
        $updated_completed_lesson_ids = array_values(array_intersect($lesson_ids, array_map('intval', $updated_completed_lesson_ids)));
        $progress_percent = count($lesson_ids) > 0
            ? round((count($updated_completed_lesson_ids) / count($lesson_ids)) * 100)
            : 0;

        $this->output->set_output(json_encode(array(
            'success' => true,
            'message' => 'Lesson marked as complete.',
            'type' => 'success',
            'progress_percent' => $progress_percent,
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
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

        $subject_title = trim(
            !empty($subject->description)
                ? (string) $subject->description
                : (!empty($subject->name) ? (string) $subject->name : (string) $subject->code)
        );

        if ($this->input->method() === 'post') {
            // Remove lesson completions for this subject
            $this->Student_model->remove_lesson_completions($student->id, $subject_id);

            // Remove enrollment from course_enrollments
            $this->db->where('user_id', $student->user_id);
            $this->db->where('course_id', $subject_id);
            $this->db->delete('course_enrollments');

            $this->session->set_flashdata('success', 'Successfully unenrolled from ' . htmlspecialchars($subject_title));
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
