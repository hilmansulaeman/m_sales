<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Merchant_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// get datatable query
	function get_datatables($where)
	{
		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'search' 	=> $this->input->post('search')['value'],
			'where'		=> $where
		);

		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/getDataTable';

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

		$dataDecode = json_decode($result);
		$data = $dataDecode->data;
		return $data;
	}

	// count datatable query
    function count_filtered($where)
    {
		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'search' 	=> $this->input->post('search')['value'],
			'where'		=> $where
		);

	    $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/countDataTable';

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

		$dataDecode = json_decode($result);
		$data = $dataDecode->data;

		return $data;
    }
	// ====================================================================================================================================================================

	// ====================================================================================================================================================================
	
	// function getDataInputLocal_old($var,$sales,$from,$to,$section)
	// {
		// 	if ($section == 'BS') {
		// 		$wheres = "t1.Hit_Code IN('101','102') AND t1.Created_Date >= '$from 00:00:00' AND t1.Created_Date <= '$to 23:59:59'";
		// 	}else{
		// 		$wheres = "t1.Created_Date >= '$from 00:00:00' AND t1.Created_Date <= '$to 23:59:59'";
		// 	}

		// 	$query = $this->db->query("SELECT
		// 			SUM(IF(Product_Type = 'EDC',1,0)) AS edc,
		// 			SUM(IF(Product_Type = 'QRIS',1,0)) AS qris,
		// 			SUM(IF(Product_Type = 'EDC_QRIS',1,0)) AS edc_qris,
		// 			COUNT(1) AS total
		// 			FROM internal.edc_merchant t1
		// 			LEFT JOIN internal.data_sales_copy t2 ON t2.DSR_Code = t1.sales_code
		// 			WHERE $var = '$sales'
		// 			AND $wheres
		// 		")->row();

		// 	return $query;
	// }

	// function getAppProcessingLocal_old($var,$sales,$from,$to,$section)
	// {
		// 	if ($section == 'PR') {
		// 		$wheres = "t1.Received_Date >= '$from 00:00:00' AND t1.Received_Date <= '$to 23:59:59'";
		// 	}else if($section == 'PI') {
		// 		$wheres = "t1.Status IN ('SUBMIT_TO_DIKA', 'PENDING_FU') AND t1.Decision_Date >= '$from 00:00:00' AND t1.Decision_Date <= '$to 23:59:59'";
		// 	}else if($section == 'PRTS') {
		// 		$wheres = "t1.Status = 'RETURN_TO_SALES' AND t1.Decision_Date >= '$from 00:00:00' AND t1.Decision_Date <= '$to 23:59:59'";
		// 	}else if($section == 'PS') {
		// 		$wheres = "t1.Status = 'SUBMIT_TO_BCA' AND t1.Decision_Date >= '$from 00:00:00' AND t1.Decision_Date <= '$to 23:59:59'";
		// 	}

		// 	$query = $this->db->query("SELECT
		// 		SUM(IF(Product_Type = 'EDC',1,0)) AS edc,
		// 		SUM(IF(Product_Type = 'QRIS',1,0)) AS qris,
		// 		SUM(IF(Product_Type = 'EDC_QRIS',1,0)) AS edc_qris,
		// 		COUNT(1) AS total
		// 		FROM internal.edc_merchant t1
		// 		LEFT JOIN internal.data_sales_copy t2 ON t2.DSR_Code = t1.sales_code
		// 		WHERE $var = '$sales'
		// 		AND $wheres
		// 	")->row();

		// 	return $query;
	// }

	// function getDataInputDummy_old($nik,$date_from,$date_to,$position,$part)
	// {
		// if ($position == 'rsm') {
		// 	$var = 't2.RSM_Code';
		// }else if($position == 'asm') {
		// 	$var = 't2.ASM_Code';
		// }else if($position == 'spv') {
		// 	$var = 't2.SPV_Code';
		// }

		// if ($part == 'BS') {
		// 	$wheres = "t1.Hit_Code IN('101','102') AND t1.Created_Date >= '$date_from 00:00:00' AND t1.Created_Date <= '$date_to 23:59:59'";
		// }else{
		// 	$wheres = "t1.Created_Date >= '$date_from 00:00:00' AND t1.Created_Date <= '$date_to 23:59:59'";
		// }

		// $query = $this->db->query("SELECT
		// 		SUM(IF(Product_Type = 'EDC',1,0)) AS edc,
		// 		SUM(IF(Product_Type = 'QRIS',1,0)) AS qris,
		// 		SUM(IF(Product_Type = 'EDC_QRIS',1,0)) AS edc_qris,
		// 		COUNT(1) AS total
		// 		FROM internal.edc_merchant t1
		// 		LEFT JOIN internal.data_sales_copy t2 ON t2.DSR_Code = t1.sales_code
		// 		WHERE $var = '$nik'
		// 		AND $wheres
		// 	")->row();

		// return $query;
	// }

	// function getAppProcessingDummy_old($nik,$date_from,$date_to,$position,$part)
	// {
		// if ($position == 'rsm') {
		// 	$var = 't2.RSM_Code';
		// }else if($position == 'asm') {
		// 	$var = 't2.ASM_Code';
		// }else if($position == 'spv') {
		// 	$var = 't2.SPV_Code';
		// }

		// if ($part == 'PR') {
		// 	$wheres = "t1.Received_Date >= '$date_from 00:00:00' AND t1.Received_Date <= '$date_to 23:59:59'";
		// }else if($part == 'PI') {
		// 	$wheres = "t1.Status IN ('SUBMIT_TO_DIKA', 'PENDING_FU') AND t1.Decision_Date >= '$date_from 00:00:00' AND t1.Decision_Date <= '$date_to 23:59:59'";
		// }else if($part == 'PRTS') {
		// 	$wheres = "t1.Status = 'RETURN_TO_SALES' AND t1.Decision_Date >= '$date_from 00:00:00' AND t1.Decision_Date <= '$date_to 23:59:59'";
		// }else if($part == 'PS') {
		// 	$wheres = "t1.Status = 'SUBMIT_TO_BCA' AND t1.Decision_Date >= '$date_from 00:00:00' AND t1.Decision_Date <= '$date_to 23:59:59'";
		// }

		// $query = $this->db->query("SELECT
		// 	SUM(IF(Product_Type = 'EDC',1,0)) AS edc,
		// 	SUM(IF(Product_Type = 'QRIS',1,0)) AS qris,
		// 	SUM(IF(Product_Type = 'EDC_QRIS',1,0)) AS edc_qris,
		// 	COUNT(1) AS total
		// 	FROM internal.edc_merchant t1
		// 	LEFT JOIN internal.data_sales_copy t2 ON t2.DSR_Code = t1.sales_code
		// 	WHERE $var = '$nik'
		// 	AND $wheres
		// ")->row();

		// return $query;
	// }

	// function getTotalsProcessingDummy_old($nik,$date_from,$date_to,$position,$part)
	// {
		// if ($position == 'rsm') {
		// 	$var = 't2.RSM_Code';
		// }else if($position == 'asm') {
		// 	$var = 't2.ASM_Code';
		// }else if($position == 'spv') {
		// 	$var = 't2.SPV_Code';
		// }

		// if ($part == 'received') {
		// 	$wheres = "t1.Received_Date >= '$date_from 00:00:00' AND t1.Received_Date <= '$date_to 23:59:59'";
		// }else if($part == 'inprocess') {
		// 	$wheres = "t1.Status IN ('SUBMIT_TO_DIKA', 'PENDING_FU') AND t1.Decision_Date >= '$date_from 00:00:00' AND t1.Decision_Date <= '$date_to 23:59:59'";
		// }else if($part == 'rts') {
		// 	$wheres = "t1.Status = 'RETURN_TO_SALES' AND t1.Decision_Date >= '$date_from 00:00:00' AND t1.Decision_Date <= '$date_to 23:59:59'";
		// }else if($part == 'send') {
		// 	$wheres = "t1.Status = 'SUBMIT_TO_BCA' AND t1.Decision_Date >= '$date_from 00:00:00' AND t1.Decision_Date <= '$date_to 23:59:59'";
		// }

		// $query = $this->db->query("SELECT
		// 	COUNT(1) AS total
		// 	FROM internal.edc_merchant t1
		// 	LEFT JOIN internal.data_sales_copy t2 ON t2.DSR_Code = t1.sales_code
		// 	WHERE $var = '$nik'
		// 	AND $wheres
		// ")->row();

		// return $query;
	// }

	// ===============================================================================================================================================================
	// API LEADER

	function getDataInputLocal($var,$sales,$from,$to,$section,$source='')
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/getDataInputLeader?lead_code='.$var.'&sales='.$sales.'&from='.$from.'&to='.$to.'&section='.$section.'&source='.$source;

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

	function getAppProcessingLocal($var,$sales,$from,$to,$section,$source='')
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/getAppProcessingLeader?lead_code='.$var.'&sales='.$sales.'&from='.$from.'&to='.$to.'&section='.$section.'&source='.$source;

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

	function getDataInputDummy($nik,$date_from,$date_to,$position,$part,$source='')
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/getDataInputDummy?nik='.$nik.'&from='.$date_from.'&to='.$date_to.'&position='.$position.'&part='.$part.'&source='.$source;

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

	function getAppProcessingDummy($nik,$date_from,$date_to,$position,$part,$source='')
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/getAppProcessingDummy?nik='.$nik.'&from='.$date_from.'&to='.$date_to.'&position='.$position.'&part='.$part.'&source='.$source;

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

	function getTotalsProcessingDummy($nik,$date_from,$date_to,$position,$part)
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/getTotalsProcessingDummy?nik='.$nik.'&from='.$date_from.'&to='.$date_to.'&position='.$position.'&part='.$part;

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
	// ===============================================================================================================================================================

	// ===============================================================================================================================================================
	// API DSR

	function getDataInput($sales, $from, $to, $section)
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/getDataInput?sales='.$sales.'&from='.$from.'&to='.$to.'&section='.$section;

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

	function getDataProcessing($sales, $from, $to, $section)
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/getDataProcessing?sales='.$sales.'&from='.$from.'&to='.$to.'&section='.$section;

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

	function getTotalsProcessing($sales, $from, $to, $section)
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/getTotalsProcessing?sales='.$sales.'&from='.$from.'&to='.$to.'&section='.$section;

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

	function detBreakdownMerchantLeader($sales_code, $sales, $status, $part, $from, $to)
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/detBreakdownMerchantLeader?sales_code='.$sales_code.'&sales='.$sales.'&status='.$status.'&part='.$part.'&from='.$from.'&to='.$to;

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

	function detBreakdownMerchantDSR($sales, $status, $part, $from, $to)
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/detBreakdownMerchantDSR?sales='.$sales.'&status='.$status.'&part='.$part.'&from='.$from.'&to='.$to;

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

	function getBreakdownMerchantexport($date_from, $date_to, $source = '')
	{
		$nik 		  = $this->session->userdata('sl_code');
		$position = $this->session->userdata('position');

		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;
		// API URL
		$url = $rowAPI->url.'api/incoming_merchant/getMerchantExport?nik='.$nik.'&position='.$position.'&date_from='.$date_from.'&date_to='.$date_to.'&source='.$source;

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
