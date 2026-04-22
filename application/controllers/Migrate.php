<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function subjects_school_id()
    {
        try {
            // Check if academic_programs table exists
            $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'");
            
            if ($check_academic->num_rows() > 0) {
                // Use academic_programs table
                $this->db->query("
                    UPDATE subjects s
                    LEFT JOIN academic_programs ap ON s.program_id = ap.id
                    LEFT JOIN academic_programs ap2 ON s.grade_level_id = ap2.id
                    SET s.school_id = COALESCE(ap.school_id, ap2.school_id, 1)
                    WHERE s.school_id IS NULL OR s.school_id = 0
                ");
            } else {
                // Fallback to old tables (programs and grade_levels)
                $this->db->query("
                    UPDATE subjects s
                    LEFT JOIN programs p ON s.program_id = p.id
                    LEFT JOIN grade_levels gl ON s.grade_level_id = gl.id
                    SET s.school_id = COALESCE(p.school_id, gl.school_id, 1)
                    WHERE s.school_id IS NULL OR s.school_id = 0
                ");
            }

            echo "Subjects updated with school_id successfully.";
        } catch (Exception $e) {
            echo "Migration failed: " . $e->getMessage();
        }
    }

    public function subjects_description_column()
    {
        try {
            // Check if description column exists in subjects table
            $check = $this->db->query("SHOW COLUMNS FROM subjects LIKE 'description'");

            if ($check->num_rows() == 0) {
                // Add description column if it doesn't exist
                $this->db->query("ALTER TABLE subjects ADD COLUMN description text DEFAULT NULL AFTER name");
                echo "Description column added to subjects table successfully.";
            } else {
                echo "Description column already exists in subjects table.";
            }
        } catch (Exception $e) {
            echo "Migration failed: " . $e->getMessage();
        }
    }

    public function publish_module($module_id)
    {
        try {
            $this->db->where('id', $module_id)->update('modules', array('is_published' => 1));
            echo "Module $module_id published successfully.";
        } catch (Exception $e) {
            echo "Failed to publish module: " . $e->getMessage();
        }
    }

    public function clear_student_completions($student_id, $subject_id = null)
    {
        try {
            $this->load->model('Student_model');
            $this->Student_model->remove_lesson_completions($student_id, $subject_id);
            echo "Lesson completions cleared for student $student_id" . ($subject_id ? " for subject $subject_id" : "") . ".";
        } catch (Exception $e) {
            echo "Failed to clear completions: " . $e->getMessage();
        }
    }

    public function publish_all_modules($subject_id)
    {
        try {
            $this->db->where('subject_id', $subject_id)->update('modules', array('is_published' => 1));
            $affected = $this->db->affected_rows();
            echo "Published $affected modules for subject $subject_id.";
        } catch (Exception $e) {
            echo "Failed to publish modules: " . $e->getMessage();
        }
    }

    public function backfill_enrollments()
    {
        try {
            // Find students with lesson completions but no course enrollment records
            $this->db->query("
                INSERT INTO course_enrollments (user_id, course_id, role, status)
                SELECT DISTINCT
                    s.user_id,
                    m.subject_id as course_id,
                    'student' as role,
                    'active' as status
                FROM lesson_completions lc
                JOIN lessons l ON lc.lesson_id = l.id
                JOIN modules m ON l.module_id = m.id
                JOIN students s ON lc.student_id = s.id
                LEFT JOIN course_enrollments ce ON s.user_id = ce.user_id AND m.subject_id = ce.course_id
                WHERE ce.id IS NULL
                GROUP BY s.user_id, m.subject_id
            ");
            $affected = $this->db->affected_rows();
            echo "Backfilled $affected enrollment records for students with lesson completions.";
        } catch (Exception $e) {
            echo "Migration failed: " . $e->getMessage();
        }
    }
}
