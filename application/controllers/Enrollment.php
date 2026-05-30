<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Enrollment extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Enrollment_model', 'Studentprofile_model', 'User_model'));
        $this->require_school();
    }

    public function index()
    {
        $data['title'] = 'Enrollment';
        $data['enrollments'] = $this->Enrollment_model->get_all($this->school_id);
        $data['stats'] = $this->Enrollment_model->get_stats($this->school_id);
        $data['grade_level_counts'] = $this->Enrollment_model->get_grade_level_counts($this->school_id);
        $this->render('enrollment/index', $data);
    }

    public function edit($id)
    {
        $data['enrollment'] = $this->db->where('id', $id)->get('enrollments')->row();
        if (!$data['enrollment']) show_404();

        // Get student profile by user_id (since enrollment.student_id is the user_id)
        $data['profile'] = $this->db->where('user_id', $data['enrollment']->student_id)->get('studentprofile')->row();
        if (!$data['profile']) show_404();

        // Get grade levels/programs for current school
        $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'");
        if ($check_academic->num_rows() > 0) {
            $data['grade_levels'] = $this->db->where('school_id', $this->school_id)
                ->get('academic_programs')
                ->result();
        } else {
            $data['grade_levels'] = $this->db->where('school_id', $this->school_id)
                ->get('grade_levels')
                ->result();
        }

        // Get sections for current school
        $data['sections'] = $this->db->where('school_id', $this->school_id)
            ->get('sections')
            ->result();

        // Get teachers (advisers) for current school
        $teacher_role_id = $this->User_model->get_role_id_by_slug('teacher');
        $data['advisers'] = $this->db->where('school_id', $this->school_id)
            ->where('role_id', $teacher_role_id)
            ->get('users')
            ->result();

        if ($this->input->method() === 'post') {
            $grade_level_id = $this->input->post('grade_level_id', TRUE);
            $section_id = $this->input->post('section_id', TRUE);
            $adviser_id = $this->input->post('adviser_id', TRUE);

            // Update enrollment record
            $enrollment_data = array(
                'grade_level_id' => $grade_level_id,
                'section_id' => $section_id
            );

            $this->db->where('id', $id)->update('enrollments', $enrollment_data);

            // Update section adviser if provided
            if ($adviser_id) {
                $this->db->where('id', $section_id)->update('sections', array('adviser_id' => $adviser_id));
            }

            $this->session->set_flashdata('success', 'Enrollment updated successfully.');
            redirect('enrollment');
        }

        $data['title'] = 'Edit Enrollment';
        $this->render('enrollment/edit', $data);
    }

    public function delete($id)
    {
        $enrollment = $this->db->where('id', $id)->get('enrollments')->row();
        if (!$enrollment) show_404();

        $this->db->where('id', $id)->delete('enrollments');
        $this->session->set_flashdata('success', 'Enrollment deleted successfully.');
        redirect('enrollment');
    }
}
