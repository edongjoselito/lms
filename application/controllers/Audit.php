<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit extends Admin_Controller {

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

        $data['title'] = 'Audit Logs';
        $data['logs'] = $this->Audit_model->get_all(100, 0, $this->school_id);
        $data['total_logs'] = $this->Audit_model->count_all($this->school_id);
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
