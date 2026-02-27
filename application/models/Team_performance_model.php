<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Team_performance_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	function getRsm($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name FROM `internal`.`data_sales_structure` WHERE BSH_Code='$sales_code' AND Position='RSM' AND Status='ACTIVE'");
		return $query;
		$query->free_result();
	}
	
	function getAsm($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name FROM `internal`.`data_sales_structure` WHERE RSM_Code='$sales_code' AND Position='ASM' AND Status='ACTIVE'");
		return $query;
		$query->free_result();
	}
	
	function getSpv($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name FROM `internal`.`data_sales_structure` WHERE ASM_Code='$sales_code' AND Position='SPV' AND Status='ACTIVE'");
		return $query;
		$query->free_result();
	}
	
	function getDsr($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name FROM `internal`.`data_sales_structure` WHERE SPV_Code='$sales_code' AND Position in('SPG', 'SPB', 'DSR', 'RO', 'Mobile Sales', 'RO') AND Status='ACTIVE'");
		return $query;
		$query->free_result();
	}
	
	function profileSales($sales_code)
	{
		$query = $this->db->query("SELECT * FROM `internal`.`data_sales_structure` WHERE DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	//Performance
	function getDataCc($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as setoran_cc, 
			COALESCE(SUM(IF(a.Status='SEND_BCA',1,0)),0) as incoming_cc,
			COALESCE(SUM(IF(a.Status='REJECT',1,0)),0) as reject_cc
			FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$field='$sales_code' AND a.Created_Date2 LIKE '%$tgl%' AND a.Apply_Card_Code in('2', '4', '7')");
		return $query;
		$query->free_result();
	}
	function getDataEdc($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as setoran_edc, 
			COALESCE(SUM(IF(a.Status='SUBMIT_TO_BCA',1,0)),0) as incoming_edc, 
			COALESCE(SUM(IF(a.Status='REJECT',1,0)),0) as reject_edc 
			FROM `internal`.`edc_merchant` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$field='$sales_code' AND a.Created_Date LIKE '%$tgl%'");
		return $query;
		$query->free_result();
	}
	function getDataSc($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as setoran_sc, 
			COALESCE(SUM(IF(a.Status='SEND_BCA',1,0)),0) as incoming_sc, 
			COALESCE(SUM(IF(a.Status='REJECT',1,0)),0) as reject_sc 
			FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code 
			WHERE b.$field='$sales_code' AND a.Created_Date LIKE '%$tgl%' ");
		return $query;
		$query->free_result();
	}
	function getDataPl($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as setoran_pl, 
			COALESCE(SUM(IF(a.Status='SEND_BCA',1,0)),0) as incoming_pl, 
			COALESCE(SUM(IF(a.Status='REJECT',1,0)),0) as reject_pl 
			FROM `internal`.`apps_pl` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code 
			WHERE b.$field='$sales_code' AND a.Created_Date LIKE '%$tgl%'");
		return $query;
		$query->free_result();
	}
	function getDataCorp($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as setoran_corp, 
			COALESCE(SUM(IF(a.Status='SEND_BCA',1,0)),0) as incoming_corp,
			COALESCE(SUM(IF(a.Status='REJECT',1,0)),0) as reject_corp
			FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$field='$sales_code' AND a.Created_Date2 LIKE '%$tgl%' AND a.Apply_Card_Code in('6')");
		return $query;
		$query->free_result();
	}
	
	function getAppCc($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COALESCE(SUM(IF(Status_1='APPROVED',1,0)),0) as app_cc,
			COALESCE(SUM(IF(Status_1='CANCEL',1,0)),0) as cancel_cc,
			COALESCE(SUM(IF(Status_1='DECLINED',1,0)),0) as reject_cc
			FROM `internal`.`application_process`
			WHERE (Sales_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (SPV_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (ASM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (RSM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (BSH_Code ='$sales_code' AND Group_Date = '$tgl')
		");
		return $query;
		$query->free_result();
	}
	
	function getAppEdc($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COALESCE(SUM(IF(Status_1='ACCEPTED',1,0)),0) as app_edc,
			COALESCE(SUM(IF(Status_1='CANCEL',1,0)),0) as cancel_edc,
			COALESCE(SUM(IF(Status_1='REJECT' OR Status='REJECTED',1,0)),0) as reject_edc
			FROM `internal`.`edc_result`
			WHERE (Sales_Code = '$sales_code' AND Group_Date = '$tgl')
			OR (SPV_Code = '$sales_code' AND Group_Date = '$tgl')
			OR (ASM_Code = '$sales_code' AND Group_Date = '$tgl')
			OR (RSM_Code = '$sales_code' AND Group_Date = '$tgl')
			OR (BSH_Code = '$sales_code' AND Group_Date = '$tgl')
		");
		return $query;
		$query->free_result();
	}
	
	function getAppSc($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COALESCE(SUM(IF(Status_1='APPROVED',1,0)),0) as app_sc,
			COALESCE(SUM(IF(Status_1='CANCEL',1,0)),0) as cancel_sc,
			COALESCE(SUM(IF(Status='DECLINED',1,0)),0) as reject_sc
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
	
	function getAppPl($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COALESCE(SUM(IF(Status_1='APPROVED' Or Status='APPROVE',1,0)),0) as app_pl,
			COALESCE(SUM(IF(Status_1='CANCEL' OR Status='CANCELED',1,0)),0) as cancel_pl,
			COALESCE(SUM(IF(Status_1='REJECTED' Or Status='REJECT',1,0)),0) as reject_pl
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
	
	function getAppCorp($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COALESCE(SUM(IF(status='APPROVED' OR status='APPROVE' OR status='Approved',1,0)),0) as app_corp,
			COALESCE(SUM(IF(status='CANCEL',1,0)),0) as cancel_corp,
			COALESCE(SUM(IF(status='REJECTED' OR status='REJECT' OR status='DECLINED',1,0)),0) as reject_corp
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
	
	//End Performance
}