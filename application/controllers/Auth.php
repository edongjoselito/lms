<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Setting_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $data['login_image_url'] = $this->get_login_image_url();
        $this->load->view('auth/login', $data);
    }

    public function login()
    {
        if ($this->input->method() !== 'post') {
            redirect('auth');
        }

        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password');

        $user = $this->User_model->authenticate($email, $password);

        if ($user) {
            // Check if school admin's school is confirmed
            if ($user->role_slug === 'school_admin' && $user->school_id) {
                $school = $this->db->where('id', $user->school_id)->get('schools')->row();
                if ($school && $school->status == 0) {
                    notify_error('Your school account is not yet confirmed. Please check your email for the confirmation link.');
                    redirect('auth');
                }
            }

            $session_data = array(
                'user_id'    => $user->id,
                'role_id'    => $user->role_id,
                'role_slug'  => $user->role_slug,
                'role_name'  => $user->role_name,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'school_id'  => $user->school_id,
                'avatar'     => $user->avatar,
                'logged_in'  => TRUE
            );

            // Load school name if user belongs to a school
            if ($user->school_id) {
                $school = $this->db->where('id', $user->school_id)->get('schools')->row();
                $session_data['school_name'] = $school ? $school->name : '';
            }

            // Set student_id for students
            if ($user->role_slug === 'student') {
                $student = $this->db->where('user_id', $user->id)->get('students')->row();
                if ($student) {
                    $session_data['student_id'] = $student->id;
                } else {
                    // Try to find student by matching user_id with user table
                    $student_by_id = $this->db->where('id', $user->id)->get('students')->row();
                    if ($student_by_id) {
                        $session_data['student_id'] = $student_by_id->id;
                    }
                }
                $session_data['last_activity'] = time();
            }

            $this->session->set_userdata($session_data);
            $this->User_model->update_last_login($user->id);

            // Track login time for attendance (students only)
            if ($user->role_slug === 'student' && $user->school_id) {
                $this->_track_login($user->id, $user->school_id);
            }

            // Super admin without school → go to school selection
            if ($user->role_slug === 'super_admin' && !$user->school_id) {
                redirect('schools/select');
            }

            redirect('dashboard');
        } else {
            notify_error('Invalid email or password.');
            redirect('auth');
        }
    }

    public function logout()
    {
        $user_id = $this->session->userdata('user_id');
        $role_slug = $this->session->userdata('role_slug');
        $school_id = $this->session->userdata('school_id');

        // Track logout time for attendance (students only)
        if ($role_slug === 'student' && $school_id) {
            $this->_track_logout($user_id, $school_id);
        }

        $this->session->sess_destroy();
        redirect('auth');
    }

    public function keep_alive()
    {
        $this->output->set_content_type('application/json');

        if (!$this->session->userdata('logged_in')) {
            $this->output
                ->set_status_header(401)
                ->set_output(json_encode(array('success' => false)));
            return;
        }

        if ($this->session->userdata('role_slug') === 'student') {
            $this->session->set_userdata('last_activity', time());
        }

        $this->output->set_output(json_encode(array('success' => true)));
    }

    private function _track_login($user_id, $school_id)
    {
        $today = date('Y-m-d');

        // Check if there's already an attendance record for today with course_id=0
        $existing = $this->db->where('user_id', $user_id)
            ->where('course_id', 0)
            ->where('date', $today)
            ->get('attendance')->row();

        if ($existing) {
            // Update existing record with new login time
            $this->db->where('id', $existing->id)->update('attendance', array(
                'login_time' => date('Y-m-d H:i:s'),
                'logout_time' => null,
                'duration_minutes' => 0
            ));
        } else {
            // Create new attendance record for login
            $this->db->insert('attendance', array(
                'user_id' => $user_id,
                'course_id' => 0, // 0 means general LMS access, not course-specific
                'date' => $today,
                'login_time' => date('Y-m-d H:i:s'),
                'duration_minutes' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ));
        }
    }

    private function _track_logout($user_id, $school_id)
    {
        $today = date('Y-m-d');

        // Find today's attendance record without logout time
        $att = $this->db->where('user_id', $user_id)
            ->where('date', $today)
            ->where('logout_time IS NULL')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get('attendance')->row();

        if ($att && $att->login_time) {
            $login = strtotime($att->login_time);
            $logout = strtotime(date('Y-m-d H:i:s'));
            $duration_minutes = round(($logout - $login) / 60);

            // Update attendance with logout time and duration
            $this->db->where('id', $att->id)->update('attendance', array(
                'logout_time' => date('Y-m-d H:i:s'),
                'duration_minutes' => $duration_minutes
            ));
        }
    }

    public function forgot_password()
    {
        if ($this->input->method() === 'post') {
            $email = $this->input->post('email', TRUE);
            $user = $this->db->where('email', $email)->get('users')->row();

            if ($user) {
                // For now, just show a success message
                // In production, you would send an email with a reset link
                notify_success('Password reset instructions have been sent to your email.');
            } else {
                notify_error('Email not found.');
            }
            redirect('auth/forgot_password');
        }

        $this->load->view('auth/forgot_password');
    }

    private function get_login_image_url()
    {
        $filename = $this->Setting_model->get_value('login_image');
        if (empty($filename)) {
            return null;
        }

        $file_path = FCPATH . 'uploads/login/' . $filename;
        if (!file_exists($file_path)) {
            return null;
        }

        return base_url('uploads/login/' . rawurlencode($filename));
    }

    public function signup()
    {
        $this->load->model('School_model');
        $this->load->helper('string');

        // Generate captcha
        $captcha = $this->_generate_captcha();
        if ($captcha === false) {
            show_error('Captcha generation failed. Please ensure the uploads/captcha directory exists and is writable.');
        }

        $this->session->set_userdata('captcha_word', $captcha['word']);

        $data['captcha_image'] = $captcha['image'];
        $data['title'] = 'Sign Up for School Account';
        $data['form_data'] = $this->session->flashdata('form_data');
        $this->load->view('auth/signup', $data);
    }

    public function create_school()
    {
        $this->load->model('School_model');
        $this->load->helper('string');

        if ($this->input->method() !== 'post') {
            redirect('auth/signup');
        }

        // Get form data
        $school_id_number = $this->input->post('school_id_number', TRUE);
        $name = $this->input->post('name', TRUE);
        $type = $this->input->post('type', TRUE);
        $email = $this->input->post('email', TRUE);
        $contact_number = $this->input->post('contact_number', TRUE);
        $address = $this->input->post('address', TRUE);
        $district = $this->input->post('district', TRUE);
        $division = $this->input->post('division', TRUE);
        $region = $this->input->post('region', TRUE);
        $password = $this->input->post('password', TRUE);
        $confirm_password = $this->input->post('confirm_password', TRUE);
        $user_captcha = $this->input->post('captcha', TRUE);

        // Build form_data once so all error paths preserve every field
        $form_data = array(
            'school_id_number' => $school_id_number,
            'name'             => $name,
            'type'             => $type,
            'email'            => $email,
            'contact_number'   => $contact_number,
            'address'          => $address,
            'district'         => $district,
            'division'         => $division,
            'region'           => $region,
            'password'         => $password,
            'confirm_password' => $confirm_password,
        );

        // Validate captcha
        $session_captcha = $this->session->userdata('captcha_word');

        if (!$user_captcha || strtolower($user_captcha) !== strtolower($session_captcha)) {
            notify_error('Invalid captcha. Please try again.');
            $this->session->set_flashdata('form_data', $form_data);
            redirect('auth/signup');
        }

        // Clear captcha
        $this->session->unset_userdata('captcha_word');

        // Validate required fields
        if (empty($school_id_number) || empty($name) || empty($type) || empty($email)) {
            notify_error('Please fill in all required fields.');
            $this->session->set_flashdata('form_data', $form_data);
            redirect('auth/signup');
        }

        // Validate password
        if (empty($password) || strlen($password) < 8) {
            notify_error('Password must be at least 8 characters long.');
            $this->session->set_flashdata('form_data', $form_data);
            redirect('auth/signup');
        }

        if ($password !== $confirm_password) {
            notify_error('Passwords do not match.');
            $this->session->set_flashdata('form_data', $form_data);
            redirect('auth/signup');
        }

        // Check if school_id_number already exists
        $existing = $this->db->where('school_id_number', $school_id_number)->get('schools')->row();
        if ($existing) {
            notify_error('School ID Number already exists.');
            $this->session->set_flashdata('form_data', $form_data);
            redirect('auth/signup');
        }

        // Check if email already exists
        $existing_email = $this->db->where('email', $email)->get('schools')->row();
        if ($existing_email) {
            notify_error('Email already registered.');
            $this->session->set_flashdata('form_data', $form_data);
            redirect('auth/signup');
        }

        // Generate confirmation token
        $confirmation_token = random_string('alnum', 64);

        // Insert school with pending status
        $school_data = array(
            'school_id_number' => strtoupper($school_id_number),
            'name' => strtoupper($name),
            'type' => $type,
            'email' => $email,
            'contact_number' => $contact_number,
            'address' => $address,
            'district' => $district,
            'division' => $division,
            'region' => $region,
            'status' => 0, // Inactive until confirmed
            'confirmation_token' => $confirmation_token,
            'admin_password' => password_hash($password, PASSWORD_DEFAULT), // Store hashed password
            'created_at' => date('Y-m-d H:i:s')
        );

        $school_id = $this->School_model->create($school_data);

        if ($school_id) {
            // Send confirmation email
            $email_sent = $this->_send_confirmation_email($email, $name, $confirmation_token);

            if ($email_sent) {
                notify_success('Registration successful! Please check your email to confirm your account.');
                redirect('auth');
            } else {
                notify_error('Registration was saved, but the confirmation email could not be sent. Please contact the administrator for assistance.');
                $this->session->set_flashdata('form_data', $form_data);
                redirect('auth/signup');
            }
        } else {
            notify_error('Registration failed. Please try again.');
            $this->session->set_flashdata('form_data', $form_data);
            redirect('auth/signup');
        }
    }

    public function confirm_email($token)
    {
        $this->load->model('School_model');

        $school = $this->db->where('confirmation_token', $token)->get('schools')->row();

        if (!$school) {
            notify_error('Invalid confirmation token.');
            redirect('auth');
        }

        if ($school->status == 1) {
            notify_success('Your account is already confirmed.');
            redirect('auth');
        }

        // Activate school
        $this->db->where('id', $school->id)->update('schools', array(
            'status' => 1,
            'confirmation_token' => null,
            'confirmed_at' => date('Y-m-d H:i:s')
        ));

        // Create school admin account
        $this->_create_school_admin($school->id, $school->email);

        notify_success('Account confirmed successfully! You can now login.');
        redirect('auth');
    }

    private function _generate_captcha()
    {
        // Ensure captcha directory exists
        $captcha_path = FCPATH . 'uploads/captcha/';
        if (!is_dir($captcha_path)) {
            mkdir($captcha_path, 0755, true);
        }

        // Generate random word
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $word = '';
        for ($i = 0; $i < 6; $i++) {
            $word .= $chars[rand(0, strlen($chars) - 1)];
        }

        // Create image
        $width = 150;
        $height = 50;
        $image = imagecreatetruecolor($width, $height);

        // Colors
        $bg_color = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $line_color = imagecolorallocate($image, 200, 200, 200);

        // Fill background
        imagefill($image, 0, 0, $bg_color);

        // Add noise lines
        for ($i = 0; $i < 5; $i++) {
            $x1 = (int)rand(0, $width);
            $y1 = (int)rand(0, $height);
            $x2 = (int)rand(0, $width);
            $y2 = (int)rand(0, $height);
            imageline($image, $x1, $y1, $x2, $y2, $line_color);
        }

        // Add noise dots
        for ($i = 0; $i < 50; $i++) {
            $x = (int)rand(0, $width);
            $y = (int)rand(0, $height);
            imagesetpixel($image, $x, $y, $line_color);
        }

        // Add text with rotation
        $font_size = 16;
        $angle = 0;
        $x = 20;
        $y = 35;

        for ($i = 0; $i < strlen($word); $i++) {
            $angle = (int)rand(-15, 15);
            $char = $word[$i];
            imagettftext($image, $font_size, $angle, $x, $y, $text_color, FCPATH . 'system/fonts/texb.ttf', $char);
            $x += 20;
        }

        // Generate filename
        $filename = 'captcha_' . time() . '_' . rand(1000, 9999) . '.png';
        $filepath = $captcha_path . $filename;

        // Save image
        imagepng($image, $filepath);
        imagedestroy($image);

        return array(
            'word' => $word,
            'image' => base_url('uploads/captcha/' . $filename),
            'is_math' => false
        );
    }

    private function _send_confirmation_email($email, $school_name, $token)
    {
        $confirmation_link = site_url('auth/confirm_email/' . $token);
        if (!$this->config->load('email', TRUE, TRUE)) {
            log_message('error', 'Confirmation email config file could not be loaded.');
            return false;
        }

        $email_config = isset($this->config->config['email']) && is_array($this->config->config['email'])
            ? $this->config->config['email']
            : array();

        if (empty($email_config)) {
            $email_config = array(
                'protocol'     => $this->config->item('protocol'),
                'smtp_host'    => $this->config->item('smtp_host'),
                'smtp_user'    => $this->config->item('smtp_user'),
                'smtp_pass'    => $this->config->item('smtp_pass'),
                'smtp_port'    => $this->config->item('smtp_port'),
                'smtp_crypto'  => $this->config->item('smtp_crypto'),
                'smtp_timeout' => $this->config->item('smtp_timeout'),
                'mailtype'     => $this->config->item('mailtype'),
                'charset'      => $this->config->item('charset'),
                'newline'      => $this->config->item('newline'),
                'crlf'         => $this->config->item('crlf'),
                'wordwrap'     => $this->config->item('wordwrap'),
                'from_email'   => $this->config->item('from_email'),
                'from_name'    => $this->config->item('from_name')
            );
            $email_config = array_filter($email_config, static function ($value) {
                return $value !== NULL;
            });
        }

        if (!is_array($email_config) || empty($email_config)) {
            log_message('error', 'Confirmation email config is empty or invalid.');
            return false;
        }

        $this->load->library('email');
        $this->email->initialize($email_config);
        $this->email->clear(TRUE);

        $message = '<p>Dear School Administrator,</p>';
        $message .= '<p>Thank you for registering your school <strong>' . htmlspecialchars($school_name, ENT_QUOTES, 'UTF-8') . '</strong>.</p>';
        $message .= '<p>To complete your registration and activate your account, please click the button below:</p>';
        $message .= '<p><a href="' . $confirmation_link . '" style="display:inline-block;padding:12px 20px;background:#2563eb;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:700;">Confirm School Account</a></p>';
        $message .= '<p>If the button does not work, copy and paste this link into your browser:</p>';
        $message .= '<p><a href="' . $confirmation_link . '">' . $confirmation_link . '</a></p>';
        $message .= '<p>If you did not register for this account, please ignore this email.</p>';
        $message .= '<p>Best regards,<br>BlueCampus LMS</p>';

        $plain_message = "Dear School Administrator,\n\n";
        $plain_message .= "Thank you for registering your school '" . $school_name . "'.\n\n";
        $plain_message .= "To complete your registration and activate your account, please open the link below:\n\n";
        $plain_message .= $confirmation_link . "\n\n";
        $plain_message .= "If you did not register for this account, please ignore this email.\n\n";
        $plain_message .= "Best regards,\nBlueCampus LMS";

        $from_email = !empty($email_config['from_email'])
            ? (string) $email_config['from_email']
            : (!empty($email_config['smtp_user']) ? (string) $email_config['smtp_user'] : '');
        $from_name = !empty($email_config['from_name'])
            ? (string) $email_config['from_name']
            : 'BlueCampus LMS';

        if ($from_email === '') {
            log_message('error', 'Confirmation email sender address is not configured.');
            return false;
        }

        $this->email->from($from_email, $from_name);
        $this->email->reply_to($from_email, $from_name);
        $this->email->to($email);
        $this->email->subject('Confirm Your School Account');
        $this->email->message($message);
        $this->email->set_alt_message($plain_message);

        if ($this->email->send()) {
            return true;
        }

        log_message('error', 'Confirmation email failed to send to ' . $email . '. ' . strip_tags($this->email->print_debugger(array('headers', 'subject'))));
        return false;
    }

    private function _create_school_admin($school_id, $email)
    {
        // Get school admin role
        $school_admin_role = $this->db->where('slug', 'school_admin')->get('roles')->row();
        if (!$school_admin_role) {
            log_message('error', 'School admin role not found');
            return false;
        }

        // Get the school to check if admin_password was set during signup
        $school = $this->db->where('id', $school_id)->get('schools')->row();

        // Use stored password if available, otherwise generate random password
        if ($school && !empty($school->admin_password)) {
            $password_hash = $school->admin_password;
            $password = '(user-provided)';
        } else {
            // Generate random password
            $password = random_string('alnum', 12);
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
        }

        // Create school admin user
        $user_data = array(
            'email' => $email,
            'password' => $password_hash,
            'role_id' => $school_admin_role->id,
            'school_id' => $school_id,
            'first_name' => 'School',
            'last_name' => 'Admin',
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('users', $user_data);
        $user_id = $this->db->insert_id();

        // Clear the admin_password from schools table after use
        if ($school && !empty($school->admin_password)) {
            $this->db->where('id', $school_id)->update('schools', array('admin_password' => null));
        }

        // Log the credentials (in production, send via email)
        log_message('info', 'School admin created for school_id ' . $school_id . '. Email: ' . $email . ', Password: ' . $password);

        return $user_id;
    }

    public function captcha_refresh()
    {
        $captcha = $this->_generate_captcha();
        $this->session->set_userdata('captcha_word', $captcha['word']);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array('image' => $captcha['image'])));
    }

    public function check_email()
    {
        $email = $this->input->post('email', TRUE);

        if (empty($email)) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(array('exists' => false)));
            return;
        }

        $existing = $this->db->where('email', $email)->get('schools')->row();

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array('exists' => $existing ? true : false)));
    }
}
