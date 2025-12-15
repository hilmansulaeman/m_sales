<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Incoming extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('incoming_model');
	}

	function index($part)
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

		$data['sql_total'] = $this->incoming_model->total($Sales_Code, $var_code, $tgl1, $tgl2);
		$data['breakdown_cc'] = $this->incoming_model->m_breakdown_cc($Sales_Code, $var_code, $tgl1, $tgl2);
		$data['breakdown_edc'] = $this->incoming_model->m_breakdown_edc($Sales_Code, $var_code, $tgl1, $tgl2);
		$data['breakdown_sc'] = $this->incoming_model->m_breakdown_sc($Sales_Code, $var_code, $tgl1, $tgl2);
		$data['breakdown_pl'] = $this->incoming_model->m_breakdown_pl($Sales_Code, $var_code, $tgl1, $tgl2);
		$data['breakdown_corp'] = $this->incoming_model->m_breakdown_corp($Sales_Code, $var_code, $tgl1, $tgl2);
		$data['breakdown_tele'] = $this->incoming_model->m_breakdown_tele($Sales_Code, $var_code, $tgl1, $tgl2);
		$data['breakdown_merchant'] = $this->incoming_model->m_breakdown_merchant($Sales_Code, $var_code, $tgl1, $tgl2);
        $data['part'] = $part;
		
		//load view
		$this->template->set('title','Data Incoming');
		$this->template->load('template','incoming/index', $data);
	}

	function det_breakdown_cc($status)
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

		$data['query'] = $this->incoming_model->m_det_breakdown_cc($Sales_Code, $var_code, $tgl1, $tgl2, $status);

		//load view
		$this->load->view('incoming/'.$view, $data);
	}

	function det_breakdown_edc($status)
	{
		$Sales_Code = $this->session->userdata('sl_code');
        $posisi = $this->session->userdata('position');
        $var_code="";
        $tgl1 = date('Y-m-01');
        $tgl2 = date('Y-m-d');
        if ($posisi == "SPV" ) {
            $var_code = "SPV_Code";
			$view = "det_breakdown_edc_leader";
        }
		elseif ($posisi == "ASM" ) {
            $var_code = "ASM_Code";
			$view = "det_breakdown_edc_leader";
        }
		elseif ($posisi == "RSM" ) {
            $var_code = "RSM_Code";
			$view = "det_breakdown_edc_leader";
        }
		elseif ($posisi == "BSH" OR $posisi == "ASH" ) {
            $var_code = "BSH_Code";
			$view = "det_breakdown_edc_leader";
        }
		else
        {
            $var_code = "DSR_Code";
			$view = "det_breakdown_edc";
        }

        $data['query'] = $this->incoming_model->m_det_breakdown_edc($Sales_Code, $var_code, $tgl1, $tgl2, $status);

        //load view
		$this->load->view('incoming/'.$view, $data);
	}
	
	function det_breakdown_merchant($status)
	{
		$Sales_Code = $this->session->userdata('sl_code');
        $posisi = $this->session->userdata('position');
        $var_code="";
        $tgl1 = date('Y-m-01');
        $tgl2 = date('Y-m-d');
        if ($posisi == "SPV" ) {
            $var_code = "SPV_Code";
			$view = "det_breakdown_merchant_leader";
        }
		elseif ($posisi == "ASM" ) {
            $var_code = "ASM_Code";
			$view = "det_breakdown_merchant_leader";
        }
		elseif ($posisi == "RSM" ) {
            $var_code = "RSM_Code";
			$view = "det_breakdown_merchant_leader";
        }
		elseif ($posisi == "BSH" OR $posisi == "ASH" ) {
            $var_code = "BSH_Code";
			$view = "det_breakdown_merchant_leader";
        }
		else
        {
            $var_code = "DSR_Code";
			$view = "det_breakdown_merchant";
        }

        $data['query'] = $this->incoming_model->m_det_breakdown_merchant($Sales_Code, $var_code, $tgl1, $tgl2, $status);

        //load view
		$this->load->view('incoming/'.$view, $data);
	}

    function det_breakdown_sc($status)
    {
        $Sales_Code = $this->session->userdata('sl_code');
        $posisi = $this->session->userdata('position');
        $var_code="";
        $tgl1 = date('Y-m-01');
        $tgl2 = date('Y-m-d');
        if ($posisi == "SPV" ) {
            $var_code = "SPV_Code";
			$view = "det_breakdown_sc_leader";
        }
		elseif ($posisi == "ASM" ) {
            $var_code = "ASM_Code";
			$view = "det_breakdown_sc_leader";
        }
		elseif ($posisi == "RSM" ) {
            $var_code = "RSM_Code";
			$view = "det_breakdown_sc_leader";
        }
		elseif ($posisi == "BSH" OR $posisi == "ASH" ) {
            $var_code = "BSH_Code";
			$view = "det_breakdown_sc_leader";
        }
		else
        {
            $var_code = "DSR_Code";
			$view = "det_breakdown_sc";
        }

        $data['query'] = $this->incoming_model->m_det_breakdown_sc($Sales_Code, $var_code, $tgl1, $tgl2, $status);

        //load view
        $this->load->view('incoming/'.$view, $data);
    }
	
	function det_breakdown_pl($status)
	{
		$Sales_Code = $this->session->userdata('sl_code');
        $posisi = $this->session->userdata('position');
        $var_code="";
        $tgl1 = date('Y-m-01');
        $tgl2 = date('Y-m-d');
        if ($posisi == "SPV" ) {
            $var_code = "SPV_Code";
			$view = "det_breakdown_pl_leader";
        }
		elseif ($posisi == "ASM" ) {
            $var_code = "ASM_Code";
			$view = "det_breakdown_pl_leader";
        }
		elseif ($posisi == "RSM" ) {
            $var_code = "RSM_Code";
			$view = "det_breakdown_pl_leader";
        }
		elseif ($posisi == "BSH" OR $posisi == "ASH" ) {
            $var_code = "BSH_Code";
			$view = "det_breakdown_pl_leader";
        }
		else
        {
            $var_code = "DSR_Code";
			$view = "det_breakdown_pl";
        }
		
		$data['query'] = $this->incoming_model->m_det_breakdown_pl($Sales_Code, $var_code, $tgl1, $tgl2, $status);
		
		//load view
        $this->load->view('incoming/'.$view, $data);
	}
	
	function det_breakdown_corp($status)
	{
		$Sales_Code = $this->session->userdata('sl_code');
        $posisi = $this->session->userdata('position');
        $var_code="";
        $tgl1 = date('Y-m-01');
        $tgl2 = date('Y-m-d');
        if ($posisi == "SPV" ) {
            $var_code = "SPV_Code";
			$view = "det_breakdown_corp_leader";
        }
		elseif ($posisi == "ASM" ) {
            $var_code = "ASM_Code";
			$view = "det_breakdown_corp_leader";
        }
		elseif ($posisi == "RSM" ) {
            $var_code = "RSM_Code";
			$view = "det_breakdown_corp_leader";
        }
		elseif ($posisi == "BSH" OR $posisi == "ASH" ) {
            $var_code = "BSH_Code";
			$view = "det_breakdown_corp_leader";
        }
		else
        {
            $var_code = "DSR_Code";
			$view = "det_breakdown_corp";
        }
		
		$data['query'] = $this->incoming_model->m_det_breakdown_corp($Sales_Code, $var_code, $tgl1, $tgl2, $status);
		
		//load view
        $this->load->view('incoming/'.$view, $data);
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
		
		$data['query'] = $this->incoming_model->m_det_breakdown_tele($Sales_Code, $var_code, $tgl1, $tgl2, $status);
		
		//load view
        $this->load->view('incoming/'.$view, $data);
	}
	
	function filter_incoming($part)
	{
        $data['part'] = $part;
		//load view
        $this->template->set('title','Data Incoming');
        $this->load->view('incoming/filter_incoming', $data);
	}
	
	
	
	
}