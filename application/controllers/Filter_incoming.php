<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Filter_incoming extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
		$this->load->model('filter_incoming_model');
	}

	function index($part, $tgl1, $tgl2)
	{
		$Sales_Code = $this->session->userdata('sl_code');
        $posisi = $this->session->userdata('position');
        $var_code="";
        if($posisi == "DSR")
        {
            $var_code = "sales_code";
        }elseif ($posisi == "SPV" ) {
            $var_code = "spv_code";
        }elseif ($posisi == "ASM" ) {
            $var_code = "asm_code";
        }elseif ($posisi == "RSM" ) {
            $var_code = "rsm_code";
        }elseif ($posisi == "BSH" OR $posisi == "ASH") {
            $var_code = "bsh_code";
        }
		
		$data['sql_counter'] = $this->filter_incoming_model->view($Sales_Code, $var_code, $tgl1, $tgl2);
        $data['sql_br_cc'] = $this->filter_incoming_model->breakdown_sts_cc($Sales_Code, $var_code, $tgl1, $tgl2);
        $data['sql_br_edc'] = $this->filter_incoming_model->breakdown_sts_edc($Sales_Code, $var_code, $tgl1, $tgl2);
        $data['sql_br_sc'] = $this->filter_incoming_model->breakdown_sts_sc($Sales_Code, $var_code, $tgl1, $tgl2);
        $data['sql_br_pl'] = $this->filter_incoming_model->breakdown_sts_pl($Sales_Code, $var_code, $tgl1, $tgl2);
        $data['sql_br_corp'] = $this->filter_incoming_model->breakdown_sts_corp($Sales_Code, $var_code, $tgl1, $tgl2);
        $data['sql_br_tele'] = $this->filter_incoming_model->breakdown_sts_tele($Sales_Code, $var_code, $tgl1, $tgl2);
		$data['part'] = $part;
		
		//load view
		$this->template->set('title','Data Incoming');
		$this->template->load('template','filter_incoming/index', $data);
	}
	
	function detail_br_cc($field, $tgl1, $tgl2, $status)
	{
		$Sales_Code = $this->session->userdata('sl_code');
		$data['query'] = $this->filter_incoming_model->detail_breakdown_cc($Sales_Code, $field, $tgl1, $tgl2, $status);
		//load view
		$this->load->view('filter_incoming/breakdown_cc', $data);
	}
	
	function detail_br_edc($field, $tgl1, $tgl2, $status)
	{
		$Sales_Code = $this->session->userdata('sl_code');
		$data['query'] = $this->filter_incoming_model->detail_breakdown_edc($Sales_Code, $field, $tgl1, $tgl2, $status);
		//load view
		$this->template->set('title','Data Incoming');
		$this->load->view('filter_incoming/breakdown_edc', $data);
	}
	
	function detail_br_sc($field, $tgl1, $tgl2, $status)
	{
		$Sales_Code = $this->session->userdata('sl_code');
		$data['query'] = $this->filter_incoming_model->detail_breakdown_sc($Sales_Code, $field, $tgl1, $tgl2, $status);
		//load view
		$this->template->set('title','Data Incoming');
		$this->load->view('filter_incoming/breakdown_sc', $data);
	}
	
	function detail_br_pl($field, $tgl1, $tgl2, $status)
	{
		$Sales_Code = $this->session->userdata('sl_code');
		$data['query'] = $this->filter_incoming_model->detail_breakdown_pl($Sales_Code, $field, $tgl1, $tgl2, $status);
		//load view
		$this->load->view('filter_incoming/breakdown_pl', $data);
	}
	
	function detail_br_corp($field, $tgl1, $tgl2, $status)
	{
		$Sales_Code = $this->session->userdata('sl_code');
		$data['query'] = $this->filter_incoming_model->detail_breakdown_corp($Sales_Code, $field, $tgl1, $tgl2, $status);
		//load view
		$this->load->view('filter_incoming/breakdown_corp', $data);
	}
	
	function detail_br_tele($field, $tgl1, $tgl2, $status)
	{
		$Sales_Code = $this->session->userdata('sl_code');
		$data['query'] = $this->filter_incoming_model->detail_breakdown_tele($Sales_Code, $field, $tgl1, $tgl2, $status);
		//load view
		$this->load->view('filter_incoming/breakdown_corp', $data);
	}
	
	//================================================= INTERNAL FUNCTION =============================================//
	//datedif
	function datediff($start,$end)
	{
		$date1 = date_create($start);
		$date2 = date_create($end);
		
		$days = date_diff($date1,$date2);
		
		return $days->format('%R%a');
	}
	
}