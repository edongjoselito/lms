<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        if (!in_array($this->role_slug, array('student', 'course_creator', 'super_admin', 'school_admin')) &&
            !($this->is_student_mode && in_array($this->original_role_slug, array('course_creator', 'teacher')))) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->load->model(array('Academic_model', 'User_model', 'Lesson_model', 'Student_model'));
    }

    private function is_student_content_view()
    {
        return $this->role_slug === 'student' || $this->is_student_mode;
    }

    private function require_course_manager()
    {
        if (!in_array($this->original_role_slug, array('course_creator', 'super_admin', 'school_admin'))) {
            show_error('You do not have permission to manage course content.', 403);
        }
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
        $subject_sections = $this->Academic_model->get_subject_sections($subject_id);
        $requires_enrollment_key = $this->Academic_model->subject_has_enrollment_keys($subject_id);
        $has_subject_access = $this->has_subject_access($subject_id);
        
        // Get modules for this subject with lessons and activities
        $modules = $has_subject_access ? $this->Lesson_model->get_modules_by_subject($subject_id) : array();
        foreach ($modules as $key => &$module) {
            if ($student_content_view && !$module->is_published) {
                unset($modules[$key]);
                continue;
            }

            $module->lessons = $this->Lesson_model->get_lessons($module->id);
            $module->activities = $this->Lesson_model->get_activities($module->id);

            if ($student_content_view) {
                $module->lessons = array_values(array_filter($module->lessons, function($lesson) {
                    return !empty($lesson->is_published);
                }));
                $module->activities = array_values(array_filter($module->activities, function($activity) {
                    return !empty($activity->is_published);
                }));
            }
        }
        unset($module);
        $modules = array_values($modules);
        $completed_lesson_ids = array();
        $accessible_lesson_ids = array();
        $progress_percent = 0;

        if ($student_content_view && $this->current_user && $has_subject_access) {
            $completed_lesson_ids = $this->get_current_completed_lesson_ids($subject_id);
            $progress_percent = $this->get_current_subject_progress_percent($subject_id);

            foreach ($this->Lesson_model->get_subject_lesson_ids($subject_id, true) as $lesson_id) {
                if ($this->is_current_lesson_accessible($lesson_id, $subject_id)) {
                    $accessible_lesson_ids[] = (int) $lesson_id;
                }
            }
        }
        
        $data['title'] = 'Subject Content: ' . $subject->code;
        $data['subject'] = $subject;
        $data['modules'] = $modules;
        $data['edit_mode'] = !$student_content_view && $this->input->get('edit') === '1';
        $data['completed_lesson_ids'] = $completed_lesson_ids;
        $data['accessible_lesson_ids'] = $accessible_lesson_ids;
        $data['progress_percent'] = $progress_percent;
        $data['student_content_view'] = $student_content_view;
        $data['subject_sections'] = $subject_sections;
        $data['available_sections'] = $this->Academic_model->get_sections(array('school_id' => $this->school_id));
        $data['requires_enrollment_key'] = $requires_enrollment_key;
        $data['has_subject_access'] = $has_subject_access;
        
        $this->render('course/content', $data);
    }

    private function get_subject_item_navigation($subject_id, $current_type, $current_id)
    {
        $items = array();
        $modules = $this->Lesson_model->get_modules_by_subject($subject_id);
        $completed_lesson_ids = $this->get_current_completed_lesson_ids($subject_id);

        foreach ($modules as $module) {
            $module_items = array();
            foreach ($this->Lesson_model->get_lessons($module->id) as $lesson) {
                if ($this->is_student_content_view() && (!$module->is_published || !$lesson->is_published)) {
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
                if ($this->is_student_content_view() && (!$module->is_published || !$activity->is_published)) {
                    continue;
                }

                $module_items[] = (object) array(
                    'id' => $activity->id,
                    'item_type' => 'activity',
                    'title' => $activity->title,
                    'module_title' => $module->title,
                    'order_num' => $activity->order_num,
                    'url' => site_url('course/activity/' . $activity->id),
                    'is_completed' => false,
                    'is_accessible' => true,
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

    private function set_preview_completed_lesson_ids($lesson_ids)
    {
        $this->session->set_userdata('preview_completed_lessons', array_values(array_unique(array_map('intval', $lesson_ids))));
    }

    private function get_current_completed_lesson_ids($subject_id)
    {
        if (!$this->is_student_content_view() || !$this->current_user) {
            return array();
        }

        if ($this->use_preview_progress()) {
            $subject_lesson_ids = $this->Lesson_model->get_subject_lesson_ids($subject_id, true);
            return array_values(array_intersect($subject_lesson_ids, $this->get_preview_completed_lesson_ids()));
        }

        return $this->Lesson_model->get_completed_lesson_ids_by_subject($subject_id, $this->current_user->id);
    }

    private function get_current_subject_progress_percent($subject_id)
    {
        $total = $this->Lesson_model->get_subject_lesson_ids($subject_id, true);
        if (empty($total)) return 0;

        $completed = $this->get_current_completed_lesson_ids($subject_id);
        return round((count($completed) / count($total)) * 100);
    }

    private function is_current_lesson_accessible($lesson_id, $subject_id)
    {
        if (!$this->is_student_content_view() || !$this->current_user) {
            return true;
        }

        $ordered = $this->Lesson_model->get_subject_lesson_ids($subject_id, true);
        $completed = $this->get_current_completed_lesson_ids($subject_id);

        foreach ($ordered as $lid) {
            if ((int) $lid === (int) $lesson_id) return true;
            if (!in_array((int) $lid, $completed)) return false;
        }

        return true;
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

        $student_id = $this->current_user->id;
        $this->Lesson_model->mark_lesson_completed($student_id, $lesson_id);
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
            $escaped_url = htmlspecialchars(trim((string) $file_url), ENT_QUOTES, 'UTF-8');
            if ($escaped_url === '') {
                return $content;
            }

            $file_markup = '<div class="lesson-file-embed mb-3" data-file-url="' . $escaped_url . '"><a href="' . $escaped_url . '" target="_blank" rel="noopener" class="btn btn-outline-primary"><i class="bi bi-file-earmark me-1"></i>Download/View File</a></div>';

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

        if ($this->is_student_content_view() && (!$module->is_published || !$lesson->is_published)) {
            show_404();
        }

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) {
            show_404();
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
        $data['navigation'] = $this->get_subject_item_navigation($subject->id, 'lesson', $lesson->id);
        $data['lesson_progress'] = $progress;
        $data['progress_percent'] = $progress_percent;
        $data['student_content_view'] = $this->is_student_content_view();

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

        if ($this->is_student_content_view() && (!$module->is_published || !$lesson->is_published)) {
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

        $module = $this->Lesson_model->get_module($activity->module_id);
        if (!$module) {
            show_404();
        }

        if ($this->is_student_content_view() && (!$module->is_published || !$activity->is_published)) {
            show_404();
        }

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) {
            show_404();
        }

        if (!$this->has_subject_access($subject->id)) {
            $this->session->set_flashdata('error', 'Enter the enrollment key to access this course.');
            redirect('course/content/' . $subject->id);
        }

        $data['title'] = 'Activity: ' . $activity->title;
        $data['subject'] = $subject;
        $data['module'] = $module;
        $data['item'] = $activity;
        $data['item_type'] = 'activity';
        $data['navigation'] = $this->get_subject_item_navigation($subject->id, 'activity', $activity->id);
        $data['student_content_view'] = $this->is_student_content_view();

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
        $this->require_course_manager();
        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject) show_404();

        if ($this->input->method() === 'post') {
            $section_name = trim($this->input->post('section_name', TRUE));
            if ($section_name) {
                $this->Academic_model->save_subject_section_by_name($subject_id, $section_name, $this->input->post('enrollment_key', TRUE));
                $this->session->set_flashdata('success', 'Section access saved.');
            }
        }
        redirect('course/content/' . $subject_id . '?edit=1');
    }

    public function remove_subject_section($subject_id, $class_program_id)
    {
        $this->require_course_manager();
        $this->Academic_model->remove_subject_section($class_program_id, $subject_id);
        $this->session->set_flashdata('success', 'Section access removed.');
        redirect('course/content/' . $subject_id . '?edit=1');
    }

    public function section_students($section_id)
    {
        $this->require_course_manager();
        $section = $this->Academic_model->get_subject_section($section_id);
        if (!$section) show_404();

        $data['title'] = 'Enrolled Students: ' . $section->section_name;
        $data['section'] = $section;
        $data['students'] = $this->Academic_model->get_section_students($section_id);

        $this->render('course/section_students', $data);
    }

    public function section_progress($section_id)
    {
        $this->require_course_manager();
        $section = $this->Academic_model->get_subject_section($section_id);
        if (!$section) show_404();

        $data['title'] = 'Section Progress: ' . $section->section_name;
        $data['section'] = $section;
        $data['students'] = $this->Academic_model->get_section_students($section_id);

        $this->render('course/section_progress', $data);
    }

    public function section_attendance($section_id)
    {
        $this->require_course_manager();
        $section = $this->Academic_model->get_subject_section($section_id);
        if (!$section) show_404();

        $data['title'] = 'Section Attendance: ' . $section->section_name;
        $data['section'] = $section;
        $data['students'] = $this->Academic_model->get_section_students($section_id);

        $this->render('course/section_attendance', $data);
    }

    public function edit_subject_section($subject_id)
    {
        $this->require_course_manager();
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
        $this->require_course_manager();
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
        $this->require_course_manager();
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
        $this->require_course_manager();
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
        $this->require_course_manager();
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        
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
        $this->require_course_manager();
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        
        $this->Lesson_model->delete_module($module_id);
        $this->session->set_flashdata('success', 'Module deleted successfully.');
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    // ---- Lesson Management ----
    public function create_lesson($module_id)
    {
        $this->require_course_manager();
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        
        if ($this->input->method() === 'post') {
            $order = $this->Lesson_model->get_next_order('lessons', 'module_id', $module_id);
            $content_type = $this->input->post('content_type', TRUE);

            // Handle file upload
            $file_path = '';
            if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
                $config['upload_path'] = './uploads/lessons/';
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = 10240; // 10MB
                $config['file_name'] = uniqid() . '_' . $_FILES['file_upload']['name'];

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, true);
                }

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('file_upload')) {
                    $upload_data = $this->upload->data();
                    $file_path = base_url('uploads/lessons/' . $upload_data['file_name']);
                }
            }

            $data = array(
                'module_id'       => $module_id,
                'title'           => $this->input->post('title', TRUE),
                'content'         => $this->build_lesson_content($content_type, $this->input->post('content'), $this->input->post('video_url', TRUE), $file_path, $this->input->post('link_url', TRUE)),
                'content_type'    => $content_type,
                'file_path'       => $file_path,
                'order_num'       => $order,
                'is_published'    => $this->input->post('is_published') ? 1 : 0,
            );
            $this->Lesson_model->create_lesson($data);
            $this->session->set_flashdata('success', 'Lesson created successfully.');
        }
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    public function edit_lesson($lesson_id)
    {
        $this->require_course_manager();
        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) show_404();
        
        $module = $this->Lesson_model->get_module($lesson->module_id);
        
        if ($this->input->method() === 'post') {
            $content_type = $this->input->post('content_type', TRUE);

            // Handle file upload
            $file_path = $lesson->file_path;
            log_message('debug', 'File upload check: ' . print_r(isset($_FILES['file_upload']) ? $_FILES['file_upload'] : 'No file', true));
            if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
                $config['upload_path'] = './uploads/lessons/';
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = 10240; // 10MB
                $config['file_name'] = uniqid() . '_' . $_FILES['file_upload']['name'];

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, true);
                }

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('file_upload')) {
                    $upload_data = $this->upload->data();
                    $file_path = base_url('uploads/lessons/' . $upload_data['file_name']);
                    log_message('debug', 'File uploaded successfully: ' . $file_path);
                } else {
                    log_message('debug', 'File upload error: ' . $this->upload->display_errors());
                }
            }

            $data = array(
                'title'           => $this->input->post('title', TRUE),
                'content'         => $this->build_lesson_content($content_type, $this->input->post('content'), $this->input->post('video_url', TRUE), $file_path, $this->input->post('link_url', TRUE)),
                'content_type'    => $content_type,
                'file_path'       => $file_path,
                'is_published'    => $this->input->post('is_published') ? 1 : 0,
            );
            $this->Lesson_model->update_lesson($lesson_id, $data);
            $this->session->set_flashdata('success', 'Lesson updated successfully.');
        }
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    public function delete_lesson($lesson_id)
    {
        $this->require_course_manager();
        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) show_404();
        
        $module = $this->Lesson_model->get_module($lesson->module_id);
        
        $this->Lesson_model->delete_lesson($lesson_id);
        $this->session->set_flashdata('success', 'Lesson deleted successfully.');
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    // ---- Activity Management ----
    public function create_activity($module_id)
    {
        $this->require_course_manager();
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        
        if ($this->input->method() === 'post') {
            $order = $this->Lesson_model->get_next_order('activities', 'module_id', $module_id);
            $data = array(
                'module_id'     => $module_id,
                'type'          => $this->input->post('type', TRUE),
                'title'         => $this->input->post('title', TRUE),
                'content'       => $this->input->post('content'),
                'settings'      => json_encode($this->input->post('settings') ?: []),
                'order_num'     => $order,
                'is_published'  => $this->input->post('is_published') ? 1 : 0,
            );
            $this->Lesson_model->create_activity($data);
            $this->session->set_flashdata('success', 'Activity created successfully.');
        }
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    public function edit_activity($activity_id)
    {
        $this->require_course_manager();
        $activity = $this->Lesson_model->get_activity($activity_id);
        if (!$activity) show_404();
        
        $module = $this->Lesson_model->get_module($activity->module_id);
        
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

    public function delete_activity($activity_id)
    {
        $this->require_course_manager();
        $activity = $this->Lesson_model->get_activity($activity_id);
        if (!$activity) show_404();
        
        $module = $this->Lesson_model->get_module($activity->module_id);
        
        $this->Lesson_model->delete_activity($activity_id);
        $this->session->set_flashdata('success', 'Activity deleted successfully.');
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }
}
