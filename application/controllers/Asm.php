<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Asm extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('asm_model');
	}
	
	function index()
	{
		$sales_code = $this->session->userdata('sl_code');
		$level = $this->session->userdata('level');
		$product = $this->session->userdata('product');
		$position = $this->session->userdata('position');

		//$sqlDsrAktual = $this->asm_model->getDataDsrAktual($sales_code);
		//if($sqlDsrAktual->num_rows() > 0)
		//{
			//$data['dtDsrAkt'] = $sqlDsrAktual->num_rows();
		//}
		//else
		//{
			//$data['dtDsrAkt'] = 0;
		//}
		
		$sqlDsrAktual = $this->asm_model->getDsrAktual($sales_code);
		if($sqlDsrAktual->num_rows() > 0)
		{
			$rDsr = $sqlDsrAktual->row();
			$data['aktDsr'] = $rDsr->jmldsr;
		}
		else
		{
			$data['aktDsr'] = 0;
		}

		$sqlSpvAktual = $this->asm_model->getSpvAktual($sales_code);
		if($sqlSpvAktual->num_rows() > 0)
		{
			$rSpv = $sqlSpvAktual->row();
			$data['aktSpv'] = $rSpv->jmlspv;
		}
		else
		{
			$data['aktSpv'] = 0;
		}
		
		$sqlTarget = $this->asm_model->getTarget($level, $position);
		if($sqlTarget->num_rows() > 0)
		{
			$rTarget = $sqlTarget->row();
			$data['dtTarget'] = $rTarget->target;
			$data['dtTargetSpv'] = $rTarget->target_spv;
			$data['dtTargetDsr'] = $rTarget->target_dsr;
			$data['dtTargetNoa'] = $rTarget->target_noa;
		}
		else
		{
			$data['dtTarget'] = 0;
			$data['dtTargetSpv'] = 0;
			$data['dtTargetDsr'] = 0;
			$data['dtTargetNoa'] = 0;
		}
		
		$date = date('Y-m');
		$sqlApsAkt = $this->asm_model->getAppsAktNew($sales_code, $date);
		if($sqlApsAkt->num_rows() > 0)
		{
			$dtApsAkt = $sqlApsAkt->row();
			$rApsAkt = $dtApsAkt->Inc_cc + $dtApsAkt->Inc_edc + $dtApsAkt->Inc_sc;
			$data['rwApsAkt'] = $rApsAkt;
			$data['dtRts'] = $dtApsAkt->Rts_cc + $dtApsAkt->Rts_edc + $dtApsAkt->Rts_sc;
		}
		else
		{
			$data['rwApsAkt'] = 0;
			$data['dtRts'] = 0 ;
		}
		
		/*$sqlRts = $this->asm_model->getAppRts($sales_code, $date);
		if($sqlRts->num_rows() > 0)
		{
			$rwRts = $sqlRts->row();
			$data['dtRts'] = $rwRts->rts_cc + $rwRts->rts_edc + $rwRts->rts_sc + $rwRts->rts_pl;
		}
		else
		{
			$data['dtRts'] = 0;
		}*/
		
		$sqlUnder = $this->asm_model->getDsrUnder($sales_code);
		if($sqlUnder->num_rows() > 0)
		{
			$data['rwUnder'] = $sqlUnder->num_rows();
		}
		else
		{
			$data['rwUnder'] = 0;
		}
		
		$tgl_1 = date('Y-m', strtotime('-1 months'));
		$sqlApproveAkt = $this->asm_model->getApproveAkt($sales_code, $tgl_1);
		if($sqlApproveAkt->num_rows() > 0)
		{
			$rwAppAkt = $sqlApproveAkt->row();
			$data['appAkt'] = $rwAppAkt->app_cc + $rwAppAkt->app_edc + $rwAppAkt->app_sc + $rwAppAkt->app_corp;
		}
		else
		{
			$data['appAkt'] = 0;
		}
		
		$sqlAppRejAkt = $this->asm_model->getAppRejectAkt($sales_code, $tgl_1);
		if($sqlAppRejAkt->num_rows() > 0)
		{
			$rwApsRejAkt = $sqlAppRejAkt->row();
			$data['dtRejAkt'] = $rwApsRejAkt->rej_cc + $rwApsRejAkt->rej_edc + $rwApsRejAkt->rej_sc +  $rwApsRejAkt->rej_corp;
		}
		else
		{
			$data['dtRejAkt'] = 0;
		}
		
		$sqlApproveNas = $this->asm_model->getApproveNas($sales_code, $tgl_1);
		if($sqlApproveNas->num_rows() > 0)
		{
			$rwAppNas = $sqlApproveNas->row();
			$data['dtAppNas'] = $rwAppNas->app_cc + $rwAppNas->app_edc + $rwAppNas->app_sc + $rwAppNas->app_corp;
		}
		else
		{
			$data['dtAppNas'] = 0;
		}
		
		$sqlRejNas = $this->asm_model->getRejNas($sales_code, $tgl_1);
		if($sqlRejNas->num_rows() > 0)
		{
			$rwRejNas = $sqlRejNas->row();
			$data['dtRejNas'] = $rwRejNas->rej_cc_nas + $rwRejNas->rej_edc_nas + $rwRejNas->rej_sc_nas + $rwRejNas->rej_corp_nas;
		}
		else
		{
			$data['dtRejNas'] = 0;
		}
		
		$data['sqlKomitmen'] = $this->asm_model->getDataKomitmen($sales_code);
		
		$tglnyah = date('Y-m', strtotime('-1 days'));
		$sqlNoa = $this->asm_model->getCountNoa($sales_code, $tglnyah);
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
		$this->template->set('title','ASM');
		$this->template->load('template','asm/index', $data);
	}
	
	function jml_spv_aktual($sales_code)
	{
		$data['query'] = $this->asm_model->getAllSpvAktual($sales_code);
		
		//load view
		$this->template->set('title','ASM');
		$this->load->view('asm/jml_spv_aktual', $data);
	}

	function jml_spv_aktual_new($sales_code)
	{
		$data['query'] = $this->asm_model->getAllSpvAktual($sales_code);
		
		//load view
		$this->template->set('title','ASM');
		$this->load->view('asm/jml_spv_aktual_new', $data);
	}
	
	function jml_apps_aktual($sales_code)
	{
		$tanggal = date('Y-m');
		$data['query'] = $this->asm_model->dataApsAktual($sales_code, $tanggal);
		//load view
		$this->template->set('title','ASM');
		$this->load->view('asm/jml_apps_aktual', $data);
	}
	
	function jml_rts_aktual($sales_code)
	{
		$data['query'] = $this->asm_model->getAllSpvAktual($sales_code);
		
		//load view
		$this->template->set('title','ASM');
		$this->load->view('asm/jml_rts_aktual_new', $data);
	}
	
	function jml_under_aktual($sales_code)
	{
		$data['query'] = $this->asm_model->getDsrUnder($sales_code);
		//load view
		$this->template->set('title','ASM');
		$this->load->view('asm/jml_under_aktual', $data);
	}

	function jml_under_aktual_new($sales_code)
	{
		$data['query'] = $this->asm_model->getDsrUnder($sales_code);
		//load view
		$this->template->set('title','ASM');
		$this->load->view('asm/jml_under_aktual_new', $data);
	}
	
	function add_keterangan($sales_code, $kategori)
	{
		//load view
		$this->template->set('title','FORM ADD');
		$this->load->view('asm/add_keterangan');
	}
	
	function simpan_keterangan($sales_code, $kategori)
	{
		$nama = $this->session->userdata('realname');
		$sql_atasan = $this->asm_model->getDataAtasan($sales_code);
		$result = $sql_atasan->row();
		
		if($result->ASM_Name != "DUMMY ASM")
		{
			$kode_atasan = $result->ASM_Code;
			$nama_atasan = $result->ASM_Name;
		}
		else
		{
			$kode_atasan = $result->RSM_Code;
			$nama_atasan = $result->RSM_Name;
		}
		
		$date = date('Y-m-d');
		$cekdata = $this->asm_model->getCek($sales_code, $kategori, $date);
		
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
			$this->asm_model->update_keterangan($data_insert, $sales_code, $kategori);
		}
		else
		{
			
			$this->asm_model->insert_keterangan($data_insert);
		}
		
		echo "<script>";
		echo "window.parent.parent.location='".base_url()."asm'";
		echo "</script>";
	}
	
	function komitmen_view($sales_code, $kategori)
	{
		$data['query'] = $this->asm_model->getKomitmen($sales_code, $kategori);
		
		//load view
		$this->template->set('title','FORM LIHAT DATA');
		$this->load->view('asm/komitmen_view', $data);
	}
	
	function komitmen_others($sales_code, $kategori)
	{
		$data['query'] = $this->asm_model->getKomitmenOthers($sales_code, $kategori);
		
		//load view
		$this->template->set('title','FORM LIHAT DATA');
		$this->load->view('asm/komitmen_other', $data);
	}

	function jml_dsr_aktual($sales_code)
	{
		$data['query'] = $this->asm_model->allDsrAktual($sales_code);
		//load view
		$this->template->set('title','FORM LIHAT DATA');
		$this->load->view('asm/jml_dsr_aktual', $data);
	}

	function detail_rts($spv_code, $product)
	{
		$tanggal = date('Y-m');
		if($product == "CC")
		{
			$data['sql'] = $this->asm_model->getApplicationCc($spv_code, $tanggal);
		}elseif($product == "EDC")
		{
			$data['sql'] = $this->asm_model->getApplicationEdc($spv_code, $tanggal);
		}elseif($product == "SC")
		{
			$data['sql'] = $this->asm_model->getApplicationSc($spv_code, $tanggal);
		}
		//load view
		$this->template->set('title','VIEW DATA RTS');
		$this->load->view('asm/detail_rts', $data);
	}

	function print_data($spv_code, $product)
	{
		$tanggal = date('Y-m');
		if($product == "CC")
		{
			$data['sql'] = $this->asm_model->getApplicationCc($spv_code, $tanggal);
		}elseif($product == "EDC")
		{
			$data['sql'] = $this->asm_model->getApplicationEdc($spv_code, $tanggal);
		}elseif($product == "SC")
		{
			$data['sql'] = $this->asm_model->getApplicationSc($spv_code, $tanggal);
		}
		//load view
		$this->template->set('title','VIEW DATA RTS');
		$this->load->view('asm/print_data', $data);
	}
	
	
}