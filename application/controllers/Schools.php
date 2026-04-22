<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Schools extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model('School_model');
        $this->load->model('Audit_model');
    }

    public function index()
    {
        $this->require_role(array('super_admin'));
        $schools = $this->School_model->get_all();
        foreach ($schools as &$s) {
            $s->stats = $this->School_model->get_school_stats($s->id);
        }

        // Dashboard metrics
        $total_schools = count($schools);
        $active_schools = 0;
        $inactive_schools = 0;
        $school_types = array('deped' => 0, 'ched' => 0, 'tesda' => 0, 'both' => 0, 'basic' => 0, 'college' => 0, 'tech_voc' => 0);

        foreach ($schools as $s) {
            if ($s->status) {
                $active_schools++;
            } else {
                $inactive_schools++;
            }

            $type = isset($s->type) ? $s->type : 'deped';
            if (isset($school_types[$type])) {
                $school_types[$type]++;
            }
        }

        // Calculate total students across all schools
        $total_students = 0;
        foreach ($schools as $s) {
            $total_students += isset($s->stats->students) ? $s->stats->students : 0;
        }

        $data['title'] = 'Schools';
        $data['schools'] = $schools;
        $data['total_schools'] = $total_schools;
        $data['active_schools'] = $active_schools;
        $data['inactive_schools'] = $inactive_schools;
        $data['school_types'] = $school_types;
        $data['total_students'] = $total_students;
        $data['recent_schools'] = array_slice($schools, 0, 5);

        $this->render('schools/index', $data);
    }

    public function create()
    {
        $this->require_role(array('super_admin'));

        if ($this->input->method() === 'post') {
            $d = array(
                'name'             => $this->input->post('name', TRUE),
                'school_id_number' => $this->input->post('school_id_number', TRUE),
                'type'             => $this->input->post('type', TRUE),
                'address'          => $this->input->post('address', TRUE),
                'contact_number'   => $this->input->post('contact_number', TRUE),
                'email'            => $this->input->post('email', TRUE),
                'division'         => $this->input->post('division', TRUE),
                'region'           => $this->input->post('region', TRUE),
            );
            $school_id = $this->School_model->create($d);

            // Create default school year
            $this->db->insert('school_years', array(
                'school_id'  => $school_id,
                'year_start' => date('Y'),
                'year_end'   => date('Y') + 1,
                'is_active'  => 1,
            ));

            // Create school admin user account
            $school_admin_role = $this->db->where('slug', 'school_admin')->get('roles')->row();
            if ($school_admin_role) {
                $school_name = $this->input->post('name', TRUE);
                $school_email = $this->input->post('email', TRUE);

                // Generate email if not provided
                if (empty($school_email)) {
                    $school_email = 'admin@' . strtolower(preg_replace('/[^a-z0-9]/', '', $school_name)) . '.lms';
                }

                // Generate default password (school name in lowercase)
                $default_password = strtolower(str_replace(' ', '', $school_name)) . '123';

                $this->db->insert('users', array(
                    'first_name' => 'School',
                    'last_name'  => 'Admin',
                    'email'      => $school_email,
                    'password'   => password_hash($default_password, PASSWORD_DEFAULT),
                    'role_id'    => $school_admin_role->id,
                    'school_id'  => $school_id,
                    'status'     => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ));

                // Send email with password
                $this->load->library('email');
                $this->config->load('email');
                $this->email->from($this->config->item('smtp_user'), 'LMS Portal');
                $this->email->to($school_email);
                $this->email->subject('Your LMS School Admin Account');

                $message = $this->load->view('emails/school_admin_password', array(
                    'school_name' => $school_name,
                    'email' => $school_email,
                    'password' => $default_password
                ), true);

                $this->email->message($message);
                $this->email->send();
            }

            // Audit log
            $this->Audit_model->log('create', 'school', $school_id, $d['name'], 'Created school: ' . $d['name'] . ' (' . $d['type'] . ')');

            $this->session->set_flashdata('success', 'School created successfully. A school admin account has been created automatically.');
            redirect('schools/select');
        }

        $data['title'] = 'Add School';
        $data['school'] = null;
        $this->render('schools/form', $data);
    }

    public function edit($id)
    {
        $this->require_role(array('super_admin'));
        $data['school'] = $this->School_model->get($id);
        if (!$data['school']) show_404();

        if ($this->input->method() === 'post') {
            $d = array(
                'name'             => $this->input->post('name', TRUE),
                'school_id_number' => $this->input->post('school_id_number', TRUE),
                'type'             => $this->input->post('type', TRUE),
                'address'          => $this->input->post('address', TRUE),
                'contact_number'   => $this->input->post('contact_number', TRUE),
                'email'            => $this->input->post('email', TRUE),
                'division'         => $this->input->post('division', TRUE),
                'region'           => $this->input->post('region', TRUE),
                'status'           => $this->input->post('status') ? 1 : 0,
            );
            $this->School_model->update($id, $d);

            // Audit log
            $this->Audit_model->log('update', 'school', $id, $d['name'], 'Updated school: ' . $d['name']);

            $this->session->set_flashdata('success', 'School updated.');
            redirect('schools');
        }

        $data['title'] = 'Edit School';
        $this->render('schools/form', $data);
    }

    public function select()
    {
        $this->require_role(array('super_admin'));

        if ($this->session->userdata('school_id')) {
            $this->session->unset_userdata('school_id');
            $this->session->unset_userdata('school_name');
            $this->school_id = null;
            $this->current_school = null;
        }

        $data['title'] = 'Select School';
        $data['schools'] = $this->School_model->get_all();
        $this->render('schools/select', $data);
    }

    public function switch_school($id)
    {
        $this->require_role(array('super_admin'));

        $school_id = (int) $id;
        $school = $school_id ? $this->School_model->get($school_id) : null;

        if (!$school) {
            $this->session->set_flashdata('error', 'School not found.');
            redirect('schools/select');
        }

        $this->session->set_userdata(array(
            'school_id' => (int) $school->id,
            'school_name' => $school->name,
        ));
        $this->session->set_flashdata('success', 'Switched to: ' . htmlspecialchars($school->name, ENT_QUOTES, 'UTF-8'));

        redirect('dashboard');
    }

    public function switch_to_platform()
    {
        $this->require_role(array('super_admin'));

        $this->session->unset_userdata('school_id');
        $this->session->unset_userdata('school_name');
        $this->session->set_flashdata('success', 'Switched to platform view.');

        redirect('schools');
    }

    public function migrate_grade_levels()
    {
        $this->require_login();

        try {
            // Check if school_id column exists
            $checkColumn = $this->db->query("SHOW COLUMNS FROM grade_levels LIKE 'school_id'");
            if ($checkColumn->num_rows() == 0) {
                // Add school_id column
                $this->db->query("ALTER TABLE `grade_levels` ADD COLUMN `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`");
                $this->session->set_flashdata('success', 'school_id column added to grade_levels table.');
            } else {
                $this->session->set_flashdata('info', 'school_id column already exists in grade_levels table.');
            }

            // Update existing records
            $this->db->query("UPDATE `grade_levels` SET `school_id` = 1 WHERE `school_id` IS NULL OR `school_id` = 0");

            // Add index if it doesn't exist
            $checkIndex = $this->db->query("SHOW INDEX FROM grade_levels WHERE Key_name = 'idx_school_id'");
            if ($checkIndex->num_rows() == 0) {
                $this->db->query("ALTER TABLE `grade_levels` ADD INDEX `idx_school_id` (`school_id`)");
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Migration failed: ' . $e->getMessage());
        }

        redirect('schools');
    }

    public function migrate_unified_academic_programs()
    {
        $this->require_login();

        try {
            // Step 1: Create the unified academic_programs table
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `academic_programs` (
                  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
                  `name` varchar(255) NOT NULL,
                  `code` varchar(50) NOT NULL,
                  `description` text DEFAULT NULL,
                  `type` enum('grade_level', 'program') NOT NULL DEFAULT 'grade_level',
                  `category` enum('elementary','junior_high','senior_high') DEFAULT NULL,
                  `degree_type` enum('bachelor','master','doctorate','diploma','certificate') DEFAULT NULL,
                  `level_order` tinyint(3) DEFAULT NULL,
                  `total_units` decimal(5,1) DEFAULT NULL,
                  `years_to_complete` tinyint(2) DEFAULT NULL,
                  `status` tinyint(1) NOT NULL DEFAULT 1,
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `school_code` (`school_id`, `code`),
                  KEY `idx_school_id` (`school_id`),
                  KEY `idx_type` (`type`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");

            // Step 2: Migrate grade_levels data
            $this->db->query("
                INSERT INTO `academic_programs` (`school_id`, `name`, `code`, `description`, `type`, `category`, `level_order`, `status`)
                SELECT 
                    1 as school_id,
                    name,
                    code,
                    NULL as description,
                    'grade_level' as type,
                    category,
                    level_order,
                    status
                FROM `grade_levels`
                ON DUPLICATE KEY UPDATE id=id
            ");

            // Step 3: Migrate programs data
            $this->db->query("
                INSERT INTO `academic_programs` (`school_id`, `name`, `code`, `description`, `type`, `degree_type`, `total_units`, `years_to_complete`, `status`, `created_at`)
                SELECT 
                    school_id,
                    name,
                    code,
                    description,
                    'program' as type,
                    degree_type,
                    total_units,
                    years_to_complete,
                    status,
                    created_at
                FROM `programs`
                ON DUPLICATE KEY UPDATE id=id
            ");

            // Step 4: Add academic_program_id column to subjects
            $checkColumn = $this->db->query("SHOW COLUMNS FROM subjects LIKE 'academic_program_id'");
            if ($checkColumn->num_rows() == 0) {
                $this->db->query("ALTER TABLE `subjects` ADD COLUMN `academic_program_id` int(11) UNSIGNED DEFAULT NULL AFTER `id`");
            }

            // Migrate grade_level references
            $this->db->query("
                UPDATE `subjects` s
                JOIN `grade_levels` gl ON s.grade_level_id = gl.id
                JOIN `academic_programs` ap ON ap.code = gl.code AND ap.type = 'grade_level' AND ap.school_id = s.school_id
                SET s.academic_program_id = ap.id
                WHERE s.grade_level_id IS NOT NULL
            ");

            // Migrate program references
            $this->db->query("
                UPDATE `subjects` s
                JOIN `programs` p ON s.program_id = p.id
                JOIN `academic_programs` ap ON ap.code = p.code AND ap.type = 'program' AND ap.school_id = s.school_id
                SET s.academic_program_id = ap.id
                WHERE s.program_id IS NOT NULL
            ");

            $this->session->set_flashdata('success', 'Unified academic_programs migration completed successfully.');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Migration failed: ' . $e->getMessage());
        }

        redirect('schools');
    }

    public function migrate_subjects_school_id()
    {
        try {
            // Update subjects with NULL school_id to use school_id from their program or grade level
            $this->db->query("
                UPDATE subjects s
                LEFT JOIN programs p ON s.program_id = p.id
                LEFT JOIN grade_levels gl ON s.grade_level_id = gl.id
                SET s.school_id = COALESCE(p.school_id, gl.school_id, 1)
                WHERE s.school_id IS NULL OR s.school_id = 0
            ");

            echo "Subjects updated with school_id successfully.";
        } catch (Exception $e) {
            echo "Migration failed: " . $e->getMessage();
        }
    }

    public function delete($id)
    {
        $this->require_role(array('super_admin'));
        $school = $this->School_model->get($id);
        $school_name = $school ? $school->name : 'Unknown';

        $this->School_model->delete($id);

        // Audit log
        $this->Audit_model->log('delete', 'school', $id, $school_name, 'Deleted school: ' . $school_name);

        $this->session->set_flashdata('success', 'School deleted.');
        redirect('schools');
    }

    public function download_template()
    {
        $this->require_role(array('super_admin'));

        $template = "name,school_id_number,type,address,contact_number,email,division,region\n";
        $template .= "Sample Elementary School,SES-001,basic,123 Sample St,1234567890,sample@school.com,NCR,NCR\n";
        $template .= "Sample College,SC-001,college,456 College Ave,0987654321,college@school.edu,NCR,NCR\n";
        $template .= "Sample Tech-Voc,STV-001,tech_voc,789 Vocational Rd,1122334455,techvoc@school.tv,NCR,NCR\n";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="schools_template.csv"');
        echo $template;
        exit;
    }

    public function bulk_upload()
    {
        $this->require_role(array('super_admin'));

        if ($this->input->method() === 'post' && !empty($_FILES['csv_file']['name'])) {
            $file = $_FILES['csv_file']['tmp_name'];

            if (($handle = fopen($file, 'r')) !== FALSE) {
                $headers = fgetcsv($handle);
                $row = 0;
                $success_count = 0;
                $error_count = 0;
                $errors = array();

                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $row++;

                    if ($row == 1) continue; // Skip header row

                    // Validate required fields
                    if (empty($data[0])) {
                        $errors[] = "Row $row: School name is required";
                        $error_count++;
                        continue;
                    }

                    if (empty($data[1])) {
                        $errors[] = "Row $row: School ID number is required";
                        $error_count++;
                        continue;
                    }

                    if (empty($data[2]) || !in_array($data[2], array('basic', 'college', 'tech_voc', 'both'))) {
                        $errors[] = "Row $row: Invalid school type. Must be: basic, college, tech_voc, or both";
                        $error_count++;
                        continue;
                    }

                    // Check if school_id_number already exists
                    $existing = $this->School_model->get_by_school_id_number($data[1]);
                    if ($existing) {
                        $errors[] = "Row $row: School ID number '{$data[1]}' already exists";
                        $error_count++;
                        continue;
                    }

                    // Create school
                    $school_data = array(
                        'name'             => $data[0],
                        'school_id_number' => $data[1],
                        'type'             => $data[2],
                        'address'          => isset($data[3]) ? $data[3] : '',
                        'contact_number'   => isset($data[4]) ? $data[4] : '',
                        'email'            => isset($data[5]) ? $data[5] : '',
                        'division'         => isset($data[6]) ? $data[6] : '',
                        'region'           => isset($data[7]) ? $data[7] : '',
                    );

                    $school_id = $this->School_model->create($school_data);

                    if ($school_id) {
                        // Create default school year
                        $this->db->insert('school_years', array(
                            'school_id'  => $school_id,
                            'year_start' => date('Y'),
                            'year_end'   => date('Y') + 1,
                            'is_active'  => 1,
                        ));

                        // Create school admin user account
                        $school_admin_role = $this->db->where('slug', 'school_admin')->get('roles')->row();
                        if ($school_admin_role) {
                            $school_name = $data[0];
                            $school_email = isset($data[5]) && !empty($data[5]) ? $data[5] : 'admin@' . strtolower(preg_replace('/[^a-z0-9]/', '', $school_name)) . '.lms';

                            $default_password = strtolower(str_replace(' ', '', $school_name)) . '123';

                            $this->db->insert('users', array(
                                'first_name' => 'School',
                                'last_name'  => 'Admin',
                                'email'      => $school_email,
                                'password'   => password_hash($default_password, PASSWORD_DEFAULT),
                                'role_id'    => $school_admin_role->id,
                                'school_id'  => $school_id,
                                'status'     => 1,
                                'created_at' => date('Y-m-d H:i:s'),
                            ));
                        }

                        // Audit log
                        $this->Audit_model->log('create', 'school', $school_id, $school_data['name'], 'Bulk imported school: ' . $school_data['name']);

                        $success_count++;
                    } else {
                        $error_count++;
                        $errors[] = "Row $row: Failed to create school";
                    }
                }

                fclose($handle);

                $this->session->set_flashdata('success', "Bulk upload complete. Successfully imported $success_count schools. $error_count errors.");
                if (!empty($errors)) {
                    $this->session->set_flashdata('errors', $errors);
                }
            }
        }

        redirect('schools');
    }
}
