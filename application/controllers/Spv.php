<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Spv extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('spv_model');
	}
	
	function index()
	{
		$sales_code = $this->session->userdata('sl_code');
		$level = $this->session->userdata('level');
		$product = $this->session->userdata('product');
		$position = $this->session->userdata('position');
		
		$data['sql_countdsr'] = $this->spv_model->getAllDsr($sales_code);
		$sql_gettarget = $this->spv_model->getTarget($level, $position);
		if($sql_gettarget->num_rows() > 0)
		{
			$dtTarget = $sql_gettarget->row();
			$data['targets'] = $dtTarget->target;
			$data['targets_noa'] = $dtTarget->target_noa;
			$data['targets_dsr'] = $dtTarget->target_dsr;
		}
		else
		{
			$data['targets'] = 0;
			$data['targets_noa'] = 0;
			$data['targets_dsr'] = 0;
		}
		
		//apps aktual
		$tanggal = date('Y-m');
		$app_aktkual = $this->spv_model->getAplikasiAktualNew($sales_code, $tanggal);
		if($app_aktkual->num_rows() > 0)
		{
			$apps_act = $app_aktkual->row();
			$data['apps_aktual'] = $apps_act->Inc_cc + $apps_act->Inc_edc + $apps_act->Inc_sc;
			$data['apl_rts'] = $apps_act->Rts_cc + $apps_act->Rts_edc + $apps_act->Rts_sc;
		}
		else
		{
			$data['apps_aktual'] = 0;
			$data['apl_rts'] = 0;
		}
		
		//apprate spv
		$tgl_1 = date('Y-m', strtotime('-1 months'));
		$sql_apprate = $this->spv_model->getAproval($sales_code, $product, $tgl_1);
		if($sql_apprate->num_rows() > 0)
		{
			$aps_rate = $sql_apprate->row();
			$appSpv = $aps_rate->aps_rates;
		}
		else
		{
			$appSpv = 0;
		}
		
		//reject spv
		$sql_rej = $this->spv_model->getRejSpv($sales_code, $product, $tgl_1);
		if($sql_rej->num_rows() > 0)
		{
			$spv_rej = $sql_rej->row();
			$aps_rej = $spv_rej->rijeks;
		}
		else
		{
			$aps_rej = 0;
		}
		
		if($appSpv != 0 AND $aps_rej != 0)
		{
			$aktualPersen = ($appSpv / ($appSpv + $aps_rej)) * 100;
		}
		else
		{
			$aktualPersen = 0;
		}
		
		$data['aktual_persen'] = $aktualPersen;
		
		//appratenasional
		$sql_app_nas = $this->spv_model->getApprovalNas($product, $tgl_1);
		if($sql_app_nas->num_rows() > 0)
		{
			$apn = $sql_app_nas->row();
			$apNas = $apn->aps_nas;
		}
		else
		{
			$apNas = 0;
		}
		//
		
		//rejectnasional
		$sql_rej_nas = $this->spv_model->getRejlNas($product, $tgl_1);
		if($sql_rej_nas->num_rows() > 0)
		{
			$dtRej = $sql_rej_nas->row();
			$rijek = $dtRej->rijek;
		}
		else
		{
			$rijek = 0;
		}
		
		//persen
		if($apNas !=0 AND $rijek != 0)
		{
			$targetPersen = ($apNas / ($apNas + $rijek)) * 100;
		}
		else
		{
			$targetPersen = 0;
		}
		
		$data['target_persen'] = $targetPersen;
		//
		
		//under perform
		//$sqlUnderPerform = $this->spv_model->getDsrUnderPerforms($sales_code, $tanggal, $product)
		$sqlUnder = $this->spv_model->getDsrUnderPerforms($sales_code, $tanggal, $product);
		if($sqlUnder->num_rows() > 0 )
		{
			$data['sales_underperf'] = $sqlUnder->num_rows();
		}
		else
		{
			$data['sales_underperf'] = 0;
		}
		
		$newtgl = date('Y-m-d');
		$data['sqlCek'] = $this->spv_model->getDataKomitmen($sales_code, $newtgl);
		
		//account
		
		$sqlNoa = $this->spv_model->getCountNoa($sales_code, $tanggal);
		if($sqlNoa->num_rows() > 0)
		{
			$noa = $sqlNoa->row();
			$data['total_noa'] = $noa->Total_Noa;
		}
		else
		{
			$data['total_noa'] = 0;
		}
		
		//end account
		
		//load view
		$this->template->set('title','Supervisor');
		$this->template->load('template','spv/index', $data);
	}
	
	function jml_dsr_aktual($sales_code)
	{
		$tanggal = date('Y-m');
		$product = $this->session->userdata('product');
		$data['query'] = $this->spv_model->getDsrAktual($sales_code);

		//load view
		$this->template->set('title','Supervisor');
		$this->load->view('spv/jml_dsr_aktual', $data);
	}
	
	function jml_apps_aktual($sales_code)
	{
		$product = $this->session->userdata('product');
		$tanggal = date('Y-m');
		$data['query'] = $this->spv_model->getDataAppsAktual($sales_code, $product, $tanggal);
		
		//load view
		$this->template->set('title','Supervisor');
		$this->load->view('spv/jml_apps_aktual', $data);
	}
	
	function jml_rts_aktual($sales_code)
	{
		$product = $this->session->userdata('product');
		$tanggal = date('Y-m', strtotime('-1 days'));
		//$data['query'] = $this->spv_model->getDataAppsRtsAkt($sales_code, $product, $tanggal);
		$data['query'] = $this->spv_model->getDataAppsRtsAktNew($sales_code, $product, $tanggal);
		
		//load view
		$this->template->set('title','Supervisor');
		$this->load->view('spv/jml_rts_aktual_new', $data);
	}
	
	function detail_rts($dsr_code, $product)
	{
		$tgl = date('Y-m', strtotime('-1 days'));
		if($product == "CC")
		{
			$data['sql'] = $this->spv_model->getNamaApplicantCc($dsr_code, $tgl);
		}
		elseif($product == "EDC")
		{
			$data['sql'] = $this->spv_model->getNamaApplicantEdc($dsr_code, $tgl);
		}elseif($product == "SC")
		{
			$data['sql'] = $this->spv_model->getNamaApplicantSc($dsr_code, $tgl);
		}

		//load view
		$this->template->set('title','Supervisor');
		$this->load->view('spv/detail_rts', $data);
	}

	function add_keterangan($sales_code, $kategori)
	{
		//load view
		$this->template->set('title','FORM ADD');
		$this->load->view('spv/add_keterangan');
	}
	
	function simpan_keterangan($sales_code, $kategori)
	{
		$nama = $this->session->userdata('realname');
		$sql_atasan = $this->spv_model->getDataAtasan($sales_code);
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
		$cekdata = $this->spv_model->getCek($sales_code, $kategori, $date);
		
		$data_insert = array(
			'keterangan'	=>$this->input->post('note'),
			'tanggal'		=>$this->input->post('tanggal'),
			'kategori'		=>$kategori,
			'kode_pembuat'	=>$sales_code,
			'nama_pembuat'	=>$nama,
			'kode_penerima'	=>$kode_atasan,
			'nama_penerima'	=>$nama_atasan,
			'created_date'	=>$date,
			'status'		=>'On Progress'
		);
		
		if($cekdata->num_rows() > 0)
		{
			$this->spv_model->update_keterangan($data_insert, $sales_code, $kategori);
		}
		else
		{
			
			$this->spv_model->insert_keterangan($data_insert);
		}
		
		echo "<script>";
		echo "window.parent.parent.location='".base_url()."spv'";
		echo "</script>";
	}
	
	function jml_under_aktual($sales_code)
	{
		$date = date('Y-m', strtotime('-1 month'));
		$data['query'] = $this->spv_model->getDsrUnderPerform($sales_code, $date);
		
		//load view
		$this->template->set('title','Supervisor');
		$this->load->view('spv/jml_under_aktual', $data);
	}
	
	function komitmen($sales_code, $kategori)
	{
		$data['query'] = $this->spv_model->getKomitmen($sales_code, $kategori);
		//load view
		$this->template->set('title','FORM LIHAT DATA');
		$this->load->view('spv/komitmen_view', $data);
	}

	function jml_under_perform($sales_code)
	{
		$product = $this->session->userdata('product');
		$date = date('Y-m');
		$data['query'] = $this->spv_model->getDsrUnderPerforms($sales_code, $date, $product);
		//load view
		$this->template->set('title','Supervisor');
		$this->load->view('spv/jml_under_aktual', $data);
	}

	function breakdown($dsr_code)
	{
		$tanggal = date('Y-m');
		$data['query'] = $this->spv_model->getAllIncomingCurrent($dsr_code, $tanggal);
		$this->load->view('spv/breakdown', $data);
	}
	
	function print_data($dsr_code, $product)
	{
		$tgl = date('Y-m', strtotime('-1 days'));
		if($product == "CC")
		{
			$data['sql'] = $this->spv_model->getNamaApplicantCc($dsr_code, $tgl);
		}
		elseif($product == "EDC")
		{
			$data['sql'] = $this->spv_model->getNamaApplicantEdc($dsr_code, $tgl);
		}elseif($product == "SC")
		{
			$data['sql'] = $this->spv_model->getNamaApplicantSc($dsr_code, $tgl);
		}
		
		//load view
		$this->template->set('title','Supervisor');
		$this->load->view('spv/print_data', $data);
	}
	
	function ubah_status($ket, $id)
	{
		$data['kets'] = str_replace('_', ' ', $ket);
		$data['sql'] = $this->spv_model->getKomitmenById($id);
		
		//load view
		$this->template->set('title','FORM UBAH DATA');
		$this->load->view('spv/ubah_status', $data);
	}
	
	
}