<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Merchant extends MY_Controller
{	
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url'));
		$this->load->library(array('auth','template'));
		$this->load->model('incoming/merchant_model');
		error_reporting(0);
	}
	
	function index()
    {
	    $username = $this->session->userdata('sl_code'); // DSR_CODE
	    $position = $this->session->userdata('position');
	    $this->session->set_userdata('date_from', date('Y-m-01'));
		$this->session->set_userdata('date_to', date('Y-m-d'));
		$this->session->set_userdata('source', 'all');
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');
		$source 	= $this->session->userdata('source');
		
		if($position == 'BSH'){
			$data['position'] = "RSM";
			$data['detail'] = "ASM";
			$var = "t2.RSM_Code";
		}
		else if($position == 'RSM'){
			$data['position'] = "ASM";
			$data['detail'] = "SPV";
			$var = "t2.ASM_Code";
		}
		else if($position == 'ASM'){
			$data['position'] = "SPV";
			$data['detail'] = "DSR";
			$var = "t2.SPV_Code";
		}
		else if($position == 'SPV'){
			$data['position'] = "DSR";
			$data['detail'] = "DSR";
			$var = "t1.sales_code";
		}
		else{
			$data['position'] = "DSR";
		}

		if ($position == 'DSR') {
			$views = 'index_dsr';
			// query data input API
			$data['getDataIS'] = $this->merchant_model->getDataInput($username, $date_from, $date_to, 'IS',$source); // IS = Input => input System
			$data['getDataBS'] = $this->merchant_model->getDataInput($username, $date_from, $date_to, 'BS',$source); // BS = Input => bukan System
			// query data processing
			$data['getDataPR'] = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PR',$source); // PR = Processing => Process Received
			$data['getDataPI'] = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PI',$source); // PI = Processing => Process Inprocess
			$data['getDataPRTS'] = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PRTS',$source); // PRTS = Processing => Processs RTS
			$data['getDataPS'] = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PS',$source); // PS = Processing => Process Send
			$data['getDataPC'] = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PC',$source); // PC = Processing => Processs Cancel
			$data['getDataPPS'] = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PPS',$source); // PPS = Processing => Process Pending
			
			// query total
			$data['getTotalsReceived']   = $this->merchant_model->getTotalsProcessing($username, $date_from, $date_to, 'received',$source);
			$data['getTotalsInprocess']  = $this->merchant_model->getTotalsProcessing($username, $date_from, $date_to, 'inprocess',$source);
			$data['getTotalsRTS']  		 = $this->merchant_model->getTotalsProcessing($username, $date_from, $date_to, 'rts',$source);
			$data['getTotalsSend']  	 = $this->merchant_model->getTotalsProcessing($username, $date_from, $date_to, 'send',$source);
			// $data['getDataPTotal'] = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'TOTAL'); // PS = Processing => Process Send
		}else{
			$views = 'index';
			// query data input local
			// $data['getDataIS'] = $this->merchant_model->getDataInputLocal($var, $username, $date_from, $date_to, 'IS'); // IS = Input => input System
			// $data['getDataBS'] = $this->merchant_model->getDataInputLocal($var, $username, $date_from, $date_to, 'BS'); // BS = Input => bukan System
			// // query data processing local
			// $data['getDataPR'] = $this->merchant_model->getDataProcessingLocal($var, $username, $date_from, $date_to, 'PR'); // PR = Processing => Process Received
			// $data['getDataPI'] = $this->merchant_model->getDataProcessingLocal($var, $username, $date_from, $date_to, 'PI'); // PI = Processing => Process Inprocess
			// $data['getDataPRTS'] = $this->merchant_model->getDataProcessingLocal($var, $username, $date_from, $date_to, 'PRTS'); // PRTS = Processing => Processs RTS
			// $data['getDataPS'] = $this->merchant_model->getDataProcessingLocal($var, $username, $date_from, $date_to, 'PS'); // PS = Processing => Process Send
		}

		//load view
		$this->template->set('title','Summary Merchant');
		$this->template->load('template','incoming/merchant/'.$views,$data);
    }
	
	// LEADER PAGE

		function get_data()
		{
			
			$position 	= $this->session->userdata('position');
			$nik 		= $this->session->userdata('sl_code');
			$date_from 	= $this->session->userdata('date_from');
			$date_to 	= $this->session->userdata('date_to');
			$source 	= $this->session->userdata('source');

			$dsr_position = "('DSR','SPG','SPB')";
			$asm_position = "('SPV', 'DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
			$array_leader = array('BSH', 'RSM', 'ASM', 'SPV');
			$array_dsr = array('DSR','SPG','SPB');

			if($position == 'BSH'){
				$where = "SM_Code = '$nik' AND Position IN('RSM', 'ASM', 'SPV')";
			}
			else if($position == 'RSM'){
				$where = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
			}
			else if($position == 'ASM'){
				// $where = "SM_Code = '$nik' AND Position IN $asm_position";
				$where = "SM_Code = '$nik' AND Position = 'SPV'";
			}
			else
			{
				$where = "SM_Code = '$nik' AND Position IN $dsr_position";
			}
			$query = $this->merchant_model->get_datatables($where);
			$data = array();
			$no = $this->input->post('start');
			foreach ($query as $row){
				$sales = $row->DSR_Code;
				
				if (in_array($row->Position, $array_leader)) {
					$buttons = '
					<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>
					<a href="javascript:void(0);" onclick="view_detail(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-info btn-xs"><i class="fa fa-cog"></i> Detail</a>';
					$var = 't2.'.$row->Position.'_Code';
				}
				else if (in_array($row->Position, $array_dsr)) {
					$buttons = '
					<a href="javascript:void(0);" onclick="view_detail(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-info btn-xs"><i class="fa fa-cog"></i> Detail</a>';
					//$var = 't2.'.$row->Position.'_Code';
					$var = 't2.DSR_Code';
				}else{
					$buttons = '';
					$var = 't1.Sales_Code';
				}
				$dataGetDataIS = $this->merchant_model->getDataInputLocal($var, $sales, $date_from, $date_to, 'IS', $source); // IS = Input => input System (total, edc, qris, edc_qris)
				$dataGetDataBS = $this->merchant_model->getDataInputLocal($var, $sales, $date_from, $date_to, 'BS', $source); // BS = Input => bukan System (total, edc, qris, edc_qris)
				
				//Total DSR
				$total_dsr1 = (in_array($row->Position, array('DSR','SPG','SPB'))) ? 0 : $this->merchant_model->getDataInputLocal($var, $sales, $date_from, $date_to, 'IS',$source)->total_dsr;

				// $total_dsr1  = $this->merchant_model->getDataInputLocal($var, $sales, $date_from, $date_to, 'IS', $source);

				$dataGetDataPR 	 = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PR', $source); // PR = Processing => Process Received
				$dataGetDataPI 	 = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PI', $source); // PI = Processing => Process Inprocess
				$dataGetDataPRTS = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PRTS', $source); // PRTS = Processing => Processs RTS
				$dataGetDataPS 	 = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PS', $source); // PS = Processing => Process Send
				$dataGetDataPC 	 = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PC', $source); // PS = Processing => Process Cancel
				$dataGetDataPPS  = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PPS', $source); // PS = Processing => Process Pending

				// $getTotalsReceived  = $this->merchant_model->getTotalsProcessingLocal($var, $sales, $date_from, $date_to, 'received');
				// $getTotalsInprocess = $this->merchant_model->getTotalsProcessingLocal($var, $sales, $date_from, $date_to, 'inprocess');
				// $getTotalsRTS  		= $this->merchant_model->getTotalsProcessingLocal($var, $sales, $date_from, $date_to, 'rts');
				// $getTotalsSend  	= $this->merchant_model->getTotalsProcessingLocal($var, $sales, $date_from, $date_to, 'send');
				
				$data[] = array(
					++$no,
					$row->DSR_Code.', '.$row->Name.' ('.$row->Position.')',
					$row->Branch,
					'<span title="Total DSR Active" class="badge bg-green">'.$actual_dsr = ($total_dsr1 == null) ? 0 : $total_dsr1.'</span>', // total dsr active
					'<span title="Total System Input" class="badge bg-black">'.$actual_totalIS = ($dataGetDataIS->total == null) ? 0 : $dataGetDataIS->total.'</span>', // input
					'<span title="Total System Received" class="badge bg-info">'.$actual_totalPR = ($dataGetDataPR->total == null) ? 0 : $dataGetDataPR->total.'</span>', // received
					'<span title="Total System Inprocess" class="badge bg-yellow">'.$actual_totalPI = ($dataGetDataPI->total == null) ? 0 : $dataGetDataPI->total.'</span>', // inprocess
					'<span title="Total System RTS" class="badge bg-red">'.$actual_totalRTS = ($dataGetDataPRTS->total == null) ? 0 : $dataGetDataPRTS->total.'</span>', // rts
					'<span title="Total System Send" class="badge bg-green">'.$actual_totalPS = ($dataGetDataPS->total == null) ? 0 : $dataGetDataPS->total.'</span>', // send
					'<span title="Total System Pending" class="badge bg-yellow">'.$actual_totalPPS = ($dataGetDataPPS->total == null) ? 0 : $dataGetDataPPS->total.'</span>', // pending
					'<span title="Total System Cancel" class="badge bg-red">'.$actual_totalPC = ($dataGetDataPC->total == null) ? 0 : $dataGetDataPC->total.'</span>', // cancel
					$buttons
				);
			}

			if($position == 'ASM'){
				$get_dummy_spv_IS = $this->merchant_model->getDataInputDummy($nik,$date_from,$date_to,'spv','IS', $source);
				$get_dummy_spv_BS = $this->merchant_model->getDataInputDummy($nik,$date_from,$date_to,'spv','BS', $source);
				
				//Total DSR
				$total_dsr2  = $this->merchant_model->getDataInputDummy($nik, $date_from, $date_to, 'spv', 'IS', $source);

				$get_dummy_spv_PR = $this->merchant_model->getAppProcessingDummy($nik,$date_from,$date_to,'spv','PR', $source);
				$get_dummy_spv_PI = $this->merchant_model->getAppProcessingDummy($nik,$date_from,$date_to,'spv','PI', $source);
				$get_dummy_spv_PRTS = $this->merchant_model->getAppProcessingDummy($nik,$date_from,$date_to,'spv','PRTS', $source);
				$get_dummy_spv_PS = $this->merchant_model->getAppProcessingDummy($nik,$date_from,$date_to,'spv','PS', $source);
				$get_dummy_spv_PC = $this->merchant_model->getAppProcessingDummy($nik,$date_from,$date_to,'spv','PC', $source);
				$get_dummy_spv_PPS = $this->merchant_model->getAppProcessingDummy($nik,$date_from,$date_to,'spv','PPS', $source);

				$getTotalsReceived  = $this->merchant_model->getTotalsProcessingDummy($nik, $date_from, $date_to, 'spv', 'received', $source);
				$getTotalsInprocess = $this->merchant_model->getTotalsProcessingDummy($nik, $date_from, $date_to, 'spv', 'inprocess', $source);
				$getTotalsRTS  		= $this->merchant_model->getTotalsProcessingDummy($nik, $date_from, $date_to, 'spv', 'rts', $source);
				$getTotalsSend  	= $this->merchant_model->getTotalsProcessingDummy($nik, $date_from, $date_to, 'spv', 'send', $source);

				$data[] = array(
					++$no,
					'DUMMY SPV',
					'ALL',
					'<span title="Total DSR Active" class="badge bg-green">'.$actual_dsr_dummy = ($total_dsr2->total_dsr == null) ? 0 : $total_dsr2->total_dsr.'</span>',
					'<span title="Total System Input" class="badge bg-black">'.$actual_totalIS = ($get_dummy_spv_IS->total == null) ? 0 : $get_dummy_spv_IS->total.'</span>',
					'<span title="Total System Received" class="badge bg-info">'.$actual_totalPR = ($get_dummy_spv_PR->total == null) ? 0 : $get_dummy_spv_PR->total.'</span>',
					'<span title="Total System Inprocess" class="badge bg-yellow">'.$actual_totalPI = ($get_dummy_spv_PI->total == null) ? 0 : $get_dummy_spv_PI->total.'</span>',
					'<span title="Total System RTS" class="badge bg-red">'.$actual_totalRTS = ($get_dummy_spv_PRTS->total == null) ? 0 : $get_dummy_spv_PRTS->total.'</span>',
					'<span title="Total System Send" class="badge bg-green">'.$actual_totalPS = ($get_dummy_spv_PS->total == null) ? 0 : $get_dummy_spv_PS->total.'</span>',
					'<span title="Total System Pending" class="badge bg-yellow">'.$actual_totalPPS = ($get_dummy_spv_PPS->total == null) ? 0 : $get_dummy_spv_PPS->total.'</span>',
					'<span title="Total System Cancel" class="badge bg-red">'.$actual_totalPC = ($get_dummy_spv_PC->total == null) ? 0 : $get_dummy_spv_PC->total.'</span>',
					'<a href="javascript:void(0);" onclick="view_spv(\''.$nik.'\', `SPV`, `DUMMY SPV`)" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
				);
			}

			$output = array(
				"draw" => $this->input->post('draw'),
				"recordsTotal" => $this->merchant_model->count_filtered($where),
				"recordsFiltered" => $this->merchant_model->count_filtered($where),
				"data" => $data,
			);
			//output dalam format JSON
			echo json_encode($output);
		}
		
		public function filter_data()
		{
			$date_from = $this->input->post('date_from');
			$date_to = $this->input->post('date_to');
			$range = $this->datediff($date_from,$date_to);
			$data = array();
			$data['error_string'] = array();
			$data['inputerror'] = array();
			$data['status'] = TRUE;

			if($range > 31)
			{
				$data['inputerror'][] = 'date_to';
				$data['error_string'][] = 'Maaf, range tanggal maksimal 31 hari';
				$data['status'] = FALSE;
			}

			if($data['status'] === FALSE)
			{
				echo json_encode($data);
				exit();
			}
			$session_data = array(
				'date_from' => $this->input->post('date_from'),
				'date_to' => $this->input->post('date_to'),
				'source'  => $this->input->post('source')
			);
			$this->session->set_userdata($session_data);
			echo json_encode(array("status" => TRUE));
		}
		
		function detailSPV($sales, $pos)
		{
			$this->session->set_userdata('sm_code', $sales);
			$this->session->set_userdata('sm_position', $pos);

			//load view
			$this->load->view('incoming/merchant/detailSPV');
		}

		function detailActual($sales, $varr)
		{
			
			$position 	= $this->session->userdata('position');
			$nik 		= $this->session->userdata('sl_code');
			$date_from 	= $this->session->userdata('date_from');
			$date_to 	= $this->session->userdata('date_to');
			$var 		= 't2.'.$varr.'_Code';
			$source 	= $this->session->userdata('source');

			$data['dataGetDataIS'] = $this->merchant_model->getDataInputLocal($var, $sales, $date_from, $date_to, 'IS',$source); // IS = Input => input System (total, edc, qris, edc_qris)
			$data['dataGetDataBS'] = $this->merchant_model->getDataInputLocal($var, $sales, $date_from, $date_to, 'BS',$source); // BS = Input => bukan System (total, edc, qris, edc_qris)

			$data['dataGetDataPR'] 	 = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PR',$source); // PR = Processing => Process Received
			$data['dataGetDataPI'] 	 = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PI',$source); // PI = Processing => Process Inprocess
			$data['dataGetDataPRTS'] = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PRTS',$source); // PRTS = Processing => Processs RTS
			$data['dataGetDataPS'] 	 = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PS',$source); // PS = Processing => Process Send
			$data['dataGetDataPC'] 	 = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PC',$source); // PRTS = Processing => Processs Cancel
			$data['dataGetDataPPS']  = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PPS',$source); // PS = Processing => Process Pending

			$data['sales_code'] = $var;
			$data['sales'] = $sales;

			$this->load->view('incoming/merchant/detailActual', $data);
		}

		function detailActualLink($sales, $pos)
		{
			$position 	= $this->session->userdata('position');
			$nik 		= $this->session->userdata('sl_code');
			$date_from 	= $this->session->userdata('date_from');
			$date_to 	= $this->session->userdata('date_to');
			$var = 't2.'.$pos.'_Code';
			$source 	= $this->session->userdata('source');

			$data['dataGetDataIS'] = $this->merchant_model->getDataInputLocal($var, $sales, $date_from, $date_to, 'IS',$source); // IS = Input => input System (total, edc, qris, edc_qris)
			$data['dataGetDataBS'] = $this->merchant_model->getDataInputLocal($var, $sales, $date_from, $date_to, 'BS',$source); // BS = Input => bukan System (total, edc, qris, edc_qris)

			$data['dataGetDataPR'] 	 = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PR',$source); // PR = Processing => Process Received
			$data['dataGetDataPI']   = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PI',$source); // PI = Processing => Process Inprocess
			$data['dataGetDataPRTS'] = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PRTS',$source); // PRTS = Processing => Processs RTS
			$data['dataGetDataPS']   = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PS',$source); // PS = Processing => Process Send
			$data['dataGetDataPC']   = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PC',$source); // PRTS = Processing => Processs Cancel
			$data['dataGetDataPPS']  = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PPS',$source); // PS = Processing => Process Pending

			$data['sales_code'] = $var;
			$data['sales'] = $sales;

			$this->template->set('title','Detail Actual');
			$this->template->load('template','incoming/merchant/detailActualLink', $data);
		}
		
		function get_data_spv()
		{
			$position 	= $this->session->userdata('position');
			$nik 		= $this->session->userdata('sm_code'); //  id data yg di klik
			$pos 		= $this->session->userdata('sm_position'); //  position data yg di klik
			$user 		= $this->session->userdata('sl_code'); // id yang sedang login
			$date_from 	= $this->session->userdata('date_from');
			$date_to 	= $this->session->userdata('date_to');
			$source 	= $this->session->userdata('source');

			$dsr_position = "('DSR','SPG','SPB')";
			$array_leader = array('BSH', 'RSM', 'ASM', 'SPV');

			if($pos == 'RSM'){
				$where = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
			}
			else if($pos == 'ASM'){
				$where = "SM_Code = '$nik' AND Position = 'SPV'";
			}
			else{
				$where = "SM_Code = '$nik' AND Position IN $dsr_position";
			}
			
			$query = $this->merchant_model->get_datatables($where);
			$data = array();
			$no = $this->input->post('start');
			foreach ($query as $row){
				$sales = $row->DSR_Code;
				if (in_array($position, $array_leader)) {
					if (in_array($row->Position, $array_leader)) {
						$buttons = '
						<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>
						<a href="'.site_url('incoming/merchant/detailActualLink/'.$sales.'/'.$row->Position.'/'.$row->Name).'" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-cog"></i> Detail</a>';
						$var = 't2.'.$row->Position.'_Code';
					}else{
						$buttons = '<a href="'.site_url('incoming/merchant/detailActualLink/'.$sales.'/'.$row->Position.'/'.$row->Name).'" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-cog"></i> Detail</a>';
						$var = 't1.Sales_Code';
					}
				}else{
					$buttons = '';
					$var = 't1.Sales_Code';
				}
				// if (in_array($row->Position, $array_leader)) {
				// 	$buttons = '
				// 	<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>
				// 	<a href="javascript:void(0);" onclick="view_detail_link(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-info btn-xs"><i class="fa fa-cog"></i> Detail</a>
				// 	';
					
				// 	$var = 't2.'.$row->Position.'_Code';
				// }else{
				// 	$buttons = '';
				// 	$var = 't1.Sales_Code';
				// }

				$dataGetDataIS = $this->merchant_model->getDataInputLocal($var, $sales, $date_from, $date_to, 'IS',$source); // IS = Input => input System
				$dataGetDataBS = $this->merchant_model->getDataInputLocal($var, $sales, $date_from, $date_to, 'BS',$source); // BS = Input => bukan System
				
				//Total DSR
				$total_dsr1 = (in_array($row->Position, array('DSR','SPG','SPB'))) ? 0 : $this->merchant_model->getDataInputLocal($var, $sales, $date_from, $date_to, 'IS',$source)->total_dsr;

				// $total_dsr1  = $this->merchant_model->getDataInputLocal($var, $sales, $date_from, $date_to, 'IS',$source);

				$dataGetDataPR   = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PR',$source); // PR = Processing => Process Received
				$dataGetDataPI   = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PI',$source); // PI = Processing => Process Inprocess
				$dataGetDataPRTS = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PRTS',$source); // PRTS = Processing => Processs RTS
				$dataGetDataPS   = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PS',$source); // PS = Processing => Process Send
				$dataGetDataPC 	 = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PC',$source); // PS = Processing => Process Cancel
				$dataGetDataPPS  = $this->merchant_model->getAppProcessingLocal($var, $sales, $date_from, $date_to, 'PPS',$source); // PS = Processing => Process Pending

				// $getTotalsReceived  = $this->merchant_model->getTotalsProcessingLocal($var, $sales, $date_from, $date_to, 'received');
				// $getTotalsInprocess = $this->merchant_model->getTotalsProcessingLocal($var, $sales, $date_from, $date_to, 'inprocess');
				// $getTotalsRTS  		= $this->merchant_model->getTotalsProcessingLocal($var, $sales, $date_from, $date_to, 'rts');
				// $getTotalsSend  	= $this->merchant_model->getTotalsProcessingLocal($var, $sales, $date_from, $date_to, 'send');
				
				$data[] = array(
					++$no,
					$row->DSR_Code.', '.$row->Name.' ('.$row->Position.')',
					$row->Branch,
					'<span title="Total DSR Active" class="badge bg-green">'.$actual_dsr = ($total_dsr1 == null) ? 0 : $total_dsr1.'</span>', // total dsr active
					'<span title="Total System" class="badge bg-black">'.$actual_total = ($dataGetDataIS->total == null) ? 0 : $dataGetDataIS->total.'</span>', // Input
					'<span title="Total System Received" class="badge bg-info">'.$actual_totalPR = ($dataGetDataPR->total == null) ? 0 : $dataGetDataPR->total.'</span>', // received
					'<span title="Total System Inprocess" class="badge bg-yellow">'.$actual_totalPI = ($dataGetDataPI->total == null) ? 0 : $dataGetDataPI->total.'</span>', // inprocess
					'<span title="Total System RTS" class="badge bg-red">'.$actual_totalRTS = ($dataGetDataPRTS->total == null) ? 0 : $dataGetDataPRTS->total.'</span>', // rts
					'<span title="Total System Send" class="badge bg-green">'.$actual_totalPS = ($dataGetDataPS->total == null) ? 0 : $dataGetDataPS->total.'</span>', // send
					'<span title="Total System Pending" class="badge bg-yellow">'.$actual_totalPPS = ($dataGetDataPPS->total == null) ? 0 : $dataGetDataPPS->total.'</span>', // pending
					'<span title="Total System Cancel" class="badge bg-red">'.$actual_totalPC = ($dataGetDataPC->total == null) ? 0 : $dataGetDataPC->total.'</span>', // cancel
					$buttons,
				);
			}

			if($pos == 'ASM'){
				$get_dummy_spv_IS = $this->merchant_model->getDataInputDummy($nik,$date_from,$date_to,'spv','IS');
				$get_dummy_spv_BS = $this->merchant_model->getDataInputDummy($nik,$date_from,$date_to,'spv','BS');
				
				//Total DSR
				$total_dsr2  = $this->merchant_model->getDataInputDummy($nik, $date_from, $date_to, 'spv', 'IS');

				$get_dummy_spv_PR   = $this->merchant_model->getAppProcessingDummy($nik,$date_from,$date_to,'spv','PR');
				$get_dummy_spv_PI   = $this->merchant_model->getAppProcessingDummy($nik,$date_from,$date_to,'spv','PI');
				$get_dummy_spv_PRTS = $this->merchant_model->getAppProcessingDummy($nik,$date_from,$date_to,'spv','PRTS');
				$get_dummy_spv_PS   = $this->merchant_model->getAppProcessingDummy($nik,$date_from,$date_to,'spv','PS');
				$get_dummy_spv_PC   = $this->merchant_model->getAppProcessingDummy($nik,$date_from,$date_to,'spv','PC');
				$get_dummy_spv_PPS  = $this->merchant_model->getAppProcessingDummy($nik,$date_from,$date_to,'spv','PPS');

				$getTotalsReceived  = $this->merchant_model->getTotalsProcessingDummy($nik, $date_from, $date_to, 'spv', 'received');
				$getTotalsInprocess = $this->merchant_model->getTotalsProcessingDummy($nik, $date_from, $date_to, 'spv', 'inprocess');
				$getTotalsRTS  		= $this->merchant_model->getTotalsProcessingDummy($nik, $date_from, $date_to, 'spv', 'rts');
				$getTotalsSend  	= $this->merchant_model->getTotalsProcessingDummy($nik, $date_from, $date_to, 'spv', 'send');

				$data[] = array(
					++$no,
					'DUMMY SPV',
					'ALL',
					'<span title="Total DSR Active" class="badge bg-green">'.$actual_dsr_dummy = ($total_dsr2->total_dsr == null) ? 0 : $total_dsr2->total_dsr.'</span>',
					'<span title="Total System Input" class="badge bg-black">'.$actual_totalIS = ($get_dummy_spv_IS->total == null) ? 0 : $get_dummy_spv_IS->total.'</span>',
					'<span title="Total System Received" class="badge bg-info">'.$actual_totalPR = ($get_dummy_spv_PR->total == null) ? 0 : $get_dummy_spv_PR->total.'</span>',
					'<span title="Total System Inprocess" class="badge bg-yellow">'.$actual_totalPI = ($get_dummy_spv_PI->total == null) ? 0 : $get_dummy_spv_PI->total.'</span>',
					'<span title="Total System RTS" class="badge bg-red">'.$actual_totalRTS = ($get_dummy_spv_PRTS->total == null) ? 0 : $get_dummy_spv_PRTS->total.'</span>',
					'<span title="Total System Send" class="badge bg-green">'.$actual_totalPS = ($get_dummy_spv_PS->total == null) ? 0 : $get_dummy_spv_PS->total.'</span>',
					'<span title="Total System Pending" class="badge bg-yellow">'.$actual_totalPPS = ($get_dummy_spv_PPS->total == null) ? 0 : $get_dummy_spv_PPS->total.'</span>',
					'<span title="Total System Cancel" class="badge bg-red">'.$actual_totalPC = ($get_dummy_spv_PC->total == null) ? 0 : $get_dummy_spv_PC->total.'</span>',
					'<a href="javascript:void(0);" onclick="view_spv(\''.$nik.'\', `SPV`, `DUMMY SPV`)" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
				);
			}
			
			$output = array(
				"draw" => $this->input->post('draw'),
				"recordsTotal" => $this->merchant_model->count_filtered($where),
				"recordsFiltered" => $this->merchant_model->count_filtered($where),
				"data" => $data,
			);
			//output dalam format JSON
			echo json_encode($output);
		}

	// END LEADER PAGE

	// DSR PAGE
		function det_breakdown_merchant_leader($sales_code, $sales, $status, $part, $poplink)
		{
			// $Sales_Code = $this->session->userdata('sl_code');
			// $posisi 	= $this->session->userdata('position');
			// $var_code 	= "";
			$tgl1 		= $this->session->userdata('date_from');
			$tgl2 		= $this->session->userdata('date_to');

			$data['part'] 	= $part;
			$data['query'] 	= $this->merchant_model->detBreakdownMerchantLeader($sales_code, $sales, $status, $part, $tgl1, $tgl2);
			//load view
			if ($poplink == "popup") {
				$this->load->view('incoming/merchant/det_breakdown_merchant', $data);
			}else{
				$this->template->set('title','Detail Actual Leader');
				$this->template->load('template','incoming/merchant/detailActualLeader', $data);
			}
		}

		function det_breakdown_merchant_dsr($status, $part)
		{
			$sales 		= $this->session->userdata('sl_code');
			$posisi 	= $this->session->userdata('position');
			$var_code 	= "";
			$tgl1 		= $this->session->userdata('date_from');
			$tgl2 		= $this->session->userdata('date_to');

			$data['part'] 	= $part;
			$data['query'] 	= $this->merchant_model->detBreakdownMerchantDSR($sales, $status, $part, $tgl1, $tgl2);
			//load view	
			$this->load->view('incoming/merchant/det_breakdown_merchant_dsr', $data);
		}

		function filter_incoming()
		{
			$username = $this->session->userdata('sl_code'); // DSR_CODE
	    	$position = $this->session->userdata('position');
			$date_from = $this->input->post('date_from');
			$date_to = $this->input->post('date_to');
			$source = $this->input->post('source');

			if($position == 'BSH'){
				$data['position'] = "RSM";
				$data['detail'] = "ASM";
				$var = "t2.RSM_Code";
			}
			else if($position == 'RSM'){
				$data['position'] = "ASM";
				$data['detail'] = "SPV";
				$var = "t2.ASM_Code";
			}
			else if($position == 'ASM'){
				$data['position'] = "SPV";
				$data['detail'] = "DSR";
				$var = "t2.SPV_Code";
			}
			else if($position == 'SPV'){
				$data['position'] = "DSR";
				$data['detail'] = "DSR";
				$var = "t1.sales_code";
			}
			else{
				$data['position'] = "DSR";
			}
			

			$data['getDataIS']   = $this->merchant_model->getDataInput($username, $date_from, $date_to, 'IS',$source); // IS = Input => input System
			$data['getDataBS']   = $this->merchant_model->getDataInput($username, $date_from, $date_to, 'BS',$source); // BS = Input => bukan System
			// query data processing
			$data['getDataPR']   = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PR', $source); // PR = Processing => Process Received
			$data['getDataPI']   = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PI', $source); // PI = Processing => Process Inprocess
			$data['getDataPRTS'] = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PRTS', $source); // PRTS = Processing => Processs RTS
			$data['getDataPS']   = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PS', $source); // PS = Processing => Process Send
			// query total
			$data['getTotalsReceived']   = $this->merchant_model->getTotalsProcessing($username, $date_from, $date_to, 'received', $source);
			$data['getTotalsInprocess']  = $this->merchant_model->getTotalsProcessing($username, $date_from, $date_to, 'inprocess', $source);
			$data['getTotalsRTS']  		 = $this->merchant_model->getTotalsProcessing($username, $date_from, $date_to, 'rts', $source);
			$data['getTotalsSend']  	 = $this->merchant_model->getTotalsProcessing($username, $date_from, $date_to, 'send', $source);

			// Tanggal filter
			$data['date_from'] = $date_from;
			$data['date_to'] = $date_to;

			$this->template->set('title','Summary Merchant');
			$this->template->load('template','incoming/merchant/index_filter',$data);
		}
	// END DSR PAGE

	// EXPORT PAGE
		function style_col()
		{
			return [
				'font' => ['bold' => true], // Set font nya jadi bold
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
				],
				'borders' => [
					'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
					'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
					'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
					'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
				]
			];
		}

		function style_col2()
		{
			return [
				'font' => [
					'bold' => true,
					'color' => ['rgb' => 'FFFFFF'],
				], // Set font nya jadi bold
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
				],
				'borders' => [
					'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
					'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
					'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
					'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
				],
				'fill' => [
					'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
					'startColor' => [
						'argb' => 'FF3da5ef',
					],
				]

			];
		}

		function style_row()
		{
			return [
				'alignment' => [
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
				],
				'borders' => [
					'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
					'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
					'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
					'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
				]
			];
		}

		function export()
		{
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$style_col = $this->style_col();
			$style_col2 = $this->style_col2();
			$style_row = $this->style_row();
			$sheet->setCellValue('A1', "Sales Code");
			$sheet->setCellValue('B1', "Sales Name");
			$sheet->setCellValue('C1', "SPV Code");
			$sheet->setCellValue('D1', "SPV Name");
			$sheet->setCellValue('E1', "ASM Code");
			$sheet->setCellValue('F1', "ASM Name");
			$sheet->setCellValue('G1', "RSM Code");
			$sheet->setCellValue('H1', "RSM NAme");
			$sheet->setCellValue('I1', "BSH Code");
			$sheet->setCellValue('J1', "BSH Name");
			$sheet->setCellValue('K1', "Branch");
			$sheet->setCellValue('L1', "Merchant Name");
			$sheet->setCellValue('M1', "Jenis Approval");
			$sheet->setCellValue('N1', "Kode Officer");
			$sheet->setCellValue('O1', "Group Fasilitas");
			$sheet->setCellValue('P1', "Product Type");
			$sheet->setCellValue('Q1', "Product Status");

			$sheet->getStyle('A1')->applyFromArray($style_col);
			$sheet->getStyle('B1')->applyFromArray($style_col);
			$sheet->getStyle('C1')->applyFromArray($style_col);
			$sheet->getStyle('D1')->applyFromArray($style_col);
			$sheet->getStyle('E1')->applyFromArray($style_col);
			$sheet->getStyle('F1')->applyFromArray($style_col);
			$sheet->getStyle('G1')->applyFromArray($style_col);
			$sheet->getStyle('H1')->applyFromArray($style_col);
			$sheet->getStyle('I1')->applyFromArray($style_col);
			$sheet->getStyle('J1')->applyFromArray($style_col);
			$sheet->getStyle('K1')->applyFromArray($style_col);
			$sheet->getStyle('L1')->applyFromArray($style_col);
			$sheet->getStyle('M1')->applyFromArray($style_col);
			$sheet->getStyle('N1')->applyFromArray($style_col);
			$sheet->getStyle('O1')->applyFromArray($style_col);
			$sheet->getStyle('P1')->applyFromArray($style_col);
			$sheet->getStyle('Q1')->applyFromArray($style_col);
			//ambil data
			$date_from = $this->session->userdata('date_from');
			$date_to 	 = $this->session->userdata('date_to');
			$source 	 = $this->session->userdata('source');

			$query = $this->merchant_model->getBreakdownMerchantexport($date_from, $date_to, $source);

			//validasi jumlah data
			if (count($query) == 0) { ?>
				<script type="text/javascript" language="javascript">
					alert("No data...!!!");
				</script>
				<?php
				echo "<meta http-equiv='refresh' content='0; url=" . site_url() . "incoming/merchant'>";

				return false;
			} else {
				$no = 1; // Untuk penomoran tabel, di awal set dengan 1
				$numrow = 2; // Set baris pertama untuk isi tabel adalah baris ke 4
				foreach ($query as $data) { // Lakukan looping pada variabel sn
					$maskingname = $this->maskingname($data->Merchant_Name);
					$statusInprocess = array('SUBMIT_TO_DIKA', 'RESUBMIT_TO_DIKA', 'PENDING_FU');
					$statusRts = array('RETURN_TO_SALES','RETURN_FROM_BCA');
					$statusSend = array('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA');
					$statusCancel = array('CANCEL', 'REJECT');

					if ($data->Received_Date >= "$date_from 00:00:00" && $data->Received_Date <= "$date_to 23:59:59") 
					{
						$statusProduct = "Received";
					} 
					else if(in_array($data->Status, $statusInprocess) && $data->Received_Date >= "$date_from 00:00:00" && $data->Received_Date <= "$date_to 23:59:59") 
					{
						$statusProduct = "Inprocess";
					}
					else if((in_array($data->Status, $statusRts) || $data->Hit_Code = '107') && $data->Received_Date >= "$date_from 00:00:00" && $data->Received_Date <= "$date_to 23:59:59") 
					{
						$statusProduct = "RTS";
					}
					else if(in_array($data->Status, $statusSend) && $data->Received_Date >= "$date_from 00:00:00" && $data->Received_Date <= "$date_to 23:59:59") 
					{
						$statusProduct = "Send";
					}
					else if(in_array($data->Status, $statusCancel) && $data->Received_Date >= "$date_from 00:00:00" && $data->Received_Date <= "$date_to 23:59:59") 
					{
						$statusProduct = "Cancel";
					}
					else if($data->Status == "PENDING_SUBMIT_TO_BCA" && $data->Received_Date >= "$date_from 00:00:00" && $data->Received_Date <= "$date_to 23:59:59") 
					{
						$statusProduct = "Pending";
					}

					$sheet->setCellValue('A' . $numrow, $data->Sales_Code);
					$sheet->setCellValue('B' . $numrow, $data->Sales_Name);
					$sheet->setCellValue('C' . $numrow, $data->SPV_Code);
					$sheet->setCellValue('D' . $numrow, $data->SPV_Name);
					$sheet->setCellValue('E' . $numrow, $data->ASM_Code);
					$sheet->setCellValue('F' . $numrow, $data->ASM_Name);
					$sheet->setCellValue('G' . $numrow, $data->RSM_Code);
					$sheet->setCellValue('H' . $numrow, $data->RSM_Name);
					$sheet->setCellValue('I' . $numrow, $data->BSH_Code);
					$sheet->setCellValue('J' . $numrow, $data->BSH_Name);
					$sheet->setCellValue('K' . $numrow, $data->Branch);
					$sheet->setCellValue('L' . $numrow, $maskingname);
					$sheet->setCellValue('M' . $numrow, $data->Status);
					$sheet->setCellValue('N' . $numrow, $data->Officer_Code);
					$sheet->setCellValue('O' . $numrow, $data->Facilities);
					$sheet->setCellValue('P' . $numrow, $data->Product_Type);
					$sheet->setCellValue('Q' . $numrow, $statusProduct);

					$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('H' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('I' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('J' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('K' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('L' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('M' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('N' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('O' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('P' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('Q' . $numrow)->applyFromArray($style_row);
					// $no++; // Tambah 1 setiap kali looping
					$numrow++; // Tambah 1 setiap kali looping
				}
				// Set width kolom
				$sheet->getColumnDimension('A')->setWidth(30);
				$sheet->getColumnDimension('B')->setWidth(30);
				$sheet->getColumnDimension('C')->setWidth(30);
				$sheet->getColumnDimension('D')->setWidth(30);
				$sheet->getColumnDimension('E')->setWidth(30);
				$sheet->getColumnDimension('F')->setWidth(30);
				$sheet->getColumnDimension('G')->setWidth(30);
				$sheet->getColumnDimension('H')->setWidth(30);
				$sheet->getColumnDimension('I')->setWidth(30);
				$sheet->getColumnDimension('J')->setWidth(30);
				$sheet->getColumnDimension('K')->setWidth(30);
				$sheet->getColumnDimension('L')->setWidth(30);
				$sheet->getColumnDimension('M')->setWidth(30);
				$sheet->getColumnDimension('N')->setWidth(30);
				$sheet->getColumnDimension('O')->setWidth(30);
				$sheet->getColumnDimension('P')->setWidth(30);
				$sheet->getColumnDimension('Q')->setWidth(30);

				// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
				$sheet->getDefaultRowDimension()->setRowHeight(-1);
				// Set orientasi kertas jadi LANDSCAPE
				$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
				// Set judul file excel nya
				$sheet->setTitle("Laporan");
				// Proses file excel
				ob_end_clean();
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header("Content-Disposition: attachment; filename=Data Incoming Merchant {$product}.xlsx");
				header('Cache-Control: max-age=0');
				$writer = new Xlsx($spreadsheet);
				$writer->save('php://output');
			}
		}

		// function unit_testing_export($type)
		// {
		// 	$date_from = $this->session->userdata('date_from');
		// 	$date_to 	 = $this->session->userdata('date_to');
		// 	$source 	 = $this->session->userdata('source');

		// 	$query = $this->merchant_model->getBreakdownMerchantexport($date_from, $date_to, $source, $type);

		// 	if ($query->num_rows() == 0) {
		// 		$result = false;
		// 	} else {
		// 		$result = true;
		// 	}

		// 	if ($type == 'qris') {
		// 		$product = strtoupper($type);
		// 	} else {
		// 		$product = "EDC dan EDC_QRIS";
		// 	}

		// 	$this->unit->run($result, 'is_true', "get data untuk export data merchant dsr incoming product {$product}");

		// 	echo $this->unit->report();
		// }
	// END EXPORT PAGE
	
	//================================================= INTERNAL FUNCTION =============================================//
	//datedif
	private function datediff($start,$end)
	{
		$date1 = date_create($start);
		$date2 = date_create($end);
		
		$days = date_diff($date1,$date2);
		
		return $days->format('%R%a');
	}

	private function maskingname($name)
		{
			$ex_name = explode(" ", $name);
			$jml_kata = count($ex_name);
			if ($jml_kata > 1) {
				// > 1 kata
				$ex_name = explode(" ", $name);
				$replace_name = '';
				for ($i = 0; $i < count($ex_name); $i++) {
					$jml_char = strlen($ex_name[$i]);
					if ($i == 0) {
						$replace_name .= $ex_name[$i] . " ";
					} elseif ($i == 1) {
						//$replace_name = substr($ex_name[$i], 0, 3);
						if ($jml_char > 6) {
							$left_string = substr($ex_name[$i], 0, 2);
							$jml_string = $jml_char - 2;
							$replace_name .= $left_string . "" . str_repeat("*", $jml_string) . " ";
						} else {
							$jml_string = 6 - 2;
							if ($jml_char > 2) {
								$left_string = substr($ex_name[$i], 0, 2);
								$repeater_mask = str_repeat("*", $jml_string);
								$replace_name .= $left_string . "" . $repeater_mask . " ";
							} else {
								$replace_name .= $ex_name[$i] . " ";
							}
						}
					} elseif ($i >= 2) {
						$repeater_mask = str_repeat("*", $jml_char);
						$replace_name .= $repeater_mask;
					}
				}
				return $replace_name;
			} else {
				// 1 kata
				$jml_char = strlen($name);
				$default_count_mask = 6;
				if ($jml_char > 6) {
					$left_string = substr($name, 0, 3);
					$jml_string = $jml_char - 3;
					$repeater_mask = str_repeat("*", $jml_string);
					$replace_name = $left_string . "" . $repeater_mask;
				} else {
					if ($jml_char > 3) {
						$left_string = substr($name, 0, 3);
						$jml_string = $default_count_mask - 3;
						$repeater_mask = str_repeat("*", $jml_string);
						$replace_name = $left_string . "" . $repeater_mask;
					} else {
						$jml_string = 6 - $jml_char;
						$replace_name = $name . "" . str_repeat("*", $jml_string);
					}
				}
				return $replace_name;
			}
		}

}