<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Application_check extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
    }

    function pemol()
    {
        $this->load->view('application_check/application_check_pemol');
    }

    function merchant()
    {
        $this->load->view('application_check/application_check_merchant');
    }

    function cc()
    {
        $this->load->view('application_check/application_check_cc');
    }

    function corporate()
    {
        $this->load->view('application_check/application_check_corporate');
    }

    function sc()
    {
        $this->load->view('application_check/application_check_sc');
    }

    function pl()
    {
        $this->load->view('application_check/application_check_pl');
    }
}
