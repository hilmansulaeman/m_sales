<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Request_to_hrd extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
    }

    function index()
    {
        $this->load->view('request_to_hrd/request_to_hrd');
    }

    function restruct()
    {
        $this->load->view('request_to_hrd/request_to_hrd_restruct');
    }

    function level()
    {
        $this->load->view('request_to_hrd/request_to_hrd_level');
    }

    function reactive()
    {
        $this->load->view('request_to_hrd/request_to_hrd_reactive');
    }
}
