<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[\AllowDynamicProperties]
class Admin_model extends CI_Model {

    private $table = 'users';

    public function login($email, $password)
    {
        $user = $this->db->where('email', $email)
                         ->where('status', 1)
                         ->get($this->table)
                         ->row();

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }

    public function get_all_users()
    {
        return $this->db->order_by('created_at', 'DESC')
                        ->get($this->table)
                        ->result();
    }

    public function get_user($id)
    {
        return $this->db->where('id', $id)
                        ->get($this->table)
                        ->row();
    }

    public function create_user($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function update_user($id, $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_user($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function email_exists($email, $exclude_id = null)
    {
        $this->db->where('email', $email);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get($this->table)->num_rows() > 0;
    }

    public function count_users()
    {
        return $this->db->count_all($this->table);
    }

    public function count_active_users()
    {
        return $this->db->where('status', 1)->count_all_results($this->table);
    }

    public function count_admins()
    {
        return $this->db->where('role', 'admin')->count_all_results($this->table);
    }
}
