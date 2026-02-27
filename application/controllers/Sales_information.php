<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_information extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
    }

    function index()
    {
        $this->load->view('sales_information/sales_information');
    }
}
