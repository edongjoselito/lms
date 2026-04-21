<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $this->load->view('auth/login');
    }

    public function login()
    {
        if ($this->input->method() !== 'post') {
            redirect('auth');
        }

        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password');

        $user = $this->User_model->authenticate($email, $password);

        if ($user) {
            $session_data = array(
                'user_id'    => $user->id,
                'role_id'    => $user->role_id,
                'role_slug'  => $user->role_slug,
                'role_name'  => $user->role_name,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'school_id'  => $user->school_id,
                'logged_in'  => TRUE
            );

            // Load school name if user belongs to a school
            if ($user->school_id) {
                $school = $this->db->where('id', $user->school_id)->get('schools')->row();
                $session_data['school_name'] = $school ? $school->name : '';
            }

            // Set student_id for students
            if ($user->role_slug === 'student') {
                $student = $this->db->where('user_id', $user->id)->get('students')->row();
                if ($student) {
                    $session_data['student_id'] = $student->id;
                } else {
                    // Try to find student by matching user_id with user table
                    $student_by_id = $this->db->where('id', $user->id)->get('students')->row();
                    if ($student_by_id) {
                        $session_data['student_id'] = $student_by_id->id;
                    }
                }
                $session_data['last_activity'] = time();
            }

            $this->session->set_userdata($session_data);
            $this->User_model->update_last_login($user->id);

            // Track login time for attendance (students only)
            if ($user->role_slug === 'student' && $user->school_id) {
                $this->_track_login($user->id, $user->school_id);
            }

            // Super admin without school → go to school selection
            if ($user->role_slug === 'super_admin' && !$user->school_id) {
                redirect('schools/select');
            }

            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Invalid email or password.');
            redirect('auth');
        }
    }

    public function logout()
    {
        $user_id = $this->session->userdata('user_id');
        $role_slug = $this->session->userdata('role_slug');
        $school_id = $this->session->userdata('school_id');

        // Track logout time for attendance (students only)
        if ($role_slug === 'student' && $school_id) {
            $this->_track_logout($user_id, $school_id);
        }

        $this->session->sess_destroy();
        redirect('auth');
    }

    private function _track_login($user_id, $school_id)
    {
        $today = date('Y-m-d');

        // Check if there's already an attendance record for today with course_id=0
        $existing = $this->db->where('user_id', $user_id)
                             ->where('course_id', 0)
                             ->where('date', $today)
                             ->get('attendance')->row();

        if ($existing) {
            // Update existing record with new login time
            $this->db->where('id', $existing->id)->update('attendance', array(
                'login_time' => date('Y-m-d H:i:s'),
                'logout_time' => null,
                'duration_minutes' => 0
            ));
        } else {
            // Create new attendance record for login
            $this->db->insert('attendance', array(
                'user_id' => $user_id,
                'course_id' => 0, // 0 means general LMS access, not course-specific
                'date' => $today,
                'login_time' => date('Y-m-d H:i:s'),
                'duration_minutes' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ));
        }
    }

    private function _track_logout($user_id, $school_id)
    {
        $today = date('Y-m-d');

        // Find today's attendance record without logout time
        $att = $this->db->where('user_id', $user_id)
                        ->where('date', $today)
                        ->where('logout_time IS NULL')
                        ->order_by('id', 'DESC')
                        ->limit(1)
                        ->get('attendance')->row();

        if ($att && $att->login_time) {
            $login = strtotime($att->login_time);
            $logout = strtotime(date('Y-m-d H:i:s'));
            $duration_minutes = round(($logout - $login) / 60);

            // Update attendance with logout time and duration
            $this->db->where('id', $att->id)->update('attendance', array(
                'logout_time' => date('Y-m-d H:i:s'),
                'duration_minutes' => $duration_minutes
            ));
        }
    }

    public function forgot_password()
    {
        if ($this->input->method() === 'post') {
            $email = $this->input->post('email', TRUE);
            $user = $this->db->where('email', $email)->get('users')->row();

            if ($user) {
                // For now, just show a success message
                // In production, you would send an email with a reset link
                $this->session->set_flashdata('success', 'Password reset instructions have been sent to your email.');
            } else {
                $this->session->set_flashdata('error', 'Email not found.');
            }
            redirect('auth/forgot_password');
        }

        $this->load->view('auth/forgot_password');
    }
}
