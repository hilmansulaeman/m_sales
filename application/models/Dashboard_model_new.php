<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	function getUpdatePassword($data, $sales_code)
	{
		$this->db->where('DSR_Code', $sales_code);
		$this->db->update('`internal`.`data_sales`', $data);
	}
	
	function UpdatePassword1($data, $sales_code)
	{
		$this->db->where('DSR_Code', $sales_code);
		$this->db->update('`db_user`.`user_employee`', $data);
	}

	function UpdatePassword($password, $id)
	{
		$data_request = array(
			'Password'	=> md5($password),
			'Password_Change'	=> 1,
			'Password_Reset' => 0
		);

		$this->db->where('Employee_ID', $id);
		$this->db->update('`db_user`.`user_employee`', $data_request);
	}
	
	// untuk update password
	// function UpdatePassword()
	// {
	// 	// API key
	// 	$apiKey = '4ad75498f665ec44c5b91e70c3cf6698';

	// 	// API auth credentials
	// 	$apiUser = "admindika";
	// 	$apiPass = "B3ndh1L2019";

	// 	// API URL
	// 	$url = 'https://rest-api.ptdika.com/api/user/change_password';
		
	// 	// Post data
	// 	$userData = array(
	// 		'password' => $this->input->post('password'),
	// 		'password_conf' => $this->input->post('retype_password'),
	// 		'id' => $this->input->post('user_id')
	// 	);

	// 	// Create a new cURL resource
	// 	$ch = curl_init($url);

	// 	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	// 	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
	// 	curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
	// 	curl_setopt($ch, CURLOPT_POST, 1);
	// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $userData);

	// 	$result = curl_exec($ch);

	// 	// Close cURL resource
	// 	curl_close($ch);
		
	// 	//$data = json_decode($result);
	// 	//return $data;
	// }

	function view($Sales_Code, $var_code, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.Apply_Card_Code in('2','4','7') AND a.Created_Date2 BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.MID_Type in('NEW', 'CUP') AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as pl,
			(SELECT COUNT(1) FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.Apply_Card_Code in('6') AND a.Created_Date2 BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as corp");
		return $query;
		$query->free_result();
	}
	
	function view_app($Sales_Code, $var_code, $tgl)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`application_process` a INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code WHERE 
			b.$var_code='$Sales_Code' AND a.Group_Date LIKE '%$tgl%' AND a.Status_1 in('APPROVED', 'Approved')) as app_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_result` a INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code WHERE 
			b.$var_code='$Sales_Code' AND a.Group_Date LIKE '%$tgl%' AND a.Status_1 IN('APPROVED', 'APPROVE', 'ACCEPTED', 'ACCEPT')) as app_edc,
			(SELECT COUNT(1) FROM `internal`.`sc_result` a INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code WHERE
			b.$var_code='$Sales_Code' AND Group_Date LIKE '%$tgl%' AND a.Status_1='APPROVED') as app_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl_result` a INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.Group_date LIKE '%$tgl%' AND a.Status_1 IN ('Approve','Approved','APPROVED','APPROVE')) as app_pl,
			(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` a INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.Group_Date LIKE '%$tgl%' AND a.Status='APPROVED') as app_corp");
		return $query;
		$query->free_result();
	}
	
	function get_poster2($where=''){
		if($where){
			$this->db->where($where);
		}
		$this->db->where('is_active','1');
		$query = $this->db->get('ref_program');
		return $query;
		$query->free_result();
	}
	
	function get_poster(){
		$position = $this->session->userdata('position');
		$product = $this->session->userdata('product');
		$channel = $this->session->userdata('channel');
		$channel_filter = array('DSR','SPV');
		
		$this->db->select("*");
		$this->db->from("ref_program");
		$this->db->where('is_active','1');
		if(in_array($position,$channel_filter)){
			$this->db->where("(CASE WHEN channel = 'All' THEN product = '$product' 
				ELSE product = '$product' AND channel = '$channel'
			END)");
		}
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}
}

/* End of file Dashboard_model.php */
/* Location: ./application/models/Dashboard_model.php */
	