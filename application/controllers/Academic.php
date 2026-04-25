<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Academic extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_role(array('super_admin', 'school_admin', 'course_creator'));
        $this->require_school();
        $this->load->model(array('Academic_model', 'User_model'));
    }

    // ---- School Years ----
    public function school_years()
    {
        $data['title'] = 'School Years';
        $data['school_years'] = $this->Academic_model->get_school_years($this->school_id);
        $this->render('academic/school_years', $data);
    }

    public function create_school_year()
    {
        if ($this->input->method() === 'post') {
            $d = array(
                'school_id'  => $this->school_id,
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
        $data['grade_levels'] = $this->Academic_model->get_grade_levels(null, $this->school_id);
        $this->render('academic/grade_levels', $data);
    }

    public function create_grade_level()
    {
        if ($this->input->method() === 'post') {
            $d = array(
                'code'         => $this->input->post('code', TRUE),
                'name'         => $this->input->post('name', TRUE),
                'category'     => $this->input->post('category', TRUE),
                'level_order'  => $this->input->post('level_order'),
                'school_id'    => $this->school_id,
                'status'       => 1,
            );
            $this->db->insert('grade_levels', $d);
            $this->session->set_flashdata('success', 'Grade level created.');
            redirect('academic/grade_levels');
        }
        $data['title'] = 'Add Grade Level';
        $data['grade_level'] = null;
        $this->render('academic/grade_level_form', $data);
    }

    public function edit_grade_level($id)
    {
        $data['grade_level'] = $this->Academic_model->get_grade_level($id);
        if (!$data['grade_level']) show_404();

        if ($this->input->method() === 'post') {
            $d = array(
                'code'         => $this->input->post('code', TRUE),
                'name'         => $this->input->post('name', TRUE),
                'category'     => $this->input->post('category', TRUE),
                'level_order'  => $this->input->post('level_order'),
            );
            $this->db->where('id', $id)->update('grade_levels', $d);
            $this->session->set_flashdata('success', 'Grade level updated.');
            redirect('academic/grade_levels');
        }
        $data['title'] = 'Edit Grade Level';
        $this->render('academic/grade_level_form', $data);
    }

    public function delete_grade_level($id)
    {
        $this->Academic_model->delete_grade_level($id);
        $this->session->set_flashdata('success', 'Grade level deleted.');
        redirect('academic/grade_levels');
    }

    public function grade_level_subjects($grade_level_id)
    {
        $grade_level = $this->Academic_model->get_grade_level($grade_level_id);
        if (!$grade_level) show_404();

        if ($this->input->method() === 'post') {
            // Add subject to grade level
            $subject_id = $this->input->post('subject_id');
            $units = $this->input->post('units');
            
            if ($subject_id) {
                $d = array(
                    'grade_level_id'  => $grade_level_id,
                    'units'           => $units,
                );
                $this->Academic_model->update_subject($subject_id, $d);
                $this->session->set_flashdata('success', 'Subject added to grade level.');
            }
            redirect('academic/grade_level_subjects/' . $grade_level_id);
        }

        $data['title'] = 'Manage Subjects - ' . $grade_level->name;
        $data['grade_level'] = $grade_level;
        $data['grade_level_subjects'] = $this->Academic_model->get_subjects_by_grade_level($grade_level_id);
        $data['available_subjects'] = $this->Academic_model->get_subjects(array('grade_level_id' => null));
        $this->render('academic/grade_level_subjects', $data);
    }

    public function remove_subject_from_grade_level($grade_level_id, $subject_id)
    {
        $subject = $this->Academic_model->get_subject($subject_id);
        if ($subject) {
            $d = array(
                'grade_level_id' => null,
            );
            $this->Academic_model->update_subject($subject_id, $d);
            $this->session->set_flashdata('success', 'Subject removed from grade level.');
        }
        redirect('academic/grade_level_subjects/' . $grade_level_id);
    }

    public function create_grade_level_subject($grade_level_id)
    {
        if ($this->input->method() === 'post') {
            $d = array(
                'code'            => $this->input->post('code', TRUE),
                'description'     => $this->input->post('description', TRUE),
                'grade_level_id'  => $grade_level_id,
                'semester_type'   => $this->input->post('semester_type', TRUE),
                'units'           => $this->input->post('units'),
                'lec_hours'       => $this->input->post('lec_hours'),
                'lab_hours'       => $this->input->post('lab_hours'),
                'status'          => 1,
            );
            $subject_id = $this->Academic_model->create_subject($d);
            $this->session->set_flashdata('success', 'Subject created and added to grade level.');
            redirect('academic/grade_level_subjects/' . $grade_level_id);
        }
        redirect('academic/grade_levels');
    }

    public function edit_grade_level_subject($grade_level_id, $subject_id)
    {
        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject || $subject->grade_level_id != $grade_level_id) show_404();

        if ($this->input->method() === 'post') {
            $d = array(
                'semester_type'   => $this->input->post('semester_type', TRUE),
                'units'           => $this->input->post('units'),
                'lec_hours'       => $this->input->post('lec_hours'),
                'lab_hours'       => $this->input->post('lab_hours'),
            );
            $this->Academic_model->update_subject($subject_id, $d);
            $this->session->set_flashdata('success', 'Subject updated successfully.');
            redirect('academic/grade_level_subjects/' . $grade_level_id);
        }

        $data['title'] = 'Edit Subject';
        $data['grade_level'] = $this->Academic_model->get_grade_level($grade_level_id);
        $data['subject'] = $subject;
        $this->render('academic/edit_grade_level_subject', $data);
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
        $data['title'] = 'Programs';
        $data['programs'] = $this->Academic_model->get_programs($this->school_id);
        $this->render('academic/programs', $data);
    }

    public function create_program()
    {
        if ($this->input->method() === 'post') {
            $type = $this->input->post('type', TRUE);

            $d = array(
                'name'              => $this->input->post('name', TRUE),
                'code'              => $this->input->post('code', TRUE),
                'description'       => $this->input->post('description', TRUE),
                'type'              => $type,
                'school_id'         => $this->school_id,
            );

            $this->Academic_model->create_academic_program($d);
            $this->session->set_flashdata('success', ($type === 'grade_level') ? 'Grade level created.' : 'Program created.');
            redirect('academic/programs');
        }
        $data['title'] = 'Add Program';
        $data['program'] = null;
        $this->render('academic/program_form', $data);
    }

    public function edit_program($id)
    {
        $data['program'] = $this->Academic_model->get_academic_program($id);
        if (!$data['program']) show_404();

        if ($this->input->method() === 'post') {
            $type = $this->input->post('type', TRUE);

            $d = array(
                'name'              => $this->input->post('name', TRUE),
                'code'              => $this->input->post('code', TRUE),
                'description'       => $this->input->post('description', TRUE),
                'type'              => $type,
            );

            $this->Academic_model->update_academic_program($id, $d);
            $this->session->set_flashdata('success', ($type === 'grade_level') ? 'Grade level updated.' : 'Program updated.');
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

    public function program_subjects($program_id)
    {
        $program = $this->Academic_model->get_program($program_id);
        if (!$program) show_404();

        if ($this->input->method() === 'post') {
            // Add subject to program
            $subject_id = $this->input->post('subject_id');
            $semester_type = $this->input->post('semester_type', TRUE);
            $year_level = $this->input->post('year_level');
            $units = $this->input->post('units');

            if ($subject_id) {
                $d = array(
                    'program_id'      => $program_id,
                    'semester_type'   => $semester_type,
                    'year_level'      => $year_level,
                    'units'           => $units,
                );
                $this->Academic_model->update_subject($subject_id, $d);
                $this->session->set_flashdata('success', 'Subject added to program.');
            }
            redirect('academic/program_subjects/' . $program_id);
        }

        $this->Academic_model->ensure_subject_teachers_table();

        $raw_teachers = $this->Academic_model->get_teachers_by_school($this->school_id);

        $program_subjects = $this->Academic_model->get_subjects_by_program($program_id);

        // Build per-subject assigned teacher_ids map
        $assigned_map = array();
        foreach ($program_subjects as $s) {
            $assigned_map[$s->id] = $this->Academic_model->get_subject_teacher_ids($s->id);
        }

        $data['title']            = 'Manage Subjects - ' . $program->name;
        $data['program']          = $program;
        $data['program_subjects'] = $program_subjects;
        $data['available_subjects'] = $this->Academic_model->get_subjects(array('program_id' => null));
        $data['is_admin']            = $this->is_admin();
        $data['can_manage_teachers'] = in_array($this->role_slug, array('super_admin', 'school_admin', 'course_creator'));
        $data['teachers']            = $raw_teachers;
        $data['assigned_map']        = $assigned_map;
        $this->render('academic/program_subjects', $data);
    }

    public function assign_subject_teacher($program_id, $subject_id)
    {
        $program = $this->Academic_model->get_program($program_id);
        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$program || !$subject || $subject->program_id != $program_id) show_404();

        $user_id = (int)$this->input->post('user_id');
        if ($user_id) {
            $action = $this->Academic_model->toggle_subject_teacher($subject_id, $user_id);
            $this->session->set_flashdata('success', $action === 'added' ? 'Teacher assigned.' : 'Teacher removed.');
        }
        redirect('academic/program_subjects/' . $program_id);
    }

    public function remove_subject_from_program($program_id, $subject_id)
    {
        $subject = $this->Academic_model->get_subject($subject_id);
        if ($subject) {
            $this->Academic_model->update_subject($subject_id, array('program_id' => null, 'semester_type' => null));
            $this->session->set_flashdata('success', 'Subject removed from program.');
        }
        redirect('academic/program_subjects/' . $program_id);
    }

    public function create_program_subject($program_id)
    {
        if (!in_array($this->role_slug, array('super_admin', 'school_admin'))) {
            $this->session->set_flashdata('error', 'Only school admins can add subjects.');
            redirect('academic/program_subjects/' . $program_id);
        }

        if ($this->input->method() === 'post') {
            $code = $this->input->post('code', TRUE);
            if ($this->Academic_model->subject_code_exists_in_program($program_id, $code)) {
                $this->session->set_flashdata('error', 'Course code "' . $code . '" already exists in this program.');
                redirect('academic/program_subjects/' . $program_id);
            }
            $d = array(
                'code'        => $code,
                'description' => $this->input->post('description', TRUE),
                'program_id'  => $program_id,
                'status'      => 1,
            );
            $this->Academic_model->create_subject($d);
            $this->session->set_flashdata('success', 'Subject created and added to program.');
            redirect('academic/program_subjects/' . $program_id);
        }
        redirect('academic/programs');
    }

    public function edit_program_subject($program_id, $subject_id)
    {
        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject || $subject->program_id != $program_id) show_404();

        if ($this->input->method() === 'post') {
            $code = $this->input->post('code', TRUE);
            if ($this->Academic_model->subject_code_exists_in_program($program_id, $code, $subject_id)) {
                $this->session->set_flashdata('error', 'Course code "' . $code . '" already exists in this program.');
                redirect('academic/edit_program_subject/' . $program_id . '/' . $subject_id);
            }
            $d = array(
                'code'        => $code,
                'description' => $this->input->post('description', TRUE),
            );
            $this->Academic_model->update_subject($subject_id, $d);
            $this->session->set_flashdata('success', 'Subject updated successfully.');
            redirect('academic/program_subjects/' . $program_id);
        }

        $data['title']   = 'Edit Subject';
        $data['program'] = $this->Academic_model->get_program($program_id);
        $data['subject'] = $subject;
        $this->render('academic/edit_program_subject', $data);
    }

    // ---- Subjects ----
    public function subjects()
    {
        if ($this->is_course_creator()) {
            redirect('academic/programs');
        }

        $filters = array('school_id' => $this->school_id);
        if ($this->input->get('system_type')) {
            $filters['system_type'] = $this->input->get('system_type');
        }
        $data['title'] = 'Subjects';
        $data['subjects'] = $this->Academic_model->get_subjects($filters);
        $data['grade_levels'] = $this->Academic_model->get_grade_levels(null, $this->school_id);
        $data['programs'] = $this->Academic_model->get_programs($this->school_id);
        $data['learning_areas'] = $this->Academic_model->get_learning_areas();
        $data['filter_type'] = $this->input->get('system_type');
        $this->render('academic/subjects', $data);
    }

    public function create_subject()
    {
        if ($this->input->method() === 'post') {
            $d = array(
                'code'            => $this->input->post('code', TRUE),
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
                'school_id'       => $this->school_id,
            );
            $this->Academic_model->create_subject($d);
            $this->session->set_flashdata('success', 'Subject created.');
            redirect('academic/subjects');
        }
        $data['title'] = 'Add Subject';
        $data['subject'] = null;
        $data['grade_levels'] = $this->Academic_model->get_grade_levels(null, $this->school_id);
        $data['programs'] = $this->Academic_model->get_programs($this->school_id);
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
        $sy = $this->Academic_model->get_active_school_year($this->school_id);
        $filters = array();
        if ($sy) $filters['school_year_id'] = $sy->id;
        if ($this->school_id) $filters['school_id'] = $this->school_id;
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
        $sy = $this->Academic_model->get_active_school_year($this->school_id);
        if ($this->input->method() === 'post') {
            $d = array(
                'school_year_id' => $sy->id,
                'school_id'      => $this->school_id,
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
        $data['school_year'] = $this->Academic_model->get_active_school_year($this->school_id);
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs();
        $data['strands'] = $this->Academic_model->get_strands();
        $data['teachers'] = $this->Academic_model->get_teachers();
        $this->render('academic/section_form', $data);
    }
}
