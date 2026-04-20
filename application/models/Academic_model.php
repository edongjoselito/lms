<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class Academic_model extends CI_Model {

    // ---- School Years ----
    public function get_school_years()
    {
        return $this->db->order_by('year_start', 'DESC')->get('school_years')->result();
    }

    public function get_active_school_year()
    {
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
        $this->db->select('subjects.*, grade_levels.name as grade_level_name, programs.code as program_code, learning_areas.name as learning_area_name');
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
        return $this->db->where('subjects.status', 1)->get('subjects')->result();
    }

    public function get_subject($id)
    {
        return $this->db->where('id', $id)->get('subjects')->row();
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
    public function get_class_programs($section_id)
    {
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
        return $this->db->select('class_programs.*, subjects.name as subject_name, subjects.code as subject_code, sections.name as section_name')
                        ->join('subjects', 'subjects.id = class_programs.subject_id')
                        ->join('sections', 'sections.id = class_programs.section_id')
                        ->where('class_programs.id', $id)
                        ->get('class_programs')
                        ->row();
    }

    public function get_teacher_classes($teacher_id, $school_year_id = null)
    {
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
