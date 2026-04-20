<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class School_model extends CI_Model {

    public function get_all()
    {
        return $this->db->order_by('name', 'ASC')->get('schools')->result();
    }

    public function get($id)
    {
        return $this->db->where('id', $id)->get('schools')->row();
    }

    public function create($data)
    {
        $this->db->insert('schools', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('schools', $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete('schools');
    }

    public function count_all()
    {
        return $this->db->count_all('schools');
    }

    public function get_school_stats($school_id)
    {
        $stats = new stdClass();
        $stats->students = $this->db->where('school_id', $school_id)->where('status', 'active')->count_all_results('students');
        $stats->teachers = $this->db->where('school_id', $school_id)->count_all_results('teachers');
        $stats->sections = $this->db->where('school_id', $school_id)->where('status', 1)->count_all_results('sections');
        $stats->users = $this->db->where('school_id', $school_id)->where('status', 1)->count_all_results('users');
        return $stats;
    }
}
