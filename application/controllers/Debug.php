<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $user_email = 'edgardo.amigo@lms.com';
        
        echo "<h2>USER INFO</h2>";
        $user = $this->db->where('email', $user_email)->get('users')->row();
        if ($user) {
            echo "<pre>";
            echo "User ID: " . $user->id . "\n";
            echo "Email: " . $user->email . "\n";
            echo "School ID: " . $user->school_id . "\n";
            echo "Role ID: " . $user->role_id . "\n";
            echo "</pre>";
        } else {
            echo "User not found\n";
        }

        echo "<h2>STUDENT INFO</h2>";
        if ($user) {
            $student = $this->db->where('user_id', $user->id)->get('students')->row();
            if ($student) {
                echo "<pre>";
                echo "Student ID: " . $student->id . "\n";
                echo "User ID: " . $student->user_id . "\n";
                echo "School ID: " . $student->school_id . "\n";
                echo "</pre>";
            } else {
                echo "Student not found\n";
            }
        }

        echo "<h2>SUBJECTS INFO</h2>";
        if (isset($student)) {
            $student_school_id = $student->school_id;
            echo "<p>Student's School ID: $student_school_id</p>";
            
            echo "<h3>All Subjects (First 10)</h3>";
            $subjects = $this->db->limit(10)->get('subjects')->result();
            echo "<table border='1'><tr><th>ID</th><th>Code</th><th>Name</th><th>School ID</th></tr>";
            foreach ($subjects as $subject) {
                echo "<tr><td>{$subject->id}</td><td>{$subject->code}</td><td>{$subject->name}</td><td>{$subject->school_id}</td></tr>";
            }
            echo "</table>";
            
            echo "<h3>Subjects matching student's school_id ($student_school_id)</h3>";
            $matching_subjects = $this->db->where('school_id', $student_school_id)->get('subjects')->result();
            echo "<p>Count: " . count($matching_subjects) . "</p>";
            echo "<table border='1'><tr><th>ID</th><th>Code</th><th>Name</th><th>School ID</th></tr>";
            foreach ($matching_subjects as $subject) {
                echo "<tr><td>{$subject->id}</td><td>{$subject->code}</td><td>{$subject->name}</td><td>{$subject->school_id}</td></tr>";
            }
            echo "</table>";
            
            echo "<h3>Subjects with NULL school_id</h3>";
            $null_subjects = $this->db->where('school_id IS NULL', null, false)->get('subjects')->result();
            echo "<p>Count: " . count($null_subjects) . "</p>";
            echo "<table border='1'><tr><th>ID</th><th>Code</th><th>Name</th><th>School ID</th></tr>";
            foreach ($null_subjects as $subject) {
                echo "<tr><td>{$subject->id}</td><td>{$subject->code}</td><td>{$subject->name}</td><td>NULL</td></tr>";
            }
            echo "</table>";
        }
    }

    public function modules($subject_id = null)
    {
        echo "<h2>MODULES INFO FOR SUBJECT $subject_id</h2>";

        $modules = $this->db->where('subject_id', $subject_id)
                             ->where('is_published', 1)
                             ->order_by('order_num', 'ASC')
                             ->get('modules')
                             ->result();

        echo "<p>Total Published Modules: " . count($modules) . "</p>";
        echo "<table border='1'><tr><th>ID</th><th>Title</th><th>Order Num</th><th>Is Published</th></tr>";
        foreach ($modules as $module) {
            echo "<tr><td>{$module->id}</td><td>{$module->title}</td><td>{$module->order_num}</td><td>{$module->is_published}</td></tr>";
        }
        echo "</table>";

        echo "<h2>ALL MODULES (Including Unpublished)</h2>";
        $all_modules = $this->db->where('subject_id', $subject_id)
                                ->order_by('order_num', 'ASC')
                                ->get('modules')
                                ->result();
        echo "<p>Total Modules: " . count($all_modules) . "</p>";
        echo "<table border='1'><tr><th>ID</th><th>Title</th><th>Order Num</th><th>Is Published</th></tr>";
        foreach ($all_modules as $module) {
            echo "<tr><td>{$module->id}</td><td>{$module->title}</td><td>{$module->order_num}</td><td>{$module->is_published}</td></tr>";
        }
        echo "</table>";

        echo "<h2>LESSONS FOR EACH PUBLISHED MODULE</h2>";
        $total_published_lessons = 0;
        foreach ($modules as $module) {
            echo "<h3>Module: {$module->title} (Order: {$module->order_num})</h3>";
            $lessons = $this->db->where('module_id', $module->id)
                                ->where('is_published', 1)
                                ->order_by('order_num', 'ASC')
                                ->get('lessons')
                                ->result();
            echo "<p>Total Lessons: " . count($lessons) . "</p>";
            $total_published_lessons += count($lessons);
            echo "<table border='1'><tr><th>ID</th><th>Title</th><th>Order Num</th><th>Is Published</th></tr>";
            foreach ($lessons as $lesson) {
                echo "<tr><td>{$lesson->id}</td><td>{$lesson->title}</td><td>{$lesson->order_num}</td><td>{$lesson->is_published}</td></tr>";
            }
            echo "</table>";
        }
        echo "<h3>Total Published Lessons in Subject: $total_published_lessons</h3>";

        echo "<h2>LESSON COMPLETIONS FOR STUDENT (Published Only)</h2>";
        $user_email = 'edgardo.amigo@lms.com';
        $user = $this->db->where('email', $user_email)->get('users')->row();
        if ($user) {
            $student = $this->db->where('user_id', $user->id)->get('students')->row();
            if ($student) {
                echo "<p>Student ID: {$student->id}</p>";
                $completions = $this->db->select('lc.*, l.title as lesson_title, m.title as module_title, l.is_published as lesson_published, m.is_published as module_published')
                                        ->from('lesson_completions lc')
                                        ->join('lessons l', 'lc.lesson_id = l.id')
                                        ->join('modules m', 'l.module_id = m.id')
                                        ->where('lc.student_id', $student->id)
                                        ->where('m.subject_id', $subject_id)
                                        ->get()
                                        ->result();
                echo "<p>Total Completions (All): " . count($completions) . "</p>";
                echo "<table border='1'><tr><th>Lesson ID</th><th>Lesson Title</th><th>Module Title</th><th>Lesson Published</th><th>Module Published</th><th>Completed At</th></tr>";
                foreach ($completions as $comp) {
                    echo "<tr><td>{$comp->lesson_id}</td><td>{$comp->lesson_title}</td><td>{$comp->module_title}</td><td>{$comp->lesson_published}</td><td>{$comp->module_published}</td><td>{$comp->completed_at}</td></tr>";
                }
                echo "</table>";

                // Filter by published
                $published_completions = array_filter($completions, function($comp) {
                    return $comp->lesson_published == 1 && $comp->module_published == 1;
                });
                echo "<p>Total Completions (Published Only): " . count($published_completions) . "</p>";
            }
        }
    }

    public function lesson($lesson_id = 13)
    {
        echo "<h2>LESSON INFO FOR LESSON $lesson_id</h2>";

        $lesson = $this->db->select('l.*, m.title as module_title, m.subject_id')
                        ->from('lessons l')
                        ->join('modules m', 'm.id = l.module_id')
                        ->where('l.id', $lesson_id)
                        ->get()
                        ->row();

        if ($lesson) {
            echo "<pre>";
            print_r($lesson);
            echo "</pre>";
        } else {
            echo "Lesson not found";
        }
    }
}
