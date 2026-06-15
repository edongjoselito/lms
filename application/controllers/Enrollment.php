<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Enrollment extends MY_Controller
{
    protected $accessible_section_ids = null;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Enrollment_model', 'Studentprofile_model', 'User_model'));
        $this->require_school();

        $method = $this->router->fetch_method();
        $teacher_allowed_methods = array('index', 'search_studentprofiles', 'create', 'edit', 'grade_level_sections');

        if ($this->role_slug === 'teacher') {
            if (!in_array($method, $teacher_allowed_methods) || !$this->has_teacher_enrollment_access()) {
                show_error('Only advisers can access enrollment.', 403);
            }
            return;
        }

        $this->require_role(array('super_admin', 'school_admin'));
    }

    private function has_teacher_enrollment_access()
    {
        return count($this->get_accessible_section_ids()) > 0;
    }

    private function get_accessible_section_ids()
    {
        if ($this->accessible_section_ids !== null) {
            return $this->accessible_section_ids;
        }

        $this->accessible_section_ids = array();

        if ($this->role_slug !== 'teacher' || !$this->current_user || !$this->school_id) {
            return $this->accessible_section_ids;
        }

        $context = $this->get_section_adviser_context();
        $staff_id_number = null;
        if (!$context['sections_adviser_is_user_id'] && $context['has_staff_table']) {
            $staff = $this->db->select('IDNumber')
                ->where('user_id', (int) $this->current_user->id)
                ->get('staff')
                ->row();

            if (!$staff || empty($staff->IDNumber)) {
                return $this->accessible_section_ids;
            }

            $staff_id_number = $staff->IDNumber;
        }

        $this->db->select('sections.id')->from('sections');
        $this->db->where('sections.school_id', (int) $this->school_id);

        if ($this->current_school_year && $this->db->field_exists('school_year_id', 'sections')) {
            $this->db->where('sections.school_year_id', (int) $this->current_school_year->id);
        }

        if ($context['sections_adviser_is_user_id'] || !$context['has_staff_table']) {
            $this->db->where('sections.adviser_id', (int) $this->current_user->id);
        } else {
            $this->db->where('sections.adviser_id', $staff_id_number);
        }

        $rows = $this->db->get()->result();
        foreach ($rows as $row) {
            $this->accessible_section_ids[] = (int) $row->id;
        }

        return $this->accessible_section_ids;
    }

    private function filter_enrollments_by_sections($enrollments, $section_ids)
    {
        if (empty($section_ids)) {
            return array();
        }

        $allowed = array_fill_keys(array_map('intval', $section_ids), true);
        $filtered = array();

        foreach ($enrollments as $enrollment) {
            $section_id = isset($enrollment->section_id) ? (int) $enrollment->section_id : 0;
            if ($section_id > 0 && isset($allowed[$section_id])) {
                $filtered[] = $enrollment;
            }
        }

        return $filtered;
    }

    private function filter_sections_by_ids($sections, $section_ids)
    {
        if (empty($section_ids)) {
            return array();
        }

        $allowed = array_fill_keys(array_map('intval', $section_ids), true);
        $filtered = array();

        foreach ($sections as $section) {
            $section_id = isset($section->id) ? (int) $section->id : 0;
            if ($section_id > 0 && isset($allowed[$section_id])) {
                $filtered[] = $section;
            }
        }

        return $filtered;
    }

    private function filter_grade_levels_by_ids($grade_levels, $grade_level_ids)
    {
        if (empty($grade_level_ids)) {
            return $grade_levels;
        }

        $allowed = array_fill_keys(array_map('strval', $grade_level_ids), true);
        $filtered = array();

        foreach ($grade_levels as $grade_level) {
            $grade_level_id = isset($grade_level->id) ? (string) $grade_level->id : '';
            if ($grade_level_id !== '' && isset($allowed[$grade_level_id])) {
                $filtered[] = $grade_level;
            }
        }

        return $filtered;
    }

    private function build_teacher_enrollment_defaults($sections)
    {
        if (empty($sections)) {
            return null;
        }

        $section = reset($sections);
        if (!$section) {
            return null;
        }

        return array(
            'grade_level_id' => !empty($section->program_id) ? (string) $section->program_id : '',
            'section_id' => isset($section->id) ? (int) $section->id : 0,
            'section_name' => isset($section->name) ? (string) $section->name : '',
            'adviser_id' => isset($section->adviser_user_id) ? (int) $section->adviser_user_id : 0,
            'adviser_name' => isset($section->adviser_name) ? (string) $section->adviser_name : '',
        );
    }

    private function build_stats_from_enrollments($enrollments)
    {
        $stats = array(
            'total_enrolled' => 0,
            'total_sections' => 0,
            'total_grade_levels' => 0,
        );

        $section_map = array();
        $grade_level_map = array();

        foreach ($enrollments as $enrollment) {
            if (!isset($enrollment->status) || $enrollment->status !== 'enrolled') {
                continue;
            }

            $stats['total_enrolled']++;

            $section_id = isset($enrollment->section_id) ? (int) $enrollment->section_id : 0;
            if ($section_id > 0) {
                $section_map[$section_id] = true;
            }

            if (isset($enrollment->year_level) && $enrollment->year_level !== null && $enrollment->year_level !== '') {
                $grade_level_map['year_' . $enrollment->year_level] = true;
            } elseif (isset($enrollment->grade_level_id) && $enrollment->grade_level_id) {
                $grade_level_map['grade_' . $enrollment->grade_level_id] = true;
            }
        }

        $stats['total_sections'] = count($section_map);
        $stats['total_grade_levels'] = count($grade_level_map);

        return $stats;
    }

    private function build_grade_level_counts_from_enrollments($enrollments)
    {
        $counts = array();

        foreach ($enrollments as $enrollment) {
            if (!isset($enrollment->status) || $enrollment->status !== 'enrolled') {
                continue;
            }

            $use_year_level = isset($enrollment->year_level) && $enrollment->year_level !== null && $enrollment->year_level !== '';
            $key = $use_year_level
                ? 'year_' . $enrollment->year_level
                : 'grade_' . (isset($enrollment->grade_level_id) ? $enrollment->grade_level_id : 0);

            if (!isset($counts[$key])) {
                $counts[$key] = (object) array(
                    'count' => 0,
                    'year_level' => $use_year_level ? $enrollment->year_level : null,
                    'grade_level_id' => $use_year_level ? null : (isset($enrollment->grade_level_id) ? $enrollment->grade_level_id : null),
                );
            }

            $counts[$key]->count++;
        }

        usort($counts, function ($a, $b) {
            $a_value = isset($a->year_level) && $a->year_level !== null && $a->year_level !== ''
                ? (int) $a->year_level
                : (int) $a->grade_level_id;
            $b_value = isset($b->year_level) && $b->year_level !== null && $b->year_level !== ''
                ? (int) $b->year_level
                : (int) $b->grade_level_id;

            return $a_value <=> $b_value;
        });

        return array_values($counts);
    }

    private function get_school_enrollment_or_404($id)
    {
        $enrollment = $this->db->where('id', (int) $id)
            ->where('school_id', (int) $this->school_id)
            ->get('enrollments')
            ->row();

        if (!$enrollment) {
            show_404();
        }

        if ($this->role_slug === 'teacher') {
            $section_ids = $this->get_accessible_section_ids();
            if (empty($section_ids) || !in_array((int) $enrollment->section_id, $section_ids, true)) {
                show_error('You do not have permission to access this enrollment.', 403);
            }
        }

        return $enrollment;
    }

    public function index()
    {
        $data['title'] = 'Enrollment';
        $data['enrollments'] = $this->Enrollment_model->get_all($this->school_id);
        $data['stats'] = $this->Enrollment_model->get_stats($this->school_id);
        $data['grade_level_counts'] = $this->Enrollment_model->get_grade_level_counts($this->school_id);
        $form_data = $this->get_enrollment_form_data();
        if ($this->role_slug === 'teacher') {
            $section_ids = $this->get_accessible_section_ids();
            $data['enrollments'] = $this->filter_enrollments_by_sections($data['enrollments'], $section_ids);
            $data['stats'] = $this->build_stats_from_enrollments($data['enrollments']);
            $data['grade_level_counts'] = $this->build_grade_level_counts_from_enrollments($data['enrollments']);
            $form_data['sections'] = $this->filter_sections_by_ids($form_data['sections'], $section_ids);
            $allowed_grade_level_ids = array();
            foreach ($form_data['sections'] as $section) {
                if (!empty($section->program_id)) {
                    $allowed_grade_level_ids[] = (string) $section->program_id;
                }
            }
            $form_data['grade_levels'] = $this->filter_grade_levels_by_ids($form_data['grade_levels'], array_values(array_unique($allowed_grade_level_ids)));
            $data['teacher_enrollment_defaults'] = $this->build_teacher_enrollment_defaults($form_data['sections']);
        }
        $data['grade_levels'] = $form_data['grade_levels'];
        $data['sections'] = $form_data['sections'];
        $data['advisers'] = $form_data['advisers'];
        $this->render('enrollment/index', $data);
    }

    public function search_studentprofiles()
    {
        $this->require_school();

        $search = trim((string) $this->input->get('q', TRUE));
        $profiles = $this->Studentprofile_model->search_for_enrollment($this->school_id, $search, 20);
        $results = array();

        foreach ($profiles as $profile) {
            $results[] = array(
                'id' => (int) $profile->id,
                'text' => trim($profile->student_number . ' - ' . $profile->last_name . ', ' . $profile->first_name),
                'student_number' => (string) $profile->student_number,
                'name' => trim($profile->last_name . ', ' . $profile->first_name . (!empty($profile->middle_name) ? ' ' . strtoupper(substr($profile->middle_name, 0, 1)) . '.' : '')),
                'email' => (string) ($profile->profile_email ?: $profile->user_email),
                'birth_date' => (string) $profile->birth_date,
            );
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('results' => $results)));
    }

    public function create()
    {
        if ($this->input->method() !== 'post') {
            redirect('enrollment');
        }

        $profile_id = (int) $this->input->post('profile_id');
        $grade_level_id = trim((string) $this->input->post('grade_level_id', TRUE));
        $section_id = (int) $this->input->post('section_id', TRUE);
        $adviser_user_id = (int) $this->input->post('adviser_id', TRUE);

        if ($profile_id <= 0 || $grade_level_id === '' || $section_id <= 0) {
            $this->session->set_flashdata('error', 'Student, grade level, and section are required.');
            redirect('enrollment');
        }

        $profile = $this->Studentprofile_model->get_for_school($profile_id, $this->school_id);
        if (!$profile || empty($profile->user_id)) {
            $this->session->set_flashdata('error', 'Invalid student profile selected.');
            redirect('enrollment');
        }

        $existing_enrollment = $this->db->where('student_id', (int) $profile->user_id)
            ->where('school_id', (int) $this->school_id)
            ->where('status', 'enrolled')
            ->count_all_results('enrollments');

        if ($existing_enrollment > 0) {
            $this->session->set_flashdata('error', 'Selected student is already enrolled.');
            redirect('enrollment');
        }

        $context = $this->get_section_adviser_context();
        if ($this->role_slug === 'teacher') {
            $accessible_section_ids = $this->get_accessible_section_ids();
            if (count($accessible_section_ids) === 1) {
                $section_id = (int) $accessible_section_ids[0];
            }
        }
        if ($this->role_slug === 'teacher' && !in_array($section_id, $this->get_accessible_section_ids(), true)) {
            $this->session->set_flashdata('error', 'Selected section is not part of your advisory class.');
            redirect('enrollment');
        }

        $section = $this->db->where('id', $section_id)
            ->where('school_id', $this->school_id)
            ->get('sections')
            ->row();

        if (!$section) {
            $this->session->set_flashdata('error', 'Selected section is invalid.');
            redirect('enrollment');
        }

        if ($this->role_slug === 'teacher' && !empty($section->program_id)) {
            $grade_level_id = (string) $section->program_id;
        }

        $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();
        $program_id = null;
        $year_level = null;
        if ($check_academic > 0) {
            $program = $this->db->where('id', $grade_level_id)
                ->where('school_id', $this->school_id)
                ->get('academic_programs')
                ->row();
            if (!$program) {
                $this->session->set_flashdata('error', 'Selected grade level is invalid.');
                redirect('enrollment');
            }
            $program_id = (int) $program->id;
            $year_level = isset($program->year_level) ? $program->year_level : null;
        } else {
            $program = $this->db->where('id', $grade_level_id)
                ->where('school_id', $this->school_id)
                ->get('programs')
                ->row();
            if (!$program) {
                $this->session->set_flashdata('error', 'Selected grade level is invalid.');
                redirect('enrollment');
            }
            $program_id = (int) $program->id;
            $year_level = isset($program->year_level) ? $program->year_level : null;
        }

        if (!empty($section->program_id) && (string) $section->program_id !== $grade_level_id) {
            $this->session->set_flashdata('error', 'Selected section does not belong to the chosen grade level.');
            redirect('enrollment');
        }

        $school_year = $this->db->where('school_id', $this->school_id)
            ->where('is_active', 1)
            ->get('school_years')
            ->row();

        if (!$school_year) {
            $this->session->set_flashdata('error', 'No active school year found.');
            redirect('enrollment');
        }

        if ($this->role_slug === 'teacher') {
            $adviser_user_id = (int) $this->current_user->id;
        }

        if ($adviser_user_id > 0) {
            $teacher_role_id = $this->User_model->get_role_id_by_slug('teacher');
            $adviser = $this->db->where('id', $adviser_user_id)
                ->where('school_id', $this->school_id)
                ->where('role_id', $teacher_role_id)
                ->get('users')
                ->row();

            if (!$adviser) {
                $this->session->set_flashdata('error', 'Selected adviser is invalid.');
                redirect('enrollment');
            }
        }

        $enrollment_data = array(
            'student_id' => (int) $profile->user_id,
            'school_id' => (int) $this->school_id,
            'school_year_id' => (int) $school_year->id,
            'grade_level_id' => $grade_level_id,
            'program_id' => $program_id,
            'year_level' => $year_level,
            'section_id' => $section_id,
            'status' => 'enrolled',
            'enrollment_date' => date('Y-m-d')
        );

        $this->db->insert('enrollments', $enrollment_data);

        if ($adviser_user_id > 0) {
            if ($context['sections_adviser_is_user_id'] || !$context['has_staff_table']) {
                $this->db->where('id', $section_id)->update('sections', array('adviser_id' => $adviser_user_id));
            } else {
                $staff = $this->db->select('IDNumber')->where('user_id', $adviser_user_id)->get('staff')->row();
                if ($staff) {
                    $this->db->where('id', $section_id)->update('sections', array('adviser_id' => $staff->IDNumber));
                }
            }
        }

        $this->session->set_flashdata('success', 'Student enrolled successfully.');
        redirect('enrollment');
    }

    public function edit($id)
    {
        $data['current_section'] = null;
        $has_staff_table = $this->db->query("SHOW TABLES LIKE 'staff'")->num_rows() > 0;
        $adviser_column = $this->db->query("SHOW COLUMNS FROM sections LIKE 'adviser_id'")->row();
        $sections_adviser_is_user_id = $adviser_column && stripos($adviser_column->Type, 'int') !== false;

        $data['enrollment'] = $this->get_school_enrollment_or_404($id);

        // Get student profile by user_id (since enrollment.student_id is the user_id)
        $data['profile'] = $this->db->where('user_id', $data['enrollment']->student_id)
            ->where('school_id', $this->school_id)
            ->get('studentprofile')
            ->row();
        if (!$data['profile']) show_404();

        // Get the year_level for the current enrollment's grade_level_id if not already in enrollment
        if (!isset($data['enrollment']->year_level) && $data['enrollment']->grade_level_id) {
            $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();
            if ($check_academic > 0) {
                $program = $this->db->select('year_level')->where('id', $data['enrollment']->grade_level_id)->get('academic_programs')->row();
                if ($program && isset($program->year_level)) {
                    $data['enrollment']->year_level = $program->year_level;
                }
            } else {
                $check_programs = $this->db->query("SHOW TABLES LIKE 'programs'")->num_rows();
                if ($check_programs > 0) {
                    $program = $this->db->select('year_level')->where('id', $data['enrollment']->grade_level_id)->get('programs')->row();
                    if ($program && isset($program->year_level)) {
                        $data['enrollment']->year_level = $program->year_level;
                    }
                }
            }
        }

        // Get grade levels/programs for current school
        $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'");
        if ($check_academic->num_rows() > 0) {
            $data['grade_levels'] = $this->db->where('school_id', $this->school_id)
                ->get('academic_programs')
                ->result();
        } else {
            $data['grade_levels'] = $this->db->where('school_id', $this->school_id)
                ->order_by('year_level', 'ASC')
                ->get('programs')
                ->result();
        }

        // Get sections for current school with adviser info
        $this->db->select('sections.*, CONCAT(u.last_name, ", ", u.first_name) as adviser_name, u.id as adviser_user_id', FALSE)
            ->from('sections');
        if ($sections_adviser_is_user_id || !$has_staff_table) {
            $this->db->join('users u', 'u.id = sections.adviser_id', 'left');
        } else {
            $this->db->join('staff t', 't.IDNumber = sections.adviser_id', 'left')
                ->join('users u', 'u.id = t.user_id', 'left');
        }
        $data['sections'] = $this->db->where('sections.school_id', $this->school_id)
            ->get()
            ->result();
        if ($this->role_slug === 'teacher') {
            $data['sections'] = $this->filter_sections_by_ids($data['sections'], $this->get_accessible_section_ids());
        }

        // Get current section's adviser if enrollment has a section
        if ($data['enrollment']->section_id) {
            $select_fields = 'sections.*, u.id as adviser_user_id';
            if ($has_staff_table) {
                $select_fields .= $sections_adviser_is_user_id ? ', st.IDNumber as adviser_staff_id' : ', t.IDNumber as adviser_staff_id';
            }

            $this->db->select($select_fields, FALSE)
                ->from('sections');
            if ($sections_adviser_is_user_id || !$has_staff_table) {
                $this->db->join('users u', 'u.id = sections.adviser_id', 'left');
                if ($has_staff_table) {
                    $this->db->join('staff st', 'st.user_id = u.id', 'left');
                }
            } else {
                $this->db->join('staff t', 't.IDNumber = sections.adviser_id', 'left')
                    ->join('users u', 'u.id = t.user_id', 'left');
            }
            $data['current_section'] = $this->db->where('sections.id', $data['enrollment']->section_id)
                ->where('sections.school_id', $this->school_id)
                ->get()
                ->row();
        }

        // Get teachers (advisers) for current school
        if ($has_staff_table) {
            // Get advisers from staff table joined with users
            $teacher_role_id = $this->User_model->get_role_id_by_slug('teacher');
            $data['advisers'] = $this->db->select('u.*, t.IDNumber as staff_id', FALSE)
                ->from('users u')
                ->join('staff t', 't.user_id = u.id', 'left')
                ->where('u.school_id', $this->school_id)
                ->where('u.role_id', $teacher_role_id)
                ->get()
                ->result();
        } else {
            // Fallback to users only
            $teacher_role_id = $this->User_model->get_role_id_by_slug('teacher');
            $data['advisers'] = $this->db->where('school_id', $this->school_id)
                ->where('role_id', $teacher_role_id)
                ->get('users')
                ->result();
        }

        if ($this->input->method() === 'post') {
            $grade_level_id = $this->input->post('grade_level_id', TRUE);
            $section_id = (int) $this->input->post('section_id', TRUE);
            $adviser_user_id = (int) $this->input->post('adviser_id', TRUE);

            if ($this->role_slug === 'teacher' && !in_array($section_id, $this->get_accessible_section_ids(), true)) {
                $this->session->set_flashdata('error', 'Selected section is not part of your advisory class.');
                redirect('enrollment/edit/' . $id);
            }

            if ($this->role_slug === 'teacher') {
                $adviser_user_id = (int) $this->current_user->id;
            }

            $program_id = null;
            $year_level = null;

            if ($grade_level_id) {
                $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();
                if ($check_academic > 0) {
                    $program = $this->db->select('id, year_level')
                        ->where('id', $grade_level_id)
                        ->get('academic_programs')
                        ->row();
                    if ($program) {
                        $program_id = (int) $program->id;
                        $year_level = isset($program->year_level) ? $program->year_level : null;
                    }
                } else {
                    $program = $this->db->select('id, year_level')
                        ->where('id', $grade_level_id)
                        ->get('programs')
                        ->row();
                    if ($program) {
                        $program_id = (int) $program->id;
                        $year_level = isset($program->year_level) ? $program->year_level : null;
                    } elseif (is_numeric($grade_level_id)) {
                        $year_level = (int) $grade_level_id;
                    }
                }
            }

            // Update enrollment record
            $enrollment_data = array(
                'grade_level_id' => $grade_level_id,
                'program_id' => $program_id,
                'year_level' => $year_level,
                'section_id' => $section_id
            );

            $this->db->where('id', $id)->update('enrollments', $enrollment_data);

            // Update section adviser if provided
            if ($adviser_user_id) {
                if ($sections_adviser_is_user_id || !$has_staff_table) {
                    $this->db->where('id', $section_id)->update('sections', array('adviser_id' => (int) $adviser_user_id));
                } else {
                    $staff = $this->db->select('IDNumber')->where('user_id', $adviser_user_id)->get('staff')->row();
                    if ($staff) {
                        $this->db->where('id', $section_id)->update('sections', array('adviser_id' => $staff->IDNumber));
                    }
                }
            }

            $this->session->set_flashdata('success', 'Enrollment updated successfully.');
            redirect('enrollment');
        }

        $data['title'] = 'Edit Enrollment';
        $this->render('enrollment/edit', $data);
    }

    public function delete($id)
    {
        $enrollment = $this->db->where('id', (int) $id)
            ->where('school_id', (int) $this->school_id)
            ->get('enrollments')
            ->row();
        if (!$enrollment) show_404();

        $this->db->where('id', $id)->delete('enrollments');
        $this->session->set_flashdata('success', 'Enrollment deleted successfully.');
        redirect('enrollment');
    }

    public function grade_level_sections($grade_level_value = null)
    {
        $grade_level_value = (int) $grade_level_value;
        if ($grade_level_value <= 0) {
            show_404();
        }

        $rows = $this->Enrollment_model->get_grade_level_section_enrollees($grade_level_value, $this->school_id);
        if ($this->role_slug === 'teacher') {
            $rows = $this->filter_enrollments_by_sections($rows, $this->get_accessible_section_ids());
        }
        $sections = array();

        foreach ($rows as $row) {
            $section_key = !empty($row->section_id) ? (int) $row->section_id : 0;
            if (!isset($sections[$section_key])) {
                $sections[$section_key] = (object) array(
                    'section_id' => $section_key,
                    'section_name' => !empty($row->section_name) ? $row->section_name : 'Unassigned Section',
                    'male' => array(),
                    'female' => array(),
                    'unspecified' => array(),
                );
            }

            $gender = strtolower(trim((string) $row->gender));
            if ($gender === 'male') {
                $sections[$section_key]->male[] = $row;
            } elseif ($gender === 'female') {
                $sections[$section_key]->female[] = $row;
            } else {
                $sections[$section_key]->unspecified[] = $row;
            }
        }

        $data['title'] = 'Enrollment by Grade Level';
        $data['grade_level_value'] = $grade_level_value;
        $data['grade_level_label'] = $this->get_grade_level_report_label($grade_level_value);
        $data['sections'] = array_values($sections);
        $this->render('enrollment/grade_level_sections', $data);
    }

    private function get_section_adviser_context()
    {
        $has_staff_table = $this->db->query("SHOW TABLES LIKE 'staff'")->num_rows() > 0;
        $adviser_column = $this->db->query("SHOW COLUMNS FROM sections LIKE 'adviser_id'")->row();
        $sections_adviser_is_user_id = $adviser_column && stripos($adviser_column->Type, 'int') !== false;

        return array(
            'has_staff_table' => $has_staff_table,
            'sections_adviser_is_user_id' => $sections_adviser_is_user_id,
        );
    }

    private function get_enrollment_form_data()
    {
        $context = $this->get_section_adviser_context();
        $data = array(
            'grade_levels' => array(),
            'sections' => array(),
            'advisers' => array(),
        );

        $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'");
        if ($check_academic->num_rows() > 0) {
            $data['grade_levels'] = $this->db->where('school_id', $this->school_id)
                ->get('academic_programs')
                ->result();
        } else {
            $data['grade_levels'] = $this->db->where('school_id', $this->school_id)
                ->order_by('year_level', 'ASC')
                ->get('programs')
                ->result();
        }

        $this->db->select('sections.*, CONCAT(u.last_name, ", ", u.first_name) as adviser_name, u.id as adviser_user_id', FALSE)
            ->from('sections');
        if ($context['sections_adviser_is_user_id'] || !$context['has_staff_table']) {
            $this->db->join('users u', 'u.id = sections.adviser_id', 'left');
        } else {
            $this->db->join('staff t', 't.IDNumber = sections.adviser_id', 'left')
                ->join('users u', 'u.id = t.user_id', 'left');
        }
        $data['sections'] = $this->db->where('sections.school_id', $this->school_id)
            ->order_by('sections.name', 'ASC')
            ->get()
            ->result();

        $teacher_role_id = $this->User_model->get_role_id_by_slug('teacher');
        if ($context['has_staff_table']) {
            $data['advisers'] = $this->db->select('u.*, t.IDNumber as staff_id', FALSE)
                ->from('users u')
                ->join('staff t', 't.user_id = u.id', 'left')
                ->where('u.school_id', $this->school_id)
                ->where('u.role_id', $teacher_role_id)
                ->order_by('u.last_name', 'ASC')
                ->order_by('u.first_name', 'ASC')
                ->get()
                ->result();
        } else {
            $data['advisers'] = $this->db->where('school_id', $this->school_id)
                ->where('role_id', $teacher_role_id)
                ->order_by('last_name', 'ASC')
                ->order_by('first_name', 'ASC')
                ->get('users')
                ->result();
        }

        return $data;
    }

    private function get_grade_level_report_label($grade_level_value)
    {
        $grade_level_value = (int) $grade_level_value;
        if ($grade_level_value <= 0) {
            return 'Grade Level';
        }

        if ($this->db->field_exists('year_level', 'enrollments')) {
            return 'Grade ' . str_pad($grade_level_value, 2, '0', STR_PAD_LEFT);
        }

        $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();
        if ($check_academic > 0) {
            $program = $this->db->select('name, year_level')
                ->where('id', $grade_level_value)
                ->where('school_id', $this->school_id)
                ->get('academic_programs')
                ->row();
            if ($program) {
                if (isset($program->year_level) && $program->year_level) {
                    return 'Grade ' . str_pad($program->year_level, 2, '0', STR_PAD_LEFT);
                }
                if (!empty($program->name)) {
                    return $program->name;
                }
            }
        } else {
            $program = $this->db->select('name, year_level')
                ->where('id', $grade_level_value)
                ->where('school_id', $this->school_id)
                ->get('programs')
                ->row();
            if ($program) {
                if (isset($program->year_level) && $program->year_level) {
                    return 'Grade ' . str_pad($program->year_level, 2, '0', STR_PAD_LEFT);
                }
                if (!empty($program->name)) {
                    return $program->name;
                }
            }
        }

        return $grade_level_value <= 12
            ? 'Grade ' . str_pad($grade_level_value, 2, '0', STR_PAD_LEFT)
            : 'Grade Level ' . $grade_level_value;
    }
}
