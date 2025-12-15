<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Rsm_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getAsmAktual($sales_code)
	{
		$query = $this->db->query("SELECT * FROM `internal`.`data_sales_copy` 
		                    WHERE RSM_Code='$sales_code' 
							AND Position='ASM' 
							AND Status='ACTIVE'");
		return $query;
		$query->free_result();
	}
	
	function getApsTarget($level, $position)
	{
		$query = $this->db->query("SELECT target FROM `internal_sms`.`tbl_targets` 
		                    WHERE level='$level' 
							AND position='$position'");
		return $query;
		$query->free_result();
	}
	
	function getApsAkt($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
				(SELECT COUNT(1) FROM `internal`.`applications` b
				INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code
				WHERE c.RSM_Code=a.DSR_Code AND b.Status='SEND_BCA' AND b.Apply_Card_Code IN('2','4','7') AND b.Created_Date2 LIKE '%$tanggal%') as cc,
				(SELECT COUNT(1) FROM `internal`.`edc_merchant` b
				INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code
				WHERE c.RSM_Code=a.DSR_Code AND b.Status IN('SUBMIT_TO_BCA','RESUBMIT_TO_BCA') AND b.MID_Type = 'NEW' AND b.Created_Date LIKE '%$tanggal%') as edc,
				(SELECT COUNT(1) FROM `internal`.`apps_sc` b
				INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code
				WHERE c.RSM_Code=a.DSR_Code AND b.Status = 'SEND_BCA' AND b.Created_Date LIKE '%$tanggal%') as sc,
				(SELECT COUNT(1) FROM `internal`.`apps_pl` b
				INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code
				WHERE c.RSM_Code=a.DSR_Code AND b.Status = 'SEND_BCA' AND b.Created_Date LIKE '%$tanggal%') as pl,
				(SELECT COUNT(1) FROM `internal`.`applications` b
				INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code
				WHERE c.RSM_Code=a.DSR_Code AND b.Status='SEND_BCA' AND b.Apply_Card_Code = '6' AND b.Created_Date2 LIKE '%$tanggal%') as corp
			FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getApsRts($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
				(SELECT COUNT(1) FROM `internal`.`applications` b
				INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_code=c.DSR_Code
				WHERE c.RSM_Code=a.DSR_Code AND b.Status='RETURN_TO_SALES' AND b.Apply_Card_Code IN('2','4','7') AND b.Created_Date2 LIKE '%$tanggal%') as rts_cc,
				(SELECT COUNT(1) FROM `internal`.`edc_merchant` b
				INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_code=c.DSR_Code
				WHERE c.RSM_Code=a.DSR_Code AND b.Status ='RETURN_TO_SALES' AND b.MID_Type = 'NEW' AND b.Created_Date LIKE '%$tanggal%') as rts_edc,
				(SELECT COUNT(1) FROM `internal`.`apps_sc` b
				INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_code=c.DSR_Code
				WHERE c.RSM_Code=a.DSR_Code AND b.Status = 'RETURN_TO_SALES' AND b.Created_Date LIKE '%$tanggal%') as rts_sc,
				(SELECT COUNT(1) FROM `internal`.`apps_pl` b
				INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_code=c.DSR_Code
				WHERE c.RSM_Code=a.DSR_Code AND b.Status = 'RETURN_TO_SALES' AND b.Created_Date LIKE '%$tanggal%') as rts_pl,
				(SELECT COUNT(1) FROM `internal`.`applications` b
				INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_code=c.DSR_Code
				WHERE c.RSM_Code=a.DSR_Code AND b.Status='RETURN_TO_SALES' AND b.Apply_Card_Code = '6' AND b.Created_Date2 LIKE '%$tanggal%') as rts_corp
			FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getUnderPerform($sales_code)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_rating_sales` WHERE RSM_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getApproveAkt2($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
		(SELECT COUNT(1) FROM `internal`.`application_process` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.RSM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='Approved') as app_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.RSM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status IN('ACCEPTED', 'APPROVED')) as app_edc,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.RSM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='Approved') as app_sc,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.RSM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='APPROVED') as app_corp
		FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getApproveAkt($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
		(SELECT COUNT(1) FROM `internal`.`application_process` b
		WHERE b.RSM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status_1='APPROVED') as app_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_result` b
		WHERE b.RSM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status_1='ACCEPTED') as app_edc,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		WHERE b.rsm_code=a.DSR_Code AND b.group_date LIKE '%$tanggal%' AND b.status_1='APPROVED') as app_sc,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		WHERE b.RSM_Code=a.DSR_Code AND b.group_date LIKE '%$tanggal%' AND b.status='APPROVED') as app_corp
		FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getAppRejectAkt2($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
		(SELECT COUNT(1) FROM `internal`.`application_process` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.RSM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='DECLINED') as rej_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.RSM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status IN('REJECT', 'REJECTED')) as rej_edc,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.RSM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='DECLINED') as rej_sc,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.RSM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='DECLINED') as rej_corp
		FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getAppRejectAkt($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
		(SELECT COUNT(1) FROM `internal`.`application_process` b
		WHERE b.RSM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status_1='DECLINED') as rej_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_result` b
		WHERE b.RSM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status_1 IN('REJECT', 'REJECTED')) as rej_edc,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		WHERE b.rsm_code=a.DSR_Code AND b.group_date LIKE '%$tanggal%' AND b.status_1='DECLINED') as rej_sc,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		WHERE b.RSM_Code=a.DSR_Code AND b.group_date LIKE '%$tanggal%' AND b.status='DECLINED') as rej_corp
		FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getApproveNas2($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
		(SELECT COUNT(1) FROM `internal`.`application_process` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status='Approved') as app_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status IN('ACCEPTED', 'APPROVED')) as app_edc,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status='Approved') as app_sc,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status='APPROVED') as app_corp
		FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getApproveNas($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
		(SELECT COUNT(1) FROM `internal`.`application_process` b
		WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status_1='APPROVED') as app_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_result` b
		WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status_1='ACCEPTED') as app_edc,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		WHERE b.group_date LIKE '%$tanggal%' AND b.status_1='APPROVED') as app_sc,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		WHERE b.group_date LIKE '%$tanggal%' AND b.status='APPROVED') as app_corp
		FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getRejNas2($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
		(SELECT COUNT(1) FROM `internal`.`application_process` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status='DECLINED') as rej_cc_nas,
		(SELECT COUNT(1) FROM `internal`.`edc_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status IN('REJECT', 'REJECTED')) as rej_edc_nas,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status='DECLINED') as rej_sc_nas,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status='DECLINED') as rej_corp_nas
		FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getRejNas($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
		(SELECT COUNT(1) FROM `internal`.`application_process` b
		WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status_1='DECLINED') as rej_cc_nas,
		(SELECT COUNT(1) FROM `internal`.`edc_result` b
		WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status_1 IN('REJECT', 'REJECTED')) as rej_edc_nas,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		WHERE b.group_date LIKE '%$tanggal%' AND b.status_1='DECLINED') as rej_sc_nas,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		WHERE b.group_date LIKE '%$tanggal%' AND b.status='DECLINED') as rej_corp_nas
		FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}

	//Know here

	function getCountDsr($sales_code)
	{
		$query = $this->db->query("SELECT
			SUM(IF(Position='DSR' OR Position='SPG' OR Position='SPB',1,0)) as dsr,
			SUM(IF(Position='SPV',1,0)) as spv,
			SUM(IF(Position='ASM',1,0)) as asm
			FROM `internal`.`data_sales_copy` WHERE RSM_Code='$sales_code' AND Status='ACTIVE'");
		return $query;
		$query->free_result();
	}

	function getAllTarget($level, $position, $product)
	{
		$query = $this->db->query("SELECT target FROM `internal_sms`.`tbl_targets` WHERE level='$level' AND position='$position' AND product='$product'");
		return $query;
		$query->free_result();
	}

	function getAplikasiAktual($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code IN('2','4','7')) AS cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status IN('SUBMIT_TO_BCA','RESUBMIT_TO_BCA') AND ms.MID_type='NEW') AS edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA') AS sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA') AS pl,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code='6') AS corp
			FROM `internal`.`data_sales_copy` a
			WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getAplikasiAktualNew($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_rsm` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}
	
	function getAplikasiRts($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code IN('2','4','7')) AS cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.MID_type='NEW') AS edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES') AS sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES') AS pl,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code='6') AS corp
			FROM `internal`.`data_sales_copy` a
			WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}

	function getAllUnderPerf($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT *, b.product, (SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE ASM_Code=b.DSR_Code AND Position='SPV' AND Status='ACTIVE') as totalnya FROM `internal_sms`.`tbl_summary_apps_asm` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.rating LIKE '%Under Perform%' OR a.rating LIKE '%Zero Account%' AND a.Periode LIKE '%$tanggal%'");
		// echo "SELECT *, b.product, (SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE ASM_Code=b.DSR_Code AND Position='SPV' AND Status='ACTIVE') as totalnya FROM `internal_sms`.`tbl_summary_apps_asm` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.rating LIKE '%Under Perform%' OR a.rating LIKE '%Zero Account%' AND a.Periode LIKE '%$tanggal%'";
		//echo "SELECT *, b.product FROM `internal_sms`.`tbl_summary_apps_asm` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.rating LIKE '%Under Perform%' AND a.Periode LIKE '%$tanggal%'";
		return $query;
		$query->free_result();
	}

	function getAllAsmAktual($sales_code)
	{
		$query = $this->db->query("SELECT a.DSR_Code as ASM_Code, a.Name as ASM_Name, a.Product, a.Efektif_Date, a.Join_Date, (SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE ASM_Code=a.DSR_Code AND Position='SPV' AND Status='ACTIVE') as totalnya FROM `internal`.`data_sales_copy` a WHERE a.RSM_Code='$sales_code' AND a.Position='ASM' AND a.Status='ACTIVE' Order By a.Name ASC");
		return $query;
		$query->free_result();
	}

	function getAllSpvAktual($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code as ASM_Code, Name as ASM_Name, Join_Date, Efektif_Date FROM `internal`.`data_sales_copy` WHERE RSM_Code='$sales_code' AND Position='ASM' AND Status='ACTIVE' Order By Name ASC");
		return $query;
		$query->free_result();
	}

	function getAllApps($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
			(SELECT COUNT(1) FROM `internal`.`applications_temp` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code IN('2','4','7')) AS inc_cc,
            (SELECT COUNT(1) FROM `internal`.`applications_temp` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code IN('2','4','7')) AS rts_cc,
            (SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status IN('SUBMIT_TO_BCA','RESUBMIT_TO_BCA') AND ms.MID_type='NEW') AS inc_edc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.MID_type='NEW') AS rts_edc,
            (SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA') AS inc_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES') AS rts_sc,
            (SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA') AS inc_pl,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES') AS rts_pl,
            (SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code='6') AS inc_corp,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code='6') AS rts_corp
			FROM `internal`.`data_sales_copy` a
			WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}

	function getAllAppr($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
				SUM(approve) as app,
				SUM(reject) as rej,
				Rating
				FROM `internal_sms`.`tbl_rating_sales` WHERE ASM_Code='$sales_code' AND periode='$tanggal'");
		return $query;
		$query->free_result();
	}

	function getAllApprSpv($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
				SUM(approve) as app,
				SUM(reject) as rej,
				Rating
				FROM `internal_sms`.`tbl_rating_sales` WHERE SPV_Code='$sales_code' AND periode='$tanggal'");
		return $query;
		$query->free_result();
	}

	function getSpvData($sales_code)
	{
		$query = $this->db->query("SELECT * FROM `internal`.`data_sales_copy` WHERE RSM_Code='$sales_code' AND Status='ACTIVE' AND Position='SPV' ORDER BY Name ASC");
		return $query;
		$query->free_result();
	}

	function getApssBySpv($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code IN('2','4','7')) AS inc_cc,
            (SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code IN('2','4','7')) AS rts_cc,
            (SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status IN('SUBMIT_TO_BCA','RESUBMIT_TO_BCA') AND ms.MID_type='NEW') AS inc_edc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.MID_type='NEW') AS rts_edc,
            (SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA') AS inc_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES') AS rts_sc,
            (SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA') AS inc_pl,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES') AS rts_pl,
            (SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code='6') AS inc_corp,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code='6') AS rts_corp
			FROM `internal`.`data_sales_copy` a
			WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}

	function getApssBySpv2($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code IN('2','4','7')) AS inc_cc,
            (SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code IN('2','4','7')) AS rts_cc,
            (SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status IN('SUBMIT_TO_BCA','RESUBMIT_TO_BCA') AND ms.MID_type='NEW') AS inc_edc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.MID_type='NEW') AS rts_edc,
            (SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA') AS inc_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES') AS rts_sc,
            (SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA') AS inc_pl,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES') AS rts_pl,
            (SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code='6') AS inc_corp,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.SPV_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code='6') AS rts_corp
			FROM `internal`.`data_sales_copy` a
			WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}

	function getDmSpv($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code='$sales_code' AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code IN('2','4','7') AND x.SPV_Name LIKE '%DUMMY SPV%') AS inc_cc,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code='$sales_code' AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code IN('2','4','7') AND x.SPV_Name LIKE '%DUMMY SPV%') AS rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code='$sales_code' AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status IN('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA') AND ms.MID_Type ='NEW' AND x.SPV_Name LIKE '%DUMMY SPV%') AS inc_edc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code='$sales_code' AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES' AND ms.MID_Type ='NEW' AND x.SPV_Name LIKE '%DUMMY SPV%') AS rts_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code='$sales_code' AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA' AND x.SPV_Name LIKE '%DUMMY SPV%') AS inc_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code='$sales_code' AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES' AND x.SPV_Name LIKE '%DUMMY SPV%') AS rts_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code='$sales_code' AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA' AND x.SPV_Name LIKE '%DUMMY SPV%') AS inc_pl,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code='$sales_code' AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES' AND x.SPV_Name LIKE '%DUMMY SPV%') AS rts_pl,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code='$sales_code' AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code ='6' AND x.SPV_Name LIKE '%DUMMY SPV%') AS inc_corp,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code='$sales_code' AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code ='6' AND x.SPV_Name LIKE '%DUMMY SPV%') AS rts_corp");
		return $query;
		$query->free_result();
	}

	function getApprSpv($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT sum()");
	}

	function getDmData($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code IN('2','4','7') AND x.ASM_Name LIKE '%DUMMY_ASM%') AS inc_cc,
            (SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code IN('2','4','7') AND x.ASM_Name LIKE '%DUMMY_ASM%') AS rts_cc,
            (SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status IN('SUBMIT_TO_BCA','RESUBMIT_TO_BCA') AND ms.MID_type='NEW' AND x.ASM_Name LIKE '%DUMMY_ASM%') AS inc_edc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.MID_type='NEW' AND x.ASM_Name LIKE '%DUMMY_ASM%') AS rts_edc,
            (SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA' AND x.ASM_Name LIKE '%DUMMY_ASM%') AS inc_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES' AND x.ASM_Name LIKE '%DUMMY_ASM%') AS rts_sc,
            (SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA' AND x.ASM_Name LIKE '%DUMMY_ASM%') AS inc_pl,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES' AND x.ASM_Name LIKE '%DUMMY_ASM%') AS rts_pl,
            (SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code='6' AND x.ASM_Name LIKE '%DUMMY_ASM%') AS inc_corp,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code='6' AND x.ASM_Name LIKE '%DUMMY_ASM%') AS rts_corp
			FROM `internal`.`data_sales_copy` a
			WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}

	function getDsrData($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name, Product, SPV_Code, SPV_Name FROM `internal`.`data_sales_copy` WHERE RSM_Code='$sales_code' AND Status='ACTIVE' AND Position IN('DSR','SPG','SPB') ORDER BY Name ASC");
		return $query;
		$query->free_result();
	}

	function getApsByDsr($sales_code, $product, $tanggal)
	{
		if($product == "CC")
		{
			$query = $this->db->query("SELECT
				SUM(IF(Status='SEND_BCA',1,0)) as inc,
				SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
				FROM `internal`.`applications` WHERE Sales_Code='$sales_code' AND Created_Date2 LIKE '%$tanggal%' AND Apply_Card_Code IN('2','4','7')");
			return $query;
			$query->free_result();
		}elseif($product == "EDC")
		{
			$query = $this->db->query("SELECT
				SUM(IF(Status='SUBMIT_TO_BCA' OR Status='RESUBMIT_TO_BCA',1,0)) as inc,
				SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
				FROM `internal`.`edc_merchant` WHERE Sales_Code='$sales_code' AND Created_Date LIKE '%$tanggal%' AND MID_Type='NEW'");
			return $query;
			$query->free_result();
		}elseif($product == "SC")
		{
			$query = $this->db->query("SELECT
				SUM(IF(Status='SEND_BCA',1,0)) as inc,
				SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
				FROM `internal`.`apps_sc` WHERE Sales_Code='$sales_code' AND Created_Date LIKE '%$tanggal%'");
			return $query;
			$query->free_result();
		}elseif($product == "PL")
		{
			$query = $this->db->query("SELECT
				SUM(IF(Status='SEND_BCA',1,0)) as inc,
				SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
				FROM `internal`.`apps_pl` WHERE Sales_Code='$sales_code' AND Created_Date LIKE '%$tanggal%'");
			return $query;
			$query->free_result();
		}elseif($product == "CORP")
		{
			$query = $this->db->query("SELECT
				SUM(IF(Status='SEND_BCA',1,0)) as inc,
				SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
				FROM `internal`.`applications` WHERE Sales_Code='$sales_code' AND Created_Date2 LIKE '%$tanggal%' AND Apply_Card_Code = '6'");
			return $query;
			$query->free_result();
		}
	}

	function getAprByDsr($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT approve, reject, Rating FROM `internal_sms`.`tbl_rating_sales` WHERE DSR_Code='$sales_code' AND periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function add_keterangan($sales_code, $kategori)
	{
		//load view
		$this->template->set('title','FORM ADD');
		$this->load->view('rsm/add_keterangan');
	}

	function getDataAtasan($sales_code)
	{
		$query = $this->db->query("SELECT * FROM `internal`.`data_sales_copy` WHERE DSR_Code='$sales_code'");
		return $query;
	}

	function insert_keterangan($data)
	{
		$this->db->insert('`internal_sms`.`tbl_keterangan_sales`', $data);
	}
	
	function update_keterangan($data, $sales_code, $kategori)
	{
		$this->db->where('kode_pembuat',$sales_code);
		$this->db->where('kategori',$kategori);
		$this->db->update('`internal_sms`.`tbl_keterangan_sales`',$data);
	}

	function getKomitmen($sales_code, $kategori)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_keterangan_sales` WHERE kode_pembuat='$sales_code' AND kategori='$kategori'");
		return $query;
		$query->free_result();
	}
	
	function getKomitmenOthers($sales_code, $kategori)
	{
		$query =$this->db->query("SELECT * FROM `internal_sms`.`tbl_keterangan_sales` WHERE kode_penerima='$sales_code' AND kategori='$kategori'");
		return $query;
		$query->free_result();
	}

	function getCek($sales_code, $kategori, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_keterangan_sales` WHERE kode_pembuat='$sales_code' AND kategori='$kategori' AND created_date='$tanggal'");
		return $query;
		$query->free_result();
	}

	//

	function getApssByAsm($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_asm` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function getApsRealtime($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code IN('2','4','7')) AS inc_cc,
            (SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code IN('2','4','7')) AS rts_cc,
            (SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status IN('SUBMIT_TO_BCA','RESUBMIT_TO_BCA') AND ms.MID_type='NEW') AS inc_edc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.MID_type='NEW') AS rts_edc,
            (SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA') AS inc_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.ASM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES') AS rts_sc
			FROM `internal`.`data_sales_copy` a
			WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}

	function getSpvByAsm($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code as SPV_Code, Name as SPV_Name, Product, Join_Date, Efektif_Date FROM `internal`.`data_sales_copy` WHERE ASM_Code='$sales_code' AND Status='ACTIVE' AND Position='SPV' Order By Name ASC");
		return $query;
		$query->free_result();
	}

	function getAppsLsAll($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_spv` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function getAppsLsAlls($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_asm` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}
	
	function getAppsLsAlls2($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_spv` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function getIncCurrent($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`applications_temp` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status IN('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA') AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status ='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') as inc_sc,

			(SELECT COUNT(1) FROM `internal`.`applications_temp` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status ='RETURN_TO_SALES' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as rts_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status ='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%') as rts_sc");
		return $query;
		$query->free_result();
	}

	function getAllSpvAktuals($sales_code)
	{
		$query = $this->db->query("SELECT a.DSR_Code as SPV_Code, a.Name as SPV_Name, a.Product, a.Join_Date, a.Efektif_Date,
			(SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE SPV_Code=a.DSR_Code AND Position IN('DSR','SPG','SPB') AND Status='ACTIVE') as totalnya
			FROM `internal`.`data_sales_copy` a WHERE a.RSM_Code='$sales_code' AND a.Position='SPV' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
		return $query;
		$query->free_result();
	}

	function getAllDsrBySpv($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name, Position, Product, Join_Date, Efektif_Date FROM `internal`.`data_sales_copy` 
			WHERE SPV_Code='$sales_code' AND Status='ACTIVE' AND Position IN('DSR','SPG', 'SPB') Order By Name ASC");
		return $query;
		$query->free_result();
	}

	function getDsrDummy($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name, Product, Join_Date, Efektif_Date FROM `internal`.`data_sales_copy` WHERE RSM_Code='$sales_code' AND SPV_Code='0' AND Status='ACTIVE' AND Position IN('DSR','SPG','SPB') ORDER BY Name ASC");
		return $query;
		$query->free_result();
	}

	function getAppsLsAllDsr($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function getIncCurrentDsr($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`applications_temp` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.DSR_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.DSR_Code='$sales_code' AND a.Status IN('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA') AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.DSR_Code='$sales_code' AND a.Status ='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') as inc_sc,

			(SELECT COUNT(1) FROM `internal`.`applications_temp` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.DSR_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.DSR_Code='$sales_code' AND a.Status ='RETURN_TO_SALES' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as rts_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.DSR_Code='$sales_code' AND a.Status ='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%') as rts_sc");
		return $query;
		$query->free_result();
	}

	function getRtsDummyAsm($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`applications_temp` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%' AND b.ASM_Code='0' AND b.Position IN('DSR','SPG','SPB')) as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`applications_temp` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%' AND b.ASM_Code='0' AND b.Position IN('DSR','SPG','SPB')) as rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.Status IN('RESUBMIT_TO_BCA','SUBMIT_TO_BCA') AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%' AND b.ASM_Code='0' AND b.Position IN('DSR','SPG','SPB')) as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%' AND b.ASM_Code='0' AND b.Position IN('DSR','SPG','SPB')) as rts_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%' AND b.ASM_Code='0' AND b.Position IN('DSR','SPG','SPB')) as inc_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%' AND b.ASM_Code='0' AND b.Position IN('DSR','SPG','SPB')) as rts_sc");
		return $query;
		$query->free_result();
	}

	function getDummyApsByAsm($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_dsr` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND b.SPV_Code='0' AND a.Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function getDummyCurrently($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`applications_temp` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND b.SPV_Code='0' AND b.Position IN('DSR','SPG','SPB') AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND b.SPV_Code='0' AND b.Position IN('DSR','SPG','SPB') AND a.Status IN('RESUBMIT_TO_BCA','SUBMIT_TO_BCA') AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND b.SPV_Code='0' AND b.Position IN('DSR','SPG','SPB') AND a.Status ='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') as inc_sc,

			(SELECT COUNT(1) FROM `internal`.`applications_temp` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND b.SPV_Code='0' AND b.Position IN('DSR','SPG','SPB') AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND b.SPV_Code='0' AND b.Position IN('DSR','SPG','SPB') AND a.Status ='RETURN_TO_SALES' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as rts_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND b.SPV_Code='0' AND b.Position IN('DSR','SPG','SPB') AND a.Status ='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%') as rts_sc");
		return $query;
		$query->free_result();
	}
	
	function getCountNoa($sales_code, $tanggal)
	{
		$sql = $this->db->query("SELECT Total_Noa FROM `tbl_summary_apps_rsm` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $sql;
		$sql->free_result();
	}
	
	function getSumDummy($asm_code, $periode)
	{
		$sql = $this->db->query("SELECT 
				SUM(Inc_cc + Inc_edc + Inc_sc) as inc,
				SUM(Rts_cc + Rts_edc + Rts_sc) as rts,
				SUM(Noa_cc + Noa_edc + Noa_sc) as noa,
				SUM(Dec_cc + Dec_edc + Dec_sc) as decl
				FROM `internal_sms`.`tbl_summary_apps_dsr` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code 
				WHERE b.ASM_Code='$asm_code' AND SPV_Code='0' AND a.Periode='$periode'");
		return $sql;
		$sql->free_result();
	}
	
	function getDataDsr($dsr_code, $periode)
	{
		$sql =  $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$dsr_code' AND Periode LIKE '%$periode%'");
		return $sql;
		$sql->free_result();
	}
	
	function getDummyDataDsr($rsm_code, $periode)
	{
		$sql = $this->db->query("SELECT
				SUM(a.Inc_cc + a.Inc_edc + a.Inc_sc) as inc,
				SUM(a.Rts_cc + a.Rts_edc + a.Rts_sc) as rts,
				SUM(a.Noa_cc + a.Noa_edc + a.Noa_sc) as noa,
				SUM(a.Dec_cc + a.Dec_edc + a.Dec_sc) as decl
				FROM `internal_sms`.`tbl_summary_apps_dsr` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code 
				WHERE b.RSM_Code='$rsm_code' AND b.SPV_Code='0' AND b.Status='ACTIVE' AND b.Position IN('DSR','SPG','SPB') AND a.Periode='$periode'");
		return $sql;
		$sql->free_result();
	}
	
	function BreakdownDataDummy($rsm_code, $periode)
	{
		$sql = $this->db->query("SELECT a.*, b.Product, b.Join_Date, b.Efektif_Date
				FROM `internal_sms`.`tbl_summary_apps_dsr` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code 
				WHERE b.RSM_Code='$rsm_code' AND b.SPV_Code='0' AND b.Status='ACTIVE' AND b.Position IN('DSR','SPG','SPB') AND a.Periode='$periode'");
		return $sql;
		$sql->free_result();
	}
	
	function getDataRtsNew($asm_code, $tgl)
	{
		$sql = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_asm` WHERE DSR_Code='$asm_code' AND Periode='$tgl'");
		return $sql;
		$sql->free_result();
	}
	
	function getDataRtsBySpv($spv_code, $periode)
	{
		$sql = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_spv` WHERE DSR_Code='$spv_code' AND Periode='$periode'");
		return $sql;
		$sql->free_result();
	}
	
	function DataRtsDummy($asm_code, $periode)
	{
		$sql = $this->db->query("SELECT
				sum(b.Inc_cc + b.Inc_edc + b.Inc_sc) as inc,
				sum(b.Rts_cc + b.Rts_edc + b.Rts_sc) as rts
				FROM `internal`.`data_sales_copy` a 
				INNER JOIN `internal_sms`.`tbl_summary_apps_dsr` b ON a.DSR_Code=b.DSR_Code
				WHERE a.ASM_Code='$asm_code' AND a.SPV_Code='0' AND a.Status='ACTIVE' AND a.Position IN('DSR','SPG','SPB') AND b.Periode='$periode'");
		return $sql;
		$sql->free_result();
	}
	
	function getAllUnderPerfNew($sales_code, $periode)
	{
		$sql = $this->db->query("SELECT a.DSR_Code as ASM_Code, a.Name as ASM_Name, b.Product, b.Join_Date, b.Efektif_Date ,
					(SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE ASM_Code=a.DSR_Code AND Position='SPV' AND Status='ACTIVE') as totalnya
					FROM `internal_sms`.`tbl_summary_apps_asm` a 
					INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code 
					WHERE b.RSM_Code='$sales_code' AND a.Periode='$periode' AND a.rating LIKE '%Under Perform%' Or a.rating LIKE '%Zero Account%'");
		return $sql;
		$sql->free_result();
	}
	
	function getDataTargetRsm($level, $position)
	{
		$sql = $this->db->query("SELECT * FROM `internal_sms`.`tbl_targets` WHERE level='$level' AND position='$position'");
		return $sql;
		$sql->free_result();
	}
	
}