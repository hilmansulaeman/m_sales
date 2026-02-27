<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Incoming extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
    }

    function mobile_sales()
    {
        $this->load->view('incoming/incoming_mobile_sales');
    }

    function pemol()
    {
        $this->load->view('incoming/incoming_pemol');
    }

    function tm_cc()
    {
        $this->load->view('incoming/incoming_tm_cc');
    }

    function tm_sc()
    {
        $this->load->view('incoming/incoming_tm_sc');
    }
}
