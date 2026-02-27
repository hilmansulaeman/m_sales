<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Corp_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	function total($Sales_Code, $var_code, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT COUNT(1) AS corp FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code WHERE b.$var_code='$Sales_Code' AND a.Apply_Card_Code in('6') AND a.Created_Date2 BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'");
		return $query;
		$query->free_result();
	}

	function m_breakdown_corp($Sales_Code, $var_code, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
				SUM(IF(a.Status='SEND_BCA',1,0)) AS send_bca,
				SUM(IF(a.Status='SEND_DIKA',1,0)) AS send_dika,
				SUM(IF(a.Status='DUPLICATE' OR a.Status='DUPLICATE_DIKA' ,1,0)) AS duplicate_dika,
				SUM(IF(a.Status='RETURN_TO_SALES',1,0)) AS return_to_sales,
				SUM(IF(a.Status='RETURN_FROM_BCA',1,0)) AS return_from_bca,
				SUM(IF(a.Status='PENDING_FU',1,0)) AS pending_fu,
				SUM(IF(a.Status='PROJECT' OR a.Status='REJECT',1,0)) AS reject_by_dika,
				SUM(IF(a.Status='CANCEL',1,0)) AS cancel,
				SUM(IF(a.Status not in('SEND_BCA', 'SEND_DIKA', 'DUPLICATE', 'DUPLICATE_DIKA', 'RETURN_TO_SALES', 'RETURN_FROM_BCA', 'PENDING_FU', 'PROJECT', 'REJECT', 'CANCEL'),1,0)) AS others
				FROM `internal`.`applications` a 
				INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code 
				WHERE b.$var_code='$Sales_Code' AND a.Apply_Card_Code ='6' AND a.Created_Date2 BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59' ");
		return $query;
		$query->free_result();
	}

	function m_det_breakdown_corp($Sales_Code, $var_code, $tgl1, $tgl2, $status)
	{
		$filStatus="";
		if($status == "DUPLICATE")
		{
			$filStatus=" AND a.Status In('DUPLICATE', 'DUPLICATE_DIKA')";
		}
		elseif($status == "PROJECT")
		{
			$filStatus = " AND a.Status In('PROJECT', 'REJECT')";
		}
		elseif($status == "others")
		{
			$filStatus = " AND a.Status Not in('SEND_BCA', 'SEND_DIKA', 'DUPLICATE', 'DUPLICATE_DIKA', 'RETURN_TO_SALES', 'RETURN_FROM_BCA', 'PENDING_FU', 'PROJECT', 'REJECT', 'CANCEL')";
		}
		else
		{
			$filStatus = " AND a.Status='$status'";
		}
		
		$query = $this->db->query("SELECT a.Customer_Name, a.Created_Date2, b.Name, b.SPV_Name, b.ASM_Name, b.RSM_Name
				FROM `internal`.`applications` a 
				INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code
				WHERE b.$var_code='$Sales_Code' AND a.Apply_Card_Code='6' AND a.Created_Date2 BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59' $filStatus 
				ORDER BY b.SPV_Name ASC
				");
		return $query;
		$query->free_result();
	}
}