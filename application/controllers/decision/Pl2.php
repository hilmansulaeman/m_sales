<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Pl extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('decision/pl_model');
	}

	function index()
	{
		$sales_code = $this->session->userdata('sl_code');
		$posisi = $this->session->userdata('position');
		$varPos = "";
		if($this->input->post('date') == "")
		{
			$tgl = date('Y-m');
		}
		else
		{
			$tgl = $this->input->post('date');
		}
		
		if($posisi == "DSR" or $posisi == "SPG" or $posisi == "SPB")
		{
			$varPos = "DSR_Code";
		}elseif($posisi == "SPV")
		{
			$varPos = "SPV_Code";
		}elseif($posisi == "ASM")
		{
			$varPos = "ASM_Code";
		}elseif($posisi == "RSM")
		{
			$varPos = "RSM_Code";
		}elseif($posisi == "BSH" OR $posisi == "ASH")
		{
			$varPos = "BSH_Code";
		}
		//$data['counter'] = $this->pl_model->getCounter($sales_code, $varPos, $tgl);
		$data['breakdown_pl'] = $this->pl_model->breakdown_pl($sales_code, $tgl);
		//load view
		$this->template->set('title','Data Decision PL');
		$this->template->load('template','decision/pl/index', $data);
	}
	
	function det_breakdown_pl($status, $tgl)
	{
		$sales_code = $this->session->userdata('sl_code');
		$posisi = $this->session->userdata('position');
		$varPos = "";
		if($posisi == "DSR" or $posisi == "PSG" or $posisi == "SPB")
		{
			$varPos = "DSR_Code";
		}elseif($posisi == "SPV")
		{
			$varPos = "SPV_Code";
		}elseif($posisi == "ASM")
		{
			$varPos = "ASM_Code";
		}elseif($posisi == "RSM")
		{
			$varPos = "RSM_Code";
		}elseif($posisi == "BSH" OR $posisi == "ASH")
		{
			$varPos = "BSH_Code";
		}
		$data['query'] = $this->pl_model->getBreakdownPl($sales_code, $varPos, $status, $tgl);
		
		//load view
		$this->load->view('decision/pl/breakdown_pl', $data);
	}
}