<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends MY_Controller {

    private $activity_progress_table_exists = null;
    private $current_student_record_loaded = false;
    private $current_student_record = null;
    private $teacher_year_levels = null;
    private $school_year_levels = null;
    private $module_owner_role_slugs = array();
    private $module_owner_names = array();

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        if (!in_array($this->role_slug, array('student', 'course_creator', 'super_admin', 'school_admin', 'teacher')) &&
            !($this->is_student_mode && in_array($this->original_role_slug, array('course_creator', 'teacher')))) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->load->model(array('Academic_model', 'User_model', 'Lesson_model', 'Student_model', 'Quiz_model'));
    }

    private function is_student_content_view()
    {
        return $this->role_slug === 'student' || $this->is_student_mode;
    }

    private function should_filter_unpublished_content()
    {
        return $this->is_student_content_view() && $this->original_role_slug === 'student';
    }

    private function can_manage_course_content($subject_id = null)
    {
        if (in_array($this->original_role_slug, array('course_creator', 'super_admin', 'school_admin'))) {
            return true;
        }
        return false;
    }

    private function require_course_manager($subject_id = null)
    {
        if ($this->can_manage_course_content($subject_id)) {
            return;
        }
        show_error('You do not have permission to manage course content.', 403);
    }

    private function can_access_subject_content_page($subject)
    {
        if (!$subject) {
            return false;
        }

        if ($this->is_student_content_view()) {
            return true;
        }

        if (in_array($this->original_role_slug, array('super_admin', 'course_creator'))) {
            return true;
        }

        if ($this->original_role_slug === 'school_admin') {
            $subject_school_id = (int) $subject->school_id;
            return $subject_school_id === 0 || $subject_school_id === (int) $this->school_id;
        }

        if ($this->original_role_slug === 'teacher') {
            $subject_school_id = (int) $subject->school_id;
            if ($subject_school_id !== 0 && $subject_school_id !== (int) $this->school_id) {
                return false;
            }

            if ($this->is_teacher_for_subject($subject->id)) {
                return true;
            }

            $year_level = $this->get_subject_year_level($subject);
            if ($year_level === '') {
                return false;
            }

            $teacher_year_levels = $this->get_teacher_accessible_year_levels();
            return isset($teacher_year_levels[$year_level]);
        }

        return false;
    }

    private function get_subject_year_level($subject)
    {
        if (!$subject) {
            return '';
        }

        if (isset($subject->year_level) && $subject->year_level !== null && $subject->year_level !== '') {
            return trim((string) $subject->year_level);
        }

        if (isset($subject->program_year_level) && $subject->program_year_level !== null && $subject->program_year_level !== '') {
            return trim((string) $subject->program_year_level);
        }

        return '';
    }

    private function can_manage_module_content($module)
    {
        if (!$module) {
            return false;
        }

        if (in_array($this->original_role_slug, array('super_admin', 'course_creator'))) {
            return true;
        }

        if ($this->original_role_slug !== 'school_admin' || !$this->current_user) {
            return false;
        }

        return (int) $module->created_by === (int) $this->current_user->id;
    }

    private function require_module_owner($module, $message = 'You can only manage content that you created.')
    {
        if ($this->can_manage_module_content($module)) {
            return;
        }

        show_error($message, 403);
    }

    private function can_reorder_subject_modules($subject_id)
    {
        if (!$this->can_manage_course_content($subject_id)) {
            return false;
        }

        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject || !$this->can_access_subject_content_page($subject)) {
            return false;
        }

        return true;
    }

    private function get_teacher_accessible_year_levels()
    {
        if ($this->teacher_year_levels !== null) {
            return $this->teacher_year_levels;
        }

        $this->teacher_year_levels = array();
        if ($this->original_role_slug !== 'teacher' || !$this->current_user) {
            return $this->teacher_year_levels;
        }

        $subjects = $this->Academic_model->get_subjects_by_teacher_user($this->current_user->id);
        foreach ($subjects as $subject) {
            $year_level = $this->get_subject_year_level($subject);

            if ($year_level !== '') {
                $this->teacher_year_levels[$year_level] = $year_level;
            }
        }

        return $this->teacher_year_levels;
    }

    private function school_has_subject_year_level($year_level)
    {
        $year_level = (string) $year_level;
        if ($year_level === '' || !$this->school_id) {
            return false;
        }

        if ($this->school_year_levels === null) {
            $this->school_year_levels = array();
            $rows = $this->db->distinct()
                ->select('year_level')
                ->where('school_id', (int) $this->school_id)
                ->where('status', 1)
                ->where('year_level IS NOT NULL', null, false)
                ->get('subjects')
                ->result();

            foreach ($rows as $row) {
                if (isset($row->year_level) && $row->year_level !== null && $row->year_level !== '') {
                    $this->school_year_levels[(string) $row->year_level] = (string) $row->year_level;
                }
            }
        }

        return isset($this->school_year_levels[$year_level]);
    }

    private function get_module_owner_role_slug($module)
    {
        $module_id = $module ? (int) $module->id : 0;
        if ($module_id < 1) {
            return '';
        }

        if (array_key_exists($module_id, $this->module_owner_role_slugs)) {
            return $this->module_owner_role_slugs[$module_id];
        }

        $slug = '';
        if (!empty($module->created_by)) {
            $owner = $this->db->select('roles.slug')
                ->from('users')
                ->join('roles', 'roles.id = users.role_id')
                ->where('users.id', (int) $module->created_by)
                ->get()
                ->row();
            $slug = $owner ? (string) $owner->slug : '';
        }

        $this->module_owner_role_slugs[$module_id] = $slug;
        return $slug;
    }

    private function get_module_owner_name($module)
    {
        $module_id = $module ? (int) $module->id : 0;
        if ($module_id < 1) {
            return '';
        }

        if (array_key_exists($module_id, $this->module_owner_names)) {
            return $this->module_owner_names[$module_id];
        }

        $name = '';
        if (!empty($module->created_by)) {
            $owner = $this->db->select('CONCAT(TRIM(COALESCE(first_name, "")), " ", TRIM(COALESCE(last_name, ""))) AS full_name', false)
                ->from('users')
                ->where('id', (int) $module->created_by)
                ->get()
                ->row();

            $name = $owner ? trim((string) $owner->full_name) : '';
        }

        $this->module_owner_names[$module_id] = $name;
        return $name;
    }

    private function can_access_shared_grade_level_lesson($subject, $module)
    {
        if (!$subject || !$module) {
            return false;
        }

        $year_level = $this->get_subject_year_level($subject);
        if ($year_level === '' || empty($module->is_published)) {
            return false;
        }

        if ($this->get_module_owner_role_slug($module) !== 'school_admin') {
            return false;
        }

        if (in_array($this->original_role_slug, array('super_admin', 'course_creator'))) {
            return true;
        }

        if ($this->original_role_slug === 'school_admin') {
            return $this->school_has_subject_year_level($year_level);
        }

        if ($this->original_role_slug === 'teacher') {
            $teacher_year_levels = $this->get_teacher_accessible_year_levels();
            return isset($teacher_year_levels[$year_level]);
        }

        return false;
    }

    private function get_shared_grade_level_modules($subject)
    {
        $year_level = $this->get_subject_year_level($subject);

        if ($this->is_student_content_view() || !$subject || $year_level === '') {
            return array();
        }

        if (!in_array($this->original_role_slug, array('school_admin', 'teacher', 'super_admin', 'course_creator'))) {
            return array();
        }

        $rows = $this->Lesson_model->get_shared_grade_level_lessons($subject->id, $year_level, $this->school_id);
        if (empty($rows)) {
            return array();
        }

        $modules = array();
        foreach ($rows as $row) {
            $module_id = (int) $row->shared_module_id;
            if (!isset($modules[$module_id])) {
                $modules[$module_id] = (object) array(
                    'id' => $module_id,
                    'title' => $row->module_title,
                    'description' => $row->module_description,
                    'is_published' => 1,
                    'created_by' => $row->module_created_by,
                    'can_manage' => false,
                    'is_shared' => true,
                    'owner_name' => $row->owner_name,
                    'source_subject_id' => (int) $row->source_subject_id,
                    'source_subject_code' => $row->source_subject_code,
                    'source_subject_description' => $row->source_subject_description,
                    'source_school_name' => $row->source_school_name,
                    'lessons' => array(),
                    'activities' => array(),
                );
            }

            $row->item_type = 'lesson';
            $row->can_manage = false;
            $row->is_shared = true;
            $modules[$module_id]->lessons[] = $row;
        }

        return array_values($modules);
    }

    private function require_section_manager($subject_id)
    {
        if (in_array($this->original_role_slug, array('course_creator', 'super_admin', 'school_admin'))) {
            return;
        }
        if ($this->original_role_slug === 'teacher' && $this->is_teacher_for_subject($subject_id)) {
            return;
        }
        show_error('You do not have permission to manage sections.', 403);
    }

    private function is_teacher_for_subject($subject_id)
    {
        if ($this->original_role_slug !== 'teacher' || !$this->current_user) {
            return false;
        }
        $this->Academic_model->ensure_subject_teachers_table();
        $row = $this->db->where('subject_id', (int)$subject_id)
                        ->where('user_id', (int)$this->current_user->id)
                        ->get('subject_teachers')->row();
        return (bool)$row;
    }

    private function get_subject_access_session()
    {
        return $this->session->userdata('subject_content_access') ?: array();
    }

    private function set_subject_access($subject_id, $class_program_id = null)
    {
        $access = $this->get_subject_access_session();
        $access[(int) $subject_id] = $class_program_id ? (int) $class_program_id : true;
        $this->session->set_userdata('subject_content_access', $access);
    }

    private function has_subject_access($subject_id)
    {
        if ($this->original_role_slug !== 'student') {
            return true;
        }

        if ($this->current_user) {
            $course_enrollment = $this->db->where('user_id', $this->current_user->id)
                                          ->where('course_id', $subject_id)
                                          ->where('role', 'student')
                                          ->where('status', 'active')
                                          ->count_all_results('course_enrollments');
            if ($course_enrollment > 0) {
                return true;
            }

            $student = $this->Student_model->get_student_by_user_id($this->current_user->id);
            if ($student && $this->Student_model->is_subject_enrolled($student->id, $subject_id)) {
                return true;
            }
        }

        if (!$this->Academic_model->subject_has_enrollment_keys($subject_id)) {
            return true;
        }

        $access = $this->get_subject_access_session();
        return !empty($access[(int) $subject_id]);
    }

    public function index()
    {
        $this->require_course_manager();
        $data['title'] = 'Course Creator Dashboard';
        
        // Get all subjects for the school
        $this->school_filter(null, 'subjects');
        $data['subjects'] = $this->Academic_model->get_subjects();
        
        // Get grade levels and programs
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();
        
        // Get counts
        $data['total_subjects'] = count($data['subjects']);
        $data['total_grade_levels'] = count($data['grade_levels']);
        $data['total_programs'] = count($data['programs']);
        
        $this->render('course/dashboard', $data);
    }

    public function subjects()
    {
        $this->require_course_manager();
        redirect('subjects');
    }

    public function teacher_subjects()
    {
        if ($this->original_role_slug !== 'teacher') {
            show_error('Access denied.', 403);
        }
        $this->Academic_model->ensure_subject_teacher_column();
        $subjects = $this->Academic_model->get_subjects_by_teacher_user($this->current_user->id);
        $data['title']    = 'My Subjects';
        $data['subjects'] = $subjects;
        $this->render('course/teacher_subjects', $data);
    }

    public function content($subject_id = null)
    {
        if (!$subject_id) {
            redirect('subjects');
        }
        
        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject) {
            show_404();
        }

        $student_content_view = $this->is_student_content_view();
        $filter_unpublished = $this->should_filter_unpublished_content();

        if (!$student_content_view && !$this->can_access_subject_content_page($subject)) {
            show_error('You do not have permission to view this course content.', 403);
        }
        

        $teacher_section_filter = null;
        if ($this->original_role_slug === 'teacher' && $this->current_user) {
            $teacher_section_filter = (int) $this->current_user->id;
        }
        // Prefer explicit subject-to-section assignments; fall back to program/year-level sections for compatibility.
        $subject_sections = array();
        if (!$student_content_view) {
            $subject_sections = $this->Academic_model->get_subject_sections($subject_id);

            // Restrict non-super-admin viewers to sections from their own school.
            if ($this->original_role_slug !== 'super_admin' && !empty($this->school_id)) {
                $current_school_id = (int) $this->school_id;
                $subject_sections = array_values(array_filter($subject_sections, function ($section) use ($current_school_id) {
                    $section_school_id = isset($section->section_school_id) ? (int) $section->section_school_id : 0;
                    return $section_school_id === $current_school_id;
                }));
            }
        }
        if (empty($subject_sections) && !empty($subject->program_id)) {
            $subject_sections = $this->Academic_model->get_sections_by_program($subject->program_id, $this->school_id);
        }
        if (empty($subject_sections) && !empty($subject->year_level)) {
            $subject_sections = $this->Academic_model->get_sections_by_year_level($subject->year_level, $this->school_id);
        }
        $requires_enrollment_key = $this->Academic_model->subject_has_enrollment_keys($subject_id);
        $has_subject_access = $this->has_subject_access($subject_id);
        
        // Get modules for this subject with lessons and activities
        $modules = $has_subject_access ? $this->Lesson_model->get_modules_by_subject($subject_id) : array();
        foreach ($modules as $key => &$module) {
            if ($filter_unpublished && !$module->is_published) {
                unset($modules[$key]);
                continue;
            }

            $module->can_manage = !$student_content_view && $this->can_manage_module_content($module);
            $module->is_shared = false;
            $module->owner_name = $this->get_module_owner_name($module);

            $module->lessons = $this->Lesson_model->get_lessons($module->id);
            $module->activities = $this->Lesson_model->get_activities($module->id);

            if ($filter_unpublished) {
                $module->lessons = array_values(array_filter($module->lessons, function($lesson) {
                    return !empty($lesson->is_published);
                }));
                $module->activities = array_values(array_filter($module->activities, function($activity) {
                    return !empty($activity->is_published);
                }));
            }

            foreach ($module->activities as $activity_key => &$activity) {
                if ($activity->type !== 'quiz') {
                    $activity->can_manage = $module->can_manage;
                    $activity->is_shared = false;
                    continue;
                }

                $activity->quiz = $this->Quiz_model->get_quiz_by_activity($activity->id);
                if ($filter_unpublished && (!$activity->quiz || empty($activity->quiz->is_published))) {
                    unset($module->activities[$activity_key]);
                    continue;
                }

                $activity->question_count = $activity->quiz ? $this->Quiz_model->count_questions($activity->quiz->id) : 0;
                $activity->can_manage = $module->can_manage;
                $activity->is_shared = false;
            }

            foreach ($module->lessons as &$lesson) {
                $lesson->can_manage = $module->can_manage;
                $lesson->is_shared = false;
            }
            unset($lesson);
            unset($activity);
            $module->activities = array_values($module->activities);
        }
        unset($module);
        $modules = array_values($modules);
        $shared_modules = $this->get_shared_grade_level_modules($subject);
        $current_user_id = $this->current_user ? (int) $this->current_user->id : 0;
        $this->apply_user_lesson_taught_statuses($modules, $current_user_id);
        $this->apply_user_lesson_taught_statuses($shared_modules, $current_user_id);
        $completed_lesson_ids = array();
        $completed_activity_ids = array();
        $accessible_lesson_ids = array();
        $progress_percent = 0;

        if ($student_content_view && $this->current_user && $has_subject_access) {
            $completed_lesson_ids = $this->get_current_completed_lesson_ids($subject_id);
            $completed_activity_ids = $this->get_current_completed_activity_ids($subject_id);
            $progress_percent = $this->get_current_subject_progress_percent($subject_id);

            foreach ($this->Lesson_model->get_subject_lesson_ids($subject_id, $filter_unpublished) as $lesson_id) {
                if ($this->is_current_lesson_accessible($lesson_id, $subject_id)) {
                    $accessible_lesson_ids[] = (int) $lesson_id;
                }
            }
        }
        
        $data['title'] = 'Subject Content: ' . $subject->code;
        $data['subject'] = $subject;
        $data['modules'] = $modules;
        $data['shared_modules'] = $shared_modules;
        $can_edit = $this->can_manage_course_content($subject_id);
        $can_reorder_modules = !$student_content_view && $can_edit && count($modules) > 1;
        $can_manage_sections = $can_edit || ($this->original_role_slug === 'teacher' && $this->is_teacher_for_subject($subject_id));
        $data['edit_mode']          = !$student_content_view && $this->input->get('edit') === '1' && $can_edit;
        $data['can_edit']           = $can_edit;
        $data['can_reorder_modules'] = $can_reorder_modules;
        $data['can_manage_sections'] = !$student_content_view && $can_manage_sections;
        $data['completed_lesson_ids'] = $completed_lesson_ids;
        $data['completed_activity_ids'] = $completed_activity_ids;
        $data['accessible_lesson_ids'] = $accessible_lesson_ids;
        $data['progress_percent'] = $progress_percent;
        $data['student_content_view'] = $student_content_view;
        $data['subject_sections'] = $subject_sections;
        $data['available_sections'] = $this->Academic_model->get_sections(array('school_id' => $this->school_id));
        $data['requires_enrollment_key'] = $requires_enrollment_key;
        $data['has_subject_access'] = $has_subject_access;
        $data['subject_learning_competencies'] = $this->Academic_model->get_learning_competencies($subject_id);

        $back_param = $this->input->get('back', TRUE);
        if ($back_param) {
            if (strpos($back_param, 'academic/program_subjects/') === 0 && $this->original_role_slug !== 'super_admin') {
                $data['back_url'] = site_url('academic/sections');
            } else {
                $data['back_url'] = site_url($back_param);
            }
        } elseif ($this->original_role_slug === 'teacher') {
            $data['back_url'] = site_url('course/teacher_subjects');
        } elseif ($this->original_role_slug === 'student') {
            $data['back_url'] = site_url('student');
        } else {
            $data['back_url'] = site_url('subjects');
        }

        $this->render('course/content', $data);
    }

    private function get_learning_competency_subject($subject_id)
    {
        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject) {
            show_404();
        }

        if (!$this->can_access_subject_content_page($subject)) {
            show_error('You do not have permission to view learning competencies for this subject.', 403);
        }

        return $subject;
    }

    private function can_create_learning_competency()
    {
        return !$this->is_student_content_view() && $this->current_user;
    }

    private function can_manage_learning_competency($competency)
    {
        if (!$this->can_create_learning_competency() || !$competency || !$this->current_user) {
            return false;
        }

        return !empty($competency->created_by) && (int) $competency->created_by === (int) $this->current_user->id;
    }

    private function require_learning_competency_owner($competency)
    {
        if ($this->can_manage_learning_competency($competency)) {
            return;
        }

        show_error('You can only manage learning competencies that you added.', 403);
    }

    private function get_learning_competencies_redirect($subject_id)
    {
        $redirect = 'course/learning_competencies/' . (int) $subject_id;
        $back_param = (string) $this->input->get('back', TRUE);

        if ($back_param !== '') {
            $redirect .= '?back=' . urlencode($back_param);
        }

        return $redirect;
    }

    private function resolve_subject_learning_competency_id($subject_id)
    {
        $competency_id = (int) $this->input->post('learning_competency_id');
        if ($competency_id <= 0) {
            return null;
        }

        $competency = $this->Academic_model->get_learning_competency($competency_id);
        if (!$competency || (int) $competency->subject_id !== (int) $subject_id) {
            return false;
        }

        return $competency_id;
    }

    private function get_course_content_redirect($subject_id, $return_query = '')
    {
        $redirect_url = site_url('course/content/' . (int) $subject_id);
        $query = array();
        $parsed_query = array();
        $return_query = ltrim((string) $return_query, '?');

        if ($return_query !== '') {
            parse_str($return_query, $parsed_query);
        }

        if (!empty($parsed_query['edit']) && $this->can_manage_course_content($subject_id)) {
            $query['edit'] = '1';
        }

        if (!empty($parsed_query['back']) && is_string($parsed_query['back'])) {
            $back_param = trim($parsed_query['back']);
            if ($back_param !== '' && strpos($back_param, '://') === false && strpos($back_param, "\n") === false && strpos($back_param, "\r") === false) {
                $query['back'] = $back_param;
            }
        }

        if (!empty($query)) {
            $redirect_url .= '?' . http_build_query($query);
        }

        return $redirect_url;
    }

    private function apply_user_lesson_taught_statuses(&$modules, $user_id)
    {
        if (!is_array($modules)) {
            return;
        }

        $lesson_ids = array();
        foreach ($modules as $module) {
            if (empty($module->lessons) || !is_array($module->lessons)) {
                continue;
            }

            foreach ($module->lessons as $lesson) {
                $lesson_ids[] = (int) $lesson->id;
            }
        }

        $taught_map = $this->Lesson_model->get_lesson_taught_map($lesson_ids, $user_id);

        foreach ($modules as &$module) {
            if (empty($module->lessons) || !is_array($module->lessons)) {
                continue;
            }

            foreach ($module->lessons as &$lesson) {
                $lesson->taught_at = isset($taught_map[(int) $lesson->id])
                    ? $taught_map[(int) $lesson->id]
                    : null;
            }
            unset($lesson);
        }
        unset($module);
    }

    private function resolve_lesson_taught_datetime($date_value, $existing_datetime = null)
    {
        $date_value = trim((string) $date_value);
        if ($date_value === '') {
            return false;
        }

        $date = DateTime::createFromFormat('Y-m-d', $date_value);
        if (!$date || $date->format('Y-m-d') !== $date_value) {
            return false;
        }

        $time_value = date('H:i:s');
        if (!empty($existing_datetime)) {
            $existing_timestamp = strtotime((string) $existing_datetime);
            if ($existing_timestamp) {
                $time_value = date('H:i:s', $existing_timestamp);
            }
        }

        return $date->format('Y-m-d') . ' ' . $time_value;
    }

    public function learning_competencies($subject_id)
    {
        $subject = $this->get_learning_competency_subject($subject_id);
        $competencies = $this->Academic_model->get_learning_competencies($subject_id);
        $competency_progress = $this->Lesson_model->get_subject_learning_competency_progress($subject_id, $this->current_user ? (int) $this->current_user->id : 0);
        $completed_competency_count = 0;
        $tracked_competency_count = 0;

        foreach ($competencies as $competency) {
            $competency->can_manage = $this->can_manage_learning_competency($competency);
            $progress = isset($competency_progress[(int) $competency->id]) ? $competency_progress[(int) $competency->id] : array(
                'total_lessons' => 0,
                'taught_lessons' => 0,
                'latest_taught_at' => null,
            );

            $competency->total_lessons = (int) $progress['total_lessons'];
            $competency->taught_lessons = (int) $progress['taught_lessons'];
            $competency->latest_taught_at = $progress['latest_taught_at'];
            $competency->latest_taught_at_label = !empty($competency->latest_taught_at)
                ? date('M j, Y', strtotime($competency->latest_taught_at))
                : '';
            $competency->completion_percent = $competency->total_lessons > 0
                ? (int) round(($competency->taught_lessons / $competency->total_lessons) * 100)
                : 0;

            if ($competency->total_lessons <= 0) {
                $competency->checklist_state = 'unlinked';
                $competency->checklist_icon = 'bi-link-45deg';
                $competency->checklist_label = 'No linked lessons yet';
                $competency->checklist_detail = 'Assign this competency to at least one lesson to track it here.';
            } elseif ($competency->taught_lessons >= $competency->total_lessons) {
                $competency->checklist_state = 'complete';
                $competency->checklist_icon = 'bi-check-lg';
                $competency->checklist_label = 'Completed';
                $competency->checklist_detail = 'All linked lessons are already marked as taught by you.';
                $completed_competency_count++;
                $tracked_competency_count++;
            } elseif ($competency->taught_lessons > 0) {
                $competency->checklist_state = 'in_progress';
                $competency->checklist_icon = 'bi-dash-lg';
                $competency->checklist_label = 'In Progress';
                $competency->checklist_detail = $competency->taught_lessons . ' of ' . $competency->total_lessons . ' linked lessons are marked as taught by you.';
                $tracked_competency_count++;
            } else {
                $competency->checklist_state = 'pending';
                $competency->checklist_icon = '';
                $competency->checklist_label = 'Not Started';
                $competency->checklist_detail = 'None of the ' . $competency->total_lessons . ' linked lessons are marked as taught by you yet.';
                $tracked_competency_count++;
            }
        }
        unset($competency);

        $back_param = (string) $this->input->get('back', TRUE);
        $back_url = $back_param !== '' ? site_url($back_param) : site_url('course/content/' . (int) $subject_id);
        $back_label = 'Back to Course';

        if ($back_param === 'course/teacher_subjects') {
            $back_label = 'Back to My Subjects';
        } elseif (strpos($back_param, 'academic/program_subjects/') === 0) {
            $back_label = 'Back to Program Subjects';
        } elseif ($back_param === 'student') {
            $back_label = 'Back to Student';
        }

        $query_suffix = $back_param !== '' ? '?back=' . urlencode($back_param) : '';

        $data['title'] = 'Learning Competencies - ' . $subject->code;
        $data['subject'] = $subject;
        $data['competencies'] = $competencies;
        $data['completed_competency_count'] = $completed_competency_count;
        $data['tracked_competency_count'] = $tracked_competency_count;
        $data['competency_completion_percent'] = !empty($competencies)
            ? (int) round(($completed_competency_count / count($competencies)) * 100)
            : 0;
        $data['back_url'] = $back_url;
        $data['back_label'] = $back_label;
        $data['can_create_learning_competency'] = $this->can_create_learning_competency();
        $data['create_url'] = site_url('course/create_learning_competency/' . (int) $subject_id) . $query_suffix;
        $data['update_base_url'] = site_url('course/update_learning_competency/' . (int) $subject_id);
        $data['delete_base_url'] = site_url('course/delete_learning_competency/' . (int) $subject_id);
        $data['route_query_suffix'] = $query_suffix;
        $this->render('academic/learning_competencies', $data);
    }

    public function create_learning_competency($subject_id)
    {
        $subject = $this->get_learning_competency_subject($subject_id);
        if ($this->input->method() !== 'post') {
            redirect($this->get_learning_competencies_redirect($subject_id));
        }

        if (!$this->can_create_learning_competency()) {
            show_error('You do not have permission to add learning competencies.', 403);
        }

        $this->form_validation->set_rules('description', 'Description', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect($this->get_learning_competencies_redirect($subject_id));
            return;
        }

        $quarter = $this->input->post('quarter', TRUE);
        $quarter = ctype_digit((string) $quarter) ? (int) $quarter : null;
        if ($quarter !== null && ($quarter < 1 || $quarter > 4)) {
            $quarter = null;
        }

        $sort_order = $this->input->post('sort_order', TRUE);
        $sort_order = is_numeric($sort_order) ? (int) $sort_order : 0;

        $data = array(
            'subject_id'   => (int) $subject_id,
            'school_id'    => !empty($subject->school_id) ? (int) $subject->school_id : (int) $this->school_id,
            'code'         => trim((string) $this->input->post('code', TRUE)),
            'description'  => trim((string) $this->input->post('description', TRUE)),
            'quarter'      => $quarter,
            'sort_order'   => $sort_order,
            'created_by'   => (int) $this->current_user->id,
        );

        if ($data['code'] === '') {
            $data['code'] = null;
        }

        $this->Academic_model->create_learning_competency($data);
        $this->session->set_flashdata('success', 'Learning competency added successfully.');
        redirect($this->get_learning_competencies_redirect($subject_id));
    }

    public function update_learning_competency($subject_id, $id)
    {
        $this->get_learning_competency_subject($subject_id);
        if ($this->input->method() !== 'post') {
            redirect($this->get_learning_competencies_redirect($subject_id));
        }

        $competency = $this->Academic_model->get_learning_competency($id);
        if (!$competency || (int) $competency->subject_id !== (int) $subject_id) {
            show_404();
        }

        $this->require_learning_competency_owner($competency);
        $this->form_validation->set_rules('description', 'Description', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect($this->get_learning_competencies_redirect($subject_id));
            return;
        }

        $quarter = $this->input->post('quarter', TRUE);
        $quarter = ctype_digit((string) $quarter) ? (int) $quarter : null;
        if ($quarter !== null && ($quarter < 1 || $quarter > 4)) {
            $quarter = null;
        }

        $sort_order = $this->input->post('sort_order', TRUE);
        $sort_order = is_numeric($sort_order) ? (int) $sort_order : 0;

        $data = array(
            'code'        => trim((string) $this->input->post('code', TRUE)),
            'description' => trim((string) $this->input->post('description', TRUE)),
            'quarter'     => $quarter,
            'sort_order'  => $sort_order,
        );

        if ($data['code'] === '') {
            $data['code'] = null;
        }

        $this->Academic_model->update_learning_competency($id, $data);
        $this->session->set_flashdata('success', 'Learning competency updated successfully.');
        redirect($this->get_learning_competencies_redirect($subject_id));
    }

    public function delete_learning_competency($subject_id, $id)
    {
        $this->get_learning_competency_subject($subject_id);
        $competency = $this->Academic_model->get_learning_competency($id);
        if (!$competency || (int) $competency->subject_id !== (int) $subject_id) {
            show_404();
        }

        $this->require_learning_competency_owner($competency);
        $this->Academic_model->delete_learning_competency($id);
        $this->session->set_flashdata('success', 'Learning competency deleted successfully.');
        redirect($this->get_learning_competencies_redirect($subject_id));
    }

    private function get_subject_item_navigation($subject_id, $current_type, $current_id)
    {
        $items = array();
        $modules = $this->Lesson_model->get_modules_by_subject($subject_id);
        $completed_lesson_ids = $this->get_current_completed_lesson_ids($subject_id);
        $completed_activity_ids = $this->get_current_completed_activity_ids($subject_id);
        $filter_unpublished = $this->should_filter_unpublished_content();

        foreach ($modules as $module) {
            $module_items = array();
            foreach ($this->Lesson_model->get_lessons($module->id) as $lesson) {
                if ($filter_unpublished && (!$module->is_published || !$lesson->is_published)) {
                    continue;
                }

                $module_items[] = (object) array(
                    'id' => $lesson->id,
                    'item_type' => 'lesson',
                    'title' => $lesson->title,
                    'module_title' => $module->title,
                    'order_num' => $lesson->order_num,
                    'url' => site_url('course/lesson/' . $lesson->id),
                    'is_completed' => in_array((int) $lesson->id, $completed_lesson_ids),
                    'is_accessible' => $this->is_current_lesson_accessible($lesson->id, $subject_id),
                );
            }

            foreach ($this->Lesson_model->get_activities($module->id) as $activity) {
                if ($filter_unpublished && (!$module->is_published || !$activity->is_published)) {
                    continue;
                }

                $quiz = $activity->type === 'quiz' ? $this->Quiz_model->get_quiz_by_activity($activity->id) : null;
                if ($filter_unpublished && $activity->type === 'quiz' && (!$quiz || empty($quiz->is_published))) {
                    continue;
                }

                $module_items[] = (object) array(
                    'id' => $activity->id,
                    'item_type' => 'activity',
                    'title' => $activity->title,
                    'module_title' => $module->title,
                    'order_num' => $activity->order_num,
                    'url' => site_url('course/' . ($activity->type === 'quiz' ? 'assessment' : 'activity') . '/' . $activity->id),
                    'is_completed' => in_array((int) $activity->id, $completed_activity_ids),
                    'is_accessible' => $this->is_item_accessible($activity->id, $activity->type, $subject_id),
                );
            }

            usort($module_items, function($a, $b) {
                return $a->order_num - $b->order_num;
            });

            $items = array_merge($items, $module_items);
        }

        $previous = null;
        $next = null;

        foreach ($items as $index => $item) {
            if ($item->item_type === $current_type && (int) $item->id === (int) $current_id) {
                $previous = $items[$index - 1] ?? null;
                $next = $items[$index + 1] ?? null;
                break;
            }
        }

        return array(
            'previous' => $previous,
            'next' => $next,
        );
    }

    private function use_preview_progress()
    {
        return $this->is_student_mode && $this->original_role_slug !== 'student';
    }

    private function get_preview_completed_lesson_ids()
    {
        return array_map('intval', $this->session->userdata('preview_completed_lessons') ?: array());
    }

    private function get_preview_completed_activity_ids()
    {
        return array_map('intval', $this->session->userdata('preview_completed_activities') ?: array());
    }

    private function set_preview_completed_lesson_ids($lesson_ids)
    {
        $this->session->set_userdata('preview_completed_lessons', array_values(array_unique(array_map('intval', $lesson_ids))));
    }

    private function set_preview_completed_activity_ids($activity_ids)
    {
        $this->session->set_userdata('preview_completed_activities', array_values(array_unique(array_map('intval', $activity_ids))));
    }

    private function get_current_student_record()
    {
        if ($this->current_student_record_loaded) {
            return $this->current_student_record;
        }

        $this->current_student_record_loaded = true;
        if (!$this->current_user) {
            $this->current_student_record = null;
            return null;
        }

        $this->current_student_record = $this->Student_model->get_student_by_user_id($this->current_user->id);
        return $this->current_student_record;
    }

    private function has_activity_progress_table()
    {
        if ($this->activity_progress_table_exists !== null) {
            return $this->activity_progress_table_exists;
        }

        $this->activity_progress_table_exists = $this->db->query("SHOW TABLES LIKE 'activity_progress'")->num_rows() > 0;
        return $this->activity_progress_table_exists;
    }

    private function get_current_completed_lesson_ids($subject_id)
    {
        if (!$this->is_student_content_view() || !$this->current_user) {
            return array();
        }

        if ($this->use_preview_progress()) {
            $subject_lesson_ids = $this->Lesson_model->get_subject_lesson_ids($subject_id, $this->should_filter_unpublished_content());
            return array_values(array_intersect($subject_lesson_ids, $this->get_preview_completed_lesson_ids()));
        }

        $student = $this->get_current_student_record();
        if (!$student) {
            $subject_lesson_ids = $this->Lesson_model->get_subject_lesson_ids($subject_id, $this->should_filter_unpublished_content());
            return array_values(array_intersect($subject_lesson_ids, $this->get_preview_completed_lesson_ids()));
        }

        return $this->Lesson_model->get_completed_lesson_ids_by_subject($subject_id, $student->id);
    }

    private function get_current_subject_progress_percent($subject_id)
    {
        $published_only = $this->should_filter_unpublished_content();
        $lesson_ids = $this->Lesson_model->get_subject_lesson_ids($subject_id, $published_only);
        $assessment_activity_ids = $this->Quiz_model->get_subject_quiz_activity_ids($subject_id, $published_only);
        $total_items = count($lesson_ids) + count($assessment_activity_ids);
        if ($total_items < 1) return 0;

        $completed_lesson_ids = array_values(array_intersect($lesson_ids, $this->get_current_completed_lesson_ids($subject_id)));
        $completed_activity_ids = array_values(array_intersect($assessment_activity_ids, $this->get_current_completed_activity_ids($subject_id)));

        return round(((count($completed_lesson_ids) + count($completed_activity_ids)) / $total_items) * 100);
    }

    private function is_current_lesson_accessible($lesson_id, $subject_id)
    {
        if (!$this->is_student_content_view() || !$this->current_user) {
            return true;
        }

        $ordered = $this->Lesson_model->get_subject_lesson_ids($subject_id, $this->should_filter_unpublished_content());
        $completed = $this->get_current_completed_lesson_ids($subject_id);

        foreach ($ordered as $lid) {
            if ((int) $lid === (int) $lesson_id) return true;
            if (!in_array((int) $lid, $completed)) return false;
        }

        return true;
    }

    private function is_item_accessible($item_id, $item_type, $subject_id)
    {
        if (!$this->is_student_content_view() || !$this->current_user) {
            return true;
        }

        $modules = $this->Lesson_model->get_modules_by_subject($subject_id);
        $filter_unpublished = $this->should_filter_unpublished_content();
        
        $all_ordered_items = array();
        foreach ($modules as $module) {
            if ($filter_unpublished && !$module->is_published) continue;
            
            $lessons = $this->Lesson_model->get_lessons($module->id);
            foreach ($lessons as $lesson) {
                if ($filter_unpublished && !$lesson->is_published) continue;
                $all_ordered_items[] = (object) array(
                    'id' => $lesson->id,
                    'type' => 'lesson',
                    'order_num' => $lesson->order_num
                );
            }
            
            $activities = $this->Lesson_model->get_activities($module->id);
            foreach ($activities as $activity) {
                if ($filter_unpublished && !$activity->is_published) continue;
                $all_ordered_items[] = (object) array(
                    'id' => $activity->id,
                    'type' => $activity->type,
                    'order_num' => $activity->order_num
                );
            }
        }
        
        usort($all_ordered_items, function($a, $b) {
            return $a->order_num - $b->order_num;
        });
        
        $completed_lesson_ids = $this->get_current_completed_lesson_ids($subject_id);
        $completed_activity_ids = $this->get_current_completed_activity_ids($subject_id);
        
        foreach ($all_ordered_items as $item) {
            if ((int) $item->id === (int) $item_id && $item->type === $item_type) return true;
            
            $is_completed = false;
            if ($item->type === 'lesson') {
                $is_completed = in_array((int) $item->id, $completed_lesson_ids);
            } else {
                $is_completed = in_array((int) $item->id, $completed_activity_ids);
            }
            
            if (!$is_completed) return false;
        }
        
        return true;
    }

    private function get_current_completed_activity_ids($subject_id)
    {
        if (!$this->is_student_content_view() || !$this->current_user) {
            return array();
        }

        if ($this->use_preview_progress()) {
            return $this->get_preview_completed_activity_ids();
        }

        $completed_activity_ids = array();
        if (!$this->has_activity_progress_table()) {
            $completed_activity_ids = $this->get_preview_completed_activity_ids();
        } else {
            $this->db->select('activity_id');
            $this->db->where('student_id', $this->current_user->id);
            $this->db->where('status', 'completed');
            $this->db->from('activity_progress');
            $result = $this->db->get()->result();
            $completed_activity_ids = array_map(function($row) { return (int) $row->activity_id; }, $result);
        }

        $completed_quiz_activity_ids = $this->Quiz_model->get_completed_quiz_activity_ids_by_subject(
            $subject_id,
            $this->current_user->id,
            $this->should_filter_unpublished_content()
        );

        return array_values(array_unique(array_merge($completed_activity_ids, $completed_quiz_activity_ids)));
    }

    private function get_current_lesson_progress($lesson_id)
    {
        if (!$this->is_student_content_view() || !$this->current_user) {
            return null;
        }

        if ($this->use_preview_progress()) {
            $completed = in_array((int) $lesson_id, $this->get_preview_completed_lesson_ids());
            return (object) array(
                'status' => $completed ? 'completed' : 'in_progress',
                'progress_percent' => $completed ? 100 : 1,
                'completed_at' => null,
            );
        }

        return $this->Lesson_model->get_progress($this->current_user->id, $lesson_id);
    }

    private function mark_current_lesson_started($lesson_id)
    {
        if (!$this->is_student_content_view() || !$this->current_user || $this->use_preview_progress()) {
            return;
        }

        $progress = $this->Lesson_model->get_progress($this->current_user->id, $lesson_id);
        if (!$progress) {
            $this->Lesson_model->update_progress($this->current_user->id, $lesson_id, array(
                'status' => 'in_progress',
                'progress_percent' => 1,
            ));
        } elseif ($progress->status === 'not_started') {
            $this->Lesson_model->update_progress($this->current_user->id, $lesson_id, array(
                'status' => 'in_progress',
                'progress_percent' => max(1, (int) $progress->progress_percent),
            ));
        }
    }

    private function mark_current_lesson_completed($lesson_id)
    {
        if ($this->use_preview_progress()) {
            $completed = $this->get_preview_completed_lesson_ids();
            $completed[] = (int) $lesson_id;
            $this->set_preview_completed_lesson_ids($completed);
            return;
        }

        $student = $this->get_current_student_record();
        if ($student) {
            $this->Lesson_model->mark_lesson_completed($student->id, $lesson_id);
        } else {
            $completed = $this->get_preview_completed_lesson_ids();
            $completed[] = (int) $lesson_id;
            $this->set_preview_completed_lesson_ids($completed);
        }

        $this->Lesson_model->update_progress($this->current_user->id, $lesson_id, array(
            'status' => 'completed',
            'progress_percent' => 100,
        ));
    }

    private function mark_current_activity_completed($activity_id)
    {
        if ($this->use_preview_progress() || !$this->has_activity_progress_table()) {
            $completed = $this->get_preview_completed_activity_ids();
            $completed[] = (int) $activity_id;
            $this->set_preview_completed_activity_ids($completed);
            return;
        }

        $student_id = $this->current_user->id;
        $this->db->where('student_id', $student_id);
        $this->db->where('activity_id', $activity_id);
        $existing = $this->db->get('activity_progress')->row();
        
        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update('activity_progress', array('status' => 'completed', 'completed_at' => date('Y-m-d H:i:s')));
        } else {
            $this->db->insert('activity_progress', array(
                'student_id' => $student_id,
                'activity_id' => $activity_id,
                'status' => 'completed',
                'completed_at' => date('Y-m-d H:i:s')
            ));
        }
    }

    private function get_video_embed_markup($video_url)
    {
        $video_url = trim((string) $video_url);
        if ($video_url === '') {
            return '';
        }

        $escaped_url = htmlspecialchars($video_url, ENT_QUOTES, 'UTF-8');
        $embed_url = '';
        $host = parse_url($video_url, PHP_URL_HOST);
        $path = parse_url($video_url, PHP_URL_PATH);
        $query = array();
        parse_str(parse_url($video_url, PHP_URL_QUERY) ?: '', $query);

        if ($host && preg_match('/(^|\.)youtu\.be$/i', $host)) {
            $video_id = trim($path ?: '', '/');
            if ($video_id !== '') {
                $embed_url = 'https://www.youtube.com/embed/' . htmlspecialchars($video_id, ENT_QUOTES, 'UTF-8');
            }
        } elseif ($host && preg_match('/(^|\.)youtube\.com$/i', $host)) {
            if (!empty($query['v'])) {
                $embed_url = 'https://www.youtube.com/embed/' . htmlspecialchars($query['v'], ENT_QUOTES, 'UTF-8');
            } elseif ($path && preg_match('#/shorts/([^/]+)#', $path, $matches)) {
                $embed_url = 'https://www.youtube.com/embed/' . htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8');
            } elseif ($path && preg_match('#/embed/([^/]+)#', $path, $matches)) {
                $embed_url = 'https://www.youtube.com/embed/' . htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8');
            }
        } elseif ($host && preg_match('/(^|\.)vimeo\.com$/i', $host) && $path && preg_match('#/(\d+)#', $path, $matches)) {
            $embed_url = 'https://player.vimeo.com/video/' . htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8');
        }

        if ($embed_url !== '') {
            return '<div class="lesson-video-embed ratio ratio-16x9 mb-3" data-video-url="' . $escaped_url . '"><iframe src="' . $embed_url . '" title="Lesson video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>';
        }

        if (preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $video_url)) {
            return '<div class="lesson-video-embed mb-3" data-video-url="' . $escaped_url . '"><video class="w-100 rounded" controls preload="metadata"><source src="' . $escaped_url . '">Your browser does not support the video tag.</video></div>';
        }

        return '<div class="lesson-video-embed lesson-video-source mb-3" data-video-url="' . $escaped_url . '"><a href="' . $escaped_url . '" target="_blank" rel="noopener" class="btn btn-outline-primary"><i class="bi bi-box-arrow-up-right me-1"></i>Open Video</a></div>';
    }

    private function get_lesson_file_markup($file_url)
    {
        $file_url = trim((string) $file_url);
        if ($file_url === '') {
            return '';
        }

        $escaped_url = htmlspecialchars($file_url, ENT_QUOTES, 'UTF-8');
        $markup = '<div class="lesson-file-embed mb-3" data-file-url="' . $escaped_url . '">';
        $markup .= '<div class="lesson-file-toolbar mb-2"><a href="' . $escaped_url . '" target="_blank" rel="noopener" class="btn btn-outline-primary"><i class="bi bi-file-earmark-pdf me-1"></i>Open PDF</a></div>';

        if (preg_match('/\.pdf(\?.*)?$/i', $file_url)) {
            $markup .= '<div class="ratio ratio-4x3 lesson-file-preview"><iframe src="' . $escaped_url . '" title="PDF preview" loading="lazy"></iframe></div>';
        }

        $markup .= '</div>';
        return $markup;
    }

    private function normalize_lesson_content_type($content_type)
    {
        $content_type = strtolower(trim((string) $content_type));
        return in_array($content_type, array('text', 'page', 'video', 'file', 'link')) ? $content_type : 'text';
    }

    private function get_upload_error_message($error_code)
    {
        $messages = array(
            UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the server upload limit.',
            UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the form upload limit.',
            UPLOAD_ERR_PARTIAL    => 'The file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'Please select a PDF file to upload.',
            UPLOAD_ERR_NO_TMP_DIR => 'The server is missing a temporary upload folder.',
            UPLOAD_ERR_CANT_WRITE => 'The server could not write the uploaded file.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
        );

        return isset($messages[$error_code]) ? $messages[$error_code] : 'The file could not be uploaded.';
    }

    private function upload_lesson_file()
    {
        if (empty($_FILES['file_upload']['name'])) {
            return array('success' => false, 'path' => '', 'error' => 'Please select a PDF file to upload.');
        }

        if ($_FILES['file_upload']['error'] !== UPLOAD_ERR_OK) {
            return array('success' => false, 'path' => '', 'error' => $this->get_upload_error_message($_FILES['file_upload']['error']));
        }

        $upload_path = FCPATH . 'uploads/lessons/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        if (!is_writable($upload_path)) {
            @chmod($upload_path, 0777);
        }
        if (!is_writable($upload_path)) {
            return array('success' => false, 'path' => '', 'error' => 'The lessons upload folder is not writable.');
        }

        $config = array(
            'upload_path'   => $upload_path,
            'allowed_types' => 'pdf',
            'max_size'      => 10240,
            'encrypt_name'  => true,
        );

        $this->load->library('upload');
        $this->upload->initialize($config, true);

        if (!$this->upload->do_upload('file_upload')) {
            return array('success' => false, 'path' => '', 'error' => $this->upload->display_errors('', ''));
        }

        $upload_data = $this->upload->data();
        return array(
            'success' => true,
            'path'    => base_url('uploads/lessons/' . $upload_data['file_name']),
            'error'   => '',
        );
    }

    private function build_lesson_content($content_type, $content, $video_url = '', $file_url = '', $link_url = '')
    {
        if ($content_type === 'video') {
            $video_markup = $this->get_video_embed_markup($video_url);
            if ($video_markup === '') {
                return $content;
            }

            $notes = trim((string) $content);
            if ($notes !== '') {
                return $video_markup . "\n" . '<div class="lesson-video-notes">' . $notes . '</div>';
            }

            return $video_markup;
        }

        if ($content_type === 'file') {
            $file_markup = $this->get_lesson_file_markup($file_url);
            if ($file_markup === '') {
                return $content;
            }

            $description = trim((string) $content);
            if ($description !== '') {
                return $file_markup . "\n" . $description;
            }

            return $file_markup;
        }

        if ($content_type === 'link') {
            $escaped_url = htmlspecialchars(trim((string) $link_url), ENT_QUOTES, 'UTF-8');
            if ($escaped_url === '') {
                return $content;
            }

            $link_markup = '<div class="lesson-link-embed mb-3" data-link-url="' . $escaped_url . '"><a href="' . $escaped_url . '" target="_blank" rel="noopener" class="btn btn-outline-success"><i class="bi bi-box-arrow-up-right me-1"></i>Open External Link</a></div>';

            $description = trim((string) $content);
            if ($description !== '') {
                return $link_markup . "\n" . $description;
            }

            return $link_markup;
        }

        return $content;
    }

    public function lesson($lesson_id = null)
    {
        if (!$lesson_id) {
            redirect('subjects');
        }

        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) {
            show_404();
        }

        $module = $this->Lesson_model->get_module($lesson->module_id);
        if (!$module) {
            show_404();
        }

        if ($this->should_filter_unpublished_content() && (!$module->is_published || !$lesson->is_published)) {
            show_404();
        }

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) {
            show_404();
        }

        $shared_lesson_view = false;
        if (!$this->is_student_content_view() && !$this->can_access_subject_content_page($subject)) {
            if (!$lesson->is_published || !$module->is_published || !$this->can_access_shared_grade_level_lesson($subject, $module)) {
                show_error('You do not have permission to view this lesson.', 403);
            }
            $shared_lesson_view = true;
        }

        if (!$this->has_subject_access($subject->id)) {
            $this->session->set_flashdata('error', 'Enter the enrollment key to access this course.');
            redirect('course/content/' . $subject->id);
        }

        $progress = null;
        $progress_percent = 0;
        if ($this->is_student_content_view() && $this->current_user) {
            if (!$this->is_current_lesson_accessible($lesson->id, $subject->id)) {
                $this->session->set_flashdata('error', 'Complete the previous lesson first.');
                redirect('course/content/' . $subject->id);
            }

            $this->mark_current_lesson_started($lesson->id);
            $progress = $this->get_current_lesson_progress($lesson->id);
            $progress_percent = $this->get_current_subject_progress_percent($subject->id);
        }

        $data['title'] = 'Lesson: ' . $lesson->title;
        $data['subject'] = $subject;
        $data['module'] = $module;
        $data['item'] = $lesson;
        $data['item_type'] = 'lesson';
        $data['navigation'] = $shared_lesson_view ? array('previous' => null, 'next' => null) : $this->get_subject_item_navigation($subject->id, 'lesson', $lesson->id);
        $data['lesson_progress'] = $progress;
        $data['progress_percent'] = $progress_percent;
        $data['student_content_view'] = $this->is_student_content_view();
        $data['can_manage_item'] = !$shared_lesson_view && $this->can_manage_module_content($module);
        $data['manage_url'] = (!$shared_lesson_view && $this->can_manage_module_content($module))
            ? site_url('course/content/' . $subject->id . '?edit=1#module-' . $module->id)
            : '';
        $back_param = $this->input->get('back', TRUE);
        if ($back_param) {
            $data['back_url'] = site_url($back_param);
        } else {
            $data['back_url'] = site_url('course/content/' . $subject->id . ((!$this->is_student_content_view() && $this->can_access_subject_content_page($subject) && $this->can_manage_course_content($subject->id)) ? '?edit=1' : ''));
        }

        $this->render('course/item_view', $data);
    }

    public function complete_lesson($lesson_id = null)
    {
        if (!$lesson_id) {
            redirect('subjects');
        }

        if (!$this->is_student_content_view() || !$this->current_user) {
            show_error('Progress can only be updated by students.', 403);
        }

        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) show_404();

        $module = $this->Lesson_model->get_module($lesson->module_id);
        if (!$module) show_404();

        if ($this->should_filter_unpublished_content() && (!$module->is_published || !$lesson->is_published)) {
            show_404();
        }

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) show_404();

        if (!$this->has_subject_access($subject->id)) {
            $this->session->set_flashdata('error', 'Enter the enrollment key to access this course.');
            redirect('course/content/' . $subject->id);
        }

        if (!$this->is_current_lesson_accessible($lesson->id, $subject->id)) {
            $this->session->set_flashdata('error', 'Complete the previous lesson first.');
            redirect('course/content/' . $subject->id);
        }

        $this->mark_current_lesson_completed($lesson->id);

        $this->session->set_flashdata('success', 'Lesson marked as complete.');
        redirect('course/lesson/' . $lesson->id);
    }

    public function activity($activity_id = null)
    {
        if (!$activity_id) {
            redirect('subjects');
        }

        $activity = $this->Lesson_model->get_activity($activity_id);
        if (!$activity) {
            show_404();
        }

        if ($activity->type === 'quiz') {
            redirect('course/assessment/' . $activity->id);
        }

        $module = $this->Lesson_model->get_module($activity->module_id);
        if (!$module) {
            show_404();
        }

        if ($this->should_filter_unpublished_content() && (!$module->is_published || !$activity->is_published)) {
            show_404();
        }

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) {
            show_404();
        }

        if (!$this->is_student_content_view() && !$this->can_access_subject_content_page($subject)) {
            show_error('You do not have permission to view this activity.', 403);
        }

        if (!$this->has_subject_access($subject->id)) {
            $this->session->set_flashdata('error', 'Enter the enrollment key to access this course.');
            redirect('course/content/' . $subject->id);
        }

        if (!$this->is_item_accessible($activity->id, $activity->type, $subject->id)) {
            $this->session->set_flashdata('error', 'Complete the previous item first.');
            redirect('course/content/' . $subject->id);
        }

        $data['title'] = 'Activity: ' . $activity->title;
        $data['subject'] = $subject;
        $data['module'] = $module;
        $data['item'] = $activity;
        $data['item_type'] = 'activity';
        $data['navigation'] = $this->get_subject_item_navigation($subject->id, 'activity', $activity->id);
        $data['student_content_view'] = $this->is_student_content_view();
        $data['can_manage_item'] = $this->can_manage_module_content($module);
        $data['manage_url'] = $this->can_manage_module_content($module)
            ? site_url('course/content/' . $subject->id . '?edit=1#module-' . $module->id)
            : '';
        $data['back_url'] = site_url('course/content/' . $subject->id . ((!$this->is_student_content_view() && $this->can_manage_course_content($subject->id)) ? '?edit=1' : ''));

        $this->render('course/item_view', $data);
    }

    public function enroll_subject($subject_id = null)
    {
        if (!$subject_id) {
            redirect('subjects');
        }

        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject) show_404();

        if (!$this->Academic_model->subject_has_enrollment_keys($subject_id)) {
            $this->set_subject_access($subject_id);
            redirect('course/content/' . $subject_id);
        }

        if ($this->input->method() === 'post') {
            $class_program = $this->Academic_model->validate_subject_enrollment_key($subject_id, $this->input->post('enrollment_key', TRUE));
            if ($class_program) {
                $this->set_subject_access($subject_id, $class_program->id);

                if ($this->original_role_slug === 'student' && $this->current_user) {
                    $existing_enrollment = $this->db->where('user_id', $this->current_user->id)
                                                     ->where('course_id', $subject_id)
                                                     ->where('role', 'student')
                                                     ->get('course_enrollments')
                                                     ->row();

                    if (!$existing_enrollment) {
                        $this->db->insert('course_enrollments', array(
                            'user_id'   => $this->current_user->id,
                            'course_id' => $subject_id,
                            'role'      => 'student',
                            'status'    => 'active'
                        ));
                    } elseif ($existing_enrollment->status !== 'active') {
                        $this->db->where('id', $existing_enrollment->id)
                                 ->update('course_enrollments', array('status' => 'active'));
                    }
                }

                $this->session->set_flashdata('success', 'Enrollment key accepted. You can now access this course.');
            } else {
                $this->session->set_flashdata('error', 'Invalid enrollment key.');
            }
        }

        redirect('course/content/' . $subject_id);
    }

    public function add_subject_section($subject_id)
    {
        $this->require_section_manager($subject_id);
        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject) show_404();

        if ($this->input->method() === 'post') {
            $section_name = trim($this->input->post('section_name', TRUE));
            if ($section_name) {
                $created_by_user_id = $this->current_user ? (int) $this->current_user->id : null;
                $this->Academic_model->save_subject_section_by_name($subject_id, $section_name, $this->input->post('enrollment_key', TRUE), $created_by_user_id);
                $this->session->set_flashdata('success', 'Section access saved.');
            }
        }
        redirect('course/content/' . $subject_id . '?edit=1');
    }

    public function remove_subject_section($subject_id, $class_program_id)
    {
        $this->require_section_manager($subject_id);

        // Check if section can be deleted
        if (!$this->Academic_model->can_delete_section($class_program_id)) {
            $this->session->set_flashdata('error', 'Cannot remove section. This is the only section for this course and there are enrolled students.');
            redirect('course/content/' . $subject_id . '?edit=1');
        }

        $this->Academic_model->remove_subject_section($class_program_id, $subject_id);
        $this->session->set_flashdata('success', 'Section access removed.');
        redirect('course/content/' . $subject_id . '?edit=1');
    }

    private function get_completion_student_ids_for_subject($subject_id)
    {
        if ((int) $subject_id < 1 || $this->original_role_slug === 'super_admin') {
            return null;
        }

        $teacher_user_id = null;
        if ($this->original_role_slug === 'teacher' && $this->current_user) {
            $teacher_user_id = (int) $this->current_user->id;
        }

        $school_id = !empty($this->school_id) ? (int) $this->school_id : null;

        return $this->Academic_model->get_subject_completion_student_ids($subject_id, $school_id, $teacher_user_id);
    }

    public function section_students($section_id)
    {
        $section = $this->Academic_model->get_subject_section($section_id);
        if (!$section) show_404();
        $this->require_section_manager($section->subject_id);

        $data['title'] = 'Enrolled Students: ' . $section->section_name;
        $data['section'] = $section;
        $data['students'] = $this->Academic_model->get_section_students($section_id);

        $this->render('course/section_students', $data);
    }

    public function section_progress($section_id)
    {
        $section = $this->Academic_model->get_subject_section($section_id);
        if (!$section) show_404();
        $this->require_section_manager($section->subject_id);

        $data['title'] = 'Section Progress: ' . $section->section_name;
        $data['section'] = $section;
        $data['students'] = $this->Academic_model->get_section_students($section_id);

        $this->render('course/section_progress', $data);
    }

    public function section_attendance($section_id)
    {
        $section = $this->Academic_model->get_subject_section($section_id);
        if (!$section) show_404();
        $this->require_section_manager($section->subject_id);

        $data['title'] = 'Section Attendance: ' . $section->section_name;
        $data['section'] = $section;
        $data['students'] = $this->Academic_model->get_section_students($section_id);

        $this->render('course/section_attendance', $data);
    }

    public function lesson_completions($lesson_id)
    {
        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) show_404();

        $module = $this->Lesson_model->get_module($lesson->module_id);
        if (!$module) show_404();

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) show_404();

        $view_subject = $subject;
        $context_subject_id = (int) $this->input->get('subject_id');
        if ($context_subject_id > 0 && $context_subject_id !== (int) $subject->id) {
            $view_subject = $this->Academic_model->get_subject($context_subject_id);
            if (!$view_subject) {
                show_404();
            }

            if ($this->is_student_content_view() || !$this->can_access_subject_content_page($view_subject) || empty($lesson->is_published) || empty($module->is_published) || !$this->can_access_shared_grade_level_lesson($subject, $module)) {
                show_error('You do not have permission to view completions for this lesson.', 403);
            }
        }

        $this->require_section_manager($view_subject->id);

        $completion_student_ids = $this->get_completion_student_ids_for_subject($view_subject->id);
        $back_param = (string) $this->input->get('back', TRUE);

        $data['title']        = 'Lesson Completions: ' . $lesson->title;
        $data['lesson']       = $lesson;
        $data['module']       = $module;
        $data['subject']      = $view_subject;
        $data['source_subject'] = $subject;
        $data['back_url']     = $back_param !== '' ? site_url($back_param) : site_url('course/content/' . $view_subject->id);
        $data['completions']  = $this->Lesson_model->get_lesson_completions($lesson_id, $completion_student_ids);

        $this->render('course/lesson_completions', $data);
    }

    public function edit_subject_section($subject_id)
    {
        $this->require_section_manager($subject_id);
        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject) show_404();

        if ($this->input->method() === 'post') {
            $class_program_id = $this->input->post('class_program_id');
            $section_name = trim($this->input->post('section_name', TRUE));
            $enrollment_key = $this->input->post('enrollment_key', TRUE);

            if ($class_program_id && $section_name) {
                $this->Academic_model->update_subject_section($class_program_id, $subject_id, $section_name, $enrollment_key);
                $this->session->set_flashdata('success', 'Section access updated.');
            }
        }
        redirect('course/content/' . $subject_id . '?edit=1');
    }

    public function upload_cover_photo($subject_id)
    {
        $this->require_course_manager($subject_id);
        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject) show_404();

        if (!empty($_FILES['cover_photo']['name'])) {
            $upload_path = FCPATH . 'uploads/covers/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
            $config['max_size'] = 5120;
            $config['encrypt_name'] = true;
            $config['file_name'] = 'cover_' . $subject_id . '_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('cover_photo')) {
                $upload_data = $this->upload->data();
                
                if (!empty($subject->cover_photo) && file_exists($upload_path . $subject->cover_photo)) {
                    unlink($upload_path . $subject->cover_photo);
                }

                $this->Academic_model->update_subject_cover_photo($subject_id, $upload_data['file_name']);
                $this->session->set_flashdata('success', 'Cover photo uploaded successfully.');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            }
        }

        redirect('course/content/' . $subject_id . '?edit=1');
    }

    public function remove_cover_photo($subject_id)
    {
        $this->require_course_manager($subject_id);
        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject) show_404();

        if (!empty($subject->cover_photo)) {
            $upload_path = FCPATH . 'uploads/covers/';
            if (file_exists($upload_path . $subject->cover_photo)) {
                unlink($upload_path . $subject->cover_photo);
            }
            $this->Academic_model->update_subject_cover_photo($subject_id, null);
            $this->session->set_flashdata('success', 'Cover photo removed successfully.');
        }

        redirect('course/content/' . $subject_id . '?edit=1');
    }

    // ---- Module Management ----
    public function create_module($subject_id)
    {
        $this->require_course_manager($subject_id);
        if ($this->input->method() === 'post') {
            $order = $this->Lesson_model->get_next_order('modules', 'subject_id', $subject_id);
            $data = array(
                'subject_id'  => $subject_id,
                'title'       => $this->input->post('title', TRUE),
                'description' => $this->input->post('description', TRUE),
                'order_num'   => $order,
                'is_published' => $this->input->post('is_published') ? 1 : 0,
                'created_by'  => $this->current_user->id,
            );
            $this->Lesson_model->create_module($data);
            $this->session->set_flashdata('success', 'Module created successfully.');
        }
        redirect('course/content/' . $subject_id . '?edit=1');
    }

    public function edit_module($module_id)
    {
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        $this->require_module_owner($module, 'You can only edit modules that you created.');
        
        if ($this->input->method() === 'post') {
            $data = array(
                'title'       => $this->input->post('title', TRUE),
                'description' => $this->input->post('description', TRUE),
                'is_published' => $this->input->post('is_published') ? 1 : 0,
            );
            $this->Lesson_model->update_module($module_id, $data);
            $this->session->set_flashdata('success', 'Module updated successfully.');
        }
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    public function delete_module($module_id)
    {
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        $this->require_module_owner($module, 'You can only delete modules that you created.');
        
        $this->Lesson_model->delete_module($module_id);
        $this->session->set_flashdata('success', 'Module deleted successfully.');
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    public function reorder_subject_modules($subject_id)
    {
        $this->output->set_content_type('application/json');

        $respond = function ($success, $message, $status_code = 200) {
            $this->output
                ->set_status_header($status_code)
                ->set_output(json_encode(array(
                    'success' => (bool) $success,
                    'message' => (string) $message,
                    'csrf_token_name' => $this->security->get_csrf_token_name(),
                    'csrf_hash' => $this->security->get_csrf_hash(),
                )));
        };

        if ($this->input->method() !== 'post') {
            $respond(false, 'Invalid request method.', 405);
            return;
        }

        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject) {
            $respond(false, 'Subject not found.', 404);
            return;
        }

        if (!$this->can_reorder_subject_modules($subject_id)) {
            $respond(false, 'You do not have permission to reorder these modules.', 403);
            return;
        }

        $module_ids = $this->input->post('module_ids');
        if (!is_array($module_ids) || empty($module_ids)) {
            $respond(false, 'No module order was provided.', 422);
            return;
        }

        $normalized_module_ids = array();
        foreach ($module_ids as $module_id) {
            $module_id = (int) $module_id;
            if ($module_id < 1) {
                $respond(false, 'Invalid module order payload.', 422);
                return;
            }

            $normalized_module_ids[] = $module_id;
        }

        if (!$this->Lesson_model->reorder_subject_modules($subject_id, $normalized_module_ids)) {
            $respond(false, 'Unable to save the new module order.', 500);
            return;
        }

        $respond(true, 'Module order updated successfully.');
    }

    // ---- Lesson Management ----
    public function create_lesson($module_id)
    {
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        $this->require_module_owner($module, 'You can only add lessons to modules that you created.');
        
        if ($this->input->method() === 'post') {
            $order = $this->Lesson_model->get_next_content_order($module_id);
            $content_type = $this->normalize_lesson_content_type($this->input->post('content_type', TRUE));

            $file_path = '';
            if ($content_type === 'file') {
                $upload_result = $this->upload_lesson_file();
                if (!$upload_result['success']) {
                    $this->session->set_flashdata('error', $upload_result['error']);
                    redirect('course/content/' . $module->subject_id . '?edit=1');
                }
                $file_path = $upload_result['path'];
            }

            $learning_competency_id = $this->resolve_subject_learning_competency_id($module->subject_id);
            if ($learning_competency_id === false) {
                $this->session->set_flashdata('error', 'Please select a valid learning competency for this subject.');
                redirect('course/content/' . $module->subject_id . '?edit=1');
            }

            $data = array(
                'module_id'               => $module_id,
                'learning_competency_id'  => $learning_competency_id,
                'title'                   => $this->input->post('title', TRUE),
                'content'                 => $this->build_lesson_content($content_type, $this->input->post('content'), $this->input->post('video_url', TRUE), $file_path, $this->input->post('link_url', TRUE)),
                'content_type'            => $content_type,
                'file_path'               => $file_path,
                'order_num'               => $order,
                'is_published'            => $this->input->post('is_published') ? 1 : 0,
            );
            $this->Lesson_model->create_lesson($data);
            $this->session->set_flashdata('success', 'Lesson created successfully.');
        }
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    public function edit_lesson($lesson_id)
    {
        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) show_404();
        $module = $this->Lesson_model->get_module($lesson->module_id);
        $this->require_module_owner($module, 'You can only edit lessons that you created.');
        
        if ($this->input->method() === 'post') {
            $content_type = $this->normalize_lesson_content_type($this->input->post('content_type', TRUE));

            $file_path = $lesson->file_path;
            if ($content_type === 'file') {
                if (isset($_FILES['file_upload']) && !empty($_FILES['file_upload']['name'])) {
                    $upload_result = $this->upload_lesson_file();
                    if (!$upload_result['success']) {
                        $this->session->set_flashdata('error', $upload_result['error']);
                        redirect('course/content/' . $module->subject_id . '?edit=1');
                    }
                    $file_path = $upload_result['path'];
                } elseif (trim((string) $file_path) === '') {
                    $this->session->set_flashdata('error', 'Please select a PDF file to upload.');
                    redirect('course/content/' . $module->subject_id . '?edit=1');
                }
            }

            $learning_competency_id = $this->resolve_subject_learning_competency_id($module->subject_id);
            if ($learning_competency_id === false) {
                $this->session->set_flashdata('error', 'Please select a valid learning competency for this subject.');
                redirect('course/content/' . $module->subject_id . '?edit=1');
            }

            $data = array(
                'learning_competency_id'  => $learning_competency_id,
                'title'                   => $this->input->post('title', TRUE),
                'content'                 => $this->build_lesson_content($content_type, $this->input->post('content'), $this->input->post('video_url', TRUE), $file_path, $this->input->post('link_url', TRUE)),
                'content_type'            => $content_type,
                'file_path'               => $file_path,
                'is_published'            => $this->input->post('is_published') ? 1 : 0,
            );
            $this->Lesson_model->update_lesson($lesson_id, $data);
            $this->session->set_flashdata('success', 'Lesson updated successfully.');
        }
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    public function mark_lesson_taught($lesson_id)
    {
        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) show_404();

        $module = $this->Lesson_model->get_module($lesson->module_id);
        if (!$module) show_404();

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) show_404();

        if ($this->input->method() !== 'post') {
            redirect($this->get_course_content_redirect($subject->id, $this->input->get('return_query', TRUE)));
        }

        if (!$this->can_access_subject_content_page($subject)) {
            show_error('You do not have permission to mark this lesson as taught.', 403);
        }

        $this->require_section_manager($subject->id);
        $this->Lesson_model->mark_lesson_taught($lesson_id, (int) $this->current_user->id);
        $this->session->set_flashdata('success', 'Lesson marked as taught.');

        redirect($this->get_course_content_redirect($subject->id, $this->input->post('return_query', TRUE)));
    }

    public function clear_lesson_taught($lesson_id)
    {
        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) show_404();

        $module = $this->Lesson_model->get_module($lesson->module_id);
        if (!$module) show_404();

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) show_404();

        if ($this->input->method() !== 'post') {
            redirect($this->get_course_content_redirect($subject->id, $this->input->get('return_query', TRUE)));
        }

        if (!$this->can_access_subject_content_page($subject)) {
            show_error('You do not have permission to update this lesson status.', 403);
        }

        $this->require_section_manager($subject->id);
        $this->Lesson_model->clear_lesson_taught($lesson_id, (int) $this->current_user->id);
        $this->session->set_flashdata('success', 'Lesson taught status cleared.');

        redirect($this->get_course_content_redirect($subject->id, $this->input->post('return_query', TRUE)));
    }

    public function update_lesson_taught_date($lesson_id)
    {
        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) show_404();

        $module = $this->Lesson_model->get_module($lesson->module_id);
        if (!$module) show_404();

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) show_404();

        if ($this->input->method() !== 'post') {
            redirect($this->get_course_content_redirect($subject->id, $this->input->get('return_query', TRUE)));
        }

        if (!$this->can_access_subject_content_page($subject)) {
            show_error('You do not have permission to update this lesson status.', 403);
        }

        $this->require_section_manager($subject->id);

        $taught_status = $this->Lesson_model->get_lesson_taught_status($lesson_id, (int) $this->current_user->id);
        if (!$taught_status) {
            $this->session->set_flashdata('error', 'Please mark the lesson as taught first.');
            redirect($this->get_course_content_redirect($subject->id, $this->input->post('return_query', TRUE)));
        }

        $taught_at = $this->resolve_lesson_taught_datetime($this->input->post('taught_date', TRUE), $taught_status->taught_at);
        if ($taught_at === false) {
            $this->session->set_flashdata('error', 'Please select a valid taught date.');
            redirect($this->get_course_content_redirect($subject->id, $this->input->post('return_query', TRUE)));
        }

        $this->Lesson_model->update_lesson_taught_date($lesson_id, (int) $this->current_user->id, $taught_at);
        $this->session->set_flashdata('success', 'Lesson taught date updated.');

        redirect($this->get_course_content_redirect($subject->id, $this->input->post('return_query', TRUE)));
    }

    public function delete_lesson($lesson_id)
    {
        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) show_404();
        $module = $this->Lesson_model->get_module($lesson->module_id);
        $this->require_module_owner($module, 'You can only delete lessons that you created.');
        
        $this->Lesson_model->delete_lesson($lesson_id);
        $this->session->set_flashdata('success', 'Lesson deleted successfully.');
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    public function reorder_module_items($module_id)
    {
        $this->output->set_content_type('application/json');

        $respond = function ($success, $message, $status_code = 200) {
            $this->output
                ->set_status_header($status_code)
                ->set_output(json_encode(array(
                    'success' => (bool) $success,
                    'message' => (string) $message,
                    'csrf_token_name' => $this->security->get_csrf_token_name(),
                    'csrf_hash' => $this->security->get_csrf_hash(),
                )));
        };

        if ($this->input->method() !== 'post') {
            $respond(false, 'Invalid request method.', 405);
            return;
        }

        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) {
            $respond(false, 'Module not found.', 404);
            return;
        }

        if (!$this->can_manage_module_content($module)) {
            $respond(false, 'You do not have permission to reorder this content.', 403);
            return;
        }

        $items = $this->input->post('items');
        if (!is_array($items) || empty($items)) {
            $respond(false, 'No content order was provided.', 422);
            return;
        }

        $normalized_items = array();
        foreach ($items as $item) {
            $item_id = isset($item['id']) ? (int) $item['id'] : 0;
            $item_type = isset($item['item_type']) ? (string) $item['item_type'] : '';

            if ($item_id < 1 || !in_array($item_type, array('lesson', 'activity'), true)) {
                $respond(false, 'Invalid content order payload.', 422);
                return;
            }

            $normalized_items[] = array(
                'id' => $item_id,
                'item_type' => $item_type,
            );
        }

        if (!$this->Lesson_model->reorder_module_content($module_id, $normalized_items)) {
            $respond(false, 'Unable to save the new content order.', 500);
            return;
        }

        $respond(true, 'Content order updated successfully.');
    }

    // ---- Activity Management ----
    public function create_activity($module_id)
    {
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        $this->require_module_owner($module, 'You can only add content to modules that you created.');
        
        if ($this->input->method() === 'post') {
            $type = $this->input->post('type', TRUE);
            $type = in_array($type, array('assignment', 'quiz', 'forum', 'resource', 'page', 'label')) ? $type : 'page';
            $order = $this->Lesson_model->get_next_content_order($module_id);
            $data = array(
                'module_id'     => $module_id,
                'type'          => $type,
                'title'         => $this->input->post('title', TRUE),
                'content'       => $this->input->post('content'),
                'settings'      => json_encode($this->input->post('settings') ?: []),
                'order_num'     => $order,
                'is_published'  => $this->input->post('is_published') ? 1 : 0,
            );

            if ($type === 'quiz') {
                $subject = $this->Academic_model->get_subject($module->subject_id);
                if (!$subject) show_404();

                $this->db->trans_start();
                $activity_id = $this->Lesson_model->create_activity($data);
                $quiz_id = $this->Quiz_model->create_quiz(array(
                    'course_id'          => $subject->id,
                    'class_program_id'   => $module->class_program_id ?: null,
                    'school_id'          => $this->school_id,
                    'title'              => $data['title'],
                    'description'        => $data['content'],
                    'quiz_type'          => 'quiz',
                    'component_id'       => $activity_id,
                    'total_points'       => 0,
                    'time_limit_minutes' => null,
                    'max_attempts'       => 1,
                    'shuffle_questions'  => 0,
                    'show_results'       => 1,
                    'is_published'       => $data['is_published'],
                    'created_by'         => $this->current_user ? $this->current_user->id : null,
                ));
                $this->Lesson_model->update_activity($activity_id, array(
                    'settings' => json_encode(array('quiz_id' => $quiz_id)),
                ));
                $this->db->trans_complete();

                if (!$this->db->trans_status()) {
                    $this->session->set_flashdata('error', 'Quiz activity could not be created.');
                    redirect('course/content/' . $module->subject_id . '?edit=1');
                }

                $import = $this->import_assessment_questions_from_upload($quiz_id);
                if (!$import['success']) {
                    $this->session->set_flashdata('warning', 'Quiz activity created, but import failed: ' . $import['message']);
                } elseif ($import['count'] > 0) {
                    $this->session->set_flashdata('success', 'Quiz activity created. ' . $import['message']);
                } else {
                    $this->session->set_flashdata('success', 'Quiz activity created successfully.');
                }

                redirect('course/assessment/' . $activity_id);
            }

            $this->Lesson_model->create_activity($data);
            $this->session->set_flashdata('success', 'Activity created successfully.');
        }
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    public function edit_activity($activity_id)
    {
        $activity = $this->Lesson_model->get_activity($activity_id);
        if (!$activity) show_404();
        $module = $this->Lesson_model->get_module($activity->module_id);
        $this->require_module_owner($module, 'You can only edit content that you created.');
        
        if ($this->input->method() === 'post') {
            $data = array(
                'type'          => $this->input->post('type', TRUE),
                'title'         => $this->input->post('title', TRUE),
                'content'       => $this->input->post('content'),
                'settings'      => json_encode($this->input->post('settings') ?: []),
                'is_published'  => $this->input->post('is_published') ? 1 : 0,
            );
            $this->Lesson_model->update_activity($activity_id, $data);
            $this->session->set_flashdata('success', 'Activity updated successfully.');
        }
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    // ---- Assessment Management ----
    private function get_assessment_context_by_activity($activity_id)
    {
        $activity = $this->Lesson_model->get_activity($activity_id);
        if (!$activity || $activity->type !== 'quiz') {
            return null;
        }

        $module = $this->Lesson_model->get_module($activity->module_id);
        if (!$module) {
            return null;
        }

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) {
            return null;
        }

        return array(
            'activity' => $activity,
            'module'   => $module,
            'subject'  => $subject,
            'quiz'     => $this->Quiz_model->get_quiz_by_activity($activity->id),
        );
    }

    private function get_assessment_context_by_quiz($quiz_id)
    {
        $quiz = $this->Quiz_model->get_quiz($quiz_id);
        if (!$quiz || empty($quiz->component_id)) {
            return null;
        }

        $context = $this->get_assessment_context_by_activity($quiz->component_id);
        if (!$context) {
            return null;
        }

        $context['quiz'] = $quiz;
        return $context;
    }

    private function get_or_create_quiz_for_activity($activity, $module, $subject)
    {
        $quiz = $this->Quiz_model->get_quiz_by_activity($activity->id);
        if ($quiz) {
            return $quiz;
        }

        $quiz_id = $this->Quiz_model->create_quiz(array(
            'course_id'          => $subject->id,
            'class_program_id'   => $module->class_program_id ?: null,
            'school_id'          => $this->school_id,
            'title'              => $activity->title,
            'description'        => $activity->content,
            'quiz_type'          => 'quiz',
            'component_id'       => $activity->id,
            'total_points'       => 0,
            'time_limit_minutes' => null,
            'max_attempts'       => 1,
            'shuffle_questions'  => 0,
            'show_results'       => 1,
            'is_published'       => $activity->is_published ? 1 : 0,
            'created_by'         => $this->current_user ? $this->current_user->id : null,
        ));

        $this->Lesson_model->update_activity($activity->id, array(
            'settings' => json_encode(array('quiz_id' => $quiz_id)),
        ));

        return $this->Quiz_model->get_quiz($quiz_id);
    }

    private function clean_import_text($value)
    {
        $value = html_entity_decode((string) $value, ENT_QUOTES, 'UTF-8');
        $value = trim(strip_tags($value));
        return preg_replace('/\s+/', ' ', $value);
    }

    private function parse_gift_answer_tokens($answer_text)
    {
        $tokens = array();
        $marker = null;
        $buffer = '';
        $escaped = false;
        $length = strlen($answer_text);

        for ($i = 0; $i < $length; $i++) {
            $char = $answer_text[$i];

            if ($escaped) {
                $buffer .= $char;
                $escaped = false;
                continue;
            }

            if ($char === '\\') {
                $escaped = true;
                continue;
            }

            if ($char === '=' || $char === '~') {
                if ($marker !== null) {
                    $tokens[] = array('marker' => $marker, 'text' => $buffer);
                }
                $marker = $char;
                $buffer = '';
                continue;
            }

            $buffer .= $char;
        }

        if ($marker !== null) {
            $tokens[] = array('marker' => $marker, 'text' => $buffer);
        }

        foreach ($tokens as &$token) {
            $token['text'] = preg_replace('/#.*/', '', $token['text']);
            $token['text'] = $this->clean_import_text($token['text']);
        }
        unset($token);

        return array_values(array_filter($tokens, function($token) {
            return $token['text'] !== '';
        }));
    }

    private function parse_gift_questions($content)
    {
        $content = str_replace(array("\r\n", "\r"), "\n", trim($content));
        $blocks = preg_split("/\n\s*\n/", $content);
        $questions = array();
        $errors = array();

        foreach ($blocks as $index => $block) {
            $block = trim(preg_replace('/^\s*\/\/.*$/m', '', $block));
            if ($block === '') {
                continue;
            }

            if (!preg_match('/^(.*?)\{(.*)\}\s*$/s', $block, $matches)) {
                $errors[] = 'GIFT item #' . ($index + 1) . ' was skipped because it has no answer block.';
                continue;
            }

            $question_text = trim($matches[1]);
            if (preg_match('/^\s*::.*?::(.*)$/s', $question_text, $title_match)) {
                $question_text = trim($title_match[1]);
            }
            $question_text = $this->clean_import_text($question_text);
            $answer_text = trim($matches[2]);

            if ($question_text === '') {
                $errors[] = 'GIFT item #' . ($index + 1) . ' was skipped because the question text is empty.';
                continue;
            }

            if ($answer_text === '') {
                $questions[] = array(
                    'question_type' => 'essay',
                    'question_text' => $question_text,
                    'points'        => 1,
                    'choices'       => array(),
                );
                continue;
            }

            if (preg_match('/^(TRUE|FALSE|T|F)$/i', $answer_text, $tf_match)) {
                $correct_true = strtoupper($tf_match[1][0]) === 'T';
                $questions[] = array(
                    'question_type' => 'true_false',
                    'question_text' => $question_text,
                    'points'        => 1,
                    'choices'       => array(
                        array('text' => 'True', 'is_correct' => $correct_true ? 1 : 0),
                        array('text' => 'False', 'is_correct' => $correct_true ? 0 : 1),
                    ),
                );
                continue;
            }

            $tokens = $this->parse_gift_answer_tokens($answer_text);
            if (empty($tokens)) {
                $errors[] = 'GIFT item #' . ($index + 1) . ' was skipped because no valid answers were found.';
                continue;
            }

            $has_wrong_choice = false;
            foreach ($tokens as $token) {
                if ($token['marker'] === '~') {
                    $has_wrong_choice = true;
                    break;
                }
            }

            if ($has_wrong_choice) {
                $choices = array();
                foreach ($tokens as $token) {
                    $choices[] = array(
                        'text'       => $token['text'],
                        'is_correct' => $token['marker'] === '=' ? 1 : 0,
                    );
                }

                $questions[] = array(
                    'question_type' => 'multiple_choice',
                    'question_text' => $question_text,
                    'points'        => 1,
                    'choices'       => $choices,
                );
            } else {
                $choices = array();
                foreach ($tokens as $token) {
                    if ($token['marker'] === '=') {
                        $choices[] = array('text' => $token['text'], 'is_correct' => 1);
                    }
                }

                $questions[] = array(
                    'question_type' => 'identification',
                    'question_text' => $question_text,
                    'points'        => 1,
                    'choices'       => $choices,
                );
            }
        }

        return array('questions' => $questions, 'errors' => $errors);
    }

    private function parse_moodle_xml_questions($content)
    {
        if (!class_exists('SimpleXMLElement')) {
            return array('questions' => array(), 'errors' => array('SimpleXML is not enabled on this server.'));
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOCDATA);
        if (!$xml) {
            return array('questions' => array(), 'errors' => array('Invalid Moodle XML file.'));
        }

        $questions = array();
        $errors = array();
        $question_number = 0;
        foreach ($xml->question as $index => $node) {
            $type = strtolower((string) $node['type']);
            if ($type === 'category') {
                continue;
            }

            $question_number++;

            $question_text = $this->clean_import_text((string) $node->questiontext->text);
            if ($question_text === '') {
                $question_text = $this->clean_import_text((string) $node->name->text);
            }

            if ($question_text === '') {
                $errors[] = 'XML question #' . $question_number . ' was skipped because the question text is empty.';
                continue;
            }

            $points = (float) $node->defaultgrade;
            if ($points <= 0) {
                $points = 1;
            }

            if ($type === 'multichoice') {
                $choices = array();
                foreach ($node->answer as $answer) {
                    $choice_text = $this->clean_import_text((string) $answer->text);
                    if ($choice_text === '') {
                        continue;
                    }
                    $choices[] = array(
                        'text'       => $choice_text,
                        'is_correct' => ((float) $answer['fraction']) > 0 ? 1 : 0,
                    );
                }

                if (count($choices) < 2) {
                    $errors[] = 'XML multiple choice question #' . $question_number . ' was skipped because it has fewer than two choices.';
                    continue;
                }

                $questions[] = array(
                    'question_type' => 'multiple_choice',
                    'question_text' => $question_text,
                    'points'        => $points,
                    'choices'       => $choices,
                );
            } elseif ($type === 'truefalse') {
                $correct_true = true;
                foreach ($node->answer as $answer) {
                    if (((float) $answer['fraction']) > 0) {
                        $correct_true = strtolower($this->clean_import_text((string) $answer->text)) === 'true';
                        break;
                    }
                }

                $questions[] = array(
                    'question_type' => 'true_false',
                    'question_text' => $question_text,
                    'points'        => $points,
                    'choices'       => array(
                        array('text' => 'True', 'is_correct' => $correct_true ? 1 : 0),
                        array('text' => 'False', 'is_correct' => $correct_true ? 0 : 1),
                    ),
                );
            } elseif ($type === 'shortanswer') {
                $choices = array();
                foreach ($node->answer as $answer) {
                    if (((float) $answer['fraction']) <= 0) {
                        continue;
                    }
                    $answer_text = $this->clean_import_text((string) $answer->text);
                    if ($answer_text !== '') {
                        $choices[] = array('text' => $answer_text, 'is_correct' => 1);
                    }
                }

                if (empty($choices)) {
                    $errors[] = 'XML short answer question #' . $question_number . ' was skipped because it has no correct answer.';
                    continue;
                }

                $questions[] = array(
                    'question_type' => 'identification',
                    'question_text' => $question_text,
                    'points'        => $points,
                    'choices'       => $choices,
                );
            } elseif ($type === 'essay') {
                $questions[] = array(
                    'question_type' => 'essay',
                    'question_text' => $question_text,
                    'points'        => $points,
                    'choices'       => array(),
                );
            } else {
                $errors[] = 'XML question #' . $question_number . ' was skipped because type "' . $type . '" is not supported.';
            }
        }

        return array('questions' => $questions, 'errors' => $errors);
    }

    private function save_imported_questions($quiz_id, $questions)
    {
        if (empty($questions)) {
            return 0;
        }

        $this->db->trans_start();
        $order = $this->Quiz_model->get_next_question_order($quiz_id);
        foreach ($questions as $question) {
            $question_id = $this->Quiz_model->create_question(array(
                'quiz_id'       => $quiz_id,
                'question_type' => $question['question_type'],
                'question_text' => $question['question_text'],
                'points'        => $question['points'],
                'order_num'     => $order++,
            ));

            if (!empty($question['choices'])) {
                $this->Quiz_model->save_choices($question_id, $question['choices']);
            }
        }
        $this->Quiz_model->recalculate_total_points($quiz_id);
        $this->db->trans_complete();

        return $this->db->trans_status() ? count($questions) : 0;
    }

    private function import_assessment_questions_from_upload($quiz_id)
    {
        $content = '';
        $format = strtolower($this->input->post('import_format', TRUE));
        
        // Check for pasted content first
        $pasted_content = $this->input->post('question_content', TRUE);
        if (!empty($pasted_content) && trim($pasted_content) !== '') {
            $content = $pasted_content;
        } 
        // Fall back to file upload
        elseif (!empty($_FILES['question_file']['name'])) {
            if ($_FILES['question_file']['error'] !== UPLOAD_ERR_OK) {
                return array('success' => false, 'count' => 0, 'message' => 'Question file upload failed.');
            }

            if ($_FILES['question_file']['size'] > 2097152) {
                return array('success' => false, 'count' => 0, 'message' => 'Question file must be 2MB or smaller.');
            }

            $extension = strtolower(pathinfo($_FILES['question_file']['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, array('gift', 'txt', 'xml'))) {
                return array('success' => false, 'count' => 0, 'message' => 'Only GIFT, TXT, and XML files are allowed.');
            }

            $content = file_get_contents($_FILES['question_file']['tmp_name']);
            
            // If format not specified, infer from extension
            if (!in_array($format, array('gift', 'xml'))) {
                $format = $extension === 'xml' ? 'xml' : 'gift';
            }
        } else {
            return array('success' => true, 'count' => 0, 'message' => '');
        }

        if (trim((string) $content) === '') {
            return array('success' => false, 'count' => 0, 'message' => 'Question content is empty.');
        }

        // Ensure format is valid
        if (!in_array($format, array('gift', 'xml'))) {
            $format = 'gift';
        }

        $parsed = $format === 'xml'
            ? $this->parse_moodle_xml_questions($content)
            : $this->parse_gift_questions($content);

        if (empty($parsed['questions'])) {
            $message = 'No supported questions were found.';
            if (!empty($parsed['errors'])) {
                $message .= ' ' . implode(' ', array_slice($parsed['errors'], 0, 3));
            }
            return array('success' => false, 'count' => 0, 'message' => $message);
        }

        $count = $this->save_imported_questions($quiz_id, $parsed['questions']);
        if ($count < 1) {
            return array('success' => false, 'count' => 0, 'message' => 'Questions could not be saved.');
        }

        $message = $count . ' question' . ($count === 1 ? '' : 's') . ' imported.';
        if (!empty($parsed['errors'])) {
            $message .= ' Skipped: ' . implode(' ', array_slice($parsed['errors'], 0, 3));
        }

        return array('success' => true, 'count' => $count, 'message' => $message);
    }

    private function assessment_availability_error($quiz)
    {
        $now = date('Y-m-d H:i:s');
        if (!empty($quiz->available_from) && $quiz->available_from > $now) {
            return 'This assessment is not yet available.';
        }
        if (!empty($quiz->available_until) && $quiz->available_until < $now) {
            return 'This assessment is already closed.';
        }
        return '';
    }

    private function get_assessment_attempt_remaining_seconds($attempt, $quiz)
    {
        if (!$attempt || !$quiz || empty($quiz->time_limit_minutes) || empty($attempt->started_at)) {
            return null;
        }

        $start_time = strtotime($attempt->started_at);
        if ($start_time === false) {
            return null;
        }

        $end_time = $start_time + ((int) $quiz->time_limit_minutes * 60);
        return max(0, $end_time - time());
    }

    private function finalize_expired_assessment_attempt($attempt, $quiz)
    {
        $remaining_seconds = $this->get_assessment_attempt_remaining_seconds($attempt, $quiz);
        if ($remaining_seconds === null || $remaining_seconds > 0) {
            return false;
        }

        $this->Quiz_model->submit_attempt($attempt->id);
        return true;
    }

    private function normalize_assessment_datetime($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $timestamp = strtotime($value);
        return $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;
    }

    public function create_assessment($module_id)
    {
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        $this->require_module_owner($module, 'You can only add assessments to modules that you created.');

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) show_404();

        if ($this->input->method() === 'post') {
            $title = trim($this->input->post('title', TRUE));
            if ($title === '') {
                $this->session->set_flashdata('error', 'Assessment title is required.');
                redirect('course/content/' . $module->subject_id . '?edit=1');
            }

            $quiz_type = $this->input->post('quiz_type', TRUE);
            $quiz_type = in_array($quiz_type, array('quiz', 'exam')) ? $quiz_type : 'quiz';
            $is_published = $this->input->post('is_published') ? 1 : 0;
            $max_attempts = max(1, (int) $this->input->post('max_attempts', TRUE));
            $time_limit = (int) $this->input->post('time_limit_minutes', TRUE);
            $time_limit = $time_limit > 0 ? $time_limit : null;
            $passing_score = (float) $this->input->post('passing_score', TRUE);
            $passing_score = $passing_score > 0 ? $passing_score : null;
            $quiz_password = $this->input->post('quiz_password', TRUE);
            $quiz_password = !empty($quiz_password) ? $quiz_password : null;

            $this->db->trans_start();
            $activity_id = $this->Lesson_model->create_activity(array(
                'module_id'    => $module_id,
                'type'         => 'quiz',
                'title'        => $title,
                'content'      => $this->input->post('description'),
                'settings'     => json_encode(array()),
                'order_num'    => $this->Lesson_model->get_next_content_order($module_id),
                'is_published' => $is_published,
            ));

            $quiz_id = $this->Quiz_model->create_quiz(array(
                'course_id'          => $subject->id,
                'class_program_id'   => $module->class_program_id ?: null,
                'school_id'          => $this->school_id,
                'title'              => $title,
                'description'        => $this->input->post('description'),
                'quiz_type'          => $quiz_type,
                'component_id'       => $activity_id,
                'total_points'       => 0,
                'time_limit_minutes' => $time_limit,
                'max_attempts'       => $max_attempts,
                'shuffle_questions'  => $this->input->post('shuffle_questions') ? 1 : 0,
                'show_results'       => $this->input->post('show_results') ? 1 : 0,
                'available_from'     => $this->normalize_assessment_datetime($this->input->post('available_from', TRUE)),
                'available_until'    => $this->normalize_assessment_datetime($this->input->post('available_until', TRUE)),
                'is_published'       => $is_published,
                'passing_score'      => $passing_score,
                'quiz_password'      => $quiz_password,
                'created_by'         => $this->current_user ? $this->current_user->id : null,
            ));

            $this->Lesson_model->update_activity($activity_id, array(
                'settings' => json_encode(array('quiz_id' => $quiz_id)),
            ));
            $this->db->trans_complete();

            if (!$this->db->trans_status()) {
                $this->session->set_flashdata('error', 'Assessment could not be created.');
                redirect('course/content/' . $module->subject_id . '?edit=1');
            }

            $import = $this->import_assessment_questions_from_upload($quiz_id);
            if (!$import['success']) {
                $this->session->set_flashdata('warning', 'Assessment created, but import failed: ' . $import['message']);
            } elseif ($import['count'] > 0) {
                $this->session->set_flashdata('success', 'Assessment created. ' . $import['message']);
            } else {
                $this->session->set_flashdata('success', 'Assessment created successfully.');
            }

            redirect('course/assessment/' . $activity_id);
        }

        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    public function edit_assessment($quiz_id)
    {
        $context = $this->get_assessment_context_by_quiz($quiz_id);
        if (!$context) show_404();
        $this->require_module_owner($context['module'], 'You can only edit assessments that you created.');

        if ($this->input->method() === 'post') {
            $title = trim($this->input->post('title', TRUE));
            if ($title === '') {
                $this->session->set_flashdata('error', 'Assessment title is required.');
                redirect('course/assessment/' . $context['activity']->id);
            }

            $quiz_type = $this->input->post('quiz_type', TRUE);
            $quiz_type = in_array($quiz_type, array('quiz', 'exam')) ? $quiz_type : 'quiz';
            $is_published = $this->input->post('is_published') ? 1 : 0;
            $max_attempts = max(1, (int) $this->input->post('max_attempts', TRUE));
            $time_limit = (int) $this->input->post('time_limit_minutes', TRUE);
            $time_limit = $time_limit > 0 ? $time_limit : null;
            $passing_score = (float) $this->input->post('passing_score', TRUE);
            $passing_score = $passing_score > 0 ? $passing_score : null;
            $quiz_password = $this->input->post('quiz_password', TRUE);
            $quiz_password = !empty($quiz_password) ? $quiz_password : null;

            $this->Lesson_model->update_activity($context['activity']->id, array(
                'title'        => $title,
                'content'      => $this->input->post('description'),
                'is_published' => $is_published,
            ));

            $this->Quiz_model->update_quiz($quiz_id, array(
                'title'              => $title,
                'description'        => $this->input->post('description'),
                'quiz_type'          => $quiz_type,
                'time_limit_minutes' => $time_limit,
                'max_attempts'       => $max_attempts,
                'shuffle_questions'  => $this->input->post('shuffle_questions') ? 1 : 0,
                'show_results'       => $this->input->post('show_results') ? 1 : 0,
                'available_from'     => $this->normalize_assessment_datetime($this->input->post('available_from', TRUE)),
                'available_until'    => $this->normalize_assessment_datetime($this->input->post('available_until', TRUE)),
                'is_published'       => $is_published,
                'passing_score'      => $passing_score,
                'quiz_password'      => $quiz_password,
            ));

            $this->session->set_flashdata('success', 'Assessment updated successfully.');
        }

        redirect('course/assessment/' . $context['activity']->id);
    }

    public function upload_assessment_questions($quiz_id)
    {
        $context = $this->get_assessment_context_by_quiz($quiz_id);
        if (!$context) show_404();
        $this->require_module_owner($context['module'], 'You can only update assessments that you created.');

        if ($this->input->method() === 'post') {
            $import = $this->import_assessment_questions_from_upload($quiz_id);
            $this->session->set_flashdata($import['success'] ? 'success' : 'error', $import['message']);
        }

        redirect('course/assessment/' . $context['activity']->id);
    }

    public function delete_assessment_question($question_id)
    {
        $question = $this->Quiz_model->get_question($question_id);
        if (!$question) show_404();

        $context = $this->get_assessment_context_by_quiz($question->quiz_id);
        if (!$context) show_404();
        $this->require_module_owner($context['module'], 'You can only update assessments that you created.');

        $this->Quiz_model->delete_question($question_id);
        $this->Quiz_model->recalculate_total_points($question->quiz_id);
        $this->session->set_flashdata('success', 'Question deleted successfully.');
        redirect('course/assessment/' . $context['activity']->id);
    }

    public function assessment($activity_id = null)
    {
        if (!$activity_id) {
            redirect('subjects');
        }

        $context = $this->get_assessment_context_by_activity($activity_id);
        if (!$context) show_404();

        $activity = $context['activity'];
        $module = $context['module'];
        $subject = $context['subject'];
        $quiz = $context['quiz'];

        if ($this->is_student_content_view()) {
            if (!$module->is_published || !$activity->is_published || !$quiz || !$quiz->is_published) {
                show_404();
            }

            if (!$this->has_subject_access($subject->id)) {
                $this->session->set_flashdata('error', 'Enter the enrollment key to access this course.');
                redirect('course/content/' . $subject->id);
            }

            if (!$this->is_item_accessible($activity->id, 'quiz', $subject->id)) {
                $this->session->set_flashdata('error', 'Complete the previous item first.');
                redirect('course/content/' . $subject->id);
            }

            $questions_count = $this->Quiz_model->count_questions($quiz->id);
            $attempts = $this->Quiz_model->get_student_attempts($quiz->id, $this->current_user->id);
            $in_progress_attempt = $this->Quiz_model->get_in_progress_attempt($quiz->id, $this->current_user->id);
            if ($in_progress_attempt && $this->finalize_expired_assessment_attempt($in_progress_attempt, $quiz)) {
                $attempts = $this->Quiz_model->get_student_attempts($quiz->id, $this->current_user->id);
                $in_progress_attempt = null;
            }
            $availability_error = $this->assessment_availability_error($quiz);
            $max_attempts = max(1, (int) $quiz->max_attempts);
            $can_start = $questions_count > 0 && !$availability_error && (!$in_progress_attempt && count($attempts) < $max_attempts);
            $latest_result_attempt = null;

            foreach ($attempts as $attempt) {
                if ($attempt->status !== 'in_progress') {
                    $latest_result_attempt = $attempt;
                }
            }

            $data['title'] = 'Assessment: ' . $quiz->title;
            $data['subject'] = $subject;
            $data['module'] = $module;
            $data['activity'] = $activity;
            $data['quiz'] = $quiz;
            $data['questions_count'] = $questions_count;
            $data['attempts'] = $attempts;
            $data['in_progress_attempt'] = $in_progress_attempt;
            $data['availability_error'] = $availability_error;
            $data['can_start'] = $can_start;
            $data['latest_result_attempt'] = $latest_result_attempt;
            $data['student_content_view'] = true;
            $this->render('course/assessment_intro', $data);
            return;
        }

        $this->require_section_manager($subject->id);
        $quiz = $this->get_or_create_quiz_for_activity($activity, $module, $subject);
        $questions = $this->Quiz_model->get_questions_with_choices($quiz->id);

        $data['title'] = 'Manage Assessment: ' . $quiz->title;
        $data['subject'] = $subject;
        $data['module'] = $module;
        $data['activity'] = $activity;
        $data['quiz'] = $quiz;
        $data['questions'] = $questions;
        $data['attempts'] = $this->Quiz_model->get_all_attempts($quiz->id);
        $data['analysis'] = $this->Quiz_model->get_quiz_analysis($quiz->id);
        $lang = $this->input->get('lang', TRUE) ? $this->input->get('lang', TRUE) : 'en';
        $data['analysis_description'] = $this->Quiz_model->generate_analysis_description($data['analysis'], $lang);
        $data['current_lang'] = $lang;
        $data['student_content_view'] = false;
        $data['can_edit_assessment'] = $this->can_manage_module_content($module);
        
        // Check if new columns exist
        $data['has_passing_score'] = $this->db->query("SHOW COLUMNS FROM quizzes LIKE 'passing_score'")->num_rows() > 0;
        $data['has_quiz_password'] = $this->db->query("SHOW COLUMNS FROM quizzes LIKE 'quiz_password'")->num_rows() > 0;
        
        $this->render('course/assessment_manage', $data);
    }

    public function start_assessment($quiz_id)
    {
        if (!$this->is_student_content_view() || !$this->current_user) {
            show_error('Assessments can only be taken by students.', 403);
        }

        $context = $this->get_assessment_context_by_quiz($quiz_id);
        if (!$context) show_404();

        $quiz = $context['quiz'];
        $subject = $context['subject'];
        if (!$context['module']->is_published || !$context['activity']->is_published || !$quiz->is_published) {
            show_404();
        }

        if (!$this->has_subject_access($subject->id)) {
            $this->session->set_flashdata('error', 'Enter the enrollment key to access this course.');
            redirect('course/content/' . $subject->id);
        }

        $availability_error = $this->assessment_availability_error($quiz);
        if ($availability_error) {
            $this->session->set_flashdata('error', $availability_error);
            redirect('course/assessment/' . $context['activity']->id);
        }

        if ($this->Quiz_model->count_questions($quiz->id) < 1) {
            $this->session->set_flashdata('error', 'This assessment has no questions yet.');
            redirect('course/assessment/' . $context['activity']->id);
        }

        // Check quiz password if set
        if (!empty($quiz->quiz_password)) {
            if ($this->input->method() === 'post') {
                $entered_password = $this->input->post('quiz_password', TRUE);
                if ($entered_password !== $quiz->quiz_password) {
                    $this->session->set_flashdata('error', 'Incorrect quiz password.');
                    redirect('course/assessment/' . $context['activity']->id);
                }
            } else {
                // Show password input form
                $data['title'] = 'Enter Quiz Password';
                $data['quiz'] = $quiz;
                $data['activity'] = $context['activity'];
                $data['subject'] = $subject;
                $this->render('course/assessment_password', $data);
                return;
            }
        }

        $in_progress = $this->Quiz_model->get_in_progress_attempt($quiz->id, $this->current_user->id);
        if ($in_progress && $this->finalize_expired_assessment_attempt($in_progress, $quiz)) {
            $this->session->set_flashdata('warning', 'Your previous assessment attempt expired and was submitted automatically.');
            $in_progress = null;
        }
        if ($in_progress) {
            redirect('course/assessment_attempt/' . $in_progress->id);
        }

        $attempts = $this->Quiz_model->get_student_attempts($quiz->id, $this->current_user->id);
        if (count($attempts) >= max(1, (int) $quiz->max_attempts)) {
            $this->session->set_flashdata('error', 'Maximum attempts reached.');
            redirect('course/assessment/' . $context['activity']->id);
        }

        $attempt_id = $this->Quiz_model->start_attempt($quiz->id, $this->current_user->id);
        redirect('course/assessment_attempt/' . $attempt_id);
    }

    public function assessment_attempt($attempt_id)
    {
        if (!$this->is_student_content_view() || !$this->current_user) {
            show_error('Assessments can only be taken by students.', 403);
        }

        $attempt = $this->Quiz_model->get_attempt($attempt_id);
        if (!$attempt || (int) $attempt->student_id !== (int) $this->current_user->id) {
            show_404();
        }

        if ($attempt->status !== 'in_progress') {
            redirect('course/assessment_result/' . $attempt->id);
        }

        $context = $this->get_assessment_context_by_quiz($attempt->quiz_id);
        if (!$context) show_404();

        if (!$context['module']->is_published || !$context['activity']->is_published || !$context['quiz']->is_published) {
            show_404();
        }

        if (!$this->has_subject_access($context['subject']->id)) {
            $this->session->set_flashdata('error', 'Enter the enrollment key to access this course.');
            redirect('course/content/' . $context['subject']->id);
        }

        if ($this->finalize_expired_assessment_attempt($attempt, $context['quiz'])) {
            $this->session->set_flashdata('warning', 'Time is up. Your assessment was submitted automatically.');
            redirect('course/assessment_result/' . $attempt->id);
            return;
        }

        $questions = $this->Quiz_model->get_questions_with_choices($context['quiz']->id);
        if (!empty($context['quiz']->shuffle_questions)) {
            shuffle($questions);
        }

        $data['title'] = 'Take Assessment: ' . $context['quiz']->title;
        $data['subject'] = $context['subject'];
        $data['module'] = $context['module'];
        $data['activity'] = $context['activity'];
        $data['quiz'] = $context['quiz'];
        $data['attempt'] = $attempt;
        $data['questions'] = $questions;
        $data['answer_map'] = $this->Quiz_model->get_attempt_answers_map($attempt->id);
        
        // Calculate remaining time
        $remaining_seconds = $this->get_assessment_attempt_remaining_seconds($attempt, $context['quiz']);
        if ($remaining_seconds !== null) {
            $data['remaining_seconds'] = $remaining_seconds;
        } else {
            $data['remaining_seconds'] = null;
        }
        
        $this->render('course/assessment_attempt', $data);
    }

    public function submit_assessment($attempt_id)
    {
        if (!$this->is_student_content_view() || !$this->current_user) {
            show_error('Assessments can only be submitted by students.', 403);
        }

        $attempt = $this->Quiz_model->get_attempt($attempt_id);
        if (!$attempt || (int) $attempt->student_id !== (int) $this->current_user->id || $attempt->status !== 'in_progress') {
            show_404();
        }

        $context = $this->get_assessment_context_by_quiz($attempt->quiz_id);
        if (!$context) show_404();

        if (!$context['module']->is_published || !$context['activity']->is_published || !$context['quiz']->is_published) {
            show_404();
        }

        if (!$this->has_subject_access($context['subject']->id)) {
            $this->session->set_flashdata('error', 'Enter the enrollment key to access this course.');
            redirect('course/content/' . $context['subject']->id);
        }

        if ($this->input->method() === 'post') {
            $questions = $this->Quiz_model->get_questions_with_choices($attempt->quiz_id);
            $posted_answers = $this->input->post('answers') ?: array();

            foreach ($questions as $question) {
                $answer_value = isset($posted_answers[$question->id]) ? $posted_answers[$question->id] : null;
                $answer_data = array('answer_text' => null, 'choice_id' => null);

                if ($question->question_type === 'multiple_choice' || $question->question_type === 'true_false') {
                    $choice_id = (int) $answer_value;
                    foreach ($question->choices as $choice) {
                        if ((int) $choice->id === $choice_id) {
                            $answer_data['choice_id'] = $choice_id;
                            break;
                        }
                    }
                } else {
                    $answer_data['answer_text'] = trim((string) $answer_value);
                }

                if ($answer_data['choice_id'] || $answer_data['answer_text'] !== '') {
                    $this->Quiz_model->save_answer($attempt->id, $question->id, $answer_data);
                }
            }

            $this->Quiz_model->submit_attempt($attempt->id);
            
            // Mark activity as completed
            $this->mark_current_activity_completed($context['activity']->id);
            
            $this->session->set_flashdata('success', 'Assessment submitted successfully.');
        }

        redirect('course/assessment_result/' . $attempt->id);
    }

    public function assessment_result($attempt_id)
    {
        $attempt = $this->Quiz_model->get_attempt($attempt_id);
        if (!$attempt) show_404();

        $context = $this->get_assessment_context_by_quiz($attempt->quiz_id);
        if (!$context) show_404();

        $is_owner = $this->current_user && (int) $attempt->student_id === (int) $this->current_user->id;
        if (!$is_owner) {
            $this->require_course_manager($context['subject']->id);
        }

        $data['title'] = 'Assessment Result: ' . $context['quiz']->title;
        $data['subject'] = $context['subject'];
        $data['module'] = $context['module'];
        $data['activity'] = $context['activity'];
        $data['quiz'] = $context['quiz'];
        $data['attempt'] = $attempt;
        $data['questions'] = $this->Quiz_model->get_questions_with_choices($context['quiz']->id);
        $data['answer_map'] = $this->Quiz_model->get_attempt_answers_map($attempt->id);
        $data['show_results'] = !$this->is_student_content_view() || !empty($context['quiz']->show_results);
        $this->render('course/assessment_result', $data);
    }

    public function delete_activity($activity_id)
    {
        $activity = $this->Lesson_model->get_activity($activity_id);
        if (!$activity) show_404();
        $module = $this->Lesson_model->get_module($activity->module_id);
        $this->require_module_owner($module, 'You can only delete content that you created.');
        
        $this->Lesson_model->delete_activity($activity_id);
        if ($activity->type === 'quiz') {
            $quiz = $this->Quiz_model->get_quiz_by_activity($activity_id);
            if ($quiz) {
                $this->Quiz_model->delete_quiz($quiz->id);
            }
        }
        $this->session->set_flashdata('success', 'Activity deleted successfully.');
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }
}
