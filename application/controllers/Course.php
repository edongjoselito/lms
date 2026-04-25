<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends MY_Controller {

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

    private function require_course_manager($subject_id = null)
    {
        if (in_array($this->original_role_slug, array('course_creator', 'super_admin', 'school_admin'))) {
            return;
        }
        if ($subject_id && $this->original_role_slug === 'teacher' && $this->is_teacher_for_subject($subject_id)) {
            return;
        }
        show_error('You do not have permission to manage course content.', 403);
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
        if ($this->original_role_slug === 'teacher') {
            redirect('course/teacher_subjects');
        }
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
        $subject_sections = $this->Academic_model->get_subject_sections($subject_id);
        $requires_enrollment_key = $this->Academic_model->subject_has_enrollment_keys($subject_id);
        $has_subject_access = $this->has_subject_access($subject_id);
        
        // Get modules for this subject with lessons and activities
        $modules = $has_subject_access ? $this->Lesson_model->get_modules_by_subject($subject_id) : array();
        foreach ($modules as $key => &$module) {
            if ($filter_unpublished && !$module->is_published) {
                unset($modules[$key]);
                continue;
            }

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
                    continue;
                }

                $activity->quiz = $this->Quiz_model->get_quiz_by_activity($activity->id);
                if ($filter_unpublished && (!$activity->quiz || empty($activity->quiz->is_published))) {
                    unset($module->activities[$activity_key]);
                    continue;
                }

                $activity->question_count = $activity->quiz ? $this->Quiz_model->count_questions($activity->quiz->id) : 0;
            }
            unset($activity);
            $module->activities = array_values($module->activities);
        }
        unset($module);
        $modules = array_values($modules);
        $completed_lesson_ids = array();
        $accessible_lesson_ids = array();
        $progress_percent = 0;

        if ($student_content_view && $this->current_user && $has_subject_access) {
            $completed_lesson_ids = $this->get_current_completed_lesson_ids($subject_id);
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
        $can_edit = in_array($this->original_role_slug, array('course_creator', 'super_admin', 'school_admin'))
                    || ($this->original_role_slug === 'teacher' && $this->is_teacher_for_subject($subject_id));
        $data['edit_mode'] = !$student_content_view && $this->input->get('edit') === '1' && $can_edit;
        $data['can_edit']  = $can_edit;
        $data['completed_lesson_ids'] = $completed_lesson_ids;
        $data['accessible_lesson_ids'] = $accessible_lesson_ids;
        $data['progress_percent'] = $progress_percent;
        $data['student_content_view'] = $student_content_view;
        $data['subject_sections'] = $subject_sections;
        $data['available_sections'] = $this->Academic_model->get_sections(array('school_id' => $this->school_id));
        $data['requires_enrollment_key'] = $requires_enrollment_key;
        $data['has_subject_access'] = $has_subject_access;

        $back_param = $this->input->get('back', TRUE);
        if ($back_param) {
            $data['back_url'] = site_url($back_param);
        } else {
            $referer = $this->input->server('HTTP_REFERER');
            $data['back_url'] = ($referer && strpos($referer, base_url()) === 0)
                ? $referer
                : site_url('course/subjects');
        }

        $this->render('course/content', $data);
    }

    private function get_subject_item_navigation($subject_id, $current_type, $current_id)
    {
        $items = array();
        $modules = $this->Lesson_model->get_modules_by_subject($subject_id);
        $completed_lesson_ids = $this->get_current_completed_lesson_ids($subject_id);
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
            $subject_lesson_ids = $this->Lesson_model->get_subject_lesson_ids($subject_id, $this->should_filter_unpublished_content());
            return array_values(array_intersect($subject_lesson_ids, $this->get_preview_completed_lesson_ids()));
        }

        return $this->Lesson_model->get_completed_lesson_ids_by_subject($subject_id, $this->current_user->id);
    }

    private function get_current_subject_progress_percent($subject_id)
    {
        $total = $this->Lesson_model->get_subject_lesson_ids($subject_id, $this->should_filter_unpublished_content());
        if (empty($total)) return 0;

        $completed = $this->get_current_completed_lesson_ids($subject_id);
        return round((count($completed) / count($total)) * 100);
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
        $this->require_course_manager($subject_id);
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
        $this->require_course_manager($subject_id);

        // Check if section can be deleted
        if (!$this->Academic_model->can_delete_section($class_program_id)) {
            $this->session->set_flashdata('error', 'Cannot remove section. This is the only section for this course and there are enrolled students.');
            redirect('course/content/' . $subject_id . '?edit=1');
        }

        $this->Academic_model->remove_subject_section($class_program_id, $subject_id);
        $this->session->set_flashdata('success', 'Section access removed.');
        redirect('course/content/' . $subject_id . '?edit=1');
    }

    public function section_students($section_id)
    {
        $section = $this->Academic_model->get_subject_section($section_id);
        if (!$section) show_404();
        $this->require_course_manager($section->subject_id);

        $data['title'] = 'Enrolled Students: ' . $section->section_name;
        $data['section'] = $section;
        $data['students'] = $this->Academic_model->get_section_students($section_id);

        $this->render('course/section_students', $data);
    }

    public function section_progress($section_id)
    {
        $section = $this->Academic_model->get_subject_section($section_id);
        if (!$section) show_404();
        $this->require_course_manager($section->subject_id);

        $data['title'] = 'Section Progress: ' . $section->section_name;
        $data['section'] = $section;
        $data['students'] = $this->Academic_model->get_section_students($section_id);

        $this->render('course/section_progress', $data);
    }

    public function section_attendance($section_id)
    {
        $section = $this->Academic_model->get_subject_section($section_id);
        if (!$section) show_404();
        $this->require_course_manager($section->subject_id);

        $data['title'] = 'Section Attendance: ' . $section->section_name;
        $data['section'] = $section;
        $data['students'] = $this->Academic_model->get_section_students($section_id);

        $this->render('course/section_attendance', $data);
    }

    public function edit_subject_section($subject_id)
    {
        $this->require_course_manager($subject_id);
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
        $this->require_course_manager($module->subject_id);
        
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
        $this->require_course_manager($module->subject_id);
        
        $this->Lesson_model->delete_module($module_id);
        $this->session->set_flashdata('success', 'Module deleted successfully.');
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    // ---- Lesson Management ----
    public function create_lesson($module_id)
    {
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        $this->require_course_manager($module->subject_id);
        
        if ($this->input->method() === 'post') {
            $order = $this->Lesson_model->get_next_order('lessons', 'module_id', $module_id);
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
        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) show_404();
        $module = $this->Lesson_model->get_module($lesson->module_id);
        $this->require_course_manager($module ? $module->subject_id : null);
        
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
        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) show_404();
        $module = $this->Lesson_model->get_module($lesson->module_id);
        $this->require_course_manager($module ? $module->subject_id : null);
        
        $this->Lesson_model->delete_lesson($lesson_id);
        $this->session->set_flashdata('success', 'Lesson deleted successfully.');
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    // ---- Activity Management ----
    public function create_activity($module_id)
    {
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        $this->require_course_manager($module->subject_id);
        
        if ($this->input->method() === 'post') {
            $type = $this->input->post('type', TRUE);
            $type = in_array($type, array('assignment', 'quiz', 'forum', 'resource', 'page', 'label')) ? $type : 'page';
            $order = $this->Lesson_model->get_next_order('activities', 'module_id', $module_id);
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
        $this->require_course_manager($module ? $module->subject_id : null);
        
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
        foreach ($xml->question as $index => $node) {
            $type = strtolower((string) $node['type']);
            if ($type === 'category') {
                continue;
            }

            $question_text = $this->clean_import_text((string) $node->questiontext->text);
            if ($question_text === '') {
                $question_text = $this->clean_import_text((string) $node->name->text);
            }

            if ($question_text === '') {
                $errors[] = 'XML question #' . ($index + 1) . ' was skipped because the question text is empty.';
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
                    $errors[] = 'XML multiple choice question #' . ($index + 1) . ' was skipped because it has fewer than two choices.';
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
                    $errors[] = 'XML short answer question #' . ($index + 1) . ' was skipped because it has no correct answer.';
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
                $errors[] = 'XML question #' . ($index + 1) . ' was skipped because type "' . $type . '" is not supported.';
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
        if (empty($_FILES['question_file']['name'])) {
            return array('success' => true, 'count' => 0, 'message' => '');
        }

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
        if (trim((string) $content) === '') {
            return array('success' => false, 'count' => 0, 'message' => 'Question file is empty.');
        }

        $format = strtolower($this->input->post('import_format', TRUE));
        if (!in_array($format, array('gift', 'xml'))) {
            $format = $extension === 'xml' ? 'xml' : 'gift';
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
        $this->require_course_manager($module->subject_id);

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

            $this->db->trans_start();
            $activity_id = $this->Lesson_model->create_activity(array(
                'module_id'    => $module_id,
                'type'         => 'quiz',
                'title'        => $title,
                'content'      => $this->input->post('description'),
                'settings'     => json_encode(array()),
                'order_num'    => $this->Lesson_model->get_next_order('activities', 'module_id', $module_id),
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
        $this->require_course_manager($context['module']->subject_id);

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
            ));

            $this->session->set_flashdata('success', 'Assessment updated successfully.');
        }

        redirect('course/assessment/' . $context['activity']->id);
    }

    public function upload_assessment_questions($quiz_id)
    {
        $context = $this->get_assessment_context_by_quiz($quiz_id);
        if (!$context) show_404();
        $this->require_course_manager($context['module']->subject_id);

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
        $this->require_course_manager($context['module']->subject_id);

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

            $questions_count = $this->Quiz_model->count_questions($quiz->id);
            $attempts = $this->Quiz_model->get_student_attempts($quiz->id, $this->current_user->id);
            $in_progress_attempt = $this->Quiz_model->get_in_progress_attempt($quiz->id, $this->current_user->id);
            $availability_error = $this->assessment_availability_error($quiz);
            $max_attempts = max(1, (int) $quiz->max_attempts);
            $can_start = $questions_count > 0 && !$availability_error && (!$in_progress_attempt && count($attempts) < $max_attempts);

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
            $data['student_content_view'] = true;
            $this->render('course/assessment_intro', $data);
            return;
        }

        $this->require_course_manager($subject->id);
        $quiz = $this->get_or_create_quiz_for_activity($activity, $module, $subject);
        $questions = $this->Quiz_model->get_questions_with_choices($quiz->id);

        $data['title'] = 'Manage Assessment: ' . $quiz->title;
        $data['subject'] = $subject;
        $data['module'] = $module;
        $data['activity'] = $activity;
        $data['quiz'] = $quiz;
        $data['questions'] = $questions;
        $data['attempts'] = $this->Quiz_model->get_all_attempts($quiz->id);
        $data['student_content_view'] = false;
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

        $in_progress = $this->Quiz_model->get_in_progress_attempt($quiz->id, $this->current_user->id);
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
        $this->require_course_manager($module ? $module->subject_id : null);
        
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
