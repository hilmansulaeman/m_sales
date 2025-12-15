<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Edc_new_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	function total($Sales_Code, $var_code, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT COUNT(1) AS edc FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code WHERE b.$var_code='$Sales_Code' AND a.MID_Type in('NEW', 'CUP') AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'");
		return $query;
		$query->free_result();
	}

	function m_breakdown_edc($Sales_Code, $var_code, $tgl1, $tgl2)
		{
			$query = $this->db->query("SELECT 
					SUM(IF(a.Status='SUBMIT_TO_BCA',1,0)) AS submit_to_bca, 
					SUM(IF(a.Status='SUBMIT_TO_DIKA',1,0)) AS submit_to_dika, 
					SUM(IF(a.Status='RETURN_TO_SALES',1,0)) AS return_to_sales, 
					SUM(IF(a.Status='RETURN_TO_DIKA',1,0)) AS return_to_dika, 
					SUM(IF(a.Status='RETURN_FROM_BCA',1,0)) AS return_from_bca, 
					SUM(IF(a.Status='RESUBMIT_TO_BCA',1,0)) AS resubmit_to_bca, 
					SUM(IF(a.Status='REJECT' OR a.Status='PROJECT',1,0)) AS reject_by_dika, 
					SUM(IF(a.Status='CANCEL',1,0)) AS cancel, 
					SUM(IF(a.Status not in('SUBMIT_TO_BCA', 'SUBMIT_TO_DIKA', 'RETURN_TO_SALES', 'RETURN_TO_DIKA', 'RETURN_FROM_BCA', 'RESUBMIT_TO_BCA', 'REJECT', 'PROJECT', 'CANCEL'),1,0)) AS others 
					FROM `internal`.`edc_merchant` a 
					INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code 
					WHERE b.$var_code='$Sales_Code' AND a.Product_Type!='QRIS' AND a.MID_Type in('NEW', 'CUP') AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59' ");
			return $query;
			$query->free_result();
	}

	function m_det_breakdown_edc($Sales_Code, $var_code, $tgl1, $tgl2, $status)
		{
			if($status == "REJECT")
			{
				$filStatus = " AND a.Status in('REJECT', 'PROJECT')";
			}
			elseif($status == "others")
			{
				$filStatus = " AND a.Status Not in('SUBMIT_TO_BCA', 'SUBMIT_TO_DIKA', 'RETURN_TO_SALES', 'RETURN_TO_DIKA', 'RETURN_FROM_BCA', 'RESUBMIT_TO_BCA', 'REJECT', 'PROJECT', 'CANCEL')";
			}
			else
			{
				$filStatus = " AND a.Status='$status'";
			}
			$query = $this->db->query("SELECT a.Merchant_Name, a.Created_Date, b.Name, b.SPV_Name, b.ASM_Name, b.RSM_Name
					FROM `internal`.`edc_merchant` a 
					INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code 
					WHERE b.$var_code='$Sales_Code' AND a.Product_Type!='QRIS' AND a.MID_Type in('NEW', 'CUP') 
					AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59' $filStatus 
					ORDER BY b.SPV_Name ASC
					");

			return $query;
			$query->free_result();
	}
}