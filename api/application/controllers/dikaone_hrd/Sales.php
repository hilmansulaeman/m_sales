<?php defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
require APPPATH . '/libraries/REST_Controller.php';

class Sales extends REST_Controller {

	function __construct() {
        parent::__construct();

        $this->load->model('dikaone_hrd/sales_model', 'model');
		error_reporting(0);
    }

	// =============================================================================================================================
	// POST
		function updateDataRequestUser_post()
		{
			$request_user_id  = $this->post('request_user_id');
			$hrd_note         = $this->post('hrd_note');
			
			$dataArr1 = array(
				'HRD_Checker'      => $this->post('hrd_checker'),
				'HRD_Checker_Name' => $this->post('hrd_checker_name'),
				'Status_Date'      => $this->post('status_date'),
				'Hit_Code'         => $this->post('hit_code'),
			);

			if ($hrd_note != '0') {
				$dataArr2 = array(
					'HRD_Note' => $hrd_note
				);

				$data_update_request = array_merge($dataArr1, $dataArr2);
			}else{
				$data_update_request = $dataArr1;
			}

			$query = $this->model->updateDataRequestUser($request_user_id, $data_update_request);

			if ($query) {
				$this->response([
					'status' => TRUE,
					'message' => 'Update data successful.',
					'data' => 'Success'
				], REST_Controller::HTTP_OK);
			}else{
				$this->response([
					'status' => FALSE,
					'message' => 'Update data fail.',
					'data' => 'Fail'
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		}

		function insertDataProcessLog_post()
		{
			$dataLog = array(
				'Request_ID'      => $this->post('request_id'),
				'Request_User_ID' => $this->post('request_user_id'),
				'Hit_Code'        => $this->post('hit_code'),
				'Description'     => $this->post('description'),
				'Updated_By'      => $this->post('updated_by'),
				'Updated_Date'    => $this->post('updated_date')
			);

			$query = $this->model->insertDataProcessLog($dataLog);

			if ($query) {
				$this->response([
					'status' => TRUE,
					'message' => 'Insert data successful.',
					'data' => $dataLog
				], REST_Controller::HTTP_OK);
			}else{
				$this->response([
					'status' => FALSE,
					'message' => 'Insert data fail.',
					'data' => 'fail'
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		}

	// =============================================================================================================================
	// GET
		public function getDataRequestUser_get()
		{
			$request_user_id = $this->get('request_user_id');

			$query = $this->model->getDataRequestUser($request_user_id);

			if ($query) {
				$this->response([
					'status' => TRUE,
					'message' => 'Get data successful.',
					'data' => $query->row()
				], REST_Controller::HTTP_OK);
			}else{
				$this->response([
					'status' => FALSE,
					'message' => 'Get data fail.',
					'data' => 'fail'
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		}



		
	// count datatable query
	// function countDataTable_post()
	// {
	// 	$where		= $this->input->post('where');

	// 	$query = $this->model->count_dataTable($where);

	// 	if ($query) {
	// 		$this->response([
	// 			'status' => TRUE,
	// 			'message' => 'Get data successful.',
	// 			'data' => $query
	// 		], REST_Controller::HTTP_OK);
	// 	}else{
	// 		$this->response([
	// 			'status' => FALSE,
	// 			'message' => 'Get data fail.',
	// 			'data' => 0
	// 		], REST_Controller::HTTP_BAD_REQUEST);
	// 	}
	// }

	// ===============================================================================================================================================================
	// LEADER PAGE

	// function getDataInputLeader_get()
	// {
	// 	$lead_code	= $this->get('lead_code');
	// 	$sales		= $this->get('sales');
	// 	$from		= $this->get('from');
	// 	$to			= $this->get('to');
	// 	$type	    = $this->get('type');

	// 	$query = $this->model->get_dataInputLeader($lead_code,$sales,$from,$to,$type);

	// 	if ($query) {
	// 		$this->response([
	// 			'status' => TRUE,
	// 			'message' => 'Get data successful.',
	// 			'data' => $query->row()
	// 		], REST_Controller::HTTP_OK);
	// 	}else{
	// 		$this->response([
	// 			'status' => FALSE,
	// 			'message' => 'Get data fail.',
	// 			'data' => 'fail'
	// 		], REST_Controller::HTTP_BAD_REQUEST);
	// 	}
	// }

	// function getDataInputDummy_get()
	// {
	// 	$nik		= $this->get('nik');
	// 	$from		= $this->get('from');
	// 	$to			= $this->get('to');
	// 	$position	= $this->get('position');
	// 	$type		= $this->get('type');

	// 	$query = $this->model->get_dataInputDummy($nik,$from,$to,$position,$type);

	// 	if ($query) {
	// 		$this->response([
	// 			'status' => TRUE,
	// 			'message' => 'Get data successful.',
	// 			'data' => $query->row()
	// 		], REST_Controller::HTTP_OK);
	// 	}else{
	// 		$this->response([
	// 			'status' => FALSE,
	// 			'message' => 'Get data fail.',
	// 			'data' => 'fail'
	// 		], REST_Controller::HTTP_BAD_REQUEST);
	// 	}
	// }

	// function detBreakdownInputLeader_get()
	// {
	// 	$sales_code	= $this->get('sales_code');
	// 	$sales		= $this->get('sales');
	// 	$status		= $this->get('status');
	// 	$type		= $this->get('type');
	// 	$from		= $this->get('from');
	// 	$to			= $this->get('to');

	// 	$query = $this->model->get_detBreakdownInputLeader($sales_code, $sales, $status, $type, $from, $to);

	// 	if ($query) {
	// 		$this->response([
	// 			'status' => TRUE,
	// 			'message' => 'Get data successful.',
	// 			'data' => $query->result()
	// 		], REST_Controller::HTTP_OK);
	// 	}else{
	// 		$this->response([
	// 			'status' => FALSE,
	// 			'message' => 'Get data fail.',
	// 			'data' => 'fail'
	// 		], REST_Controller::HTTP_BAD_REQUEST);
	// 	}
	// }
	// ===============================================================================================================================================================

	// ===============================================================================================================================================================
	// DSR PAGE

	// function getDataInput_get()
	// {
	// 	$sales	= $this->get('sales');
	// 	$from	= $this->get('from');
	// 	$to		= $this->get('to');
	// 	$type   = $this->get('type');

	// 	$query = $this->model->get_dataInput($sales,$from,$to,$type);

	// 	if ($query) {
	// 		$this->response([
	// 			'status' => TRUE,
	// 			'message' => 'Get data successful.',
	// 			'data' => $query->row()
	// 		], REST_Controller::HTTP_OK);
	// 	}else{
	// 		$this->response([
	// 			'status' => FALSE,
	// 			'message' => 'Get data fail.',
	// 			'data' => 'fail'
	// 		], REST_Controller::HTTP_BAD_REQUEST);
	// 	}
	// }

	// function detBreakdownInputDSR_get()
	// {
	// 	$sales	 = $this->get('sales');
	// 	$status  = $this->get('status');
	// 	$type    = $this->get('type');
	// 	$from	 = $this->get('from');
	// 	$to		 = $this->get('to');

	// 	$query = $this->model->get_detBreakdownInputDSR($sales, $status, $type, $from, $to);

	// 	if ($query) {
	// 		$this->response([
	// 			'status' => TRUE,
	// 			'message' => 'Get data successful.',
	// 			'data' => $query->result()
	// 		], REST_Controller::HTTP_OK);
	// 	}else{
	// 		$this->response([
	// 			'status' => FALSE,
	// 			'message' => 'Get data fail.',
	// 			'data' => 'fail'
	// 		], REST_Controller::HTTP_BAD_REQUEST);
	// 	}
	// }
}