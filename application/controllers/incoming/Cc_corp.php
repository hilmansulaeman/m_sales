<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Cc_corp extends MY_Controller
{	
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url'));
		$this->load->library(array('auth','template'));
		$this->load->model('incoming/cc_model_new');
	}
	
	function index()
    {
	    $username = $this->session->userdata('username'); // DSR_CODE
	    $position = $this->session->userdata('position');
	    $this->session->set_userdata('date_from', date('Y-m-01'));
		$this->session->set_userdata('date_to', date('Y-m-d'));
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');
		
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
			$data['query'] = $this->cc_model_new->getDataInput($username, $date_from, $date_to, 'corp'); // corporate
		}else{
			$views = 'index';
		}

		//load view
		$this->template->set('title','Summary Incoming CC Corporate');
		$this->template->load('template','incoming/cc_corp/'.$views,$data);
    }
	
	// LEADER PAGE

	function get_data()
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');
		
		$array_leader = array('BSH', 'RSM', 'ASM', 'SPV');
		$array_dsr = array('DSR','SPG','SPB');

		if($position == 'BSH'){
			$where = "SM_Code = '$nik' AND Position = 'RSM'";
		}
		else if($position == 'RSM'){
			$where = "SM_Code = '$nik' AND Position = 'ASM'";
		}
		else if($position == 'ASM'){
			$where = "SM_Code = '$nik' AND Position = 'SPV'";
		}
		else{
			$where = "SM_Code = '$nik' AND Position = 'RO'";
		}
		$query = $this->cc_model_new->get_datatables($where);
		$data = array();
		$no = $this->input->post('start');
		foreach ($query as $row){
			$sales = $row->DSR_Code;
			if (in_array($row->Position, $array_leader)) {
				$buttons = '
				<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';
				$var = 't3.'.$row->Position.'_Code';
			}
			else{
				$buttons = '';
				$var = 't1.Sales_Code';
			}
			
			$getIncoming = $this->cc_model_new->getDataInputLeader($var, $sales, $date_from, $date_to, 'corp'); // corporate
			$total_dsr = $row->Position === 'RO' ? 0 : ($getIncoming->total_ro == null ? 0 : $getIncoming->total_ro);
			// $total_dsr = $getIncoming->total_ro == null ? 0 : $getIncoming->total_ro;
			$input = $getIncoming->total == null ? 0 : $getIncoming->total;
			$send = $getIncoming->send == null ? 0 : $getIncoming->send;
			$inprocess = $getIncoming->inprocess == null ? 0 : $getIncoming->inprocess;
			$duplicate = $getIncoming->duplicate == null ? 0 : $getIncoming->duplicate;
			$rts =  $getIncoming->rts == null ? 0 : $getIncoming->rts;
			$cancel = $getIncoming->cancel == null ? 0 : $getIncoming->cancel;
			$reject = $getIncoming->reject == null ? 0 : $getIncoming->reject;

			$data[] = array(
				++$no,
				$row->DSR_Code.', '.$row->Name.' ('.$row->Position.')',
				$row->Branch,
				'<span title="Total DSR Active" class="badge bg-black">'. $total_dsr .'</span>', // total dsr active
				'<span title="Total Input" class="badge bg-red">'. $input .'</span>', // total input
				'<a href="javascript:void(0);" onclick="view_detail(\''.$var.'\',\''.$sales.'\',\'SEND\',\''.$row->Name.'\')"><span title="Total Send" class="badge bg-green">'. $send .'</span></a>', // submit
				'<a href="javascript:void(0);" onclick="view_detail(\''.$var.'\',\''.$sales.'\',\'INPROCESS\',\''.$row->Name.'\')"><span title="Total Inprocess" class="badge bg-info">'. $inprocess .'</span></a>', // inprocess
				'<a href="javascript:void(0);" onclick="view_detail(\''.$var.'\',\''.$sales.'\',\'DUPLICATE\',\''.$row->Name.'\')"><span title="Total Duplicate" class="badge bg-black">'. $duplicate .'</span></a>', // duplicate
				'<a href="javascript:void(0);" onclick="view_detail(\''.$var.'\',\''.$sales.'\',\'RTS\',\''.$row->Name.'\')"><span title="Total RTS" class="badge bg-red">'.$rts .'</span></a>', // rts
				'<a href="javascript:void(0);" onclick="view_detail(\''.$var.'\',\''.$sales.'\',\'CANCEL\',\''.$row->Name.'\')"><span title="Total Cancel" class="badge bg-yellow">'. $cancel .'</span></a>', // cancel by CH
				'<a href="javascript:void(0);" onclick="view_detail(\''.$var.'\',\''.$sales.'\',\'REJECT\',\''.$row->Name.'\')"><span title="Total Reject" class="badge bg-red">'. $reject .'</span></a>', // reject by DIKA
				$buttons
			);
		}

		if($position == 'ASM'){
			
			$getIncomingDummy = $this->cc_model_new->getDataInputDummy($nik, $date_from, $date_to, 'spv', 'corp'); // corporate
			$total_dsr_dummy = $getIncomingDummy->total_ro == null ? 0 : $getIncomingDummy->total_ro;
			$input_dummy = $getIncomingDummy->total == null ? 0 : $getIncomingDummy->total;
			$send_dummy = $getIncomingDummy->send == null ? 0 : $getIncomingDummy->send;
			$inprocess_dummy = $getIncomingDummy->inprocess == null ? 0 : $getIncomingDummy->inprocess;
			$duplicate_dummy = $getIncomingDummy->duplicate == null ? 0 : $getIncomingDummy->duplicate;
			$rts_dummy = $getIncomingDummy->rts == null ? 0 : $getIncomingDummy->rts;
			$cancel_dummy = $getIncomingDummy->cancel == null ? 0 : $getIncomingDummy->cancel;
			$reject_dummy = $getIncomingDummy->reject == null ? 0 : $getIncomingDummy->reject;

			$data[] = array(
				++$no,
				'DUMMY SPV',
				'ALL',
				'<span title="Total DSR Active" class="badge bg-black">'. $total_dsr_dummy .'</span>', // total dsr active
				'<span title="Total Input" class="badge bg-red">'. $input_dummy .'</span>', // input
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Send" class="badge bg-green">'. $send_dummy .'</span></a>', // submit
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Inprocess" class="badge bg-info">'. $inprocess_dummy .'</span></a>', // inprocess
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Duplicate" class="badge bg-black">'. $duplicate_dummy .'</span></a>', // duplicate
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total RTS" class="badge bg-red">'. $rts_dummy .'</span></a>', // rts
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Cancel" class="badge bg-yellow">'. $cancel_dummy .'</span></a>', // cancel by CH
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Reject" class="badge bg-red">'. $reject_dummy .'</span></a>', // reject by DIKA
				'<a href="javascript:void(0);" onclick="view_spv(\''.$nik.'\', `SPV`, `DUMMY SPV`)" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
			);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->cc_model_new->count_filtered($where),
			"recordsFiltered" => $this->cc_model_new->count_filtered($where),
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
			'date_to' => $this->input->post('date_to')
		);
		$this->session->set_userdata($session_data);
		echo json_encode(array("status" => TRUE));
	}
	
	function detailSPV($sales, $pos)
	{
		$this->session->set_userdata('sm_code', $sales);
		$this->session->set_userdata('sm_position', $pos);
		$data['position'] = $this->session->userdata('sm_position');

		//load view
		$this->load->view('incoming/cc_corp/detailSPV',$data);
	}
	
	function get_data_spv()
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sm_code'); //  id data yg di klik
		$pos 		= $this->session->userdata('sm_position'); //  position data yg di klik
		$user 		= $this->session->userdata('username'); // id yang sedang login
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');

		$array_structure = array('BSH', 'RSM', 'ASM', 'SPV');
		
		if($pos == 'RSM'){
			$where = "SM_Code = '$nik' AND Position = 'ASM'";
		}
		else if($pos == 'ASM'){
			$where = "SM_Code = '$nik' AND Position = 'SPV'";
		}
		else
		{
			$where = "SM_Code = '$nik' AND Position = 'RO'";
		}
		
		$query = $this->cc_model_new->get_datatables($where);
		$data = array();
		$no = $this->input->post('start');
		foreach ($query as $row){
			$sales = $row->DSR_Code;
			if (in_array($position, $array_structure)) {
				if (in_array($row->Position, $array_structure)) {
					$buttons = '
					<a href="javascript:void(0);" onclick="view_spv(\''.$sales.'\',\''.$row->Position.'\',\''.$row->Name.'\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';
					$var = 't3.'.$row->Position.'_Code';
				}else{
					$buttons = '';
					$var = 't1.Sales_Code';
				}
			}else{
				$buttons = '';
				$var = 't1.Sales_Code';
			}

			$getIncoming = $this->cc_model_new->getDataInputLeader($var, $sales, $date_from, $date_to, 'corp'); // corporate
			$total_dsr = $row->Position === 'RO' ? 0 : ($getIncoming->total_ro == null ? 0 : $getIncoming->total_ro);
			// $total_dsr = $getIncoming->total_ro == null ? 0 : $getIncoming->total_ro;
			$input = $getIncoming->total == null ? 0 : $getIncoming->total;
			$send = $getIncoming->send == null ? 0 : $getIncoming->send;
			$inprocess = $getIncoming->inprocess == null ? 0 : $getIncoming->inprocess;
			$duplicate = $getIncoming->duplicate == null ? 0 : $getIncoming->duplicate;
			$rts =  $getIncoming->rts == null ? 0 : $getIncoming->rts;
			$cancel = $getIncoming->cancel == null ? 0 : $getIncoming->cancel;
			$reject = $getIncoming->reject == null ? 0 : $getIncoming->reject;

			$data[] = array(
				++$no,
				$row->DSR_Code.', '.$row->Name.' ('.$row->Position.')',
				$row->Branch,
				'<span title="Total DSR Active" class="badge bg-black">'. $total_dsr .'</span>', // total dsr active
				'<span title="Total Input" class="badge bg-red">'. $input .'</span>', // total input
				'<a href="'.site_url('incoming/cc_corp/detail_leader/'.$var.'/'.$sales.'/SEND/').'" target="_blank"><span title="Total Send" class="badge bg-green">'. $send .'</span></a>', // submit
				'<a href="'.site_url('incoming/cc_corp/detail_leader/'.$var.'/'.$sales.'/INPROCESS/').'" target="_blank"><span title="Total Inprocess" class="badge bg-info">'. $inprocess .'</span></a>', // inprocess
				'<a href="'.site_url('incoming/cc_corp/detail_leader/'.$var.'/'.$sales.'/DUPLICATE/').'" target="_blank"><span title="Total Duplicate" class="badge bg-black">'. $duplicate .'</span></a>', // duplicate
				'<a href="'.site_url('incoming/cc_corp/detail_leader/'.$var.'/'.$sales.'/RTS/').'" target="_blank"><span title="Total RTS" class="badge bg-red">'.$rts .'</span></a>', // rts
				'<a href="'.site_url('incoming/cc_corp/detail_leader/'.$var.'/'.$sales.'/CANCEL/').'" target="_blank"><span title="Total Cancel" class="badge bg-yellow">'. $cancel .'</span></a>', // cancel by CH
				'<a href="'.site_url('incoming/cc_corp/detail_leader/'.$var.'/'.$sales.'/REJECT/').'" target="_blank"><span title="Total Reject" class="badge bg-red">'. $reject .'</span></a>', // reject by DIKA
				$buttons
			);
		}

		if($pos == 'ASM'){
			$getIncomingDummy = $this->cc_model_new->getDataInputDummy($nik, $date_from, $date_to, 'spv', 'corp'); // corporate
			$total_dsr_dummy = $getIncomingDummy->total_ro == null ? 0 : $getIncomingDummy->total_ro;
			$input_dummy = $getIncomingDummy->total == null ? 0 : $getIncomingDummy->total;
			$send_dummy = $getIncomingDummy->send == null ? 0 : $getIncomingDummy->send;
			$inprocess_dummy = $getIncomingDummy->inprocess == null ? 0 : $getIncomingDummy->inprocess;
			$duplicate_dummy = $getIncomingDummy->duplicate == null ? 0 : $getIncomingDummy->duplicate;
			$rts_dummy = $getIncomingDummy->rts == null ? 0 : $getIncomingDummy->rts;
			$cancel_dummy = $getIncomingDummy->cancel == null ? 0 : $getIncomingDummy->cancel;
			$reject_dummy = $getIncomingDummy->reject == null ? 0 : $getIncomingDummy->reject;

			$data[] = array(
				++$no,
				'DUMMY SPV',
				'ALL',
				'<span title="Total DSR Active" class="badge bg-black">'. $total_dsr_dummy .'</span>', // total dsr active
				'<span title="Total Input" class="badge bg-red">'. $input_dummy .'</span>', // input
				'<a href="'.site_url('incoming/cc_corp/detail_leader/t3.ASM_Code/'.$nik.'/SEND/').'" target="_blank"><span title="Total Send" class="badge bg-green">'. $send_dummy .'</span></a>', // submit
				'<a href="'.site_url('incoming/cc_corp/detail_leader/t3.ASM_Code/'.$nik.'/INPROCESS/').'" target="_blank"><span title="Total Inprocess" class="badge bg-info">'. $inprocess_dummy .'</span></a>', // inprocess
				'<a href="'.site_url('incoming/cc_corp/detail_leader/t3.ASM_Code/'.$nik.'/DUPLICATE/').'" target="_blank"><span title="Total Duplicate" class="badge bg-black">'. $duplicate_dummy .'</span></a>', // duplicate
				'<a href="'.site_url('incoming/cc_corp/detail_leader/t3.ASM_Code/'.$nik.'/RTS/').'" target="_blank"><span title="Total RTS" class="badge bg-red">'. $rts_dummy .'</span></a>', // rts
				'<a href="'.site_url('incoming/cc_corp/detail_leader/t3.ASM_Code/'.$nik.'/CANCEL/').'" target="_blank"><span title="Total Cancel" class="badge bg-yellow">'. $cancel_dummy .'</span></a>', // cancel by CH
				'<a href="'.site_url('incoming/cc_corp/detail_leader/t3.ASM_Code/'.$nik.'/REJECT/').'" target="_blank"><span title="Total Reject" class="badge bg-red">'. $reject_dummy .'</span></a>', // reject by DIKA
				'<a href="javascript:void(0);" onclick="view_spv(\''.$nik.'\', `SPV`, `DUMMY SPV`)" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
			);
		}
		
		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->cc_model_new->count_filtered($where),
			"recordsFiltered" => $this->cc_model_new->count_filtered($where),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	function view_leader()
    {
	    $username = $this->session->userdata('username'); // DSR_CODE
	    $position = $this->session->userdata('position');
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');
		
		$data['nik'] = $username;
		if($position == 'BSH'){
			$data['var'] = "t3.BSH_Code";
		}
		else if($position == 'RSM'){
			$data['var'] = "t3.RSM_Code";
		}
		else if($position == 'ASM'){
			$data['var'] = "t3.ASM_Code";
		}
		else{
			$data['var'] = "t3.SPV_Code";
		}
		$data['query'] = $this->cc_model_new->getDataInputLeader($data['var'], $username, $date_from, $date_to, 'corp'); // corporate

		//load view
		$this->template->set('title','Summary Incoming CC Corporate');
		$this->template->load('template','incoming/cc_corp/view_leader',$data);
    }

    // END LEADER PAGE

    // DSR PAGE
	function detail_leader_popup($sales_code, $sales, $status)
	{
		$tgl1 		= $this->session->userdata('date_from');
		$tgl2 		= $this->session->userdata('date_to');

		$data['status'] 	= $status;
		$data['query'] 	= $this->cc_model_new->detBreakdownInputLeader($sales_code, $sales, $status, 'corp', $tgl1, $tgl2);
		//load view
		$this->load->view('incoming/cc_corp/detail_leader_popup', $data);
	}
	
	function detail_leader($sales_code, $sales, $status)
	{
		$tgl1 		= $this->session->userdata('date_from');
		$tgl2 		= $this->session->userdata('date_to');

		$data['status'] 	= $status;
		$data['query'] 	= $this->cc_model_new->detBreakdownInputLeader($sales_code, $sales, $status, 'corp', $tgl1, $tgl2);
		//load view
		$this->template->set('title','Detail Actual Leader');
		$this->template->load('template','incoming/cc_corp/detail_leader', $data);
	}

	function detail_dsr($status)
	{
		$sales 		= $this->session->userdata('username');
		$tgl1 		= $this->session->userdata('date_from');
		$tgl2 		= $this->session->userdata('date_to');

		$data['status'] = $status;
		$data['query'] 	= $this->cc_model_new->detBreakdownInputDSR($sales, $status, 'corp', $tgl1, $tgl2);
		//load view	
		$this->load->view('incoming/cc_corp/detail_dsr', $data);
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
			$sheet->setCellValue('L1', "Customer Name");
			$sheet->setCellValue('M1', "Status");
			// $sheet->setCellValue('N1', "Week");
			// $sheet->setCellValue('O1', "Plafon");

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
			// $sheet->getStyle('N1')->applyFromArray($style_col);
			// $sheet->getStyle('O1')->applyFromArray($style_col2);
			//ambil data
			$date_from = $this->session->userdata('date_from');
			$date_to 	 = $this->session->userdata('date_to');

			$query = $this->cc_model_new->getBreakdownExport($date_from, $date_to, 'corp');

			//validasi jumlah data
			if (count($query) == 0) { ?>
				<script type="text/javascript" language="javascript">
					alert("No data...!!!");
				</script>
				<?php
				echo "<meta http-equiv='refresh' content='0; url=" . site_url() . "incoming/cc_corp'>";

				return false;
			} else {
				$no = 1; // Untuk penomoran tabel, di awal set dengan 1
				$numrow = 2; // Set baris pertama untuk isi tabel adalah baris ke 4
				foreach ($query as $data) { // Lakukan looping pada variabel sn
					$maskingname = $this->maskingname($data->Customer_Name);


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
					// $sheet->setCellValue('N' . $numrow, $data->Week);
					// $sheet->setCellValue('O' . $numrow, $data->Facilities_Type2);

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
					// $sheet->getStyle('N' . $numrow)->applyFromArray($style_row);
					// $sheet->getStyle('O' . $numrow)->applyFromArray($style_row);
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
				// $sheet->getColumnDimension('N')->setWidth(20);
				// $sheet->getColumnDimension('O')->setWidth(20);



				// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
				$sheet->getDefaultRowDimension()->setRowHeight(-1);
				// Set orientasi kertas jadi LANDSCAPE
				$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
				// Set judul file excel nya
				$sheet->setTitle("Laporan");
				// Proses file excel
				ob_end_clean();
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header("Content-Disposition: attachment; filename=Data Incoming Corp.xlsx");
				header('Cache-Control: max-age=0');
				$writer = new Xlsx($spreadsheet);
				$writer->save('php://output');
			}
		}

		function unit_testing_export()
		{
			$date_from = $this->session->userdata('date_from');
			$date_to 	 = $this->session->userdata('date_to');

			$query = $this->cc_model_new->getBreakdownExport($date_from, $date_to, 'corp');

			if ($query->num_rows() == 0) {
				$result = false;
			} else {
				$result = true;
			}

			$this->unit->run($result, 'is_true', "get data untuk export data dsr incoming");

			echo $this->unit->report();
		}
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