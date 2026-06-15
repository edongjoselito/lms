<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class Enrollment_model extends CI_Model {

    public function get_enrollments($filters = array())
    {
        $this->db->select('enrollments.*, students.lrn, students.student_id as stud_id_num, CONCAT(u.first_name, " ", u.last_name) as student_name, u.email, grade_levels.name as grade_level_name, programs.code as program_code, sections.name as section_name', FALSE);
        $this->db->join('students', 'students.id = enrollments.student_id');
        $this->db->join('users u', 'u.id = students.user_id');
        $this->db->join('grade_levels', 'grade_levels.id = enrollments.grade_level_id', 'left');
        $this->db->join('programs', 'programs.id = enrollments.program_id', 'left');
        $this->db->join('sections', 'sections.id = enrollments.section_id', 'left');

        if (!empty($filters['school_year_id'])) {
            $this->db->where('enrollments.school_year_id', $filters['school_year_id']);
        }
        if (!empty($filters['system_type'])) {
            $this->db->where('enrollments.system_type', $filters['system_type']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('enrollments.status', $filters['status']);
        }
        if (!empty($filters['section_id'])) {
            $this->db->where('enrollments.section_id', $filters['section_id']);
        }
        if (!empty($filters['school_id'])) {
            $this->db->where('enrollments.school_id', $filters['school_id']);
        }
        return $this->db->order_by('u.last_name', 'ASC')->get('enrollments')->result();
    }

    public function get_enrollment($id)
    {
        return $this->db->select('enrollments.*, students.lrn, students.student_id as stud_id_num, CONCAT(u.first_name, " ", u.last_name) as student_name, u.email, grade_levels.name as grade_level_name, programs.code as program_code, sections.name as section_name', FALSE)
                        ->join('students', 'students.id = enrollments.student_id')
                        ->join('users u', 'u.id = students.user_id')
                        ->join('grade_levels', 'grade_levels.id = enrollments.grade_level_id', 'left')
                        ->join('programs', 'programs.id = enrollments.program_id', 'left')
                        ->join('sections', 'sections.id = enrollments.section_id', 'left')
                        ->where('enrollments.id', $id)
                        ->get('enrollments')
                        ->row();
    }

    public function create($data)
    {
        $data['enrollment_date'] = date('Y-m-d');
        $this->db->insert('enrollments', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('enrollments', $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete('enrollments');
    }

    public function count_enrolled($school_year_id, $school_id = null)
    {
        $this->db->where('school_year_id', $school_year_id);
        $this->db->where('status', 'enrolled');
        if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
        return $this->db->count_all_results('enrollments');
    }

    // ---- Students ----
    public function get_students($filters = array())
    {
        $this->db->select('students.*, CONCAT(u.first_name, " ", u.last_name) as full_name, u.email, u.first_name, u.last_name, grade_levels.name as grade_level_name, programs.code as program_code', FALSE);
        $this->db->join('users u', 'u.id = students.user_id');
        $this->db->join('grade_levels', 'grade_levels.id = students.grade_level_id', 'left');
        $this->db->join('programs', 'programs.id = students.program_id', 'left');

        if (!empty($filters['system_type'])) {
            $this->db->where('students.system_type', $filters['system_type']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('students.status', $filters['status']);
        }
        if (!empty($filters['school_id'])) {
            $this->db->where('students.school_id', $filters['school_id']);
        }
        return $this->db->order_by('u.last_name', 'ASC')->get('students')->result();
    }

    public function get_student($id)
    {
        return $this->db->select('students.*, u.first_name, u.last_name, u.email, u.phone, grade_levels.name as grade_level_name, programs.code as program_code', FALSE)
                        ->join('users u', 'u.id = students.user_id')
                        ->join('grade_levels', 'grade_levels.id = students.grade_level_id', 'left')
                        ->join('programs', 'programs.id = students.program_id', 'left')
                        ->where('students.id', $id)
                        ->get('students')
                        ->row();
    }

    public function get_student_by_user($user_id)
    {
        return $this->db->where('user_id', $user_id)->get('students')->row();
    }

    public function create_student($user_data, $student_data)
    {
        $user_data['password'] = password_hash($user_data['password'], PASSWORD_BCRYPT);
        $user_data['role_id'] = 5; // student role
        if (isset($student_data['school_id'])) {
            $user_data['school_id'] = $student_data['school_id'];
        }
        $this->db->insert('users', $user_data);
        $user_id = $this->db->insert_id();

        $student_data['user_id'] = $user_id;
        $this->db->insert('students', $student_data);
        return $this->db->insert_id();
    }

    public function count_students($system_type = null, $school_id = null)
    {
        if ($system_type) {
            $this->db->where('system_type', $system_type);
        }
        if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
        return $this->db->where('status', 'active')->count_all_results('students');
    }

    public function get_section_students($section_id, $school_year_id)
    {
        return $this->db->select('enrollments.*, CONCAT(u.first_name, " ", u.last_name) as student_name, students.lrn, students.student_id as stud_id_num', FALSE)
                        ->join('students', 'students.id = enrollments.student_id')
                        ->join('users u', 'u.id = students.user_id')
                        ->where('enrollments.section_id', $section_id)
                        ->where('enrollments.school_year_id', $school_year_id)
                        ->where('enrollments.status', 'enrolled')
                        ->order_by('u.last_name', 'ASC')
                        ->get('enrollments')
                        ->result();
    }

    // Methods for studentprofile-based enrollment system
    public function get_all($school_id = null)
    {
        // Check if academic_programs table exists
        $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();
        
        if ($check_academic > 0) {
            $this->db->select('enrollments.*, sp.student_number, sp.first_name, sp.middle_name, sp.last_name, sp.birth_date, sp.email as profile_email, u.email as user_email, u.status as user_status, sections.name as section_name, enrollments.grade_level_id, ap.year_level, ap.name as grade_level_name, CONCAT(school_years.year_start, "-", school_years.year_end) as school_year_name');
            $this->db->from('enrollments');
            $this->db->join('studentprofile sp', 'sp.user_id = enrollments.student_id', 'left');
            $this->db->join('users u', 'u.id = enrollments.student_id', 'left');
            $this->db->join('sections', 'sections.id = enrollments.section_id', 'left');
            $this->db->join('school_years', 'school_years.id = enrollments.school_year_id', 'left');
            $this->db->join('academic_programs ap', 'ap.id = enrollments.grade_level_id', 'left');
        } else {
            // Check if programs table exists
            $check_programs = $this->db->query("SHOW TABLES LIKE 'programs'")->num_rows();
            if ($check_programs > 0) {
                // Check if name column exists in programs table
                $checkName = $this->db->query("SHOW COLUMNS FROM programs LIKE 'name'")->num_rows();
                $select_fields = 'enrollments.*, sp.student_number, sp.first_name, sp.middle_name, sp.last_name, sp.birth_date, sp.email as profile_email, u.email as user_email, u.status as user_status, sections.name as section_name, enrollments.grade_level_id, p.year_level';
                if ($checkName > 0) {
                    $select_fields .= ', p.name as grade_level_name';
                }
                $select_fields .= ', CONCAT(school_years.year_start, "-", school_years.year_end) as school_year_name';
                
                $this->db->select($select_fields);
                $this->db->from('enrollments');
                $this->db->join('studentprofile sp', 'sp.user_id = enrollments.student_id', 'left');
                $this->db->join('users u', 'u.id = enrollments.student_id', 'left');
                $this->db->join('sections', 'sections.id = enrollments.section_id', 'left');
                $this->db->join('school_years', 'school_years.id = enrollments.school_year_id', 'left');
                $this->db->join('programs p', 'p.id = enrollments.grade_level_id', 'left');
            } else {
                // Fallback without year_level
                $this->db->select('enrollments.*, sp.student_number, sp.first_name, sp.middle_name, sp.last_name, sp.birth_date, sp.email as profile_email, u.email as user_email, u.status as user_status, sections.name as section_name, enrollments.grade_level_id, CONCAT(school_years.year_start, "-", school_years.year_end) as school_year_name');
                $this->db->from('enrollments');
                $this->db->join('studentprofile sp', 'sp.user_id = enrollments.student_id', 'left');
                $this->db->join('users u', 'u.id = enrollments.student_id', 'left');
                $this->db->join('sections', 'sections.id = enrollments.section_id', 'left');
                $this->db->join('school_years', 'school_years.id = enrollments.school_year_id', 'left');
            }
        }
        
        if ($school_id) {
            $this->db->where('enrollments.school_id', $school_id);
        }
        return $this->db->order_by('sp.last_name, sp.first_name')->get()->result();
    }

    public function get_stats($school_id = null)
    {
        $stats = array(
            'total_enrolled' => 0,
            'total_sections' => 0,
            'total_grade_levels' => 0
        );

        if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
        $stats['total_enrolled'] = $this->db->where('status', 'enrolled')->count_all_results('enrollments');

        if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
        $stats['total_sections'] = $this->db->count_all_results('sections');

        // Count distinct grade levels from enrollments
        if ($this->db->field_exists('year_level', 'enrollments')) {
            $this->db->select('COUNT(DISTINCT year_level) as count', FALSE);
            $this->db->where('year_level IS NOT NULL', null, false);
        } else {
            $this->db->select('COUNT(DISTINCT grade_level_id) as count', FALSE);
        }
        if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
        $result = $this->db->where('status', 'enrolled')->get('enrollments')->row();
        $stats['total_grade_levels'] = $result ? $result->count : 0;

        return $stats;
    }

    public function get_grade_level_counts($school_id = null)
    {
        if ($this->db->field_exists('year_level', 'enrollments')) {
            $this->db->select('year_level, COUNT(*) as count', FALSE);
            $this->db->where('year_level IS NOT NULL', null, false);
            $group_field = 'year_level';
        } else {
            $this->db->select('grade_level_id, COUNT(*) as count', FALSE);
            $group_field = 'grade_level_id';
        }
        $this->db->where('status', 'enrolled');
        if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
        $this->db->group_by($group_field);
        $this->db->order_by($group_field, 'ASC');
        return $this->db->get('enrollments')->result();
    }

    public function get_grade_level_section_enrollees($grade_level_value, $school_id = null)
    {
        $grade_level_value = (int) $grade_level_value;
        if ($grade_level_value <= 0) {
            return array();
        }

        $this->db->select('enrollments.*, sections.name as section_name, sp.student_number, sp.first_name, sp.middle_name, sp.last_name, sp.birth_date, COALESCE(students.gender, \'Unspecified\') as gender', FALSE);
        $this->db->from('enrollments');
        $this->db->join('sections', 'sections.id = enrollments.section_id', 'left');
        $this->db->join('studentprofile sp', 'sp.user_id = enrollments.student_id', 'left');
        $this->db->join('students', 'students.user_id = enrollments.student_id', 'left');

        if ($this->db->field_exists('year_level', 'enrollments')) {
            $this->db->where('enrollments.year_level', $grade_level_value);
        } else {
            $this->db->where('enrollments.grade_level_id', $grade_level_value);
        }

        $this->db->where('enrollments.status', 'enrolled');
        if ($school_id) {
            $this->db->where('enrollments.school_id', $school_id);
        }

        $this->db->order_by('sections.name', 'ASC');
        $this->db->order_by("CASE COALESCE(students.gender, 'Unspecified') WHEN 'Male' THEN 1 WHEN 'Female' THEN 2 ELSE 3 END", '', FALSE);
        $this->db->order_by('sp.last_name', 'ASC');
        $this->db->order_by('sp.first_name', 'ASC');

        return $this->db->get()->result();
    }
}
