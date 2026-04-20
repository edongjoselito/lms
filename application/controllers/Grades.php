<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grades extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model(array('Grade_model', 'Academic_model', 'Enrollment_model'));
    }

    public function index()
    {
        $sy = $this->Academic_model->get_active_school_year();
        $data['title'] = 'Grading';
        $data['school_year'] = $sy;

        if ($this->is_teacher()) {
            $teacher = $this->Academic_model->get_teacher_by_user($this->current_user->id);
            $data['classes'] = $teacher ? $this->Academic_model->get_teacher_classes($teacher->id, $sy ? $sy->id : null) : array();
            $this->render('grades/teacher_index', $data);
        } else {
            $data['sections'] = $sy ? $this->Academic_model->get_sections(array('school_year_id' => $sy->id)) : array();
            $this->render('grades/admin_index', $data);
        }
    }

    public function class_record($class_program_id)
    {
        $cp = $this->Academic_model->get_class_program($class_program_id);
        if (!$cp) show_404();

        $sy = $this->Academic_model->get_active_school_year();
        $students = $sy ? $this->Enrollment_model->get_section_students($cp->section_id, $sy->id) : array();
        $semester = $this->Academic_model->get_active_semester('quarter');

        $grades = array();
        foreach ($students as $student) {
            $grades[$student->id] = $this->Grade_model->get_student_grade($student->id, $class_program_id, $semester ? $semester->id : null);
        }

        $data['title'] = 'Class Record: ' . $cp->subject_name;
        $data['class_program'] = $cp;
        $data['students'] = $students;
        $data['grades'] = $grades;
        $data['semester'] = $semester;
        $data['components'] = $this->Grade_model->get_components('deped', 'core');
        $this->render('grades/class_record', $data);
    }

    public function encode($class_program_id, $enrollment_id)
    {
        $cp = $this->Academic_model->get_class_program($class_program_id);
        $enrollment = $this->Enrollment_model->get_enrollment($enrollment_id);
        if (!$cp || !$enrollment) show_404();

        $semester = $this->Academic_model->get_active_semester('quarter');
        $system_type = $enrollment->system_type;

        if ($this->input->method() === 'post') {
            $components = $this->input->post('component');
            if ($components) {
                foreach ($components as $comp_id => $entries) {
                    foreach ($entries as $entry) {
                        if (!empty($entry['score']) || $entry['score'] === '0') {
                            $d = array(
                                'enrollment_id'    => $enrollment_id,
                                'class_program_id' => $class_program_id,
                                'component_id'     => $comp_id,
                                'semester_id'      => $semester ? $semester->id : null,
                                'activity_name'    => $entry['name'],
                                'score'            => $entry['score'],
                                'total_score'      => $entry['total'],
                            );
                            if (!empty($entry['id'])) {
                                $d['id'] = $entry['id'];
                            }
                            $this->Grade_model->save_entry($d);
                        }
                    }
                }
            }

            // Compute grade
            if ($system_type === 'deped') {
                $result = $this->Grade_model->compute_deped_grade(
                    $enrollment_id, $class_program_id,
                    $semester ? $semester->id : null, 'core'
                );
                $grade_data = array(
                    'enrollment_id'    => $enrollment_id,
                    'class_program_id' => $class_program_id,
                    'semester_id'      => $semester ? $semester->id : null,
                    'system_type'      => 'deped',
                    'ww_score'         => isset($result['components']['WW']) ? $result['components']['WW']['weighted'] : null,
                    'pt_score'         => isset($result['components']['PT']) ? $result['components']['PT']['weighted'] : null,
                    'qa_score'         => isset($result['components']['QA']) ? $result['components']['QA']['weighted'] : null,
                    'initial_grade'    => $result['initial_grade'],
                    'transmuted_grade' => $result['transmuted_grade'],
                    'final_grade'      => $result['transmuted_grade'],
                    'remarks'          => $result['remarks'],
                );
                $this->Grade_model->save_student_grade($grade_data);
            }

            $this->session->set_flashdata('success', 'Grades saved and computed.');
            redirect("grades/class_record/{$class_program_id}");
        }

        $subject_cat = 'core';
        $components = $this->Grade_model->get_components($system_type, $subject_cat);
        $entries = $this->Grade_model->get_entries($enrollment_id, $class_program_id, $semester ? $semester->id : null);

        $grouped_entries = array();
        foreach ($entries as $e) {
            $grouped_entries[$e->component_id][] = $e;
        }

        $data['title'] = 'Encode Grades';
        $data['class_program'] = $cp;
        $data['enrollment'] = $enrollment;
        $data['semester'] = $semester;
        $data['components'] = $components;
        $data['entries'] = $grouped_entries;
        $data['system_type'] = $system_type;
        $this->render('grades/encode', $data);
    }
}
