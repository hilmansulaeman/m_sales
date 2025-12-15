<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class My_performance_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	function getDataCc($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as setoran_cc, 
			COALESCE(SUM(IF(a.Status='SEND_BCA',1,0)),0) as incoming_cc
			FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$field='$sales_code' AND a.Created_Date2 LIKE '%$tgl%' AND a.Apply_Card_Code in('2', '4')");
		return $query;
		$query->free_result();
	}
	
	function getDataEdc($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as setoran_edc, 
			COALESCE(SUM(IF(a.Status IN ('SUBMIT_TO_BCA','RESUBMIT_TO_BCA'),1,0)),0) as incoming_edc
			FROM `internal`.`edc_merchant` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$field='$sales_code' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tgl%'");
		return $query;
		$query->free_result();
	}
	
	function getDataSc($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as setoran_sc, 
			COALESCE(SUM(IF(a.Status='SEND_BCA',1,0)),0) as incoming_sc
			FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code 
			WHERE b.$field='$sales_code' AND a.Created_Date LIKE '%$tgl%' ");
		return $query;
		$query->free_result();
	}
	
	function getDataPl($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as setoran_pl, 
			COALESCE(SUM(IF(a.Status='SEND_BCA',1,0)),0) as incoming_pl
			FROM `internal`.`apps_pl` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code 
			WHERE b.$field='$sales_code' AND a.Created_Date LIKE '%$tgl%'");
		return $query;
		$query->free_result();
	}
	
	function getDataCorp($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as setoran_corp, 
			COALESCE(SUM(IF(a.Status='SEND_BCA',1,0)),0) as incoming_corp
			FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$field='$sales_code' AND a.Created_Date2 LIKE '%$tgl%' AND a.Apply_Card_Code in('6')");
		return $query;
		$query->free_result();
	}
	
	function getDataTele($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as setoran_tele, 
			COALESCE(SUM(IF(a.Status='SEND_BCA',1,0)),0) as incoming_tele,
			COALESCE(SUM(IF(a.Status='REJECT',1,0)),0) as reject_tele
			FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.$field='$sales_code' AND a.Created_Date2 LIKE '%$tgl%' AND a.Apply_Card_Code in('7')");
		return $query;
		$query->free_result();
	}
	
	function getAppCc($sales_code, $tgl)
	{
		$query = $this->db->query("SELECT 
			COALESCE(SUM(IF(Status_1='APPROVED' OR Status_1='APPROVE',1,0)),0) as app_cc,
			COALESCE(SUM(IF(Status_1='CANCEL',1,0)),0) as cancel_cc,
			COALESCE(SUM(IF(Status_1='DECLINED' OR Status_1='Declined',1,0)),0) as reject_cc
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
	
	function getAppEdc($sales_code, $tgl)
	{
		$query = $this->db->query("SELECT 
			COALESCE(SUM(IF(Status_1='ACCEPTED',1,0)),0) as app_edc,
			COALESCE(SUM(IF(Status_1='CANCEL',1,0)),0) as cancel_edc,
			COALESCE(SUM(IF(Status_1='REJECT' OR Status_1='REJECTED',1,0)),0) as reject_edc
			FROM `internal`.`edc_result`
			WHERE Product IN('EDC','EDC_QRIS')
			AND (Sales_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (SPV_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (ASM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (RSM_Code ='$sales_code' AND Group_Date = '$tgl')
			OR (BSH_Code ='$sales_code' AND Group_Date = '$tgl')
		");
		return $query;
		$query->free_result();
	}
	
	function getAppSc($sales_code, $tgl)
	{
		$query = $this->db->query("SELECT 
			COALESCE(SUM(IF(Status_1='APPROVED',1,0)),0) as app_sc,
			COALESCE(SUM(IF(Status_1='CANCEL',1,0)),0) as cancel_sc,
			COALESCE(SUM(IF(Status_1='DECLINED',1,0)),0) as reject_sc
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
	
	function getAppPl($sales_code, $tgl)
	{
		$query = $this->db->query("SELECT 
			COALESCE(SUM(IF(Status_1='APPROVED' OR Status_1='APPROVE',1,0)),0) as app_pl,
			COALESCE(SUM(IF(Status_1='CANCEL' OR Status_1='CANCELED',1,0)),0) as cancel_pl,
			COALESCE(SUM(IF(Status_1='REJECTED' OR Status_1='REJECT',1,0)),0) as reject_pl
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
	
	function getAppCorp($sales_code, $tgl)
	{
		$query = $this->db->query("SELECT 
			COALESCE(SUM(IF(status='APPROVED',1,0)),0) as app_corp,
			COALESCE(SUM(IF(status='CANCEL',1,0)),0) as cancel_corp,
			COALESCE(SUM(IF(status='REJECTED' or status='DECLINED',1,0)),0) as reject_corp
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
	
	//last month
	function ls_setoran($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT
			(SELECT  COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code in('2','4') AND tgl_input LIKE '%$tgl%') as cc,
			(SELECT COUNT(1) FROM `tbl_incoming_edc` WHERE $field='$sales_code' AND mid_type IN ('NEW', 'CUP') AND tgl_input LIKE '%$tgl%') as edc,
			(SELECT COUNT(1) FROM `tbl_incoming_sc` WHERE $field='$sales_code' AND tgl_input LIKE '%$tgl%') as sc,
			(SELECT COUNT(1) FROM `tbl_incoming_pl` WHERE $field='$sales_code' AND tgl_input LIKE '%$tgl%') as pl,
			(SELECT COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code='6' AND tgl_input LIKE '%$tgl%') as corp,
			(SELECT  COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code in('7') AND tgl_input LIKE '%$tgl%') as tele");
		return $query;
		$query->free_result();
	}
	
	//last month production
	
	function ls_setoran2($sales_code, $field, $tgl)
	{
		$query = $this->db->query("");
	}
	
	function ls_incoming($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT
			(SELECT  COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code in('2','4') AND tgl_input LIKE '%$tgl%' AND status='SEND_BCA') as ls_cc,
			(SELECT COUNT(1) FROM `tbl_incoming_edc` WHERE $field='$sales_code' AND mid_type IN ('NEW', 'CUP') AND tgl_input LIKE '%$tgl%' AND status='SUBMIT_TO_BCA') as ls_edc,
			(SELECT COUNT(1) FROM `tbl_incoming_sc` WHERE $field='$sales_code' AND tgl_input LIKE '%$tgl%' AND status='SEND_BCA') as ls_sc,
			(SELECT COUNT(1) FROM `tbl_incoming_pl` WHERE $field='$sales_code' AND tgl_input LIKE '%$tgl%' AND status='SEND_BCA') as ls_pl,
			(SELECT COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code='6' AND tgl_input LIKE '%$tgl%' AND status='SEND_BCA') as ls_corp,
			(SELECT  COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code in('7') AND tgl_input LIKE '%$tgl%' AND status='SEND_BCA') as ls_tele");
		return $query;
		$query->free_result();
	}
	
	function ls_app_can_rej($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `tbl_approve_cc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status IN('APPROVED', 'APPROVE')) as ls_app_cc,
			(SELECT COUNT(1) FROM `tbl_approve_cc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status = 'CANCEL') as ls_cancel_cc,
			(SELECT COUNT(1) FROM `tbl_approve_cc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status in('REJECT', 'REJECTED', 'DECLINE', 'DECLINED')) as ls_reject_cc,
			(SELECT COUNT(1) FROM `tbl_approve_edc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%'  AND status='ACCEPTED' AND mid_type LIKE '%NEW%' OR mid_type LIKE '%CUP%') as ls_app_edc,
			(SELECT COUNT(1) FROM `tbl_approve_edc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status='CANCEL' AND mid_type LIKE '%NEW%' OR mid_type LIKE '%CUP%') as ls_cancel_edc,
			(SELECT COUNT(1) FROM `tbl_approve_edc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status in('REJECT', 'REJECTED', 'DECLINE', 'DECLINED')) as ls_reject_edc,
			(SELECT COUNT(1) FROM `tbl_approval_sc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status IN ('APPROVE', 'APPROVED')) as ls_app_sc,
			(SELECT COUNT(1) FROM `tbl_approval_sc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status='CANCEL') as ls_cancel_sc,
			(SELECT COUNT(1) FROM `tbl_approval_sc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' and status IN('DECLINE', 'DECLINED', 'REJECT', 'REJECTED')) as ls_reject_sc,
			(SELECT COUNT(1) FROM `tbl_approval_pl` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status IN('APPROVE', 'APPROVED')) as ls_app_pl,
			(SELECT COUNT(1) FROM `tbl_approval_pl` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status='CANCEL') as ls_cancel_pl,
			(SELECT COUNT(1) FROM `tbl_approval_pl` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status IN('REJECT', 'REJECTED', 'DECLINE', 'DECLINED')) as ls_reject_pl,
			(SELECT COUNT(1) FROM `tbl_approve_corporate` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status in('APPROVE', 'APPROVED')) as ls_app_corp,
			(SELECT COUNT(1) FROM `tbl_approve_corporate` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status='CANCEL') as ls_cancel_corp,
			(SELECT COUNT(1) FROM `tbl_approve_corporate` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status IN ('DECLINE', 'DECLINED', 'REJECT', 'REJECTED')) as ls_reject_corp");
		return $query;
		$query->free_result();
	}
	
	//-2 month
	function scnd_setoran($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT
			(SELECT  COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code in('2','4') AND tgl_input LIKE '%$tgl%') as scnd_cc,
			(SELECT COUNT(1) FROM `tbl_incoming_edc` WHERE $field='$sales_code' AND mid_type IN ('NEW', 'CUP') AND tgl_input LIKE '%$tgl%') as scnd_edc,
			(SELECT COUNT(1) FROM `tbl_incoming_sc` WHERE $field='$sales_code' AND tgl_input LIKE '%$tgl%') as scnd_sc,
			(SELECT COUNT(1) FROM `tbl_incoming_pl` WHERE $field='$sales_code' AND tgl_input LIKE '%$tgl%') as scnd_pl,
			(SELECT COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code='6' AND tgl_input LIKE '%$tgl%') as scnd_corp,
			(SELECT  COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code in('7') AND tgl_input LIKE '%$tgl%') as scnd_tele");
		return $query;
		$query->free_result();
	}
	
	function scnd_incoming($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT
			(SELECT  COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code in('2','4') AND tgl_input LIKE '%$tgl%' AND status='SEND_BCA') as scnd_inc_cc,
			(SELECT COUNT(1) FROM `tbl_incoming_edc` WHERE $field='$sales_code' AND mid_type IN ('NEW', 'CUP') AND tgl_input LIKE '%$tgl%' AND status='SUBMIT_TO_BCA') as scnd_inc_edc,
			(SELECT COUNT(1) FROM `tbl_incoming_sc` WHERE $field='$sales_code' AND tgl_input LIKE '%$tgl%' AND status='SEND_BCA') as scnd_inc_sc,
			(SELECT COUNT(1) FROM `tbl_incoming_pl` WHERE $field='$sales_code' AND tgl_input LIKE '%$tgl%' AND status='SEND_BCA') as scnd_inc_pl,
			(SELECT COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code='6' AND tgl_input LIKE '%$tgl%' AND status='SEND_BCA') as scnd_inc_corp,
			(SELECT  COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code in('7') AND tgl_input LIKE '%$tgl%' AND status='SEND_BCA') as scnd_inc_tele");
		return $query;
		$query->free_result();
	}
	
	function scnd_app_can_rej($sales_code, $field, $tgl)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `tbl_approve_cc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status IN('APPROVED', 'APPROVE')) as scnd_app_cc,
			(SELECT COUNT(1) FROM `tbl_approve_cc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status = 'CANCEL') as scnd_cancel_cc,
			(SELECT COUNT(1) FROM `tbl_approve_cc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status in('REJECT', 'REJECTED', 'DECLINE', 'DECLINED')) as scnd_reject_cc,
			(SELECT COUNT(1) FROM `tbl_approve_edc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%'  AND status='ACCEPTED' AND mid_type LIKE '%NEW%' OR mid_type LIKE '%CUP%') as scnd_app_edc,
			(SELECT COUNT(1) FROM `tbl_approve_edc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status='CANCEL' AND mid_type LIKE '%NEW%' OR mid_type LIKE '%CUP%') as scnd_cancel_edc,
			(SELECT COUNT(1) FROM `tbl_approve_edc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status in('REJECT', 'REJECTED', 'DECLINE', 'DECLINED')) as scnd_reject_edc,
			(SELECT COUNT(1) FROM `tbl_approval_sc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status IN ('APPROVE', 'APPROVED')) as scnd_app_sc,
			(SELECT COUNT(1) FROM `tbl_approval_sc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status='CANCEL') as scnd_cancel_sc,
			(SELECT COUNT(1) FROM `tbl_approval_sc` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' and status IN('DECLINE', 'DECLINED', 'REJECT', 'REJECTED')) as scnd_reject_sc,
			(SELECT COUNT(1) FROM `tbl_approval_pl` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status IN('APPROVE', 'APPROVED')) as scnd_app_pl,
			(SELECT COUNT(1) FROM `tbl_approval_pl` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status='CANCEL') as scnd_cancel_pl,
			(SELECT COUNT(1) FROM `tbl_approval_pl` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status IN('REJECT', 'REJECTED', 'DECLINE', 'DECLINED')) as scnd_reject_pl,
			(SELECT COUNT(1) FROM `tbl_approve_corporate` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status in('APPROVE', 'APPROVED')) as scnd_app_corp,
			(SELECT COUNT(1) FROM `tbl_approve_corporate` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status='CANCEL') as scnd_cancel_corp,
			(SELECT COUNT(1) FROM `tbl_approve_corporate` WHERE $field='$sales_code' AND group_date LIKE '%$tgl%' AND status IN ('DECLINE', 'DECLINED', 'REJECT', 'REJECTED')) as scnd_reject_corp");
		return $query;
		$query->free_result();
	}
	
}