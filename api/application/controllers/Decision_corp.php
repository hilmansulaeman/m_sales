<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Decision_corp extends REST_Controller {

    private $model = 'Decision_corp_model';

	function __construct() {
        parent::__construct();

        $this->load->model($this->model);
		$this->{$this->model}->config('`internal`.`corporate_ro_result`','id');
		error_reporting(0);
    }

	// get datatable query
	function getDataTable_post()
	{
		$where		= $this->input->post('where');
		$groups		= $this->input->post('groups');
		$groupDate	= $this->input->post('groupDate');

		$getDataId = $this->Decision_corp_model->get_dataTable($where, $groups, $groupDate);

		if ($getDataId) {
			$this->response([
				'status' => TRUE,
				'message' => 'Get data successful.',
				'data' => $getDataId->result()
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
	function countDataTable_post()
	{
		$where		= $this->input->post('where');
		$groups		= $this->input->post('groups');
		$groupDate	= $this->input->post('groupDate');

		$getDataId = $this->Decision_corp_model->count_dataTable($where, $groups, $groupDate);

		if ($getDataId) {
			$this->response([
				'status' => TRUE,
				'message' => 'Get data successful.',
				'data' => $getDataId
			], REST_Controller::HTTP_OK);
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Get data fail.',
				'data' => 0
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	
	function breakdownCorp_get()
	{
		$sales_code		= $this->get('sales_code');
		$groupDate		= $this->get('groupDate');
		$upVar 			= $this->get('upVar');
		$upSales_code	= $this->get('upSales_code');

		$getDataId = $this->Decision_corp_model->breakdown_corp($sales_code,$groupDate,$upVar,$upSales_code);

		if ($getDataId) {
			$this->response([
				'status' => TRUE,
				'message' => 'Get data successful.',
				'data' => $getDataId->row()
			], REST_Controller::HTTP_OK);
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Get data fail.',
				'data' => 'fail'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}