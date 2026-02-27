<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Incoming_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	function total($Sales_Code, $var_code, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.Apply_Card_Code in('2','4') AND a.Created_Date2 BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.MID_Type in('NEW', 'CUP') AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as pl,
			(SELECT COUNT(1) FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.Apply_Card_Code in('6') AND a.Created_Date2 BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as corp,
			(SELECT COUNT(1) FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code
			WHERE b.$var_code='$Sales_Code' AND a.Apply_Card_Code in('7') AND a.Created_Date2 BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as tele");
		return $query;
		$query->free_result();
	}

	function m_breakdown_cc($Sales_Code, $var_code, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
				SUM(IF(a.Status='SEND_BCA',1,0)) AS send_bca,
				SUM(IF(a.Status='RESEND_BCA',1,0)) AS resend_bca,
				SUM(IF(a.Status='SEND_DIKA',1,0)) AS send_dika,
				SUM(IF(a.Status='DUPLICATE' OR a.Status='DUPLICATE_DIKA' ,1,0)) AS duplicate_dika,
				SUM(IF(a.Status='DUPLICATE_BCA',1,0)) AS duplicate_bca,
				SUM(IF(a.Status='DUPLICATE_HP',1,0)) AS duplicate_hp,
				SUM(IF(a.Status='RETURN_TO_SALES',1,0)) AS return_to_sales,
				SUM(IF(a.Status='RETURN_FROM_BCA',1,0)) AS return_from_bca,
				SUM(IF(a.Status='PENDING_FU',1,0)) AS pending_fu,
				SUM(IF(a.Status='PROJECT' OR a.Status='REJECT',1,0)) AS reject_by_dika,
				SUM(IF(a.Status='CANCEL',1,0)) AS cancel,
				SUM(IF(a.Status='RTS_SEND',1,0)) AS rts_send,
				SUM(IF(a.Status not in('SEND_BCA', 'RESEND_BCA', 'SEND_DIKA', 'DUPLICATE', 'DUPLICATE_DIKA', 'DUPLICATE_BCA', 'DUPLICATE_HP', 'RETURN_TO_SALES', 'RETURN_FROM_BCA', 'PENDING_FU', 'PROJECT', 'CANCEL', 'RTS_SEND'),1,0)) AS others
				FROM `internal`.`applications` a 
				INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code
				WHERE b.$var_code='$Sales_Code' AND a.Apply_Card_Code in('2','4') AND a.Created_Date2 BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'");
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

	function m_breakdown_sc($Sales_Code, $var_code, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT 
				SUM(IF(a.Status='SEND_BCA',1,0)) AS send_bca,
				SUM(IF(a.Status='SEND_DIKA',1,0)) AS send_dika,
				SUM(IF(a.Status='DUPLICATE',1,0)) AS duplicate,
				SUM(IF(a.Status='RETURN_TO_SALES',1,0)) AS return_to_sales,
				SUM(IF(a.Status='PROJECT' OR a.Status='REJECT',1,0)) AS reject_by_dika,
				SUM(IF(a.Status='CANCEL',1,0)) AS cancel,
				SUM(IF(a.Status not in('SEND_BCA', 'SEND_DIKA', 'DUPLICATE', 'RETURN_TO_SALES', 'PROJECT', 'REJECT', 'CANCEL'),1,0)) AS others
				FROM `internal`.`apps_sc` a 
				INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code 
				WHERE b.$var_code='$Sales_Code' AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'");
		return $query;
		$query->free_result();
	}

	function m_breakdown_pl($Sales_Code, $var_code, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
				SUM(IF(a.Status='SEND_BCA',1,0)) AS send_bca,
				SUM(IF(a.Status='SEND_DIKA',1,0)) AS send_dika,
				SUM(IF(a.Status='DUPLICATE',1,0)) AS duplicate,
				SUM(IF(a.Status='RETURN_TO_SALES',1,0)) AS return_to_sales,
				SUM(IF(a.Status='PROJECT' OR a.Status='REJECT',1,0)) AS reject_by_dika,
				SUM(IF(a.Status='CANCEL',1,0)) AS cancel,
				SUM(IF(a.Status not in('SEND_BCA', 'SEND_DIKA', 'DUPLICATE', 'RETURN_TO_SALES', 'PROJECT', 'REJECT', 'CANCEL'),1,0)) AS others
				FROM `internal`.`apps_pl` a 
				INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code 
				WHERE b.$var_code='$Sales_Code' AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'");
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

	function m_breakdown_tele($Sales_Code, $var_code, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
				SUM(IF(a.Status='SEND_BCA',1,0)) AS send_bca,
				SUM(IF(a.Status='RESEND_BCA',1,0)) AS resend_bca,
				SUM(IF(a.Status='SEND_DIKA',1,0)) AS send_dika,
				SUM(IF(a.Status='DUPLICATE' OR a.Status='DUPLICATE_DIKA' ,1,0)) AS duplicate_dika,
				SUM(IF(a.Status='DUPLICATE_BCA',1,0)) AS duplicate_bca,
				SUM(IF(a.Status='DUPLICATE_HP',1,0)) AS duplicate_hp,
				SUM(IF(a.Status='RETURN_TO_SALES',1,0)) AS return_to_sales,
				SUM(IF(a.Status='RETURN_FROM_BCA',1,0)) AS return_from_bca,
				SUM(IF(a.Status='PENDING_FU',1,0)) AS pending_fu,
				SUM(IF(a.Status='PROJECT' OR a.Status='REJECT',1,0)) AS reject_by_dika,
				SUM(IF(a.Status='CANCEL',1,0)) AS cancel,
				SUM(IF(a.Status not in('SEND_BCA', 'RESEND_BCA', 'SEND_DIKA', 'DUPLICATE', 'DUPLICATE_DIKA', 'DUPLICATE_BCA', 'DUPLICATE_HP', 'RETURN_TO_SALES', 'RETURN_FROM_BCA', 'PENDING_FU', 'PROJECT', 'REJECT', 'CANCEL'),1,0)) AS others
				FROM `internal`.`applications` a 
				INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code
				WHERE b.$var_code='$Sales_Code' AND a.Apply_Card_Code in('7') AND a.Created_Date2 BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'");
		return $query;
		$query->free_result();
	}
	
	function m_det_breakdown_cc($Sales_Code, $var_code, $tgl1, $tgl2, $status)
	{
		$filStatus="";
		if($status == "DUPLICATE")
		{
			$filStatus=" AND a.Status In('DUPLICATE', 'DUPLICATE_DIKA')";
		}
		elseif($status == "PROJECT")
		{
			$filStatus = " AND a.Status In('PROJECT','REJECT')";
		}
		elseif($status == "others")
		{
			$filStatus = " AND a.Status Not in('SEND_BCA', 'RESEND_BCA', 'SEND_DIKA', 'DUPLICATE', 'DUPLICATE_DIKA', 'DUPLICATE_BCA', 'DUPLICATE_HP', 'RETURN_TO_SALES', 'RETURN_FROM_BCA', 'PENDING_FU', 'PROJECT', 'CANCEL', 'RTS_SEND')";
		}
		else
		{
			$filStatus = " AND a.Status='$status'";
		}
		$query = $this->db->query("SELECT 
										a.Customer_Name, a.Submited_Date, a.Created_Date2, b.Name, b.SPV_Name, b.ASM_Name, b.RSM_Name
									FROM `internal`.`applications` a 
									INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code
									WHERE b.$var_code='$Sales_Code' 
									AND a.Apply_Card_Code in('2','4') $filStatus 
									AND a.Created_Date2 BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'
									ORDER BY b.SPV_Name ASC
								 ");
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

	function m_det_breakdown_sc($Sales_Code, $var_code, $tgl1, $tgl2, $status)
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
			$filStatus = " AND a.Status Not in('SEND_BCA', 'SEND_DIKA', 'DUPLICATE', 'RETURN_TO_SALES', 'PROJECT', 'REJECT', 'CANCEL')";
		}
		else
		{
			$filStatus = " AND a.Status='$status'";
		}

		$query = $this->db->query("SELECT a.Customer_Name, a.Created_Date, b.Name, b.SPV_Name, b.ASM_Name, b.RSM_Name
				FROM `internal`.`apps_sc` a 
				INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code 
				WHERE b.$var_code='$Sales_Code' AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59' $filStatus 
				ORDER BY b.SPV_Name ASC
				");
		return $query;
		$query->free_result();
	}
	
	function m_det_breakdown_pl($Sales_Code, $var_code, $tgl1, $tgl2, $status)
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
			$filStatus = " AND a.Status Not in('SEND_BCA', 'SEND_DIKA', 'DUPLICATE', 'RETURN_TO_SALES', 'PROJECT', 'REJECT', 'CANCEL')";
		}
		else
		{
			$filStatus = " AND a.Status='$status'";
		}
		
		$query = $this->db->query("SELECT a.Customer_Name, a.Created_Date, b.Name, b.SPV_Name, b.ASM_Name, b.RSM_Name
				FROM `internal`.`apps_pl` a 
				INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code 
				WHERE b.$var_code='$Sales_Code' AND a.Created_Date BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59' $filStatus 
				ORDER BY b.SPV_Name ASC
				");
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
	
	function m_det_breakdown_tele($Sales_Code, $var_code, $tgl1, $tgl2, $status)
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
			$filStatus = " AND a.Status Not in('SEND_BCA', 'RESEND_BCA', 'SEND_DIKA', 'DUPLICATE', 'DUPLICATE_DIKA', 'DUPLICATE_BCA', 'DUPLICATE_HP', 'RETURN_TO_SALES', 'RETURN_FROM_BCA', 'PENDING_FU', 'PROJECT', 'REJECT', 'CANCEL')";
		}
		else
		{
			$filStatus = " AND a.Status='$status'";
		}
		
		$query = $this->db->query("SELECT a.Customer_Name, a.Created_Date2, b.Name, b.SPV_Name, b.ASM_Name, b.RSM_Name
				FROM `internal`.`applications` a 
				INNER JOIN `internal`.`data_sales_copy` b ON a.sales_code=b.DSR_Code
				WHERE b.$var_code='$Sales_Code' AND a.Apply_Card_Code='7' AND a.Created_Date2 BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59' $filStatus 
				ORDER BY b.SPV_Name ASC
				");
		return $query;
		$query->free_result();
	}
	
	
	
}