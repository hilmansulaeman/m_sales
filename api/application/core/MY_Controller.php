<?php if(!defined('BASEPATH')) exit ('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    function __construct() 
	{
        parent::__construct();
        $this->load->helper('url');
		
        if(!$this->auth->is_logged_in())
		{
            redirect('panel/login/');
        }
    }
}

class Cms_Controller extends CI_Controller
{
    function __construct()
	{
        parent::__construct();
    }
}
