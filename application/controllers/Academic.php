<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Academic extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $method = $this->router->fetch_method();

        if (in_array($method, array('section_students', 'student_subject_records'))) {
            $this->require_login();
        } else {
            $this->require_role(array('super_admin', 'school_admin', 'course_creator'));
        }

        if (!$this->is_global_super_admin_academic_setup_method($method)) {
            $this->require_school();
        }

        $this->load->model(array('Academic_model', 'User_model'));
    }

    private function is_global_super_admin_academic_setup_method($method)
    {
        return $this->is_super_admin() && in_array($method, array(
            'school_years',
            'create_school_year',
            'activate_school_year',
            'programs',
            'teacher_assignment_report',
            'create_program',
            'edit_program',
            'delete_program',
            'program_subjects',
            'assign_subject_teacher',
            'remove_subject_from_program',
            'create_program_subject',
            'edit_program_subject',
        ));
    }

    private function require_super_admin_academic_setup()
    {
        if ($this->is_super_admin()) {
            return;
        }

        show_error('Only Super Admin accounts can access this page.', 403);
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
        $this->require_super_admin_academic_setup();
        $data['title'] = 'School Years';
        $data['school_years'] = $this->Academic_model->get_global_school_years();
        $this->render('academic/school_years', $data);
    }

    public function create_school_year()
    {
        $this->require_super_admin_academic_setup();
        if ($this->input->method() === 'post') {
            $d = array(
                'year_start' => $this->input->post('year_start'),
                'year_end'   => $this->input->post('year_end'),
                'is_active'  => $this->input->post('is_active') ? 1 : 0,
            );
            $sy_id = $this->Academic_model->create_school_year_for_all_schools($d);
            $this->session->set_flashdata('success', 'School year created for all schools.');
            redirect('academic/school_years');
        }
        $data['title'] = 'Add School Year';
        $this->render('academic/school_year_form', $data);
    }

    public function activate_school_year($id)
    {
        $this->require_super_admin_academic_setup();
        $school_year = $this->Academic_model->get_school_year($id);
        if (!$school_year) {
            show_404();
        }

        $this->Academic_model->activate_school_year_globally($id);

        if ($this->school_id) {
            $school_year = $this->Academic_model->get_active_school_year($this->school_id);
            if ($school_year) {
                $this->session->set_userdata(array(
                    'school_year_id' => (int) $school_year->id,
                    'school_year_name' => $school_year->year_start . '-' . $school_year->year_end,
                ));
            }
        }

        $this->session->set_flashdata('success', 'School year activated for all schools.');
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
        $this->require_super_admin_academic_setup();
        $data['title'] = 'Programs';
        $data['programs'] = $this->Academic_model->get_global_programs();
        $this->render('academic/programs', $data);
    }

    public function create_program()
    {
        $this->require_super_admin_academic_setup();
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
                'school_id'         => 0,
                'year_level'        => $year_level,
            );

            $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();
            if ($check_academic > 0) {
                $this->Academic_model->create_academic_program($d);
            } else {
                $this->Academic_model->create_legacy_program_for_all_schools($d);
            }

            $this->session->set_flashdata('success', ($type === 'grade_level') ? 'Grade level created for all schools.' : 'Program created for all schools.');
            redirect('academic/programs');
        }
        $data['title'] = 'Add Program';
        $data['program'] = null;
        $this->render('academic/program_form', $data);
    }

    public function edit_program($id)
    {
        $this->require_super_admin_academic_setup();
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
                        'school_id' => 0,
                    );
                    $this->db->where('id', $id)->update('academic_programs', $d);
                } else {
                    // Update programs table - check which columns exist
                    $updated = $this->Academic_model->update_legacy_program_for_all_schools($id, array(
                        'year_level' => $year_level,
                        'description' => $description,
                    ));

                    if (!$updated) {
                        $this->session->set_flashdata('error', 'Unable to update grade level because the target year level already exists.');
                        redirect('academic/edit_program/' . $id);
                    }
                }
            } else {
                $updated = $this->Academic_model->update_legacy_program_for_all_schools($id, array(
                    'year_level' => $year_level,
                    'description' => $description,
                ));

                if (!$updated) {
                    $this->session->set_flashdata('error', 'Unable to update grade level because the target year level already exists.');
                    redirect('academic/edit_program/' . $id);
                }
            }

            $this->session->set_flashdata('success', 'Program updated for all schools.');
            redirect('academic/programs');
        }

        $data['title'] = 'Edit Program';
        $this->render('academic/program_form', $data);
    }

    public function delete_program($id)
    {
        $this->require_super_admin_academic_setup();
        $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();

        if ($check_academic > 0) {
            $this->Academic_model->delete_program($id);
            $this->session->set_flashdata('success', 'Program deleted.');
            redirect('academic/programs');
        }

        $deleted = $this->Academic_model->delete_legacy_program_for_all_schools($id);
        if (!$deleted) {
            $this->session->set_flashdata('error', 'Cannot delete this grade level because it is still used by sections or subjects.');
            redirect('academic/programs');
        }

        $this->session->set_flashdata('success', 'Program deleted for all schools.');
        redirect('academic/programs');
    }

    public function program_subjects($program_id)
    {
        $this->require_super_admin_academic_setup();
        $program = $this->Academic_model->get_program($program_id);
        if (!$program) show_404();
        $program_school_id = $this->school_id ? (int) $this->school_id : 0;

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
                    'school_id'       => $program_school_id,
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
        $this->require_super_admin_academic_setup();
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
        $this->require_super_admin_academic_setup();
        $subject = $this->Academic_model->get_subject($subject_id);
        if ($subject) {
            $this->Academic_model->delete_subject($subject_id, $this->school_id, true);
            $this->session->set_flashdata('success', 'Subject deleted successfully.');
        }
        redirect('academic/program_subjects/' . $program_id);
    }

    // ---- Learning Competencies CRUD ----
    public function learning_competencies($program_id, $subject_id)
    {
        $this->require_login();
        $program = $this->Academic_model->get_program($program_id);
        $subject = $this->Academic_model->get_subject($subject_id);
        if (!$program || !$subject) show_404();

        $competencies = $this->Academic_model->get_learning_competencies($subject_id);

        $data['title'] = 'Learning Competencies - ' . htmlspecialchars($subject->code);
        $data['program'] = $program;
        $data['subject'] = $subject;
        $data['competencies'] = $competencies;
        $this->render('academic/learning_competencies', $data);
    }

    public function create_learning_competency($program_id, $subject_id)
    {
        $this->require_login();
        if ($this->input->method() !== 'post') {
            redirect('academic/learning_competencies/' . $program_id . '/' . $subject_id);
        }

        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('academic/learning_competencies/' . $program_id . '/' . $subject_id);
            return;
        }

        $data = array(
            'subject_id' => $subject_id,
            'school_id' => $this->school_id,
            'code' => $this->input->post('code', TRUE),
            'description' => $this->input->post('description', TRUE),
            'quarter' => $this->input->post('quarter') ? (int)$this->input->post('quarter') : NULL,
            'sort_order' => $this->input->post('sort_order') ? (int)$this->input->post('sort_order') : 0,
        );

        $this->Academic_model->create_learning_competency($data);
        $this->session->set_flashdata('success', 'Learning competency added successfully.');
        redirect('academic/learning_competencies/' . $program_id . '/' . $subject_id);
    }

    public function update_learning_competency($program_id, $subject_id, $id)
    {
        $this->require_login();
        if ($this->input->method() !== 'post') {
            redirect('academic/learning_competencies/' . $program_id . '/' . $subject_id);
        }

        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('academic/learning_competencies/' . $program_id . '/' . $subject_id);
            return;
        }

        $data = array(
            'code' => $this->input->post('code', TRUE),
            'description' => $this->input->post('description', TRUE),
            'quarter' => $this->input->post('quarter') ? (int)$this->input->post('quarter') : NULL,
            'sort_order' => $this->input->post('sort_order') ? (int)$this->input->post('sort_order') : 0,
        );

        $this->Academic_model->update_learning_competency($id, $data);
        $this->session->set_flashdata('success', 'Learning competency updated successfully.');
        redirect('academic/learning_competencies/' . $program_id . '/' . $subject_id);
    }

    public function delete_learning_competency($program_id, $subject_id, $id)
    {
        $this->require_login();
        $this->Academic_model->delete_learning_competency($id);
        $this->session->set_flashdata('success', 'Learning competency deleted successfully.');
        redirect('academic/learning_competencies/' . $program_id . '/' . $subject_id);
    }

    public function create_program_subject($program_id)
    {
        $this->require_super_admin_academic_setup();
        $program_school_id = $this->school_id ? (int) $this->school_id : 0;

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
                'school_id'   => $program_school_id,
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
        $this->require_super_admin_academic_setup();
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

    public function teacher_assignment_report()
    {
        $this->require_super_admin_academic_setup();

        $rows = $this->Academic_model->get_teacher_assignment_report_rows();
        $report_groups = array();
        $total_subjects = 0;
        $total_assignments = 0;

        foreach ($rows as $row) {
            $year_level_value = trim((string) $row->year_level);
            if ($year_level_value !== '' && is_numeric($year_level_value)) {
                $group_key = (string) ((int) $year_level_value);
                $group_label = 'Grade ' . str_pad((int) $year_level_value, 2, '0', STR_PAD_LEFT);
            } else {
                $group_key = 'unassigned';
                $group_label = 'Unassigned Grade Level';
            }

            if (!isset($report_groups[$group_key])) {
                $report_groups[$group_key] = array(
                    'key' => $group_key,
                    'label' => $group_label,
                    'subjects' => array(),
                );
            }

            $subject_id = (int) $row->subject_id;
            if (!isset($report_groups[$group_key]['subjects'][$subject_id])) {
                $report_groups[$group_key]['subjects'][$subject_id] = array(
                    'id' => $subject_id,
                    'code' => (string) $row->code,
                    'description' => (string) $row->description,
                    'teachers' => array(),
                );
                $total_subjects++;
            }

            if (!empty($row->teacher_id)) {
                $teacher_name = trim((string) $row->teacher_last_name . ', ' . (string) $row->teacher_first_name, ', ');
                if ($teacher_name === '') {
                    $teacher_name = 'Teacher #' . (int) $row->teacher_id;
                }

                if (!empty($row->teacher_school_name)) {
                    $teacher_name .= ' (' . $row->teacher_school_name . ')';
                }

                if (!in_array($teacher_name, $report_groups[$group_key]['subjects'][$subject_id]['teachers'], true)) {
                    $report_groups[$group_key]['subjects'][$subject_id]['teachers'][] = $teacher_name;
                    $total_assignments++;
                }
            }
        }

        $numeric_group_keys = array();
        $special_group_keys = array();
        foreach (array_keys($report_groups) as $group_key) {
            if (ctype_digit((string) $group_key)) {
                $numeric_group_keys[] = (int) $group_key;
            } else {
                $special_group_keys[] = $group_key;
            }
        }

        sort($numeric_group_keys, SORT_NUMERIC);

        $ordered_groups = array();
        foreach ($numeric_group_keys as $numeric_group_key) {
            $group = $report_groups[(string) $numeric_group_key];
            $group['subjects'] = array_values($group['subjects']);
            $group['subject_total'] = count($group['subjects']);
            $ordered_groups[] = $group;
        }

        foreach ($special_group_keys as $special_group_key) {
            $group = $report_groups[$special_group_key];
            $group['subjects'] = array_values($group['subjects']);
            $group['subject_total'] = count($group['subjects']);
            $ordered_groups[] = $group;
        }

        $data['title'] = 'Teacher Assignment Report';
        $data['report_groups'] = $ordered_groups;
        $data['report_group_total'] = count($ordered_groups);
        $data['report_subject_total'] = $total_subjects;
        $data['report_assignment_total'] = $total_assignments;
        $data['generated_at'] = date('F j, Y g:i A');
        $this->render('academic/teacher_assignment_report', $data);
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
        $global_subject_rows = $this->Academic_model->get_subjects();
        $subjects_by_year_level = array();
        $preferred_subject_school_id = (int) $this->school_id;

        foreach ($global_subject_rows as $subject_row) {
            $subject_year_level = '';

            if (isset($subject_row->year_level) && trim((string) $subject_row->year_level) !== '') {
                $subject_year_level = trim((string) $subject_row->year_level);
            } elseif (isset($subject_row->program_year_level) && trim((string) $subject_row->program_year_level) !== '') {
                $subject_year_level = trim((string) $subject_row->program_year_level);
            }

            if ($subject_year_level === '') {
                continue;
            }

            if (!isset($subjects_by_year_level[$subject_year_level])) {
                $subjects_by_year_level[$subject_year_level] = array();
            }

            $subject_code = isset($subject_row->code) ? trim((string) $subject_row->code) : '';
            $subject_description = isset($subject_row->description) ? trim((string) $subject_row->description) : '';
            $subject_key = strtolower($subject_code . '|' . $subject_description);
            $subject_school_id = isset($subject_row->school_id) ? (int) $subject_row->school_id : 0;
            $subject_rank = 2;

            if ($preferred_subject_school_id > 0 && $subject_school_id === $preferred_subject_school_id) {
                $subject_rank = 0;
            } elseif ($subject_school_id === 0) {
                $subject_rank = 1;
            }

            if (isset($subjects_by_year_level[$subject_year_level][$subject_key])) {
                $existing_rank = isset($subjects_by_year_level[$subject_year_level][$subject_key]->_rank)
                    ? (int) $subjects_by_year_level[$subject_year_level][$subject_key]->_rank
                    : 99;

                if ($subject_rank >= $existing_rank) {
                    continue;
                }
            }

            $subjects_by_year_level[$subject_year_level][$subject_key] = (object) array(
                'id' => isset($subject_row->id) ? (int) $subject_row->id : 0,
                'program_id' => isset($subject_row->program_id) ? (int) $subject_row->program_id : 0,
                'school_id' => $subject_school_id,
                'code' => $subject_code,
                'description' => $subject_description,
                '_rank' => $subject_rank,
            );
        }

        foreach ($subjects_by_year_level as $year_level => $subject_items) {
            uasort($subject_items, function ($left, $right) {
                $left_code = strtolower((string) $left->code);
                $right_code = strtolower((string) $right->code);

                if ($left_code === $right_code) {
                    return strcmp(strtolower((string) $left->description), strtolower((string) $right->description));
                }

                return strcmp($left_code, $right_code);
            });

            foreach ($subject_items as $subject_item) {
                if (isset($subject_item->_rank)) {
                    unset($subject_item->_rank);
                }
            }

            $subjects_by_year_level[$year_level] = array_values($subject_items);
        }

        $data['title'] = 'Sections';
        $data['school_year'] = $sy;
        $data['sections'] = $this->Academic_model->get_sections($filters);
        $data['grade_levels'] = $this->Academic_model->get_section_grade_levels();
        $data['programs'] = $this->Academic_model->get_programs($this->school_id);
        $data['teachers'] = $this->Academic_model->get_teachers_by_school($this->school_id);
        $data['subjects_by_year_level'] = $subjects_by_year_level;
        $this->render('academic/sections', $data);
    }

    public function create_section()
    {
        if ($this->role_slug === 'school_admin') {
            $this->session->set_flashdata('error', 'Please add sections from the grade level list.');
            redirect('academic/sections');
        }

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

        $local_grade_level = $this->db->where('school_id', $this->school_id)
            ->where('year_level', $grade_level->year_level)
            ->order_by('id', 'ASC')
            ->get('programs')
            ->row();

        if (!$local_grade_level) {
            $this->Academic_model->sync_programs_to_school($this->school_id);
            $local_grade_level = $this->db->where('school_id', $this->school_id)
                ->where('year_level', $grade_level->year_level)
                ->order_by('id', 'ASC')
                ->get('programs')
                ->row();
        }

        if (!$local_grade_level) {
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
                'program_id'     => $local_grade_level->id,
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
            $post_data = $this->input->post(NULL, TRUE);
            $adviser_id = $this->input->post('adviser_id') ?: NULL;
            $subject_ids = $this->input->post('subject_ids') ?: array();
            $staff_ids = $this->input->post('staff_ids') ?: array();

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
                'adviser_id' => $adviser_id,
            );

            if (is_array($post_data) && array_key_exists('program_id', $post_data)) {
                $d['program_id'] = $this->input->post('program_id') ?: NULL;
            }

            if (is_array($post_data) && array_key_exists('year_level', $post_data)) {
                $d['year_level'] = $this->input->post('year_level') ?: NULL;
            }

            $this->Academic_model->update_section($id, $d);

            // Build assignments array with subject_id and staff_id pairs
            $assignments = array();
            if (!empty($subject_ids) && !empty($staff_ids)) {
                foreach ($subject_ids as $index => $subject_id) {
                    if (isset($staff_ids[$index]) && !empty($staff_ids[$index])) {
                        $assignments[] = array(
                            'subject_id' => $subject_id,
                            'staff_id' => $staff_ids[$index]
                        );
                    }
                }
            }

            // Update section teachers with subject assignments
            $this->Academic_model->update_section_teachers($id, $assignments);

            $this->session->set_flashdata('success', 'Section updated.');
            redirect('academic/sections');
        }
        $data['title'] = 'Edit Section';
        $data['grade_level'] = $grade_level;
        $data['teachers'] = $this->Academic_model->get_teachers_by_school($this->school_id);
        $data['section_teachers'] = $this->Academic_model->get_section_teachers($id);
        $data['subjects'] = $this->Academic_model->get_subjects_by_school($this->school_id);
        $this->render('academic/section_simple_edit_form', $data);
    }

    public function assign_section_teacher()
    {
        $this->require_login();
        $section_id = $this->input->post('section_id');
        $subject_id = $this->input->post('subject_id');
        $staff_id = $this->input->post('staff_id');

        if (!$section_id || !$subject_id || !$staff_id) {
            $this->session->set_flashdata('error', 'Missing required parameters.');
            redirect($this->input->server('HTTP_REFERER'));
        }

        // Check if assignment already exists
        $this->Academic_model->ensure_section_teachers_table();
        $existing = $this->db->where('section_id', $section_id)
            ->where('subject_id', $subject_id)
            ->where('staff_id', $staff_id)
            ->get('section_teachers')
            ->row();

        if ($existing) {
            $this->session->set_flashdata('error', 'Teacher is already assigned to this subject in this section.');
            redirect($this->input->server('HTTP_REFERER'));
        }

        // Insert new assignment
        $this->db->insert('section_teachers', array(
            'section_id' => $section_id,
            'subject_id' => $subject_id,
            'staff_id' => $staff_id
        ));

        $this->session->set_flashdata('success', 'Teacher assigned successfully.');
        redirect($this->input->server('HTTP_REFERER'));
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
        $back = (string) $this->input->get('back', TRUE);
        if (!$this->can_access_section_students($section, $subject_id)) {
            show_error('You do not have permission to access this page.', 403);
        }

        $students = $this->Academic_model->get_section_students($section_id, $subject_id);
        
        $data['title'] = 'Section Students - ' . htmlspecialchars($section->name);
        $data['section'] = $section;
        $data['students'] = $students;
        $data['subject_id'] = $subject_id;
        $data['back'] = $back;
        $this->render('academic/section_students', $data);
    }

    public function student_subject_records($section_id, $student_user_id)
    {
        $section = $this->Academic_model->get_section($section_id);
        if (!$section) show_404();

        $subject_id = (int) $this->input->get('subject_id', TRUE);
        $back = (string) $this->input->get('back', TRUE);
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
        $data['back'] = $back;
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
