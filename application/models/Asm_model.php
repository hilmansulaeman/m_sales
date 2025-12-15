<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Asm_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getDsrAktual($sales_code)
	{
		$query = $this->db->query("SELECT COUNT(1) jmldsr FROM `internal`.`data_sales_copy` 
		                           WHERE ASM_Code='$sales_code' 
								   AND Position IN('DSR','SPG', 'SPB') 
								   AND Status='ACTIVE'
								 ");
		return $query;
		$query->free_result();
	}

	function getSpvAktual($sales_code)
	{
		$query = $this->db->query("SELECT COUNT(1) jmlspv FROM `internal`.`data_sales_copy` 
		                           WHERE ASM_Code='$sales_code' 
								   AND Position='SPV' 
								   AND Status='ACTIVE'
								 ");
		return $query;
		$query->free_result();
	}

	function jml_dsr_aktual($sales_code)
	{
		$query = $this->db->query("");
		return $query;
		$query->free_result();
	}
	
	function getDataDsrAktual($sales_code)
	{
		$query = $this->db->query("SELECT * FROM `internal`.`data_sales_copy` 
		                           WHERE ASM_Code='$sales_code' 
								   AND Status='ACTIVE' 
								   AND Position='SPV'
								  ");
		return $query;
		$query->free_result();
	}
	
	function getTarget($level, $position)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_targets` 
		                           WHERE level='$level' 
								   AND position='$position'
								 ");
		return $query;
		$query->free_result();
	}
	
	/*function getAppsAkt($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name, 
			(SELECT COUNT(1) FROM `internal`.`applications` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code=a.DSR_Code AND a.Created_Date2 LIKE '%$tanggal%' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2', '4', '7')) as cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code=a.DSR_Code AND a.Created_Date LIKE '%$tanggal%' AND a.Status IN('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA') AND a.MID_Type='NEW') as edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code=a.DSR_Code AND a.Created_Date LIKE '%$tanggal%' AND a.Status='SEND_BCA') as sc
			FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}*/
	
	function getAppsAktNew($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_asm` 
		                           WHERE DSR_Code='$sales_code' 
								   AND Periode LIKE '%$tanggal%'
								 ");
		return $query;
		$query->free_result();
	}

	function getDataDm($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT(
			SELECT COUNT(1) FROM `internal`.`applications_temp` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code='$sales_code' AND SPV_Name LIKE '%DUMMY_SPV%' AND a.Created_Date2 LIKE '%$tanggal%' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2', '4', '7')) as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`applications_temp` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code='$sales_code' AND SPV_Name LIKE '%DUMMY_SPV%' AND a.Created_Date2 LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2', '4', '7')) as cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code='$sales_code' AND SPV_Name LIKE '%DUMMY_SPV%' AND a.Created_Date LIKE '%$tanggal%' AND a.Status IN('RESUBMIT_TO_BCA','SUBMIT_TO_BCA') AND a.MID_Type='NEW') as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code='$sales_code' AND SPV_Name LIKE '%DUMMY_SPV%' AND a.Created_Date LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES' AND a.MID_Type='NEW') as edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code='$sales_code' AND SPV_Name LIKE '%DUMMY_SPV%' AND a.Created_Date LIKE '%$tanggal%' AND a.Status='SEND_BCA') as inc_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code='$sales_code' AND SPV_Name LIKE '%DUMMY_SPV%' AND a.Created_Date LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES') as sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code='$sales_code' AND SPV_Name LIKE '%DUMMY_SPV%' AND a.Created_Date LIKE '%$tanggal%' AND a.Status='SEND_BCA') as inc_pl,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code='$sales_code' AND SPV_Name LIKE '%DUMMY_SPV%' AND a.Created_Date LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES') as pl,
			(SELECT COUNT(1) FROM `internal`.`applications` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code='$sales_code' AND SPV_Name LIKE '%DUMMY_SPV%' AND a.Created_Date2 LIKE '%$tanggal%' AND a.Status='SEND_BCA' AND a.Apply_Card_Code='6') as inc_corp,
			(SELECT COUNT(1) FROM `internal`.`applications` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code='$sales_code' AND SPV_Name LIKE '%DUMMY_SPV%' AND a.Created_Date2 LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code='6') as corp");
		return $query;
		$query->free_result();
	}
	
	function getAppRts($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name, 
			(SELECT COUNT(1) FROM `internal`.`applications` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code=a.DSR_Code AND a.Created_Date2 LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2', '4', '7')) as rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code=a.DSR_Code AND a.Created_Date LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES' AND a.MID_Type='NEW') as rts_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code=a.DSR_Code AND a.Created_Date LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES') as rts_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
			WHERE b.ASM_Code=a.DSR_Code AND a.Created_Date LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES') as rts_pl
			FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getDsrUnder($sales_code)
	{
		$periode = date('Y-m');
		$query = $this->db->query("SELECT b.DSR_Code as SPV_Code, b.Name as SPV_Name, b.Product, b.Join_Date, b.Efektif_Date, 
		                          (SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE SPV_Code=a.DSR_Code AND Position IN ('DSR', 'SPG', 'SPB') AND Status='ACTIVE') as totalDsr 
								  FROM `internal_sms`.`tbl_summary_apps_spv` a 
								  INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code 
								  WHERE b.ASM_Code='$sales_code' 
								  AND a.Ratings LIKE '%Under Perform%' 
								  AND a.Periode LIKE '%$periode%'
								 ");
		return $query;
		$query->free_result();
	}
	
	/*function getApproveAkt($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
		(SELECT COUNT(1) FROM `internal`.`application_process` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='Approved') as app_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status IN('ACCEPTED', 'APPROVED')) as app_edc,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='Approved') as app_sc,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='APPROVED') as app_corp
		FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}*/
	
	function getApproveAkt($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
		(SELECT COUNT(1) FROM `internal`.`application_process` b
		WHERE b.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='Approved') as app_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_result` b
		WHERE b.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status IN('ACCEPTED', 'APPROVED')) as app_edc,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		WHERE b.asm_code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='Approved') as app_sc,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		WHERE b.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='APPROVED') as app_corp
		FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	/*function getAppRejectAkt($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
		(SELECT COUNT(1) FROM `internal`.`application_process` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='DECLINED') as rej_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status IN('REJECT', 'REJECTED')) as rej_edc,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='DECLINED') as rej_sc,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		INNER JOIN `internal`.`data_sales_copy` c ON b.Sales_Code=c.DSR_Code WHERE c.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='DECLINED') as rej_corp
		FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}*/
	
	function getAppRejectAkt($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
		(SELECT COUNT(1) FROM `internal`.`application_process` b
		WHERE b.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status_1='DECLINED') as rej_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_result` b
		WHERE b.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status_1 IN('REJECT', 'REJECTED')) as rej_edc,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		WHERE b.asm_code=a.DSR_Code AND b.group_date LIKE '%$tanggal%' AND b.status_1='DECLINED') as rej_sc,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		WHERE b.ASM_Code=a.DSR_Code AND b.Group_Date LIKE '%$tanggal%' AND b.Status='DECLINED') as rej_corp
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
		WHERE b.Group_Date LIKE '%$tanggal%' AND b.Status_1 IN('ACCEPTED', 'APPROVED')) as app_edc,
		(SELECT COUNT(1) FROM `internal`.`sc_result` b
		WHERE b.group_date LIKE '%$tanggal%' AND b.status_1='Approved') as app_sc,
		(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` b
		WHERE b.group_date LIKE '%$tanggal%' AND b.status='APPROVED') as app_corp
		FROM `internal`.`data_sales_copy` a WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	/*function getRejNas($sales_code, $tanggal)
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
	}*/
	
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
	
	function getDataSpvAktual($sales_code)
	{
		$query = $this->db->query("SELECT a.DSR_Code as SPV_Code, a.Name as SPV_Name, 
			(SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE SPV_Code=a.DSR_Code AND Position IN ('DSR', 'SPG', 'SPB') AND Status='ACTIVE') as totalDSR
			FROM `internal`.`data_sales_copy` a
			WHERE a.ASM_Code='$sales_code' AND a.Position='SPV' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
		return $query;
		$query->free_result();
	}
	
	function dataApsAktual($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code as SPV_Code, a.Name as SPV_Name,

			(SELECT COUNT(1) FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code=a.DSR_Code AND a.Status='SEND_BCA' AND a.Created_Date2 LIKE '%$tanggal%' AND a.Apply_Card_Code IN('2', '4', '7')) AS cc,

			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code=a.DSR_Code AND a.Status IN ('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA') AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') AS edc,

			(SELECT COUNT(1) FROM `internal`.`apps_sc` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code=a.DSR_Code AND a.Status='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') AS sc,

			(SELECT COUNT(1) FROM `internal`.`apps_pl` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code=a.DSR_Code AND a.Status='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') AS pl,

			(SELECT COUNT(1) FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code=a.DSR_Code AND a.Status='SEND_BCA' AND a.Created_Date2 LIKE '%$tanggal%' AND a.Apply_Card_Code = '6') AS corp

			FROM `internal`.`data_sales_copy` a
			WHERE a.ASM_Code='$sales_code' AND a.Position='SPV' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
		return $query;
		$query->free_result();
	}
	
	function dataApsRts($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.Customer_Name as Cust_Name, b.Name, b.SPV_Code, b.SPV_Name, a.Status 
			FROM `internal`.`applications` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code 
			WHERE b.ASM_Code='$sales_code' AND a.Apply_Card_Code IN('2', '4', '7') AND a.Created_Date2 LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES' ORDER BY b.SPV_Name");
		return $query;
		$query->free_result();
	}
	
	function dataApsRts2($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.Merchant_Name as Cust_Name, b.Name, b.SPV_Code, b.SPV_Name, a.Status 
			FROM `internal`.`edc_merchant` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code 
			WHERE b.ASM_Code='$sales_code' AND MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES' ORDER BY b.SPV_Name");
		return $query;
		$query->free_result();
	} 
	
	function dataApsRts3($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.Customer_Name as Cust_Name, b.Name, b.SPV_Code, b.SPV_Name, a.Status 
			FROM `internal`.`apps_sc` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code 
			WHERE b.ASM_Code='$sales_code' AND a.Created_Date LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES' ORDER BY b.SPV_Name");
		return $query;
		$query->free_result();
	}
	
	function dataApsRts4($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.Customer_Name as Cust_Name, b.Name, b.SPV_Code, b.SPV_Name, a.Status 
			FROM `internal`.`apps_pl` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code 
			WHERE b.ASM_Code='$sales_code' AND a.Created_Date LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES' ORDER BY b.SPV_Name");
		return $query;
		$query->free_result();
	}

	function dataApsRts5($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.Customer_Name as Cust_Name, b.Name, b.SPV_Code, b.SPV_Name, a.Status 
			FROM `internal`.`applications` a
			INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code 
			WHERE b.ASM_Code='$sales_code' AND a.Apply_Card_Code='6' AND a.Created_Date2 LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES' ORDER BY b.SPV_Name");
		return $query;
		$query->free_result();
	}
	
	function dataUnder($sales_code)
	{
		
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_rating_sales` a 
			LEFT JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code
			WHERE a.ASM_Code='$sales_code' AND a.Rating='Under Perform' ORDER BY a.Name ASC");
		return $query;
		$query->free_result();
	}
	
	function getDataAtasan($sales_code)
	{
		$query = $this->db->query("SELECT * FROM `internal`.`data_sales_structure` WHERE DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getCek($sales_code, $kategori, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_keterangan_sales` WHERE kode_pembuat='$sales_code' AND kategori='$kategori' AND created_date='$tanggal'");
		return $query;
		$query->free_result();
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
	
	function getDataKomitmen($sales_code)
	{
		$query = $this->db->query("SELECT
			SUM(IF(a.kategori='JUMLAH_DSR',1,0)) as jmldsr,
			SUM(IF(a.kategori='APLIKASI',1,0)) as aplikasi,
			SUM(IF(a.kategori='RTS',1,0)) as rts,
			SUM(IF(a.kategori='UNDER_PERFORM',1,0)) as under_perform,
			SUM(IF(a.kategori='APPROVAL_RATE',1,0)) as app_rate
			FROM `internal_sms`.`tbl_keterangan_sales` a WHERE a.kode_pembuat='$sales_code'");
		return $query;
		$query->free_result();
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

	function allDsrAktual($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name, SPV_Name FROM `internal`.`data_sales_copy` WHERE ASM_Code='$sales_code' AND Position IN('DSR', 'SPG', 'SPB') AND Status='ACTIVE' ORDER BY SPV_Name ASC");
		return $query;
		$query->free_result();
	}

	function getAllApps($sales_code, $product, $tanggal)
	{
		if($product == "CC")
		{
			$query = $this->db->query("SELECT
					SUM(IF(Status='SEND_BCA',1,0)) as inc,
					SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
					FROM `internal`.`applications` WHERE Sales_Code='$sales_code' AND Apply_Card_Code IN('2','4','7') AND Status IN('SEND_BCA', 'RETURN_TO_SALES') AND Created_Date2 LIKE '%$tanggal%'");
			return $query;
			$query->free_result();
		}elseif($product == "EDC")
		{
			$query = $this->db->query("SELECT
					SUM(IF(Status='SUBMIT_TO_BCA' OR Status='RESUBMIT_TO_BCA',1,0)) as inc,
					SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
					FROM `internal`.`edc_merchant` WHERE Sales_Code='$sales_code' AND MID_Type='NEW' AND Status IN('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA','RETURN_TO_SALES') AND Created_Date LIKE '%$tanggal%'");
			return $query;
			$query->free_result();
		}elseif($product == "SC")
		{
			$query = $this->db->query("SELECT
					SUM(IF(Status='SEND_BCA',1,0)) as inc,
					SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
					FROM `internal`.`apps_sc` WHERE Sales_Code='$sales_code' AND Status IN('SEND_BCA', 'RETURN_TO_SALES') AND Created_Date LIKE '%$tanggal%'");
			return $query;
			$query->free_result();
		}elseif($product == "PL")
		{
			$query = $this->db->query("SELECT
					SUM(IF(Status='SEND_BCA',1,0)) as inc,
					SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
					FROM `internal`.`apps_pl` WHERE Sales_Code='$sales_code' AND Status IN('SEND_BCA', 'RETURN_TO_SALES') AND Created_Date LIKE '%$tanggal%'");
			return $query;
			$query->free_result();
		}elseif($product == "CORP")
		{
			$query = $this->db->query("SELECT
					SUM(IF(Status='SEND_BCA',1,0)) as inc,
					SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
					FROM `internal`.`applications` WHERE Sales_Code='$sales_code' AND Apply_Card_Code= '6' AND Status IN('SEND_BCA', 'RETURN_TO_SALES') AND Created_Date2 LIKE '%$tanggal%'");
			return $query;
			$query->free_result();
		}
	}
	
	function getAllAppr($sales_code, $periode)
	{
		$query = $this->db->query("SELECT approve, reject, Rating FROM `internal_sms`.`tbl_rating_sales` WHERE DSR_Code='$sales_code' AND periode='$periode'");
		return $query;
		$query->free_result();
	}

	function getAllSpvAktual($sales_code)
	{
		$query = $this->db->query("SELECT a.DSR_Code as SPV_Code, a.Name as SPV_Name, a.Product, a.Join_Date, a.Efektif_Date,
		(SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE SPV_Code=a.DSR_Code AND Status='ACTIVE') as totalDsr
		FROM `internal`.`data_sales_copy` a WHERE a.ASM_Code='$sales_code' AND a.Position='SPV' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
		return $query;
		$query->free_result();
	}

	function getCountDsr($sales_code)
	{
		$query = $this->db->query("SELECT COUNT(1) as jmldsr FROM `internal`.`data_sales_copy` WHERE SPV_Code='$sales_code' AND Status='ACTIVE'");
		return $query;
		$query->free_result();
	}

	function getAllApsAkt($sales_code, $product, $tanggal)
	{
		IF($product == "CC")
		{
			$query = $this->db->query("SELECT
					SUM(IF(a.Status='SEND_BCA',1,0)) as inc,
					SUM(IF(a.Status='RETURN_TO_SALES',1,0)) as rts
					FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
					WHERE b.SPV_Code='$sales_code' AND a.Apply_Card_Code IN('2','4','7') AND a.Status IN('SEND_BCA','RETURN_TO_SALES') AND a.Created_Date2 LIKE '%$tanggal%'");
			return $query;
		}elseif($product == "EDC")
		{
			$query = $this->db->query("SELECT
					SUM(IF(a.Status='SUBMIT_TO_BCA' OR a.Status='RESUBMIT_TO_BCA',1,0)) as inc,
					SUM(IF(a.Status='RETURN_TO_SALES',1,0)) as rts
					FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
					WHERE b.SPV_Code='$sales_code' AND a.MID_Type='NEW' AND a.Status IN('SUBMIT_TO_BCA','RESUBMIT_TO_BCA','RETURN_TO_SALES') AND a.Created_Date LIKE '%$tanggal%'");
			return $query;
		}elseif($product == "SC")
		{
			$query = $this->db->query("SELECT
					SUM(IF(a.Status='SEND_BCA',1,0)) as inc,
					SUM(IF(a.Status='RETURN_TO_SALES',1,0)) as rts
					FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
					WHERE b.SPV_Code='$sales_code' AND a.Status IN('SEND_BCA','RETURN_TO_SALES') AND a.Created_Date LIKE '%$tanggal%'");
			return $query;
		}elseif($product =="PL")
		{
			$query = $this->db->query("SELECT
					SUM(IF(a.Status='SEND_BCA',1,0)) as inc,
					SUM(IF(a.Status='RETURN_TO_SALES',1,0)) as rts
					FROM `internal`.`apps_pl` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
					WHERE b.SPV_Code='$sales_code' AND a.Status IN('SEND_BCA','RETURN_TO_SALES') AND a.Created_Date LIKE '%$tanggal%'");
			return $query;
		}elseif($product == "CORP")
		{
			$query = $this->db->query("SELECT
					SUM(IF(a.Status='SEND_BCA',1,0)) as inc,
					SUM(IF(a.Status='RETURN_TO_SALES',1,0)) as rts
					FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
					WHERE b.SPV_Code='$sales_code' AND a.Apply_Card_Code='6' AND a.Status IN('SEND_BCA','RETURN_TO_SALES') AND a.Created_Date2 LIKE '%$tanggal%'");
			return $query;
		}
	}

	function getAllApprove($sales_code, $periode)
	{
		$query = $this->db->query("SELECT approve, reject FROM `internal_sms`.`tbl_rating_sales` WHERE SPV_Code='$sales_code' AND periode LIKE '%$periode'");
		return $query;
		$query->free_result();
	}

	function getRts_($sales_code, $product, $tanggal)
	{
		IF($product == "CC")
		{
			$query = $this->db->query("SELECT
					SUM(IF(a.Status='SEND_BCA',1,0)) as inc,
					SUM(IF(a.Status='RETURN_TO_SALES',1,0)) as rts,
					FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
					WHERE b.SPV_Code='$sales_code' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%'");
			return $query;
			$query->free_result();
		}elseif($product == "EDC")
		{
			$query = $this->db->query("SELECT
					SUM(IF(a.Status='SUBMIT_TO_BCA' OR a.Status='RESUBMIT_TO_BCA',1,0)) as inc,
					SUM(IF(a.Status='RETURN_TO_SALES',1,0)) as rts,
					FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
					WHERE b.SPV_Code='$sales_code' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%'");
			return $query;
			$query->free_result();
		}elseif($product == "SC")
		{
			$query = $this->db->query("SELECT
					SUM(IF(a.Status='SEND_BCA',1,0)) as inc,
					SUM(IF(a.Status='RETURN_TO_SALES',1,0)) as rts,
					FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
					WHERE b.SPV_Code='$sales_code' AND a.Created_Date LIKE '%$tanggal%'");
			return $query;
			$query->free_result();
		}elseif($product =="PL")
		{
			$query = $this->db->query("SELECT
					SUM(IF(a.Status='SEND_BCA',1,0)) as inc,
					SUM(IF(a.Status='RETURN_TO_SALES',1,0)) as rts,
					FROM `internal`.`apps_pl` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
					WHERE b.SPV_Code='$sales_code' AND a.Created_Date LIKE '%$tanggal%'");
			return $query;
			$query->free_result();
		}elseif($product == "CORP")
		{
			$query = $this->db->query("SELECT
					SUM(IF(a.Status='SEND_BCA',1,0)) as inc,
					SUM(IF(a.Status='RETURN_TO_SALES',1,0)) as rts,
					FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code
					WHERE b.SPV_Code='$sales_code' AND a.Apply_Card_Code='6' AND a.Created_Date2 LIKE '%$tanggal%'");
			return $query;
			$query->free_result();
		}
	}

	function getRts($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`applications_temp` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2', '4', '7') AND a.Created_Date2 LIKE '%$tanggal%') as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`applications_temp` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2', '4', '7') AND a.Created_Date2 LIKE '%$tanggal%') as cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status IN ('SUBMIT_TO_BCA','RESUBMIT_TO_BCA') AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') as inc_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%') as sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') as inc_pl,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%') as pl,
			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Apply_Card_Code='6' AND a.Created_Date2 LIKE '%$tanggal%') as inc_corp,
			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code='6' AND a.Created_Date2 LIKE '%$tanggal%') as corp");
		return $query;
		$query->free_result();
	}

	function getAppsLsAll($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_spv` 
		                     WHERE DSR_Code='$sales_code' 
							 AND Periode LIKE '%$tanggal%'
							");
		return $query;
		$query->free_result();
	}

	function getAppsLsAllDsr($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function getIncCurrent($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status IN('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA') AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status ='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') as inc_sc,

			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status ='RETURN_TO_SALES' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as rts_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND a.Status ='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%') as rts_sc");
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

	function getAllDsrBySpv($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name, Position, Product, Join_Date, Efektif_Date FROM `internal`.`data_sales_copy` WHERE SPV_Code='$sales_code' AND Status='ACTIVE' AND Position IN('DSR','SPG', 'SPB') ORDER BY Name ASC");
		return $query;
		$query->free_result();
	}

	function getDsrDummy($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name, Product, Join_Date, Efektif_Date FROM `internal`.`data_sales_copy` WHERE ASM_Code='$sales_code' AND SPV_Code='0' AND Status='ACTIVE' AND Position IN('DSR','SPG','SPB', 'Mobile Sales')");
		return $query;
		$query->free_result();
	}
	
	function getCountNoa($sales_code, $tanggal)
	{
		$sql = $this->db->query("SELECT Total_Noa FROM `tbl_summary_apps_asm` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $sql;
		$sql->free_result();
	}

	function getDsrBySpv($spv_code)
	{
		$sql = $this->db->query("SELECT DSR_Code, Name, Product, Join_Date, Efektif_Date FROM `internal`.`data_sales_copy` WHERE SPV_Code='$spv_code' AND Status='ACTIVE' Order By Name ASC");
		return $sql;
		$sql->free_result();
	}

	function getDummySpv2($asm_code, $tanggal)
	{
		$sql = $this->db->query("SELECT 
					SUM(Inc_cc + Inc_edc + Inc_sc) as inc,
					SUM(Rts_cc + Rts_edc + Rts_sc) as rts,
					SUM(Noa_cc + Noa_edc + Noa_sc) as noa,
					SUM(Dec_cc + Dec_edc + Dec_sc) as decl
					FROM `internal_sms`.`tbl_summary_apps_dsr` a 
					INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code 
					WHERE b.ASM_Code='$asm_code' AND b.Status='ACTIVE' AND b.SPV_Code='0' AND a.Periode='$tanggal'");
		return $sql;
		$sql->free_result();
	}
	
	function getDummySpv($asm_code, $tanggal)
	{
		$sql = $this->db->query("SELECT 
					SUM(Inc_cc + Inc_edc + Inc_sc) as inc,
					SUM(Rts_cc + Rts_edc + Rts_sc) as rts,
					SUM(Noa_cc + Noa_edc + Noa_sc) as noa,
					SUM(Dec_cc + Dec_edc + Dec_sc) as decl
					FROM `internal_sms`.`tbl_summary_apps_dsr` a 
					WHERE ASM_Code='$asm_code' AND SPV_Code='0' AND Periode='$tanggal'");
		return $sql;
		$sql->free_result();
	}

	function getDsrDummySpv($asm_code)
	{
		$sql = $this->db->query("SELECT * FROM `internal`.`data_sales_copy` 
		             WHERE ASM_Code='K1102197' 
					 AND Status='ACTIVE' 
					 AND SPV_Code='0' 
					 ORDER BY Name AC
					");
		return $sql;
		$sql->free_result();
	}

	function getAppsBySpv($spv_code, $tanggal)
	{
		$sql = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_spv` 
		             WHERE DSR_Code='$spv_code' 
					 AND Periode LIKE '%$tanggal%'
					");
		return $sql;
		$sql->free_result();
	}

	function getApplicationCc($spv_code, $tanggal)
	{
		$sql = $this->db->query("SELECT 
				a.Customer_Name as Cust_Name, a.Created_Date2 as Tgl_Input, b.Name as Ds_Name, b.SPV_Name as Spv_Name 
				FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$spv_code' AND a.Status='RETURN_TO_SALES' AND a.Created_Date2 LIKE '%$tanggal%' Order by b.Name");
		return $sql;
		$sql->free_result();
	}

	function getApplicationEdc($spv_code, $tanggal)
	{
		$sql = $this->db->query("SELECT 
				a.Merchant_Name as Cust_Name, a.Created_Date as Tgl_Input, b.Name as Ds_Name, b.SPV_Name as Spv_Name
				FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$spv_code' AND a.Status='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%' Order by b.Name");
		return $sql;
		$sql->free_result();
	}

	function getApplicationSc($spv_code, $tanggal)
	{
		$sql = $this->db->query("SELECT 
				a.Customer_Name as Cust_Name, a.Created_Date as Tgl_Input, b.Name as Ds_Name, b.SPV_Name as Spv_Name 
				FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.SPV_Code='$spv_code' AND a.Status='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%' Order by b.Name");
		return $sql;
		$sql->free_result();
	}

	function getAllSpvUnderAktual()
	{

	}
}