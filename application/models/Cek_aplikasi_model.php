<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Cek_aplikasi_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	// ================================================== DATA INCOMING =============================================== //
	
	function get_incoming_pemol($key, $sales)
	{
		$rowAPI = $this->get_api('PEMOL');
		
		// API URL
		$url = $rowAPI->url.'api/check_incoming/pemol?key='.urlencode($key).'&sales_code='.$sales;

		$query = $this->_get_incoming('PEMOL',$url);
		
		$data = $query->data;
		return $data;
	}
	
	function get_incoming_merchant($key, $sales)
	{
		$rowAPI = $this->get_api('CC');
		
		// API URL
		$url = $rowAPI->url.'api/check_incoming/merchant?key='.urlencode($key).'&sales_code='.$sales;

		$query = $this->_get_incoming('CC',$url);
		
		$data = $query->data;
		return $data;
	}
	
	function get_incoming_cc($key, $sales)
	{
		$rowAPI = $this->get_api('CC');
		
		// API URL
		$url = $rowAPI->url.'api/check_incoming/cc?key='.urlencode($key).'&sales_code='.$sales;

		$query = $this->_get_incoming('CC',$url);
		
		$data = $query->data;
		return $data;
	}
	
	function get_incoming_corporate($key, $sales)
	{
		$rowAPI = $this->get_api('CC');
		
		// API URL
		$url = $rowAPI->url.'api/check_incoming/corporate?key='.urlencode($key).'&sales_code='.$sales;

		$query = $this->_get_incoming('CC',$url);
		
		$data = $query->data;
		return $data;
	}
	
	function get_incoming_sc($key, $sales)
	{
		$rowAPI = $this->get_api('SC');
		
		// API URL
		$url = $rowAPI->url.'api/check_incoming/sc?key='.urlencode($key).'&sales_code='.$sales;

		$query = $this->_get_incoming('SC',$url);
		
		$data = $query->data;
		return $data;
	}
	
	function get_incoming_pl($key, $sales)
	{
		$rowAPI = $this->get_api('PL');
		
		// API URL
		$url = $rowAPI->url.'api/check_incoming/pl?key='.urlencode($key).'&sales_code='.$sales;

		$query = $this->_get_incoming('PL',$url);
		
		$data = $query->data;
		return $data;
	}
	
	private function get_api($product)
	{
		$query = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = '$product'");
		return $query->row();
		$query->free_result();
	}
	
	private function _get_incoming($product,$url)
	{
		$rowAPI = $this->get_api($product);

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
	
	// ================================================== LOG INCOMING =============================================== //
	
	// Log Incoming Merchant
	function get_log_merchant($id, $status)
	{
		$rowAPI = $this->get_api('CC');
		
		// API URL
		$url = $rowAPI->url.'api/check_incoming/log_merchant?id='.$id.'&status='.$status;

		$query = $this->_get_incoming('CC',$url);
		
		$data = $query->data;
		return $data;
	}
	
	// ================================================== DATA DECISION =============================================== //
	
	function get_decision_pemol($key,$sales=''){
		$key_ = $this->db->escape_str($key);
		$this->db->select('*');
		$this->db->from('data_upload_oa_pemol_detail');
		$this->db->where('Nomor_Rekening', $key_);
		if($sales != ''){
			$this->db->where('Sales_Code',$sales);
		}
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}
	
	function get_decision_merchant($key,$sales=''){
		$key_ = $this->db->escape_str($key);
		$this->db->select('*');
		$this->db->from('internal.edc_result');
		if($sales != ''){
			$this->db->where('Sales_Code',$sales);
		}
		$this->db->group_start();
		$this->db->or_like('Merchant_Name', urldecode($key_));
		$this->db->or_like('Owner_Name', urldecode($key_));
		$this->db->group_end();
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}
	
	function get_decision_cc($key,$sales=''){
		$key_ = $this->db->escape_str($key);
		$this->db->select('*');
		$this->db->from('internal.application_process');
		$this->db->like('Cust_Name', urldecode($key_));
		$this->db->or_like('NO_ACCO', urldecode($key_));
		
		if($sales != ''){
			$this->db->where('Sales_Code',$sales);
		}
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}
	
	function get_decision_corporate($key,$sales=''){
		$key_ = $this->db->escape_str($key);
		$this->db->select('*');
		$this->db->from('internal.corporate_ro_result');
		$this->db->like('customer_name', urldecode($key_));
		if($sales != ''){
			$this->db->where('sales_code',$sales);
		}
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}
	
	function get_decision_sc($key,$sales=''){
		$key_ = $this->db->escape_str($key);
		$this->db->select('*');
		$this->db->from('internal.sc_result');
		$this->db->like('cust_name', urldecode($key_));
		if($sales != ''){
			$this->db->where('sales_code',$sales);
		}
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}
	
	function get_decision_pl($key,$sales=''){
		$key_ = $this->db->escape_str($key);
		$this->db->select('*');
		$this->db->from('internal.apps_pl_result');
		$this->db->like('Debitur_Name', urldecode($key_));
		if($sales != ''){
			$this->db->where('sales_code',$sales);
		}
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}
}