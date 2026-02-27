<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cc_ms extends MY_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library(array('auth', 'template', 'unit_test'));
		$this->load->model('incoming_new/cc_model_new');
	}

	function test_curl()
	{
		$baseUrl = 'https://dev.ptdika.com/rest-api/api/incoming_cc/getDataInput';

		// Parameter query string
		$sales = 'K3700312';
		$from = '2024-01-01';
		$to = '2024-01-31';
		$type = 'reg';
		$project = 'Superindo Meruya Jakarta Barat';
		$apiKey = '4ad75498f665ec44c5b91e70c3cf6698';
		$apiUser = 'admindika';
		$apiPass = 'B3ndh1L2019';

		// Membuat array parameter query
		$params = array(
			'sales' => $sales,
			'from' => $from,
			'to' => $to,
			'type' => $type,
			'project' => $project
		);

		// Inisialisasi cURL session
		$ch = curl_init();

		// Set URL
		curl_setopt($ch, CURLOPT_URL, $baseUrl);

		// Set HTTP method
		curl_setopt($ch, CURLOPT_HTTPGET, 1);

		// Menambahkan parameter query string ke URL
		curl_setopt($ch, CURLOPT_URL, $baseUrl . '?' . http_build_query($params));

		// Set option untuk mengembalikan hasil sebagai string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Basic ' . base64_encode($apiUser . ':' . $apiPass),
			'X-API-KEY: ' . $apiKey
		));

		// Eksekusi cURL request
		$result = curl_exec($ch);

		// Check for cURL errors
		if (curl_errno($ch)) {
			echo 'Curl error: ' . curl_error($ch);
		} else {
			// Process the response data
			echo $result;
		}

		// Close cURL session
		curl_close($ch);
	}

	function index()
	{
		// $this->test_curl();
		// die;
		// try {
		$username = $this->session->userdata('sl_code'); // DSR_CODE
		$position = $this->session->userdata('position');

		$date_filter = $this->input->post('date_filter');
		if (isset($date_filter)) {
			$this->session->set_userdata('date_from', $this->input->post('date_from'));
			$this->session->set_userdata('date_to', $this->input->post('date_to'));
			$this->session->set_userdata('sessProject', $this->input->post('project'));
		} else {
			$this->session->set_userdata('date_from', date('Y-m-01'));
			$this->session->set_userdata('date_to', date('Y-m-d'));
			$this->session->set_userdata('sessProject', '');
		}
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');
		$project 	= $this->input->post('project');
		// var_dump($project); die;


		if ($position == 'BSH') {
			$data['position'] = "RSM";
			$data['detail'] = "ASM";
			$var = "t2.RSM_Code";
		} else if ($position == 'RSM') {
			$data['position'] = "ASM";
			$data['detail'] = "SPV";
			$var = "t2.ASM_Code";
		} else if ($position == 'ASM') {
			$data['position'] = "SPV";
			$data['detail'] = "DSR";
			$var = "t2.SPV_Code";
		} else if ($position == 'SPV') {
			$data['position'] = "DSR";
			$data['detail'] = "DSR";
			$var = "t1.sales_code";
		} else {
			$data['position'] = "DSR";
		}

		if ($position == 'DSR') {
			$views = 'index_dsr';
			// query data input API
			$data['query'] = $this->cc_model_new->getDataInput($username, $date_from, $date_to, 'reg', $project); // Reguler
			// $data['project'] = $this->cc_model_new->getDataProject(); // get project
		} else {
			$views = 'index';
		}

		$data['selected_project'] = $this->session->userdata('sessProject');
		$data['project'] = $this->cc_model_new->getDataProject(); // get project
		//load view
		$this->template->set('title', 'Summary Incoming CC MS');
		$this->template->load('template', 'incoming_new/cc_ms/' . $views, $data);

		// 	$result = true;
		// } catch (Exception $e) {
		// 	$result = false;
		// }


		// $this->unit->run($result, "is_true", "Unit Testing filter");
		// echo $this->unit->report();
	}

	// LEADER PAGE

	function get_data()
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');
		$project 	= $this->session->set_userdata('project', $this->input->post('project'));
		// var_dump($project); die;	
		// $this->session->set_userdata('sessProject', '');
		// $project 	= $this->input->post('project');


		// cekvar($date_from);

		$dsr_position = "('DSR','SPG','SPB')";
		$array_leader = array('BSH', 'RSM', 'ASM', 'SPV');
		$array_dsr = array('DSR', 'SPG', 'SPB');

		if ($position == 'BSH') {
			$where = "SM_Code = '$nik' AND Position IN('RSM', 'ASM', 'SPV')";
		} else if ($position == 'RSM') {
			$where = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
		} else if ($position == 'ASM') {
			$where = "SM_Code = '$nik' AND Position = 'SPV'";
		} else {
			$where = "SM_Code = '$nik' AND Position IN $dsr_position";
		}
		$query = $this->cc_model_new->get_datatables($where);


		$data = array();
		$no = $this->input->post('start');
		foreach ($query as $row) {
			$sales = $row->DSR_Code;
			// cekvar($sales);
			if (in_array($row->Position, $array_leader)) {
				$buttons = '
				<a href="javascript:void(0);" onclick="view_spv(\'' . $sales . '\',\'' . $row->Position . '\',\'' . $row->Name . '\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';
				$var = 't3.' . $row->Position . '_Code';
				$var2 = $row->Position . '_Code';
			} else {
				$buttons = '';
				$var = 't1.Sales_Code';
				$var2 = 'Sales_Code';
			}

			$getIncoming = $this->cc_model_new->getDataInputLeader($var, $sales, $date_from, $date_to, 'reg'); // Reguler
			// $getIncoming = $this->cc_model_new->getDataInputLeader($var, $sales, $date_from, $date_to, 'reg', $project); // Reguler

			$getIncomingMS = $this->cc_model_new->getDataInputMSLeader($var2, $sales, $date_from, $date_to); // Reguler

			$total_dsr 	 = $getIncoming->total_dsr == null ? 0 : $getIncoming->total_dsr;
			$input 		 = $getIncoming->total == null ? 0 : $getIncoming->total;
			$send 		 = $getIncoming->send == null ? 0 : $getIncoming->send;
			$send_hc 	 = $getIncoming->send_hc == null ? 0 : $getIncoming->send_hc;
			$inprocess 	 = $getIncoming->inprocess == null ? 0 : $getIncoming->inprocess;
			$duplicate 	 = $getIncoming->duplicate == null ? 0 : $getIncoming->duplicate;
			$rts 		 = $getIncoming->rts == null ? 0 : $getIncoming->rts;
			$cancel 	 = $getIncoming->cancel == null ? 0 : $getIncoming->cancel;
			$reject 	 = $getIncoming->reject == null ? 0 : $getIncoming->reject;
			$send_acco 	 = $getIncoming->acco == null ? 0 : $getIncoming->acco;
			$send_ms 	 = $getIncomingMS->send_ms == null ? 0 : $getIncomingMS->send_ms;


			$data[] = array(
				++$no,
				$row->DSR_Code . ', ' . $row->Name . ' (' . $row->Position . ')',
				$row->Branch,
				'<span title="Total DSR Active" class="badge bg-black">' . $total_dsr . '</span>', // total dsr active
				'<span title="Total Input" class="badge bg-red">' . $input . '</span>', // total input
				'<a href="javascript:void(0);" onclick="view_detail(\'' . $var . '\',\'' . $sales . '\',\'SEND\',\'' . $row->Name . '\')"><span title="Total Sendddd" class="badge bg-green">' . $send . '</span></a>', // send
				'<a href="javascript:void(0);" onclick="view_detail(\'' . $var . '\',\'' . $sales . '\',\'SEND_ACCO\',\'' . $row->Name . '\')"><span title="Total Send Acco" class="badge bg-blue">' . $send_acco . '</span></a>', // send acco by DIKA
				'<a href="javascript:void(0);" onclick="view_detail(\'' . $var . '\',\'' . $sales . '\',\'SEND_HC\',\'' . $row->Name . '\')"><span title="Total Send HC" class="badge bg-green">' . $send_hc . '</span></a>', // send hc
				'<a href="javascript:void(0);" onclick="view_detail(\'' . $var . '\',\'' . $sales . '\',\'INPROCESS\',\'' . $row->Name . '\')"><span title="Total Inprocess" class="badge bg-info">' . $inprocess . '</span></a>', // inprocess
				'<a href="javascript:void(0);" onclick="view_detail(\'' . $var . '\',\'' . $sales . '\',\'DUPLICATE\',\'' . $row->Name . '\')"><span title="Total Duplicate" class="badge bg-black">' . $duplicate . '</span></a>', // duplicate
				'<a href="javascript:void(0);" onclick="view_detail(\'' . $var . '\',\'' . $sales . '\',\'RTS\',\'' . $row->Name . '\')"><span title="Total RTS" class="badge bg-red">' . $rts . '</span></a>', // rts
				'<a href="javascript:void(0);" onclick="view_detail(\'' . $var . '\',\'' . $sales . '\',\'CANCEL\',\'' . $row->Name . '\')"><span title="Total Cancel" class="badge bg-yellow">' . $cancel . '</span></a>', // cancel by CH
				'<a href="javascript:void(0);" onclick="view_detail(\'' . $var . '\',\'' . $sales . '\',\'REJECT\',\'' . $row->Name . '\')"><span title="Total Reject" class="badge bg-red">' . $reject . '</span></a>', // reject by DIKA
				'<a href="javascript:void(0);" onclick="view_detail_ms(\'' . $var2 . '\',\'' . $sales . '\',\'' . $row->Name . '\')"><span title="Total Send MS" class="badge bg-purple">' . $send_ms . '</span></a>', // send ms by DIKA
				$buttons
			);
		}

		if ($position == 'ASM') {

			$getIncomingDummy = $this->cc_model_new->getDataInputDummy($nik, $date_from, $date_to, 'asm', 'reg'); // Reguler
			$getIncomingMSDummy = $this->cc_model_new->getDataInputMSDummy($nik, $date_from, $date_to, 'asm', 'reg'); // Reguler
			$total_dsr_dummy  = $getIncomingDummy->total_dsr == null ? 0 : $getIncomingDummy->total_dsr;
			$input_dummy 	  = $getIncomingDummy->total == null ? 0 : $getIncomingDummy->total;
			$send_dummy       = $getIncomingDummy->send == null ? 0 : $getIncomingDummy->send;
			$send_hc_dummy    = $getIncomingDummy->send_hc == null ? 0 : $getIncomingDummy->send_hc;
			$inprocess_dummy  = $getIncomingDummy->inprocess == null ? 0 : $getIncomingDummy->inprocess;
			$duplicate_dummy  = $getIncomingDummy->duplicate == null ? 0 : $getIncomingDummy->duplicate;
			$rts_dummy        = $getIncomingDummy->rts == null ? 0 : $getIncomingDummy->rts;
			$cancel_dummy     = $getIncomingDummy->cancel == null ? 0 : $getIncomingDummy->cancel;
			$reject_dummy     = $getIncomingDummy->reject == null ? 0 : $getIncomingDummy->reject;
			$send_acco_dummy  = $getIncomingDummy->acco == null ? 0 : $getIncomingDummy->acco;
			$send_ms_dummy 	  = $getIncomingMSDummy->send_ms == null ? 0 : $getIncomingMSDummy->send_ms;

			$data[] = array(
				++$no,
				'DUMMY SPV',
				'ALL',
				'<span title="Total DSR Active" class="badge bg-black">' . $total_dsr_dummy . '</span>', // total dsr active
				'<span title="Total Input" class="badge bg-red">' . $input_dummy . '</span>', // input
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Send" class="badge bg-green">' . $send_dummy . '</span></a>', // send
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Send Acco" class="badge bg-blue">' . $send_acco_dummy . '</span></a>', // send acco kode referensi != 0
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Send hc" class="badge bg-green">' . $send_hc_dummy . '</span></a>', // send hc
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Inprocess" class="badge bg-info">' . $inprocess_dummy . '</span></a>', // inprocess
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Duplicate" class="badge bg-black">' . $duplicate_dummy . '</span></a>', // duplicate
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total RTS" class="badge bg-red">' . $rts_dummy . '</span></a>', // rts
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Cancel" class="badge bg-yellow">' . $cancel_dummy . '</span></a>', // cancel by CH
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Reject" class="badge bg-red">' . $reject_dummy . '</span></a>', // reject by DIKA
				'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Send MS" class="badge bg-purple">' . $send_ms_dummy . '</span></a>', // send ms by DIKA
				'<a href="javascript:void(0);" onclick="view_spv(\'' . $nik . '\', `SPV`, `DUMMY SPV`)" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
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
		$project = $this->input->post('project');

		// $data_filter = $this->input->post('date_filter');
		// if(isset($data_filter)){
		// 	$this->session->set_userdata('date_from', $this->input->post('date_from'));
		// 	$this->session->set_userdata('date_to', $this->input->post('date_to'));
		// 	$this->session->set_userdata('sessProject', $this->input->post('project'));
		// }else{
		// 	$this->session->set_userdata('date_from', date('Y-m-01'));
		// 	$this->session->set_userdata('date_to', date('Y-m-d'));
		// 	$this->session->set_userdata('sessProject', '');

		// }

		$range = $this->datediff($date_from, $date_to);
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($range > 31) {
			$data['inputerror'][] = 'date_to';
			$data['error_string'][] = 'Maaf, range tanggal maksimal 31 hari';
			$data['status'] = FALSE;
		}

		if ($project == '') {
			$data['inputerror'][] = 'date_to';
			$data['error_string'][] = 'pilih nama pameran';
			$data['status'] = FALSE;
		}

		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
		$session_data = array(
			'date_from' => $this->input->post('date_from'),
			'date_to' => $this->input->post('date_to'),
			'project' => $this->input->post('project')
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
		$this->load->view('incoming_new/cc_ms/detailSPV', $data);
	}

	function get_data_spv()
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sm_code'); //  id data yg di klik
		$pos 		= $this->session->userdata('sm_position'); //  position data yg di klik
		$user 		= $this->session->userdata('sl_code'); // id yang sedang login
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');

		$dsr_position = "('DSR','SPG','SPB')";
		$array_structure = array('BSH', 'RSM', 'ASM', 'SPV');

		if ($pos == 'RSM') {
			$where = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
		} else if ($pos == 'ASM') {
			$where = "SM_Code = '$nik' AND Position = 'SPV'";
		} else {
			$where = "SM_Code = '$nik' AND Position IN $dsr_position";
		}

		$query = $this->cc_model_new->get_datatables($where);
		$data = array();
		$no = $this->input->post('start');
		foreach ($query as $row) {
			$sales = $row->DSR_Code;
			if (in_array($position, $array_structure)) {
				if (in_array($row->Position, $array_structure)) {
					$buttons = '
					<a href="javascript:void(0);" onclick="view_spv(\'' . $sales . '\',\'' . $row->Position . '\',\'' . $row->Name . '\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';
					$var = 't3.' . $row->Position . '_Code';
					$var2 = $row->Position . '_Code';
				} else {
					$buttons = '';
					$var = 't1.Sales_Code';
					$var2 = 'Sales_Code';
				}
			} else {
				$buttons = '';
				$var = 't1.Sales_Code';
				$var2 = 'Sales_Code';
			}

			$getIncoming = $this->cc_model_new->getDataInputLeader($var, $sales, $date_from, $date_to, 'reg'); // Reguler
			$getIncomingMS = $this->cc_model_new->getDataInputMSLeader($var2, $sales, $date_from, $date_to); // Reguler
			$total_dsr   = $getIncoming->total_dsr == null ? 0 : $getIncoming->total_dsr;
			$input 	     = $getIncoming->total == null ? 0 : $getIncoming->total;
			$send 		 = $getIncoming->send == null ? 0 : $getIncoming->send;
			$send_hc     = $getIncoming->send_hc == null ? 0 : $getIncoming->send_hc;
			$inprocess 	 = $getIncoming->inprocess == null ? 0 : $getIncoming->inprocess;
			$duplicate 	 = $getIncoming->duplicate == null ? 0 : $getIncoming->duplicate;
			$rts 		 = $getIncoming->rts == null ? 0 : $getIncoming->rts;
			$cancel 	 = $getIncoming->cancel == null ? 0 : $getIncoming->cancel;
			$reject 	 = $getIncoming->reject == null ? 0 : $getIncoming->reject;
			$send_acco 	 = $getIncoming->acco == null ? 0 : $getIncoming->acco;
			$send_ms 	 = $getIncomingMS->send_ms == null ? 0 : $getIncomingMS->send_ms;

			$data[] = array(
				++$no,
				$row->DSR_Code . ', ' . $row->Name . ' (' . $row->Position . ')',
				$row->Branch,
				'<span title="Total DSR Active" class="badge bg-black">' . $total_dsr . '</span>', // total dsr active
				'<span title="Total Input" class="badge bg-red">' . $input . '</span>', // total input
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/SEND/') . '" target="_blank"><span title="Total Send" class="badge bg-green">' . $send . '</span></a>', // send
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/SEND_ACCO/') . '" target="_blank"><span title="Total Send Acco" class="badge bg-blue">' . $send_acco . '</span></a>', // send
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/SEND_HC/') . '" target="_blank"><span title="Total Send hc" class="badge bg-green">' . $send_hc . '</span></a>', // send hc
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/INPROCESS/') . '" target="_blank"><span title="Total Inprocess" class="badge bg-info">' . $inprocess . '</span></a>', // inprocess
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/DUPLICATE/') . '" target="_blank"><span title="Total Duplicate" class="badge bg-black">' . $duplicate . '</span></a>', // duplicate
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/RTS/') . '" target="_blank"><span title="Total RTS" class="badge bg-red">' . $rts . '</span></a>', // rts
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/CANCEL/') . '" target="_blank"><span title="Total Cancel" class="badge bg-yellow">' . $cancel . '</span></a>', // cancel by CH
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/REJECT/') . '" target="_blank"><span title="Total Reject" class="badge bg-red">' . $reject . '</span></a>', // reject by DIKA
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader_ms/' . $var2 . '/' . $sales . '/SEND_MS/') . '" target="_blank"><span title="Total Send MS" class="badge bg-purple">' . $send_ms . '</span></a>', // send by DIKA
				$buttons
			);
		}

		if ($pos == 'ASM') {
			$getIncomingDummy = $this->cc_model_new->getDataInputDummy($nik, $date_from, $date_to, 'asm', 'reg'); // Reguler
			$getIncomingMSDummy = $this->cc_model_new->getDataInputMSDummy($nik, $date_from, $date_to, 'asm', 'reg'); // Reguler
			$total_dsr_dummy  = $getIncomingDummy->total_dsr == null ? 0 : $getIncomingDummy->total_dsr;
			$input_dummy      = $getIncomingDummy->total == null ? 0 : $getIncomingDummy->total;
			$send_dummy       = $getIncomingDummy->send == null ? 0 : $getIncomingDummy->send;
			$send_hc_dummy    = $getIncomingDummy->send_hc == null ? 0 : $getIncomingDummy->send_hc;
			$inprocess_dummy  = $getIncomingDummy->inprocess == null ? 0 : $getIncomingDummy->inprocess;
			$duplicate_dummy  = $getIncomingDummy->duplicate == null ? 0 : $getIncomingDummy->duplicate;
			$rts_dummy        = $getIncomingDummy->rts == null ? 0 : $getIncomingDummy->rts;
			$cancel_dummy     = $getIncomingDummy->cancel == null ? 0 : $getIncomingDummy->cancel;
			$reject_dummy     = $getIncomingDummy->reject == null ? 0 : $getIncomingDummy->reject;
			$send_acco_dummy  = $getIncomingDummy->acco == null ? 0 : $getIncomingDummy->acco;
			$send_ms_dummy 	  = $getIncomingMSDummy->send_ms == null ? 0 : $getIncomingMSDummy->send_ms;

			$data[] = array(
				++$no,
				'DUMMY SPV',
				'ALL',
				'<span title="Total DSR Active" class="badge bg-black">' . $total_dsr_dummy . '</span>', // total dsr active
				'<span title="Total Input" class="badge bg-red">' . $input_dummy . '</span>', // input
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/SEND/') . '" target="_blank"><span title="Total Send" class="badge bg-green">' . $send_dummy . '</span></a>', // send
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/SEND_ACCO/') . '" target="_blank"><span title="Total Send" class="badge bg-blue">' . $send_acco_dummy . '</span></a>', // send
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/SEND_HC/') . '" target="_blank"><span title="Total Send hc" class="badge bg-green">' . $send_hc_dummy . '</span></a>', // send hc
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/INPROCESS/') . '" target="_blank"><span title="Total Inprocess" class="badge bg-info">' . $inprocess_dummy . '</span></a>', // inprocess
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/DUPLICATE/') . '" target="_blank"><span title="Total Duplicate" class="badge bg-black">' . $duplicate_dummy . '</span></a>', // duplicate
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/RTS/') . '" target="_blank"><span title="Total RTS" class="badge bg-red">' . $rts_dummy . '</span></a>', // rts
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/CANCEL/') . '" target="_blank"><span title="Total Cancel" class="badge bg-yellow">' . $cancel_dummy . '</span></a>', // cancel by CH
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/REJECT/') . '" target="_blank"><span title="Total Reject" class="badge bg-red">' . $reject_dummy . '</span></a>', // reject by DIKA
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader_ms/ASM_Code/' . $nik . '/SEND_MS/') . '" target="_blank"><span title="Total SEND MS" class="badge bg-red">' . $send_ms_dummy . '</span></a>', // reject by DIKA
				'<a href="javascript:void(0);" onclick="view_spv(\'' . $nik . '\', `SPV`, `DUMMY SPV`)" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
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
		$username = $this->session->userdata('sl_code'); // DSR_CODE
		$position = $this->session->userdata('position');
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');

		$data['nik'] = $username;
		if ($position == 'BSH') {
			$data['var'] = "t3.BSH_Code";
		} else if ($position == 'RSM') {
			$data['var'] = "t3.RSM_Code";
		} else if ($position == 'ASM') {
			$data['var'] = "t3.ASM_Code";
		} else {
			$data['var'] = "t3.SPV_Code";
		}
		$data['query'] = $this->cc_model_new->getDataInputLeader($data['var'], $username, $date_from, $date_to, 'reg'); // Reguler

		//load view
		$this->template->set('title', 'Summary Incoming CC MS');
		$this->template->load('template', 'incoming_new/cc_ms/view_leader', $data);
	}

	// END LEADER PAGE

	// DSR PAGE
	function detail_leader_popup($sales_code, $sales, $status)
	{
		$tgl1 		= $this->session->userdata('date_from');
		$tgl2 		= $this->session->userdata('date_to');

		$data['status'] = $status;

		$data['query'] 	= $this->cc_model_new->detBreakdownInputLeader($sales_code, $sales, $status, 'reg', $tgl1, $tgl2);

		//load view
		$this->load->view('incoming_new/cc_ms/detail_leader_popup', $data);
	}

	function detail_leaderMS_popup($sales_code, $sales)
	{
		$tgl1 		= $this->session->userdata('date_from');
		$tgl2 		= $this->session->userdata('date_to');

		// $data['status'] = $status;

		$data['query'] 	= $this->cc_model_new->detBreakdownInputMSLeader($sales_code, $sales, 'reg', $tgl1, $tgl2);

		//load view
		$this->load->view('incoming_new/cc_ms/detail_leader_ms_popup', $data);
	}

	function detail_leader($sales_code, $sales, $status)
	{
		$tgl1 		= $this->session->userdata('date_from');
		$tgl2 		= $this->session->userdata('date_to');

		$data['status'] 	= $status;
		$data['query'] 	= $this->cc_model_new->detBreakdownInputLeader($sales_code, $sales, $status, 'reg', $tgl1, $tgl2);
		//load view
		$this->template->set('title', 'Detail Actual Leader');
		$this->template->load('template', 'incoming_new/cc_ms/detail_leader', $data);
	}

	function detail_leader_ms($sales_code, $sales, $status)
	{
		$tgl1 		= $this->session->userdata('date_from');
		$tgl2 		= $this->session->userdata('date_to');

		$data['status'] 	= $status;
		$data['query'] 	= $this->cc_model_new->detBreakdownInputMSLeader($sales_code, $sales, $tgl1, $tgl2);
		//load view
		$this->template->set('title', 'Detail Actual Leader');
		$this->template->load('template', 'incoming_new/cc_ms/detail_leader_ms', $data);
	}

	function detail_dsr($status)
	{
		// try {
		$sales 		 = $this->session->userdata('sl_code');
		$tgl1 		 = $this->session->userdata('date_from');
		$tgl2 		 = $this->session->userdata('date_to');
		$sessProject = $this->session->userdata('sessProject');
		// $project 	= $this->input->post('project');
		// $project 	=  $this->session->set_userdata('project', $this->input->post('project'));

		// var_dump($sales);
		// var_dump($status);
		// var_dump('reg');
		// var_dump($tgl1);
		// var_dump($tgl2);
		// var_dump($sessProject);
		// die();

		$data['status'] = $status;
		$data['query'] 	= $this->cc_model_new->detBreakdownInputDSR($sales, $status, 'reg', $tgl1, $tgl2, $sessProject);
		$data['project'] = $this->cc_model_new->getDataProject(); // get project

		//load view	
		$this->load->view('incoming_new/cc_ms/detail_dsr', $data);
		// $result = true;
		// } catch (Exception $e) {
		// 	$result = false;
		// }

		// $this->unit->run($result, "is_true", "Unit Testing detail");
		// echo $this->unit->report();
	}
	// END DSR PAGE

	//================================================= INTERNAL FUNCTION =============================================//
	//datedif
	private function datediff($start, $end)
	{
		$date1 = date_create($start);
		$date2 = date_create($end);

		$days = date_diff($date1, $date2);

		return $days->format('%R%a');
	}

	function unit_test()
	{
		$cc_reg = $this->cc_model_new->unit_test();

		$this->unit->run($cc_reg, 'is_array', 'Testing data detail');

		// print hasil testing
		echo $this->unit->report();
	}
}
