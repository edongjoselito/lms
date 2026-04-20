<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lessons extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(array('Lesson_model', 'Course_model'));
    }

    // ---- Module CRUD (course-based) ----
    public function create_module($course_id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $course = $this->Course_model->get_course($course_id);
        if (!$course) show_404();

        if ($this->input->method() === 'post') {
            $d = array(
                'course_id'    => $course_id,
                'title'        => $this->input->post('title', TRUE),
                'description'  => $this->input->post('description', TRUE),
                'order_num'    => $this->Lesson_model->get_next_order('modules', 'course_id', $course_id),
                'is_published' => $this->input->post('is_published') ? 1 : 0,
                'created_by'   => $this->current_user->id,
            );
            $this->Lesson_model->create_module($d);
            $this->session->set_flashdata('success', 'Module created.');
            redirect('courses/view/' . $course_id);
        }

        $data['title'] = 'Add Module';
        $data['course'] = $course;
        $data['module'] = null;
        $this->render('lessons/module_form', $data);
    }

    public function edit_module($id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $module = $this->Lesson_model->get_module($id);
        if (!$module) show_404();

        if ($this->input->method() === 'post') {
            $d = array(
                'title'        => $this->input->post('title', TRUE),
                'description'  => $this->input->post('description', TRUE),
                'is_published' => $this->input->post('is_published') ? 1 : 0,
            );
            $this->Lesson_model->update_module($id, $d);
            $this->session->set_flashdata('success', 'Module updated.');
            redirect('courses/view/' . $module->course_id);
        }

        $course = $this->Course_model->get_course($module->course_id);
        $data['title'] = 'Edit Module';
        $data['course'] = $course;
        $data['module'] = $module;
        $this->render('lessons/module_form', $data);
    }

    public function delete_module($id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $module = $this->Lesson_model->get_module($id);
        if (!$module) show_404();
        $course_id = $module->course_id;
        $this->Lesson_model->delete_module($id);
        $this->session->set_flashdata('success', 'Module deleted.');
        redirect('courses/view/' . $course_id);
    }

    // ---- Lesson CRUD ----
    public function create_lesson($module_id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        $course = $this->Course_model->get_course($module->course_id);

        if ($this->input->method() === 'post') {
            $d = array(
                'module_id'    => $module_id,
                'title'        => $this->input->post('title', TRUE),
                'content'      => $this->input->post('content'),
                'content_type' => $this->input->post('content_type', TRUE),
                'external_url' => $this->input->post('external_url', TRUE) ?: NULL,
                'duration_minutes' => $this->input->post('duration_minutes') ?: NULL,
                'order_num'    => $this->Lesson_model->get_next_order('lessons', 'module_id', $module_id),
                'is_published' => $this->input->post('is_published') ? 1 : 0,
            );

            if ($_FILES && !empty($_FILES['lesson_file']['name'])) {
                $upload_path = FCPATH . 'uploads/lessons/';
                if (!is_dir($upload_path)) mkdir($upload_path, 0777, TRUE);

                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = 'pdf|doc|docx|ppt|pptx|xls|xlsx|mp4|webm|mp3|zip|jpg|jpeg|png|gif';
                $config['max_size']      = 50000;
                $config['encrypt_name']  = TRUE;
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('lesson_file')) {
                    $d['file_path'] = 'uploads/lessons/' . $this->upload->data('file_name');
                }
            }

            // PDF / H5P attachment (supplementary material)
            if ($_FILES && !empty($_FILES['attachment_pdf']['name'])) {
                $upload_path = FCPATH . 'uploads/lessons/';
                if (!is_dir($upload_path)) mkdir($upload_path, 0777, TRUE);

                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = 'pdf|h5p';
                $config['max_size']      = 50000;
                $config['encrypt_name']  = TRUE;
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('attachment_pdf')) {
                    $d['attachment_path'] = 'uploads/lessons/' . $this->upload->data('file_name');
                }
            }

            $this->Lesson_model->create_lesson($d);
            $this->session->set_flashdata('success', 'Lesson created.');
            redirect('courses/view/' . $module->course_id);
        }

        $data['title'] = 'Add Lesson';
        $data['course'] = $course;
        $data['module'] = $module;
        $data['lesson'] = null;
        $this->render('lessons/lesson_form', $data);
    }

    public function edit_lesson($id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $lesson = $this->Lesson_model->get_lesson($id);
        if (!$lesson) show_404();
        $module = $this->Lesson_model->get_module($lesson->module_id);
        $course = $this->Course_model->get_course($module->course_id);

        if ($this->input->method() === 'post') {
            $d = array(
                'title'        => $this->input->post('title', TRUE),
                'content'      => $this->input->post('content'),
                'content_type' => $this->input->post('content_type', TRUE),
                'external_url' => $this->input->post('external_url', TRUE) ?: NULL,
                'duration_minutes' => $this->input->post('duration_minutes') ?: NULL,
                'is_published' => $this->input->post('is_published') ? 1 : 0,
            );

            if ($_FILES && !empty($_FILES['lesson_file']['name'])) {
                $upload_path = FCPATH . 'uploads/lessons/';
                if (!is_dir($upload_path)) mkdir($upload_path, 0777, TRUE);

                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = 'pdf|doc|docx|ppt|pptx|xls|xlsx|mp4|webm|mp3|zip|jpg|jpeg|png|gif';
                $config['max_size']      = 50000;
                $config['encrypt_name']  = TRUE;
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('lesson_file')) {
                    $d['file_path'] = 'uploads/lessons/' . $this->upload->data('file_name');
                }
            }

            // PDF / H5P attachment (supplementary material)
            if ($_FILES && !empty($_FILES['attachment_pdf']['name'])) {
                $upload_path = FCPATH . 'uploads/lessons/';
                if (!is_dir($upload_path)) mkdir($upload_path, 0777, TRUE);

                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = 'pdf|h5p';
                $config['max_size']      = 50000;
                $config['encrypt_name']  = TRUE;
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('attachment_pdf')) {
                    $d['attachment_path'] = 'uploads/lessons/' . $this->upload->data('file_name');
                }
            }

            $this->Lesson_model->update_lesson($id, $d);
            $this->session->set_flashdata('success', 'Lesson updated.');
            redirect('courses/view/' . $module->course_id);
        }

        $data['title'] = 'Edit Lesson';
        $data['course'] = $course;
        $data['module'] = $module;
        $data['lesson'] = $lesson;
        $this->render('lessons/lesson_form', $data);
    }

    public function delete_lesson($id)
    {
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $lesson = $this->Lesson_model->get_lesson($id);
        if (!$lesson) show_404();
        $module = $this->Lesson_model->get_module($lesson->module_id);
        $this->Lesson_model->delete_lesson($id);
        $this->session->set_flashdata('success', 'Lesson deleted.');
        redirect('courses/view/' . $module->course_id);
    }

    public function view($id)
    {
        $lesson = $this->Lesson_model->get_lesson($id);
        if (!$lesson) show_404();
        $module = $this->Lesson_model->get_module($lesson->module_id);
        $course = $this->Course_model->get_course($module->course_id);

        // Enforce sequential access for students
        if ($this->is_student()) {
            if (!$this->Lesson_model->is_lesson_accessible($id, $course->id, $this->current_user->id)) {
                $this->session->set_flashdata('error', 'You must complete the previous lesson first.');
                redirect('courses/view/' . $course->id);
                return;
            }
            // Mark lesson as completed
            $this->Lesson_model->update_progress($this->current_user->id, $id, array(
                'status'           => 'completed',
                'progress_percent' => 100,
                'completed_at'     => date('Y-m-d H:i:s'),
            ));
        }

        // Find next lesson in course sequence
        $ordered_ids = $this->Lesson_model->get_course_lesson_ids($course->id);
        $next_lesson_id = null;
        foreach ($ordered_ids as $idx => $lid) {
            if ($lid == $id && isset($ordered_ids[$idx + 1])) {
                $next_lesson_id = $ordered_ids[$idx + 1];
                break;
            }
        }

        $data['title'] = $lesson->title;
        $data['lesson'] = $lesson;
        $data['course'] = $course;
        $data['next_lesson_id'] = $next_lesson_id;
        $this->render('lessons/view', $data);
    }
}
