<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Check_limit extends REST_Controller {

    private $model = 'check_limit_model';

	function __construct() {
        parent::__construct();

        $this->load->model($this->model);
		error_reporting(0);
    }

	// get ASM
	function limit_event_get()
	{
		$sales_code = $this->get('sales_code');
		$product = $this->get('product');
		$date_now = $this->get('date_now');

		if(!empty($sales_code)){
			$data = $this->check_limit_model->check_limit_data_event($sales_code, $product, $date_now);

			if ($data) {
				$this->response([
					'status' => TRUE,
					'message' => 'Get data successful.',
					'data' => $data->row()
				], REST_Controller::HTTP_OK);
			}else{
				$this->response([
					'status' => FALSE,
					'message' => 'Get data fail.',
					'data' => 'fail'
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Provide get data.',
				'data' => 'provide'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	// get RSM
	function limit_get()
	{
		$product = $this->get('product');

		if(!empty($product)){
			$data = $this->customer_model->check_limit($product);

			if ($data) {
				$this->response([
					'status' => TRUE,
					'message' => 'Get data successful.',
					'data' => $data->row()
				], REST_Controller::HTTP_OK);
			}else{
				$this->response([
					'status' => FALSE,
					'message' => 'Get data fail.',
					'data' => 'fail'
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Provide get data.',
				'data' => 'provide'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

}