<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schools extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model('School_model');
        $this->load->model('Audit_model');
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

            // Create school admin user account
            $school_admin_role = $this->db->where('slug', 'school_admin')->get('roles')->row();
            if ($school_admin_role) {
                $school_name = $this->input->post('name', TRUE);
                $school_email = $this->input->post('email', TRUE);
                
                // Generate email if not provided
                if (empty($school_email)) {
                    $school_email = 'admin@' . strtolower(preg_replace('/[^a-z0-9]/', '', $school_name)) . '.lms';
                }

                // Generate default password (school name in lowercase)
                $default_password = strtolower(str_replace(' ', '', $school_name)) . '123';

                $this->db->insert('users', array(
                    'first_name' => 'School',
                    'last_name'  => 'Admin',
                    'email'      => $school_email,
                    'password'   => password_hash($default_password, PASSWORD_DEFAULT),
                    'role_id'    => $school_admin_role->id,
                    'school_id'  => $school_id,
                    'status'     => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ));

                // Send email with password
                $this->load->library('email');
                $this->email->from('berps@softtechservices.net', 'LMS Portal');
                $this->email->to($school_email);
                $this->email->subject('Your LMS School Admin Account');
                
                $message = $this->load->view('emails/school_admin_password', array(
                    'school_name' => $school_name,
                    'email' => $school_email,
                    'password' => $default_password
                ), true);
                
                $this->email->message($message);
                $this->email->send();
            }

            // Audit log
            $this->Audit_model->log('create', 'school', $school_id, $d['name'], 'Created school: ' . $d['name'] . ' (' . $d['type'] . ')');

            $this->session->set_flashdata('success', 'School created successfully. A school admin account has been created automatically.');
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

            // Audit log
            $this->Audit_model->log('update', 'school', $id, $d['name'], 'Updated school: ' . $d['name']);

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
        $school = $this->School_model->get($id);
        $school_name = $school ? $school->name : 'Unknown';
        
        $this->School_model->delete($id);

        // Audit log
        $this->Audit_model->log('delete', 'school', $id, $school_name, 'Deleted school: ' . $school_name);

        $this->session->set_flashdata('success', 'School deleted.');
        redirect('schools');
    }
}
