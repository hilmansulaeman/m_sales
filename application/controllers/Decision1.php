<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Decision extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('decision_model');
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
		//$data['counter'] = $this->decision_model->getCounter($sales_code, $varPos, $tgl);
		$data['breakdown_cc'] = $this->decision_model->breakdown_cc($sales_code, $tgl);
		$data['breakdown_edc'] = $this->decision_model->breakdown_edc($sales_code, $tgl);
		$data['breakdown_sc'] = $this->decision_model->breakdown_sc($sales_code, $tgl);
		$data['breakdown_pl'] = $this->decision_model->breakdown_pl($sales_code, $tgl);
		$data['breakdown_corp'] = $this->decision_model->breakdown_corp($sales_code, $tgl);
		//load view
		$this->template->set('title','Data Decision');
		$this->template->load('template','decision/index', $data);
	}
	
	function det_breakdown_cc($status, $tgl)
	{
		$sales_code = $this->session->userdata('sl_code');
		$posisi = $this->session->userdata('position');
		$varPos = "";
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
		$data['query'] = $this->decision_model->getBreakdownCc($sales_code, $varPos, $status, $tgl);
		
		//load view
		$this->load->view('decision/breakdown_cc', $data);
	}
	
	function det_breakdown_edc($status, $tgl)
	{
		$sales_code = $this->session->userdata('sl_code');
		$posisi = $this->session->userdata('position');
		$varPos = "";
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
		$data['query'] = $this->decision_model->getBreakdownEdc($sales_code, $varPos, $status, $tgl);
		
		//load view
		$this->load->view('decision/breakdown_edc', $data);
	}
	
	function det_breakdown_sc($status, $tgl)
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
		$data['query'] = $this->decision_model->getBreakdownSc($sales_code, $varPos, $status, $tgl);
		
		//load view
		$this->load->view('decision/breakdown_sc', $data);
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
		$data['query'] = $this->decision_model->getBreakdownPl($sales_code, $varPos, $status, $tgl);
		
		//load view
		$this->load->view('decision/breakdown_pl', $data);
	}
	
	function det_breakdown_corp($status, $tgl)
	{
		$sales_code = $this->session->userdata('sl_code');
		$posisi = $this->session->userdata('position');
		$varPos = "";
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
		$data['query'] = $this->decision_model->getBreakdownCorp($sales_code, $varPos, $status, $tgl);
		
		//load view
		$this->load->view('decision/breakdown_corp', $data);
	}
}