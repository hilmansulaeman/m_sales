<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// $config['protocol'] = 'mail';
// $config['smtp_host'] = '202.137.31.194';
$config['charset'] = 'utf-8';
$config['useragent'] = 'CodeIgniter';
$config['protocol'] = 'smtp';
$config['smtp_crypto'] = 'ssl'; //mail, sendmail, or smtp
$config['smtp_host'] = 'mail.ptdika.com';
$config['smtp_port'] = 465;
$config['smtp_timeout'] = 5; //SMTP Timeout (in seconds)
$config['smtp_user'] = 'support@ptdika.com'; //change this
$config['smtp_pass'] = 'D1^#@bikinriweuh'; //change this //
$config['validate'] = TRUE; // bool whether to validate email or not
$config['wordwrap'] = TRUE;
$config['priority'] = 3; //Email Priority. 1 = highest. 5 = lowest. 3 = normal.
$config['newline'] = "\r\n"; //use double quotes to comply with RFC 822 standard


