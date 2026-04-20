<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mode extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
    }

    public function toggle()
    {
        $this->toggle_student_mode();
    }
}
