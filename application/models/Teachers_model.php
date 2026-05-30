<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teachers_model extends CI_Model {

    public function get_all($school_id = null, $search = null)
    {
        // Check if staff table exists
        $checkTable = $this->db->query("SHOW TABLES LIKE 'staff'")->num_rows();
        if ($checkTable > 0) {
            $this->db->select('staff.*, users.first_name, users.last_name, users.email, users.status as user_status');
            $this->db->join('users', 'users.id = staff.user_id');
            if ($school_id) {
                $this->db->where('staff.school_id', $school_id);
            }
            if ($search) {
                $this->db->group_start()
                    ->like('users.first_name', $search)
                    ->or_like('users.last_name', $search)
                    ->or_like('users.email', $search)
                    ->or_like('staff.IDNumber', $search)
                    ->group_end();
            }
            return $this->db->order_by('staff.created_at DESC')->get('staff')->result();
        } else {
            // Fallback to teachers table
            $this->db->select('teachers.*, users.first_name, users.last_name, users.email, users.status as user_status');
            $this->db->join('users', 'users.id = teachers.user_id');
            if ($school_id) {
                $this->db->where('teachers.school_id', $school_id);
            }
            if ($search) {
                $this->db->group_start()
                    ->like('users.first_name', $search)
                    ->or_like('users.last_name', $search)
                    ->or_like('users.email', $search)
                    ->or_like('teachers.id', $search)
                    ->group_end();
            }
            return $this->db->order_by('teachers.created_at DESC')->get('teachers')->result();
        }
    }

    public function get($id)
    {
        // Check if staff table exists
        $checkTable = $this->db->query("SHOW TABLES LIKE 'staff'")->num_rows();
        if ($checkTable > 0) {
            $this->db->select('staff.*, users.first_name, users.last_name, users.email, users.status as user_status');
            $this->db->join('users', 'users.id = staff.user_id');
            $this->db->where('staff.IDNumber', $id);
            return $this->db->get('staff')->row();
        } else {
            // Fallback to teachers table
            $this->db->select('teachers.*, users.first_name, users.last_name, users.email, users.status as user_status');
            $this->db->join('users', 'users.id = teachers.user_id');
            $this->db->where('teachers.id', $id);
            return $this->db->get('teachers')->row();
        }
    }

    public function create($data)
    {
        // Check if username column exists in users table
        $checkUsername = $this->db->query("SHOW COLUMNS FROM users LIKE 'username'")->num_rows();
        // Check if role_slug column exists in users table
        $checkRoleSlug = $this->db->query("SHOW COLUMNS FROM users LIKE 'role_slug'")->num_rows();

        // First create user
        $user_data = array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'school_id' => $data['school_id'],
            'status' => 1
        );
        if ($checkUsername > 0) {
            $user_data['username'] = $data['email'];
        }
        if ($checkRoleSlug > 0) {
            $user_data['role_slug'] = 'teacher';
        } else {
            // Use role_id instead - get teacher role id
            $teacher_role = $this->db->where('slug', 'teacher')->get('roles')->row();
            $user_data['role_id'] = $teacher_role ? $teacher_role->id : 4;
        }
        $this->db->insert('users', $user_data);
        $user_id = $this->db->insert_id();

        // Check if staff table exists
        $checkTable = $this->db->query("SHOW TABLES LIKE 'staff'")->num_rows();
        if ($checkTable > 0) {
            // Generate IDNumber
            $year = date('Y');
            $prefix = 'STF' . $year;
            $last_id = $this->db->like('IDNumber', $prefix, 'after')->order_by('IDNumber DESC')->limit(1)->get('staff')->row();
            if ($last_id) {
                $last_num = (int)substr($last_id->IDNumber, -4);
                $new_num = str_pad($last_num + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $new_num = '0001';
            }
            $id_number = $prefix . $new_num;

            // Create staff record
            $staff_data = array(
                'IDNumber' => $id_number,
                'user_id' => $user_id,
                'school_id' => $data['school_id']
            );
            $this->db->insert('staff', $staff_data);
            return $id_number;
        } else {
            // Fallback to teachers table
            $teacher_data = array(
                'user_id' => $user_id,
                'school_id' => $data['school_id']
            );
            $this->db->insert('teachers', $teacher_data);
            return $this->db->insert_id();
        }
    }

    public function update($id, $data)
    {
        // Check if username column exists in users table
        $checkUsername = $this->db->query("SHOW COLUMNS FROM users LIKE 'username'")->num_rows();

        // Update user info
        $user_data = array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email']
        );
        if ($checkUsername > 0) {
            $user_data['username'] = $data['email'];
        }
        if (isset($data['password']) && !empty($data['password'])) {
            $user_data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $teacher = $this->get($id);
        if ($teacher) {
            $this->db->where('id', $teacher->user_id)->update('users', $user_data);
            return $this->db->affected_rows() > 0;
        }

        return false;
    }

    public function delete($id)
    {
        $teacher = $this->get($id);
        if ($teacher) {
            // Check if staff table exists
            $checkTable = $this->db->query("SHOW TABLES LIKE 'staff'")->num_rows();
            if ($checkTable > 0) {
                // Delete staff record
                $this->db->where('IDNumber', $id)->delete('staff');
            } else {
                // Delete teacher record
                $this->db->where('id', $id)->delete('teachers');
            }
            // Delete user record
            $this->db->where('id', $teacher->user_id)->delete('users');
            return true;
        }
        return false;
    }
}
