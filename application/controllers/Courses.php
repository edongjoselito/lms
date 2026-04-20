<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(array('Course_model', 'Lesson_model', 'Quiz_model'));
        $this->load->helper('text');
    }

    public function index()
    {
        $filters = array();
        if ($this->school_id) $filters['school_id'] = $this->school_id;

        if ($this->is_student()) {
            $data['courses'] = $this->Course_model->get_user_courses($this->current_user->id, 'student', $this->school_id);
            $data['available_courses'] = $this->Course_model->get_available_courses($this->current_user->id, $this->school_id);
        } elseif ($this->is_teacher()) {
            // Teachers see courses they created or are enrolled as teacher
            $data['courses'] = $this->Course_model->get_courses(array_merge($filters, array('created_by' => $this->current_user->id)));
            $data['enrolled_courses'] = $this->Course_model->get_user_courses($this->current_user->id, 'teacher', $this->school_id);
        } else {
            // Admin sees all
            $data['courses'] = $this->Course_model->get_courses($filters);
        }

        $data['title'] = 'Courses';
        $data['can_create'] = $this->is_admin() || $this->is_teacher();
        $this->render('courses/index', $data);
    }

    public function view($id)
    {
        $course = $this->Course_model->get_course($id);
        if (!$course) show_404();

        // Check access
        if ($this->is_student() && !$this->Course_model->is_enrolled($id, $this->current_user->id)) {
            $this->session->set_flashdata('error', 'You are not enrolled in this course.');
            redirect('courses');
            return;
        }

        $this->load->model('Lesson_model');
        $modules = $this->Lesson_model->get_modules_by_course($id);
        foreach ($modules as &$m) {
            $m->lessons = $this->Lesson_model->get_lessons($m->id);
        }

        $quizzes = $this->Quiz_model->get_quizzes_by_course($id);

        // Progress tracking for students
        $completed_ids = array();
        $progress_pct = 0;
        if ($this->is_student()) {
            $completed_ids = $this->Lesson_model->get_completed_lesson_ids($id, $this->current_user->id);
            $progress_pct = $this->Lesson_model->get_course_progress_percent($id, $this->current_user->id);
        }

        // Build sequential access map for students
        $lesson_accessible = array();
        if ($this->is_student()) {
            $ordered_ids = $this->Lesson_model->get_course_lesson_ids($id);
            foreach ($ordered_ids as $idx => $lid) {
                if ($idx === 0) {
                    $lesson_accessible[$lid] = true;
                } else {
                    $prev = $ordered_ids[$idx - 1];
                    $lesson_accessible[$lid] = in_array($prev, $completed_ids);
                }
            }
        }

        $data['title'] = $course->title;
        $data['course'] = $course;
        $data['modules'] = $modules;
        $data['quizzes'] = $quizzes;
        $data['can_edit'] = $this->_can_edit_course($course);
        $data['is_enrolled'] = $this->Course_model->is_enrolled($id, $this->current_user->id);
        $data['student_count'] = $this->Course_model->count_enrolled($id, 'student');
        $data['completed_ids'] = $completed_ids;
        $data['progress_pct'] = $progress_pct;
        $data['lesson_accessible'] = $lesson_accessible;
        $data['is_student_view'] = $this->is_student();
        $this->render('courses/view', $data);
    }

    public function create()
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));

        if ($this->input->method() === 'post') {
            $data = array(
                'school_id'    => $this->school_id ?: 1,
                'code'         => $this->input->post('code', TRUE) ?: NULL,
                'title'        => $this->input->post('title', TRUE),
                'description'  => $this->input->post('description'),
                'category'     => $this->input->post('category', TRUE) ?: NULL,
                'is_published' => $this->input->post('is_published') ? 1 : 0,
                'created_by'   => $this->current_user->id,
            );

            // Cover image upload
            if ($_FILES && !empty($_FILES['cover_image']['name'])) {
                $upload_path = FCPATH . 'uploads/courses/';
                if (!is_dir($upload_path)) mkdir($upload_path, 0777, TRUE);
                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
                $config['max_size']      = 5000;
                $config['encrypt_name']  = TRUE;
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('cover_image')) {
                    $data['cover_image'] = 'uploads/courses/' . $this->upload->data('file_name');
                }
            }

            $course_id = $this->Course_model->create_course($data);

            // Auto-enroll creator as teacher
            $this->Course_model->enroll_user($course_id, $this->current_user->id, 'teacher');

            $this->session->set_flashdata('success', 'Course created successfully.');
            redirect('courses/view/' . $course_id);
        }

        $data['title'] = 'Create Course';
        $data['course'] = null;
        $this->render('courses/form', $data);
    }

    public function edit($id)
    {
        $course = $this->Course_model->get_course($id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        if ($this->input->method() === 'post') {
            $d = array(
                'code'         => $this->input->post('code', TRUE) ?: NULL,
                'title'        => $this->input->post('title', TRUE),
                'description'  => $this->input->post('description'),
                'category'     => $this->input->post('category', TRUE) ?: NULL,
                'is_published' => $this->input->post('is_published') ? 1 : 0,
            );

            if ($_FILES && !empty($_FILES['cover_image']['name'])) {
                $upload_path = FCPATH . 'uploads/courses/';
                if (!is_dir($upload_path)) mkdir($upload_path, 0777, TRUE);
                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
                $config['max_size']      = 5000;
                $config['encrypt_name']  = TRUE;
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('cover_image')) {
                    $d['cover_image'] = 'uploads/courses/' . $this->upload->data('file_name');
                }
            }

            $this->Course_model->update_course($id, $d);
            $this->session->set_flashdata('success', 'Course updated.');
            redirect('courses/view/' . $id);
        }

        $data['title'] = 'Edit Course';
        $data['course'] = $course;
        $this->render('courses/form', $data);
    }

    public function delete($id)
    {
        $course = $this->Course_model->get_course($id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        $this->Course_model->delete_course($id);
        $this->session->set_flashdata('success', 'Course deleted.');
        redirect('courses');
    }

    // ---- Enrollment Management ----
    public function participants($id)
    {
        $course = $this->Course_model->get_course($id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        $data['title'] = 'Participants: ' . $course->title;
        $data['course'] = $course;
        $data['teachers'] = $this->Course_model->get_enrollments($id, 'teacher');
        $data['students'] = $this->Course_model->get_enrollments($id, 'student');
        $data['available_students'] = $this->Course_model->get_available_students($id, $this->school_id);
        $this->render('courses/participants', $data);
    }

    public function enroll($course_id)
    {
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        $user_ids = $this->input->post('user_ids');
        $role = $this->input->post('role') ?: 'student';

        if ($user_ids && is_array($user_ids)) {
            foreach ($user_ids as $uid) {
                $this->Course_model->enroll_user($course_id, $uid, $role);
            }
            $this->session->set_flashdata('success', count($user_ids) . ' participant(s) enrolled.');
        }

        redirect('courses/participants/' . $course_id);
    }

    public function unenroll($course_id, $user_id)
    {
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        $this->Course_model->unenroll_user($course_id, $user_id);
        $this->session->set_flashdata('success', 'Participant removed.');
        redirect('courses/participants/' . $course_id);
    }

    public function self_enroll($course_id)
    {
        $this->require_role(array('student'));
        $course = $this->Course_model->get_course($course_id);
        if (!$course || !$course->is_published) show_404();

        if ($this->Course_model->is_enrolled($course_id, $this->current_user->id)) {
            $this->session->set_flashdata('info', 'You are already enrolled in this course.');
        } else {
            $this->Course_model->enroll_user($course_id, $this->current_user->id, 'student');
            $this->session->set_flashdata('success', 'You have been enrolled in "' . $course->title . '".');
        }

        redirect('courses/view/' . $course_id);
    }

    public function student_progress($course_id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        $progress_data = $this->Lesson_model->get_course_student_progress($course_id);
        $modules = $this->Lesson_model->get_modules_by_course($course_id);
        foreach ($modules as &$m) {
            $m->lessons = $this->Lesson_model->get_lessons($m->id);
        }

        $data['title'] = 'Student Progress: ' . $course->title;
        $data['course'] = $course;
        $data['students'] = $progress_data['students'];
        $data['total_lessons'] = $progress_data['total_lessons'];
        $data['modules'] = $modules;
        $this->render('courses/student_progress', $data);
    }

    private function _can_edit_course($course)
    {
        if ($this->is_admin()) return true;
        if ($this->is_teacher() && $course->created_by == $this->current_user->id) return true;
        return false;
    }
}
