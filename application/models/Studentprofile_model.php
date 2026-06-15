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

    public function get_all($school_id = null, $search = null, $limit = null, $offset = null)
    {
        $this->db->select('studentprofile.*, studentprofile.email as profile_email, users.email as user_email, users.status as user_status');
        $this->db->from('studentprofile');
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
        if ($limit !== null) {
            $this->db->limit((int) $limit, (int) $offset);
        }
        return $this->db->order_by('studentprofile.id', 'DESC')->get()->result();
    }

    public function count_all($school_id = null, $search = null)
    {
        $this->db->from('studentprofile');
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

        return (int) $this->db->count_all_results();
    }

    public function get($id)
    {
        return $this->db->select('studentprofile.*, students.gender', FALSE)
            ->from('studentprofile')
            ->join('students', 'students.user_id = studentprofile.user_id', 'left')
            ->where('studentprofile.id', $id)
            ->get()
            ->row();
    }

    public function get_for_school($id, $school_id)
    {
        return $this->db->select('studentprofile.*, students.gender', FALSE)
            ->from('studentprofile')
            ->join('students', 'students.user_id = studentprofile.user_id', 'left')
            ->where('studentprofile.id', $id)
            ->where('studentprofile.school_id', $school_id)
            ->get()
            ->row();
    }

    public function get_by_student_number($student_number, $school_id)
    {
        return $this->db->where('student_number', $student_number)
            ->where('school_id', $school_id)
            ->get('studentprofile')
            ->row();
    }

    public function email_exists($email, $school_id = null, $exclude_id = null)
    {
        $email = trim((string) $email);
        if ($email === '') {
            return false;
        }

        $this->db->from('studentprofile');
        $this->db->where('email', $email);

        if ($school_id !== null) {
            $this->db->where('school_id', (int) $school_id);
        }

        if ($exclude_id !== null) {
            $this->db->where('id !=', (int) $exclude_id);
        }

        return $this->db->count_all_results() > 0;
    }

    public function find_by_identity($school_id, $first_name, $middle_name, $last_name, $birth_date, $exclude_id = null)
    {
        $sql = "SELECT *
                FROM studentprofile
                WHERE school_id = ?
                  AND LOWER(TRIM(first_name)) = ?
                  AND LOWER(TRIM(IFNULL(middle_name, ''))) = ?
                  AND LOWER(TRIM(last_name)) = ?
                  AND birth_date = ?";

        $params = array(
            (int) $school_id,
            strtolower(trim((string) $first_name)),
            strtolower(trim((string) $middle_name)),
            strtolower(trim((string) $last_name)),
            $birth_date,
        );

        if ($exclude_id !== null) {
            $sql .= " AND id != ?";
            $params[] = (int) $exclude_id;
        }

        $sql .= " LIMIT 1";

        return $this->db->query($sql, $params)->row();
    }

    public function search_for_enrollment($school_id, $search = null, $limit = 20)
    {
        $this->db->select('studentprofile.*, studentprofile.email as profile_email, users.email as user_email');
        $this->db->from('studentprofile');
        $this->db->join('users', 'users.id = studentprofile.user_id', 'left');
        $this->db->join(
            'enrollments e',
            'e.student_id = studentprofile.user_id AND e.school_id = studentprofile.school_id AND e.status = "enrolled"',
            'left',
            FALSE
        );
        $this->db->where('studentprofile.school_id', $school_id);
        $this->db->where('studentprofile.user_id IS NOT NULL', null, false);
        $this->db->where('e.id IS NULL', null, false);

        if ($search !== null && $search !== '') {
            $this->db->group_start();
            $this->db->like('studentprofile.student_number', $search);
            $this->db->or_like('studentprofile.first_name', $search);
            $this->db->or_like('studentprofile.middle_name', $search);
            $this->db->or_like('studentprofile.last_name', $search);
            $this->db->or_like('CONCAT(studentprofile.first_name, " ", studentprofile.last_name)', $search, FALSE);
            $this->db->or_like('CONCAT(studentprofile.last_name, ", ", studentprofile.first_name)', $search, FALSE);
            $this->db->group_end();
        }

        return $this->db->order_by('studentprofile.last_name', 'ASC')
            ->order_by('studentprofile.first_name', 'ASC')
            ->limit((int) $limit)
            ->get()
            ->result();
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
