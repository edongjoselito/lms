<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
    }

    public function index()
    {
        $data['title'] = 'Attendance';
        $date = $this->input->get('date') ?: date('Y-m-d');
        $course_id = $this->input->get('course_id');

        // Get students' attendance for the selected date
        $this->db->select('attendance.*, CONCAT(u.first_name, " ", u.last_name) as name, u.email', FALSE);
        $this->db->join('users u', 'u.id = attendance.user_id');
        $this->db->join('roles r', 'r.id = u.role_id');
        $this->db->where('attendance.date', $date);
        if ($this->school_id) {
            $this->db->where('u.school_id', $this->school_id);
        }
        $this->db->where('r.slug', 'student');

        // Filter by course if specified
        if ($course_id) {
            $this->db->join('course_enrollments ce', 'ce.user_id = attendance.user_id AND ce.course_id = ' . $course_id);
            $this->db->where('ce.status', 'active');
            $this->db->where('ce.role', 'student');
        }

        $this->db->order_by('attendance.login_time', 'DESC');
        $records = $this->db->get('attendance')->result();

        $data['records'] = $records;
        $data['date'] = $date;
        $data['course_id'] = $course_id;

        if ($course_id) {
            $course = $this->db->where('id', $course_id)->get('courses')->row();
            $data['course'] = $course;
        }

        $this->render('attendance/index', $data);
    }

    public function student($user_id)
    {
        $data['title'] = 'Student Attendance History';
        $student = $this->db->where('id', $user_id)->get('users')->row();
        if (!$student) show_404();

        $this->db->where('user_id', $user_id);
        $this->db->order_by('date', 'DESC');
        $records = $this->db->get('attendance')->result();

        $data['student'] = $student;
        $data['records'] = $records;
        $this->render('attendance/student', $data);
    }
}
