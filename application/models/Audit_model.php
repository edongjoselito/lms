<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_model extends CI_Model {

    public function log($action, $entity_type, $entity_id, $entity_name = null, $description = null)
    {
        $user_id = $this->session->userdata('user_id');
        $user_name = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
        $school_id = $this->session->userdata('school_id');
        
        $ip_address = $this->input->ip_address();

        $data = array(
            'user_id' => $user_id,
            'user_name' => $user_name,
            'action' => $action,
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'entity_name' => $entity_name,
            'description' => $description,
            'ip_address' => $ip_address,
            'school_id' => $school_id,
        );

        $this->db->insert('audit_logs', $data);
    }

    public function get_all($limit = 100, $offset = 0, $school_id = null)
    {
        $this->db->select('audit_logs.*');
        $this->db->order_by('created_at', 'DESC');
        
        if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
        
        return $this->db->limit($limit, $offset)->get('audit_logs')->result();
    }

    public function get_by_entity($entity_type, $entity_id)
    {
        $this->db->where('entity_type', $entity_type);
        $this->db->where('entity_id', $entity_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('audit_logs')->result();
    }

    public function get_by_user($user_id, $limit = 50)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->limit($limit)->get('audit_logs')->result();
    }

    public function count_all($school_id = null)
    {
        if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
        return $this->db->count_all_results('audit_logs');
    }
}
