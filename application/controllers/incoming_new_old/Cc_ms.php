<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cc_ms extends MY_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url', 'download'));
		$this->load->library(array('auth', 'template', 'unit_test'));
		$this->load->model('incoming_new/cc_model', 'cc_model');
	}

	function index()
	{
		// $nik 		= $this->session->userdata('sl_code');
		// $where = "SM_Code = '$nik'";
		// $query = $this->cc_model->get_datatables($where);
		// var_dump($query);
		// die;
		$username = $this->session->userdata('username'); // DSR_CODE
		$position = $this->session->userdata('position');
		$date_filter = $this->input->post('date_filter');
		if (isset($date_filter)) {
			$this->session->set_userdata('date_from', $this->input->post('date_from'));
			$this->session->set_userdata('date_to', $this->input->post('date_to'));
		} else {
			$this->session->set_userdata('date_from', date('Y-m-01'));
			$this->session->set_userdata('date_to', date('Y-m-d'));
		}
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');

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
			$data['query'] = $this->cc_model->getDataInput($username, $date_from, $date_to, 'ms'); // Mobile Sales
		} else {
			$views = 'index';
		}

		//load view
		$this->template->set('title', 'Summary Incoming CC MS');
		$this->template->load('template', 'incoming_new/cc_ms/' . $views, $data);
	}

	// LEADER PAGE

	function get_data()
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');

		$array_leader = array('BSH', 'RSM', 'ASM', 'SPV');
		$array_dsr = array('DSR', 'SPG', 'SPB');

		if ($position == 'BSH') {
			$where = "SM_Code = '$nik' AND Position IN('RSM', 'ASM')";
		} else if ($position == 'RSM') {
			$where = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
		} else if ($position == 'ASM') {
			$where = "SM_Code = '$nik' AND Position = 'SPV'";
		} else {
			// $where = "SM_Code = '$nik' AND Position = 'Mobile Sales'";
			$where = "SM_Code = '$nik'";
		}

		// echo $where;
		// die;

		$query = $this->cc_model->get_datatables($where);
		$data = array();
		$no = $this->input->post('start');
		foreach ($query as $row) {
			// $sales = $row->DSR_Code;
			if (in_array($row->Position, $array_leader)) {
				$buttons = '
				<a href="javascript:void(0);" onclick="view_spv(\'' . $row->DSR_Code . '\',\'' . $row->Position . '\',\'' . $row->Name . '\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';
				$var = 't3.' . $row->Position . '_Code';
			} else {
				$buttons = '';
				$var = 't1.Sales_Code';
			}

			// $getIncoming = $this->cc_model->getDataInputLeader($var, $sales, $date_from, $date_to, 'ms'); // Mobile Sales
			// $total_dsr = $getIncoming->total_ms == null ? 0 : $getIncoming->total_ms;
			// $input = $getIncoming->total == null ? 0 : $getIncoming->total;
			// $send = $getIncoming->send == null ? 0 : $getIncoming->send;
			// $inprocess = $getIncoming->inprocess == null ? 0 : $getIncoming->inprocess;
			// $duplicate = $getIncoming->duplicate == null ? 0 : $getIncoming->duplicate;
			// $rts =  $getIncoming->rts == null ? 0 : $getIncoming->rts;
			// $cancel = $getIncoming->cancel == null ? 0 : $getIncoming->cancel;
			// $reject = $getIncoming->reject == null ? 0 : $getIncoming->reject;

			$data[] = array(
				++$no,
				$row->DSR_Code . ', ' . $row->Name . ' (' . $row->Position . ')',
				$row->Branch,
				// '<span title="Total DSR Active" class="badge bg-black">' . $total_dsr . '</span>', // total dsr active
				// '<span title="Total Input" class="badge bg-red">' . $input . '</span>', // total input
				// '<a href="javascript:void(0);" onclick="view_detail(\'' . $var . '\',\'' . $sales . '\',\'SEND\',\'' . $row->Name . '\')"><span title="Total Send" class="badge bg-green">' . $send . '</span></a>', // submit
				// $buttons
			);
		}

		// if ($position == 'ASM') {

		// 	$getIncomingDummy = $this->cc_model->getDataInputDummy($nik, $date_from, $date_to, 'asm', 'ms'); // Mobile Sales
		// 	$total_dsr_dummy = $getIncomingDummy->total_ms == null ? 0 : $getIncomingDummy->total_ms;
		// 	$input_dummy = $getIncomingDummy->total == null ? 0 : $getIncomingDummy->total;
		// 	$send_dummy = $getIncomingDummy->send == null ? 0 : $getIncomingDummy->send;
		// 	$inprocess_dummy = $getIncomingDummy->inprocess == null ? 0 : $getIncomingDummy->inprocess;
		// 	$duplicate_dummy = $getIncomingDummy->duplicate == null ? 0 : $getIncomingDummy->duplicate;
		// 	$rts_dummy = $getIncomingDummy->rts == null ? 0 : $getIncomingDummy->rts;
		// 	$cancel_dummy = $getIncomingDummy->cancel == null ? 0 : $getIncomingDummy->cancel;
		// 	$reject_dummy = $getIncomingDummy->reject == null ? 0 : $getIncomingDummy->reject;

		// 	$data[] = array(
		// 		++$no,
		// 		'DUMMY SPV',
		// 		'ALL',
		// 		'<span title="Total DSR Active" class="badge bg-black">' . $total_dsr_dummy . '</span>', // total dsr active
		// 		'<span title="Total Input" class="badge bg-red">' . $input_dummy . '</span>', // input
		// 		'<a href="javascript:void(0);" onclick="view_detail(`t3.SPV_Code`,`0`, `SPV`, `DUMMY SPV`)"><span title="Total Send" class="badge bg-green">' . $send_dummy . '</span></a>', // submit
		// 		'<a href="javascript:void(0);" onclick="view_spv(\'' . $nik . '\', `SPV`, `DUMMY SPV`)" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
		// 	);
		// }

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->cc_model->count_filtered($where),
			"recordsFiltered" => $this->cc_model->count_filtered($where),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	public function filter_data()
	{
		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
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

		if ($data['status'] === FALSE) {
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
		$this->load->view('incoming_new/cc_ms/detailSPV', $data);
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

		if ($pos == 'RSM') {
			$where = "SM_Code = '$nik' AND Position IN('ASM', 'SPV')";
		} else if ($pos == 'ASM') {
			$where = "SM_Code = '$nik' AND Position = 'SPV'";
		} else {
			$where = "SM_Code = '$nik' AND Position = 'Mobile Sales'";
		}

		$query = $this->cc_model->get_datatables($where);
		$data = array();
		$no = $this->input->post('start');
		foreach ($query as $row) {
			$sales = $row->DSR_Code;
			if (in_array($position, $array_structure)) {
				if (in_array($row->Position, $array_structure)) {
					$buttons = '
					<a href="javascript:void(0);" onclick="view_spv(\'' . $sales . '\',\'' . $row->Position . '\',\'' . $row->Name . '\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';
					$var = 't3.' . $row->Position . '_Code';
				} else {
					$buttons = '';
					$var = 't1.Sales_Code';
				}
			} else {
				$buttons = '';
				$var = 't1.Sales_Code';
			}

			$getIncoming = $this->cc_model->getDataInputLeader($var, $sales, $date_from, $date_to, 'ms'); // Mobile Sales
			$total_dsr = $getIncoming->total_ms == null ? 0 : $getIncoming->total_ms;
			$input = $getIncoming->total == null ? 0 : $getIncoming->total;
			$send = $getIncoming->send == null ? 0 : $getIncoming->send;
			$inprocess = $getIncoming->inprocess == null ? 0 : $getIncoming->inprocess;
			$duplicate = $getIncoming->duplicate == null ? 0 : $getIncoming->duplicate;
			$rts =  $getIncoming->rts == null ? 0 : $getIncoming->rts;
			$cancel = $getIncoming->cancel == null ? 0 : $getIncoming->cancel;
			$reject = $getIncoming->reject == null ? 0 : $getIncoming->reject;

			$data[] = array(
				++$no,
				$row->DSR_Code . ', ' . $row->Name . ' (' . $row->Position . ')',
				$row->Branch,
				'<span title="Total DSR Active" class="badge bg-black">' . $total_dsr . '</span>', // total dsr active
				'<span title="Total Input" class="badge bg-red">' . $input . '</span>', // total input
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/SEND/') . '" target="_blank"><span title="Total Send" class="badge bg-green">' . $send . '</span></a>', // submit
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/INPROCESS/') . '" target="_blank"><span title="Total Inprocess" class="badge bg-info">' . $inprocess . '</span></a>', // inprocess
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/DUPLICATE/') . '" target="_blank"><span title="Total Duplicate" class="badge bg-black">' . $duplicate . '</span></a>', // duplicate
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/RTS/') . '" target="_blank"><span title="Total RTS" class="badge bg-red">' . $rts . '</span></a>', // rts
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/CANCEL/') . '" target="_blank"><span title="Total Cancel" class="badge bg-yellow">' . $cancel . '</span></a>', // cancel by CH
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/' . $var . '/' . $sales . '/REJECT/') . '" target="_blank"><span title="Total Reject" class="badge bg-red">' . $reject . '</span></a>', // reject by DIKA
				$buttons
			);
		}

		if ($pos == 'ASM') {
			$getIncomingDummy = $this->cc_model->getDataInputDummy($nik, $date_from, $date_to, 'asm', 'ms'); // Mobile Sales
			$total_dsr_dummy = $getIncomingDummy->total_ms == null ? 0 : $getIncomingDummy->total_ms;
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
				'<span title="Total DSR Active" class="badge bg-black">' . $total_dsr_dummy . '</span>', // total dsr active
				'<span title="Total Input" class="badge bg-red">' . $input_dummy . '</span>', // input
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/SEND/') . '" target="_blank"><span title="Total Send" class="badge bg-green">' . $send_dummy . '</span></a>', // submit
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/INPROCESS/') . '" target="_blank"><span title="Total Inprocess" class="badge bg-info">' . $inprocess_dummy . '</span></a>', // inprocess
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/DUPLICATE/') . '" target="_blank"><span title="Total Duplicate" class="badge bg-black">' . $duplicate_dummy . '</span></a>', // duplicate
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/RTS/') . '" target="_blank"><span title="Total RTS" class="badge bg-red">' . $rts_dummy . '</span></a>', // rts
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/CANCEL/') . '" target="_blank"><span title="Total Cancel" class="badge bg-yellow">' . $cancel_dummy . '</span></a>', // cancel by CH
				'<a href="' . site_url('incoming_new/cc_ms/detail_leader/t3.ASM_Code/' . $nik . '/REJECT/') . '" target="_blank"><span title="Total Reject" class="badge bg-red">' . $reject_dummy . '</span></a>', // reject by DIKA
				'<a href="javascript:void(0);" onclick="view_spv(\'' . $nik . '\', `SPV`, `DUMMY SPV`)" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>'
			);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->cc_model->count_filtered($where),
			"recordsFiltered" => $this->cc_model->count_filtered($where),
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
		if ($position == 'BSH') {
			$data['var'] = "t3.BSH_Code";
		} else if ($position == 'RSM') {
			$data['var'] = "t3.RSM_Code";
		} else if ($position == 'ASM') {
			$data['var'] = "t3.ASM_Code";
		} else {
			$data['var'] = "t3.SPV_Code";
		}
		$data['query'] = $this->cc_model->getDataInputLeader($data['var'], $username, $date_from, $date_to, 'ms'); // Mobile Sales

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

		$data['status'] 	= $status;
		$data['query'] 	= $this->cc_model->detBreakdownInputLeader($sales_code, $sales, $status, 'ms', $tgl1, $tgl2);
		//load view
		$this->load->view('incoming_new/cc_ms/detail_leader_popup', $data);
	}

	function detail_leader($sales_code, $sales, $status)
	{
		$tgl1 		= $this->session->userdata('date_from');
		$tgl2 		= $this->session->userdata('date_to');

		$data['status'] 	= $status;
		$data['query'] 	= $this->cc_model->detBreakdownInputLeader($sales_code, $sales, $status, 'ms', $tgl1, $tgl2);
		//load view
		$this->template->set('title', 'Detail Actual Leader');
		$this->template->load('template', 'incoming_new/cc_ms/detail_leader', $data);
	}

	function detail_dsr($status)
	{
		$sales 		= $this->session->userdata('username');
		$tgl1 		= $this->session->userdata('date_from');
		$tgl2 		= $this->session->userdata('date_to');

		$data['status'] = $status;
		$data['query'] 	= $this->cc_model->detBreakdownInputDSR($sales, $status, 'ms', $tgl1, $tgl2);
		//load view	
		$this->load->view('incoming_new/cc_ms/detail_dsr', $data);
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

	function downloadFileCCMS()
	{
		$data = file_get_contents('assets/TemplateCCMS.xlsx');
		// var_dump($data);
		// die;
		// $name = 'Template Upload CCMS.xlsx';
		// force_download($name, $data);

		if (file_exists($data)) {
			$data = fopen($data, 'rb');
			// ... (lanjutkan dengan force_download atau yang lainnya)
			$this->unit->run($data, 'is_resource', 'Unit Testing Download Template CCMS');
		} else {
			$this->unit->run(FALSE, 'is_resource', 'Unit Testing Download Template CCMS');
		}

		echo $this->unit->report();
	}
}
