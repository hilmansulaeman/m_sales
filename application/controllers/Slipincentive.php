<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/

class Slipincentive extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('slipincentive_model');
	}
	
	function index()
	{
		$periode = $this->input->post('periode');
		$sales_code = $this->session->userdata('sl_code');
		$data['combo'] = $this->slipincentive_model->getPeriode($sales_code);
		$data['query'] = $this->slipincentive_model->dataIncentive($sales_code, $periode);
		//load view
		$this->template->set('title','Data Incentive');
		$this->template->load('template','slipincentive/index', $data);
	}
}