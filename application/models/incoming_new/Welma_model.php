<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welma_model extends CI_Model
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

		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Welma'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/summary_api/getDataTable';

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

        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Welma'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/summary_api/countDataTable';

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
	
	// get welma
	function get_welma($var,$sales,$from,$to)
	{
		
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Welma'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/summary_api/getWelma?var='.$var.'&sales='.$sales.'&from='.$from.'&to='.$to;

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

	// get welma dummy
	function get_welma_dummy($sales,$from,$to,$part)
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Welma'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/summary_api/getWelmaDummy?sales='.$sales.'&from='.$from.'&to='.$to.'&part='.$part;

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

	// //get SPV BY ASM
	// function get_by_asm($sm_code)
	// {
	//     $this->db->select('*');
	// 	$this->db->from('`internal`.`data_sales`');
	// 	$this->db->where('SM_Code', $sm_code);
	// 	$this->db->where('Position', 'SPV');
	// 	$this->db->where('Status', 'ACTIVE');
	// 	$this->db->order_by('Name', 'ASC');
	// 	$query = $this->db->get();
	// 	return $query;
	// 	$query->free_result();
	// }
	
	// //get SPV BY RSM
	// function get_by_rsm($sm_code)
	// {
	//     $this->db->select('*');
	// 	$this->db->from('`internal`.`data_sales_structure`');
	// 	$this->db->where('RSM_Code', $sm_code);
	// 	$this->db->where('Position', 'SPV');
	// 	$this->db->where('Status', 'ACTIVE');
	// 	$this->db->order_by('Name', 'ASC');
	// 	$query = $this->db->get();
	// 	return $query;
	// 	$query->free_result();
	// }

	// API AREA
	
	// function get_pemol($var,$sales,$from,$to)
	// {
	// 	$getData = $this->db->query("SELECT count(1) as total
	// 									FROM data_pemol t1
	// 									INNER JOIN internal.data_sales_copy t2
	// 									ON t1.Sales_Code = t2.DSR_Code
	// 									WHERE $var = '$sales'
	// 									AND t1.Input_Date >= '$from'
	// 									AND t1.Input_Date <= '$to'
	// 								");
	// 	return $getData;
	// 	$getData->free_result();
	// }
	
	// function get_pemol_dummy_rsm($sales,$from,$to)
	// {
	//     $getData = $this->db->query("SELECT count(1) as total
	// 	                             FROM data_pemol t1
	// 								 INNER JOIN internal.data_sales_copy t2
	// 								 ON t1.Sales_Code = t2.DSR_Code
	// 								 WHERE t2.RSM_Code = '0'
	// 								 AND t2.BSH_Code = '$sales'
	// 								 AND t1.Input_Date >= '$from'
	// 								 AND t1.Input_Date <= '$to'
	// 	                           ");	
	// 	return $getData;
	// 	$getData->free_result();
	// }
	
	// function get_pemol_dummy_asm($sales,$from,$to)
	// {
	//     $getData = $this->db->query("SELECT count(1) as total
	// 	                             FROM data_pemol t1
	// 								 INNER JOIN internal.data_sales_copy t2
	// 								 ON t1.Sales_Code = t2.DSR_Code
	// 								 WHERE t2.ASM_Code = '0'
	// 								 AND t2.RSM_Code = '$sales'
	// 								 AND t1.Input_Date >= '$from'
	// 								 AND t1.Input_Date <= '$to'
	// 	                           ");	
	// 	return $getData;
	// 	$getData->free_result();
	// }
	
	// function get_pemol_dummy_spv($sales,$from,$to)
	// {
	//     $getData = $this->db->query("SELECT count(1) as total
	// 	                             FROM data_pemol t1
	// 								 INNER JOIN internal.data_sales_copy t2
	// 								 ON t1.Sales_Code = t2.DSR_Code
	// 								 WHERE t2.SPV_Code = '0'
	// 								 AND t2.ASM_Code = '$sales'
	// 								 AND t1.Input_Date >= '$from'
	// 								 AND t1.Input_Date <= '$to'
	// 	                           ");	
	// 	return $getData;
	// 	$getData->free_result();
	// }
	
	// function get_pemol2($sales,$from,$to)
	// {
	//     $getData = $this->db->query("SELECT count(1) as total
	// 	                             FROM data_pemol t1
	// 								 INNER JOIN internal.data_sales_copy t2
	// 								 ON t1.Sales_Code = t2.DSR_Code
	// 								 WHERE t2.ASM_Code = '0'
	// 								 AND t2.RSM_Code = '$sales'
	// 								 AND t1.Input_Date >= '$from'
	// 								 AND t1.Input_Date <= '$to'
	// 	                           ");	
	// 	return $getData;
	// 	$getData->free_result();
	// }
	
	// function get_pemol3($sales,$from,$to)
	// {
	//     $getData = $this->db->query("SELECT count(1) as total
	// 	                             FROM data_pemol t1
	// 								 INNER JOIN internal.data_sales_copy t2
	// 								 ON t1.Sales_Code = t2.DSR_Code
	// 								 WHERE t2.SPV_Code = '0'
	// 								 AND t2.ASM_Code = '$sales'
	// 								 AND t1.Input_Date >= '$from'
	// 								 AND t1.Input_Date <= '$to'
	// 	                           ");	
	// 	return $getData;
	// 	$getData->free_result();
	// }
	
	// function get_pemol_spv($spv,$from,$to)
	// {
	//     $getData = $this->db->query("SELECT count(1) as total 
	// 	                             FROM data_pemol t1
	// 								 LEFT JOIN internal.data_sales_copy t2 
	// 								 ON t1.Sales_Code=t2.DSR_Code
	// 								 WHERE t2.SPV_Code = '$spv'
	// 								 AND t1.Input_Date >= '$from'
	// 								 AND t1.Input_Date <= '$to'
	// 	                           ");	
	// 	return $getData;
	// 	$getData->free_result();
	// }

}
