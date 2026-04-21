<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->require_role(array('course_creator', 'super_admin', 'school_admin'));
        $this->load->model(array('Academic_model', 'User_model', 'Lesson_model'));
    }

    public function index()
    {
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
        $data['title'] = 'Manage Subjects';
        
        $this->school_filter(null, 'subjects');
        $data['subjects'] = $this->Academic_model->get_subjects();
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();
        
        $this->render('course/subjects', $data);
    }

    public function content($subject_id = null)
    {
        if (!$subject_id) {
            redirect('course/subjects');
        }
        
        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject) {
            show_404();
        }
        
        // Get modules for this subject with lessons and activities
        $modules = $this->Lesson_model->get_modules_by_subject($subject_id);
        foreach ($modules as &$module) {
            $module->lessons = $this->Lesson_model->get_lessons($module->id);
            $module->activities = $this->Lesson_model->get_activities($module->id);
        }
        
        $data['title'] = 'Subject Content: ' . $subject->code;
        $data['subject'] = $subject;
        $data['modules'] = $modules;
        $data['edit_mode'] = $this->input->get('edit') === '1';
        
        $this->render('course/content', $data);
    }

    public function lesson($lesson_id = null)
    {
        if (!$lesson_id) {
            redirect('course/subjects');
        }

        $lesson = $this->Lesson_model->get_lesson($lesson_id);
        if (!$lesson) {
            show_404();
        }

        $module = $this->Lesson_model->get_module($lesson->module_id);
        if (!$module) {
            show_404();
        }

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) {
            show_404();
        }

        $data['title'] = 'Lesson: ' . $lesson->title;
        $data['subject'] = $subject;
        $data['module'] = $module;
        $data['item'] = $lesson;
        $data['item_type'] = 'lesson';

        $this->render('course/item_view', $data);
    }

    public function activity($activity_id = null)
    {
        if (!$activity_id) {
            redirect('course/subjects');
        }

        $activity = $this->Lesson_model->get_activity($activity_id);
        if (!$activity) {
            show_404();
        }

        $module = $this->Lesson_model->get_module($activity->module_id);
        if (!$module) {
            show_404();
        }

        $subject = $this->Academic_model->get_subject($module->subject_id);
        if (!$subject) {
            show_404();
        }

        $data['title'] = 'Activity: ' . $activity->title;
        $data['subject'] = $subject;
        $data['module'] = $module;
        $data['item'] = $activity;
        $data['item_type'] = 'activity';

        $this->render('course/item_view', $data);
    }

    // ---- Module Management ----
    public function create_module($subject_id)
    {
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
        
        $this->Lesson_model->delete_module($module_id);
        $this->session->set_flashdata('success', 'Module deleted successfully.');
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    // ---- Lesson Management ----
    public function create_lesson($module_id)
    {
        $module = $this->Lesson_model->get_module($module_id);
        if (!$module) show_404();
        
        if ($this->input->method() === 'post') {
            $order = $this->Lesson_model->get_next_order('lessons', 'module_id', $module_id);
            $data = array(
                'module_id'       => $module_id,
                'title'           => $this->input->post('title', TRUE),
                'content'         => $this->input->post('content'),
                'content_type'    => $this->input->post('content_type', TRUE),
                'duration_minutes'=> $this->input->post('duration_minutes'),
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
        
        if ($this->input->method() === 'post') {
            $data = array(
                'title'           => $this->input->post('title', TRUE),
                'content'         => $this->input->post('content'),
                'content_type'    => $this->input->post('content_type', TRUE),
                'duration_minutes'=> $this->input->post('duration_minutes'),
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
        
        $this->Lesson_model->delete_lesson($lesson_id);
        $this->session->set_flashdata('success', 'Lesson deleted successfully.');
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }

    // ---- Activity Management ----
    public function create_activity($module_id)
    {
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
        $activity = $this->Lesson_model->get_activity($activity_id);
        if (!$activity) show_404();
        
        $module = $this->Lesson_model->get_module($activity->module_id);
        
        $this->Lesson_model->delete_activity($activity_id);
        $this->session->set_flashdata('success', 'Activity deleted successfully.');
        redirect('course/content/' . $module->subject_id . '?edit=1');
    }
}
