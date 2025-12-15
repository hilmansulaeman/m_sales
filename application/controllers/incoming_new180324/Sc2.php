<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Sc extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('incoming/sc_model');
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

		$data['sql_total'] = $this->sc_model->total($Sales_Code, $var_code, $tgl1, $tgl2);
		$data['breakdown_sc'] = $this->sc_model->m_breakdown_sc($Sales_Code, $var_code, $tgl1, $tgl2);
		
		//load view
		$this->template->set('title','Data Incoming');
		$this->template->load('template','incoming/sc/index', $data);
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

        $data['query'] = $this->sc_model->m_det_breakdown_sc($Sales_Code, $var_code, $tgl1, $tgl2, $status);

        //load view
        $this->load->view('incoming/sc/'.$view, $data);
    }
	
	function filter_incoming()
	{
		//load view
        $this->template->set('title','Data Incoming');
        $this->load->view('incoming/filter_incoming');
	}
	
	
	
	
}