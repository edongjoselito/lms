<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol']     = 'smtp';
$config['smtp_host']    = 'mail.srmsportal.com';
$config['smtp_user']    = 'berps@softtechservices.net';
$config['smtp_pass']    = getenv('SRMS_SMTP_PASS') ?: 'moth34board';
$config['smtp_port']    = 465;
$config['smtp_crypto']  = 'ssl';
$config['mailtype']     = 'html';
$config['charset']      = 'utf-8';
$config['newline']      = "\r\n";
$config['crlf']         = "\r\n";
$config['wordwrap']     = TRUE;
