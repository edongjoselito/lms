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
}
