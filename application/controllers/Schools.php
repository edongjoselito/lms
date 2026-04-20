<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schools extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model('School_model');
    }

    public function index()
    {
        $this->require_role(array('super_admin'));
        $schools = $this->School_model->get_all();
        foreach ($schools as &$s) {
            $s->stats = $this->School_model->get_school_stats($s->id);
        }
        $data['title'] = 'Schools';
        $data['schools'] = $schools;
        $this->render('schools/index', $data);
    }

    public function create()
    {
        $this->require_role(array('super_admin'));

        if ($this->input->method() === 'post') {
            $d = array(
                'name'             => $this->input->post('name', TRUE),
                'school_id_number' => $this->input->post('school_id_number', TRUE),
                'type'             => $this->input->post('type', TRUE),
                'address'          => $this->input->post('address', TRUE),
                'contact_number'   => $this->input->post('contact_number', TRUE),
                'email'            => $this->input->post('email', TRUE),
                'division'         => $this->input->post('division', TRUE),
                'region'           => $this->input->post('region', TRUE),
            );
            $school_id = $this->School_model->create($d);

            // Create default school year
            $this->db->insert('school_years', array(
                'school_id'  => $school_id,
                'year_start' => date('Y'),
                'year_end'   => date('Y') + 1,
                'is_active'  => 1,
            ));

            $this->session->set_flashdata('success', 'School created successfully.');
            redirect('schools');
        }

        $data['title'] = 'Add School';
        $data['school'] = null;
        $this->render('schools/form', $data);
    }

    public function edit($id)
    {
        $this->require_role(array('super_admin'));
        $data['school'] = $this->School_model->get($id);
        if (!$data['school']) show_404();

        if ($this->input->method() === 'post') {
            $d = array(
                'name'             => $this->input->post('name', TRUE),
                'school_id_number' => $this->input->post('school_id_number', TRUE),
                'type'             => $this->input->post('type', TRUE),
                'address'          => $this->input->post('address', TRUE),
                'contact_number'   => $this->input->post('contact_number', TRUE),
                'email'            => $this->input->post('email', TRUE),
                'division'         => $this->input->post('division', TRUE),
                'region'           => $this->input->post('region', TRUE),
                'status'           => $this->input->post('status') ? 1 : 0,
            );
            $this->School_model->update($id, $d);
            $this->session->set_flashdata('success', 'School updated.');
            redirect('schools');
        }

        $data['title'] = 'Edit School';
        $this->render('schools/form', $data);
    }

    public function select()
    {
        $this->require_role(array('super_admin'));
        $data['title'] = 'Select School';
        $data['schools'] = $this->School_model->get_all();
        $this->render('schools/select', $data);
    }

    public function switch_school($id)
    {
        // Super admin can switch to any school
        if ($this->is_super_admin()) {
            $school = $this->School_model->get($id);
            if ($school) {
                $this->session->set_userdata('school_id', $school->id);
                $this->session->set_userdata('school_name', $school->name);
                $this->session->set_flashdata('success', 'Switched to: ' . $school->name);
            }
        }
        redirect('dashboard');
    }

    public function switch_to_platform()
    {
        if ($this->is_super_admin()) {
            $this->session->unset_userdata('school_id');
            $this->session->unset_userdata('school_name');
            $this->session->set_flashdata('success', 'Switched to platform view.');
        }
        redirect('schools');
    }

    public function delete($id)
    {
        $this->require_role(array('super_admin'));
        $this->School_model->delete($id);
        $this->session->set_flashdata('success', 'School deleted.');
        redirect('schools');
    }
}
