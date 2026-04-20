<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Academic extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Academic_model', 'User_model'));
    }

    // ---- School Years ----
    public function school_years()
    {
        $data['title'] = 'School Years';
        $data['school_years'] = $this->Academic_model->get_school_years();
        $this->render('academic/school_years', $data);
    }

    public function create_school_year()
    {
        if ($this->input->method() === 'post') {
            $d = array(
                'school_id'  => 1,
                'year_start' => $this->input->post('year_start'),
                'year_end'   => $this->input->post('year_end'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
            );
            $sy_id = $this->Academic_model->create_school_year($d);
            if ($d['is_active']) {
                $this->Academic_model->set_active_school_year($sy_id);
            }
            $this->session->set_flashdata('success', 'School year created.');
            redirect('academic/school_years');
        }
        $data['title'] = 'Add School Year';
        $this->render('academic/school_year_form', $data);
    }

    public function activate_school_year($id)
    {
        $this->Academic_model->set_active_school_year($id);
        $this->session->set_flashdata('success', 'School year activated.');
        redirect('academic/school_years');
    }

    // ---- Grade Levels ----
    public function grade_levels()
    {
        $data['title'] = 'Grade Levels (K-12)';
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $this->render('academic/grade_levels', $data);
    }

    // ---- SHS Tracks & Strands ----
    public function strands()
    {
        $data['title'] = 'SHS Tracks & Strands';
        $data['tracks'] = $this->Academic_model->get_tracks();
        $data['strands'] = $this->Academic_model->get_strands();
        $this->render('academic/strands', $data);
    }

    // ---- Programs (CHED) ----
    public function programs()
    {
        $data['title'] = 'Programs (CHED)';
        $data['programs'] = $this->Academic_model->get_programs();
        $this->render('academic/programs', $data);
    }

    public function create_program()
    {
        if ($this->input->method() === 'post') {
            $d = array(
                'name'              => $this->input->post('name', TRUE),
                'code'              => $this->input->post('code', TRUE),
                'description'       => $this->input->post('description', TRUE),
                'degree_type'       => $this->input->post('degree_type', TRUE),
                'total_units'       => $this->input->post('total_units'),
                'years_to_complete' => $this->input->post('years_to_complete'),
            );
            $this->Academic_model->create_program($d);
            $this->session->set_flashdata('success', 'Program created.');
            redirect('academic/programs');
        }
        $data['title'] = 'Add Program';
        $data['program'] = null;
        $this->render('academic/program_form', $data);
    }

    public function edit_program($id)
    {
        $data['program'] = $this->Academic_model->get_program($id);
        if (!$data['program']) show_404();

        if ($this->input->method() === 'post') {
            $d = array(
                'name'              => $this->input->post('name', TRUE),
                'code'              => $this->input->post('code', TRUE),
                'description'       => $this->input->post('description', TRUE),
                'degree_type'       => $this->input->post('degree_type', TRUE),
                'total_units'       => $this->input->post('total_units'),
                'years_to_complete' => $this->input->post('years_to_complete'),
            );
            $this->Academic_model->update_program($id, $d);
            $this->session->set_flashdata('success', 'Program updated.');
            redirect('academic/programs');
        }
        $data['title'] = 'Edit Program';
        $this->render('academic/program_form', $data);
    }

    public function delete_program($id)
    {
        $this->Academic_model->delete_program($id);
        $this->session->set_flashdata('success', 'Program deleted.');
        redirect('academic/programs');
    }

    // ---- Subjects ----
    public function subjects()
    {
        $filters = array();
        if ($this->input->get('system_type')) {
            $filters['system_type'] = $this->input->get('system_type');
        }
        $data['title'] = 'Subjects';
        $data['subjects'] = $this->Academic_model->get_subjects($filters);
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();
        $data['learning_areas'] = $this->Academic_model->get_learning_areas();
        $data['filter_type'] = $this->input->get('system_type');
        $this->render('academic/subjects', $data);
    }

    public function create_subject()
    {
        if ($this->input->method() === 'post') {
            $d = array(
                'code'            => $this->input->post('code', TRUE),
                'name'            => $this->input->post('name', TRUE),
                'description'     => $this->input->post('description', TRUE),
                'system_type'     => $this->input->post('system_type', TRUE),
                'grade_level_id'  => $this->input->post('grade_level_id') ?: NULL,
                'learning_area_id'=> $this->input->post('learning_area_id') ?: NULL,
                'strand_id'       => $this->input->post('strand_id') ?: NULL,
                'program_id'      => $this->input->post('program_id') ?: NULL,
                'year_level'      => $this->input->post('year_level') ?: NULL,
                'semester_type'   => $this->input->post('semester_type') ?: NULL,
                'units'           => $this->input->post('units') ?: NULL,
                'lec_hours'       => $this->input->post('lec_hours') ?: NULL,
                'lab_hours'       => $this->input->post('lab_hours') ?: NULL,
            );
            $this->Academic_model->create_subject($d);
            $this->session->set_flashdata('success', 'Subject created.');
            redirect('academic/subjects');
        }
        $data['title'] = 'Add Subject';
        $data['subject'] = null;
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();
        $data['learning_areas'] = $this->Academic_model->get_learning_areas();
        $data['strands'] = $this->Academic_model->get_strands();
        $this->render('academic/subject_form', $data);
    }

    public function edit_subject($id)
    {
        $data['subject'] = $this->Academic_model->get_subject($id);
        if (!$data['subject']) show_404();

        if ($this->input->method() === 'post') {
            $d = array(
                'code'            => $this->input->post('code', TRUE),
                'name'            => $this->input->post('name', TRUE),
                'description'     => $this->input->post('description', TRUE),
                'system_type'     => $this->input->post('system_type', TRUE),
                'grade_level_id'  => $this->input->post('grade_level_id') ?: NULL,
                'learning_area_id'=> $this->input->post('learning_area_id') ?: NULL,
                'strand_id'       => $this->input->post('strand_id') ?: NULL,
                'program_id'      => $this->input->post('program_id') ?: NULL,
                'year_level'      => $this->input->post('year_level') ?: NULL,
                'semester_type'   => $this->input->post('semester_type') ?: NULL,
                'units'           => $this->input->post('units') ?: NULL,
                'lec_hours'       => $this->input->post('lec_hours') ?: NULL,
                'lab_hours'       => $this->input->post('lab_hours') ?: NULL,
            );
            $this->Academic_model->update_subject($id, $d);
            $this->session->set_flashdata('success', 'Subject updated.');
            redirect('academic/subjects');
        }
        $data['title'] = 'Edit Subject';
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();
        $data['learning_areas'] = $this->Academic_model->get_learning_areas();
        $data['strands'] = $this->Academic_model->get_strands();
        $this->render('academic/subject_form', $data);
    }

    // ---- Sections ----
    public function sections()
    {
        $sy = $this->Academic_model->get_active_school_year();
        $filters = array();
        if ($sy) $filters['school_year_id'] = $sy->id;
        $data['title'] = 'Sections';
        $data['school_year'] = $sy;
        $data['sections'] = $this->Academic_model->get_sections($filters);
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();
        $data['teachers'] = $this->Academic_model->get_teachers();
        $this->render('academic/sections', $data);
    }

    public function create_section()
    {
        $sy = $this->Academic_model->get_active_school_year();
        if ($this->input->method() === 'post') {
            $d = array(
                'school_year_id' => $sy->id,
                'name'           => $this->input->post('name', TRUE),
                'system_type'    => $this->input->post('system_type', TRUE),
                'grade_level_id' => $this->input->post('grade_level_id') ?: NULL,
                'strand_id'      => $this->input->post('strand_id') ?: NULL,
                'program_id'     => $this->input->post('program_id') ?: NULL,
                'year_level'     => $this->input->post('year_level') ?: NULL,
                'adviser_id'     => $this->input->post('adviser_id') ?: NULL,
                'capacity'       => $this->input->post('capacity') ?: 40,
            );
            $this->Academic_model->create_section($d);
            $this->session->set_flashdata('success', 'Section created.');
            redirect('academic/sections');
        }
        $data['title'] = 'Add Section';
        $data['section'] = null;
        $data['school_year'] = $sy;
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();
        $data['strands'] = $this->Academic_model->get_strands();
        $data['teachers'] = $this->Academic_model->get_teachers();
        $this->render('academic/section_form', $data);
    }

    public function edit_section($id)
    {
        $data['section'] = $this->Academic_model->get_section($id);
        if (!$data['section']) show_404();

        if ($this->input->method() === 'post') {
            $d = array(
                'name'           => $this->input->post('name', TRUE),
                'system_type'    => $this->input->post('system_type', TRUE),
                'grade_level_id' => $this->input->post('grade_level_id') ?: NULL,
                'strand_id'      => $this->input->post('strand_id') ?: NULL,
                'program_id'     => $this->input->post('program_id') ?: NULL,
                'year_level'     => $this->input->post('year_level') ?: NULL,
                'adviser_id'     => $this->input->post('adviser_id') ?: NULL,
                'capacity'       => $this->input->post('capacity') ?: 40,
            );
            $this->Academic_model->update_section($id, $d);
            $this->session->set_flashdata('success', 'Section updated.');
            redirect('academic/sections');
        }
        $data['title'] = 'Edit Section';
        $data['school_year'] = $this->Academic_model->get_active_school_year();
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();
        $data['strands'] = $this->Academic_model->get_strands();
        $data['teachers'] = $this->Academic_model->get_teachers();
        $this->render('academic/section_form', $data);
    }
}
