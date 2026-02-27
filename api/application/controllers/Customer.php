<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Customer extends REST_Controller {

    private $model = 'customer_model';

	function __construct() {
        parent::__construct();

        $this->load->model($this->model);
		$this->{$this->model}->config('data_pemol','RegnoId');
		error_reporting(0);
    }

	// get ASM
	function dataByASM_get()
	{
		$sm_code = $this->get('sm_code');

		if(!empty($sm_code)){
			$getDataId = $this->customer_model->getByASM($sm_code);

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
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Provide get data.',
				'data' => 'provide'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	// get RSM
	function dataByRSM_get()
	{
		$sm_code = $this->get('sm_code');

		if(!empty($sm_code)){
			$getDataId = $this->customer_model->getByRSM($sm_code);

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
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Provide get data.',
				'data' => 'provide'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	// get Branch
	function dataBranch_get()
	{
		$getDataId = $this->customer_model->getBranch();

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

	// get datatable query
	function getDataTable_post()
	{
		$sales_code = $this->input->post('sales_code');
		$where		= $this->input->post('wheres');
		$position	= $this->input->post('position');

		if(!empty($sales_code)){
			$getDataId = $this->customer_model->get_datapemol($sales_code, $where, $position);

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
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Provide get data.',
				'data' => 'provide'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	// count datatable query
	function countDataTable_post()
	{
		$sales_code = $this->input->post('sales_code');
		$where		= $this->input->post('wheres');
		$position	= $this->input->post('position');

		if(!empty($sales_code)){
			$getDataId = $this->customer_model->count_datapemol($sales_code, $where, $position);

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
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Provide get data.',
				'data' => 'provide'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	// insert query
	function add_post()
	{
		$data = array(
			'Account_Number' => $this->post('Account_Number'),
			'Sales_Code'	 => $this->post('Sales_Code'),
			'Sales_Name'	 => $this->post('Sales_Name'),
			'Branch'		 => $this->post('Branch'),
			'Input_Date'	 => $this->post('Input_Date')
		);

		$insert = $this->db->insert('data_pemol', $data);
		$id = $this->db->insert_id();

		if($insert){
			$this->response([
				'status' => TRUE,
				'message' => 'Added data successful.',
				'RegnoId' => $id
			], REST_Controller::HTTP_OK);
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Added data fail.'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	// get where
	function dataWhere_get()
	{
		$id 		= $this->get('id');
		$reference  = $this->get('reference');

		if(!empty($id)){
			$getDataId = $this->customer_model->getId($id, $reference);

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
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Provide get data.',
				'data' => 'provide'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	// edit query
	function edit_post()
	{
		$id = $this->post('RegnoId');

		$data = array(
			'Account_Number' => $this->post('Account_Number'),
			'Modified_Date'	 => $this->post('Modified_Date'),
			'Modified_By'	 => $this->post('Modified_By')
		);

		$edit = $this->db->update('data_pemol', $data, array('RegnoId' => $id));

		if($edit){
			$this->response([
				'status' => TRUE,
				'message' => 'Update data successful.'
			], REST_Controller::HTTP_OK);
		}else{
			$this->response([
				'status' => FALSE,
				'message' => 'Update data fail.'
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	// =========================================================
	
	// Login admin with password
	// public function login_admin_get() {
    //     // Get the post data
    //     $username = $this->get('username');
    //     $password = $this->get('password');
        
    //     // Validate the post data
    //     if(!empty($username) && !empty($password)){
            
    //         // Check if any user exists with the given credentials
    //         $user = $this->{$this->model}->do_login_admin($username,$password);
            
    //         if($user){
    //             // Set the response and exit
    //             $this->response([
    //                 'status' => TRUE,
    //                 'message' => 'User login successful.',
    //                 'data' => $user->row()
    //             ], REST_Controller::HTTP_OK);
    //         }else{
    //             // Set the response and exit
    //             //BAD_REQUEST (400) being the HTTP response code
    //             $this->response([
	// 			    'status' => FALSE,
	// 				'message' => 'Wrong username or password.'
	// 			], REST_Controller::HTTP_BAD_REQUEST);
    //         }
    //     }else{
    //         // Set the response and exit
	// 		$this->response([
	// 			'status' => FALSE,
	// 			'message' => 'Provide username and password.'
	// 		], REST_Controller::HTTP_BAD_REQUEST);
    //     }
    // }
	
	// Login sales with dob
	// public function login_sales_get() {
    //     // Get the post data
    //     $username = $this->get('username');
    //     $password = $this->get('password');
        
    //     // Validate the post data
    //     if(!empty($username) && !empty($password)){
            
    //         // Check if any user exists with the given credentials
    //         $user = $this->{$this->model}->do_login_sales($username,$password);
            
    //         if($user){
    //             // Set the response and exit
    //             $this->response([
    //                 'status' => TRUE,
    //                 'message' => 'User login successful.',
    //                 'data' => $user->row()
    //             ], REST_Controller::HTTP_OK);
    //         }else{
    //             // Set the response and exit
    //             //BAD_REQUEST (400) being the HTTP response code
    //             $this->response([
	// 			    'status' => FALSE,
	// 				'message' => 'Wrong username or password.'
	// 			], REST_Controller::HTTP_BAD_REQUEST);
    //         }
    //     }else{
    //         // Set the response and exit
	// 		$this->response([
	// 			'status' => FALSE,
	// 			'message' => 'Provide username and password.'
	// 		], REST_Controller::HTTP_BAD_REQUEST);
    //     }
    // }
	
	// Login sales with password
	// public function login_saless_get() {
    //     // Get the post data
    //     $username = $this->get('username');
    //     $password = $this->get('password');
        
    //     // Validate the post data
    //     if(!empty($username) && !empty($password)){
            
    //         // Check if any user exists with the given credentials
    //         $user = $this->{$this->model}->do_login_saless($username,$password);
            
    //         if($user){
    //             // Set the response and exit
    //             $this->response([
    //                 'status' => TRUE,
    //                 'message' => 'User login successful.',
    //                 'data' => $user->row()
    //             ], REST_Controller::HTTP_OK);
    //         }else{
    //             // Set the response and exit
    //             //BAD_REQUEST (400) being the HTTP response code
    //             $this->response([
	// 			    'status' => FALSE,
	// 				'message' => 'Wrong username or password.'
	// 			], REST_Controller::HTTP_BAD_REQUEST);
    //         }
    //     }else{
    //         // Set the response and exit
	// 		$this->response([
	// 			'status' => FALSE,
	// 			'message' => 'Please provide username and password.'
	// 		], REST_Controller::HTTP_BAD_REQUEST);
    //     }
    // }
	
	// function change_password_post()
	// {
	//     $id = $this->post('id');
	//     $password = $this->post('password');
	// 	$password_conf = $this->post('password_conf');
	// 	if(!empty($password) && !empty($password_conf)){
	// 		if($password == $password_conf)
	// 		{
	// 			$data_request = array(
	// 				'Password'	=>md5($this->post('password')),
	// 				'Password_Change'	=>1
	// 			);
	// 			$update = $this->{$this->model}->update($data_request, $id);
				
	// 			// Set the response and exit
	// 			$this->response([
	// 				'status' => TRUE,
	// 				'message' => 'Password berhasil diganti.',
	// 				'data' => $data_request
	// 			], REST_Controller::HTTP_OK);
	// 		}
	// 		else{
	// 			// Set the response and exit
	// 			//BAD_REQUEST (400) being the HTTP response code
	// 			$this->response([
	// 				'status' => FALSE,
	// 				'message' => 'Password tidak sama!'
	// 			], REST_Controller::HTTP_BAD_REQUEST);
	// 		}
	// 	}
	// 	else{
	// 	    // Set the response and exit
	// 		$this->response([
	// 			'status' => FALSE,
	// 			'message' => 'Password baru tidak boleh kosong!'
	// 		], REST_Controller::HTTP_BAD_REQUEST);
	// 	}
	// }
	
	// function reset_password_post($id,$password)
	// {
	// 	if(!empty($password)){
	// 		$data_request = array(
	// 			'Password'	=>md5($password),
	// 			'Password_Change'	=>0
	// 		);
	// 		$update = $this->{$this->model}->update($data_request, $id);
			
	// 		// Set the response and exit
	// 		$this->response([
	// 			'status' => TRUE,
	// 			'message' => 'Reset password berhasil.',
	// 			'data' => $data_request
	// 		], REST_Controller::HTTP_OK);
	// 	}
	// 	else{
	// 	    // Set the response and exit
	// 		$this->response([
	// 			'status' => FALSE,
	// 			'message' => 'Reset Password gagal.'
	// 		], REST_Controller::HTTP_BAD_REQUEST);
	// 	}
	// }
}