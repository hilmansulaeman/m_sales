<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Bsh extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('bsh_model');
	}

	function index()
	{
		$sales_code = $this->session->userdata('sl_code');
		$level = $this->session->userdata('level');
		$product = $this->session->userdata('product');
		$position = $this->session->userdata('position');

		$data['query'] = $this->bsh_model->jumlah_ds_apps($sales_code, date('Y-m'), $position, $product);
		$under = $this->bsh_model->data_up($sales_code, date('Y-m'));
		$data['under_perf'] = $under->num_rows();

		//load view
		$this->template->set('title','BSH');
		$this->template->load('template','bsh/index', $data);
	}

	function jml_rsm_aktual($sales_code)
	{
		$data['query'] = $this->bsh_model->m_jml_rsm_aktual($sales_code);
		//load view
		$this->template->set('title','BSH');
		$this->load->view('bsh/jml_rsm_aktual', $data);
	}
	
	function jml_rsm_aktual_new($sales_code)
	{
		$data['query'] = $this->bsh_model->m_jml_rsm_aktual($sales_code);
		//load view
		$this->template->set('title','BSH');
		$this->load->view('bsh/jml_rsm_aktual_new', $data);
	}

	function jml_asm_aktual($sales_code)
	{
		$data['query'] = $this->bsh_model->getAllAsmAktual($sales_code);

		//load view
		$this->template->set('title','BSH');
		$this->load->view('bsh/jml_asm_aktual', $data);
	}
	
	function jml_asm_aktual_new($sales_code)
	{
		$data['query'] = $this->bsh_model->getAllAsmAktual($sales_code);

		//load view
		$this->template->set('title','BSH');
		$this->load->view('bsh/jml_asm_aktual_new', $data);
	}

	function jml_spv_aktual($sales_code)
	{
		$data['query'] = $this->bsh_model->getAllSpvAktuals($sales_code);
		//load view
		$this->template->set('title','BSH');
		$this->load->view('bsh/jml_spv_aktual', $data);
	}
	
	function jml_spv_aktual_new($sales_code)
	{
		$data['query'] = $this->bsh_model->getAllSpvAktuals($sales_code);
		//load view
		$this->template->set('title','BSH');
		$this->load->view('bsh/jml_spv_aktual_new', $data);
	}

	function jml_rts_aktual($sales_code)
	{
		$data['query'] = $this->bsh_model->m_jml_rsm_aktual($sales_code);
		//load view
		$this->template->set('title','BSH');
		$this->load->view('bsh/jml_rts_aktual', $data);
	}
	
	function jml_rts_aktual_new($sales_code)
	{
		$data['query'] = $this->bsh_model->m_jml_rsm_aktual($sales_code);
		//load view
		$this->template->set('title','BSH');
		$this->load->view('bsh/jml_rts_aktual_new', $data);
	}
	
	function jml_under_aktual($sales_code)
	{
		$tanggal = date('Y-m', strtotime('-1 month'));
		$data['query'] = $this->bsh_model->getUnderPerform($sales_code, $tanggal);
		//load view
		$this->template->set('title','BSH');
		$this->load->view('bsh/jml_under_aktual', $data);
	}
	
	function jml_under_aktual_new($sales_code)
	{
		$tanggal = date('Y-m');
		$data['query'] = $this->bsh_model->getUnderPerform($sales_code, $tanggal);
		//load view
		$this->template->set('title','BSH');
		$this->load->view('bsh/jml_under_aktual_new', $data);
	}

	function komitmen_others($sales_code, $kategori)
	{
		$data['query'] = $this->bsh_model->getKomitmenOthers($sales_code, $kategori);
		
		//load view
		$this->template->set('title','FORM LIHAT DATA');
		$this->load->view('bsh/komitmen_other', $data);
	}
}