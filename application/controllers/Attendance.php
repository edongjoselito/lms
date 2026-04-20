<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_role(array('super_admin', 'school_admin', 'teacher'));
        $this->load->model(array('Academic_model', 'Enrollment_model'));
    }

    public function index()
    {
        $sy = $this->Academic_model->get_active_school_year($this->school_id);
        $data['title'] = 'Attendance';
        $data['school_year'] = $sy;

        if ($this->is_teacher()) {
            $teacher = $this->Academic_model->get_teacher_by_user($this->current_user->id);
            $data['classes'] = $teacher ? $this->Academic_model->get_teacher_classes($teacher->id, $sy ? $sy->id : null) : array();
        } else {
            $data['classes'] = array();
            $filters = array();
            if ($sy) $filters['school_year_id'] = $sy->id;
            if ($this->school_id) $filters['school_id'] = $this->school_id;
            $data['sections'] = $this->Academic_model->get_sections($filters);
        }
        $this->render('attendance/index', $data);
    }

    public function record($class_program_id)
    {
        $cp = $this->Academic_model->get_class_program($class_program_id);
        if (!$cp) show_404();

        $sy = $this->Academic_model->get_active_school_year($this->school_id);
        $students = $sy ? $this->Enrollment_model->get_section_students($cp->section_id, $sy->id) : array();
        $date = $this->input->get('date') ?: date('Y-m-d');

        if ($this->input->method() === 'post') {
            $date = $this->input->post('attendance_date');
            $statuses = $this->input->post('status');
            $remarks = $this->input->post('remarks');

            if ($statuses) {
                foreach ($statuses as $enrollment_id => $status) {
                    $existing = $this->db->where('enrollment_id', $enrollment_id)
                                         ->where('class_program_id', $class_program_id)
                                         ->where('date', $date)
                                         ->get('attendance')->row();
                    $d = array(
                        'enrollment_id'    => $enrollment_id,
                        'class_program_id' => $class_program_id,
                        'date'             => $date,
                        'status'           => $status,
                        'remarks'          => isset($remarks[$enrollment_id]) ? $remarks[$enrollment_id] : null,
                        'recorded_by'      => $this->current_user->id,
                    );
                    if ($existing) {
                        $this->db->where('id', $existing->id)->update('attendance', $d);
                    } else {
                        $this->db->insert('attendance', $d);
                    }
                }
            }
            $this->session->set_flashdata('success', 'Attendance recorded.');
            redirect("attendance/record/{$class_program_id}?date={$date}");
        }

        // Load existing attendance for the date
        $att_records = array();
        $records = $this->db->where('class_program_id', $class_program_id)
                            ->where('date', $date)
                            ->get('attendance')->result();
        foreach ($records as $r) {
            $att_records[$r->enrollment_id] = $r;
        }

        $data['title'] = 'Record Attendance: ' . $cp->subject_name;
        $data['class_program'] = $cp;
        $data['students'] = $students;
        $data['date'] = $date;
        $data['att_records'] = $att_records;
        $this->render('attendance/record', $data);
    }
}
