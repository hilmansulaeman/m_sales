<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Search_model extends CI_Model
{
	function __construct()
	{
		
	}
	
	function data_inc($sales_code, $field, $kategori, $cari)
	{
		if($kategori == "CC"){
			$query = $this->db->query("SELECT a.Customer_Name as cs_name, a.Status as status, a.Created_Date2 as dates FROM `internal`.`applications` a 
				LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
				WHERE b.$field='$sales_code' AND a.Customer_Name LIKE '%$cari%' AND a.Apply_Card_Code in('2', '4', '7') Order By a.Created_Date2 DESC");
			return $query;
			$query->free_result();
		}
		elseif($kategori == "EDC"){
			$query = $this->db->query("SELECT a.Merchant_Name as cs_name, a.Status as status, a.Created_Date as dates FROM `internal`.`edc_merchant` a
				LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
				WHERE b.$field='$sales_code' AND a.Merchant_Name LIKE '%$cari%' Order By a.Created_Date DESC");
			return $query;
			$query->free_result();
		}
		elseif($kategori == "SC"){
			$query = $this->db->query("SELECT a.Customer_Name as cs_name, a.Status as status, a.Submited_Date as dates FROM `internal`.`apps_sc` a 
				LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
				WHERE b.$field='$sales_code' AND a.Customer_Name LIKE '%$cari%' Order By a.Submited_Date DESC");
			return $query;
			$query->free_result();
		}
		elseif($kategori == "PL"){
			$query = $this->db->query("SELECT a.Customer_Name as cs_name a.Status as status, a.Submited_Date as dates FROM `internal`.`apps_pl` a
				LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
				WHERE b.$field='$sales_code' AND a.Customer_Name LIKE '%$cari%' Order By a.Submited_Date DESC");
			return $query;
			$query->free_result();
		}
		elseif($kategori == "CORP"){
			$query = $this->db->query("SELECT a.Customer_Name as cs_name, a.Status as status, a.Created_Date2 as dates FROM `internal`.`applications` a 
				LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
				WHERE b.$field='$sales_code' AND a.Customer_Name LIKE '%$cari%' AND a.Apply_Card_Code = '6' Order By a.Created_Date2 DESC");
			return $query;
			$query->free_result();
		}
	}
	
	function data_app($sales_code, $field, $kategori, $cari)
	{
		if($kategori == "CC"){
			$query = $this->db->query("SELECT a.Cust_Name as name, a.Status_1 as sts, a.Date_Result as tgl FROM `internal`.`application_process` a
				LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
				WHERE b.$field='$sales_code' AND a.Cust_Name LIKE '%$cari%' ORDER BY a.Date_Result DESC");
			return $query;
			$query->free_result();
		}
		elseif($kategori == "EDC"){
			$query = $this->db->query("SELECT a.Merchant_Name as name, a.Status_1 as sts, a.Date_AMH as tgl FROM `internal`.`edc_result` a
				LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
				WHERE b.$field='$sales_code' AND a.Merchant_Name LIKE '%$cari%' Order By a.Date_AMH DESC");
			return $query;
			$query->free_result();
		}
		elseif($kategori == "SC"){
			$query = $this->db->query("SELECT a.cust_name as name, a.status_1 as sts, a.date_result as tgl FROM `internal`.`sc_result` a
				LEFT JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
				WHERE b.$field='$sales_code' AND a.cust_name LIKE '%$cari%' Order by a.date_result DESC");
			return $query;
			$query->free_result();
		}
		elseif($kategori == "PL"){
			$query = $this->db->query("SELECT a.Debitur_Name as name, a.Status_1 as sts, a.Date_Result as tgl FROM `internal`.`apps_pl_result` a
				LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
				WHERE b.$field='$sales_code' AND a.Debitur_Name LIKE '%$cari%' Order By a.Date_Result DESC");
			return $query;
			$query->free_result();
		}
		elseif($kategori == "CORP"){
			$query = $this->db->query("SELECT a.customer_name as name, a.status as sts, a.date_result as tgl FROM `internal`.`corporate_ro_result` a
				LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
				WHERE b.$field='$sales_code' AND a.customer_name LIKE '%$cari%' Order By a.date_result DESC");
			return $query;
			$query->free_result();
		}
	}
}