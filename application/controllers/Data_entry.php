<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Data_entry extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
    }

    function index()
    {
        $this->load->view('data_entry/data_entry');
    }
}
