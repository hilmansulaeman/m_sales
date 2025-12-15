<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class qris_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	// get datatable query
	function _get_datamerchant_query($where)
    {
		$sales_code = $this->session->userdata('username');
		
		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'search' 	=> $this->input->post('search')['value'],
			'sales_code'=> $sales_code,
			'wheres'	=> $where
		);

		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/getDataTable';

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
    function _count_datamerchant($where)
    {
		$sales_code = $this->session->userdata('username');
		
		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'search' 	=> $this->input->post('search')['value'],
			'sales_code'=> $sales_code,
			'wheres'	=> $where
		);

        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/countDataTable';

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
	
	function get_status_api() {
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/status_aplikasi';

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

		$mainData = json_decode($result);
		$data = $mainData->data;
		
		return $data;
	}

	function total($sales_code, $position, $tgl1, $tgl2)
	{
		if($position == 'DSR') {
			$where = "b.DSR_Code = '$sales_code'";
		}
		else if($position == 'SPV') {
			$where = "b.SPV_Code = '$sales_code'";
		}
		else if($position == 'ASM') {
			$where = "b.ASM_Code = '$sales_code'";
		}
		else if($position == 'RSM') {
			$where = "b.RSM_Code = '$sales_code'";
		}
		else {
			$where = "b.BSH_Code = '$sales_code'";
		}
		$query = $this->db->query("SELECT COUNT(1) AS qris FROM `internal`.`edc_merchant` a
								INNER JOIN `internal`.`data_sales_structure` b ON b.DSR_Code = a.Sales_Code
								WHERE $where AND Product_Type='QRIS' AND a.Status NOT IN('return_to_dika','return_to_sales','approve') 
								AND a.Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'");
		return $query;
		$query->free_result();
	}
    
    function m_breakdown_qris($sales_code, $position, $tgl1, $tgl2)
    {
		if($position == 'DSR') {
			$where = "b.DSR_Code = '$sales_code'";
		}
		else if($position == 'SPV') {
			$where = "b.SPV_Code = '$sales_code'";
		}
		else if($position == 'ASM') {
			$where = "b.ASM_Code = '$sales_code'";
		}
		else if($position == 'RSM') {
			$where = "b.RSM_Code = '$sales_code'";
		}
		else {
			$where = "b.BSH_Code = '$sales_code'";
		}
		//$sales_code = $this->CI->session->userdata('sales_code'); 
		$query = $this->db->query("SELECT
            SUM(IF(a.Hit_Code IN('101','102','107'),1,0)) AS new_merchant,
            SUM(IF(a.Hit_Code IN('103','104'),1,0)) AS returned,
            SUM(IF(a.Hit_Code = '105',1,0)) AS submit_to_dika,
            SUM(IF(a.Hit_Code = '106' AND a.Status = 'pending_fu',1,0)) AS pending_fu,
            SUM(IF(a.Hit_Code = '106' AND a.Status = 'submit_to_bca',1,0)) AS submit_to_bca,
            SUM(IF(a.Hit_Code = '106' AND a.Status = 'return_from_bca',1,0)) AS return_from_bca,
            SUM(IF(a.Hit_Code = '106' AND a.Status = 'resubmit_to_bca',1,0)) AS resubmit_to_bca,
            SUM(IF(a.Hit_Code = '106' AND a.Status = 'cancel',1,0)) AS cancel,
            SUM(IF(a.Hit_Code = '106' AND a.Status = 'reject',1,0)) AS reject
            FROM `internal`.`edc_merchant` a
			INNER JOIN `internal`.`data_sales_structure` b
			ON b.DSR_Code = a.Sales_Code
			WHERE $where AND Product_Type = 'QRIS' AND Status_Aplikasi_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'
			");
		
		if($query->num_rows() == 0){ 
			return false;
		}
		else{
			return $query;
			$query->free_result();
		}
    }

}