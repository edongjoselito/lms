<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class Lesson_model extends CI_Model {

    private $lesson_taught_statuses_table_exists = null;

    private function ensure_learning_competency_column()
    {
        if (!$this->db->field_exists('learning_competency_id', 'lessons')) {
            $this->db->query("ALTER TABLE `lessons` ADD COLUMN `learning_competency_id` int(11) UNSIGNED DEFAULT NULL AFTER `module_id`, ADD KEY `idx_lesson_learning_competency` (`learning_competency_id`)");
        }
    }

    private function ensure_taught_at_column()
    {
        if (!$this->db->field_exists('taught_at', 'lessons')) {
            $this->db->query("ALTER TABLE `lessons` ADD COLUMN `taught_at` datetime DEFAULT NULL AFTER `is_published`");
        }
    }

    private function ensure_lesson_taught_statuses_table()
    {
        if ($this->lesson_taught_statuses_table_exists === true) {
            return;
        }

        if ($this->db->table_exists('lesson_taught_statuses')) {
            $this->lesson_taught_statuses_table_exists = true;
            return;
        }

        $this->db->query("CREATE TABLE IF NOT EXISTS `lesson_taught_statuses` (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `lesson_id` int(11) UNSIGNED NOT NULL,
            `user_id` int(11) UNSIGNED NOT NULL,
            `taught_at` datetime NOT NULL,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_lesson_user_taught` (`lesson_id`,`user_id`),
            KEY `idx_lesson_taught_user` (`user_id`),
            CONSTRAINT `fk_lesson_taught_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_lesson_taught_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->lesson_taught_statuses_table_exists = true;
    }

    private function ensure_lesson_schema()
    {
        $this->ensure_learning_competency_column();
        $this->ensure_taught_at_column();
        $this->ensure_lesson_taught_statuses_table();
    }

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
        $this->ensure_lesson_schema();
        return $this->db->where('module_id', $module_id)
                        ->order_by('order_num', 'ASC')
                        ->get('lessons')
                        ->result();
    }

    public function get_shared_grade_level_lessons($current_subject_id, $year_level, $exclude_school_id = null)
    {
        $this->ensure_lesson_schema();

        if ($year_level === null || $year_level === '') {
            return array();
        }

        $this->db->select('lessons.*,
                           modules.id as shared_module_id,
                           modules.title as module_title,
                           modules.description as module_description,
                           modules.order_num as module_order_num,
                           modules.created_by as module_created_by,
                           subjects.id as source_subject_id,
                           subjects.code as source_subject_code,
                           subjects.description as source_subject_description,
                           subjects.school_id as source_school_id,
                           schools.name as source_school_name,
                           CONCAT(users.first_name, " ", users.last_name) as owner_name,
                           roles.slug as owner_role_slug', FALSE);
        $this->db->from('lessons');
        $this->db->join('modules', 'modules.id = lessons.module_id');
        $this->db->join('subjects', 'subjects.id = modules.subject_id');
        $this->db->join('users', 'users.id = modules.created_by', 'left');
        $this->db->join('roles', 'roles.id = users.role_id', 'left');
        $this->db->join('schools', 'schools.id = subjects.school_id', 'left');
        $this->db->where('subjects.id !=', (int) $current_subject_id);
        $this->db->where('subjects.status', 1);
        $this->db->where('subjects.year_level', $year_level);
        $this->db->where('modules.is_published', 1);
        $this->db->where('lessons.is_published', 1);
        $this->db->where('roles.slug', 'school_admin');

        if ($exclude_school_id !== null) {
            $this->db->where('subjects.school_id !=', (int) $exclude_school_id);
        }

        return $this->db->order_by('schools.name', 'ASC')
            ->order_by('subjects.code', 'ASC')
            ->order_by('modules.order_num', 'ASC')
            ->order_by('lessons.order_num', 'ASC')
            ->get()
            ->result();
    }

    public function get_lessons_by_class($class_program_id)
    {
        $this->ensure_lesson_schema();
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
        $this->ensure_lesson_schema();
        return $this->db->select('lessons.*, modules.title as module_title, modules.class_program_id')
                        ->join('modules', 'modules.id = lessons.module_id')
                        ->where('lessons.id', $id)
                        ->get('lessons')
                        ->row();
    }

    public function create_lesson($data)
    {
        $this->ensure_lesson_schema();
        $this->db->insert('lessons', $data);
        return $this->db->insert_id();
    }

    public function update_lesson($id, $data)
    {
        $this->ensure_lesson_schema();
        return $this->db->where('id', $id)->update('lessons', $data);
    }

    public function get_lesson_taught_status($lesson_id, $user_id)
    {
        $this->ensure_lesson_schema();
        $lesson_id = (int) $lesson_id;
        $user_id = (int) $user_id;

        if ($lesson_id < 1 || $user_id < 1) {
            return null;
        }

        return $this->db->where('lesson_id', $lesson_id)
            ->where('user_id', $user_id)
            ->get('lesson_taught_statuses')
            ->row();
    }

    public function get_lesson_taught_map($lesson_ids, $user_id)
    {
        $this->ensure_lesson_schema();
        $user_id = (int) $user_id;
        $lesson_ids = array_values(array_unique(array_filter(array_map('intval', (array) $lesson_ids))));

        if ($user_id < 1 || empty($lesson_ids)) {
            return array();
        }

        $rows = $this->db->select('lesson_id, taught_at')
            ->where('user_id', $user_id)
            ->where_in('lesson_id', $lesson_ids)
            ->get('lesson_taught_statuses')
            ->result();

        $map = array();
        foreach ($rows as $row) {
            $map[(int) $row->lesson_id] = (string) $row->taught_at;
        }

        return $map;
    }

    private function save_lesson_taught_status($lesson_id, $user_id, $taught_at)
    {
        $this->ensure_lesson_schema();
        $lesson_id = (int) $lesson_id;
        $user_id = (int) $user_id;

        if ($lesson_id < 1 || $user_id < 1 || empty($taught_at)) {
            return false;
        }

        $existing = $this->get_lesson_taught_status($lesson_id, $user_id);
        $data = array(
            'taught_at' => $taught_at,
        );

        if ($existing) {
            return $this->db->where('id', (int) $existing->id)
                ->update('lesson_taught_statuses', $data);
        }

        $data['lesson_id'] = $lesson_id;
        $data['user_id'] = $user_id;
        return $this->db->insert('lesson_taught_statuses', $data);
    }

    public function mark_lesson_taught($lesson_id, $user_id, $taught_at = null)
    {
        $taught_at = $taught_at ? (string) $taught_at : date('Y-m-d H:i:s');
        return $this->save_lesson_taught_status($lesson_id, $user_id, $taught_at);
    }

    public function clear_lesson_taught($lesson_id, $user_id)
    {
        $this->ensure_lesson_schema();
        return $this->db->where('lesson_id', (int) $lesson_id)
            ->where('user_id', (int) $user_id)
            ->delete('lesson_taught_statuses');
    }

    public function update_lesson_taught_date($lesson_id, $user_id, $taught_at)
    {
        $this->ensure_lesson_schema();
        $existing = $this->get_lesson_taught_status($lesson_id, $user_id);
        if (!$existing) {
            return false;
        }

        return $this->db->where('id', (int) $existing->id)->update('lesson_taught_statuses', array(
            'taught_at' => $taught_at,
        ));
    }

    public function get_subject_learning_competency_progress($subject_id, $user_id = null)
    {
        $this->ensure_lesson_schema();

        $user_id = (int) $user_id;
        $this->db->from('lessons');
        $this->db->join('modules', 'modules.id = lessons.module_id');

        if ($user_id > 0) {
            $this->db->select('lessons.learning_competency_id, COUNT(lessons.id) AS total_lessons, SUM(CASE WHEN lts.taught_at IS NOT NULL THEN 1 ELSE 0 END) AS taught_lessons, MAX(lts.taught_at) AS latest_taught_at', false);
            $this->db->join('lesson_taught_statuses lts', 'lts.lesson_id = lessons.id AND lts.user_id = ' . $user_id, 'left');
        } else {
            $this->db->select('lessons.learning_competency_id, COUNT(lessons.id) AS total_lessons, SUM(CASE WHEN lessons.taught_at IS NOT NULL THEN 1 ELSE 0 END) AS taught_lessons, MAX(lessons.taught_at) AS latest_taught_at', false);
        }

        $rows = $this->db->where('modules.subject_id', (int) $subject_id)
            ->where('lessons.learning_competency_id IS NOT NULL', null, false)
            ->group_by('lessons.learning_competency_id')
            ->get()
            ->result();

        $progress = array();
        foreach ($rows as $row) {
            $competency_id = (int) $row->learning_competency_id;
            if ($competency_id < 1) {
                continue;
            }

            $progress[$competency_id] = array(
                'total_lessons' => (int) $row->total_lessons,
                'taught_lessons' => (int) $row->taught_lessons,
                'latest_taught_at' => !empty($row->latest_taught_at) ? (string) $row->latest_taught_at : null,
            );
        }

        return $progress;
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

    public function get_next_content_order($module_id)
    {
        $lesson_row = $this->db->select_max('order_num')
            ->where('module_id', $module_id)
            ->get('lessons')
            ->row();

        $activity_row = $this->db->select_max('order_num')
            ->where('module_id', $module_id)
            ->get('activities')
            ->row();

        $lesson_max = ($lesson_row && $lesson_row->order_num) ? (int) $lesson_row->order_num : 0;
        $activity_max = ($activity_row && $activity_row->order_num) ? (int) $activity_row->order_num : 0;

        return max($lesson_max, $activity_max) + 1;
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
                         ->get('lessons')
                         ->result();
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

    public function reorder_module_content($module_id, $items)
    {
        $lesson_ids = array_map('intval', array_map(function ($row) {
            return $row->id;
        }, $this->db->select('id')->where('module_id', $module_id)->get('lessons')->result()));

        $activity_ids = array_map('intval', array_map(function ($row) {
            return $row->id;
        }, $this->db->select('id')->where('module_id', $module_id)->get('activities')->result()));

        $expected_count = count($lesson_ids) + count($activity_ids);
        if ($expected_count !== count($items)) {
            return false;
        }

        $lesson_lookup = array_fill_keys($lesson_ids, true);
        $activity_lookup = array_fill_keys($activity_ids, true);
        $seen = array();

        $this->db->trans_begin();

        foreach ($items as $index => $item) {
            $item_id = isset($item['id']) ? (int) $item['id'] : 0;
            $item_type = isset($item['item_type']) ? (string) $item['item_type'] : '';
            $item_key = $item_type . ':' . $item_id;

            if ($item_id < 1 || isset($seen[$item_key])) {
                $this->db->trans_rollback();
                return false;
            }

            if ($item_type === 'lesson' && isset($lesson_lookup[$item_id])) {
                $this->db->where('id', $item_id)
                    ->where('module_id', $module_id)
                    ->update('lessons', array('order_num' => $index + 1));
            } elseif ($item_type === 'activity' && isset($activity_lookup[$item_id])) {
                $this->db->where('id', $item_id)
                    ->where('module_id', $module_id)
                    ->update('activities', array('order_num' => $index + 1));
            } else {
                $this->db->trans_rollback();
                return false;
            }

            $seen[$item_key] = true;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    public function reorder_subject_modules($subject_id, $module_ids)
    {
        $existing_ids = array_map('intval', array_map(function ($row) {
            return $row->id;
        }, $this->db->select('id')->where('subject_id', $subject_id)->get('modules')->result()));

        if (count($existing_ids) !== count($module_ids)) {
            return false;
        }

        $existing_lookup = array_fill_keys($existing_ids, true);
        $seen = array();

        $this->db->trans_begin();

        foreach ($module_ids as $index => $module_id) {
            $module_id = (int) $module_id;

            if ($module_id < 1 || isset($seen[$module_id]) || !isset($existing_lookup[$module_id])) {
                $this->db->trans_rollback();
                return false;
            }

            $this->db->where('id', $module_id)
                ->where('subject_id', $subject_id)
                ->update('modules', array('order_num' => $index + 1));

            $seen[$module_id] = true;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
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

    public function get_lesson_completions($lesson_id, $student_ids = null)
    {
        if (is_array($student_ids)) {
            $student_ids = array_values(array_unique(array_map('intval', array_filter($student_ids))));
            if (empty($student_ids)) {
                return array();
            }
        }

        $this->db->select('lesson_completions.completed_at, users.id as user_id, CONCAT(users.first_name, " ", users.last_name) as name, users.email', FALSE);
        $this->db->from('lesson_completions');
        $this->db->join('students', 'students.id = lesson_completions.student_id');
        $this->db->join('users', 'users.id = students.user_id');
        $this->db->where('lesson_completions.lesson_id', $lesson_id);

        if (is_array($student_ids)) {
            $this->db->where_in('lesson_completions.student_id', $student_ids);
        }

        return $this->db
            ->order_by('lesson_completions.completed_at', 'DESC')
            ->get()
            ->result();
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

    // ---- Lesson Plans (ILAW Template) ----
    private function ensure_lesson_plans_table()
    {
        $checkTable = $this->db->query("SHOW TABLES LIKE 'lesson_plans'")->num_rows();
        if ($checkTable == 0) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `lesson_plans` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `lesson_id` int(11) DEFAULT NULL,
                  `module_id` int(11) DEFAULT NULL,
                  `school_id` int(11) DEFAULT NULL,
                  `objectives` text,
                  `subject_matter` text,
                  `materials` text,
                  `procedures` text,
                  `evaluation` text,
                  `assignment` text,
                  `remarks` text,
                  `created_by` int(11) UNSIGNED DEFAULT NULL,
                  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  KEY `lesson_id` (`lesson_id`),
                  KEY `module_id` (`module_id`),
                  KEY `school_id` (`school_id`),
                  KEY `created_by` (`created_by`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }

        if (!$this->db->field_exists('module_id', 'lesson_plans')) {
            $this->db->query("ALTER TABLE `lesson_plans` ADD COLUMN `module_id` int(11) DEFAULT NULL AFTER `lesson_id`, ADD KEY `module_id` (`module_id`)");
        }

        $lessonIdColumn = $this->db->query("SHOW COLUMNS FROM `lesson_plans` LIKE 'lesson_id'")->row();
        if ($lessonIdColumn && isset($lessonIdColumn->Null) && strtoupper($lessonIdColumn->Null) === 'NO') {
            $this->db->query("ALTER TABLE `lesson_plans` MODIFY `lesson_id` int(11) DEFAULT NULL");
        }
    }

    public function get_lesson_plan($lesson_id)
    {
        $this->ensure_lesson_plans_table();
        return $this->db->where('lesson_id', $lesson_id)->get('lesson_plans')->row();
    }

    public function get_module_lesson_plan($module_id)
    {
        $this->ensure_lesson_plans_table();
        return $this->db->where('module_id', $module_id)
                        ->where('lesson_id IS NULL', null, false)
                        ->get('lesson_plans')
                        ->row();
    }

    public function get_lesson_plan_by_id($id)
    {
        $this->ensure_lesson_plans_table();
        return $this->db->where('id', $id)->get('lesson_plans')->row();
    }

    public function create_lesson_plan($data)
    {
        $this->ensure_lesson_plans_table();
        $this->db->insert('lesson_plans', $data);
        return $this->db->insert_id();
    }

    public function update_lesson_plan($id, $data)
    {
        $this->ensure_lesson_plans_table();
        $this->db->where('id', $id)->update('lesson_plans', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_lesson_plan($id)
    {
        $this->ensure_lesson_plans_table();
        $this->db->where('id', $id)->delete('lesson_plans');
        return $this->db->affected_rows() > 0;
    }

    // ---- Lesson Notes ----
    private function ensure_lesson_notes_table()
    {
        $checkTable = $this->db->query("SHOW TABLES LIKE 'lesson_notes'")->num_rows();
        if ($checkTable == 0) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `lesson_notes` (
                  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `lesson_id` int(11) UNSIGNED NOT NULL,
                  `school_id` int(11) UNSIGNED DEFAULT NULL,
                  `note_text` text NOT NULL,
                  `created_by` int(11) UNSIGNED DEFAULT NULL,
                  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  KEY `lesson_id` (`lesson_id`),
                  KEY `school_id` (`school_id`),
                  KEY `created_by` (`created_by`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }
    }

    public function get_lesson_notes($lesson_id, $school_id = null)
    {
        $this->ensure_lesson_notes_table();
        $this->db->select('lesson_notes.*, CONCAT(TRIM(COALESCE(users.first_name, "")), " ", TRIM(COALESCE(users.last_name, ""))) AS creator_name', false);
        $this->db->from('lesson_notes');
        $this->db->join('users', 'users.id = lesson_notes.created_by', 'left');
        $this->db->where('lesson_notes.lesson_id', (int) $lesson_id);

        if ($school_id !== null) {
            $this->db->where('lesson_notes.school_id', (int) $school_id);
        }

        return $this->db->order_by('lesson_notes.updated_at', 'DESC')
            ->order_by('lesson_notes.id', 'DESC')
            ->get()
            ->result();
    }

    public function get_lesson_note($id, $school_id = null)
    {
        $this->ensure_lesson_notes_table();
        $this->db->select('lesson_notes.*, CONCAT(TRIM(COALESCE(users.first_name, "")), " ", TRIM(COALESCE(users.last_name, ""))) AS creator_name', false);
        $this->db->from('lesson_notes');
        $this->db->join('users', 'users.id = lesson_notes.created_by', 'left');
        $this->db->where('lesson_notes.id', (int) $id);

        if ($school_id !== null) {
            $this->db->where('lesson_notes.school_id', (int) $school_id);
        }

        return $this->db->get()->row();
    }

    public function create_lesson_note($data)
    {
        $this->ensure_lesson_notes_table();
        $this->db->insert('lesson_notes', $data);
        return $this->db->insert_id();
    }

    public function update_lesson_note($id, $data, $school_id = null)
    {
        $this->ensure_lesson_notes_table();
        $this->db->where('id', (int) $id);

        if ($school_id !== null) {
            $this->db->where('school_id', (int) $school_id);
        }

        $this->db->update('lesson_notes', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_lesson_note($id, $school_id = null)
    {
        $this->ensure_lesson_notes_table();
        $this->db->where('id', (int) $id);

        if ($school_id !== null) {
            $this->db->where('school_id', (int) $school_id);
        }

        $this->db->delete('lesson_notes');
        return $this->db->affected_rows() > 0;
    }
}
