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

        // Students can view published courses to enroll, but need enrollment to access content
        if ($this->is_student() && !$course->is_published) {
            $this->session->set_flashdata('error', 'This course is not yet published.');
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

        // Get sections for enrollment
        $this->db->where('school_id', $this->school_id ?: 1);
        $sections = $this->db->get('sections')->result();

        // Get student's quiz attempts if student
        $quiz_attempts = array();
        if ($this->is_student()) {
            foreach ($quizzes as $q) {
                $quiz_attempts[$q->id] = $this->Quiz_model->get_student_attempts($q->id, $this->current_user->id);
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
        $data['sections'] = $sections;
        $data['quiz_attempts'] = $quiz_attempts;
        $this->render('courses/view', $data);
    }

    public function create()
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));

        if ($this->input->method() === 'post') {
            $data = array(
                'school_id'      => $this->school_id ?: 1,
                'title'          => $this->input->post('title', TRUE),
                'code'           => $this->input->post('code', TRUE),
                'description'    => $this->input->post('description', TRUE),
                'category'       => $this->input->post('category', TRUE),
                'enrollment_key' => $this->input->post('enrollment_key', TRUE),
                'is_published'   => $this->input->post('is_published') ? 1 : 0,
                'created_by'     => $this->current_user->id,
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
            $data = array(
                'title'          => $this->input->post('title', TRUE),
                'code'           => $this->input->post('code', TRUE),
                'description'    => $this->input->post('description', TRUE),
                'category'       => $this->input->post('category', TRUE),
                'enrollment_key' => $this->input->post('enrollment_key', TRUE),
                'is_published'   => $this->input->post('is_published') ? 1 : 0,
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
                    $data['cover_image'] = 'uploads/courses/' . $this->upload->data('file_name');
                }
            }

            $this->Course_model->update_course($id, $data);
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

        // Check if user is a collaborator and get their assigned section
        $collaborator_section_id = null;
        if ($this->is_teacher() && $course->created_by != $this->current_user->id) {
            $collaborator_section_id = $this->Course_model->get_collaborator_sections($id, $this->current_user->id);
        }

        $data['title'] = 'Participants: ' . $course->title;
        $data['course'] = $course;
        $data['teachers'] = $this->Course_model->get_enrollments($id, 'teacher');
        $data['students'] = $this->Course_model->get_enrollments($id, 'student', $collaborator_section_id);
        $data['available_students'] = $this->Course_model->get_available_students($id, $this->school_id, $collaborator_section_id);
        $data['collaborator_section_id'] = $collaborator_section_id;
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

    public function unenroll_user($course_id, $user_id)
    {
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        $this->Course_model->unenroll_user($course_id, $user_id);
        $this->session->set_flashdata('success', 'Participant removed.');
        redirect('courses/participants/' . $course_id);
    }

    public function download_enrollment_template($course_id)
    {
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        // Generate CSV template
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="enrollment_template_' . $course->id . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, array('First Name', 'Last Name', 'Email'));
        fputcsv($output, array('Juan', 'Dela Cruz', 'juan.delacruz@example.com'));
        fputcsv($output, array('Maria', 'Santos', 'maria.santos@example.com'));
        fclose($output);
        exit;
    }

    public function import_enrollment($course_id)
    {
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        if ($this->input->method() !== 'post' || !isset($_FILES['enrollment_file'])) {
            $this->session->set_flashdata('error', 'Please upload a CSV file.');
            redirect('courses/participants/' . $course_id);
            return;
        }

        $file = $_FILES['enrollment_file'];
        if ($file['type'] !== 'text/csv' && pathinfo($file['name'], PATHINFO_EXTENSION) !== 'csv') {
            $this->session->set_flashdata('error', 'Please upload a CSV file only.');
            redirect('courses/participants/' . $course_id);
            return;
        }

        // Get student role ID once
        $student_role = $this->db->select('id')->where('slug', 'student')->get('roles')->row();

        $handle = fopen($file['tmp_name'], 'r');
        $header = fgetcsv($handle); // Skip header
        $enrolled_count = 0;
        $created_count = 0;
        $already_enrolled_count = 0;
        $error_count = 0;

        while (($data = fgetcsv($handle)) !== FALSE) {
            $first_name = trim($data[0] ?? '');
            $last_name = trim($data[1] ?? '');
            $email = trim($data[2] ?? '');

            if (!$first_name || !$last_name || !$email) {
                $error_count++;
                continue;
            }

            // Find user by email
            $user = $this->db->select('id')
                           ->where('email', $email)
                           ->where('role_id', $student_role->id)
                           ->get('users')->row();

            if (!$user) {
                // Create student if not exists
                $user_data = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'password' => password_hash('password123', PASSWORD_BCRYPT), // Default password
                    'role_id' => $student_role->id,
                    'school_id' => $this->school_id ?: 1,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                );
                $this->db->insert('users', $user_data);
                $user_id = $this->db->insert_id();
                $created_count++;

                // Enroll the newly created student
                $this->Course_model->enroll_user($course_id, $user_id, 'student');
                $enrolled_count++;
            } else {
                // Check if already enrolled
                if ($this->Course_model->is_enrolled($course_id, $user->id)) {
                    $already_enrolled_count++;
                } else {
                    $this->Course_model->enroll_user($course_id, $user->id, 'student');
                    $enrolled_count++;
                }
            }
        }

        fclose($handle);

        $message = "Import completed: {$enrolled_count} student(s) enrolled.";
        if ($created_count > 0) {
            $message .= " {$created_count} new student(s) created (default password: password123).";
        }
        if ($already_enrolled_count > 0) {
            $message .= " {$already_enrolled_count} already enrolled.";
        }
        if ($error_count > 0) {
            $message .= " {$error_count} row(s) skipped (invalid data).";
        }
        $this->session->set_flashdata('success', $message);
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
            $key = $this->input->post('enrollment_key');
            $section_id = $this->input->post('section_id');

            // Check section-specific key first, then fall back to course-level key
            $section_key_valid = false;
            if ($section_id) {
                $section_key = $this->Course_model->get_section_enrollment_key($course_id, $section_id);
                if ($section_key === $key) {
                    $section_key_valid = true;
                }
            }

            if ($section_key_valid || $key === $course->enrollment_key) {
                $this->Course_model->enroll_user($course_id, $this->current_user->id, 'student');
                $this->session->set_flashdata('success', 'You have been enrolled in "' . $course->title . '".');
            } else {
                $this->session->set_flashdata('error', 'Invalid enrollment key.');
            }
        }

        redirect('courses/view/' . $course_id);
    }

    public function unenroll($course_id)
    {
        $this->require_role(array('student'));
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();

        if (!$this->Course_model->is_enrolled($course_id, $this->current_user->id)) {
            $this->session->set_flashdata('error', 'You are not enrolled in this course.');
        } else {
            $this->Course_model->unenroll_user($course_id, $this->current_user->id);
            $this->session->set_flashdata('success', 'You have been unenrolled from "' . $course->title . '".');
        }

        redirect('courses');
    }

    // ---- Collaborator Management ----
    public function collaborators($course_id)
    {
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        $data['title'] = 'Course Collaborators';
        $data['course'] = $course;
        $data['collaborators'] = $this->Course_model->get_collaborators($course_id);
        $data['section_keys'] = $this->Course_model->get_all_section_keys($course_id);
        
        // Get available teachers (excluding creator and current collaborators)
        $collab_ids = array_map(function($c) { return $c->teacher_id; }, $data['collaborators']);
        $collab_ids[] = $course->created_by;
        
        $this->db->select('users.id, CONCAT(users.first_name, " ", users.last_name) as name, users.email');
        $this->db->join('roles', 'roles.id = users.role_id');
        $this->db->where('roles.slug', 'teacher');
        $this->db->where('users.status', 1);
        if ($this->school_id) {
            $this->db->where('users.school_id', $this->school_id);
        }
        if (!empty($collab_ids)) {
            $this->db->where_not_in('users.id', $collab_ids);
        }
        $data['available_teachers'] = $this->db->get('users')->result();
        
        // Get sections for this school
        $this->db->where('school_id', $this->school_id ?: 1);
        $data['sections'] = $this->db->get('sections')->result();
        
        $this->render('courses/collaborators', $data);
    }

    public function add_collaborator($course_id)
    {
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        if ($this->input->method() !== 'post') {
            redirect('courses/collaborators/' . $course_id);
            return;
        }

        $teacher_id = $this->input->post('teacher_id');
        $section_id = $this->input->post('section_id') ?: null;

        if (!$teacher_id) {
            $this->session->set_flashdata('error', 'Please select a teacher.');
            redirect('courses/collaborators/' . $course_id);
            return;
        }

        $this->Course_model->add_collaborator($course_id, $teacher_id, $section_id);
        $this->session->set_flashdata('success', 'Collaborator added successfully.');
        redirect('courses/collaborators/' . $course_id);
    }

    public function remove_collaborator($course_id, $teacher_id)
    {
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        $this->Course_model->remove_collaborator($course_id, $teacher_id);
        $this->session->set_flashdata('success', 'Collaborator removed successfully.');
        redirect('courses/collaborators/' . $course_id);
    }

    public function set_section_key($course_id)
    {
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();
        if (!$this->_can_edit_course($course)) show_error('Unauthorized', 403);

        if ($this->input->method() !== 'post') {
            redirect('courses/collaborators/' . $course_id);
            return;
        }

        $section_id = $this->input->post('section_id');
        $enrollment_key = $this->input->post('enrollment_key');

        if (!$section_id || !$enrollment_key) {
            $this->session->set_flashdata('error', 'Section and enrollment key are required.');
            redirect('courses/collaborators/' . $course_id);
            return;
        }

        $this->Course_model->set_section_enrollment_key($course_id, $section_id, $enrollment_key);
        $this->session->set_flashdata('success', 'Section enrollment key set successfully.');
        redirect('courses/collaborators/' . $course_id);
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
        if ($this->is_teacher() && $this->Course_model->is_collaborator($course->id, $this->current_user->id)) return true;
        return false;
    }
}
