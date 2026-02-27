<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Rsm extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('rsm_model');
	}
	
	function index()
	{
		$sales_code = $this->session->userdata('sl_code');
		$product = $this->session->userdata('product');
		$level = $this->session->userdata('level');
		$position = $this->session->userdata('position');
		$tanggal = date('Y-m');
		$tgl_1 = date('Y-m', strtotime('-1 months'));
		
		//target
		
		$sql_targets = $this->rsm_model->getDataTargetRsm($level, $position);
		if($sql_targets->num_rows > 0)
		{
			$dtarget = $sql_targets->row();
			$data['target_asmnya'] = $dtarget->target_asm;
			$data['target_spvnya'] = $dtarget->target_spv;
			$data['target_dsrnya'] = $dtarget->target_dsr;
			$data['target_aplikasinya'] = $dtarget->target;
			$data['target_noanya'] = $dtarget->target_noa;
		}
		else
		{
			$data['target_asmnya'] = 0;
			$data['target_spvnya'] = 0;
			$data['target_dsrnya'] = 0;
			$data['target_aplikasinya'] = 0;
			$data['target_noanya'] = 0;
		}
		
		
		//jml_dsr
		$jmlDsr = $this->rsm_model->getCountDsr($sales_code);
		$dt = $jmlDsr->row();
		$data['jml_dsr'] = $dt->dsr;
		$data['jml_spv'] = $dt->spv;
		$data['jml_asm'] = $dt->asm;

		//target apps
		// $sqlTarget = $this->rsm_model->getAllTarget($level, $position, $product);
		// if($sqlTarget->num_rows() > 0)
		// {
			// $dtTarget = $sqlTarget->row();
			// $data['target_apps'] = $dtTarget->target;
		// }
		// else
		// {
			// $data['target_apps'] = 0;
		// }

		//apps
		$sqlAppsAkt = $this->rsm_model->getAplikasiAktualNew($sales_code, $tanggal);
		if($sqlAppsAkt->num_rows() > 0)
		{
			$dtAplikasi = $sqlAppsAkt->row();
			$data['aplikasi_akt'] = $dtAplikasi->Inc_cc + $dtAplikasi->Inc_edc + $dtAplikasi->Inc_sc;
			$data['aplikasi_rts'] = $dtAplikasi->Rts_cc + $dtAplikasi->Rts_edc + $dtAplikasi->Rts_sc;
		}
		else
		{
			$data['aplikasi_akt'] = 0;
			$data['aplikasi_rts'] = 0;
		}

		//rts
		/*$sqlRts = $this->rsm_model->getAplikasiRts($sales_code, $tanggal);
		if($sqlRts->num_rows() > 0)
		{
			$dtRts = $sqlRts->row();
			$data['aplikasi_rts'] = $dtRts->cc + $dtRts->edc + $dtRts->sc + $dtRts->pl + $dtRts->corp;
		}
		else
		{
			$data['aplikasi_rts'] = 0;
		}*/

		//Under Perform
		$sqlUnder = $this->rsm_model->getAllUnderPerfNew($sales_code, $tanggal);
		if($sqlUnder->num_rows() > 0)
		{
			$data['under_perf'] = $sqlUnder->num_rows();
		}
		else
		{
			$data['under_perf'] = 0;
		}

		
		$sqlApproveAkt = $this->rsm_model->getApproveAkt($sales_code, $tgl_1);
		if($sqlApproveAkt->num_rows() > 0)
		{
			$rwAppAkt = $sqlApproveAkt->row();
			$data['appAkt'] = $rwAppAkt->app_cc + $rwAppAkt->app_edc + $rwAppAkt->app_sc + $rwAppAkt->app_corp;
		}
		else
		{
			$data['appAkt'] = 0;
		}
		
		$sqlAppRejAkt = $this->rsm_model->getAppRejectAkt($sales_code, $tgl_1);
		if($sqlAppRejAkt->num_rows() > 0)
		{
			$rwApsRejAkt = $sqlAppRejAkt->row();
			$data['dtRejAkt'] = $rwApsRejAkt->rej_cc + $rwApsRejAkt->rej_edc + $rwApsRejAkt->rej_sc +  $rwApsRejAkt->rej_corp;
		}
		else
		{
			$data['dtRejAkt'] = 0;
		}
		
		$sqlApproveNas = $this->rsm_model->getApproveNas($sales_code, $tgl_1);
		if($sqlApproveNas->num_rows() > 0)
		{
			$rwAppNas = $sqlApproveNas->row();
			$data['dtAppNas'] = $rwAppNas->app_cc + $rwAppNas->app_edc + $rwAppNas->app_sc + $rwAppNas->app_corp;
		}
		else
		{
			$data['dtAppNas'] = 0;
		}
		
		$sqlRejNas = $this->rsm_model->getRejNas($sales_code, $tgl_1);
		if($sqlRejNas->num_rows() > 0)
		{
			$rwRejNas = $sqlRejNas->row();
			$data['dtRejNas'] = $rwRejNas->rej_cc_nas + $rwRejNas->rej_edc_nas + $rwRejNas->rej_sc_nas + $rwRejNas->rej_corp_nas;
		}
		else
		{
			$data['dtRejNas'] = 0;
		}
		
		$tglnyah = date('Y-m', strtotime('-1 days'));
		$sqlNoa = $this->rsm_model->getCountNoa($sales_code, $tglnyah);
		if($sqlNoa->num_rows() > 0)
		{
			$noa = $sqlNoa->row();
			$data['total_noa'] = $noa->Total_Noa;
		}
		else
		{
			$data['total_noa'] = 0;
		}
		
		//load view
		$this->template->set('title','RSM');
		$this->template->load('template','rsm/index', $data);
	}

	function jml_asm_aktual($sales_code)
	{
		$data['query'] = $this->rsm_model->getAllAsmAktual($sales_code);
		//load view
		$this->template->set('title','RSM');
		$this->load->view('rsm/jml_asm_aktual', $data);
	}
	
	function jml_asm_aktual_new($sales_code)
	{
		$data['query'] = $this->rsm_model->getAllAsmAktual($sales_code);
		//load view
		$this->template->set('title','RSM');
		$this->load->view('rsm/jml_asm_aktual_new', $data);
	}

	function jml_rts_aktual($sales_code)
	{
		$data['query'] = $this->rsm_model->getAllSpvAktual($sales_code);

		//load view
		$this->template->set('title','RSM');
		$this->load->view('rsm/jml_rts_aktual', $data);
	}
	
	
	function jml_rts_aktual_new($sales_code)
	{
		$data['query'] = $this->rsm_model->getAllSpvAktual($sales_code);

		//load view
		$this->template->set('title','RSM');
		$this->load->view('rsm/jml_rts_aktual_new', $data);
	}
	
	function jml_under_aktual($sales_code)
	{
		$tanggal = date('Y-m', strtotime('-1 days'));   
		$data['query'] = $this->rsm_model->getAllUnderPerf($sales_code, $tanggal);

		//load view
		$this->template->set('title','RSM');
		$this->load->view('rsm/jml_under_aktual', $data);
	}

	function jml_spv_aktual($sales_code)
	{
		$data['query'] = $this->rsm_model->getAllSpvAktuals($sales_code);

		//load view
		$this->template->set('title','RSM');
		$this->load->view('rsm/jml_spv_aktual', $data);
	}
	
	function jml_spv_aktual_new($sales_code)
	{
		$data['query'] = $this->rsm_model->getAllSpvAktuals($sales_code);

		//load view
		$this->template->set('title','RSM');
		$this->load->view('rsm/jml_spv_aktual_new', $data);
	}
	
	function jml_dsr_aktual($sales_code)
	{
		$data['query'] = $this->rsm_model->getDsrData($sales_code);
		//load view
		$this->template->set('title','RSM');
		$this->load->view('rsm/jml_dsr_aktual', $data);
	}

	function add_keterangan($sales_code, $kategori)
	{
		//load view
		$this->template->set('title','FORM ADD');
		$this->load->view('rsm/add_keterangan');
	}

	function simpan_keterangan($sales_code, $kategori)
	{
		$nama = $this->session->userdata('realname');
		$sql_atasan = $this->rsm_model->getDataAtasan($sales_code);
		$result = $sql_atasan->row();
		$kode_atasan = $result->BSH_Code;
		$nama_atasan = $result->BSH_Name;
		
		$date = date('Y-m-d');
		$cekdata = $this->rsm_model->getCek($sales_code, $kategori, $date);
		
		$data_insert = array(
			'keterangan'	=>$this->input->post('note'),
			'tanggal'		=>$this->input->post('tanggal'),
			'kategori'		=>$kategori,
			'kode_pembuat'	=>$sales_code,
			'nama_pembuat'	=>$nama,
			'kode_penerima'	=>$kode_atasan,
			'nama_penerima'	=>$nama_atasan,
			'created_date'	=>$date
		);
		
		if($cekdata->num_rows() > 0)
		{
			$this->rsm_model->update_keterangan($data_insert, $sales_code, $kategori);
		}
		else
		{
			
			$this->rsm_model->insert_keterangan($data_insert);
		}
		
		echo "<script>";
		echo "window.parent.parent.location='".base_url()."rsm'";
		echo "</script>";
	}

	function komitmen_view($sales_code, $kategori)
	{
		$data['query'] = $this->rsm_model->getKomitmen($sales_code, $kategori);
		
		//load view
		$this->template->set('title','FORM LIHAT DATA');
		$this->load->view('spv/komitmen_view', $data);
	}
	
	function komitmen_others($sales_code, $kategori)
	{
		$data['query'] = $this->rsm_model->getKomitmenOthers($sales_code, $kategori);
		
		//load view
		$this->template->set('title','FORM LIHAT DATA');
		$this->load->view('asm/komitmen_other', $data);
	}
	
	function destail_rts($asm_code)
	{
		
		//load view
		$this->template->set('title','View Detail RTS');
		$this->load->view('rsm/detail_rts');
	}
	
	function jml_under_aktual_new($sales_code)
	{
		$tanggal = date('Y-m');
		$data['query'] = $this->rsm_model->getAllUnderPerfNew($sales_code, $tanggal);
		
		//load view
		$this->template->set('title','RSM');
		$this->load->view('rsm/jml_under_aktual_new', $data);
	}
	
	
	
	
}