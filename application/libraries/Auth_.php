<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Auth library
 */

class Auth
{
	var $CI = NULL;

	function __construct()
	{
		// get CI's object
		$this->CI = &get_instance();
		$this->CI->load->helper('cookie');
		$this->CI->load->library('session');
		$this->CI->load->database();
	}

	// untuk validasi login sales
	function do_login($username, $password)
	{
		// API key
		$apiKey = '4ad75498f665ec44c5b91e70c3cf6698';

		// API auth credentials
		$apiUser = "admindika";
		$apiPass = "B3ndh1L2019";

		// API URL
		//$url = 'https://rest-api.ptdika.com/api/user/login_saless?username='.$username.'&password='.$password;
		$url = 'https://dev.ptdika.com/rest-api/api/user/login_saless?username=' . $username . '&password=' . $password;

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
		$responStatus = $data->status;

		if ($responStatus == '1') {
			$userdata = $data->data;
			$array_dsr = array("RO", "Mobile Sales", "SPG", "SPB", "SALES");
			if (in_array($userdata->Position, $array_dsr)) {
				$paramPos = "DSR";
			} else {
				$paramPos = $userdata->Position;
				/*if($paramPos == "ASH")
				{
					$paramPos = "BSH";
				}*/
			}
			$session_data = array(
				'user_id'	=> $userdata->Employee_ID,
				'username'	=> $userdata->DSR_Code,
				'realname'	=> $userdata->Name,
				'position'	=> $paramPos,
				'sl_code'	=> $userdata->DSR_Code,
				'product'	=> $userdata->Product,
				'channel'	=> $userdata->Channel,
				'level'		=> $userdata->Level,
				'branch'	=> $userdata->Branch,
				'foto'		=> $userdata->Image_Photo,
				'FL_pass'	=> $userdata->Password_Change
			);
			// buat session
			$this->CI->session->set_userdata($session_data);
			return true;
		} else if ($responStatus == '0') {
			return false;
		} else {
			$this->do_loginn($username, $password);
			return true;
		}
	}

	// untuk validasi login sales
	function do_loginn($username, $password)
	{
		$username_ = $this->CI->db->escape_str($username);
		$password_ = $this->CI->db->escape_str($password);

		// cek di database, ada ga?
		$this->CI->db->from('`db_user`.`user_employee`');
		$this->CI->db->where('DSR_Code', $username_);
		$this->CI->db->where('Password=MD5("' . $password_ . '")', '', false);
		// $this->CI->db->where('Date_Of_Birth', $password);
		$this->CI->db->where('Status', 'ACTIVE');
		$result = $this->CI->db->get();
		if ($result->num_rows() == 0) {
			// username dan password tsb tidak ada 
			return false;
		} else {
			// jika ada, maka ambil informasi dari database
			$userdata = $result->row();
			$array_dsr = array("RO", "Mobile Sales", "SPG", "SPB", "SALES");
			if (in_array($userdata->Position, $array_dsr)) {
				$paramPos = "DSR";
			} else {
				$paramPos = $userdata->Position;
				/*if($paramPos == "ASH")
				{
					$paramPos = "BSH";
				}*/
			}
			$session_data = array(
				'user_id'	=> $userdata->Employee_ID,
				'username'	=> $userdata->DSR_Code,
				'realname'	=> $userdata->Name,
				'position'	=> $paramPos,
				'sl_code'	=> $userdata->DSR_Code,
				'product'	=> $userdata->Product,
				'channel'	=> $userdata->Channel,
				'level'		=> $userdata->Level,
				'branch'	=> $userdata->Branch,
				'foto'		=> $userdata->image,
				'FL_pass'	=> $userdata->Password_Change
			);
			// buat session
			$this->CI->session->set_userdata($session_data);
			return true;
		}
	}

	// untuk validasi login user
	function do_login_user($username, $password)
	{
		$username_ = $this->CI->db->escape_str($username);
		$password_ = $this->CI->db->escape_str($password);

		// cek di database, ada ga?
		$this->CI->db->from('users');
		$this->CI->db->where('username', $username_);
		$this->CI->db->where('password=MD5("' . $password_ . '")', '', false);
		$this->CI->db->where('privilege !=', '1');
		$this->CI->db->where('Status', 'ACTIVE');
		$result = $this->CI->db->get();
		if ($result->num_rows() == 0) {
			// username dan password tsb tidak ada 
			return false;
		} else {
			// jika ada, maka ambil informasi dari database
			$userdata = $result->row();
			$array_dsr = array("RO", "Mobile Sales", "SPG", "SPB", "SALES");
			if (in_array($userdata->position, $array_dsr)) {
				$paramPos = "DSR";
			} else {
				$paramPos = $userdata->position;
			}
			$session_data = array(
				'username'	=> $userdata->username,
				'realname'	=> $userdata->name,
				'position'	=> $paramPos,
				'sl_code'	=> $userdata->sales_code,
				'product'	=> $userdata->product,
				'channel'	=> $userdata->channel,
				'level'		=> $userdata->level,
				'branch'	=> $userdata->branch,
				'foto'		=> $userdata->image,
				'FL_pass'	=> '1'
			);
			// buat session
			$this->CI->session->set_userdata($session_data);
			return true;
		}
	}

	// untuk mengecek apakah user sudah login/belum
	// function is_logged_in()
	// {
	// 	if($this->CI->session->userdata('sl_code') == '')
	// 	{
	// 		return false;
	// 	}
	// 	else{
	// 	    return true;
	// 	}
	// }

	function is_logged_in()
	{
		//check session data
		$username = $this->CI->session->userdata('username');
		$db = $this->CI->config->item('sess_save_path');
		$query = $this->CI->db->get_where($db, array('username' => $username));

		if ($username == '' || $query->num_rows() == 0) {
			return false;
		} else {
			return true;
		}
	}

	// untuk logout
	// function do_logout()
	// {
	// 	$this->CI->session->sess_destroy();	
	// }

	function do_logout()
	{
		if ($this->CI->config->item('sess_driver') == 'database') {
			//delete session data
			$username = $this->CI->session->userdata('username');
			$this->delete_session($username);
		}
		//destroy session
		$this->CI->session->sess_destroy();
	}

	//check active session
	function active_session($username)
	{
		// cek di database, ada ga?
		$username_ = $this->CI->db->escape_str($username);
		$this->CI->db->from($this->CI->config->item('sess_save_path'));
		$this->CI->db->where('username', $username_);
		$query = $this->CI->db->get();
		if ($query->num_rows() > 0) {
			// user sudah login
			return false;
		} else {
			// user belum login
			return true;
		}
	}

	//delete session data
	private function delete_session($username)
	{
		$this->CI->db->where('username', $username);
		$this->CI->db->delete($this->CI->config->item('sess_save_path'));
	}
}
