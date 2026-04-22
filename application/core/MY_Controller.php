<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class MY_Controller extends CI_Controller {

    protected $current_user = null;
    protected $role_slug = null;
    protected $school_id = null;
    protected $current_school = null;
    protected $is_student_mode = false;
    protected $original_role_slug = null;

    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('logged_in')) {
            $this->current_user = (object) array(
                'id'         => $this->session->userdata('user_id'),
                'role_id'    => $this->session->userdata('role_id'),
                'role_slug'  => $this->session->userdata('role_slug'),
                'role_name'  => $this->session->userdata('role_name'),
                'first_name' => $this->session->userdata('first_name'),
                'last_name'  => $this->session->userdata('last_name'),
                'email'      => $this->session->userdata('email'),
                'school_id'  => $this->session->userdata('school_id'),
            );
            $this->original_role_slug = $this->session->userdata('role_slug');
            $this->role_slug = $this->session->userdata('role_slug');
            $this->school_id = $this->session->userdata('school_id');

            // Check if teacher is in student mode
            if ($this->session->userdata('student_mode') && $this->session->userdata('student_mode') === true) {
                $this->is_student_mode = true;
                $this->role_slug = 'student'; // Override role for student mode
            }

            // Load current school info
            if ($this->school_id) {
                $this->current_school = $this->db->where('id', $this->school_id)->get('schools')->row();
            }

            // Auto-logout students using configured session lifetime (only for actual students, not teachers in student mode)
            if ($this->role_slug === 'student' && !$this->is_student_mode) {
                $last_activity = $this->session->userdata('last_activity');
                $now = time();
                $session_expiration = (int) $this->config->item('sess_expiration');

                if ($session_expiration > 0 && $last_activity && ($now - $last_activity) > $session_expiration) {
                    $this->session->sess_destroy();
                    redirect('auth');
                    return;
                }

                // Update last activity time
                $this->session->set_userdata('last_activity', $now);
            }
        }
    }

    protected function require_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    protected function require_role($roles = array())
    {
        $this->require_login();
        if (!empty($roles) && !in_array($this->role_slug, $roles)) {
            show_error('You do not have permission to access this page.', 403);
        }
    }

    protected function require_school()
    {
        $this->require_login();
        if (!$this->school_id && !$this->is_super_admin()) {
            show_error('No school context. Please contact your administrator.', 403);
        }
        if ($this->is_super_admin() && !$this->school_id) {
            redirect('schools/select');
        }
    }

    protected function is_super_admin()
    {
        return $this->role_slug === 'super_admin';
    }

    protected function is_admin()
    {
        return in_array($this->role_slug, array('super_admin', 'school_admin'));
    }

    protected function is_teacher()
    {
        return $this->role_slug === 'teacher';
    }

    protected function is_student()
    {
        return $this->role_slug === 'student';
    }

    protected function is_registrar()
    {
        return $this->role_slug === 'registrar';
    }

    protected function is_course_creator()
    {
        return $this->role_slug === 'course_creator';
    }

    protected function school_filter($builder = null, $table = null, $allow_null = false)
    {
        if ($this->school_id) {
            $col = $table ? $table . '.school_id' : 'school_id';
            if ($allow_null) {
                $this->db->group_start();
                $this->db->where($col, $this->school_id);
                $this->db->or_where($col, NULL);
                $this->db->group_end();
            } else {
                $this->db->where($col, $this->school_id);
            }
        }
    }

    protected function render($view, $data = array())
    {
        $data['current_user'] = $this->current_user;
        $data['role_slug'] = $this->role_slug;
        $data['school_id'] = $this->school_id;
        $data['current_school'] = $this->current_school;
        $data['is_student_mode'] = $this->is_student_mode;
        $data['original_role_slug'] = $this->original_role_slug;
        $this->load->view('layouts/header', $data);
        $this->load->view($view, $data);
        $this->load->view('layouts/footer', $data);
    }

    protected function toggle_student_mode()
    {
        if (!in_array($this->original_role_slug, array('teacher', 'course_creator'))) {
            show_error('Only teachers and course creators can switch to student mode.', 403);
        }

        if ($this->is_student_mode) {
            $this->session->set_userdata('student_mode', false);
        } else {
            $this->session->set_userdata('student_mode', true);
        }

        redirect($this->input->server('HTTP_REFERER') ?: 'dashboard');
    }
}

class Admin_Controller extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_role(array('super_admin', 'school_admin'));
    }
}

class Staff_Controller extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_role(array('super_admin', 'school_admin', 'registrar'));
    }
}

class Teacher_Controller extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
    }
}

class Student_Controller extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_role(array('student'));
    }
}
