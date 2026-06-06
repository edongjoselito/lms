<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Studentprofile_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->ensure_table_exists();
    }

    private function ensure_table_exists()
    {
        if (!$this->db->table_exists('studentprofile')) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `studentprofile` (
                  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `user_id` int(11) UNSIGNED DEFAULT NULL,
                  `school_id` int(11) UNSIGNED NOT NULL,
                  `student_number` varchar(50) NOT NULL,
                  `first_name` varchar(100) NOT NULL,
                  `middle_name` varchar(100) DEFAULT NULL,
                  `last_name` varchar(100) NOT NULL,
                  `birth_date` date NOT NULL,
                  `email` varchar(255) DEFAULT NULL,
                  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uq_student_number_school` (`student_number`, `school_id`),
                  KEY `fk_sp_user` (`user_id`),
                  KEY `fk_sp_school` (`school_id`),
                  CONSTRAINT `fk_sp_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
                  CONSTRAINT `fk_sp_school` FOREIGN KEY (`school_id`) REFERENCES `schools`(`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }
    }

    public function get_all($school_id = null, $search = null)
    {
        $this->db->select('studentprofile.*, studentprofile.email as profile_email, users.email as user_email, users.status as user_status');
        $this->db->join('users', 'users.id = studentprofile.user_id', 'left');
        if ($school_id) {
            $this->db->where('studentprofile.school_id', $school_id);
        }
        if ($search) {
            $this->db->group_start();
            $this->db->like('studentprofile.student_number', $search);
            $this->db->or_like('studentprofile.first_name', $search);
            $this->db->or_like('studentprofile.middle_name', $search);
            $this->db->or_like('studentprofile.last_name', $search);
            $this->db->or_like('CONCAT(studentprofile.first_name, " ", studentprofile.last_name)', $search, FALSE);
            $this->db->or_like('CONCAT(studentprofile.last_name, ", ", studentprofile.first_name)', $search, FALSE);
            $this->db->group_end();
        }
        return $this->db->order_by('studentprofile.id', 'DESC')->get('studentprofile')->result();
    }

    public function get($id)
    {
        return $this->db->where('id', $id)->get('studentprofile')->row();
    }

    public function get_by_student_number($student_number, $school_id)
    {
        return $this->db->where('student_number', $student_number)
            ->where('school_id', $school_id)
            ->get('studentprofile')
            ->row();
    }

    public function create($data)
    {
        $this->db->insert('studentprofile', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('studentprofile', $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete('studentprofile');
    }
}
