<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends MY_Controller
{
    protected $login_image_key = 'login_image';

    public function __construct()
    {
        parent::__construct();
        $this->require_role(array('super_admin'));
        $this->load->model('Setting_model');
        $this->load->model('Audit_model');
    }

    public function index()
    {
        $data['title'] = 'Settings';
        $data['settings_ready'] = $this->Setting_model->is_available();
        $data['login_image'] = $this->Setting_model->get_value($this->login_image_key);
        $data['login_image_url'] = $this->get_login_image_url($data['login_image']);

        $this->render('settings/index', $data);
    }

    public function upload_login_image()
    {
        if (!$this->Setting_model->is_available()) {
            $this->session->set_flashdata('error', 'Unable to prepare the platform settings table automatically.');
            redirect('settings');
        }

        if (empty($_FILES['login_image']['name'])) {
            $this->session->set_flashdata('error', 'Please choose an image to upload.');
            redirect('settings');
        }

        $upload_path = FCPATH . 'uploads/login/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        if (!is_writable($upload_path)) {
            @chmod($upload_path, 0755);
        }

        if (!is_writable($upload_path)) {
            $this->session->set_flashdata('error', 'The login image folder is not writable.');
            redirect('settings');
        }

        $upload_result = $this->store_login_image_upload('login_image', $upload_path);
        if (!$upload_result['success']) {
            $this->session->set_flashdata('error', $upload_result['error']);
            redirect('settings');
        }

        $old_image = $this->Setting_model->get_value($this->login_image_key);

        if (!empty($old_image)) {
            $old_path = $upload_path . $old_image;
            if (file_exists($old_path)) {
                unlink($old_path);
            }
        }

        $this->Setting_model->set_value($this->login_image_key, $upload_result['file_name']);
        $this->Audit_model->log('update', 'platform_setting', 0, 'Login Image', 'Updated BlueCampus login image.');

        $this->session->set_flashdata('success', 'Login image updated successfully.');
        redirect('settings');
    }

    public function remove_login_image()
    {
        if (!$this->Setting_model->is_available()) {
            $this->session->set_flashdata('error', 'Unable to prepare the platform settings table automatically.');
            redirect('settings');
        }

        $filename = $this->Setting_model->get_value($this->login_image_key);
        if (!empty($filename)) {
            $file_path = FCPATH . 'uploads/login/' . $filename;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $this->Setting_model->delete($this->login_image_key);
        $this->Audit_model->log('update', 'platform_setting', 0, 'Login Image', 'Removed BlueCampus login image.');

        $this->session->set_flashdata('success', 'Login image removed. Default login design is active.');
        redirect('settings');
    }

    protected function get_login_image_url($filename)
    {
        if (empty($filename)) {
            return null;
        }

        $file_path = FCPATH . 'uploads/login/' . $filename;
        if (!file_exists($file_path)) {
            return null;
        }

        return base_url('uploads/login/' . rawurlencode($filename));
    }

    protected function store_login_image_upload($field_name, $upload_path)
    {
        $file = isset($_FILES[$field_name]) ? $_FILES[$field_name] : null;
        if (empty($file) || !isset($file['tmp_name'])) {
            return array(
                'success' => false,
                'error' => 'Please choose an image to upload.',
            );
        }

        if ((int) $file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            return array(
                'success' => false,
                'error' => $this->get_upload_error_message((int) $file['error']),
            );
        }

        if ((int) $file['size'] > (4096 * 1024)) {
            return array(
                'success' => false,
                'error' => 'The selected image exceeds the 4MB limit.',
            );
        }

        $allowed_extensions = array('jpg', 'jpeg', 'jfif', 'png', 'webp', 'gif');
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowed_extensions, true)) {
            return array(
                'success' => false,
                'error' => 'Please upload a JPG, JFIF, PNG, WebP, or GIF image.',
            );
        }

        $image_info = @getimagesize($file['tmp_name']);
        if ($image_info === false || empty($image_info['mime'])) {
            return array(
                'success' => false,
                'error' => 'The selected file is not a valid image.',
            );
        }

        $normalized_extension = $this->normalize_image_extension($image_info['mime']);
        if ($normalized_extension === null) {
            return array(
                'success' => false,
                'error' => 'Please upload a JPG, JFIF, PNG, WebP, or GIF image. HEIC/HEIF files are not supported on the login page.',
            );
        }

        $target_name = md5(uniqid(mt_rand(), true)) . '.' . $normalized_extension;
        $target_path = rtrim($upload_path, '/\\') . DIRECTORY_SEPARATOR . $target_name;

        if (!@move_uploaded_file($file['tmp_name'], $target_path)) {
            return array(
                'success' => false,
                'error' => 'Unable to save the uploaded image.',
            );
        }

        return array(
            'success' => true,
            'file_name' => $target_name,
        );
    }

    protected function normalize_image_extension($mime)
    {
        $mime = strtolower(trim((string) $mime));

        $map = array(
            'image/jpeg' => 'jpg',
            'image/pjpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/x-png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        );

        return isset($map[$mime]) ? $map[$mime] : null;
    }

    protected function get_upload_error_message($error_code)
    {
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'The selected image exceeds the upload size limit.';

            case UPLOAD_ERR_PARTIAL:
                return 'The image upload was interrupted. Please try again.';

            case UPLOAD_ERR_NO_FILE:
                return 'Please choose an image to upload.';

            case UPLOAD_ERR_NO_TMP_DIR:
                return 'The server upload temp folder is missing.';

            case UPLOAD_ERR_CANT_WRITE:
                return 'The server could not write the uploaded image.';

            case UPLOAD_ERR_EXTENSION:
                return 'The upload was stopped by a server extension.';

            default:
                return 'Unable to upload the selected image.';
        }
    }
}
