<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class My_performance extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('my_performance_model');
	}

	function index()
	{
		$sales_code = $this->session->userdata('sl_code');
		
		$posisi = $this->session->userdata('position');
        $var_code="";
		if ($posisi == "BSH" OR $posisi == "ASH") {
            $var_code = "BSH_Code";
        }
		elseif ($posisi == "RSM" ) {
            $var_code = "RSM_Code";
        }
		elseif ($posisi == "ASM" ) {
            $var_code = "ASM_Code";
        }
		elseif ($posisi == "SPV" ) {
            $var_code = "SPV_Code";
        }
		else{
            $var_code = "DSR_Code";
        }
		
		//hari kerja & total hari berjalan
		$data['hb'] = $this->hitunghari(date('01-m-Y'), date('d-m-Y'), "-");
		$data['hk'] = $this->hitunghari(date('01-m-Y'), date('t-m-Y'), "-");
		
		
		//============ QUERY UNTUK BULAN BERJALAN =============//
		// setoran & incoming
		$data['sql_cc'] = $this->my_performance_model->getDataCc($sales_code, $var_code, date('Y-m'));
		$data['sql_edc'] = $this->my_performance_model->getDataEdc($sales_code, $var_code, date('Y-m'));
		$data['sql_sc'] = $this->my_performance_model->getDataSc($sales_code, $var_code, date('Y-m'));
		$data['sql_pl'] = $this->my_performance_model->getDataPl($sales_code, $var_code, date('Y-m'));
		$data['sql_corp'] = $this->my_performance_model->getDataCorp($sales_code, $var_code, date('Y-m'));
		$data['sql_tele'] = $this->my_performance_model->getDataTele($sales_code, $var_code, date('Y-m'));
		// end setoran & incoming
		
		// approve, cancel & decline
		$data['app_cc'] = $this->my_performance_model->getAppCc($sales_code, date('Y-m'));
		$data['app_edc'] = $this->my_performance_model->getAppEdc($sales_code, date('Y-m'));
		$data['app_sc'] = $this->my_performance_model->getAppSc($sales_code, date('Y-m'));
		$data['app_pl'] = $this->my_performance_model->getAppPl($sales_code, date('Y-m'));
		$data['app_corp'] = $this->my_performance_model->getAppCorp($sales_code, date('Y-m'));
		// end approve, cancel & decline
		
		
		//============ QUERY UNTUK BULAN SEBELUMNYA (M-1) =============//
		// last month setoran & incoming
		$data['ls_sql_cc'] = $this->my_performance_model->getDataCc($sales_code, $var_code, date('Y-m', strtotime('-1 month')));
		$data['ls_sql_edc'] = $this->my_performance_model->getDataEdc($sales_code, $var_code, date('Y-m', strtotime('-1 month')));
		$data['ls_sql_sc'] = $this->my_performance_model->getDataSc($sales_code, $var_code, date('Y-m', strtotime('-1 month')));
		$data['ls_sql_pl'] = $this->my_performance_model->getDataPl($sales_code, $var_code, date('Y-m', strtotime('-1 month')));
		$data['ls_sql_corp'] = $this->my_performance_model->getDataCorp($sales_code, $var_code, date('Y-m', strtotime('-1 month')));
		$data['ls_sql_tele'] = $this->my_performance_model->getDataTele($sales_code, $var_code, date('Y-m', strtotime('-1 month')));
		// end setoran & incoming
		
		// last month approve, cancel & decline
		$data['ls_app_cc'] = $this->my_performance_model->getAppCc($sales_code, date('Y-m', strtotime('-1 month')));
		$data['ls_app_edc'] = $this->my_performance_model->getAppEdc($sales_code, date('Y-m', strtotime('-1 month')));
		$data['ls_app_sc'] = $this->my_performance_model->getAppSc($sales_code, date('Y-m', strtotime('-1 month')));
		$data['ls_app_pl'] = $this->my_performance_model->getAppPl($sales_code, date('Y-m', strtotime('-1 month')));
		$data['ls_app_corp'] = $this->my_performance_model->getAppCorp($sales_code, date('Y-m', strtotime('-1 month')));
		// end approve, cancel & decline
		
		
		//============ QUERY UNTUK BULAN SEBELUMNYA (M-2) =============//
		// -two month setoran & incoming
		$data['two_sql_cc'] = $this->my_performance_model->getDataCc($sales_code, $var_code, date('Y-m', strtotime('-2 month')));
		$data['two_sql_edc'] = $this->my_performance_model->getDataEdc($sales_code, $var_code, date('Y-m', strtotime('-2 month')));
		$data['two_sql_sc'] = $this->my_performance_model->getDataSc($sales_code, $var_code, date('Y-m', strtotime('-2 month')));
		$data['two_sql_pl'] = $this->my_performance_model->getDataPl($sales_code, $var_code, date('Y-m', strtotime('-2 month')));
		$data['two_sql_corp'] = $this->my_performance_model->getDataCorp($sales_code, $var_code, date('Y-m', strtotime('-2 month')));
		$data['two_sql_tele'] = $this->my_performance_model->getDataTele($sales_code, $var_code, date('Y-m', strtotime('-2 month')));
		// end setoran & incoming
		
		// -two month approve, cancel & decline
		$data['two_app_cc'] = $this->my_performance_model->getAppCc($sales_code, date('Y-m', strtotime('-2 month')));
		$data['two_app_edc'] = $this->my_performance_model->getAppEdc($sales_code, date('Y-m', strtotime('-2 month')));
		$data['two_app_sc'] = $this->my_performance_model->getAppSc($sales_code, date('Y-m', strtotime('-2 month')));
		$data['two_app_pl'] = $this->my_performance_model->getAppPl($sales_code, date('Y-m', strtotime('-2 month')));
		$data['two_app_corp'] = $this->my_performance_model->getAppCorp($sales_code, date('Y-m', strtotime('-2 month')));
		// end approve, cancel & decline
		
		
		
		//load view
		$this->template->set('title','My Performance');
		$this->template->load('template','my_performance/index', $data);
	}

	function index2()
	{
		$sales_code = $this->session->userdata('sl_code');
		
		$posisi = $this->session->userdata('position');
        $var_code="";
		if($posisi == "DSR" or $posisi == "PSG" or $posisi == "SPB" or $posisi == "Mobile Sales")
        {
            $var_code = "DSR_Code";
        }elseif ($posisi == "SPV" ) {
            $var_code = "SPV_Code";
        }elseif ($posisi == "ASM" ) {
            $var_code = "ASM_Code";
        }elseif ($posisi == "RSM" ) {
            $var_code = "RSM_Code";
        }elseif ($posisi == "BSH" OR $posisi =="ASH" ) {
            $var_code = "BSH_Code";
        }
		
		// setoran & incoming
		$data['sql_cc'] = $this->my_performance_model->getDataCc($sales_code, $var_code, date('Y-m'));
		$data['sql_edc'] = $this->my_performance_model->getDataEdc($sales_code, $var_code, date('Y-m'));
		$data['sql_sc'] = $this->my_performance_model->getDataSc($sales_code, $var_code, date('Y-m'));
		$data['sql_pl'] = $this->my_performance_model->getDataPl($sales_code, $var_code, date('Y-m'));
		$data['sql_corp'] = $this->my_performance_model->getDataCorp($sales_code, $var_code, date('Y-m'));
		// end setoran & incoming
		
		// approve, cancel & decline
		$data['app_cc'] = $this->my_performance_model->getAppCc($sales_code, $var_code, date('Y-m'));
		$data['app_edc'] = $this->my_performance_model->getAppEdc($sales_code, $var_code, date('Y-m'));
		$data['app_sc'] = $this->my_performance_model->getAppSc($sales_code, $var_code, date('Y-m'));
		$data['app_pl'] = $this->my_performance_model->getAppPl($sales_code, $var_code, date('Y-m'));
		$data['app_corp'] = $this->my_performance_model->getAppCorp($sales_code, $var_code, date('Y-m'));
		// end approve, cancel & decline
		
		//load view
		$this->template->set('title','My Performance');
		$this->template->load('template','my_performance/index2', $data);
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