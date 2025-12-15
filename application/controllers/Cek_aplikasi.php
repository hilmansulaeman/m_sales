<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Cek_aplikasi extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('form','url', 'html'));
        $this->load->model('cek_aplikasi_model');
	}
	
	function pemol()
	{
		$position = $this->session->userdata('position');
		$leader_position = array('SPV','ASM','RSM','BSH');
		if(in_array($position,$leader_position)){
			$sales = '';
		}
		else{
			$sales = $this->session->userdata('sl_code');
		}
		
		if(isset($_GET['key'])){
			$data['key']      = $_GET['key'];
			$data['incoming'] = $this->cek_aplikasi_model->get_incoming_pemol($data['key'],$sales);
			$data['decision'] = $this->cek_aplikasi_model->get_decision_pemol($data['key'],$sales);
		}
		else{
			$data['key']      = '';
			$data['incoming'] = '';
			$data['decision'] = '';
		}
		
		
		//load view
		$this->template->set('title','Cek Aplikasi Pemol');
		$this->template->load('template','cek_aplikasi/pemol', $data);
	}
	
	function merchant()
	{
		$position = $this->session->userdata('position');
		$leader_position = array('SPV','ASM','RSM','BSH');
		if(in_array($position,$leader_position)){
			$sales = '';
		}
		else{
			$sales = $this->session->userdata('sl_code');
		}
		
		if(isset($_GET['key'])){
			$data['key']      = $_GET['key'];
			$data['incoming'] = $this->cek_aplikasi_model->get_incoming_merchant($data['key'],$sales);
			$data['decision'] = $this->cek_aplikasi_model->get_decision_merchant($data['key'],$sales);
		}
		else{
			$data['key']      = '';
			$data['incoming'] = '';
			$data['decision'] = '';
		}
		
		
		//load view
		$this->template->set('title','Cek Aplikasi Merchant');
		$this->template->load('template','cek_aplikasi/merchant', $data);
	}
	
	function cc()
	{
		$position = $this->session->userdata('position');
		$leader_position = array('SPV','ASM','RSM','BSH');
		if(in_array($position,$leader_position)){
			$sales = '';
		}
		else{
			$sales = $this->session->userdata('sl_code');
		}
		
		if(isset($_GET['key'])){
			$data['key']      = $_GET['key'];
			$data['incoming'] = $this->cek_aplikasi_model->get_incoming_cc($data['key'],$sales);
			$data['decision'] = $this->cek_aplikasi_model->get_decision_cc($data['key'],$sales);
		}
		else{
			$data['key']      = '';
			$data['incoming'] = '';
			$data['decision'] = '';
		}
		
		
		//load view
		$this->template->set('title','Cek Aplikasi CC');
		$this->template->load('template','cek_aplikasi/cc', $data);
	}
	
	function corp()
	{
		$position = $this->session->userdata('position');
		$leader_position = array('SPV','ASM','RSM','BSH');
		if(in_array($position,$leader_position)){
			$sales = '';
		}
		else{
			$sales = $this->session->userdata('sl_code');
		}
		
		if(isset($_GET['key'])){
			$data['key']      = $_GET['key'];
			$data['incoming'] = $this->cek_aplikasi_model->get_incoming_corporate($data['key'],$sales);
			$data['decision'] = $this->cek_aplikasi_model->get_decision_corporate($data['key'],$sales);
		}
		else{
			$data['key']      = '';
			$data['incoming'] = '';
			$data['decision'] = '';
		}
		
		
		//load view
		$this->template->set('title','Cek Aplikasi Corporate');
		$this->template->load('template','cek_aplikasi/corporate', $data);
	}
	
	function sc()
	{
		$position = $this->session->userdata('position');
		$leader_position = array('SPV','ASM','RSM','BSH');
		if(in_array($position,$leader_position)){
			$sales = '';
		}
		else{
			$sales = $this->session->userdata('sl_code');
		}
		
		if(isset($_GET['key'])){
			$data['key']      = $_GET['key'];
			$data['incoming'] = $this->cek_aplikasi_model->get_incoming_sc($data['key'],$sales);
			$data['decision'] = $this->cek_aplikasi_model->get_decision_sc($data['key'],$sales);
		}
		else{
			$data['key']      = '';
			$data['incoming'] = '';
			$data['decision'] = '';
		}
		
		
		//load view
		$this->template->set('title','Cek Aplikasi Smartcash');
		$this->template->load('template','cek_aplikasi/sc', $data);
	}
	
	function pl()
	{
		$position = $this->session->userdata('position');
		$leader_position = array('SPV','ASM','RSM','BSH');
		if(in_array($position,$leader_position)){
			$sales = '';
		}
		else{
			$sales = $this->session->userdata('sl_code');
		}
		
		if(isset($_GET['key'])){
			$data['key']      = $_GET['key'];
			$data['incoming'] = $this->cek_aplikasi_model->get_incoming_pl($data['key'],$sales);
			$data['decision'] = $this->cek_aplikasi_model->get_decision_pl($data['key'],$sales);
		}
		else{
			$data['key']      = '';
			$data['incoming'] = '';
			$data['decision'] = '';
		}
		
		
		//load view
		$this->template->set('title','Cek Aplikasi PL');
		$this->template->load('template','cek_aplikasi/pl', $data);
	}
}