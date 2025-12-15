<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_model extends MY_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	// user detail
	function user_detail($username)
	{
		// API URL
		$url = 'https://rest-api.ptdika.com/api/user/user_detail?sales_code='.$username;
		
		$data = $this->get_api_user($url);
		
		$responStatus = $data->status;
		$rows = $responStatus == '1' ? $data->data : '';
		return array('status'=>$responStatus,'data'=>$rows);
	}
	
	// cek email
	function check_email($email)
	{
		// API URL
		$url = 'https://rest-api.ptdika.com/api/user/user_email?email='.$email;
		
		$data = $this->get_api_user($url);
		$responStatus = $data->status;
		$rows = $responStatus == '1' ? $data->data : '';
		
		return array('status'=>$responStatus,'data'=>$rows);
	}
	
	// cek token
	function check_token($token)
	{
		// API URL
		$url = 'https://rest-api.ptdika.com/api/user/user_token?token='.$token;
		
		$data = $this->get_api_user($url);
		$responStatus = $data->status;
		$rows = $responStatus == '1' ? $data->data : '';
		
		return array('status'=>$responStatus,'data'=>$rows);
	}
	
	// reset token
	function reset_token($token,$email)
	{
		// API URL
		$url = 'https://rest-api.ptdika.com/api/user/reset_token';
		
		// Post data
		$userData = array(
			'email' => $email,
			'token' => $token
		);

		$this->get_api_user_post($url,$userData);
	}
	
	// update password
	function update_password($id)
	{
		// API URL
		$url = 'https://rest-api.ptdika.com/api/user/update_password';
		
		// Post data
		$userData = array(
			'password' => $this->input->post('new_password'),
			'id' => $id
		);

		$this->get_api_user_post($url,$userData);
	}
	
	//========================= INTERNAL FUNCTION ============================//
	
	// get api with method get
	private function get_api_user($url)
	{
		// API key
		$apiKey = '4ad75498f665ec44c5b91e70c3cf6698';

		// API auth credentials
		$apiUser = "admindika";
		$apiPass = "B3ndh1L2019";

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);
		
		$data = json_decode($result);
		return $data;
	}
	
	// get api with method post
	private function get_api_user_post($url,$userData=array())
	{
		// API key
		$apiKey = '4ad75498f665ec44c5b91e70c3cf6698';

		// API auth credentials
		$apiUser = "admindika";
		$apiPass = "B3ndh1L2019";

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $userData);

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);
	}
}