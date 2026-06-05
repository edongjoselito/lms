<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Studentprofile extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Studentprofile_model', 'User_model'));
        $this->require_school();
    }

    public function index()
    {
        $data['title'] = 'Student Profiles';
        $search = $this->input->get('search', TRUE);
        $data['profiles'] = $this->Studentprofile_model->get_all($this->school_id, $search);
        $data['enrolled_user_ids'] = $this->db->select('student_id')
            ->from('enrollments')
            ->where('school_id', $this->school_id)
            ->where('status', 'enrolled')
            ->group_by('student_id')
            ->get()
            ->result_array();
        $this->render('studentprofile/index', $data);
    }

    public function create()
    {
        if ($this->input->method() === 'post') {
            $student_number = $this->input->post('student_number', TRUE);
            $email = $this->input->post('email', TRUE);
            $first_name = $this->input->post('first_name', TRUE);
            $middle_name = $this->input->post('middle_name', TRUE);
            $last_name = $this->input->post('last_name', TRUE);
            $birth_date = $this->input->post('birth_date', TRUE);

            // Check if student number already exists
            $existing = $this->Studentprofile_model->get_by_student_number($student_number, $this->school_id);
            if ($existing) {
                $this->session->set_flashdata('error', 'Student Number already exists in this school.');
                redirect('studentprofile/create');
            }

            // Check if student number already exists in users table (as email)
            $existing_user = $this->db->where('email', $student_number)->get('users')->row();
            if ($existing_user) {
                $this->session->set_flashdata('error', 'Student Number already exists as a login in the system.');
                redirect('studentprofile/create');
            }

            // Create user record with student number as login email
            $password = date('Y-m-d', strtotime($birth_date)); // Default password: YYYY-MM-DD
            $user_data = array(
                'email' => $student_number,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'first_name' => $first_name,
                'middle_name' => $middle_name,
                'last_name' => $last_name,
                'role_id' => $this->User_model->get_role_id_by_slug('student'),
                'school_id' => $this->school_id,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('users', $user_data);
            $user_id = $this->db->insert_id();

            // Create student profile
            $profile_data = array(
                'user_id' => $user_id,
                'school_id' => $this->school_id,
                'student_number' => $student_number,
                'email' => $email,
                'first_name' => $first_name,
                'middle_name' => $middle_name,
                'last_name' => $last_name,
                'birth_date' => $birth_date
            );
            $this->Studentprofile_model->create($profile_data);

            $this->session->set_flashdata('success', 'Student Profile created. Login: ' . $student_number . ', Password: ' . $password);
            redirect('studentprofile');
        }

        $data['title'] = 'Add Student Profile';
        $this->render('studentprofile/form', $data);
    }

    public function edit($id)
    {
        $data['profile'] = $this->Studentprofile_model->get($id);
        if (!$data['profile']) show_404();

        if ($this->input->method() === 'post') {
            $student_number = $this->input->post('student_number', TRUE);
            $email = $this->input->post('email', TRUE);

            // Check if student number already exists (excluding current record)
            $existing = $this->db->where('student_number', $student_number)
                ->where('school_id', $this->school_id)
                ->where('id !=', $id)
                ->get('studentprofile')
                ->row();
            if ($existing) {
                $this->session->set_flashdata('error', 'Student Number already exists in this school.');
                redirect('studentprofile/edit/' . $id);
            }

            // Check if student number already exists in users table (as email, excluding current user)
            $existing_user = $this->db->where('email', $student_number)
                ->where('id !=', $data['profile']->user_id)
                ->get('users')
                ->row();
            if ($existing_user) {
                $this->session->set_flashdata('error', 'Student Number already exists as a login in the system.');
                redirect('studentprofile/edit/' . $id);
            }

            $profile_data = array(
                'student_number' => $student_number,
                'email' => $email,
                'first_name' => $this->input->post('first_name', TRUE),
                'middle_name' => $this->input->post('middle_name', TRUE),
                'last_name' => $this->input->post('last_name', TRUE),
                'birth_date' => $this->input->post('birth_date', TRUE)
            );

            // Update user record if exists (always use student number as login email)
            if ($data['profile']->user_id) {
                $user_data = array(
                    'email' => $student_number,
                    'first_name' => $this->input->post('first_name', TRUE),
                    'middle_name' => $this->input->post('middle_name', TRUE),
                    'last_name' => $this->input->post('last_name', TRUE),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->db->where('id', $data['profile']->user_id)->update('users', $user_data);
            }

            $this->Studentprofile_model->update($id, $profile_data);
            $this->session->set_flashdata('success', 'Student Profile updated.');
            redirect('studentprofile');
        }

        $data['title'] = 'Edit Student Profile';
        $this->render('studentprofile/form', $data);
    }

    public function delete($id)
    {
        $profile = $this->Studentprofile_model->get($id);
        if (!$profile) show_404();

        // Delete user record if exists
        if ($profile->user_id) {
            $this->db->where('id', $profile->user_id)->delete('users');
        }

        $this->Studentprofile_model->delete($id);
        $this->session->set_flashdata('success', 'Student Profile deleted.');
        redirect('studentprofile');
    }

    public function enroll($id)
    {
        $data['profile'] = $this->Studentprofile_model->get($id);
        if (!$data['profile']) show_404();

        // Get student's current enrollment to show current grade level
        $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();
        if ($check_academic > 0) {
            $data['current_enrollment'] = $this->db->select('e.*, ap.name as grade_level_name')
                ->from('enrollments e')
                ->join('academic_programs ap', 'ap.id = e.grade_level_id', 'left')
                ->where('e.student_id', $data['profile']->user_id)
                ->where('e.status', 'enrolled')
                ->order_by('e.enrollment_date', 'DESC')
                ->limit(1)
                ->get()
                ->row();
        } else {
            $check_grade_levels = $this->db->query("SHOW TABLES LIKE 'grade_levels'")->num_rows();
            if ($check_grade_levels > 0) {
                $data['current_enrollment'] = $this->db->select('e.*, gl.name as grade_level_name')
                    ->from('enrollments e')
                    ->join('grade_levels gl', 'gl.id = e.grade_level_id', 'left')
                    ->where('e.student_id', $data['profile']->user_id)
                    ->where('e.status', 'enrolled')
                    ->order_by('e.enrollment_date', 'DESC')
                    ->limit(1)
                    ->get()
                    ->row();
            } else {
                // Get grade level name from programs table using year_level
                $data['current_enrollment'] = $this->db->select('e.*, p.name as grade_level_name')
                    ->from('enrollments e')
                    ->join('programs p', 'p.year_level = e.year_level AND p.school_id = e.school_id', 'left')
                    ->where('e.student_id', $data['profile']->user_id)
                    ->where('e.status', 'enrolled')
                    ->order_by('e.enrollment_date', 'DESC')
                    ->limit(1)
                    ->get()
                    ->row();
            }
        }

        // Get grade levels/programs for current school (same logic as Academic controller)
        $checkTable = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();
        if ($checkTable > 0) {
            $data['grade_levels'] = $this->db->where('status', 1)
                ->where('school_id', $this->school_id)
                ->order_by('type, level_order, name')
                ->get('academic_programs')
                ->result();
        } else {
            // Fallback to programs table (simpler ordering for legacy table)
            $checkStatus = $this->db->query("SHOW COLUMNS FROM programs LIKE 'status'")->num_rows();
            $checkName = $this->db->query("SHOW COLUMNS FROM programs LIKE 'name'")->num_rows();
            $checkYearLevel = $this->db->query("SHOW COLUMNS FROM programs LIKE 'year_level'")->num_rows();
            
            $query = $this->db->where('school_id', $this->school_id);
            if ($checkStatus > 0) {
                $query->where('status', 1);
            }
            if ($checkName > 0) {
                $query->order_by('name');
            } elseif ($checkYearLevel > 0) {
                $query->order_by('year_level', 'ASC');
            }
            $data['grade_levels'] = $query->get('programs')->result();
        }

        // Get sections for current school with program info and adviser
        $checkGradeLevel = $this->db->query("SHOW COLUMNS FROM sections LIKE 'grade_level_id'")->num_rows();
        
        $this->db->select('sections.*, CONCAT(u.last_name, ", ", u.first_name) as adviser_name', FALSE);
        if ($checkGradeLevel > 0) {
            $this->db->select('grade_levels.name as grade_level_name');
            $this->db->join('grade_levels', 'grade_levels.id = sections.grade_level_id', 'left');
        }
        $this->db->join('programs', 'programs.id = sections.program_id', 'left');
        $this->db->join('users u', 'u.id = sections.adviser_id', 'left');
        $this->db->where('sections.school_id', $this->school_id);
        $data['sections'] = $this->db->get('sections')->result();

        // Get teachers (advisers) for current school
        $teacher_role_id = $this->User_model->get_role_id_by_slug('teacher');
        $data['advisers'] = $this->db->where('school_id', $this->school_id)
            ->where('role_id', $teacher_role_id)
            ->get('users')
            ->result();

        if ($this->input->method() === 'post') {
            $grade_level_id = $this->input->post('grade_level_id', TRUE);
            $section_id = $this->input->post('section_id', TRUE);
            $adviser_id = $this->input->post('adviser_id', TRUE);

            // Get current school year
            $school_year = $this->db->where('school_id', $this->school_id)
                ->where('is_active', 1)
                ->get('school_years')
                ->row();

            if (!$school_year) {
                $this->session->set_flashdata('error', 'No active school year found.');
                redirect('studentprofile/enroll/' . $id);
            }

            // Get program_id and year_level from the selected grade level
            $program_id = $grade_level_id;
            $year_level = null;
            
            $check_academic = $this->db->query("SHOW TABLES LIKE 'academic_programs'")->num_rows();
            if ($check_academic > 0) {
                $check_year_level = $this->db->query("SHOW COLUMNS FROM academic_programs LIKE 'year_level'")->num_rows();
                if ($check_year_level > 0) {
                    $prog = $this->db->select('year_level')->where('id', $grade_level_id)->get('academic_programs')->row();
                    if ($prog && isset($prog->year_level)) {
                        $year_level = $prog->year_level;
                    }
                }
            } else {
                $check_year_level = $this->db->query("SHOW COLUMNS FROM programs LIKE 'year_level'")->num_rows();
                if ($check_year_level > 0) {
                    $prog = $this->db->select('year_level')->where('id', $grade_level_id)->get('programs')->row();
                    if ($prog && isset($prog->year_level)) {
                        $year_level = $prog->year_level;
                    }
                }
            }

            // Create enrollment record
            $enrollment_data = array(
                'student_id' => $data['profile']->user_id,
                'school_id' => $this->school_id,
                'school_year_id' => $school_year->id,
                'grade_level_id' => $grade_level_id,
                'program_id' => $program_id,
                'year_level' => $year_level,
                'section_id' => $section_id,
                'status' => 'enrolled',
                'enrollment_date' => date('Y-m-d')
            );

            $this->db->insert('enrollments', $enrollment_data);

            // Update section adviser if provided
            if ($adviser_id) {
                $this->db->where('id', $section_id)->update('sections', array('adviser_id' => $adviser_id));
            }

            $this->session->set_flashdata('success', 'Student enrolled successfully.');
            redirect('studentprofile');
        }

        $data['title'] = 'Enroll Student';
        $this->render('studentprofile/enroll', $data);
    }

    public function bulk_upload()
    {
        if ($this->input->method() === 'post') {
            $config['upload_path'] = FCPATH . 'uploads/temp/';
            $config['allowed_types'] = 'csv';
            $config['max_size'] = 5120;
            $config['file_name'] = 'students_' . time();

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, true);
            }

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file')) {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('studentprofile/bulk_upload');
            }

            $file_data = $this->upload->data();
            $file_path = $file_data['full_path'];

            try {
                $handle = fopen($file_path, 'r');
                if ($handle === false) {
                    throw new Exception('Unable to open file');
                }

                $header = fgetcsv($handle);
                $success_count = 0;
                $error_count = 0;
                $errors = array();
                $row_num = 2;

                while (($data = fgetcsv($handle)) !== false) {
                    $student_number = isset($data[0]) ? trim($data[0]) : '';
                    $first_name = isset($data[1]) ? trim($data[1]) : '';
                    $middle_name = isset($data[2]) ? trim($data[2]) : '';
                    $last_name = isset($data[3]) ? trim($data[3]) : '';
                    $birth_date = isset($data[4]) ? trim($data[4]) : '';
                    $email = isset($data[5]) ? trim($data[5]) : '';

                    if (empty($student_number) || empty($first_name) || empty($last_name) || empty($birth_date)) {
                        $error_count++;
                        $errors[] = "Row $row_num: Missing required fields";
                        $row_num++;
                        continue;
                    }

                    // Check if student number already exists
                    $existing = $this->Studentprofile_model->get_by_student_number($student_number, $this->school_id);
                    if ($existing) {
                        $error_count++;
                        $errors[] = "Row $row_num: Student Number $student_number already exists";
                        $row_num++;
                        continue;
                    }

                    // Check if student number already exists in users table
                    $existing_user = $this->db->where('email', $student_number)->get('users')->row();
                    if ($existing_user) {
                        $error_count++;
                        $errors[] = "Row $row_num: Student Number $student_number already exists as login";
                        $row_num++;
                        continue;
                    }

                    // Create user record
                    $password = date('Y-m-d', strtotime($birth_date));
                    $user_data = array(
                        'email' => $student_number,
                        'password' => password_hash($password, PASSWORD_BCRYPT),
                        'first_name' => $first_name,
                        'middle_name' => $middle_name,
                        'last_name' => $last_name,
                        'role_id' => $this->User_model->get_role_id_by_slug('student'),
                        'school_id' => $this->school_id,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('users', $user_data);
                    $user_id = $this->db->insert_id();

                    // Create student profile
                    $profile_data = array(
                        'user_id' => $user_id,
                        'school_id' => $this->school_id,
                        'student_number' => $student_number,
                        'email' => $email,
                        'first_name' => $first_name,
                        'middle_name' => $middle_name,
                        'last_name' => $last_name,
                        'birth_date' => $birth_date
                    );
                    $this->Studentprofile_model->create($profile_data);
                    $success_count++;
                    $row_num++;
                }

                fclose($handle);
                unlink($file_path);

                $message = "Bulk upload completed. Success: $success_count, Errors: $error_count";
                if (!empty($errors)) {
                    $message .= ". Errors: " . implode('; ', array_slice($errors, 0, 5));
                    if (count($errors) > 5) {
                        $message .= "... and " . (count($errors) - 5) . " more";
                    }
                }
                $this->session->set_flashdata('success', $message);
                redirect('studentprofile');

            } catch (Exception $e) {
                if (isset($handle) && $handle !== false) {
                    fclose($handle);
                }
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                $this->session->set_flashdata('error', 'Error processing file: ' . $e->getMessage());
                redirect('studentprofile/bulk_upload');
            }
        }

        $data['title'] = 'Bulk Upload Students';
        $this->render('studentprofile/bulk_upload', $data);
    }

    public function download_template()
    {
        // Generate CSV template with sample data
        $csv_data = "Student Number,First Name,Middle Name,Last Name,Birth Date (YYYY-MM-DD),Email (Optional)\n";
        $csv_data .= "2025-0001,Juan,Dela,Cruz,2010-05-15,juan.cruz@email.com\n";
        $csv_data .= "2025-0002,Maria,Santos,Reyes,2010-06-20,maria.reyes@email.com\n";
        $csv_data .= "2025-0003,Jose, Garcia,Lim,2010-07-10,jose.lim@email.com\n";
        $csv_data .= "2025-0004,Ana,Mendoza,Tan,2010-08-05,ana.tan@email.com\n";
        $csv_data .= "2025-0005,Carlos,Dizon,Ong,2010-09-12,carlos.ong@email.com\n";
        $csv_data .= "2025-0006,Patricia,Ng,Chua,2010-10-18,patricia.chua@email.com\n";
        $csv_data .= "2025-0007,Michael,Reyes,Co,2010-11-25,michael.co@email.com\n";
        $csv_data .= "2025-0008,Elizabeth,Sy,Lee,2010-12-30,elizabeth.lee@email.com\n";
        $csv_data .= "2025-0009,David,Go,Ho,2011-01-15,david.ho@email.com\n";
        $csv_data .= "2025-0010,Sarah,Cheng,Wong,2011-02-20,sarah.wong@email.com\n";
        $csv_data .= "2025-0011,Robert,Tan,Lau,2011-03-10,robert.lau@email.com\n";
        $csv_data .= "2025-0012,Jennifer,Lim,Chan,2011-04-05,jennifer.chan@email.com\n";
        $csv_data .= "2025-0013,William,Huang,Wu,2011-05-18,william.wu@email.com\n";
        $csv_data .= "2025-0014,Amanda,Zhao,Liu,2011-06-22,amanda.liu@email.com\n";
        $csv_data .= "2025-0015,Christopher,Wang,Xu,2011-07-08,christopher.xu@email.com\n";
        $csv_data .= "2025-0016,Jessica,Li,Yang,2011-08-14,jessica.yang@email.com\n";
        $csv_data .= "2025-0017,Daniel,Zhou,Zhang,2011-09-25,daniel.zhang@email.com\n";
        $csv_data .= "2025-0018,Michelle,Chen,Lin,2011-10-30,michelle.lin@email.com\n";
        $csv_data .= "2025-0019,Matthew,Wu,Zhao,2011-11-12,matthew.zhao@email.com\n";
        $csv_data .= "2025-0020,Laura,Xu,Wang,2011-12-20,laura.wang@email.com\n";
        $csv_data .= "2025-0021,Andrew,Sun,Li,2012-01-08,andrew.li@email.com\n";
        $csv_data .= "2025-0022,Stephanie,Moon,Kim,2012-02-15,stephanie.kim@email.com\n";
        $csv_data .= "2025-0023,Joshua,Park,Lee,2012-03-22,joshua.lee@email.com\n";
        $csv_data .= "2025-0024,Emily,Choi,Yoon,2012-04-10,emily.yoon@email.com\n";
        $csv_data .= "2025-0025,Brian,Kang,Seo,2012-05-18,brian.seo@email.com\n";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="student_profile_template.csv"');
        header('Cache-Control: max-age=0');

        echo $csv_data;
        exit;
    }
}
