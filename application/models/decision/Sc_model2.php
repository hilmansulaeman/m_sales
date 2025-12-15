<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Sc_model extends CI_Model
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
}