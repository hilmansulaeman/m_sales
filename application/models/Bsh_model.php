<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Bsh_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function jumlah_ds_apps($sales_code, $tanggal, $posisi, $product)
	{
		$date2 = date('Y-m', strtotime('-1 month'));
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE BSH_Code='$sales_code' AND Status='ACTIVE' AND Position='RSM') AS total_rsm,
			(SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE BSH_Code='$sales_code' AND Status='ACTIVE' AND Position='ASM') AS total_asm,
			(SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE BSH_Code='$sales_code' AND Status='ACTIVE' AND Position='SPV') AS total_spv,
			(SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE BSH_Code='$sales_code' AND Status='ACTIVE' AND Position IN('DSR','SPG','SPB')) AS total_dsr,
			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_code=b.DSR_Code WHERE b.BSH_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_code=b.DSR_Code WHERE b.BSH_Code='$sales_code' AND a.Status IN('RESUBMIT_TO_BCA','SUBMIT_TO_BCA') AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_code=b.DSR_Code WHERE b.BSH_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') as inc_sc,
			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_code=b.DSR_Code WHERE b.BSH_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_code=b.DSR_Code WHERE b.BSH_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as rts_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_code=b.DSR_Code WHERE b.BSH_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%') as rts_sc,
			(SELECT target FROM `internal_sms`.`tbl_targets` WHERE position='$posisi' AND product='$product') as target_apps,
			(SELECT COUNT(1) FROM `internal_sms`.`tbl_summary_apps_rsm` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code 
			WHERE b.BSH_Code='$sales_code' AND a.rating LIKE '%Under Perform%' OR a.rating LIKE '%Zero Account%' AND Periode LIKE '%$tanggal%') as up
			");
		return $query;
		$query->free_result();
	}

	function m_jml_rsm_aktual($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code as RSM_Code, Name as RSM_Name, Join_Date, Efektif_Date, Product,
		(SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE RSM_Code=RSM_Code AND Position='ASM' AND Status='ACTIVE') as asmJml
		FROM `internal`.`data_sales_copy` WHERE BSH_Code='$sales_code' AND Status='ACTIVE' AND Position='RSM'");
		return $query;
		$query->free_result();
	}

	function getApps($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_rsm` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function CurrentApps($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.Status IN('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA') AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.Status ='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') as inc_sc,

			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.Status ='RETURN_TO_SALES' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as rts_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND a.Status ='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%') as rts_sc");
		return $query;
		$query->free_result();
	}

	function getAsmByRsm($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code as ASM_Code, Name as ASM_Name, Join_Date, Efektif_Date, Product FROM `internal`.`data_sales_copy` WHERE RSM_Code='$sales_code' AND Status='ACTIVE' AND Position='ASM' ORDER BY Name ASC");
		return $query;
		$query->free_result();
	}

	function getAppsAsm($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_asm` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function CurrentAppsAsm($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND a.Status IN('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA') AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND a.Status ='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') as inc_sc,

			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND a.Status ='RETURN_TO_SALES' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as rts_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND a.Status ='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%') as rts_sc");
		return $query;
		$query->free_result();
	}
	
	function CurrentAppsAsmNew($asm_code, $periode)
	{
		$sql = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_asm` WHERE DSR_Code='$asm_code' AND Periode='$periode'");
		return $sql;
		$sql->free_result();
	}

	function getAllAsmAktual($sales_code)
	{
		$query = $this->db->query("SELECT a.DSR_Code as ASM_Code, a.Name as ASM_Name, a.Product, a.Join_Date, a.Efektif_Date,
		(SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE ASM_Code=a.DSR_Code AND Position='SPV' AND Status='ACTIVE') as totalnya
		FROM `internal`.`data_sales_copy` a WHERE a.BSH_Code='$sales_code' AND a.Position='ASM' AND a.Status='ACTIVE' Order By a.Name ASC");
		return $query;
		$query->free_result();
	}

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

	function getAppsLsAlls($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_spv` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function getAllSpvAktuals($sales_code)
	{
		$query = $this->db->query("SELECT a.DSR_Code as SPV_Code, a.Name as SPV_Name, a.Product, a.Join_Date, a.Efektif_Date,(SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE SPV_Code=a.DSR_Code AND Position IN('DSR','SPG','SPB') AND STATUS='ACTIVE') as totalnya FROM `internal`.`data_sales_copy` a WHERE a.BSH_Code='$sales_code' AND a.Position='SPV' AND a.Status='ACTIVE' AND a.Product IN('CC','EDC','SC') ORDER BY a.Name ASC");
		return $query;
		$query->free_result();
	}

	function getAllDsrBySpv($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name, Position, Product, Join_Date, Efektif_Date FROM `internal`.`data_sales_copy` WHERE SPV_Code='$sales_code' AND Status='ACTIVE' AND Position IN('DSR','SPG', 'SPB') Order By Name ASC");
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
			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.DSR_Code='$sales_code' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.DSR_Code='$sales_code' AND a.Status IN('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA') AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.DSR_Code='$sales_code' AND a.Status ='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') as inc_sc,

			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.DSR_Code='$sales_code' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.DSR_Code='$sales_code' AND a.Status ='RETURN_TO_SALES' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as rts_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.DSR_Code='$sales_code' AND a.Status ='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%') as rts_sc");
		return $query;
		$query->free_result();
	}

	function getDsrDummy($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name, Product, Join_Date, Efektif_Date FROM `internal`.`data_sales_copy` WHERE BSH_Code='$sales_code' AND SPV_Code='0' AND Status='ACTIVE' AND Position IN('DSR','SPG','SPB') ORDER BY Name ASC");
		return $query;
		$query->free_result();
	}

	function getAllApps($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, a.Name,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code IN('2','4','7')) AS inc_cc,
            (SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code IN('2','4','7')) AS rts_cc,
            (SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status IN('SUBMIT_TO_BCA','RESUBMIT_TO_BCA') AND ms.MID_type='NEW') AS inc_edc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.MID_type='NEW') AS rts_edc,
            (SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA') AS inc_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES') AS rts_sc,
            (SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='SEND_BCA') AS inc_pl,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date LIKE '%$tanggal%' AND ms.Status ='RETURN_TO_SALES') AS rts_pl,
            (SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='SEND_BCA' AND ms.Apply_Card_Code='6') AS inc_corp,
			(SELECT COUNT(1) FROM `internal`.`applications` ms INNER JOIN `internal`.`data_sales_copy` x ON ms.Sales_Code=x.DSR_Code WHERE x.RSM_Code=a.DSR_Code AND ms.Created_Date2 LIKE '%$tanggal%' AND ms.Status='RETURN_TO_SALES' AND ms.Apply_Card_Code='6') AS rts_corp
			FROM `internal`.`data_sales_copy` a
			WHERE a.DSR_Code='$sales_code'");
		return $query;
		$query->free_result();
	}
	
	function getAllAppsNew($sales_code, $periode)
	{
		$sql = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_rsm` WHERE DSR_Code='$sales_code' AND Periode='$periode'");
		return $sql;
		$sql->free_result();
	}

	function DummyAsm($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT 
				sum(Inc_cc + Inc_edc + Inc_sc) as inc, 
				sum(Rts_cc + Rts_edc + Rts_sc) as rts, sum(Noa_cc + Noa_edc + Noa_sc) as noa, 
				sum(Dec_cc + Dec_edc + Dec_sc) as decl, Apprate_cc, Apprate_edc, Apprate_sc, 
				ratings
				FROM `internal_sms`.`tbl_summary_apps_dsr` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND a.Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function DummyAsmReal($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
		(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2','4','5') AND a.Created_Date2 LIKE '%$tanggal%') inc_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND a.Status IN('SUBMIT_TO_BCA','RESUBMIT_TO_BCA') AND a.MID_type='NEW' AND a.Created_Date LIKE '%$tanggal%') inc_edc,
		(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND a.Status='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') inc_sc,
		(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2','4','5') AND a.Created_Date2 LIKE '%$tanggal%') rts_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND a.Status='RETURN_TO_SALES' AND a.MID_type='NEW' AND a.Created_Date LIKE '%$tanggal%') rts_edc,
		(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND a.Status='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%') rts_sc");
		return $query;
		$query->free_result();
	}

	function DummySpv($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT 
				sum(Inc_cc + Inc_edc + Inc_sc) as inc, 
				sum(Rts_cc + Rts_edc + Rts_sc) as rts, sum(Noa_cc + Noa_edc + Noa_sc) as noa, 
				sum(Dec_cc + Dec_edc + Dec_sc) as decl, Apprate_cc, Apprate_edc, Apprate_sc, 
				ratings 
				FROM `internal_sms`.`tbl_summary_apps_dsr` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code' AND b.SPV_Code='0' AND a.Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function DummySpvReal($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
		(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code'  AND a.Status='SEND_BCA' AND b.SPV_Code='0' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') inc_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code'  AND a.Status IN('RESUBMIT_TO_BCA','SUBMIT_TO_BCA') AND b.SPV_Code='0' AND a.MID_Type ='NEW' AND a.Created_Date LIKE '%$tanggal%') inc_edc,
		(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code'  AND a.Status='SEND_BCA' AND b.SPV_Code='0' AND a.Created_Date LIKE '%$tanggal%') inc_sc,
        
        (SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code'  AND a.Status='RETURN_TO_SALES' AND b.SPV_Code='0' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') rts_cc,
		(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code'  AND a.Status='RETURN_TO_SALES' AND b.SPV_Code='0' AND a.MID_Type ='NEW' AND a.Created_Date LIKE '%$tanggal%') rts_edc,
		(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.ASM_Code='$sales_code'  AND a.Status='RETURN_TO_SALES' AND b.SPV_Code='0' AND a.Created_Date LIKE '%$tanggal%') rts_sc");
		return $query;
		$query->free_result();
	}

	function getCurentDummys($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND b.Position !='ASM' AND a.Status='SEND_BCA' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND b.Position !='ASM' AND a.Status IN('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA') AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND b.Position !='ASM' AND a.Status ='SEND_BCA' AND a.Created_Date LIKE '%$tanggal%') as inc_sc,

			(SELECT COUNT(1) FROM `internal`.`applications` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND b.Position !='ASM' AND a.Status='RETURN_TO_SALES' AND a.Apply_Card_Code IN('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%') as rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND b.Position !='ASM' AND a.Status ='RETURN_TO_SALES' AND a.MID_Type='NEW' AND a.Created_Date LIKE '%$tanggal%') as rts_edc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` a INNER JOIN `internal`.`data_sales_copy` b ON a.Sales_Code=b.DSR_Code WHERE b.RSM_Code='$sales_code' AND b.ASM_Code='0' AND b.Position !='ASM' AND a.Status ='RETURN_TO_SALES' AND a.Created_Date LIKE '%$tanggal%') as rts_sc");
		return $query;
		$query->free_result();
	}
	
	function getUnderPerform($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT b.DSR_Code as RSM_Code, b.Name as RSM_Name, b.Product, b.Join_Date, b.Efektif_Date, (SELECT COUNT(1) FROM `internal`.`data_sales_copy` WHERE RSM_Code=a.DSR_Code AND Status='ACTIVE' AND Position='ASM') as totalnya FROM `internal_sms`.`tbl_summary_apps_rsm` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code 
			WHERE b.BSH_Code='$sales_code' AND a.rating LIKE '%Under Perform%' OR a.rating LIKE '%Zero Account%' AND Periode LIKE '%$tanggal%'");
		return $query;
		$query->free_result();
	}

	function getKomitmenOthers($sales_code, $kategori)
	{
		$query =$this->db->query("SELECT * FROM `internal_sms`.`tbl_keterangan_sales` WHERE kode_penerima='$sales_code' AND kategori='$kategori'");
		return $query;
		$query->free_result();
	}
	
	function getDummyData($asm_code, $periode)
	{
		$sql = $this->db->query("SELECT
				SUM(Inc_cc + Inc_edc + Inc_sc) as inc,
				SUM(Rts_cc + Rts_edc + Rts_sc) as rts,
				SUM(Noa_cc + Noa_edc + Noa_sc) as noa,
				SUM(Dec_cc + Dec_edc + Dec_sc) as decl
				FROM `internal`.`data_sales_copy` b INNER JOIN `internal_sms`.`tbl_summary_apps_dsr` c ON b.DSR_Code = c.DSR_Code 
				WHERE b.ASM_Code='$asm_code' AND b.SPV_Code='0' AND b.Position IN('SPG','SPB','DSR') AND b.Status='ACTIVE' AND c.Periode='$periode'");
		return $sql;
		$sql->free_result();
	}
	
	function data_up($bsh_code, $periode)
	{
		$sql = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_rsm` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code 
			WHERE b.BSH_Code='$bsh_code' AND a.rating IN('Under Perform', 'Under Perform 1', 'Under Perform 2', 'Zero Account') AND Periode='$periode'");
		return $sql;
		$sql->free_result();
	}
	
}