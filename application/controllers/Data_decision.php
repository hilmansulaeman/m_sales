<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Data_decision extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
    }

    function pemol()
    {
        $this->load->view('data_decision/data_decision_pemol');
    }

    function merchant()
    {
        $this->load->view('data_decision/data_decision_merchant');
    }

    function cc()
    {
        $this->load->view('data_decision/data_decision_cc');
    }

    function corporate()
    {
        $this->load->view('data_decision/data_decision_corporate');
    }

    function sc()
    {
        $this->load->view('data_decision/data_decision_sc');
    }

    function pl()
    {
        $this->load->view('data_decision/data_decision_pl');
    }

    function pemol_dsr()
    {
        $this->load->view('data_decision/data_decision_pemol_dsr');
    }
}
