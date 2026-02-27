<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Edc_model extends CI_Model
{
	
	function __construct()
	{
		
	}
	
	function getCounter($sales_code, $posisi, $tgl)
	{
		$query = $this->db->query("SELECT (
			SELECT COUNT(1) FROM `internal`.`application_process` a LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code = b.DSR_Code 
			WHERE b.$posisi = '$sales_code' AND a.Group_Date LIKE '%$tgl%' AND a.Status_1 in('APPROVED', 'Approved', 'CANCEL', 'DECLINED')) AS cc, 
			(SELECT COUNT(1) FROM `internal`.`edc_result` a LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$posisi='$sales_code' AND Group_Date LIKE '%$tgl%' AND a.Status_1 in('ACCEPTED','CANCEL','REJECT','REJECTED')) as edc,
			(SELECT COUNT(1) FROM `internal`.`sc_result` a LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$posisi='$sales_code' AND Group_Date LIKE '%$tgl%') as sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl_result` a LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$posisi='$sales_code' AND Group_Date LIKE '%$tgl%') as pl,
			(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` a LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$posisi='$sales_code' AND Group_Date LIKE '%$tgl%') as corp");
			return $query;
			$query->free_result();
	}
	
	function breakdown_edc($sales_code, $tgl)
	{
		$query = $this->db->query("SELECT
			COUNT(1) as counter_edc,
			COALESCE(SUM(IF(Status_1='ACCEPTED' OR Status_1='ACCEPT',1,0)),0) as approve_edc,
			COALESCE(SUM(IF(Status_1='FASILITAS ADD_ON',1,0)),0) as approve_addon,
			COALESCE(SUM(IF(Status_1='CANCEL',1,0)),0) as cancel_edc,
			COALESCE(SUM(IF(Status_1='REJECT' OR Status_1='REJECTED',1,0)),0) as decline_edc
			FROM `internal`.`edc_result`
			WHERE Status_1 IN('ACCEPTED','CANCEL','REJECT','REJECTED','FASILITAS ADD_ON')
			AND ((Sales_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (SPV_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (ASM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (RSM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (BSH_Code ='$sales_code' AND Group_Date = '$tgl'))
		");
		return $query;
		$query->free_result();
	}
	
	function getBreakdownEdc($sales_code, $posisi, $status, $tgl)
	{
		if($status == "APPROVE")
		{
			$filter = " AND Status_1 in('ACCEPTED', 'ACCEPT')";
		}
		elseif($status == "ADDON")
		{
			$filter = " AND Status_1 = 'FASILITAS ADD_ON'";
		}
		elseif($status == "CANCEL")
		{
			$filter = " AND Status_1 = 'CANCEL'";
		}elseif($status == "DECLINE")
		{
			$filter = " AND Status_1 in('REJECT','REJECTED')";
		}else
		{
			$filter = "AND Status_1 = '$status'";
		}
		
		$query = $this->db->query("SELECT *
									FROM `internal`.`edc_result` 
									WHERE (Sales_Code ='$sales_code' AND Group_Date = '$tgl' $filter)
									OR (SPV_Code ='$sales_code' AND Group_Date = '$tgl' $filter)
									OR (ASM_Code ='$sales_code' AND Group_Date = '$tgl' $filter)
									OR (RSM_Code ='$sales_code' AND Group_Date = '$tgl' $filter)
									OR (BSH_Code ='$sales_code' AND Group_Date = '$tgl' $filter) 
								");
		return $query;
		$query->free_result();
	}
}