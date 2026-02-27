<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Check_postal_code extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
    }

    function index()
    {
        $this->load->view('check_postal_code/check_postal_code');
    }
}
