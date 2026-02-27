<?php if(!defined('BASEPATH')) exit ('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    function __construct() 
	{
        parent::__construct();
        $this->load->library(array('auth','ar_acl'));
		date_default_timezone_set('Asia/Jakarta');
        $this->load->helper('url');
		
        if(!$this->auth->is_logged_in())
		{
            redirect('login');
        }
    }

    //string validation input
    public function check_string($str)
    {
        if (preg_match_all(" /^<|>|'|\"/ ", $str)) {
            // Let's return false for the validation and set a custom message for this function
            $this->form_validation->set_message("check_string", "Karakter (<|>|'|\") tidak diizinkan");
            return false;
        } else {
            return true;
        }
    }

}