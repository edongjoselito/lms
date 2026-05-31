<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Enrollment extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Enrollment_model', 'Studentprofile_model', 'User_model'));
        $this->require_school();
    }

    public function index()
    {
        $data['title'] = 'Enrollment';
        $data['enrollments'] = $this->Enrollment_model->get_all($this->school_id);
        $data['stats'] = $this->Enrollment_model->get_stats($this->school_id);
        $data['grade_level_counts'] = $this->Enrollment_model->get_grade_level_counts($this->school_id);
        $this->render('enrollment/index', $data);
    }

    public function edit($id)
    {
        $data['current_section'] = null;
        $has_staff_table = $this->db->query("SHOW TABLES LIKE 'staff'")->num_rows() > 0;
        $adviser_column = $this->db->query("SHOW COLUMNS FROM sections LIKE 'adviser_id'")->row();
        $sections_adviser_is_user_id = $adviser_column && stripos($adviser_column->Type, 'int') !== false;

        // Check if year_level column exists in enrollments table
        $checkYearLevel = $this->db->query("SHOW COLUMNS FROM enrollments LIKE 'year_level'")->num_rows();
        if ($checkYearLevel > 0) {
            $data['enrollment'] = $this->db->where('id', $id)->get('enrollments')->row();
        } else {
            $data['enrollment'] = $this->db->where('id', $id)->get('enrollments')->row();
        }
        
        if (!$data['enrollment']) show_404();

        // Get student profile by user_id (since enrollment.student_id is the user_id)
        $data['profile'] = $this->db->where('user_id', $data['enrollment']->student_id)->get('studentprofile')->row();
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
            $section_id = $this->input->post('section_id', TRUE);
            $adviser_user_id = $this->input->post('adviser_id', TRUE);

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
        $enrollment = $this->db->where('id', $id)->get('enrollments')->row();
        if (!$enrollment) show_404();

        $this->db->where('id', $id)->delete('enrollments');
        $this->session->set_flashdata('success', 'Enrollment deleted successfully.');
        redirect('enrollment');
    }
}
