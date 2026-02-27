<?php defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
require APPPATH . '/libraries/REST_Controller.php';

class Check_decision extends REST_Controller {

    private $model = 'check_decision_model';

	function __construct() {
        parent::__construct();

        $this->load->model($this->model);
		error_reporting(0);
    }

	
	// Merchant
	function merchant_get()
	{
		$sales_code	= $this->get('sales_code');
		$keyword    = $this->get('key');
		$query = $this->{$this->model}->get_decision_merchant($keyword,$sales_code);

		if ($query) {
			$this->response([
				'status' => TRUE,
				'message' => 'Get data successful.',
				'data' => $query->result()
			], REST_Controller::HTTP_OK);
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Get data fail.',
				'data' => 'fail'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}
	
	// CC
	function cc_get()
	{
		$sales_code	= $this->get('sales_code');
		$keyword    = $this->get('key');
		$query = $this->{$this->model}->get_decision_cc($keyword,$sales_code);

		if ($query) {
			$this->response([
				'status' => TRUE,
				'message' => 'Get data successful.',
				'data' => $query->result()
			], REST_Controller::HTTP_OK);
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Get data fail.',
				'data' => 'fail'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}
	
	// Corporate
	function corporate_get()
	{
		$sales_code	= $this->get('sales_code');
		$keyword    = $this->get('key');
		$query = $this->{$this->model}->get_decision_corporate($keyword,$sales_code);

		if ($query) {
			$this->response([
				'status' => TRUE,
				'message' => 'Get data successful.',
				'data' => $query->result()
			], REST_Controller::HTTP_OK);
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Get data fail.',
				'data' => 'fail'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}
	
	// SC
	function sc_get()
	{
		$sales_code	= $this->get('sales_code');
		$keyword    = $this->get('key');
		$query = $this->{$this->model}->get_decision_sc($keyword,$sales_code);

		if ($query) {
			$this->response([
				'status' => TRUE,
				'message' => 'Get data successful.',
				'data' => $query->result()
			], REST_Controller::HTTP_OK);
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Get data fail.',
				'data' => 'fail'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}
	
	// PL
	function pl_get()
	{
		$sales_code	= $this->get('sales_code');
		$keyword    = $this->get('key');
		$query = $this->{$this->model}->get_decision_pl($keyword,$sales_code);

		if ($query) {
			$this->response([
				'status' => TRUE,
				'message' => 'Get data successful.',
				'data' => $query->result()
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