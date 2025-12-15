<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Search extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('search_model');
	}
	
	function index()
	{
		$sales_code = $this->session->userdata('sl_code');
		$posisi = $this->session->userdata('position');
		$kategori = $this->input->post('kategori');
		$cari = $this->input->post('tcari');
		$field = "";
		if($posisi == "BSH"){
			$field = "BSH_Code";
		}
		elseif($posisi == "RSM"){
			$field = "RSM_Code";
		}
		elseif($posisi == "ASM"){
			$field = "ASM_Code";
		}
		elseif($posisi == "SPV"){
			$field = "SPV_Code";
		}
		else{
			$field = "DSR_Code";
		}
		
		$data['query_inc'] = $this->search_model->data_inc($sales_code, $field, $kategori, $cari);
		$data['query_app'] = $this->search_model->data_app($sales_code, $field, $kategori, $cari);
		
		if($kategori <> "")
		{
			//load view
			$this->template->set('title','Data Perncarian');
			$this->template->load('template','search/pencarian', $data);
		}
		else
		{
			//load view
			$this->template->set('title','Data Perncarian');
			$this->template->load('template','search/index');
		}	
	}
}