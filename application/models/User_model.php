<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class User_model extends CI_Model {

    public function authenticate($email, $password)
    {
        $user = $this->db->select('users.*, roles.slug as role_slug, roles.name as role_name')
                         ->join('roles', 'roles.id = users.role_id')
                         ->where('users.email', $email)
                         ->where('users.status', 1)
                         ->get('users')
                         ->row();

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    public function update_last_login($user_id)
    {
        $this->db->where('id', $user_id)->update('users', array('last_login' => date('Y-m-d H:i:s')));
    }

    public function get_all($filters = array())
    {
        $this->db->select('users.*, roles.name as role_name, roles.slug as role_slug, schools.name as school_name');
        $this->db->join('roles', 'roles.id = users.role_id');
        $this->db->join('schools', 'schools.id = users.school_id', 'left');
        if (!empty($filters['role_id'])) {
            $this->db->where('users.role_id', $filters['role_id']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('users.status', $filters['status']);
        }
        if (!empty($filters['school_id'])) {
            $this->db->where('users.school_id', $filters['school_id']);
        }
        return $this->db->order_by('users.created_at', 'DESC')->get('users')->result();
    }

    public function get($id)
    {
        return $this->db->select('users.*, roles.name as role_name, roles.slug as role_slug, schools.name as school_name')
                        ->join('roles', 'roles.id = users.role_id')
                        ->join('schools', 'schools.id = users.school_id', 'left')
                        ->where('users.id', $id)
                        ->get('users')
                        ->row();
    }

    public function create($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        return $this->db->where('id', $id)->update('users', $data);
    }

    public function update_profile($user_id, $data)
    {
        $allowed_fields = array('first_name', 'last_name', 'email');
        $update_data = array();
        foreach ($allowed_fields as $field) {
            if (isset($data[$field])) {
                $update_data[$field] = $data[$field];
            }
        }
        if (!empty($update_data)) {
            return $this->db->where('id', $user_id)->update('users', $update_data);
        }
        return false;
    }

    public function change_password($user_id, $current_password, $new_password)
    {
        $user = $this->db->where('id', $user_id)->get('users')->row();
        if (!$user) return false;
        if (!password_verify($current_password, $user->password)) return false;

        $this->db->where('id', $user_id)->update('users', array(
            'password' => password_hash($new_password, PASSWORD_BCRYPT)
        ));
        return true;
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete('users');
    }

    public function email_exists($email, $exclude_id = null)
    {
        $this->db->where('email', $email);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get('users')->num_rows() > 0;
    }

    public function get_roles()
    {
        return $this->db->get('roles')->result();
    }

    public function get_role($id)
    {
        return $this->db->where('id', $id)->get('roles')->row();
    }

    public function count_all()
    {
        return $this->db->count_all('users');
    }

    public function count_by_role($role_slug, $school_id = null)
    {
        $this->db->join('roles', 'roles.id = users.role_id');
        $this->db->where('roles.slug', $role_slug);
        if ($school_id) {
            $this->db->where('users.school_id', $school_id);
        }
        return $this->db->count_all_results('users');
    }

    public function count_active()
    {
        return $this->db->where('status', 1)->count_all_results('users');
    }

    public function count_by_school($school_id = null)
    {
        if ($school_id) {
            $this->db->where('school_id', $school_id);
        }
        return $this->db->where('status', 1)->count_all_results('users');
    }
}
