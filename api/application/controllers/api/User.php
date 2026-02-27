<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller {

    private $model = 'user_model';

	function __construct() {
        parent::__construct();

        $this->load->model($this->model);
		// $this->{$this->model}->config('user_employee','Employee_ID');
		$this->{$this->model}->config('data_pemol','RegnoId');
		error_reporting(0);
    }

	function index_post()
	{
		$data = array(
			'Account_Number' => $this->post('Account_Number'),
			'Sales_Code' 	 => $this->post('Sales_Code'),
			'Sales_Name' 	 => $this->post('Sales_Name'),
			'Branch' 		 => $this->post('Branch'),
			'Input_Date' 	 => $this->post('Input_Date')
		);
		
		$insert = $this->db->insert('data_pemol', $data);
		if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
		// if ($insert) {
		// 	$this->response([
		// 		'status' => TRUE,
		// 		'message' => 'Data Added Successful.'
		// 	], REST_Controller::HTTP_OK);
		// }else{
		// 	$this->response([
		// 		'status' => FALSE,
		// 		'message' => 'Data Cant Added.'
		// 	], REST_Controller::HTTP_BAD_REQUEST);
		// }
	}
	
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