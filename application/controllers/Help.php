<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
    }

    public function index()
    {
        // Redirect to role-specific guide based on user's role
        $role_slug = $this->role_slug;

        if ($role_slug === 'school_admin') {
            redirect('help/school_admin');
        } elseif ($role_slug === 'teacher') {
            redirect('help/teacher');
        } elseif ($role_slug === 'student') {
            redirect('help/student');
        } else {
            // For other roles (super_admin, registrar), show menu
            $data['title'] = 'User Guide';
            $data['role_slug'] = $role_slug;
            $this->render('help/index', $data);
        }
    }

    public function school_admin()
    {
        $data['title'] = 'School Administrator Guide';
        $this->render('help/school_admin', $data);
    }

    public function teacher()
    {
        $data['title'] = 'Teacher Guide';
        $this->render('help/teacher', $data);
    }

    public function student()
    {
        $data['title'] = 'Student Guide';
        $this->render('help/student', $data);
    }
}
