<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class Academic_model extends CI_Model {

    // ---- School Years ----
    public function get_school_years($school_id = null)
    {
        if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
        return $this->db->order_by('year_start', 'DESC')->get('school_years')->result();
    }

    public function get_active_school_year($school_id = null)
    {
        if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
        return $this->db->where('is_active', 1)->get('school_years')->row();
    }

    public function get_school_year($id)
    {
        return $this->db->where('id', $id)->get('school_years')->row();
    }

    public function create_school_year($data)
    {
        $this->db->insert('school_years', $data);
        return $this->db->insert_id();
    }

    public function update_school_year($id, $data)
    {
        return $this->db->where('id', $id)->update('school_years', $data);
    }

    public function set_active_school_year($id)
    {
        $this->db->update('school_years', array('is_active' => 0));
        return $this->db->where('id', $id)->update('school_years', array('is_active' => 1));
    }

    // ---- Semesters / Quarters ----
    public function get_semesters($school_year_id, $type = null)
    {
        $this->db->where('school_year_id', $school_year_id);
        if ($type) {
            $this->db->where('type', $type);
        }
        return $this->db->order_by('term_number', 'ASC')->get('semesters')->result();
    }

    public function get_active_semester($type = 'quarter')
    {
        return $this->db->where('is_active', 1)->where('type', $type)->get('semesters')->row();
    }

    // ---- Grade Levels (DepEd) ----
    public function get_grade_levels($category = null)
    {
        if ($category) {
            $this->db->where('category', $category);
        }
        return $this->db->where('status', 1)->order_by('level_order', 'ASC')->get('grade_levels')->result();
    }

    public function get_grade_level($id)
    {
        return $this->db->where('id', $id)->get('grade_levels')->row();
    }

    // ---- SHS Tracks & Strands ----
    public function get_tracks()
    {
        return $this->db->get('shs_tracks')->result();
    }

    public function get_strands($track_id = null)
    {
        if ($track_id) {
            $this->db->where('track_id', $track_id);
        }
        return $this->db->select('shs_strands.*, shs_tracks.name as track_name')
                        ->join('shs_tracks', 'shs_tracks.id = shs_strands.track_id')
                        ->get('shs_strands')
                        ->result();
    }

    public function get_strand($id)
    {
        return $this->db->select('shs_strands.*, shs_tracks.name as track_name')
                        ->join('shs_tracks', 'shs_tracks.id = shs_strands.track_id')
                        ->where('shs_strands.id', $id)
                        ->get('shs_strands')
                        ->row();
    }

    // ---- Programs (CHED) ----
    public function get_programs()
    {
        return $this->db->where('status', 1)->get('programs')->result();
    }

    public function get_program($id)
    {
        return $this->db->where('id', $id)->get('programs')->row();
    }

    public function create_program($data)
    {
        $this->db->insert('programs', $data);
        return $this->db->insert_id();
    }

    public function update_program($id, $data)
    {
        return $this->db->where('id', $id)->update('programs', $data);
    }

    public function delete_program($id)
    {
        return $this->db->where('id', $id)->delete('programs');
    }

    // ---- Subjects ----
    public function get_subjects($filters = array())
    {
        $this->db->select('subjects.*, grade_levels.name as grade_level_name, programs.code as program_code, programs.name as program_name, learning_areas.name as learning_area_name');
        $this->db->select('(SELECT COUNT(lessons.id) FROM modules JOIN lessons ON lessons.module_id = modules.id WHERE modules.subject_id = subjects.id) as lesson_count', FALSE);
        $this->db->join('grade_levels', 'grade_levels.id = subjects.grade_level_id', 'left');
        $this->db->join('programs', 'programs.id = subjects.program_id', 'left');
        $this->db->join('learning_areas', 'learning_areas.id = subjects.learning_area_id', 'left');

        if (!empty($filters['system_type'])) {
            $this->db->where('subjects.system_type', $filters['system_type']);
        }
        if (!empty($filters['grade_level_id'])) {
            $this->db->where('subjects.grade_level_id', $filters['grade_level_id']);
        }
        if (!empty($filters['program_id'])) {
            $this->db->where('subjects.program_id', $filters['program_id']);
        }
        if (!empty($filters['semester_type'])) {
            $this->db->where('subjects.semester_type', $filters['semester_type']);
        }
        return $this->db->where('subjects.status', 1)->order_by('semester_type, code')->get('subjects')->result();
    }

    public function get_subjects_by_program($program_id)
    {
        $this->db->select('subjects.*, programs.code as program_code, programs.name as program_name');
        $this->db->join('programs', 'programs.id = subjects.program_id');
        $this->db->where('subjects.program_id', $program_id);
        $this->db->where('subjects.status', 1);
        return $this->db->order_by('semester_type, code')->get('subjects')->result();
    }

    public function get_subjects_by_grade_level($grade_level_id)
    {
        $this->db->select('subjects.*, grade_levels.code as grade_level_code, grade_levels.name as grade_level_name');
        $this->db->join('grade_levels', 'grade_levels.id = subjects.grade_level_id');
        $this->db->where('subjects.grade_level_id', $grade_level_id);
        $this->db->where('subjects.status', 1);
        return $this->db->order_by('code')->get('subjects')->result();
    }

    public function get_subject($id)
    {
        $this->db->select('subjects.*, grade_levels.name as grade_level_name, programs.code as program_code, programs.name as program_name, learning_areas.name as learning_area_name');
        $this->db->select('(SELECT COUNT(lessons.id) FROM modules JOIN lessons ON lessons.module_id = modules.id WHERE modules.subject_id = subjects.id) as lesson_count', FALSE);
        $this->db->join('grade_levels', 'grade_levels.id = subjects.grade_level_id', 'left');
        $this->db->join('programs', 'programs.id = subjects.program_id', 'left');
        $this->db->join('learning_areas', 'learning_areas.id = subjects.learning_area_id', 'left');
        return $this->db->where('subjects.id', $id)->get('subjects')->row();
    }

    public function create_subject($data)
    {
        $this->db->insert('subjects', $data);
        return $this->db->insert_id();
    }

    public function update_subject($id, $data)
    {
        return $this->db->where('id', $id)->update('subjects', $data);
    }

    public function delete_subject($id)
    {
        return $this->db->where('id', $id)->delete('subjects');
    }

    // ---- Learning Areas ----
    public function get_learning_areas()
    {
        return $this->db->get('learning_areas')->result();
    }

    // ---- Sections ----
    public function get_sections($filters = array())
    {
        $this->db->select('sections.*, grade_levels.name as grade_level_name, programs.code as program_code, CONCAT(u.first_name, " ", u.last_name) as adviser_name', FALSE);
        $this->db->join('grade_levels', 'grade_levels.id = sections.grade_level_id', 'left');
        $this->db->join('programs', 'programs.id = sections.program_id', 'left');
        $this->db->join('teachers', 'teachers.id = sections.adviser_id', 'left');
        $this->db->join('users u', 'u.id = teachers.user_id', 'left');

        if (!empty($filters['school_year_id'])) {
            $this->db->where('sections.school_year_id', $filters['school_year_id']);
        }
        if (!empty($filters['system_type'])) {
            $this->db->where('sections.system_type', $filters['system_type']);
        }
        if (!empty($filters['grade_level_id'])) {
            $this->db->where('sections.grade_level_id', $filters['grade_level_id']);
        }
        if (!empty($filters['school_id'])) {
            $this->db->where('sections.school_id', $filters['school_id']);
        }
        return $this->db->get('sections')->result();
    }

    public function get_section($id)
    {
        return $this->db->select('sections.*, grade_levels.name as grade_level_name, programs.code as program_code')
                        ->join('grade_levels', 'grade_levels.id = sections.grade_level_id', 'left')
                        ->join('programs', 'programs.id = sections.program_id', 'left')
                        ->where('sections.id', $id)
                        ->get('sections')
                        ->row();
    }

    public function create_section($data)
    {
        $this->db->insert('sections', $data);
        return $this->db->insert_id();
    }

    public function update_section($id, $data)
    {
        return $this->db->where('id', $id)->update('sections', $data);
    }

    public function delete_section($id)
    {
        return $this->db->where('id', $id)->delete('sections');
    }

    // ---- Class Programs (Section-Subject-Teacher mapping) ----
    public function ensure_class_program_enrollment_key_column()
    {
        if (!$this->db->field_exists('enrollment_key', 'class_programs')) {
            $this->db->query("ALTER TABLE `class_programs` ADD COLUMN `enrollment_key` varchar(50) DEFAULT NULL AFTER `teacher_id`");
        }
    }

    public function get_class_programs($section_id)
    {
        $this->ensure_class_program_enrollment_key_column();
        return $this->db->select('class_programs.*, subjects.name as subject_name, subjects.code as subject_code, CONCAT(u.first_name, " ", u.last_name) as teacher_name, semesters.name as semester_name', FALSE)
                        ->join('subjects', 'subjects.id = class_programs.subject_id')
                        ->join('teachers', 'teachers.id = class_programs.teacher_id', 'left')
                        ->join('users u', 'u.id = teachers.user_id', 'left')
                        ->join('semesters', 'semesters.id = class_programs.semester_id', 'left')
                        ->where('class_programs.section_id', $section_id)
                        ->get('class_programs')
                        ->result();
    }

    public function get_class_program($id)
    {
        $this->ensure_class_program_enrollment_key_column();
        return $this->db->select('class_programs.*, subjects.name as subject_name, subjects.code as subject_code, sections.name as section_name, sections.system_type')
                        ->join('subjects', 'subjects.id = class_programs.subject_id')
                        ->join('sections', 'sections.id = class_programs.section_id')
                        ->where('class_programs.id', $id)
                        ->get('class_programs')
                        ->row();
    }

    public function get_class_program_by_subject_section($subject_id, $section_id)
    {
        $this->ensure_class_program_enrollment_key_column();
        return $this->db->where('subject_id', $subject_id)
                        ->where('section_id', $section_id)
                        ->get('class_programs')
                        ->row();
    }

    public function get_subject_sections($subject_id)
    {
        $this->ensure_class_program_enrollment_key_column();
        return $this->db->select('class_programs.*, sections.name as section_name, sections.system_type, grade_levels.name as grade_level_name, programs.code as program_code', FALSE)
                        ->join('sections', 'sections.id = class_programs.section_id')
                        ->join('grade_levels', 'grade_levels.id = sections.grade_level_id', 'left')
                        ->join('programs', 'programs.id = sections.program_id', 'left')
                        ->where('class_programs.subject_id', $subject_id)
                        ->where('class_programs.status', 1)
                        ->order_by('sections.name', 'ASC')
                        ->get('class_programs')
                        ->result();
    }

    public function subject_has_enrollment_keys($subject_id)
    {
        $this->ensure_class_program_enrollment_key_column();
        return $this->db->where('subject_id', $subject_id)
                        ->where('enrollment_key IS NOT NULL', null, false)
                        ->where('enrollment_key !=', '')
                        ->where('status', 1)
                        ->count_all_results('class_programs') > 0;
    }

    public function validate_subject_enrollment_key($subject_id, $enrollment_key)
    {
        $this->ensure_class_program_enrollment_key_column();
        $key = trim((string) $enrollment_key);
        if ($key === '') {
            return null;
        }

        return $this->db->select('class_programs.*, sections.name as section_name')
                        ->join('sections', 'sections.id = class_programs.section_id')
                        ->where('class_programs.subject_id', $subject_id)
                        ->where('class_programs.enrollment_key', $key)
                        ->where('class_programs.status', 1)
                        ->get('class_programs')
                        ->row();
    }

    public function save_subject_section($subject_id, $section_id, $enrollment_key = null)
    {
        $this->ensure_class_program_enrollment_key_column();
        $key = trim((string) $enrollment_key);
        $data = array(
            'subject_id'      => $subject_id,
            'section_id'      => $section_id,
            'enrollment_key'  => $key === '' ? null : $key,
            'status'          => 1,
        );

        $existing = $this->get_class_program_by_subject_section($subject_id, $section_id);
        if ($existing) {
            $this->db->where('id', $existing->id)->update('class_programs', $data);
            return $existing->id;
        }

        $this->db->insert('class_programs', $data);
        return $this->db->insert_id();
    }

    public function save_subject_section_by_name($subject_id, $section_name, $enrollment_key = null)
    {
        $this->db->where('school_id', $this->session->userdata('school_id'));
        $section = $this->db->where('name', $section_name)->get('sections')->row();

        if (!$section) {
            $school_year = $this->db->where('is_active', 1)->where('school_id', $this->session->userdata('school_id'))->get('school_years')->row();
            $school_year_id = $school_year ? $school_year->id : null;
            
            $section_data = array(
                'name'          => $section_name,
                'school_id'     => $this->session->userdata('school_id'),
                'system_type'   => 'custom',
                'school_year_id'=> $school_year_id,
            );
            $this->db->insert('sections', $section_data);
            $section_id = $this->db->insert_id();
        } else {
            $section_id = $section->id;
        }

        return $this->save_subject_section($subject_id, $section_id, $enrollment_key);
    }

    public function remove_subject_section($class_program_id, $subject_id)
    {
        return $this->db->where('id', $class_program_id)
                        ->where('subject_id', $subject_id)
                        ->delete('class_programs');
    }

    public function update_subject_section($class_program_id, $subject_id, $section_name, $enrollment_key = null)
    {
        $this->ensure_class_program_enrollment_key_column();
        
        $class_program = $this->db->where('id', $class_program_id)->where('subject_id', $subject_id)->get('class_programs')->row();
        if (!$class_program) {
            return false;
        }

        $key = trim((string) $enrollment_key);
        
        // Always update the section name
        $this->db->where('id', $class_program->section_id)->update('sections', array('name' => $section_name));

        // Update enrollment key
        $this->db->where('id', $class_program_id)->where('subject_id', $subject_id)->update('class_programs', array(
            'enrollment_key' => $key === '' ? null : $key
        ));

        return true;
    }

    public function update_subject_cover_photo($subject_id, $cover_photo)
    {
        $this->db->where('id', $subject_id)->update('subjects', array('cover_photo' => $cover_photo));
        return true;
    }

    public function get_student_enrolled_subjects($student_id)
    {
        $student = $this->db->where('id', $student_id)->get('students')->row();
        if (!$student) {
            return array();
        }
        
        // For now, return empty array - enrollment tracking needs proper table structure
        return array();
    }

    public function get_available_subjects_for_student($student_id)
    {
        $student = $this->db->where('id', $student_id)->get('students')->row();
        if (!$student) {
            return array();
        }
        
        // Get all subjects for the student's school
        $this->db->select('subjects.*, class_programs.enrollment_key as requires_key', FALSE);
        $this->db->from('class_programs');
        $this->db->join('subjects', 'subjects.id = class_programs.subject_id');
        $this->db->where('class_programs.status', 1);
        $this->db->where('subjects.school_id', $student->school_id);
        
        return $this->db->get()->result();
    }

    public function get_subjects_for_student($student_id, $filters = array())
    {
        $student = $this->db->where('id', $student_id)->get('students')->row();
        if (!$student) {
            return array();
        }
        
        // Query subjects directly from subjects table
        $this->db->select('subjects.*', FALSE);
        $this->db->from('subjects');
        $this->db->where('subjects.school_id', $student->school_id);
        
        if (!empty($filters['system_type'])) {
            $this->db->where('subjects.system_type', $filters['system_type']);
        }
        
        $subjects = $this->db->get()->result();
        
        // Group by program (using program_code if available, otherwise 'General')
        $grouped = array();
        foreach ($subjects as $subject) {
            $program_key = 'General';
            if (!isset($grouped[$program_key])) {
                $grouped[$program_key] = array(
                    'program_code' => 'General',
                    'program_name' => 'All Subjects',
                    'subjects' => array()
                );
            }
            $grouped[$program_key]['subjects'][] = $subject;
        }
        
        return $grouped;
    }

    public function validate_enrollment_key($subject_id, $enrollment_key, $student_id)
    {
        $this->ensure_class_program_enrollment_key_column();
        
        $student = $this->db->where('id', $student_id)->get('students')->row();
        if (!$student) {
            return false;
        }
        
        // Validate enrollment key - get any class_program for this subject
        $class_program = $this->db->where('subject_id', $subject_id)
                                  ->where('status', 1)
                                  ->get('class_programs')
                                  ->row();
        
        if (!$class_program) {
            return false;
        }
        
        $stored_key = trim((string) $class_program->enrollment_key);
        $provided_key = trim((string) $enrollment_key);
        
        // If no key is set, allow enrollment
        if ($stored_key === '') {
            return true;
        }
        
        // Validate key
        if ($stored_key === $provided_key) {
            return true;
        }
        
        return false;
    }

    public function enroll_student($student_id, $class_program_id)
    {
        // Placeholder - enrollment tracking needs proper table structure
        return true;
    }

    public function is_student_enrolled($student_id, $subject_id)
    {
        // For now, return true to allow access
        return true;
    }

    public function get_teacher_classes($teacher_id, $school_year_id = null)
    {
        $this->ensure_class_program_enrollment_key_column();
        $this->db->select('class_programs.*, subjects.name as subject_name, subjects.code as subject_code, sections.name as section_name, grade_levels.name as grade_level_name, programs.code as program_code', FALSE);
        $this->db->join('subjects', 'subjects.id = class_programs.subject_id');
        $this->db->join('sections', 'sections.id = class_programs.section_id');
        $this->db->join('grade_levels', 'grade_levels.id = sections.grade_level_id', 'left');
        $this->db->join('programs', 'programs.id = sections.program_id', 'left');
        $this->db->where('class_programs.teacher_id', $teacher_id);
        if ($school_year_id) {
            $this->db->where('sections.school_year_id', $school_year_id);
        }
        return $this->db->get('class_programs')->result();
    }

    // ---- Teachers ----
    public function get_teachers()
    {
        return $this->db->select('teachers.*, users.first_name, users.last_name, users.email')
                        ->join('users', 'users.id = teachers.user_id')
                        ->where('users.status', 1)
                        ->get('teachers')
                        ->result();
    }

    public function get_teacher_by_user($user_id)
    {
        return $this->db->where('user_id', $user_id)->get('teachers')->row();
    }
}
