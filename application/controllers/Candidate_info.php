<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Candidate_info extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
    }

    function candidate_details()
    {
        $this->load->view('candidate_info/candidate_details');
    }

    function approval()
    {
        $this->load->view('candidate_info/approval');
    }

    function history()
    {
        $this->load->view('candidate_info/history');
    }
}
