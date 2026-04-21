<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class Lesson_model extends CI_Model {

    // ---- Modules ----
    public function get_modules($class_program_id)
    {
        return $this->db->where('class_program_id', $class_program_id)
                        ->order_by('order_num', 'ASC')
                        ->get('modules')
                        ->result();
    }

    public function get_modules_by_course($course_id)
    {
        return $this->db->where('course_id', $course_id)
                        ->order_by('order_num', 'ASC')
                        ->get('modules')
                        ->result();
    }

    public function get_modules_by_subject($subject_id)
    {
        return $this->db->where('subject_id', $subject_id)
                        ->order_by('order_num', 'ASC')
                        ->get('modules')
                        ->result();
    }

    public function get_module_with_lessons($module_id)
    {
        $module = $this->get_module($module_id);
        if ($module) {
            $module->lessons = $this->get_lessons($module_id);
            $module->activities = $this->get_activities($module_id);
        }
        return $module;
    }

    public function get_module($id)
    {
        return $this->db->where('id', $id)->get('modules')->row();
    }

    public function create_module($data)
    {
        $this->db->insert('modules', $data);
        return $this->db->insert_id();
    }

    public function update_module($id, $data)
    {
        return $this->db->where('id', $id)->update('modules', $data);
    }

    public function delete_module($id)
    {
        $this->db->where('module_id', $id)->delete('lessons');
        return $this->db->where('id', $id)->delete('modules');
    }

    public function count_modules($class_program_id)
    {
        return $this->db->where('class_program_id', $class_program_id)
                        ->count_all_results('modules');
    }

    // ---- Lessons ----
    public function get_lessons($module_id)
    {
        return $this->db->where('module_id', $module_id)
                        ->order_by('order_num', 'ASC')
                        ->get('lessons')
                        ->result();
    }

    public function get_lessons_by_class($class_program_id)
    {
        return $this->db->select('lessons.*, modules.title as module_title')
                        ->join('modules', 'modules.id = lessons.module_id')
                        ->where('modules.class_program_id', $class_program_id)
                        ->order_by('modules.order_num', 'ASC')
                        ->order_by('lessons.order_num', 'ASC')
                        ->get('lessons')
                        ->result();
    }

    public function get_lesson($id)
    {
        return $this->db->select('lessons.*, modules.title as module_title, modules.class_program_id')
                        ->join('modules', 'modules.id = lessons.module_id')
                        ->where('lessons.id', $id)
                        ->get('lessons')
                        ->row();
    }

    public function create_lesson($data)
    {
        $this->db->insert('lessons', $data);
        return $this->db->insert_id();
    }

    public function update_lesson($id, $data)
    {
        return $this->db->where('id', $id)->update('lessons', $data);
    }

    public function delete_lesson($id)
    {
        $this->db->where('lesson_id', $id)->delete('lesson_progress');
        return $this->db->where('id', $id)->delete('lessons');
    }

    public function count_lessons($module_id)
    {
        return $this->db->where('module_id', $module_id)->count_all_results('lessons');
    }

    public function get_next_order($table, $parent_field, $parent_id)
    {
        $row = $this->db->select_max('order_num')
                        ->where($parent_field, $parent_id)
                        ->get($table)
                        ->row();
        return ($row && $row->order_num) ? $row->order_num + 1 : 1;
    }

    // ---- Student Progress ----
    public function get_progress($student_id, $lesson_id)
    {
        return $this->db->where('student_id', $student_id)
                        ->where('lesson_id', $lesson_id)
                        ->get('lesson_progress')
                        ->row();
    }

    public function update_progress($student_id, $lesson_id, $data)
    {
        $existing = $this->get_progress($student_id, $lesson_id);
        if ($existing) {
            return $this->db->where('id', $existing->id)->update('lesson_progress', $data);
        } else {
            $data['student_id'] = $student_id;
            $data['lesson_id'] = $lesson_id;
            $this->db->insert('lesson_progress', $data);
            return $this->db->insert_id();
        }
    }

    public function get_class_progress($class_program_id, $student_id)
    {
        return $this->db->select('lesson_progress.*, lessons.title as lesson_title')
                        ->join('lessons', 'lessons.id = lesson_progress.lesson_id')
                        ->join('modules', 'modules.id = lessons.module_id')
                        ->where('modules.class_program_id', $class_program_id)
                        ->where('lesson_progress.student_id', $student_id)
                        ->get('lesson_progress')
                        ->result();
    }

    /**
     * Get all published lesson IDs for a course in sequential order (module order, then lesson order).
     */
    public function get_course_lesson_ids($course_id)
    {
        $rows = $this->db->select('lessons.id')
                         ->join('modules', 'modules.id = lessons.module_id')
                         ->where('modules.course_id', $course_id)
                         ->where('lessons.is_published', 1)
                         ->where('modules.is_published', 1)
                         ->order_by('modules.order_num', 'ASC')
                         ->order_by('lessons.order_num', 'ASC')
                         ->get('lessons')
                         ->result();
        return array_map(function($r) { return (int) $r->id; }, $rows);
    }

    /**
     * Get set of completed lesson IDs for a user in a course.
     */
    public function get_completed_lesson_ids($course_id, $user_id)
    {
        $rows = $this->db->select('lesson_progress.lesson_id')
                         ->join('lessons', 'lessons.id = lesson_progress.lesson_id')
                         ->join('modules', 'modules.id = lessons.module_id')
                         ->where('modules.course_id', $course_id)
                         ->where('lesson_progress.student_id', $user_id)
                         ->where('lesson_progress.status', 'completed')
                         ->get('lesson_progress')
                         ->result();
        return array_map(function($r) { return (int) $r->lesson_id; }, $rows);
    }

    /**
     * Compute course progress percentage for a user.
     */
    public function get_course_progress_percent($course_id, $user_id)
    {
        $total = $this->get_course_lesson_ids($course_id);
        if (empty($total)) return 0;
        $completed = $this->get_completed_lesson_ids($course_id, $user_id);
        return round((count($completed) / count($total)) * 100);
    }

    /**
     * Check if a lesson is accessible (all prior lessons in course must be completed).
     */
    public function is_lesson_accessible($lesson_id, $course_id, $user_id)
    {
        $ordered = $this->get_course_lesson_ids($course_id);
        $completed = $this->get_completed_lesson_ids($course_id, $user_id);

        foreach ($ordered as $lid) {
            if ($lid == $lesson_id) return true; // reached the target — all prior are done
            if (!in_array($lid, $completed)) return false; // prior lesson not completed
        }
        return true;
    }

    public function get_subject_lesson_ids($subject_id, $published_only = true)
    {
        $this->db->select('lessons.id')
                 ->join('modules', 'modules.id = lessons.module_id')
                 ->where('modules.subject_id', $subject_id);

        if ($published_only) {
            $this->db->where('lessons.is_published', 1)
                     ->where('modules.is_published', 1);
        }

        $rows = $this->db->order_by('modules.order_num', 'ASC')
                         ->order_by('lessons.order_num', 'ASC')
                         ->get('lessons');
        return array_map(function($r) { return (int) $r->id; }, $rows);
    }

    public function get_completed_lesson_ids_by_subject($subject_id, $student_id)
    {
        $this->db->select('lesson_completions.lesson_id');
        $this->db->from('lesson_completions');
        $this->db->join('lessons', 'lessons.id = lesson_completions.lesson_id');
        $this->db->join('modules', 'modules.id = lessons.module_id');
        $this->db->where('lesson_completions.student_id', $student_id);
        $this->db->where('modules.subject_id', $subject_id);
        $result = $this->db->get()->result();
        
        $ids = array();
        foreach ($result as $row) {
            $ids[] = (int) $row->lesson_id;
        }
        return $ids;
    }

    public function get_total_completed_lessons($student_id)
    {
        return $this->db->where('student_id', $student_id)
                        ->count_all_results('lesson_completions');
    }

    public function get_subject_progress_percent($subject_id, $user_id)
    {
        $total = $this->get_subject_lesson_ids($subject_id, true);
        if (empty($total)) return 0;

        $completed = $this->get_completed_lesson_ids_by_subject($subject_id, $user_id);
        return round((count($completed) / count($total)) * 100);
    }

    public function is_subject_lesson_accessible($lesson_id, $subject_id, $user_id)
    {
        $ordered = $this->get_subject_lesson_ids($subject_id, true);
        $completed = $this->get_completed_lesson_ids_by_subject($subject_id, $user_id);

        foreach ($ordered as $lid) {
            if ((int) $lid === (int) $lesson_id) return true;
            if (!in_array((int) $lid, $completed)) return false;
        }

        return true;
    }

    /**
     * Get student progress data for a course (for teacher monitoring).
     */
    public function get_course_student_progress($course_id)
    {
        // Get all enrolled students
        $this->db->select('course_enrollments.user_id, CONCAT(u.first_name, " ", u.last_name) as name, u.email', FALSE);
        $this->db->join('users u', 'u.id = course_enrollments.user_id');
        $this->db->where('course_enrollments.course_id', $course_id);
        $this->db->where('course_enrollments.role', 'student');
        $this->db->where('course_enrollments.status', 'active');
        $students = $this->db->get('course_enrollments')->result();

        // Get total lesson count
        $total_lessons = count($this->get_course_lesson_ids($course_id));

        foreach ($students as &$s) {
            $completed = $this->get_completed_lesson_ids($course_id, $s->user_id);
            $s->lessons_completed = count($completed);
            $s->progress_percent = $total_lessons > 0 ? round(($s->lessons_completed / $total_lessons) * 100) : 0;
            $s->completed_ids = $completed;
        }

        return array('students' => $students, 'total_lessons' => $total_lessons);
    }

    // ---- Activities (Moodle-style: assignments, quizzes, forums, etc.) ----
    public function get_activities($module_id)
    {
        return $this->db->where('module_id', $module_id)
                        ->order_by('order_num', 'ASC')
                        ->get('activities')
                        ->result();
    }

    public function get_activity($id)
    {
        return $this->db->where('id', $id)->get('activities')->row();
    }

    public function create_activity($data)
    {
        $this->db->insert('activities', $data);
        return $this->db->insert_id();
    }

    public function update_activity($id, $data)
    {
        return $this->db->where('id', $id)->update('activities', $data);
    }

    public function delete_activity($id)
    {
        return $this->db->where('id', $id)->delete('activities');
    }

    public function reorder_activities($module_id, $activity_ids)
    {
        foreach ($activity_ids as $index => $id) {
            $this->db->where('id', $id)->where('module_id', $module_id)->update('activities', ['order_num' => $index + 1]);
        }
        return true;
    }

    // ---- Lesson Completions (Sequential Access) ----
    public function mark_lesson_completed($student_id, $lesson_id)
    {
        $data = array(
            'student_id' => $student_id,
            'lesson_id' => $lesson_id,
            'completed_at' => date('Y-m-d H:i:s')
        );
        
        $existing = $this->db->where('student_id', $student_id)
                            ->where('lesson_id', $lesson_id)
                            ->get('lesson_completions')
                            ->row();
        
        if ($existing) {
            return true;
        }
        
        $this->db->insert('lesson_completions', $data);
        return $this->db->insert_id();
    }

    public function is_lesson_completed($student_id, $lesson_id)
    {
        return $this->db->where('student_id', $student_id)
                        ->where('lesson_id', $lesson_id)
                        ->count_all_results('lesson_completions') > 0;
    }

    public function get_student_lesson_completions($student_id, $subject_id)
    {
        $this->db->select('lesson_completions.lesson_id, lesson_completions.completed_at');
        $this->db->from('lesson_completions');
        $this->db->join('lessons', 'lessons.id = lesson_completions.lesson_id');
        $this->db->join('modules', 'modules.id = lessons.module_id');
        $this->db->where('lesson_completions.student_id', $student_id);
        $this->db->where('modules.subject_id', $subject_id);
        return $this->db->get()->result();
    }

    public function get_previous_lesson($lesson_id, $module_id)
    {
        $current_lesson = $this->get_lesson($lesson_id);
        if (!$current_lesson) {
            return null;
        }

        return $this->db->where('module_id', $module_id)
                        ->where('order_num <', $current_lesson->order_num)
                        ->order_by('order_num', 'DESC')
                        ->limit(1)
                        ->get('lessons')
                        ->row();
    }

    public function can_access_lesson($student_id, $lesson_id, $module_id)
    {
        $previous_lesson = $this->get_previous_lesson($lesson_id, $module_id);
        
        if (!$previous_lesson) {
            return true;
        }

        return $this->is_lesson_completed($student_id, $previous_lesson->id);
    }
}
