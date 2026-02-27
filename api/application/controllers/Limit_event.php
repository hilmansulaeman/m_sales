<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Limit_event extends REST_Controller
{

    private $model = 'limit_event_model';

    function __construct()
    {
        parent::__construct();

        $this->load->model($this->model);
        $this->{$this->model}->config('internal.data_sales', 'Number_Id');
        error_reporting(0);
    }

    // get data internal.data_employee
    function dataSales_get()
    {
        $getData = $this->limit_event_model->getSales();

        if ($getData) {
            $this->response([
                'status' => TRUE,
                'message' => 'Get data successful.',
                'total' => $getData->num_rows(),
                'data' => $getData->result()
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Get data fail.',
                'data' => 'fail'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
