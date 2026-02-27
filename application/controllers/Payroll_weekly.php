<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_weekly extends MY_Controller
{
	function __construct()
    {
        parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('payroll_weekly_model');
    }
	
	function index()
	{
		$sales_code = $this->session->userdata('sl_code');
		$periode = $this->input->post('periode');
		if($periode == "" or $periode == "--Pilih--")
		{
			$periode = date('Y-m');
		}
		$data['periode'] = $this->payroll_weekly_model->getPeriode($sales_code);
		$count = $this->payroll_weekly_model->getCountPayroll($sales_code, $periode);
		$cek = $count->row();
		if($cek->counts > 0)
		{
			$data['notif1'] = "none";
			$data['notif2'] = "block";
		}
		else
		{
			$data['notif1'] = "block";
			$data['notif2'] = "none";
		}
		$data['query'] = $this->payroll_weekly_model->getDataPayroll($sales_code, $periode);
		
		//load view
		$this->template->set('title','Payroll Weekly');
		$this->template->load('template','payroll_weekly/index', $data);
	}
}