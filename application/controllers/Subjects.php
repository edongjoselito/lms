<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subjects extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_role(array('super_admin', 'school_admin', 'course_creator'));
        $this->require_school();
        $this->load->model(array('Academic_model', 'Audit_model'));
    }

    public function index()
    {
        $filters = array();
        if ($this->input->get('program_id')) {
            $filters['program_id'] = $this->input->get('program_id');
        }
        if ($this->input->get('grade_level_id')) {
            $filters['grade_level_id'] = $this->input->get('grade_level_id');
        }
        if ($this->input->get('semester_type')) {
            $filters['semester_type'] = $this->input->get('semester_type');
        }
        
        $data['title'] = 'Subjects';
        $data['subjects'] = $this->Academic_model->get_subjects($filters);
        $data['programs'] = $this->Academic_model->get_programs();
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $data['filter_program'] = $this->input->get('program_id');
        $data['filter_grade_level'] = $this->input->get('grade_level_id');
        $data['filter_semester'] = $this->input->get('semester_type');
        $this->render('subjects/index', $data);
    }

    public function create()
    {
        if ($this->input->method() === 'post') {
            $program_id = $this->input->post('program_id');
            $grade_level_id = $this->input->post('grade_level_id');
            $semester_type = $this->input->post('semester_type', TRUE);
            
            // Only require semester if program is selected (CHED)
            if (!$program_id) {
                $semester_type = null;
            }
            
            $d = array(
                'code'            => $this->input->post('code', TRUE),
                'description'     => $this->input->post('description', TRUE),
                'program_id'      => $program_id,
                'grade_level_id'  => $grade_level_id,
                'semester_type'   => $semester_type,
                'units'           => $this->input->post('units'),
                'lec_hours'       => $this->input->post('lec_hours'),
                'lab_hours'       => $this->input->post('lab_hours'),
                'status'          => 1,
            );
            $subject_id = $this->Academic_model->create_subject($d);
            
            // Audit log
            $this->Audit_model->log('create', 'subject', $subject_id, $d['code'], 'Created subject: ' . $d['code']);
            
            $this->session->set_flashdata('success', 'Subject created successfully.');
            redirect('subjects');
        }
        
        $data['title'] = 'Add Subject';
        $data['subject'] = null;
        $data['programs'] = $this->Academic_model->get_programs();
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $this->render('subjects/form', $data);
    }

    public function edit($id)
    {
        $data['subject'] = $this->Academic_model->get_subject($id);
        if (!$data['subject']) show_404();

        if ($this->input->method() === 'post') {
            $program_id = $this->input->post('program_id');
            $grade_level_id = $this->input->post('grade_level_id');
            $semester_type = $this->input->post('semester_type', TRUE);
            
            // Only require semester if program is selected (CHED)
            if (!$program_id) {
                $semester_type = null;
            }
            
            $d = array(
                'code'            => $this->input->post('code', TRUE),
                'description'     => $this->input->post('description', TRUE),
                'program_id'      => $program_id,
                'grade_level_id'  => $grade_level_id,
                'semester_type'   => $semester_type,
                'units'           => $this->input->post('units'),
                'lec_hours'       => $this->input->post('lec_hours'),
                'lab_hours'       => $this->input->post('lab_hours'),
            );
            $this->Academic_model->update_subject($id, $d);
            
            // Audit log
            $this->Audit_model->log('update', 'subject', $id, $d['code'], 'Updated subject: ' . $d['code']);
            
            $this->session->set_flashdata('success', 'Subject updated successfully.');
            redirect('subjects');
        }
        
        $data['title'] = 'Edit Subject';
        $data['programs'] = $this->Academic_model->get_programs();
        $data['grade_levels'] = $this->Academic_model->get_grade_levels();
        $this->render('subjects/form', $data);
    }

    public function delete($id)
    {
        $subject = $this->Academic_model->get_subject($id);
        $subject_name = $subject ? ($subject->description ?: $subject->code) : 'Unknown';
        
        $this->Academic_model->delete_subject($id);
        
        // Audit log
        $this->Audit_model->log('delete', 'subject', $id, $subject_name, 'Deleted subject: ' . $subject_name);
        
        $this->session->set_flashdata('success', 'Subject deleted successfully.');
        redirect('subjects');
    }

    public function view($id)
    {
        $data['subject'] = $this->Academic_model->get_subject($id);
        if (!$data['subject']) show_404();
        
        $data['title'] = 'Subject Details: ' . ($data['subject']->description ?: $data['subject']->code);
        $this->render('subjects/view', $data);
    }
}
