<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Pl_model extends CI_Model
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
	
	function breakdown_pl($sales_code, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as counter_pl,
			COALESCE(SUM(IF(Status_1='Approve' OR Status_1='Approved' OR Status_1='APPROVED' OR Status_1='APPROVE',1,0)),0) as approve_pl,
			COALESCE(SUM(IF(Status_1='CANCEL' OR Status_1='CANCELED',1,0)),0) as cancel_pl,
			COALESCE(SUM(IF(Status_1='DECLINED' OR Status_1='DECLINE' OR Status_1='REJECT' OR Status_1='REJECTED',1,0)),0) as decline_pl
			FROM `internal`.`apps_pl_result`
			WHERE (Sales_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (SPV_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (ASM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (RSM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (BSH_Code ='$sales_code' AND Group_Date = '$tgl')
		");
		return $query;
		$query->free_result();
	}

	function getBreakdownPl($sales_code, $posisi, $status, $tgl)
	{
		
		if($status == "DECLINED")
		{
			$filter = " AND Status_1 in('DECLINE', 'DECLINED', 'REJECTED', 'REJECT')";
		}elseif($status == "CANCEL")
		{
			$filter = " AND Status_1 = 'CANCEL'";
		}elseif($status == "APPROVED")
		{
			$filter = " AND Status_1 ='APPROVED'";
		}else
		{
			$filter = " AND Status_1 ='$status'";
		}
		$query = $this->db->query("SELECT *
									FROM `internal`.`apps_pl_result`
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