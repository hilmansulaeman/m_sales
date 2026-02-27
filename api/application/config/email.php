<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['charset'] = 'utf-8';
$config['useragent'] = 'CodeIgniter'; 
$config['protocol'] = 'smtp'; //mail, sendmail, or smtp
$config['smtp_crypto'] = 'ssl';
$config['mailtype'] = 'html';
$config['smtp_host'] = 'mail.ptdika.com'; //change this
$config['smtp_port'] = 465;
$config['smtp_timeout'] = 5; //SMTP Timeout (in seconds)
$config['smtp_user'] = 'support@ptdika.com'; //change this
$config['smtp_pass'] = 'D1^#@bikinriweuh'; //change this
$config['validation'] = TRUE; // bool whether to validate email or not
$config['wordwrap'] = TRUE;
$config['priority'] = 3; //Email Priority. 1 = highest. 5 = lowest. 3 = normal.
$config['newline'] = "\r\n"; //use double quotes to comply with RFC 822 standard


