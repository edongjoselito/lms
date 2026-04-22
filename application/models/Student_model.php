<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student_model extends CI_Model {

    public function get_student_by_user_id($user_id)
    {
        return $this->db->where('user_id', $user_id)->get('students')->row();
    }

    public function get_student($student_id)
    {
        return $this->db->where('id', $student_id)->get('students')->row();
    }

    public function create_student($user_id, $school_id)
    {
        // Generate unique lrn and student_id
        $lrn = 'LRN-' . $user_id . '-' . time();
        $student_id = 'STU-' . $user_id . '-' . time();
        
        $this->db->insert('students', array(
            'user_id' => $user_id,
            'lrn' => $lrn,
            'student_id' => $student_id,
            'grade_level_id' => null,
            'program_id' => null,
            'school_id' => $school_id,
            'system_type' => 'deped',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ));
        return $this->db->insert_id();
    }

    public function update_student_school_id($student_id, $school_id)
    {
        return $this->db->where('id', $student_id)->update('students', array('school_id' => $school_id));
    }

    public function get_subjects($student_id, $filters = array())
    {
        $student = $this->get_student($student_id);
        if (!$student) {
            return array();
        }

        $this->db->select('subjects.*');
        $this->db->from('subjects');
        $this->db->group_start();
        $this->db->where('school_id', $student->school_id);
        $this->db->or_where('school_id IS NULL', null, false);
        $this->db->group_end();

        if (!empty($filters['system_type'])) {
            $this->db->where('system_type', $filters['system_type']);
        }

        return $this->db->get()->result();
    }

    public function get_subject($subject_id)
    {
        return $this->db->where('id', $subject_id)->get('subjects')->row();
    }

    public function get_modules_by_subject($subject_id)
    {
        return $this->db->where('subject_id', $subject_id)
                        ->where('is_published', 1)
                        ->order_by('order_num', 'ASC')
                        ->get('modules')
                        ->result();
    }

    public function get_lessons($module_id)
    {
        return $this->db->where('module_id', $module_id)
                        ->where('is_published', 1)
                        ->order_by('order_num', 'ASC')
                        ->get('lessons')
                        ->result();
    }

    public function get_ordered_lessons_by_subject($subject_id)
    {
        return $this->db->select('l.*, m.title as module_title, m.subject_id')
                        ->from('lessons l')
                        ->join('modules m', 'm.id = l.module_id')
                        ->where('m.subject_id', $subject_id)
                        ->where('m.is_published', 1)
                        ->where('l.is_published', 1)
                        ->order_by('m.order_num', 'ASC')
                        ->order_by('l.order_num', 'ASC')
                        ->get()
                        ->result();
    }

    public function mark_lesson_completed($student_id, $lesson_id)
    {
        $existing = $this->db->where('student_id', $student_id)
                            ->where('lesson_id', $lesson_id)
                            ->get('lesson_completions')
                            ->row();

        if (!$existing) {
            $this->db->insert('lesson_completions', array(
                'student_id' => $student_id,
                'lesson_id' => $lesson_id,
                'completed_at' => date('Y-m-d H:i:s')
            ));
        }
        return true;
    }

    public function get_completed_lesson_ids($student_id, $subject_id)
    {
        $this->db->select('lc.lesson_id');
        $this->db->from('lesson_completions lc');
        $this->db->join('lessons l', 'l.id = lc.lesson_id');
        $this->db->join('modules m', 'm.id = l.module_id');
        $this->db->where('lc.student_id', $student_id);
        $this->db->where('m.subject_id', $subject_id);
        $this->db->where('m.is_published', 1);
        $this->db->where('l.is_published', 1);

        $result = $this->db->get()->result();
        return array_column($result, 'lesson_id');
    }

    public function get_total_lessons($subject_id)
    {
        $this->db->from('lessons l');
        $this->db->join('modules m', 'm.id = l.module_id');
        $this->db->where('m.subject_id', $subject_id);
        $this->db->where('m.is_published', 1);
        $this->db->where('l.is_published', 1);
        return $this->db->count_all_results();
    }

    public function remove_lesson_completions($student_id, $subject_id)
    {
        $lesson_ids = $this->db->select('l.id')
                              ->from('lessons l')
                              ->join('modules m', 'm.id = l.module_id')
                              ->where('m.subject_id', $subject_id)
                              ->get()
                              ->result_array();
        $lesson_ids = array_column($lesson_ids, 'id');

        if (!empty($lesson_ids)) {
            $this->db->where('student_id', $student_id);
            $this->db->where_in('lesson_id', $lesson_ids);
            return $this->db->delete('lesson_completions');
        }
        return true;
    }

    public function get_enrolled_subjects($student_id)
    {
        $student = $this->get_student($student_id);
        if (!$student) {
            return array();
        }

        $subjects = array();

        $course_subjects = $this->db->select('s.*, "" as section_name', FALSE)
                                   ->from('subjects s')
                                   ->join('course_enrollments ce', 'ce.course_id = s.id')
                                   ->where('ce.user_id', $student->user_id)
                                   ->where('ce.role', 'student')
                                   ->where('ce.status', 'active')
                                   ->where('s.status', 1)
                                   ->get()
                                   ->result();

        foreach ($course_subjects as $subject) {
            $subjects[(int) $subject->id] = $subject;
        }

        $section_subjects = $this->db->select('s.*, sections.name as section_name', FALSE)
                                    ->from('subjects s')
                                    ->join('class_programs cp', 'cp.subject_id = s.id AND cp.status = 1')
                                    ->join('sections', 'sections.id = cp.section_id', 'left')
                                    ->join('enrollments e', 'e.section_id = cp.section_id')
                                    ->where('e.student_id', $student_id)
                                    ->where('e.status', 'enrolled')
                                    ->where('s.status', 1)
                                    ->get()
                                    ->result();

        foreach ($section_subjects as $subject) {
            $subject_id = (int) $subject->id;
            if (isset($subjects[$subject_id])) {
                if (empty($subjects[$subject_id]->section_name) && !empty($subject->section_name)) {
                    $subjects[$subject_id]->section_name = $subject->section_name;
                }
                continue;
            }
            $subjects[$subject_id] = $subject;
        }

        return array_values($subjects);
    }

    public function is_subject_enrolled($student_id, $subject_id)
    {
        $student = $this->get_student($student_id);
        if (!$student) {
            return false;
        }

        $course_enrollment = $this->db->where('user_id', $student->user_id)
                                      ->where('course_id', $subject_id)
                                      ->where('role', 'student')
                                      ->where('status', 'active')
                                      ->count_all_results('course_enrollments');

        if ($course_enrollment > 0) {
            return true;
        }

        return $this->db->from('enrollments e')
                        ->join('class_programs cp', 'cp.section_id = e.section_id AND cp.status = 1')
                        ->where('e.student_id', $student_id)
                        ->where('e.status', 'enrolled')
                        ->where('cp.subject_id', $subject_id)
                        ->count_all_results() > 0;
    }
}
