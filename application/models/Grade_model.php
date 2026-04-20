<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class Grade_model extends CI_Model {

    // ---- Grade Components ----
    public function get_components($system_type, $subject_category = null)
    {
        $this->db->where('system_type', $system_type);
        if ($subject_category) {
            $this->db->where('subject_category', $subject_category);
        }
        return $this->db->get('grade_components')->result();
    }

    // ---- Grade Entries (individual scores) ----
    public function get_entries($enrollment_id, $class_program_id, $semester_id = null)
    {
        $this->db->select('grade_entries.*, grade_components.name as component_name, grade_components.code as component_code');
        $this->db->join('grade_components', 'grade_components.id = grade_entries.component_id');
        $this->db->where('grade_entries.enrollment_id', $enrollment_id);
        $this->db->where('grade_entries.class_program_id', $class_program_id);
        if ($semester_id) {
            $this->db->where('grade_entries.semester_id', $semester_id);
        }
        return $this->db->get('grade_entries')->result();
    }

    public function save_entry($data)
    {
        if (!empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            return $this->db->where('id', $id)->update('grade_entries', $data);
        }
        $this->db->insert('grade_entries', $data);
        return $this->db->insert_id();
    }

    public function delete_entry($id)
    {
        return $this->db->where('id', $id)->delete('grade_entries');
    }

    // ---- Student Grades (computed) ----
    public function get_student_grade($enrollment_id, $class_program_id, $semester_id = null)
    {
        $this->db->where('enrollment_id', $enrollment_id);
        $this->db->where('class_program_id', $class_program_id);
        if ($semester_id) {
            $this->db->where('semester_id', $semester_id);
        }
        return $this->db->get('student_grades')->row();
    }

    public function get_class_grades($class_program_id, $semester_id = null)
    {
        $this->db->select('student_grades.*, CONCAT(u.first_name, " ", u.last_name) as student_name, students.lrn, students.student_id as stud_id_num', FALSE);
        $this->db->join('enrollments', 'enrollments.id = student_grades.enrollment_id');
        $this->db->join('students', 'students.id = enrollments.student_id');
        $this->db->join('users u', 'u.id = students.user_id');
        $this->db->where('student_grades.class_program_id', $class_program_id);
        if ($semester_id) {
            $this->db->where('student_grades.semester_id', $semester_id);
        }
        return $this->db->order_by('u.last_name', 'ASC')->get('student_grades')->result();
    }

    public function save_student_grade($data)
    {
        $existing = $this->get_student_grade(
            $data['enrollment_id'],
            $data['class_program_id'],
            isset($data['semester_id']) ? $data['semester_id'] : null
        );

        if ($existing) {
            return $this->db->where('id', $existing->id)->update('student_grades', $data);
        }
        $this->db->insert('student_grades', $data);
        return $this->db->insert_id();
    }

    public function lock_grade($id, $approved_by)
    {
        return $this->db->where('id', $id)->update('student_grades', array(
            'is_locked'   => 1,
            'approved_by' => $approved_by,
            'approved_at' => date('Y-m-d H:i:s')
        ));
    }

    // ---- DepEd Grade Computation ----
    public function compute_deped_grade($enrollment_id, $class_program_id, $semester_id, $subject_category = 'core')
    {
        $components = $this->get_components('deped', $subject_category);
        $result = array();

        foreach ($components as $comp) {
            $entries = $this->db->where('enrollment_id', $enrollment_id)
                                ->where('class_program_id', $class_program_id)
                                ->where('component_id', $comp->id)
                                ->where('semester_id', $semester_id)
                                ->get('grade_entries')
                                ->result();

            $total_score = 0;
            $total_possible = 0;
            foreach ($entries as $entry) {
                $total_score += $entry->score;
                $total_possible += $entry->total_score;
            }

            $percentage = ($total_possible > 0) ? ($total_score / $total_possible) * 100 : 0;
            $weighted = $percentage * ($comp->weight_percentage / 100);
            $result[$comp->code] = array(
                'percentage' => round($percentage, 2),
                'weighted'   => round($weighted, 2),
            );
        }

        $initial_grade = 0;
        foreach ($result as $r) {
            $initial_grade += $r['weighted'];
        }

        $transmuted = $this->transmute($initial_grade);

        return array(
            'components'      => $result,
            'initial_grade'   => round($initial_grade, 2),
            'transmuted_grade' => $transmuted,
            'remarks'         => ($transmuted >= 75) ? 'passed' : 'failed'
        );
    }

    // ---- Transmutation ----
    public function transmute($initial_grade)
    {
        $row = $this->db->where('initial_grade_min <=', $initial_grade)
                        ->where('initial_grade_max >=', $initial_grade)
                        ->get('transmutation_table')
                        ->row();
        return $row ? $row->transmuted_grade : 60;
    }

    // ---- CHED GPA Computation ----
    public function compute_ched_gpa($student_id, $school_year_id, $semester_id)
    {
        $grades = $this->db->select('student_grades.final_grade, subjects.units')
                           ->join('class_programs', 'class_programs.id = student_grades.class_program_id')
                           ->join('subjects', 'subjects.id = class_programs.subject_id')
                           ->join('enrollments', 'enrollments.id = student_grades.enrollment_id')
                           ->where('enrollments.student_id', $student_id)
                           ->where('enrollments.school_year_id', $school_year_id)
                           ->where('student_grades.semester_id', $semester_id)
                           ->where('student_grades.system_type', 'ched')
                           ->get('student_grades')
                           ->result();

        $total_units = 0;
        $total_grade_points = 0;
        foreach ($grades as $g) {
            if ($g->units > 0 && $g->final_grade > 0) {
                $total_units += $g->units;
                $total_grade_points += ($g->final_grade * $g->units);
            }
        }

        $gpa = ($total_units > 0) ? $total_grade_points / $total_units : 0;

        return array(
            'total_units'       => $total_units,
            'total_grade_points' => round($total_grade_points, 2),
            'gpa'               => round($gpa, 3)
        );
    }

    // ---- Student grade summary for report card ----
    public function get_student_quarterly_grades($enrollment_id)
    {
        return $this->db->select('student_grades.*, subjects.name as subject_name, subjects.code as subject_code, semesters.name as quarter_name, semesters.term_number')
                        ->join('class_programs', 'class_programs.id = student_grades.class_program_id')
                        ->join('subjects', 'subjects.id = class_programs.subject_id')
                        ->join('semesters', 'semesters.id = student_grades.semester_id', 'left')
                        ->where('student_grades.enrollment_id', $enrollment_id)
                        ->order_by('subjects.name', 'ASC')
                        ->order_by('semesters.term_number', 'ASC')
                        ->get('student_grades')
                        ->result();
    }
}
