<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Corp_model extends CI_Model
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