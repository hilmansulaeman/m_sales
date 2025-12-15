<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Recruitment_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// get datatable query
	private function _get_datatables($url)
	{
		$nik = $this->session->userdata('sl_code');
		$position = $this->session->userdata('position');
		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'search' 	=> $this->input->post('search')['value'],
			'nik'		=> $nik,
			'position'  => $position
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

	// get datatable query
	function get_datatables()
	{
		$rowAPI = $this->get_api();

		// API URL
		$url = $rowAPI->url . 'api/recruitment/getDataTable';

		$query = $this->_get_datatables($url);
		$data = $query->data;
		return $data;
	}

	// count datatable query
	function count_filtered()
	{
		$rowAPI = $this->get_api();

		// API URL
		$url = $rowAPI->url . 'api/recruitment/countDataTable';

		$query = $this->_get_datatables($url);

		$data = $query->data;
		return $data;
	}

	function kebutuhan()
	{
		$rowAPI = $this->get_api();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// Create a new cURL resource

		$url = $rowAPI->url . 'api/recruitment/kebutuhan/';
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
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

	private function get_api()
	{
		$query = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Recruitment'");
		return $query->row();
		$query->free_result();
	}

	function getDetailPelamar($id)
	{
		$rowAPI = $this->get_api();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// Create a new cURL resource

		$url = $rowAPI->url . 'api/recruitment/getDetailPelamar/?Recruitment_ID=' . $id;
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
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

	function dataSalesCopy($id)
	{
		$rowAPI = $this->get_api();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// Create a new cURL resource

		$url = $rowAPI->url . 'api/recruitment/dataSalesCopy/?nik=' . $id;
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
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

	function cekDuplikat($id)
	{
		$rowAPI = $this->get_api();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// Create a new cURL resource

		$url = $rowAPI->url . 'api/recruitment/cekDuplikat/?recruitment_id=' . $id;
		// echo $url;die();
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
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

	function smCode($nik)
	{
		$rowAPI = $this->get_api();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// Create a new cURL resource

		$url = $rowAPI->url . 'api/recruitment/smCode/?nik=' . $nik;
		// echo $url;die();
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
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

	function update($data)
	{

		$rowAPI = $this->get_api();

		$apiKey = $rowAPI->api_key;


		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		$url = $rowAPI->url . 'api/recruitment/update/';
		// echo $url;
		// die;

		$ch = curl_init($url);


		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));


		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$data2 = json_decode($result);
		// var_dump($data2);
		// die;

		return $data2->data;
	}

	function insert_approval_msales($data)
	{

		$rowAPI = $this->get_api();

		$apiKey = $rowAPI->api_key;


		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		$url = $rowAPI->url . 'api/recruitment/insert_approval_msales/';

		$ch = curl_init($url);


		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));


		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$data2 = json_decode($result);
		// var_dump($data2);
		// die;

		return $data2->data;
	}

	function listSpv($code, $nik)
	{
		$rowAPI = $this->get_api();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// Create a new cURL resource

		$url = $rowAPI->url . 'api/recruitment/listSpv/?code=' . $code . '&nik=' . $nik;
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");


		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$data = json_decode($result);
		// var_dump($data);
		// die;
		return $data->data;
	}

	function dataSpv($nik)
	{
		$rowAPI = $this->get_api();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// Create a new cURL resource

		$url = $rowAPI->url . 'api/recruitment/data_spv/?nik=' . $nik;
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");


		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$data = json_decode($result);
		// var_dump($data);
		// die;
		return $data->data;
	}
}
