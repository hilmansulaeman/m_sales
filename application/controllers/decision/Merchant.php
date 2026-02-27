<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Merchant extends MY_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library(array('auth', 'template'));
		$this->load->model('decision/merchant_model');
	}

	function index()
	{
		$username = $this->session->userdata('sl_code'); // DSR_CODE
		$position = $this->session->userdata('position');
		$date_filter = $this->input->post('date');
		if (isset($date_filter)) {
			$this->session->set_userdata('groupDate', $date_filter);
		} else {
			$period = $this->merchant_model->get_last_period();
			$group_date = $period->num_rows() == 0 ? date('Y-m') : $period->row()->Group_Date;
			$this->session->set_userdata('groupDate', $group_date);
		}
		$groupDate 	= $this->session->userdata('groupDate');

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

			// $data['getEdcNew'] = $this->merchant_model->get_actualEDC('Sales_Code', $username, $groupDate, 'new', 'EDC');
			// $data['getEdcExis'] = $this->merchant_model->get_actualEDC('Sales_Code', $username, $groupDate, 'exis', 'EDC');

			// $data['getQrisNew'] = $this->merchant_model->get_actualQRIS('Sales_Code', $username, $groupDate, 'new', 'QRIS');
			// $data['getQrisExis'] = $this->merchant_model->get_actualQRIS('Sales_Code', $username, $groupDate, 'exis', 'QRIS');

			// $data['getEdcQrisNew'] = $this->merchant_model->get_actualEDC('Sales_Code', $username, $groupDate, 'new', 'EDC_QRIS');
			// $data['getEdcQrisExis'] = $this->merchant_model->get_actualEDC('Sales_Code', $username, $groupDate, 'exis', 'EDC_QRIS');

			// $data['getPKEdc'] = $this->merchant_model->get_point_edc('Sales_Code', $username, $groupDate, 'KREDIT', 'EDC');
			// $data['getPNKEdc'] = $this->merchant_model->get_point_edc('Sales_Code', $username, $groupDate, 'NON KREDIT', 'EDC');
			// $data['getPEEdc'] = $this->merchant_model->get_point_edc('Sales_Code', $username, $groupDate, 'Existing', 'EDC');

			// $data['getPNQris'] = $this->merchant_model->get_point_qris('Sales_Code', $username, $groupDate, 'New', 'QRIS');
			// $data['getPEQris'] = $this->merchant_model->get_point_qris('Sales_Code', $username, $groupDate, 'Existing', 'QRIS');

			// $data['getPKEdcQris'] = $this->merchant_model->get_point_edc('Sales_Code', $username, $groupDate, 'KREDIT', 'EDC_QRIS');
			// $data['getPNKEdcQris'] = $this->merchant_model->get_point_edc('Sales_Code', $username, $groupDate, 'NON KREDIT', 'EDC_QRIS');
			// $data['getPEEdcQris'] = $this->merchant_model->get_point_edc('Sales_Code', $username, $groupDate, 'Existing', 'EDC');
			$getEdcResult = $this->merchant_model->get_summary_detail('Sales_Code', $username, $groupDate, 'EDC');
			$getEdcResult2 = $this->merchant_model->get_summary_detail('Sales_Code', $username, $groupDate, 'EDC_QRIS');
			$getQrisResult = $this->merchant_model->get_summary_detail('Sales_Code', $username, $groupDate, 'QRIS');

			// EDC
			$actual_kredit = 0 + $getEdcResult->kredit;
			$actual_nonkredit = 0 + $getEdcResult->non_kredit;
			$data['totalPointKredit'] = $this->merchant_model->calculate_point('EDC', 'KREDIT', $actual_kredit);
			$data['totalPointNonKredit'] = $this->merchant_model->calculate_point('EDC', 'NON KREDIT', $actual_nonkredit);
			$data['totalPointsExisEDC'] = $this->merchant_model->calculate_point('EDC', 'EXISTING', 0 + $getEdcResult->exis);
			$data['allDataEDC'] = $getEdcResult;

			// EDC + QRIS
			$actual_kredit2 = 0 + $getEdcResult2->kredit;
			$actual_nonkredit2 = 0 + $getEdcResult2->non_kredit;
			$data['totalPointKredit2'] = $this->merchant_model->calculate_point('EDC', 'KREDIT', $actual_kredit2);
			$data['totalPointNonKredit2'] = $this->merchant_model->calculate_point('EDC', 'NON KREDIT', $actual_nonkredit2);
			$data['totalPointsExisEDC2'] = $this->merchant_model->calculate_point('EDC', 'EXISTING', 0 + $getEdcResult2->exis);
			$data['allDataEDC2'] = $getEdcResult2;

			//QRIS
			$data['totalPointsNewQRIS'] = $this->merchant_model->calculate_point('QRIS', 'NEW', 0 + $getQrisResult->ntb);
			$data['totalPointsExisQRIS'] = $this->merchant_model->calculate_point('QRIS', 'EXISTING', 0 + $getQrisResult->exis);
			$data['dataQrisNew'] = $this->merchant_model->get_summary_qris('Sales_Code', $username, $groupDate, 'NEW');
			$data['dataQrisExis'] = $this->merchant_model->get_summary_qris('Sales_Code', $username, $groupDate, 'EXISTING');
			$data['dataQrisReject'] = $this->merchant_model->get_summary_qris('Sales_Code', $username, $groupDate, 'REJECT');

			$data['sales_code'] = 'Sales_Code';
			$data['sales'] = $username;
			// query data input API
		} else {
			$views = 'index';
			// query data input local
		}

		//load view
		$this->template->set('title', 'Decision Merchant');
		$this->template->load('template', 'decision/merchant/' . $views, $data);
	}

	// LEADER PAGE

	function get_data()
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');
		$groupDate 	= $this->session->userdata('groupDate');

		$dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$asm_position = "('SPV', 'DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$array_structure = array('BSH', 'RSM', 'ASM', 'SPV');

		if ($position == 'BSH') {
			$where = "BSH_Code = '$nik'";
			$groups = "RSM_Code";
		} else if ($position == 'RSM') {
			$where = "RSM_Code = '$nik'";
			$groups = "ASM_Code";
		} else if ($position == 'ASM') {
			$where = "ASM_Code = '$nik'";
			// $where = "SM_Code = '$nik' AND Position IN $asm_position";
			$groups = "SPV_Code";
		} else {
			$where = "SPV_Code = '$nik'";
			$groups = "Sales_Code";
		}
		$query = $this->merchant_model->get_datatables($where, $groups, $groupDate);
		$data = array();
		$no = $this->input->post('start');
		foreach ($query->result() as $row) {
			if ($position == 'BSH') {
				$sales_code = $row->RSM_Code;
				$sales_name = $row->RSM_Name;
				$var = 'RSM_Code';
			} else if ($position == 'RSM') {
				$sales_code = $row->ASM_Code;
				$sales_name = $row->ASM_Name;
				$var = 'ASM_Code';
			} else if ($position == 'ASM') {
				$sales_code = $row->SPV_Code;
				$sales_name = $row->SPV_Name;
				$var = 'SPV_Code';
			} else {
				$sales_code = $row->Sales_Code;
				$sales_name = $row->Sales_Name;
				$var = 'Sales_Code';
			}

			$sales_position = substr($var, 0, -5);

			$getEdcResult = $this->merchant_model->get_summary($var, $sales_code, $groupDate, 'EDC');
			$getQrisResult = $this->merchant_model->get_summary($var, $sales_code, $groupDate, 'QRIS');

			//Account
			$actual_new = 0 + $getEdcResult->ntb + $getQrisResult->ntb;
			$actual_existing = 0 + $getEdcResult->exis + $getQrisResult->exis;
			$actual_rejected = 0 + $getEdcResult->rejected + $getQrisResult->rejected;
			$actual_kredit = 0 + $getEdcResult->kredit;
			$actual_nonkredit = 0 + $getEdcResult->non_kredit;

			//Point New
			$point_kredit = $this->merchant_model->calculate_point('EDC', 'KREDIT', $actual_kredit);
			$point_nonkredit = $this->merchant_model->calculate_point('EDC', 'NON KREDIT', $actual_nonkredit);
			$point_qris_new = $this->merchant_model->calculate_point('QRIS', 'NEW', 0 + $getQrisResult->ntb);
			$totalPointsNew = $point_kredit + $point_nonkredit + $point_qris_new;

			//Point Existing
			$point_edc_exis = $this->merchant_model->calculate_point('EDC', 'EXISTING', 0 + $getEdcResult->exis);
			$point_qris_exis = $this->merchant_model->calculate_point('QRIS', 'EXISTING', 0 + $getQrisResult->exis);
			$totalPointsExis = $point_edc_exis + $point_qris_exis;

			$data[] = array(
				++$no,
				$sales_code . ', ' . $sales_name . ' (' . $sales_position . ')',
				'<span title="Total New" class="badge bg-green">' . $actual_new . '</span>',
				'<span title="Total Point New" class="badge bg-green">' . $totalPointsNew . '</span>',
				'<span title="Total Existing" class="badge bg-black">' . $actual_existing . '</span>',
				'<span title="Total Point Existing" class="badge bg-black">' . $totalPointsExis . '</span>',
				'<span title="Total Rejected" class="badge bg-red">' . $actual_rejected . '</span>',
				'<a href="javascript:void(0);" onclick="view_spv(\'' . $sales_code . '\',\'' . $sales_position . '\',\'' . str_replace(' ','-',$sales_name) . '\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>
					<a href="javascript:void(0);" onclick="view_detail(\'' . $sales_code . '\',\'' . $sales_position . '\',\'' . str_replace(' ','-',$sales_name) . '\')" class="btn btn-info btn-xs"><i class="fa fa-cog"></i> Detail</a>'
			);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->merchant_model->count_filtered($where, $groups, $groupDate),
			"recordsFiltered" => $this->merchant_model->count_filtered($where, $groups, $groupDate),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	public function filter_data()
	{
		$group_date = $this->input->post('group_date');
		// $date_to = $this->input->post('date_to');
		// $range = $this->datediff($date_from,$date_to);
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		// if($range > 31)
		// {
		// 	$data['inputerror'][] = 'date_to';
		// 	$data['error_string'][] = 'Maaf, range tanggal maksimal 31 hari';
		// 	$data['status'] = FALSE;
		// }

		// if($data['status'] === FALSE)
		// {
		// 	echo json_encode($data);
		// 	exit();
		// }
		$session_data = array(
			'groupDate' => $this->input->post('group_date'),
			// 'date_to' => $this->input->post('date_to')
		);
		$this->session->set_userdata($session_data);
		echo json_encode(array("status" => TRUE));
	}

	function detailSPV($sales, $pos)
	{
		$this->session->set_userdata('sm_code', $sales);
		$this->session->set_userdata('sm_position', $pos);

		//load view
		$this->load->view('decision/merchant/detailSPV');
	}

	function detailActual($sales, $varr)
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');
		$groupDate 	= $this->session->userdata('groupDate');
		$var = $varr . '_Code';

		// $data['getEdcNew'] = $this->merchant_model->get_actualEDC($var, $sales, $groupDate, 'new', 'EDC');
		// $data['getEdcExis'] = $this->merchant_model->get_actualEDC($var, $sales, $groupDate, 'exis', 'EDC');

		// $data['getQrisNew'] = $this->merchant_model->get_actualQRIS($var, $sales, $groupDate, 'new', 'QRIS');
		// $data['getQrisExis'] = $this->merchant_model->get_actualQRIS($var, $sales, $groupDate, 'exis', 'QRIS');

		// $data['getEdcQrisNew'] = $this->merchant_model->get_actualEDC($var, $sales, $groupDate, 'new', 'EDC_QRIS');
		// $data['getEdcQrisExis'] = $this->merchant_model->get_actualEDC($var, $sales, $groupDate, 'exis', 'EDC_QRIS');

		// $data['getPKEdc'] = $this->merchant_model->get_point_edc($var, $sales, $groupDate, 'KREDIT', 'EDC');
		// $data['getPNKEdc'] = $this->merchant_model->get_point_edc($var, $sales, $groupDate, 'NON KREDIT', 'EDC');
		// $data['getPEEdc'] = $this->merchant_model->get_point_edc($var, $sales, $groupDate, 'Existing', 'EDC');

		// $data['getPNQris'] = $this->merchant_model->get_point_qris($var, $sales, $groupDate, 'New', 'QRIS');
		// $data['getPEQris'] = $this->merchant_model->get_point_qris($var, $sales, $groupDate, 'Existing', 'QRIS');

		// $data['getPKEdcQris'] = $this->merchant_model->get_point_edc($var, $sales, $groupDate, 'KREDIT', 'EDC_QRIS');
		// $data['getPNKEdcQris'] = $this->merchant_model->get_point_edc($var, $sales, $groupDate, 'NON KREDIT', 'EDC_QRIS');
		// $data['getPEEdcQris'] = $this->merchant_model->get_point_edc($var, $sales, $groupDate, 'Existing', 'EDC');

		$getEdcResult = $this->merchant_model->get_summary_detail($var, $sales, $groupDate, 'EDC');
		$getEdcResult2 = $this->merchant_model->get_summary_detail($var, $sales, $groupDate, 'EDC_QRIS');
		$getQrisResult = $this->merchant_model->get_summary_detail($var, $sales, $groupDate, 'QRIS');

		// EDC
		$actual_kredit = 0 + $getEdcResult->kredit;
		$actual_nonkredit = 0 + $getEdcResult->non_kredit;
		$data['totalPointKredit'] = $this->merchant_model->calculate_point('EDC', 'KREDIT', $actual_kredit);
		$data['totalPointNonKredit'] = $this->merchant_model->calculate_point('EDC', 'NON KREDIT', $actual_nonkredit);
		$data['totalPointsExisEDC'] = $this->merchant_model->calculate_point('EDC', 'EXISTING', 0 + $getEdcResult->exis);
		$data['allDataEDC'] = $getEdcResult;

		// EDC + QRIS
		$actual_kredit2 = 0 + $getEdcResult2->kredit;
		$actual_nonkredit2 = 0 + $getEdcResult2->non_kredit;
		$data['totalPointKredit2'] = $this->merchant_model->calculate_point('EDC', 'KREDIT', $actual_kredit2);
		$data['totalPointNonKredit2'] = $this->merchant_model->calculate_point('EDC', 'NON KREDIT', $actual_nonkredit2);
		$data['totalPointsExisEDC2'] = $this->merchant_model->calculate_point('EDC', 'EXISTING', 0 + $getEdcResult2->exis);
		$data['allDataEDC2'] = $getEdcResult2;

		//QRIS
		$data['totalPointsNewQRIS'] = $this->merchant_model->calculate_point('QRIS', 'NEW', 0 + $getQrisResult->ntb);
		$data['totalPointsExisQRIS'] = $this->merchant_model->calculate_point('QRIS', 'EXISTING', 0 + $getQrisResult->exis);
		$data['dataQrisNew'] = $this->merchant_model->get_summary_qris($var, $sales, $groupDate, 'NEW');
		$data['dataQrisExis'] = $this->merchant_model->get_summary_qris($var, $sales, $groupDate, 'EXISTING');
		$data['dataQrisReject'] = $this->merchant_model->get_summary_qris($var, $sales, $groupDate, 'REJECT');

		$data['sales_code'] = $var;
		$data['sales'] = $sales;

		$this->load->view('decision/merchant/detailActual', $data);
	}

	function detailActualLink($sales, $pos)
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sl_code');
		$groupDate 	= $this->session->userdata('groupDate');
		$var = $pos . '_Code';

		// $data['getEdcNew'] = $this->merchant_model->get_actualEDC($var, $sales, $groupDate, 'new', 'EDC');
		// $data['getEdcExis'] = $this->merchant_model->get_actualEDC($var, $sales, $groupDate, 'exis', 'EDC');

		// $data['getQrisNew'] = $this->merchant_model->get_actualQRIS($var, $sales, $groupDate, 'new', 'QRIS');
		// $data['getQrisExis'] = $this->merchant_model->get_actualQRIS($var, $sales, $groupDate, 'exis', 'QRIS');

		// $data['getEdcQrisNew'] = $this->merchant_model->get_actualEDC($var, $sales, $groupDate, 'new', 'EDC_QRIS');
		// $data['getEdcQrisExis'] = $this->merchant_model->get_actualEDC($var, $sales, $groupDate, 'exis', 'EDC_QRIS');

		// $data['getPKEdc'] = $this->merchant_model->get_point_edc($var, $sales, $groupDate, 'KREDIT', 'EDC');
		// $data['getPNKEdc'] = $this->merchant_model->get_point_edc($var, $sales, $groupDate, 'NON KREDIT', 'EDC');
		// $data['getPEEdc'] = $this->merchant_model->get_point_edc($var, $sales, $groupDate, 'Existing', 'EDC');

		// $data['getPNQris'] = $this->merchant_model->get_point_qris($var, $sales, $groupDate, 'New', 'QRIS');
		// $data['getPEQris'] = $this->merchant_model->get_point_qris($var, $sales, $groupDate, 'Existing', 'QRIS');

		// $data['getPKEdcQris'] = $this->merchant_model->get_point_edc($var, $sales, $groupDate, 'KREDIT', 'EDC_QRIS');
		// $data['getPNKEdcQris'] = $this->merchant_model->get_point_edc($var, $sales, $groupDate, 'NON KREDIT', 'EDC_QRIS');
		// $data['getPEEdcQris'] = $this->merchant_model->get_point_edc($var, $sales, $groupDate, 'Existing', 'EDC');
		$getEdcResult = $this->merchant_model->get_summary_detail($var, $sales, $groupDate, 'EDC');
		$getEdcResult2 = $this->merchant_model->get_summary_detail($var, $sales, $groupDate, 'EDC_QRIS');
		$getQrisResult = $this->merchant_model->get_summary_detail($var, $sales, $groupDate, 'QRIS');

		// EDC
		$actual_kredit = 0 + $getEdcResult->kredit;
		$actual_nonkredit = 0 + $getEdcResult->non_kredit;
		$data['totalPointKredit'] = $this->merchant_model->calculate_point('EDC', 'KREDIT', $actual_kredit);
		$data['totalPointNonKredit'] = $this->merchant_model->calculate_point('EDC', 'NON KREDIT', $actual_nonkredit);
		$data['totalPointsExisEDC'] = $this->merchant_model->calculate_point('EDC', 'EXISTING', 0 + $getEdcResult->exis);
		$data['allDataEDC'] = $getEdcResult;

		// EDC + QRIS
		$actual_kredit2 = 0 + $getEdcResult2->kredit;
		$actual_nonkredit2 = 0 + $getEdcResult2->non_kredit;
		$data['totalPointKredit2'] = $this->merchant_model->calculate_point('EDC', 'KREDIT', $actual_kredit2);
		$data['totalPointNonKredit2'] = $this->merchant_model->calculate_point('EDC', 'NON KREDIT', $actual_nonkredit2);
		$data['totalPointsExisEDC2'] = $this->merchant_model->calculate_point('EDC', 'EXISTING', 0 + $getEdcResult2->exis);
		$data['allDataEDC2'] = $getEdcResult2;

		// QRIS
		$data['totalPointsNewQRIS'] = $this->merchant_model->calculate_point('QRIS', 'NEW', 0 + $getQrisResult->ntb);
		$data['totalPointsExisQRIS'] = $this->merchant_model->calculate_point('QRIS', 'EXISTING', 0 + $getQrisResult->exis);
		$data['dataQrisNew'] = $this->merchant_model->get_summary_qris($var, $sales, $groupDate, 'NEW');
		$data['dataQrisExis'] = $this->merchant_model->get_summary_qris($var, $sales, $groupDate, 'EXISTING');
		$data['dataQrisReject'] = $this->merchant_model->get_summary_qris($var, $sales, $groupDate, 'REJECT');

		$data['sales_code'] = $var;
		$data['sales'] = $sales;

		$this->template->set('title', 'Detail Decision');
		$this->template->load('template', 'decision/merchant/detailActualLink', $data);
	}

	function get_data_spv()
	{
		$position 	= $this->session->userdata('position');
		$nik 		= $this->session->userdata('sm_code'); //  id data yg di klik
		$pos 		= $this->session->userdata('sm_position'); //  position data yg di klik
		$user 		= $this->session->userdata('sl_code'); // id yang sedang login
		$groupDate 	= $this->session->userdata('groupDate');

		$dsr_position = "('DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$asm_position = "('SPV', 'DSR','SPG','SPB','FO','Funding Officer','RO','Relationship Officer')";
		$array_structure = array('BSH', 'RSM', 'ASM', 'SPV');

		/*if($pos == 'BSH'){
				$where = "BSH_Code = '$nik'";
				$groups = "RSM_Code";
			}*/
		if ($pos == 'RSM') {
			$where = "RSM_Code = '$nik' AND BSH_Code = '$user'";
			$groups = "ASM_Code";
		} else if ($pos == 'ASM') {
			$where = "ASM_Code = '$nik' AND (RSM_Code = '$user' OR BSH_Code = '$user')";
			$groups = "SPV_Code";
		} else {
			$where = "SPV_Code = '$nik' AND (ASM_Code = '$user' OR RSM_Code = '$user' OR BSH_Code = '$user')";
			$groups = "Sales_Code";
		}

		$query = $this->merchant_model->get_datatables($where, $groups, $groupDate);
		$data = array();
		$no = $this->input->post('start');
		foreach ($query->result() as $row) {
			if ($pos == 'BSH') {
				$sales_code = $row->RSM_Code;
				$sales_name = $row->RSM_Name;
				$var = 'RSM_Code';
			} else if ($pos == 'RSM') {
				$sales_code = $row->ASM_Code;
				$sales_name = $row->ASM_Name;
				$var = 'ASM_Code';
			} else if ($pos == 'ASM') {
				$sales_code = $row->SPV_Code;
				$sales_name = $row->SPV_Name;
				$var = 'SPV_Code';
			} else {
				$sales_code = $row->Sales_Code;
				$sales_name = $row->Sales_Name;
				$var = 'Sales_Code';
			}

			$sales_position = substr($var, 0, -5);

			// $getEdcAccepted = $this->merchant_model->get_edc_result($var,$sales_code,$groupDate,'accepted');
			// $getEdcRejected = $this->merchant_model->get_edc_result($var,$sales_code,$groupDate,'rejected');

			// $getPointsNewEDC = $this->merchant_model->get_point_all_new($var,$sales_code,$groupDate,'EDC');
			// $getPointsNewQRIS = $this->merchant_model->get_point_all_new($var,$sales_code,$groupDate,'QRIS');
			// $getPointsNewEDCQRIS = $this->merchant_model->get_point_all_new($var,$sales_code,$groupDate,'EDC_QRIS');
			// $totalPointsNew = $getPointsNewEDC + $getPointsNewQRIS + $getPointsNewEDCQRIS;

			// $getPointsExisEDC = $this->merchant_model->get_point_all_exis($var,$sales_code,$groupDate,'EDC');
			// $getPointsExisQRIS = $this->merchant_model->get_point_all_exis($var,$sales_code,$groupDate,'QRIS');
			// $getPointsExisEDC_QRIS = $this->merchant_model->get_point_all_exis($var,$sales_code,$groupDate,'EDC_QRIS');
			// $totalPointsExis = $getPointsExisEDC + $getPointsExisQRIS + $getPointsExisEDC_QRIS;

			$getEdcResult = $this->merchant_model->get_summary($var, $sales_code, $groupDate, 'EDC');
			$getQrisResult = $this->merchant_model->get_summary($var, $sales_code, $groupDate, 'QRIS');

			$actual_new = 0 + $getEdcResult->ntb + $getQrisResult->ntb;
			$actual_existing = 0 + $getEdcResult->exis + $getQrisResult->exis;
			$actual_rejected = 0 + $getEdcResult->rejected + $getQrisResult->rejected;
			$actual_kredit = 0 + $getEdcResult->kredit;
			$actual_nonkredit = 0 + $getEdcResult->non_kredit;

			$point_kredit = $this->merchant_model->calculate_point('EDC', 'KREDIT', $actual_kredit);
			$point_nonkredit = $this->merchant_model->calculate_point('EDC', 'NON KREDIT', $actual_nonkredit);
			$point_qris_new = $this->merchant_model->calculate_point('QRIS', 'NEW', 0 + $getQrisResult->ntb);
			$totalPointsNew = $point_kredit + $point_nonkredit + $point_qris_new;

			$point_edc_exis = $this->merchant_model->calculate_point('EDC', 'EXISTING', 0 + $getEdcResult->exis);
			$point_qris_exis = $this->merchant_model->calculate_point('QRIS', 'EXISTING', 0 + $getQrisResult->exis);
			$totalPointsExis = $point_edc_exis + $point_qris_exis;

			if (in_array($sales_position, $array_structure)) {
				$buttons = '
					<a href="javascript:void(0);" onclick="view_spv(\'' . $sales_code . '\',\'' . $sales_position . '\',\'' . $sales_name . '\')" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> View</a>
					<a href="' . site_url('decision/merchant/detailActualLink/' . $sales_code . '/' . $sales_position . '/' . str_replace(' ','-',$sales_name)) . '" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-cog"></i> Detail</a>
					';
			} else {
				$buttons = '<a href="' . site_url('decision/merchant/detailActualLink/' . $sales_code . '/' . $sales_position . '/' . str_replace(' ','-',$sales_name)) . '" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-cog"></i> Detail</a>';
			}

			$data[] = array(
				++$no,
				$sales_code . ', ' . $sales_name . ' (' . $sales_position . ')',
				'<span title="Total New" class="badge bg-green">' . $actual_new . '</span>',
				'<span title="Total Point New" class="badge bg-green">' . $totalPointsNew . '</span>',
				'<span title="Total Existing" class="badge bg-black">' . $actual_existing . '</span>',
				'<span title="Total Point Existing" class="badge bg-black">' . $totalPointsExis . '</span>',
				'<span title="Total Rejected" class="badge bg-red">' . $actual_rejected . '</span>',
				$buttons
			);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->merchant_model->count_filtered($where, $groups, $groupDate),
			"recordsFiltered" => $this->merchant_model->count_filtered($where, $groups, $groupDate),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	function det_breakdown_merchant_leader($sales_code, $sales, $mid, $ft, $product, $poplink)
	{
		// $Sales_Code = $this->session->userdata('sl_code');
		// $posisi 	= $this->session->userdata('position');
		// $var_code 	= "";
		$fas = str_replace('-', ' ', $ft);
		$groupDate 	= $this->session->userdata('groupDate');
		$data['query'] 	= $this->merchant_model->detBreakdownMerchantLeader($sales_code, $sales, $mid, $fas, $product, $groupDate);

		//load view
		if ($poplink == 'popup') {
			$this->load->view('decision/merchant/det_breakdown_merchant', $data);
		} else {
			$this->template->set('title', 'Detail Actual Leader');
			$this->template->load('template', 'decision/merchant/detailActualLeader', $data);
		}
	}

	function det_breakdown_merchant_dsr($sales_code, $sales, $mid, $ft, $product)
	{
		// $Sales_Code = $this->session->userdata('sl_code');
		// $posisi 	= $this->session->userdata('position');
		// $var_code 	= "";
		$fas = str_replace('-', ' ', $ft);
		$groupDate 	= $this->session->userdata('groupDate');
		$data['query'] 	= $this->merchant_model->detBreakdownMerchantLeader($sales_code, $sales, $mid, $fas, $product, $groupDate);

		//load view
		$this->load->view('decision/merchant/det_breakdown_merchant_dsr', $data);
	}

	function style_col()
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
					'argb' => 'FF0e456d',
				],
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

	function export_qris()
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
		$sheet->setCellValue('N1', "Week");
		$sheet->setCellValue('O1', "Kode Officer");
		$sheet->setCellValue('P1', "Akuisisi Type");

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
		$sheet->getStyle('O1')->applyFromArray($style_col2);
		$sheet->getStyle('p1')->applyFromArray($style_col2);
		//ambil data
		$groupDate 	= $this->session->userdata('groupDate');

		$query = $this->merchant_model->qris_export($groupDate);

		//validasi jumlah data
		if ($query->num_rows() == 0) { ?>
			<script type="text/javascript" language="javascript">
				alert("No data...!!!");
			</script>
		<?php
			echo "<meta http-equiv='refresh' content='0; url=" . site_url() . "decision/merchant'>";

			return false;
		} else {
			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			$numrow = 2; // Set baris pertama untuk isi tabel adalah baris ke 4
			foreach ($query->result() as $data) { // Lakukan looping pada variabel sn
				$maskingname = $this->maskingname($data->Merchant_Name);


				$sheet->setCellValue('A' . $numrow,  $data->Sales_Code);
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
				$sheet->setCellValue('M' . $numrow, $data->Facilities_Type);
				$sheet->setCellValue('N' . $numrow, $data->Week);
				$sheet->setCellValue('O' . $numrow, $data->Facilities_Type2);
				$sheet->setCellValue('p' . $numrow, $data->AKUISISI_Type);

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
			$sheet->getColumnDimension('N')->setWidth(20);
			$sheet->getColumnDimension('O')->setWidth(20);
			$sheet->getColumnDimension('P')->setWidth(30);



			// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
			$sheet->getDefaultRowDimension()->setRowHeight(-1);
			// Set orientasi kertas jadi LANDSCAPE
			$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
			// Set judul file excel nya
			$sheet->setTitle("Laporan");
			// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header("Content-Disposition: attachment; filename=Detail Achievement QRIS.xlsx");
			header('Cache-Control: max-age=0');
			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		}
	}
	function export_edc()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$style_col = $this->style_col();
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
		$sheet->setCellValue('N1', "Week");
		$sheet->setCellValue('O1', "Group Fasilitas");
		$sheet->setCellValue('P1', "Akuisisi Type");

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
		//ambil data
		$groupDate 	= $this->session->userdata('groupDate');

		$query = $this->merchant_model->edc_export($groupDate);

		//validasi jumlah data
		if ($query->num_rows() == 0) { ?>
			<script type="text/javascript" language="javascript">
				alert("No data...!!!");
			</script>
<?php
			echo "<meta http-equiv='refresh' content='0; url=" . site_url() . "decision/merchant'>";

			return false;
		} else {
			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			$numrow = 2; // Set baris pertama untuk isi tabel adalah baris ke 4
			foreach ($query->result() as $data) { // Lakukan looping pada variabel sn
				$maskingname = $this->maskingname($data->Merchant_Name);


				$sheet->setCellValue('A' . $numrow,  $data->Sales_Code);
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
				$sheet->setCellValue('M' . $numrow, $data->Facilities_Type);
				$sheet->setCellValue('N' . $numrow, $data->Week);
				$sheet->setCellValue('O' . $numrow, $data->Facilities_Type2);
				$sheet->setCellValue('P' . $numrow, $data->AKUISISI_Type);

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
			$sheet->getColumnDimension('N')->setWidth(20);
			$sheet->getColumnDimension('O')->setWidth(20);
			$sheet->getColumnDimension('O')->setWidth(30);



			// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
			$sheet->getDefaultRowDimension()->setRowHeight(-1);
			// Set orientasi kertas jadi LANDSCAPE
			$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
			// Set judul file excel nya
			$sheet->setTitle("Laporan");
			// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header("Content-Disposition: attachment; filename=Detail Achievement EDC.xlsx");
			header('Cache-Control: max-age=0');
			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		}
	}

	// END LEADER PAGE

	// DSR PAGE
	// function det_breakdown_merchant($status, $part)
	// {
	// 	$Sales_Code = $this->session->userdata('sl_code');
	// 	$posisi = $this->session->userdata('position');
	// 	$var_code="";
	// 	$tgl1 = date('Y-m-01');
	// 	$tgl2 = date('Y-m-d');

	// 	$data['part'] = $part;
	// 	$data['query'] = $this->merchant_model->m_det_breakdown_merchant($Sales_Code, $tgl1, $tgl2, $status, $part);

	// 	//load view
	// 	$this->load->view('incoming/merchant/det_breakdown_merchant', $data);
	// }

	// function filter_incoming($date_from, $date_to)
	// {
	// 	$username = $this->session->userdata('sl_code'); // DSR_CODE
	// 	$position = $this->session->userdata('position');

	// 	if($position == 'BSH'){
	// 		$data['position'] = "RSM";
	// 		$data['detail'] = "ASM";
	// 		$var = "t2.RSM_Code";
	// 	}
	// 	else if($position == 'RSM'){
	// 		$data['position'] = "ASM";
	// 		$data['detail'] = "SPV";
	// 		$var = "t2.ASM_Code";
	// 	}
	// 	else if($position == 'ASM'){
	// 		$data['position'] = "SPV";
	// 		$data['detail'] = "DSR";
	// 		$var = "t2.SPV_Code";
	// 	}
	// 	else if($position == 'SPV'){
	// 		$data['position'] = "DSR";
	// 		$data['detail'] = "DSR";
	// 		$var = "t1.sales_code";
	// 	}
	// 	else{
	// 		$data['position'] = "DSR";
	// 	}

	// 	$data['getDataIS']   = $this->merchant_model->getDataInput($username, $date_from, $date_to, 'IS'); // IS = Input => input System
	// 	$data['getDataBS']   = $this->merchant_model->getDataInput($username, $date_from, $date_to, 'BS'); // BS = Input => bukan System
	// 	// query data processing
	// 	$data['getDataPR']   = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PR'); // PR = Processing => Process Received
	// 	$data['getDataPI']   = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PI'); // PI = Processing => Process Inprocess
	// 	$data['getDataPRTS'] = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PRTS'); // PRTS = Processing => Processs RTS
	// 	$data['getDataPS']   = $this->merchant_model->getDataProcessing($username, $date_from, $date_to, 'PS'); // PS = Processing => Process Send
	// 	// query total
	// 	$data['getTotalsReceived']   = $this->merchant_model->getTotalsProcessing($username, $date_from, $date_to, 'received');
	// 	$data['getTotalsInprocess']  = $this->merchant_model->getTotalsProcessing($username, $date_from, $date_to, 'inprocess');
	// 	$data['getTotalsRTS']  		 = $this->merchant_model->getTotalsProcessing($username, $date_from, $date_to, 'rts');
	// 	$data['getTotalsSend']  	 = $this->merchant_model->getTotalsProcessing($username, $date_from, $date_to, 'send');

	// 	// Tanggal filter
	// 	$data['date_from'] = $date_from;
	// 	$data['date_to'] = $date_to;

	// 	$this->template->set('title','Summary Merchant');
	// 	$this->template->load('template','incoming/merchant/index_filter',$data);
	// }
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
	
	//masking name	
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
