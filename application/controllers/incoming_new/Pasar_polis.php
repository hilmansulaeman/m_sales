<?php

use phpDocumentor\Reflection\Types\Null_;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pasar_polis extends MY_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library(array('auth', 'template'));
		$this->load->model('incoming/pasar_polis_model');
	}

	function index()
	{

		$username = $this->session->userdata('sl_code'); // DSR_CODE
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
			$data['query'] = $this->pasar_polis_model->getDataInput($username, $date_from, $date_to, 'reg'); // Reguler
		} else {
			$views = 'index';
		}

		//load view
		$this->template->set('title', 'Summary Incoming Pasar Polis');
		$this->template->load('template', 'incoming/pasar_polis/' . $views, $data);
	}

	// LEADER PAGE

	function get_data()
	{

		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');
		$date_from 	= $this->session->userdata('date_from');
		$date_to 	= $this->session->userdata('date_to');



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

		$query = $this->pasar_polis_model->get_datatables($where);

		$data = array();
		$no = $this->input->post('start');
		foreach ($query as $row) {
			$sales = $row->DSR_Code;
			if (in_array($row->Position, $array_leader)) {
				$buttons = '
				<a href="javascript:void(0);" onclick="view_spv(\'' . $sales . '\',\'' . $row->Position . '\',\'' . $row->Name . '\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>';
				$var = 't3.' . $row->Position . '_Code';
			} else {
				$buttons = '';
				$var = 't1.Sales_Code';
			}

			$getIncoming = $this->pasar_polis_model->getDataInputLeader($var, $sales, $date_from, $date_to); // Reguler

			$total_basic = ($getIncoming == null) ? 0 : $getIncoming->total_basic;
			$total_platinum = ($getIncoming == null) ? 0 : $getIncoming->total_platinum;
			$total_produk = ($getIncoming == null) ? 0 : $getIncoming->jumlah;

			$premi_basic = ($getIncoming->premi_basic == null) ? 0 : $getIncoming->premi_basic;
			$premi_platinum = ($getIncoming->premi_platinum == null) ? 0 : $getIncoming->premi_platinum;
			$total_premi = ($getIncoming->total_premi == null) ? 0 : $getIncoming->total_premi;

			$data[] = array(
				++$no,
				$row->DSR_Code . ', ' . $row->Name . ' (' . $row->Position . ')',
				'<span title="Total Send" class="badge bg-red">' . $total_basic . '</span>',
				'<span title="Total Send" class="badge bg-green">' . $premi_basic . '</span>',
				'<span title="Total Send" class="badge bg-red">' . $total_platinum . '</span>',
				'<span title="Total Send" class="badge bg-green">' . $premi_platinum . '</span>',
				'<span title="Total Send" class="badge bg-red">' . $total_produk . '</span>',
				'<span title="Total Send" class="badge bg-green">' . $total_premi . '</span>',
				$buttons
			);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->pasar_polis_model->count_filtered($where),
			"recordsFiltered" => $this->pasar_polis_model->count_filtered($where),
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
		$this->load->view('incoming/pasar_polis/detailSPV', $data);
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

		$query = $this->pasar_polis_model->get_datatables($where);
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

			$getIncoming = $this->pasar_polis_model->getDataInputLeader($var, $sales, $date_from, $date_to); // Reguler

			$total_basic = ($getIncoming == null) ? 0 : $getIncoming->total_basic;
			$total_platinum = ($getIncoming == null) ? 0 : $getIncoming->total_platinum;
			$total_produk = ($getIncoming == null) ? 0 : $getIncoming->jumlah;

			$premi_basic = ($getIncoming->premi_basic == null) ? 0 : $getIncoming->premi_basic;
			$premi_platinum = ($getIncoming->premi_platinum == null) ? 0 : $getIncoming->premi_platinum;
			$total_premi = ($getIncoming->total_premi == null) ? 0 : $getIncoming->total_premi;

			$data[] = array(
				++$no,
				$row->DSR_Code . ', ' . $row->Name . ' (' . $row->Position . ')',
				'<span title="Total Send" class="badge bg-red">' . $total_basic . '</span>',
				'<span title="Total Send" class="badge bg-green">' . $premi_basic . '</span>',
				'<span title="Total Send" class="badge bg-red">' . $total_platinum . '</span>',
				'<span title="Total Send" class="badge bg-green">' . $premi_platinum . '</span>',
				'<span title="Total Send" class="badge bg-red">' . $total_produk . '</span>',
				'<span title="Total Send" class="badge bg-green">' . $total_premi . '</span>',
				$buttons
			);
		}


		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->pasar_polis_model->count_filtered($where),
			"recordsFiltered" => $this->pasar_polis_model->count_filtered($where),
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
		$data['query'] = $this->pasar_polis_model->getDataInputLeader($data['var'], $username, $date_from, $date_to);
		// $data = $this->pasar_polis_model->getDataInputLeader($data['var'], $username, $date_from, $date_to);

		//load view
		$this->template->set('title','Summary Incoming CC Reg');
		$this->template->load('template','incoming/pasar_polis/view_leader',$data);
	}


	private function datediff($start, $end)
	{
		$date1 = date_create($start);
		$date2 = date_create($end);

		$days = date_diff($date1, $date2);

		return $days->format('%R%a');
	}
}
