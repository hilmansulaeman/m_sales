<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	// get datatable query
	private function _get_datatables($url, $where)
	{
		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'search' 	=> $this->input->post('search')['value'],
			'where'		=> $where
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
	function get_datatables($where)
	{
		$rowAPI = $this->get_api();
		
		// API URL
		$url = $rowAPI->url.'api/incoming_sc/getDataTable';
		
		$query = $this->_get_datatables($url,$where);
		
		$data = $query->data;
		return $data;
	}

	// count datatable query
    function count_filtered($where)
    {
		$rowAPI = $this->get_api();
		
		// API URL
		$url = $rowAPI->url.'api/incoming_sc/countDataTable';

		$query = $this->_get_datatables($url,$where);
		
		$data = $query->data;
		return $data;
    }

	// ===============================================================================================================================================================
	// API LEADER
	
	// get data input leader
	function getDataInputLeader($var,$sales,$from,$to,$type)
	{
		$rowAPI = $this->get_api();
		
		// API URL
		$url = $rowAPI->url.'api/incoming_sc/getDataInputLeader?lead_code='.$var.'&sales='.$sales.'&from='.$from.'&to='.$to.'&type='.$type;
		
		$query = $this->get_incoming($url);
		
		$data = $query->data;
		return $data;
	}

	function getDataInputDummy($nik,$from,$to,$position,$type)
	{
		$rowAPI = $this->get_api();
		
		// API URL
		$url = $rowAPI->url.'api/incoming_sc/getDataInputDummy?nik='.$nik.'&from='.$from.'&to='.$to.'&position='.$position.'&type='.$type;

		$query = $this->get_incoming($url);
		
		$data = $query->data;
		return $data;
	}
	
	// ===============================================================================================================================================================

	// ===============================================================================================================================================================
	// API DSR

	function getDataInput($sales, $from, $to, $type)
	{
		$rowAPI = $this->get_api();
		
		// API URL
		$url = $rowAPI->url.'api/incoming_sc/getDataInput?sales='.$sales.'&from='.$from.'&to='.$to.'&type='.$type;

		$query = $this->get_incoming($url);
		
		$data = $query->data;
		return $data;
	}

	function detBreakdownInputLeader($sales_code, $sales, $status, $type, $from, $to)
	{
		$rowAPI = $this->get_api();
		
		// API URL
		$url = $rowAPI->url.'api/incoming_sc/detBreakdownInputLeader?sales_code='.$sales_code.'&sales='.$sales.'&status='.$status.'&type='.$type.'&from='.$from.'&to='.$to;

		$query = $this->get_incoming($url);
		
		$data = $query->data;
		return $data;
	}

	function detBreakdownInputDSR($sales, $status, $type, $from, $to)
	{
		$rowAPI = $this->get_api();
		
		// API URL
		$url = $rowAPI->url.'api/incoming_sc/detBreakdownInputDSR?sales='.$sales.'&status='.$status.'&type='.$type.'&from='.$from.'&to='.$to;

		$api = $this->get_incoming($url);
		
		$data = $api->data;
		return $data;
	}
	
	private function get_api()
	{
		$query = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'SC'");
		return $query->row();
		$query->free_result();
	}
	
	private function get_incoming($url)
	{
		$rowAPI = $this->get_api();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// Create a new cURL resource
		$ch = curl_init($url);

		//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
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
}
