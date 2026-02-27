<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Slip_incentive extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
    }

    function index()
    {
        $this->load->view('slip_incentive/slip_incentive');
    }
}
