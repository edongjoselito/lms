<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting_model extends CI_Model
{
    protected $table = 'platform_settings';
    protected $table_ready = null;

    public function is_available()
    {
        return $this->ensure_table();
    }

    public function get_value($key, $default = null)
    {
        if (!$this->is_available()) {
            return $default;
        }

        $row = $this->db
            ->select('setting_value')
            ->where('setting_key', $key)
            ->get($this->table)
            ->row();

        return $row ? $row->setting_value : $default;
    }

    public function set_value($key, $value)
    {
        if (!$this->is_available()) {
            return false;
        }

        $existing = $this->db
            ->select('id')
            ->where('setting_key', $key)
            ->get($this->table)
            ->row();

        if ($existing) {
            return $this->db
                ->where('id', $existing->id)
                ->update($this->table, array('setting_value' => $value));
        }

        return $this->db->insert($this->table, array(
            'setting_key' => $key,
            'setting_value' => $value,
        ));
    }

    public function delete($key)
    {
        if (!$this->is_available()) {
            return false;
        }

        return $this->db->where('setting_key', $key)->delete($this->table);
    }

    protected function ensure_table()
    {
        if ($this->table_ready !== null) {
            return $this->table_ready;
        }

        if ($this->db->table_exists($this->table)) {
            $this->table_ready = true;
            return true;
        }

        $this->load->dbforge();

        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'setting_key' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ),
            'setting_value' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
        ));
        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', true);

        $this->dbforge->create_table($this->table, true);

        if ($this->db->table_exists($this->table)) {
            $index_exists = $this->db
                ->query("SHOW INDEX FROM `{$this->table}` WHERE Key_name = 'setting_key'")
                ->num_rows() > 0;

            if (!$index_exists) {
                $this->db->query("ALTER TABLE `{$this->table}` ADD UNIQUE KEY `setting_key` (`setting_key`)");
            }
        }

        $this->table_ready = $this->db->table_exists($this->table);
        return $this->table_ready;
    }
}
