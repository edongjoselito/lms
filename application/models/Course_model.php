<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class Course_model extends CI_Model {

    public function get_courses($filters = array())
    {
        $this->db->select('courses.*, CONCAT(u.first_name, " ", u.last_name) as creator_name', FALSE);
        $this->db->join('users u', 'u.id = courses.created_by', 'left');

        if (!empty($filters['school_id'])) {
            $this->db->where('courses.school_id', $filters['school_id']);
        }
        if (!empty($filters['created_by'])) {
            $this->db->where('courses.created_by', $filters['created_by']);
        }
        if (isset($filters['is_published'])) {
            $this->db->where('courses.is_published', $filters['is_published']);
        }
        if (!empty($filters['category'])) {
            $this->db->where('courses.category', $filters['category']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('courses.title', $filters['search']);
            $this->db->or_like('courses.code', $filters['search']);
            $this->db->or_like('courses.description', $filters['search']);
            $this->db->group_end();
        }

        return $this->db->order_by('courses.updated_at', 'DESC')
                        ->get('courses')
                        ->result();
    }

    public function get_course($id)
    {
        return $this->db->select('courses.*, CONCAT(u.first_name, " ", u.last_name) as creator_name', FALSE)
                        ->join('users u', 'u.id = courses.created_by', 'left')
                        ->where('courses.id', $id)
                        ->get('courses')
                        ->row();
    }

    public function create_course($data)
    {
        $this->db->insert('courses', $data);
        return $this->db->insert_id();
    }

    public function update_course($id, $data)
    {
        return $this->db->where('id', $id)->update('courses', $data);
    }

    public function delete_course($id)
    {
        $this->db->where('course_id', $id)->delete('course_enrollments');
        $modules = $this->db->where('course_id', $id)->get('modules')->result();
        foreach ($modules as $m) {
            $this->db->where('module_id', $m->id)->delete('lessons');
        }
        $this->db->where('course_id', $id)->delete('modules');
        $this->db->where('course_id', $id)->delete('quizzes');
        return $this->db->where('id', $id)->delete('courses');
    }

    // ---- Enrollment ----
    public function enroll_user($course_id, $user_id, $role = 'student')
    {
        return $this->db->insert('course_enrollments', array(
            'course_id' => $course_id,
            'user_id' => $user_id,
            'role' => $role,
            'status' => 'active',
            'enrolled_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function unenroll_user($course_id, $user_id)
    {
        return $this->db->where('course_id', $course_id)
                        ->where('user_id', $user_id)
                        ->delete('course_enrollments');
    }

    public function is_enrolled($course_id, $user_id)
    {
        return $this->db->where('course_id', $course_id)
                        ->where('user_id', $user_id)
                        ->where('status', 'active')
                        ->count_all_results('course_enrollments') > 0;
    }

    // ---- Collaborators ----
    public function add_collaborator($course_id, $teacher_id, $section_id = null)
    {
        $this->db->where('course_id', $course_id)->where('teacher_id', $teacher_id)->delete('course_collaborators');
        return $this->db->insert('course_collaborators', array(
            'course_id' => $course_id,
            'teacher_id' => $teacher_id,
            'section_id' => $section_id,
        ));
    }

    public function remove_collaborator($course_id, $teacher_id)
    {
        return $this->db->where('course_id', $course_id)->where('teacher_id', $teacher_id)->delete('course_collaborators');
    }

    public function get_collaborators($course_id)
    {
        return $this->db->select('course_collaborators.*, CONCAT(u.first_name, " ", u.last_name) as name, u.email, s.name as section_name', FALSE)
                        ->join('users u', 'u.id = course_collaborators.teacher_id')
                        ->join('roles r', 'r.id = u.role_id')
                        ->join('sections s', 's.id = course_collaborators.section_id', 'left')
                        ->where('course_collaborators.course_id', $course_id)
                        ->where('r.slug', 'teacher')
                        ->get('course_collaborators')->result();
    }

    public function is_collaborator($course_id, $teacher_id)
    {
        return $this->db->where('course_id', $course_id)
                        ->where('teacher_id', $teacher_id)
                        ->count_all_results('course_collaborators') > 0;
    }

    public function get_collaborator_sections($course_id, $teacher_id)
    {
        $collab = $this->db->select('section_id')
                         ->where('course_id', $course_id)
                         ->where('teacher_id', $teacher_id)
                         ->get('course_collaborators')
                         ->row();
        return $collab ? $collab->section_id : null;
    }

    // ---- Section Enrollment Keys ----
    public function set_section_enrollment_key($course_id, $section_id, $key)
    {
        $this->db->where('course_id', $course_id)->where('section_id', $section_id)->delete('section_enrollment_keys');
        return $this->db->insert('section_enrollment_keys', array(
            'course_id' => $course_id,
            'section_id' => $section_id,
            'enrollment_key' => $key,
        ));
    }

    public function get_section_enrollment_key($course_id, $section_id)
    {
        $row = $this->db->where('course_id', $course_id)->where('section_id', $section_id)->get('section_enrollment_keys')->row();
        return $row ? $row->enrollment_key : null;
    }

    public function get_all_section_keys($course_id)
    {
        return $this->db->select('section_enrollment_keys.*, s.name as section_name', FALSE)
                        ->join('sections s', 's.id = section_enrollment_keys.section_id')
                        ->where('section_enrollment_keys.course_id', $course_id)
                        ->get('section_enrollment_keys')->result();
    }

    public function get_enrollments($course_id, $role = null, $section_id = null)
    {
        $this->db->select('course_enrollments.*, CONCAT(u.first_name, " ", u.last_name) as name, u.email, u.section_id, r.name as role_name', FALSE);
        $this->db->join('users u', 'u.id = course_enrollments.user_id');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->where('course_enrollments.course_id', $course_id);
        $this->db->where('course_enrollments.status', 'active');
        if ($role) {
            $this->db->where('course_enrollments.role', $role);
        }
        if ($section_id) {
            $this->db->where('u.section_id', $section_id);
        }
        return $this->db->order_by('course_enrollments.created_at', 'DESC')->get('course_enrollments')->result();
    }

    public function get_user_courses($user_id, $role = null, $school_id = null)
    {
        $this->db->select('courses.*, course_enrollments.role as enrollment_role, CONCAT(u.first_name, " ", u.last_name) as creator_name', FALSE);
        $this->db->join('courses', 'courses.id = course_enrollments.course_id');
        $this->db->join('users u', 'u.id = courses.created_by', 'left');
        $this->db->where('course_enrollments.user_id', $user_id);
        $this->db->where('course_enrollments.status', 'active');
        if ($role) {
            $this->db->where('course_enrollments.role', $role);
        }
        if ($school_id) {
            $this->db->where('courses.school_id', $school_id);
        }
        return $this->db->order_by('courses.title', 'ASC')
                        ->get('course_enrollments')
                        ->result();
    }

    public function count_enrolled($course_id, $role = 'student')
    {
        return $this->db->where('course_id', $course_id)
                        ->where('role', $role)
                        ->where('status', 'active')
                        ->count_all_results('course_enrollments');
    }

    public function get_available_courses($user_id, $school_id = null)
    {
        $enrolled_ids = $this->db->select('course_id')
                                 ->where('user_id', $user_id)
                                 ->where('status', 'active')
                                 ->get('course_enrollments')
                                 ->result();
        $exclude = array_map(function($e) { return $e->course_id; }, $enrolled_ids);

        $this->db->select('courses.*, CONCAT(u.first_name, " ", u.last_name) as creator_name', FALSE);
        $this->db->join('users u', 'u.id = courses.created_by', 'left');
        $this->db->where('courses.is_published', 1);
        if ($school_id) {
            $this->db->where('courses.school_id', $school_id);
        }
        if (!empty($exclude)) {
            $this->db->where_not_in('courses.id', $exclude);
        }
        return $this->db->order_by('courses.title', 'ASC')->get('courses')->result();
    }

    public function get_available_students($course_id, $school_id = null, $section_id = null)
    {
        $this->db->select('users.id, CONCAT(users.first_name, " ", users.last_name) as name, users.email', FALSE);
        $this->db->join('roles', 'roles.id = users.role_id');
        $this->db->where('roles.slug', 'student');
        $this->db->where('users.status', 1);
        if ($school_id) {
            $this->db->where('users.school_id', $school_id);
        }
        if ($section_id) {
            $this->db->where('users.section_id', $section_id);
        }
        $enrolled_ids = $this->db->select('user_id')
                            ->where('course_id', $course_id)
                            ->where('status', 'active')
                            ->get('course_enrollments')
                            ->result_array();
        $enrolled_ids = array_column($enrolled_ids, 'user_id');
        if (!empty($enrolled_ids)) {
            $this->db->where_not_in('users.id', $enrolled_ids);
        }
        return $this->db->order_by('users.last_name, users.first_name')->get('users')->result();
    }
}
