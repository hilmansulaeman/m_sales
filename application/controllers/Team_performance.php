<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Team_performance extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('team_performance_model');
	}

	function pos_bsh()
	{
		$this->template->set('title','Team Performance');
		$this->template->load('template','team_performance/index_bsh');
	}
	
	function lihat_performance($sales_code, $posisi)
	{
		$sales_code = $this->uri->segment(3);
		$posisi = $this->uri->segment(4);
		$filpos = "";
		if($posisi == "RSM")
		{
			$filpos = "RSM_Code";
		}elseif($posisi == "ASM")
		{
			$filpos = "ASM_Code";
		}elseif($posisi == "SPV")
		{
			$filpos = "SPV_Code";
		}elseif($posisi == "DSR" or $posisi == "SPG" or $posisi == "SPB")
		{
			$filpos = "DSR_Code";
		}
		
		//hari kerja & total hari berjalan
		$data['hb'] = $this->hitunghari(date('01-m-Y'), date('d-m-Y'), "-");
		$data['hk'] = $this->hitunghari(date('01-m-Y'), date('t-m-Y'), "-");
		
		$data['sql_sales'] = $this->team_performance_model->profileSales($sales_code);
		
		$data['setoran_cc_n'] = $this->team_performance_model->getDataCc($sales_code, $filpos, date('Y-m'));
		$data['setoran_edc_n'] = $this->team_performance_model->getDataEdc($sales_code, $filpos, date('Y-m'));
		$data['setoran_sc_n'] = $this->team_performance_model->getDataSc($sales_code, $filpos, date('Y-m'));
		$data['setoran_pl_n'] = $this->team_performance_model->getDataPl($sales_code, $filpos, date('Y-m'));
		$data['setoran_corp_n'] = $this->team_performance_model->getDataCorp($sales_code, $filpos, date('Y-m'));
		
		$data['approve_cc_n'] = $this->team_performance_model->getAppCc($sales_code, $filpos, date('Y-m'));
		$data['approve_edc_n'] = $this->team_performance_model->getAppEdc($sales_code, $filpos, date('Y-m'));
		$data['approve_sc_n'] = $this->team_performance_model->getAppSc($sales_code, $filpos, date('Y-m'));
		$data['approve_pl_n'] = $this->team_performance_model->getAppPl($sales_code, $filpos, date('Y-m'));
		$data['approve_corp_n'] = $this->team_performance_model->getAppCorp($sales_code, $filpos, date('Y-m'));
		
		$data['setoran_cc_ls'] = $this->team_performance_model->getDataCc($sales_code, $filpos, date('Y-m', strtotime('-1 month')));
		$data['setoran_edc_ls'] = $this->team_performance_model->getDataEdc($sales_code, $filpos, date('Y-m', strtotime('-1 month')));
		$data['setoran_sc_ls'] = $this->team_performance_model->getDataSc($sales_code, $filpos, date('Y-m', strtotime('-1 month')));
		$data['setoran_pl_ls'] = $this->team_performance_model->getDataPl($sales_code, $filpos, date('Y-m', strtotime('-1 month')));
		$data['setoran_corp_ls'] = $this->team_performance_model->getDataCorp($sales_code, $filpos, date('Y-m', strtotime('-1 month')));
		
		$data['approve_cc_ls'] = $this->team_performance_model->getAppCc($sales_code, $filpos, date('Y-m', strtotime('-1 month')));
		$data['approve_edc_ls'] = $this->team_performance_model->getAppEdc($sales_code, $filpos, date('Y-m', strtotime('-1 month')));
		$data['approve_sc_ls'] = $this->team_performance_model->getAppSc($sales_code, $filpos, date('Y-m', strtotime('-1 month')));
		$data['approve_pl_ls'] = $this->team_performance_model->getAppPl($sales_code, $filpos, date('Y-m', strtotime('-1 month')));
		$data['approve_corp_ls'] = $this->team_performance_model->getAppCorp($sales_code, $filpos, date('Y-m', strtotime('-1 month')));
		
		$data['setoran_cc_lss'] = $this->team_performance_model->getDataCc($sales_code, $filpos, date('Y-m', strtotime('-2 month')));
		$data['setoran_edc_lss'] = $this->team_performance_model->getDataEdc($sales_code, $filpos, date('Y-m', strtotime('-2 month')));
		$data['setoran_sc_lss'] = $this->team_performance_model->getDataSc($sales_code, $filpos, date('Y-m', strtotime('-2 month')));
		$data['setoran_pl_lss'] = $this->team_performance_model->getDataPl($sales_code, $filpos, date('Y-m', strtotime('-2 month')));
		$data['setoran_corp_lss'] = $this->team_performance_model->getDataCorp($sales_code, $filpos, date('Y-m', strtotime('-2 month')));
		
		$data['approve_cc_lss'] = $this->team_performance_model->getAppCc($sales_code, $filpos, date('Y-m', strtotime('-2 month')));
		$data['approve_edc_lss'] = $this->team_performance_model->getAppEdc($sales_code, $filpos, date('Y-m', strtotime('-2 month')));
		$data['approve_sc_lss'] = $this->team_performance_model->getAppSc($sales_code, $filpos, date('Y-m', strtotime('-2 month')));
		$data['approve_pl_lss'] = $this->team_performance_model->getAppPl($sales_code, $filpos, date('Y-m', strtotime('-2 month')));
		$data['approve_corp_lss'] = $this->team_performance_model->getAppCorp($sales_code, $filpos, date('Y-m', strtotime('-2 month')));
		//load view
		$this->template->set('title','Team Performance');
		$this->template->load('template','team_performance/lihat_performance', $data);
	}

	function pilih_rsm($sales_code)
	{
		$data['query'] = $this->team_performance_model->getRsm($sales_code);
		
		//load view
		$this->load->view('team_performance/pilih_sales_bsh', $data);
	}
	
	function pilih_asm($sales_code)
	{
		$data['query'] = $this->team_performance_model->getAsm($sales_code);
		
		//load view
		$this->load->view('team_performance/pilih_sales_rsm', $data);
	}
	
	function pilih_spv($sales_code)
	{
		
		$data['query'] = $this->team_performance_model->getSpv($sales_code);
		
		//load view
		$this->load->view('team_performance/pilih_sales_asm', $data);
	}
	
	function pilih_dsr($sales_code)
	{
		$data['query'] = $this->team_performance_model->getDsr($sales_code);
		
		//load view
		$this->load->view('team_performance/pilih_sales_dsr', $data);
	}
	
	//====================================== INTERNAL FUNCTION ====================================//
	//Hitung hari kerja dan total hari berjalan
	private function hitunghari($tglawal,$tglakhir,$delimiter)
	{
		$tgl_awal = $tgl_akhir = $minggu = $sabtu = $koreksi = $libur = 0;
		$liburnasional = array("01-05-2014","15-05-2014","27-05-2014","29-05-2014");
		
	    //memecah tanggal untuk mendapatkan hari, bulan dan tahun
		$pecah_tglawal = explode($delimiter, $tglawal);
		$pecah_tglakhir = explode($delimiter, $tglakhir);
		
	    //mengubah Gregorian date menjadi Julian Day Count
		$tgl_awal = gregoriantojd($pecah_tglawal[1], $pecah_tglawal[0], $pecah_tglawal[2]);
		$tgl_akhir = gregoriantojd($pecah_tglakhir[1], $pecah_tglakhir[0], $pecah_tglakhir[2]);
	 
	    //mengubah ke unix timestamp
		$jmldetik = 24*3600;
		$a = strtotime($tglawal);
		$b = strtotime($tglakhir);
		
	    //menghitung jumlah libur nasional 
		for($i=$a; $i<$b; $i+=$jmldetik){
			foreach ($liburnasional as $key => $tgllibur) {
				if($tgllibur==date("d-m-Y",$i)){
					$libur++;
				}
			}
		}
		
	    //menghitung jumlah hari minggu
		for($i=$a; $i<$b; $i+=$jmldetik){
			if(date("w",$i)=="0"){
				$minggu++;
			}
		}
		
	    //menghitung jumlah hari sabtu
		for($i=$a; $i<$b; $i+=$jmldetik){
			if(date("w",$i)=="6"){
				$sabtu++;
			}
		}
	 
	    //dijalankan jika $tglakhir adalah hari sabtu atau minggu
		if(date("w",$b)=="0" || date("w",$b)=="6"){
			$koreksi = 1;
		}
		
	    //mengitung selisih dengan pengurangan kemudian ditambahkan 1 agar tanggal awal bln juga dihitung
		$total_hari =  $tgl_akhir - $tgl_awal - $libur - $minggu - $sabtu - $koreksi + 1;
		return $total_hari;
	}
}