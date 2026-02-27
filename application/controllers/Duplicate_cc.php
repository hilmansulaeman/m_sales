<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
class Duplicate_cc extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->library('upload');
		$this->load->helper(array('url','form','file'));
        //$this->load->helper(array('url', 'html'));
        $this->load->model('duplicate_cc_model');
	}

	function index()
	{
		
		$myid  = $this->session->userdata('sl_code');
		$position  = $this->session->userdata('position');				
		$txt_nama = $this->input->post('txt_nama');
		$dt_tgl_lahir = $this->input->post('dt_tgl_lahir');
		$txt_no_ktp = $this->input->post('txt_no_ktp');
		 
		
		if($txt_nama =="" and $dt_tgl_lahir =="" and $txt_no_ktp =="")
		{  						
			$filter ="and customer_name ='$txt_nama'";
			$txt_nama ="";
			$dt_tgl_lahir ="";
			$txt_no_ktp ="";
			$cek ="0";
		}elseif($txt_nama !="" and $dt_tgl_lahir =="" and $txt_no_ktp =="")
		{
				
			$filter ="and customer_name like '%$txt_nama%'";
			$txt_nama ="";
			$dt_tgl_lahir ="";
			$txt_no_ktp ="";
			$cek ="1";
		}elseif($txt_nama !="" and $dt_tgl_lahir !="" and $txt_no_ktp =="")
		{
			$filter ="and customer_name like '%$txt_nama%' and dob like '%$dt_tgl_lahir%' ";
			$txt_nama ="";
			$dt_tgl_lahir ="";
			$txt_no_ktp ="";
			$cek ="1";
		
		}elseif($txt_nama =="" and $dt_tgl_lahir !="" and $txt_no_ktp =="")
		{
			$filter ="and dob like '%$dt_tgl_lahir%'";
			$txt_nama ="";
			$dt_tgl_lahir ="";
			$txt_no_ktp ="";
			$cek ="1";
		}elseif($txt_nama !="" and $dt_tgl_lahir !="" and $txt_no_ktp !="")
		{
			$filter ="and customer_name like '%$txt_nama%' and dob like '%$dt_tgl_lahir%' and ktp like '%$txt_no_ktp%'  ";
			$txt_nama ="";
			$dt_tgl_lahir ="";
			$txt_no_ktp ="";
			$cek ="1";
		}elseif($txt_no_ktp !="" and $txt_nama =="" and $dt_tgl_lahir =="")
		{			
			$filter =" and ktp like '%$txt_no_ktp%' ";		
			$txt_nama ="";
			$dt_tgl_lahir ="";
			$txt_no_ktp ="";			
			$cek ="1";
		}
		//month	
		 
		$data['query_duplicate'] = $this->duplicate_cc_model->data_duplicate_cc($myid, $filter);
		$data['filter'] = $filter;
		$data['myid'] = $myid;
		$data['cek'] = $cek;
	
		 
		//

		//Load View
		$this->template->set('title','Data Duplicate CC');
		$this->template->load('template','duplicate_cc/index', $data);
	}
	
	function cek_duplicate()
	{
		$submit = $this->input->post('cek');
		$nama = $this->input->post('nama');
		$dob = $this->input->post('dob');
		$ktp = $this->input->post('ktp');
		$data['result'] = "";
		if($submit == "nama")
		{
			$query_cek = $this->duplicate_cc_model->cekDataDuplicate($nama, $dob);
			$dataRow = $query_cek->num_rows();
			if($dataRow > 0)
			{
				$data['result'] = "duplicate bca";
			}else
			{
				$query_cek_apps = $this->duplicate_cc_model->cekDataDuplicateApps($nama, $dob);
				$dataRowApss = $query_cek_apps->num_rows();
				if($dataRowApss > 0)
				{
					$data['result'] = "duplicate dika";
				}else
				{
					$data['result'] = "available";
				}
			}
		}elseif($submit == "ktp")
		{
			$query_cek2 = $this->duplicate_cc_model->getDuplicateWithKtp($ktp);
			$rowKtp = $query_cek2->num_rows();
			if($rowKtp > 0)
			{
				$data['result'] = "duplicate dika";
			}
			else
			{
				$data['result'] = "available";
			}
		}
		//Load View
		$this->template->set('title','Data Duplicate CC');
		$this->template->load('template','duplicate_cc/cek_duplicate', $data);
	}
		 
	 
}