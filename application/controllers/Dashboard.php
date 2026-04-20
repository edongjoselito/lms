<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('auth');
        }
        $this->load->model('Admin_model');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['total_users'] = $this->Admin_model->count_users();
        $data['active_users'] = $this->Admin_model->count_active_users();
        $data['total_admins'] = $this->Admin_model->count_admins();

        $this->load->view('layouts/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('layouts/footer');
    }
}
