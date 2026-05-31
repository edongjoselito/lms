<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Academic extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        if (in_array($this->router->fetch_method(), array('section_students', 'student_subject_records'))) {
            $this->require_login();
        } else {
            $this->require_role(array('super_admin', 'school_admin', 'course_creator'));
        }
        $this->require_school();
        $this->load->model(array('Academic_model', 'User_model'));
    }

    private function is_teacher_assigned_to_subject($subject_id)
    {
        $subject_id = (int) $subject_id;
        if ($this->role_slug !== 'teacher' || !$this->current_user || $subject_id <= 0) {
            return false;
        }

        $this->Academic_model->ensure_subject_teachers_table();
        $assigned = $this->db->where('subject_id', $subject_id)
            ->where('user_id', (int) $this->current_user->id)
            ->get('subject_teachers')
            ->row();

        return (bool) $assigned;
    }

    private function can_access_section_students($section, $subject_id = null)
    {
        if (!$section) {
            return false;
        }

        if (in_array($this->role_slug, array('super_admin', 'school_admin', 'course_creator'))) {
            return true;
        }

        if ($this->role_slug !== 'teacher' || !$this->current_user) {
            return false;
        }

        if (!empty($this->school_id) && !empty($section->school_id) && (int) $section->school_id !== (int) $this->school_id) {
            return false;
        }

        if (!empty($section->adviser_id) && (int) $section->adviser_id === (int) $this->current_user->id) {
            return true;
        }

        return $this->is_teacher_assigned_to_subject($subject_id);
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
        // Get school type
        $school = $this->db->where('id', $this->school_id)->get('schools')->row();
        $school_type = $school ? $school->type : null;
        $data['school_type'] = $school_type;

        if ($this->input->method() === 'post') {
            // Auto-detect type based on school type
            $type = ($school_type === 'deped') ? 'grade_level' : 'program';

            $code = $this->input->post('code', TRUE);
            $name = $this->input->post('name', TRUE);
            
            // Extract year level from code (e.g., G3 -> 3) or name (e.g., Grade 03 -> 3)
            $year_level = null;
            if (preg_match('/G(\d+)/', $code, $matches)) {
                $year_level = (int)$matches[1];
            } elseif (preg_match('/Grade\s*(\d+)/i', $name, $matches)) {
                $year_level = (int)$matches[1];
            }

            $d = array(
                'name'              => $name,
                'code'              => $code,
                'description'       => $this->input->post('description', TRUE),
                'type'              => $type,
                'school_id'         => $this->school_id,
                'year_level'        => $year_level,
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
        // Try to fetch from programs table first
        $data['program'] = $this->db->where('id', $id)->get('programs')->row();

        // If not found, try academic_programs table
        if (!$data['program']) {
            $data['program'] = $this->db->where('id', $id)->get('academic_programs')->row();
        }

        if (!$data['program']) show_404();

        // Get school type
        $school = $this->db->where('id', $this->school_id)->get('schools')->row();
        $data['school_type'] = $school ? $school->type : null;

        // If program doesn't have name/code, generate from year_level
        if (!isset($data['program']->name) && isset($data['program']->year_level)) {
            $data['program']->name = 'Grade ' . str_pad($data['program']->year_level, 2, '0', STR_PAD_LEFT);
        }
        if (!isset($data['program']->code) && isset($data['program']->year_level)) {
            $data['program']->code = 'G' . str_pad($data['program']->year_level, 2, '0', STR_PAD_LEFT);
        }

        if ($this->input->method() === 'post') {
            $name = $this->input->post('name', TRUE);
            $code = $this->input->post('code', TRUE);
            $description = $this->input->post('description', TRUE);

            // Extract year level from code or name
            $year_level = null;
            if (preg_match('/G(\d+)/', $code, $matches)) {
                $year_level = (int)$matches[1];
            } elseif (preg_match('/Grade\s*(\d+)/i', $name, $matches)) {
                $year_level = (int)$matches[1];
            }

            // Update in the table where the record exists
            $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();
            if ($check_academic > 0) {
                $check_program = $this->db->where('id', $id)->get('academic_programs')->row();
                if ($check_program) {
                    $d = array(
                        'name' => $name,
                        'code' => $code,
                        'description' => $description,
                    );
                    $this->db->where('id', $id)->update('academic_programs', $d);
                } else {
                    // Update programs table - check which columns exist
                    $checkName = $this->db->query("SHOW COLUMNS FROM programs LIKE 'name'")->num_rows();
                    $checkCode = $this->db->query("SHOW COLUMNS FROM programs LIKE 'code'")->num_rows();
                    $checkDesc = $this->db->query("SHOW COLUMNS FROM programs LIKE 'description'")->num_rows();
                    $checkYearLevel = $this->db->query("SHOW COLUMNS FROM programs LIKE 'year_level'")->num_rows();

                    $d = array();
                    if ($checkName > 0) $d['name'] = $name;
                    if ($checkCode > 0) $d['code'] = $code;
                    if ($checkDesc > 0) $d['description'] = $description;
                    if ($checkYearLevel > 0 && $year_level) $d['year_level'] = $year_level;

                    $this->db->where('id', $id)->update('programs', $d);
                }
            } else {
                // Update programs table - check which columns exist
                $checkName = $this->db->query("SHOW COLUMNS FROM programs LIKE 'name'")->num_rows();
                $checkCode = $this->db->query("SHOW COLUMNS FROM programs LIKE 'code'")->num_rows();
                $checkDesc = $this->db->query("SHOW COLUMNS FROM programs LIKE 'description'")->num_rows();
                $checkYearLevel = $this->db->query("SHOW COLUMNS FROM programs LIKE 'year_level'")->num_rows();

                $d = array();
                if ($checkName > 0) $d['name'] = $name;
                if ($checkCode > 0) $d['code'] = $code;
                if ($checkDesc > 0) $d['description'] = $description;
                if ($checkYearLevel > 0 && $year_level) $d['year_level'] = $year_level;

                $this->db->where('id', $id)->update('programs', $d);
            }

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
            $teacher_id = $this->input->post('teacher_id');

            if ($subject_id) {
                // Get year_level from program if not provided
                if (!$year_level) {
                    // Check if year_level column exists in the program table
                    $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();
                    if ($check_academic > 0) {
                        $check_year_level = $this->db->query("SHOW COLUMNS FROM academic_programs LIKE 'year_level'")->num_rows();
                        if ($check_year_level > 0) {
                            $prog = $this->db->select('year_level')->where('id', $program_id)->get('academic_programs')->row();
                            if ($prog && isset($prog->year_level)) {
                                $year_level = $prog->year_level;
                            }
                        }
                    } else {
                        $check_year_level = $this->db->query("SHOW COLUMNS FROM programs LIKE 'year_level'")->num_rows();
                        if ($check_year_level > 0) {
                            $prog = $this->db->select('year_level')->where('id', $program_id)->get('programs')->row();
                            if ($prog && isset($prog->year_level)) {
                                $year_level = $prog->year_level;
                            }
                        }
                    }
                }

                $d = array(
                    'program_id'      => $program_id,
                    'school_id'       => $this->school_id,
                    'year_level'      => $year_level,
                    'units'           => $units,
                );
                
                $this->Academic_model->update_subject($subject_id, $d);
                
                // Handle teacher assignment using subject_teachers table
                if ($teacher_id) {
                    // Clear existing teachers for this subject
                    $this->Academic_model->clear_subject_teachers($subject_id);
                    // Add the new teacher
                    $this->Academic_model->add_subject_teacher($subject_id, $teacher_id);
                }
                
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

        $data['title']            = 'Manage Subjects - ' . (isset($program->name) ? $program->name : (isset($program->year_level) ? 'Grade ' . str_pad($program->year_level, 2, '0', STR_PAD_LEFT) : 'Program'));
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
            $this->Academic_model->delete_subject($subject_id, $this->school_id, true);
            $this->session->set_flashdata('success', 'Subject deleted successfully.');
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
            
            // Get year_level from POST or from program
            $year_level = $this->input->post('year_level');
            if (!$year_level) {
                $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();
                if ($check_academic > 0) {
                    $check_year_level = $this->db->query("SHOW COLUMNS FROM academic_programs LIKE 'year_level'")->num_rows();
                    if ($check_year_level > 0) {
                        $prog = $this->db->select('year_level')->where('id', $program_id)->get('academic_programs')->row();
                        if ($prog && isset($prog->year_level)) {
                            $year_level = $prog->year_level;
                        }
                    }
                } else {
                    $check_year_level = $this->db->query("SHOW COLUMNS FROM programs LIKE 'year_level'")->num_rows();
                    if ($check_year_level > 0) {
                        $prog = $this->db->select('year_level')->where('id', $program_id)->get('programs')->row();
                        if ($prog && isset($prog->year_level)) {
                            $year_level = $prog->year_level;
                        }
                    }
                }
            }
            
            $d = array(
                'code'        => $code,
                'description' => $this->input->post('description', TRUE),
                'program_id'  => $program_id,
                'school_id'   => $this->school_id,
                'year_level'  => $year_level,
                'status'      => 1,
            );
            $subject_id = $this->Academic_model->create_subject($d);
            
            // Handle teacher assignment
            $teacher_id = $this->input->post('teacher_id');
            if ($teacher_id && $subject_id) {
                $this->Academic_model->set_subject_teachers($subject_id, array($teacher_id));
                // Save teacher_id directly to subjects table
                $this->Academic_model->update_subject($subject_id, array('teacher_id' => $teacher_id));
            }
            
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
            
            // Handle teacher assignment
            $teacher_id = $this->input->post('teacher_id');
            if ($teacher_id) {
                $this->Academic_model->set_subject_teachers($subject_id, array($teacher_id));
                // Save teacher_id directly to subjects table
                $this->Academic_model->update_subject($subject_id, array('teacher_id' => $teacher_id));
            } else {
                // Clear assignment if no teacher selected
                $this->Academic_model->set_subject_teachers($subject_id, array());
                $this->Academic_model->update_subject($subject_id, array('teacher_id' => null));
            }
            
            $this->session->set_flashdata('success', 'Subject updated successfully.');
            redirect('academic/program_subjects/' . $program_id);
        }

        $data['title']   = 'Edit Subject';
        $data['program'] = $this->Academic_model->get_program($program_id);
        $data['subject'] = $subject;
        $data['teachers'] = $this->Academic_model->get_teachers_by_school($this->school_id);
        $data['assigned_teachers'] = $this->Academic_model->get_subject_teachers($subject_id);
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
        $data['grade_levels'] = $this->Academic_model->get_grade_levels(null, $this->school_id);
        $data['programs'] = $this->Academic_model->get_programs($this->school_id);
        $data['teachers'] = $this->Academic_model->get_teachers_by_school($this->school_id);
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
                'program_id'     => $this->input->post('program_id') ?: NULL,
                'year_level'     => $this->input->post('year_level') ?: NULL,
                'adviser_id'     => $this->input->post('adviser_id') ?: NULL,
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
        $data['teachers'] = $this->Academic_model->get_teachers_by_school($this->school_id);
        $this->render('academic/section_form', $data);
    }

    public function create_section_for_grade($grade_level_id)
    {
        $sy = $this->Academic_model->get_active_school_year($this->school_id);
        // Always fetch from programs table
        $grade_level = $this->db->where('id', $grade_level_id)->get('programs')->row();

        if (!$grade_level) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $adviser_id = $this->input->post('adviser_id') ?: NULL;
            
            // Check if adviser is already assigned to another section
            if ($adviser_id) {
                $existing = $this->db->where('adviser_id', $adviser_id)
                    ->where('school_id', $this->school_id)
                    ->get('sections')
                    ->row();
                if ($existing) {
                    $this->session->set_flashdata('error', 'This adviser is already assigned to another section.');
                    redirect('academic/create_section_for_grade/' . $grade_level_id);
                }
            }
            
            $d = array(
                'school_year_id' => $sy->id,
                'school_id'      => $this->school_id,
                'name'           => $this->input->post('name', TRUE),
                'program_id'     => $grade_level_id,
                'year_level'     => isset($grade_level->year_level) ? $grade_level->year_level : NULL,
                'adviser_id'     => $adviser_id,
            );
            $this->Academic_model->create_section($d);
            $gl_name = isset($grade_level->name) ? $grade_level->name : (isset($grade_level->year_level) ? $grade_level->year_level : 'Grade Level');
            $this->session->set_flashdata('success', 'Section created for ' . $gl_name . '.');
            redirect('academic/sections');
        }

        $gl_name = isset($grade_level->name) ? $grade_level->name : (isset($grade_level->year_level) ? $grade_level->year_level : 'Grade Level');
        $data['title'] = 'Add Section for ' . $gl_name;
        $data['grade_level'] = $grade_level;
        $data['teachers'] = $this->Academic_model->get_teachers_by_school($this->school_id);
        $this->render('academic/section_simple_form', $data);
    }

    public function edit_section($id)
    {
        $data['section'] = $this->Academic_model->get_section($id);
        if (!$data['section']) show_404();

        // Get grade level from programs table
        $grade_level = null;
        if (!empty($data['section']->program_id)) {
            $grade_level = $this->db->where('id', $data['section']->program_id)->get('programs')->row();
        }

        if ($this->input->method() === 'post') {
            $adviser_id = $this->input->post('adviser_id') ?: NULL;
            
            // Check if adviser is already assigned to another section (excluding current section)
            if ($adviser_id) {
                $existing = $this->db->where('adviser_id', $adviser_id)
                    ->where('school_id', $this->school_id)
                    ->where('id !=', $id)
                    ->get('sections')
                    ->row();
                if ($existing) {
                    $this->session->set_flashdata('error', 'This adviser is already assigned to another section.');
                    redirect('academic/edit_section/' . $id);
                }
            }
            
            $d = array(
                'name'       => $this->input->post('name', TRUE),
                'program_id' => $this->input->post('program_id') ?: NULL,
                'year_level' => $this->input->post('year_level') ?: NULL,
                'adviser_id' => $adviser_id,
            );
            $this->Academic_model->update_section($id, $d);
            $this->session->set_flashdata('success', 'Section updated.');
            redirect('academic/sections');
        }
        $data['title'] = 'Edit Section';
        $data['grade_level'] = $grade_level;
        $data['teachers'] = $this->Academic_model->get_teachers_by_school($this->school_id);
        $this->render('academic/section_simple_edit_form', $data);
    }

    public function delete_section($id)
    {
        $section = $this->Academic_model->get_section($id);
        if (!$section) {
            show_404();
        }

        if ($this->school_id && (int) $section->school_id !== (int) $this->school_id) {
            show_error('You do not have permission to delete this section.', 403);
        }

        $dependency_counts = $this->Academic_model->get_section_dependency_counts($id);
        if ($dependency_counts->enrollment_count > 0 || $dependency_counts->class_program_count > 0) {
            $messages = array();

            if ($dependency_counts->enrollment_count > 0) {
                $messages[] = $dependency_counts->enrollment_count . ' enrollment' . ($dependency_counts->enrollment_count === 1 ? '' : 's');
            }

            if ($dependency_counts->class_program_count > 0) {
                $messages[] = $dependency_counts->class_program_count . ' subject assignment' . ($dependency_counts->class_program_count === 1 ? '' : 's');
            }

            $this->session->set_flashdata(
                'error',
                'Section cannot be deleted because it still has ' . implode(' and ', $messages) . '.'
            );
            redirect('academic/sections');
        }

        if ($this->Academic_model->delete_section($id)) {
            $this->session->set_flashdata('success', 'Section deleted.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete section.');
        }

        redirect('academic/sections');
    }

    public function section_students($section_id)
    {
        $section = $this->Academic_model->get_section($section_id);
        if (!$section) show_404();

        $subject_id = (int) $this->input->get('subject_id', TRUE);
        if (!$this->can_access_section_students($section, $subject_id)) {
            show_error('You do not have permission to access this page.', 403);
        }

        $students = $this->Academic_model->get_section_students($section_id, $subject_id);
        
        $data['title'] = 'Section Students - ' . htmlspecialchars($section->name);
        $data['section'] = $section;
        $data['students'] = $students;
        $data['subject_id'] = $subject_id;
        $this->render('academic/section_students', $data);
    }

    public function student_subject_records($section_id, $student_user_id)
    {
        $section = $this->Academic_model->get_section($section_id);
        if (!$section) show_404();

        $subject_id = (int) $this->input->get('subject_id', TRUE);
        if ($subject_id <= 0) show_404();

        if (!$this->can_access_section_students($section, $subject_id)) {
            show_error('You do not have permission to access this page.', 403);
        }

        $student = $this->Academic_model->get_section_student($section_id, (int) $student_user_id);
        if (!$student) show_404();

        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$subject) show_404();

        $lesson_records = $this->Academic_model->get_student_subject_lesson_records($student->student_db_id, $subject_id);
        $assessment_records = $this->Academic_model->get_student_subject_assessment_records((int) $student_user_id, $subject_id);

        $completed_lesson_count = 0;
        foreach ($lesson_records as $record) {
            if (!empty($record->completed_at)) {
                $completed_lesson_count++;
            }
        }

        $completed_assessment_count = 0;
        $attempt_count = 0;
        foreach ($assessment_records as $assessment) {
            if (!empty($assessment->attempts)) {
                $attempt_count += count($assessment->attempts);
                foreach ($assessment->attempts as $attempt) {
                    if (in_array($attempt->status, array('submitted', 'graded'), true)) {
                        $completed_assessment_count++;
                        break;
                    }
                }
            }
        }

        $total_items = count($lesson_records) + count($assessment_records);
        $completed_items = $completed_lesson_count + $completed_assessment_count;
        $progress_percent = $total_items > 0 ? round(($completed_items / $total_items) * 100) : 0;

        $data['title'] = 'Student Records - ' . htmlspecialchars($student->name);
        $data['section'] = $section;
        $data['subject'] = $subject;
        $data['student'] = $student;
        $data['subject_id'] = $subject_id;
        $data['lesson_records'] = $lesson_records;
        $data['assessment_records'] = $assessment_records;
        $data['completed_lesson_count'] = $completed_lesson_count;
        $data['completed_assessment_count'] = $completed_assessment_count;
        $data['attempt_count'] = $attempt_count;
        $data['completed_items'] = $completed_items;
        $data['total_items'] = $total_items;
        $data['progress_percent'] = $progress_percent;
        $this->render('academic/student_subject_records', $data);
    }

    public function migrate_adviser_to_user()
    {
        // Migration: Change sections.adviser_id to reference users.id instead of teachers.id
        // First, try to drop the existing foreign key (ignore error if it doesn't exist)
        try {
            $this->db->query("ALTER TABLE `sections` DROP FOREIGN KEY `fk_sec_adviser`");
        } catch (Exception $e) {
            // Ignore if constraint doesn't exist
        }
        
        // Add new foreign key to users table
        $this->db->query("ALTER TABLE `sections` ADD CONSTRAINT `fk_sec_adviser_user` FOREIGN KEY (`adviser_id`) REFERENCES `users`(`id`) ON DELETE SET NULL");
        echo "Migration completed: sections.adviser_id now references users.id";
    }
}
