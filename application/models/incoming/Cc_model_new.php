<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cc_model_new extends CI_Model
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
		$url = $rowAPI->url.'api/incoming_cc/getDataTable';
		
		$query = $this->_get_datatables($url,$where);
		
		$data = $query->data;
		return $data;
	}

	// count datatable query
    function count_filtered($where)
    {
		$rowAPI = $this->get_api();
		
		// API URL
		$url = $rowAPI->url.'api/incoming_cc/countDataTable';

		$query = $this->_get_datatables($url,$where);
		
		$data = $query->data;
		return $data;
    }

	// ===============================================================================================================================================================
	// API LEADER

	function getDataProject()
	{
		$rowAPI = $this->get_api();

		// API URL
		$url = $rowAPI->url . 'api/incoming_cc/project';
		// echo($url); die;
		$query = $this->get_incoming($url);

		if (!$query->status) {
			$this->db->distinct();
			$this->db->select('Project');
			$this->db->from("internal.applications");
			$this->db->where('Project !=', '');
			$this->db->order_by("Project", "asc");
			$query = $this->db->get();

			$data =  $query->result();
		} else {
			$data = $query->data;
		}

		return $data;
	}
	
	// get data input leader
	function getDataInputLeader($var,$sales,$from,$to,$type,$project = '')
	{
		$rowAPI = $this->get_api();

		$p = str_replace(' ', '_', $project);
		
		// API URL
		$url = $rowAPI->url.'api/incoming_cc/getDataInputLeader?lead_code='.$var.'&sales='.$sales.'&from='.$from.'&to='.$to.'&type='.$type.'&project='.$p;
		
		$query = $this->get_incoming($url);
		
		$data = $query->data;
		return $data;
	}

	function getDataInputDummy($nik,$from,$to,$position,$type,$project = '')
	{
		$rowAPI = $this->get_api();
		$p = str_replace(' ', '_', $project);
		// API URL
		$url = $rowAPI->url.'api/incoming_cc/getDataInputDummy?nik='.$nik.'&from='.$from.'&to='.$to.'&position='.$position.'&type='.$type.'&project='.$p;

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
		$url = $rowAPI->url.'api/incoming_cc/getDataInput?sales='.$sales.'&from='.$from.'&to='.$to.'&type='.$type;

		$query = $this->get_incoming($url);
		
		$data = $query->data;
		return $data;
	}

	function detBreakdownInputLeader($sales_code, $sales, $status, $type, $from, $to, $project = '')
	{
		$rowAPI = $this->get_api();
		$p = str_replace(' ', '_', $project);
		
		// API URL
		$url = $rowAPI->url.'api/incoming_cc/detBreakdownInputLeader?sales_code='.$sales_code.'&sales='.$sales.'&status='.$status.'&type='.$type.'&from='.$from.'&to='.$to.'&project='.$p;

		$query = $this->get_incoming($url);
		
		$data = $query->data;
		return $data;
	}

	function detBreakdownInputDSR($sales, $status, $type, $from, $to)
	{
		$rowAPI = $this->get_api();
		
		// API URL
		$url = $rowAPI->url.'api/incoming_cc/detBreakdownInputDSR?sales='.$sales.'&status='.$status.'&type='.$type.'&from='.$from.'&to='.$to;

		$api = $this->get_incoming($url);
		
		$data = $api->data;
		return $data;
	}
	
	private function get_api()
	{
		$query = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'CC'");
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

	function getBreakdownExport($date_from, $date_to, $type, $project = '')
	{
		$nik 		  = $this->session->userdata('sl_code');
		$position = $this->session->userdata('position');
		$p = str_replace(' ', '_', $project);

		// $getAPI = $this->db->query("SELECT * FROM `key_api_dev` WHERE `Description` = 'CC'");
		// $rowAPI = $getAPI->row();
		$rowAPI = $this->get_api();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;
		// API URL
		$url = $rowAPI->url.'api/incoming_cc/getCCExport?nik='.$nik.'&position='.$position.'&date_from='.$date_from.'&date_to='.$date_to.'&type='.$type.'&project='.$p;

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

		$dataDecode = json_decode($result);
		$data = $dataDecode->data;

		return $data;
	}
}
