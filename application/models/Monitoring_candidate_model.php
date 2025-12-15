<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_candidate_model extends CI_Model
{
	public $tokenAdminCouponService;
	public $ipPublic;

	function __construct()
	{
		parent::__construct();

		$this->tokenAdminCouponService = '6d7cdb9d765eed83d5eca1c6fe034b04';
		$this->ipPublic = '103.166.194.231';
	}

	private function get_api()
	{
		$query = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Recruitment'");
		return $query->row();
		$query->free_result();
	}

	//=============================================================================================================================
	//DATATABLES
		private function _get_datatables($url, $position, $nik)
		{
			$nik = $this->session->userdata('sl_code');
			$position = $this->session->userdata('position');
			$data = array(
				'length' 	 => $this->input->post('length'),
				'start' 	 => $this->input->post('start'),
				'search' 	 => $this->input->post('search')['value'],
				'position' => $position,
				'nik'		   => $nik
			);

			$rowAPI = $this->get_api();

			$apiKey = $rowAPI->api_key;

			// API auth credentials
			$apiUser = $rowAPI->Username;
			$apiPass = $rowAPI->Password;

			// Create a new cURL resource
			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_TIMEOUT, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
			curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			$result = curl_exec($ch);

			// Close cURL resource
			curl_close($ch);

			$data = json_decode($result);
			return $data;
		}

		function get_datatables($position, $nik)
		{
			$rowAPI = $this->get_api();

			// API URL
			$url = $rowAPI->url . 'api/recruitment/getDataTable_monitoring_c';
			// echo $url; die;

			$query = $this->_get_datatables($url, $position, $nik);
			$data = $query->data;
			return $data;
		}

		function count_filtered($position, $nik)
		{
			$rowAPI = $this->get_api();

			// API URL
			$url = $rowAPI->url . 'api/recruitment/countDataTable_monitoring_c';

			$query = $this->_get_datatables($url, $position, $nik);

			$data = $query->data;
			return $data;
		}
	//END

	//=============================================================================================================================
	//GET TABLE
		function get_course_id($product)
		{
			$rowAPI = $this->get_api();

			$url = $rowAPI->url . "api/recruitment/get_course_id?product={$product}";

			$apiKey = $rowAPI->api_key;

			// API auth credentials
			$apiUser = $rowAPI->Username;
			$apiPass = $rowAPI->Password;

			// Create a new cURL resource
			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 0);
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
			curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");

			$result = curl_exec($ch);

			// Close cURL resource
			curl_close($ch);

			$dataJson = json_decode($result);
			$data = $dataJson->data;
			
			return $data;
		}

		function get_data_participant($participant_id)
		{
			$rowAPI = $this->get_api();

			$url = $rowAPI->url . "api/recruitment/data_participant?participant_id={$participant_id}";

			$apiKey = $rowAPI->api_key;

			// API auth credentials
			$apiUser = $rowAPI->Username;
			$apiPass = $rowAPI->Password;

			// Create a new cURL resource
			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 0);
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
			curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");

			$result = curl_exec($ch);

			// Close cURL resource
			curl_close($ch);

			$dataJson = json_decode($result);
			$data = $dataJson->data;
			
			return $data;
		}
	//END

	//=============================================================================================================================
	//GET API LMS
		function get_single_voucher($voucher)
		{
			$url = "https://training.ptdika.com/webservice/rest/server.php?wstoken={$this->tokenAdminCouponService}&moodlewsrestformat=json&wsfunction=block_coupon_get_coupon_reports&submission_code={$voucher}";

			// Create a new cURL resource
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

			$result = curl_exec($ch);

			// Close cURL resource
			curl_close($ch);

			$query = json_decode($result);

			// $data = $query[0];

			return $query;
		}

		function get_grades($course_id, $user_id_number)
		{
			$url = "http://{$this->ipPublic}:3000/api/v1/grades?courseid={$course_id}&useridnumber={$user_id_number}";

			// Create a new cURL resource
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

			$result = curl_exec($ch);

			// Close cURL resource
			curl_close($ch);

			$query = json_decode($result);
			// $data = $query->data;

			return $query;
		}

		function get_attendance($course_id, $user_id_number)
		{
			$url = "http://{$this->ipPublic}:3000/api/v1/attendances?courseid={$course_id}&useridnumber={$user_id_number}";

			// Create a new cURL resource
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

			$result = curl_exec($ch);

			// Close cURL resource
			curl_close($ch);

			$query = json_decode($result);
			// $data = $query->data;

			return $query;
		}
	//END
}
