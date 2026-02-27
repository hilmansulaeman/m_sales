<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Tele extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('incoming/tele_model');
	}

	function index()
	{
		$Sales_Code = $this->session->userdata('sl_code');
        $posisi = $this->session->userdata('position');
        $var_code="";
        $tgl1 = date('Y-m-01');
        $tgl2 = date('Y-m-d');
		
		if ($posisi == "SPV" ) {
            $var_code = "SPV_Code";
        }
		elseif ($posisi == "ASM" ) {
            $var_code = "ASM_Code";
        }
		elseif ($posisi == "RSM" ) {
            $var_code = "RSM_Code";
        }
		elseif ($posisi == "BSH" OR $posisi == "ASH") {
            $var_code = "BSH_Code";
        }
		else{
		    $var_code = "DSR_Code";
		}

		$data['sql_total'] = $this->tele_model->total($Sales_Code, $var_code, $tgl1, $tgl2);
		$data['breakdown_tele'] = $this->tele_model->m_breakdown_tele($Sales_Code, $var_code, $tgl1, $tgl2);
		
		//load view
		$this->template->set('title','Data Incoming');
		$this->template->load('template','incoming/tele/index', $data);
	}
	
	function det_breakdown_tele($status)
	{
		$Sales_Code = $this->session->userdata('sl_code');
        $posisi = $this->session->userdata('position');
        $var_code="";
        $tgl1 = date('Y-m-01');
        $tgl2 = date('Y-m-d');
        if ($posisi == "SPV" ) {
            $var_code = "SPV_Code";
			$view = "det_breakdown_cc_leader";
        }
		elseif ($posisi == "ASM" ) {
            $var_code = "ASM_Code";
			$view = "det_breakdown_cc_leader";
        }
		elseif ($posisi == "RSM" ) {
            $var_code = "RSM_Code";
			$view = "det_breakdown_cc_leader";
        }
		elseif ($posisi == "BSH" OR $posisi == "ASH" ) {
            $var_code = "BSH_Code";
			$view = "det_breakdown_cc_leader";
        }
		else
        {
            $var_code = "DSR_Code";
			$view = "det_breakdown_cc";
        }
		
		$data['query'] = $this->tele_model->m_det_breakdown_tele($Sales_Code, $var_code, $tgl1, $tgl2, $status);
		
		//load view
        $this->load->view('incoming/cc/'.$view, $data);
	}
	
	function filter_incoming()
	{
		//load view
        $this->template->set('title','Data Incoming');
        $this->load->view('incoming/filter_incoming');
	}
	
	
	
	
}