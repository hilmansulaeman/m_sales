<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Filter_incoming_qris extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
		$this->load->model('incoming/qris_model');
	}

	function index($tgl1, $tgl2)
	{
		$sales_code = $this->session->userdata('username');

		$data['status_aplikasi'] = $this->qris_model->get_status_api();
		$data['breakdown_qris'] = $this->qris_model->m_breakdown_qris($sales_code, $tgl1, $tgl2);
        //load view
		$this->template->set('title','Data Incoming QRIS');
		$this->template->load('template','incoming/qris/status_aplikasi', $data);
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