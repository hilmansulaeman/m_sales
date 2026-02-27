<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Decision_model extends CI_Model
{
	
	function __construct()
	{
		
	}
	
	function getCounter_old($sales_code, $posisi, $tgl)
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
	
	function getCounter($sales_code, $posisi, $tgl)
	{
		$query = $this->db->query("SELECT (
			SELECT COUNT(1) FROM `internal`.`application_process`
			WHERE $posisi = '$sales_code' AND Group_Date LIKE '%$tgl%' AND Status_1 IN('APPROVED', 'Approved', 'CANCEL', 'DECLINED')) AS cc, 
			(SELECT COUNT(1) FROM `internal`.`edc_result`
			WHERE $posisi = '$sales_code' AND Group_Date LIKE '%$tgl%' AND Status_1 IN('ACCEPTED','CANCEL','REJECT','REJECTED') AND Product IN('EDC','EDC_QRIS')) as edc,
			(SELECT COUNT(1) FROM `internal`.`sc_result`
			WHERE $posisi = '$sales_code' AND Group_Date LIKE '%$tgl%') as sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl_result`
			WHERE $posisi = '$sales_code' AND Group_Date LIKE '%$tgl%') as pl,
			(SELECT COUNT(1) FROM `internal`.`corporate_ro_result`
			WHERE $posisi = '$sales_code' AND Group_Date LIKE '%$tgl%') as corp
		");
		return $query;
		$query->free_result();
	}
	
	function breakdown_cc2($sales_code, $posisi, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as count_app_cc,
			COALESCE(SUM(IF(a.Status_1='APPROVED' or a.Status_1='Approved',1,0)),0) as approve_cc,
			COALESCE(SUM(IF(a.Status_1='CANCEL',1,0)),0) as cancel_cc,
			COALESCE(SUM(IF(a.Status_1='DECLINED',1,0)),0) as decline_cc
			FROM `internal`.`application_process` a LEFT JOIN `internal`.`data_sales_structure` b ON a.Sales_Code = b.DSR_Code 
			WHERE b.$posisi='$sales_code' AND a.Group_Date LIKE '%$tgl%'");
		return $query;
		$query->free_result();
	}
	
	function breakdown_cc($sales_code, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as count_app_cc,
			COALESCE(SUM(IF(Status_1='APPROVED' or Status_1='Approved',1,0)),0) as approve_cc,
			COALESCE(SUM(IF(Status_1='CANCEL',1,0)),0) as cancel_cc,
			COALESCE(SUM(IF(Status_1='DECLINED',1,0)),0) as decline_cc
			FROM `internal`.`application_process`
			WHERE Status_1 IN('APPROVED','CANCEL','DECLINED')
			AND ((Sales_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (SPV_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (ASM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (RSM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (BSH_Code ='$sales_code' AND Group_Date = '$tgl'))
		");
		return $query;
		$query->free_result();
	}
	
	function breakdown_edc_old($sales_code, $tgl)
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
	
	function breakdown_edc($sales_code, $tgl)
	{
		$query = $this->db->query("SELECT
			COUNT(1) as counter_edc,
			COALESCE(SUM(IF(Status_1='ACCEPTED' OR Status_1='ACCEPT',1,0)),0) as approve_edc,
			COALESCE(SUM(IF(Status_1='FASILITAS ADD_ON',1,0)),0) as approve_addon,
			COALESCE(SUM(IF(Status_1='CANCEL',1,0)),0) as cancel_edc,
			COALESCE(SUM(IF(Status_1='REJECT' OR Status_1='REJECTED',1,0)),0) as decline_edc
			FROM `internal`.`edc_result`
			WHERE Product IN ('EDC','EDC_QRIS')
			AND Status_1 IN('ACCEPTED','CANCEL','REJECT','REJECTED','FASILITAS ADD_ON')
			AND ((Sales_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (SPV_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (ASM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (RSM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (BSH_Code ='$sales_code' AND Group_Date = '$tgl'))
		");
		return $query;
		$query->free_result();
	}
	
	function breakdown_sc($sales_code, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as counter_sc,
			COALESCE(SUM(IF(Status_1='APPROVED',1,0)),0) as approve_sc,
			COALESCE(SUM(IF(Status_1='CANCEL',1,0)),0) as cancel_sc,
			COALESCE(SUM(IF(Status_1='DECLINED',1,0)),0) as decline_sc
			FROM `internal`.`sc_result`
			WHERE (Sales_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (SPV_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (ASM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (RSM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (BSH_Code ='$sales_code' AND Group_Date = '$tgl')
		");
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
	
	function breakdown_corp($sales_code, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as counter_corp,
			COALESCE(SUM(IF(Status='APPROVED',1,0)),0) as approve_corp,
			COALESCE(SUM(IF(Status='CANCEL',1,0)),0) as cancel_corp,
			COALESCE(SUM(IF(Status='DECLINED',1,0)),0) as decline_corp
			FROM `internal`.`corporate_ro_result`
			WHERE (Sales_Code ='$sales_code' AND group_date = '$tgl')
			OR (SPV_Code ='$sales_code' AND group_date = '$tgl')
			OR (ASM_Code ='$sales_code' AND group_date = '$tgl')
			OR (RSM_Code ='$sales_code' AND group_date = '$tgl')
			OR (BSH_Code ='$sales_code' AND group_date = '$tgl')
		");
		return $query;
		$query->free_result();
	}
	
	function getBreakdownCc($sales_code, $posisi, $status, $tgl)
	{
		if($status == "APPROVE")
		{
			$filter = " AND Status_1 in ('APPROVED', 'Approved')";
		}
		elseif($status == "CANCEL")
		{
			$filter = " AND Status_1 = 'CANCEL'";
		}elseif($status == "DECLINE")
		{
			$filter = " AND Status_1 = 'DECLINED'";
		}else
		{
			$filter = " AND Status_1 = '$status'";
		}
		
		$query = $this->db->query("SELECT * 
		                           FROM `internal`.`application_process`
								   WHERE (Sales_Code ='$sales_code' AND Group_Date = '$tgl' $filter)
								   OR (SPV_Code ='$sales_code' AND Group_Date = '$tgl' $filter)
								   OR (ASM_Code ='$sales_code' AND Group_Date = '$tgl' $filter)
								   OR (RSM_Code ='$sales_code' AND Group_Date = '$tgl' $filter)
								   OR (BSH_Code ='$sales_code' AND Group_Date = '$tgl' $filter) 
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
	
	function getBreakdownSc($sales_code, $posisi, $status, $tgl)
	{
		$query = $this->db->query("SELECT *
		                           FROM `internal`.`sc_result`
								   WHERE (Sales_Code ='$sales_code' AND Group_Date = '$tgl' AND Status_1 = '$status')
								   OR (SPV_Code ='$sales_code' AND Group_Date = '$tgl' AND Status_1 = '$status')
								   OR (ASM_Code ='$sales_code' AND Group_Date = '$tgl' AND Status_1 = '$status')
								   OR (RSM_Code ='$sales_code' AND Group_Date = '$tgl' AND Status_1 = '$status')
								   OR (BSH_Code ='$sales_code' AND Group_Date = '$tgl' AND Status_1 = '$status') 
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
	
	function getBreakdownCorp($sales_code, $posisi, $status, $tgl)
	{
		$query = $this->db->query("SELECT *
			                       FROM `internal`.`corporate_ro_result`
			                       WHERE (Sales_Code ='$sales_code' AND group_date = '$tgl' AND status = '$status')
								   OR (SPV_Code ='$sales_code' AND group_date = '$tgl' AND status = '$status')
								   OR (ASM_Code ='$sales_code' AND group_date = '$tgl' AND status = '$status')
								   OR (RSM_Code ='$sales_code' AND group_date = '$tgl' AND status = '$status')
								   OR (BSH_Code ='$sales_code' AND group_date = '$tgl' AND status = '$status') 
								 ");
		return $query;
		$query->free_result();
	}
}