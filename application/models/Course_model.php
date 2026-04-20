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
        $existing = $this->db->where('course_id', $course_id)
                             ->where('user_id', $user_id)
                             ->get('course_enrollments')->row();
        if ($existing) {
            return $this->db->where('id', $existing->id)->update('course_enrollments', array('status' => 'active'));
        }
        $this->db->insert('course_enrollments', array(
            'course_id' => $course_id,
            'user_id'   => $user_id,
            'role'      => $role,
            'status'    => 'active',
        ));
        return $this->db->insert_id();
    }

    public function unenroll_user($course_id, $user_id)
    {
        return $this->db->where('course_id', $course_id)
                        ->where('user_id', $user_id)
                        ->update('course_enrollments', array('status' => 'dropped'));
    }

    public function get_enrollments($course_id, $role = null)
    {
        $this->db->select('course_enrollments.*, CONCAT(u.first_name, " ", u.last_name) as name, u.email, r.name as role_name', FALSE);
        $this->db->join('users u', 'u.id = course_enrollments.user_id');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->where('course_enrollments.course_id', $course_id);
        $this->db->where('course_enrollments.status', 'active');
        if ($role) {
            $this->db->where('course_enrollments.role', $role);
        }
        return $this->db->order_by('u.last_name', 'ASC')->get('course_enrollments')->result();
    }

    public function is_enrolled($course_id, $user_id)
    {
        return $this->db->where('course_id', $course_id)
                        ->where('user_id', $user_id)
                        ->where('status', 'active')
                        ->count_all_results('course_enrollments') > 0;
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

    public function get_available_students($course_id, $school_id = null)
    {
        $enrolled_ids = $this->db->select('user_id')
                                 ->where('course_id', $course_id)
                                 ->where('status', 'active')
                                 ->get('course_enrollments')
                                 ->result();
        $exclude = array_map(function($e) { return $e->user_id; }, $enrolled_ids);

        $this->db->select('users.id, users.first_name, users.last_name, users.email');
        $this->db->join('roles', 'roles.id = users.role_id');
        $this->db->where('users.status', 1);
        $this->db->where_in('roles.slug', array('student'));
        if ($school_id) {
            $this->db->where('users.school_id', $school_id);
        }
        if (!empty($exclude)) {
            $this->db->where_not_in('users.id', $exclude);
        }
        return $this->db->order_by('users.last_name', 'ASC')->get('users')->result();
    }
}
