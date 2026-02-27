<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Cnc extends REST_Controller
{

    private $model = 'cnc_model';

    public function __construct()
    {
        parent::__construct();

        $this->load->model($this->model);
        $this->load->helper('cek');
        date_default_timezone_set('Asia/Jakarta');
        error_reporting(0);
    }

    public function add_post()
    {
		//INSERT DATA
		$data = array(
			'Sales_Code' => $this->post('Sales_Code'),
			'Sales_Name' => $this->post('Sales_Name'),
			'Position' => $this->post('Position'),
			'Product' => $this->post('Product'),
			'Mob' => $this->post('Mob'),
			'Status' => $this->post('Status'),
			'SPV_Name' => $this->post('SPV_Name'),
			'ASM_Name' => $this->post('ASM_Name'),
			'RSM_Name' => $this->post('RSM_Name'),
			'BSH_Name' => $this->post('BSH_Name'),
			'Branch' => $this->post('Branch'),
			'Period' => $this->post('Period'),
			'Tgl_01' => $this->post('Tgl_01'),
			'Tgl_02' => $this->post('Tgl_02'),
			'Tgl_03' => $this->post('Tgl_03'),
			'Tgl_04' => $this->post('Tgl_04'),
			'Tgl_05' => $this->post('Tgl_05'),
			'Tgl_06' => $this->post('Tgl_06'),
			'Tgl_07' => $this->post('Tgl_07'),
			'Tgl_08' => $this->post('Tgl_08'),
			'Tgl_09' => $this->post('Tgl_09'),
			'Tgl_10' => $this->post('Tgl_10'),
			'Tgl_11' => $this->post('Tgl_11'),
			'Tgl_12' => $this->post('Tgl_12'),
			'Tgl_13' => $this->post('Tgl_13'),
			'Tgl_14' => $this->post('Tgl_14'),
			'Tgl_15' => $this->post('Tgl_15'),
			'Tgl_16' => $this->post('Tgl_16'),
			'Tgl_17' => $this->post('Tgl_17'),
			'Tgl_18' => $this->post('Tgl_18'),
			'Tgl_19' => $this->post('Tgl_19'),
			'Tgl_20' => $this->post('Tgl_20'),
			'Tgl_21' => $this->post('Tgl_21'),
			'Tgl_22' => $this->post('Tgl_22'),
			'Tgl_23' => $this->post('Tgl_23'),
			'Tgl_24' => $this->post('Tgl_24'),
			'Tgl_25' => $this->post('Tgl_25'),
			'Tgl_26' => $this->post('Tgl_26'),
			'Tgl_27' => $this->post('Tgl_27'),
			'Tgl_28' => $this->post('Tgl_28'),
			'Tgl_29' => $this->post('Tgl_29'),
			'Tgl_30' => $this->post('Tgl_30'),
			'Tgl_31' => $this->post('Tgl_31'),
			'Created_By' => $this->post('Created_By'),
		);

		$this->{$this->model}->config('internal.applications', 'RegnoId');
		$insert = $this->{$this->model}->insert($data);
		$id = $this->db->insert_id();

		if ($insert) {
			$this->response([
				'status' => true,
				'message' => 'Data berhasil diinput.',
				'RegnoId' => $id,
				'post' => $data,
			], REST_Controller::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Data gagal diinput.',
				'post' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
    }
}
