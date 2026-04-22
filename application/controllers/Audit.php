<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Audit extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Audit_model');
    }

    public function index()
    {
        $filters = array();
        if ($this->school_id) {
            $filters['school_id'] = $this->school_id;
        }

        // Pagination
        $per_page = 20;
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $offset = ($page - 1) * $per_page;

        $total_logs = $this->Audit_model->count_all($this->school_id);
        $logs = $this->Audit_model->get_all($per_page, $offset, $this->school_id);

        $data['title'] = 'Audit Logs';
        $data['logs'] = $logs;
        $data['total_logs'] = $total_logs;
        $data['per_page'] = $per_page;
        $data['current_page'] = $page;
        $data['total_pages'] = ceil($total_logs / $per_page);
        $this->render('audit/index', $data);
    }

    public function view_entity($entity_type, $entity_id)
    {
        $data['title'] = 'Audit History: ' . ucfirst($entity_type) . ' #' . $entity_id;
        $data['logs'] = $this->Audit_model->get_by_entity($entity_type, $entity_id);
        $data['entity_type'] = $entity_type;
        $data['entity_id'] = $entity_id;
        $this->render('audit/entity', $data);
    }

    public function view_user($user_id)
    {
        $data['title'] = 'Audit History: User #' . $user_id;
        $data['logs'] = $this->Audit_model->get_by_user($user_id);
        $data['user_id'] = $user_id;
        $this->render('audit/user', $data);
    }
}
