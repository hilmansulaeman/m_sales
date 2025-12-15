<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Spv_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getAllDsr($sales_code)
	{
		$query = $this->db->query("SELECT COUNT(1) AS total_dsr FROM `internal`.`data_sales_structure` WHERE SPV_Code='$sales_code' AND Status='ACTIVE'");
		return $query;
	}
	
	function getTarget($level, $position, $product)
	{
		$query = $this->db->query("SELECT target FROM `internal_sms`.`tbl_targets` WHERE level='$level' AND position='$position' AND product='$product'");
		return $query;
	}
	
	function getAplikasiAktual($sales_code, $product, $tanggal)
	{
		if($product == "CC")
		{
			$query = $this->db->query("SELECT COUNT(1) as jmlapps FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.SPV_Code='$sales_code' AND a.Apply_Card_Code in('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%' AND a.Status='SEND_BCA'");
			return $query;
		}elseif($product == "EDC")
		{
			$query = $this->db->query("SELECT COUNT(1) as jmlapps FROM `internal`.`edc_merchant` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.SPV_Code='$sales_code' AND a.MID_Type in('NEW') AND a.Created_Date LIKE '%$tanggal%' AND a.Status in ('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA')");
			return $query;
		}elseif($product == "SC")
		{
			$query = $this->db->query("SELECT COUNT(1) as jmlapps FROM `internal`.`apps_sc` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.SPV_Code='$sales_code' AND a.Created_Date LIKE '%$tanggal%' AND a.Status='SEND_BCA'");
			return $query;
		}
		elseif($product == "PL")
		{
			$query = $this->db->query("SELECT COUNT(1) as jmlapps FROM `internal`.`apps_pl` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.SPV_Code='$sales_code' AND a.Created_Date LIKE '%$tanggal%' AND a.Status='SEND_BCA'");
			return $query;
		}
		elseif($product == "CORP")
		{
			$query = $this->db->query("SELECT COUNT(1) as jmlapps FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.SPV_Code='$sales_code' AND a.Apply_Card_Code in('6') AND a.Created_Date2 LIKE '%$tanggal%' AND a.Status='SEND_BCA'");
			return $query;
		}
	}
	
	function getAplikasiAktualNew($sales_code,$tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_spv` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $query;	
	}
	
	function getAplikasiRts($sales_code, $product, $tanggal)
	{
		if($product == "CC")
		{
			$query = $this->db->query("SELECT COUNT(1) as appsrts FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.SPV_Code='$sales_code' AND a.Apply_Card_Code in('2','4','7') AND a.Created_Date2 LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES'");
			return $query;
		}elseif($product == "EDC")
		{
			$query = $this->db->query("SELECT COUNT(1) as appsrts FROM `internal`.`edc_merchant` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.SPV_Code='$sales_code' AND a.MID_Type in('NEW') AND a.Created_Date LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES'");
			return $query;
		}elseif($product == "SC")
		{
			$query = $this->db->query("SELECT COUNT(1) as appsrts FROM `internal`.`apps_sc` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.SPV_Code='$sales_code' AND a.Created_Date LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES'");
			return $query;
		}
		elseif($product == "PL")
		{
			$query = $this->db->query("SELECT COUNT(1) as appsrts FROM `internal`.`apps_pl` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.SPV_Code='$sales_code' AND a.Created_Date LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES'");
			return $query;
		}
		elseif($product == "CORP")
		{
			$query = $this->db->query("SELECT COUNT(1) as appsrts FROM `internal`.`applications` a 
			INNER JOIN `internal`.`data_sales_structure` b ON a.sales_code=b.DSR_Code
			WHERE b.SPV_Code='$sales_code' AND a.Apply_Card_Code in('6') AND a.Created_Date2 LIKE '%$tanggal%' AND a.Status='RETURN_TO_SALES'");
			return $query;
		}
	}
	
	function getAproval($sales_code, $product, $tanggal)
	{
		if($product == "CC")
		{
			$query = $this->db->query("SELECT COUNT(1) as aps_rates FROM `internal`.`application_process` a 
								INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
								WHERE b.SPV_Code='$sales_code' AND a.Group_Date LIKE '%$tanggal%' AND a.Status='Approved'");
			return $query;
		}
		elseif($product == "EDC")
		{
			$query = $this->db->query("SELECT COUNT(1) as aps_rates FROM `internal`.`edc_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE b.SPV_Code='$sales_code' AND a.Group_Date LIKE '%$tanggal%' AND a.Status IN('ACCEPTED', 'APPROVED')");
			return $query;
		}
		elseif($product == "SC")
		{
			$query = $this->db->query("SELECT COUNT(1) as aps_rates FROM `internal`.`sc_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE b.SPV_Code='$sales_code' AND a.Group_Date LIKE '%$tanggal%' AND a.Status='Approved'");
			return $query;
		}
		elseif($product == "PL")
		{
			$query = $this->db->query("SELECT COUNT(1) as aps_rates FROM `internal`.`apps_pl_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE b.SPV_Code='$sales_code' AND a.Group_Date LIKE '%$tanggal%' AND a.Status IN('Approve', 'Approved')");
			return $query;
		}
		elseif($product == "CORP")
		{
			$query = $this->db->query("SELECT COUNT(1) as aps_rates FROM `internal`.`corporate_ro_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE b.SPV_Code='$sales_code' AND a.Group_Date LIKE '%$tanggal%' AND a.Status='APPROVED'");
			return $query;
		}
	}
	
	function getRejSpv($sales_code, $product, $tanggal)
	{
		if($product == "CC")
		{
			$query = $this->db->query("SELECT COUNT(1) as rijeks FROM `internal`.`application_process` a 
								INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
								WHERE b.SPV_Code='$sales_code' AND a.Group_Date LIKE '%$tanggal%' AND a.Status='DECLINED'");
			return $query;
		}
		elseif($product == "EDC")
		{
			$query = $this->db->query("SELECT COUNT(1) as rijeks FROM `internal`.`edc_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE b.SPV_Code='$sales_code' AND a.Group_Date LIKE '%$tanggal%' AND a.Status IN('REJECT', 'REJECTED')");
			return $query;
		}
		elseif($product == "SC")
		{
			$query = $this->db->query("SELECT COUNT(1) as rijeks FROM `internal`.`sc_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE b.SPV_Code='$sales_code' AND a.Group_Date LIKE '%$tanggal%' AND a.Status='DECLINED'");
			return $query;
		}
		elseif($product == "PL")
		{
			$query = $this->db->query("SELECT COUNT(1) as rijeks FROM `internal`.`apps_pl_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE b.SPV_Code='$sales_code' AND a.Group_Date LIKE '%$tanggal%' AND a.Status='Rejected'");
			return $query;
		}
		elseif($product == "CORP")
		{
			$query = $this->db->query("SELECT COUNT(1) as rijeks FROM `internal`.`corporate_ro_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE b.SPV_Code='$sales_code' AND a.Group_Date LIKE '%$tanggal%' AND a.Status='DECLINED'");
			return $query;
		}
	}
	
	function getApprovalNas($product, $tanggal)
	{
		if($product == "CC")
		{
			$query = $this->db->query("SELECT COUNT(1) as aps_nas FROM `internal`.`application_process` a 
								INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
								WHERE a.Group_Date LIKE '%$tanggal%' AND a.Status='Approved'");
			return $query;
		}
		elseif($product == "EDC")
		{
			$query = $this->db->query("SELECT COUNT(1) as aps_nas FROM `internal`.`edc_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE a.Group_Date LIKE '%$tanggal%' AND a.Status IN('ACCEPTED', 'APPROVED')");
			return $query;
		}
		elseif($product == "SC")
		{
			$query = $this->db->query("SELECT COUNT(1) as aps_nas FROM `internal`.`sc_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE a.Group_Date LIKE '%$tanggal%' AND a.Status='Approved'");
			return $query;
		}
		elseif($product == "PL")
		{
			$query = $this->db->query("SELECT COUNT(1) as aps_nas FROM `internal`.`apps_pl_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE a.Group_Date LIKE '%$tanggal%' AND a.Status IN('Approve', 'Approved')");
			return $query;
		}
		elseif($product == "CORP")
		{
			$query = $this->db->query("SELECT COUNT(1) as aps_nas FROM `internal`.`corporate_ro_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE a.Group_Date LIKE '%$tanggal%' AND a.Status='APPROVED'");
			return $query;
		}
	}
	
	function getRejlNas($product, $tanggal)
	{
		if($product == "CC")
		{
			$query = $this->db->query("SELECT COUNT(1) as rijek FROM `internal`.`application_process` a 
								INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
								WHERE a.Group_Date LIKE '%$tanggal%' AND a.Status='DECLINED'");
			return $query;
		}
		elseif($product == "EDC")
		{
			$query = $this->db->query("SELECT COUNT(1) as rijek FROM `internal`.`edc_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE a.Group_Date LIKE '%$tanggal%' AND a.Status IN('REJECT', 'REJECTED')");
			return $query;
		}
		elseif($product == "SC")
		{
			$query = $this->db->query("SELECT COUNT(1) as rijek FROM `internal`.`sc_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE a.Group_Date LIKE '%$tanggal%' AND a.Status='DECLINED'");
			return $query;
		}
		elseif($product == "PL")
		{
			$query = $this->db->query("SELECT COUNT(1) as rijek FROM `internal`.`apps_pl_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE a.Group_Date LIKE '%$tanggal%' AND a.Status='Rejected'");
			return $query;
		}
		elseif($product == "CORP")
		{
			$query = $this->db->query("SELECT COUNT(1) as rijek FROM `internal`.`corporate_ro_result` a 
							INNER JOIN `internal`.`data_sales_structure` b ON a.Sales_Code=b.DSR_Code 
							WHERE a.Group_Date LIKE '%$tanggal%' AND a.Status='DECLINED'");
			return $query;
		}
	}
	
	//card
	function getJmlDsrAct($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT a.DSR_Code, Name, 
			(SELECT COUNT(1) FROM `internal`.`applications` WHERE Sales_Code=a.DSR_Code AND Apply_Card_Code IN('2','4','7') AND Status='SEND_BCA' AND Created_Date2 LIKE '%$tanggal%') as inc_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` WHERE Sales_Code=a.DSR_Code AND MID_Type='NEW' AND Status IN('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA') AND Created_Date LIKE '%$tanggal%') as inc_edc,
			(SELECT COUNT(1) FROM `internal`.`sc_result` WHERE Sales_Code=a.DSR_Code AND Status='SEND_BCA' AND Created_Date LIKE '%$tanggal%') as inc_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` WHERE Sales_Code=a.DSR_Code AND Status='SEND_BCA' AND Created_Date LIKE '%$tanggal%') as inc_pl,
			(SELECT COUNT(1) FROM `internal`.`applications` WHERE Sales_Code=a.DSR_Code AND Apply_Card_Code='6' AND Status='SEND_BCA' AND Created_Date2 LIKE '%$tanggal%') as inc_corp,
			(SELECT COUNT(1) FROM `internal`.`applications` WHERE Sales_Code=a.DSR_Code AND Apply_Card_Code IN('2','4','7') AND Status='RETURN_TO_SALES' AND Created_Date2 LIKE '%$tanggal%') as rts_cc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` WHERE Sales_Code=a.DSR_Code AND MID_Type='NEW' AND Status='RETURN_TO_SALES' AND Created_Date LIKE '%$tanggal%') as rts_edc,
			(SELECT COUNT(1) FROM `internal`.`sc_result` WHERE Sales_Code=a.DSR_Code AND Status='RETURN_TO_SALES' AND Created_Date LIKE '%$tanggal%') as rts_sc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` WHERE Sales_Code=a.DSR_Code AND Status='RETURN_TO_SALES' AND Created_Date LIKE '%$tanggal%') as rts_pl,
			(SELECT COUNT(1) FROM `internal`.`applications` WHERE Sales_Code=a.DSR_Code AND Apply_Card_Code='6' AND Status='RETURN_TO_SALES' AND Created_Date2 LIKE '%$tanggal%') as rts_corp
			FROM `internal`.`data_sales_structure` a 
			WHERE a.SPV_Code='$sales_code' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
		return $query;
	}

	//here
	function cc_inc($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT 
			SUM(IF(Status='SEND_BCA',1,0)) AS inc,
			SUM(IF(Status='RETURN_TO_SALES',1,0)) AS rts
			FROM `internal`.`applications` WHERE Sales_Code='$sales_code' AND Apply_Card_Code IN('2','4','7') AND Created_Date LIKE '%$tanggal%'");
		return $query;
	}

	function getJmlDsrActCc($sales_code)
	{
		$tanggal = date('Y-m');
		$date = date('Y-m', strtotime('-1 month'));
		$query = $this->db->query("SELECT a.DSR_Code, Name,
			(SELECT COUNT(1) FROM `internal`.`applications` WHERE Sales_Code=a.DSR_Code AND Apply_Card_Code IN('2','4','7') AND Status='SEND_BCA' AND Created_Date2 LIKE '%$tanggal%') as inc,
			(SELECT COUNT(1) FROM `internal`.`applications` WHERE Sales_Code=a.DSR_Code AND Apply_Card_Code IN('2','4','7') AND Status='RETURN_TO_SALES' AND Created_Date2 LIKE '%$tanggal%') as rts
			FROM `internal`.`data_sales_structure` a 
			WHERE a.SPV_Code='$sales_code' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
		return $query;
	}

	function getDsrAktual($sales_code)
	{
		$query = $this->db->query("SELECT DSR_Code, Name, Product, Join_Date, Efektif_date FROM `internal`.`data_sales_copy` WHERE SPV_Code='$sales_code' AND Status='ACTIVE' ORDER BY Name");
		return $query;
	}

	function getDataAktualApps($sales_code, $product, $tanggal)
	{
		if($product == "CC")
		{
			$query = $this->db->query("SELECT
				SUM(IF(Status = 'SEND_BCA',1,0)) AS inc,
				SUM(IF(Status = 'RETURN_TO_SALES',1,0)) AS rts
				FROM `internal`.`applications` WHERE Sales_Code='$sales_code' AND Apply_Card_Code IN('2','4','7')  AND Created_Date2 LIKE '%$tanggal%'");
			return $query;
		}
		elseif($product == "EDC")
		{
			$query = $this->db->query("SELECT
				SUM(IF(Status = 'SUBMIT_TO_BCA' AND Status = 'RESUBMIT_TO_BCA',1,0)) AS inc,
				SUM(IF(Status = 'RETURN_TO_SALES',1,0)) AS rts
				FROM `internal`.`edc_merchant` WHERE Sales_Code='$sales_code' AND MID_Type='NEW' AND Created_Date LIKE '%$tanggal%'");
			return $query;
		}
		elseif($product == "SC")
		{
			$query = $this->db->query("SELECT
				SUM(IF(Status = 'SEND_BCA',1,0)) AS inc,
				SUM(IF(Status = 'RETURN_TO_SALES',1,0)) AS rts
				FROM `internal`.`apps_sc` WHERE Sales_Code='$sales_code' AND Created_Date LIKE '%$tanggal%'");
			return $query;
		}
		elseif($product == "PL")
		{
			$query = $this->db->query("SELECT
				SUM(IF(Status = 'SEND_BCA',1,0)) AS inc,
				SUM(IF(Status = 'RETURN_TO_SALES',1,0)) AS rts
				FROM `internal`.`apps_pl` WHERE Sales_Code='$sales_code' AND Created_Date LIKE '%$tanggal%'");
			return $query;
		}
		elseif($product == "CORP")
		{
			$query = $this->db->query("SELECT
				SUM(IF(Status = 'SEND_BCA',1,0)) AS inc,
				SUM(IF(Status = 'RETURN_TO_SALES',1,0)) AS rts
				FROM `internal`.`applications` WHERE Sales_Code='$sales_code' AND Apply_Card_Code='6' AND Created_Date2 LIKE '%$tanggal%'");
			return $query;
		}
	}

	function getDataAktualAppr($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT approve, reject, Rating FROM `internal_sms`.`tbl_rating_sales` WHERE DSR_Code='$sales_code' AND periode='$tanggal'");
		return $query;
	}

	function getJmlDsrActEdc($sales_code)
	{
		$tanggal = date('Y-m');
		$date = date('Y-m', strtotime('-1 month'));
		$query = $this->db->query("SELECT a.DSR_Code, Name,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` WHERE Sales_Code=a.DSR_Code AND MID_Type='NEW' AND Status IN ('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA') AND Created_Date LIKE '%$tanggal%') as inc,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` WHERE Sales_Code=a.DSR_Code AND MID_Type = 'NEW' AND Status='RETURN_TO_SALES' AND Created_Date LIKE '%$tanggal%') as rts,
			(SELECT COUNT(1) FROM `internal`.`edc_result` WHERE Sales_code=a.DSR_Code AND Status_1='ACCEPTED' AND Group_Date LIKE '%$date%') as app,
			(SELECT COUNT(1) FROM `internal`.`edc_result` WHERE Sales_code=a.DSR_Code AND Status_1='REJECT' AND Group_Date LIKE '%$date%') as rej
			FROM `internal`.`data_sales_structure` a 
			WHERE a.SPV_Code='$sales_code' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
		return $query;
	}

	function getJmlDsrActSc($sales_code)
	{
		$tanggal = date('Y-m');
		$date = date('Y-m', strtotime('-1 month'));
		$query = $this->db->query("SELECT a.DSR_Code, Name,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` WHERE Sales_Code=a.DSR_Code AND Status = 'SEND_BCA' AND Created_Date LIKE '%$tanggal%') as inc,
			(SELECT COUNT(1) FROM `internal`.`apps_sc` WHERE Sales_Code=a.DSR_Code AND Status='RETURN_TO_SALES' AND Created_Date LIKE '%$tanggal%') as rts,
			(SELECT COUNT(1) FROM `internal`.`sc_result` WHERE Sales_code=a.DSR_Code AND Status_1='APPROVED' AND Group_Date LIKE '%$date%') as app,
			(SELECT COUNT(1) FROM `internal`.`edc_result` WHERE Sales_code=a.DSR_Code AND Status_1='DECLINED' AND Group_Date LIKE '%$date%') as rej
			FROM `internal`.`data_sales_structure` a 
			WHERE a.SPV_Code='$sales_code' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
		return $query;
	}

	function getJmlDsrActPl($sales_code)
	{
		$tanggal = date('Y-m');
		$date = date('Y-m', strtotime('-1 month'));
		$query = $this->db->query("SELECT a.DSR_Code, Name,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` WHERE Sales_Code=a.DSR_Code AND Status = 'SEND_BCA' AND Created_Date LIKE '%$tanggal%') as inc,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` WHERE Sales_Code=a.DSR_Code AND Status='RETURN_TO_SALES' AND Created_Date LIKE '%$tanggal%') as rts,
			(SELECT COUNT(1) FROM `internal`.`apps_pl_result` WHERE Sales_code=a.DSR_Code AND Status_1='APPROVED' AND Group_Date LIKE '%$date%') as app,
			(SELECT COUNT(1) FROM `internal`.`apps_pl_result` WHERE Sales_code=a.DSR_Code AND Status_1='REJECTED' AND Group_Date LIKE '%$date%') as rej
			FROM `internal`.`data_sales_structure` a 
			WHERE a.SPV_Code='$sales_code' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
		return $query;
	}

	function getJmlDsrActCorp($sales_code)
	{
		$tanggal = date('Y-m');
		$date = date('Y-m', strtotime('-1 month'));
		$query = $this->db->query("SELECT a.DSR_Code, Name,
			(SELECT COUNT(1) FROM `internal`.`applications` WHERE Sales_Code=a.DSR_Code AND Apply_Card_Code ='6' AND Status='SEND_BCA' AND Created_Date2 LIKE '%$tanggal%') as inc,
			(SELECT COUNT(1) FROM `internal`.`applications` WHERE Sales_Code=a.DSR_Code AND Apply_Card_Code ='6' AND Status='RETURN_TO_SALES' AND Created_Date2 LIKE '%$tanggal%') as rts,
			(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` WHERE Sales_code=a.DSR_Code AND Status_1='Approved' AND Group_Date LIKE '%$date%') as app,
			(SELECT COUNT(1) FROM `internal`.`corporate_ro_result` WHERE Sales_code=a.DSR_Code AND Status_1='DECLINED' AND Group_Date LIKE '%$date%') as rej
			FROM `internal`.`data_sales_structure` a 
			WHERE a.SPV_Code='$sales_code' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
		return $query;
	}
	
	function getDataAppsAktual($sales_code, $product, $tanggal)
	{
		if($product == "CC")
		{
			$query = $this->db->query("SELECT a.DSR_Code, a.Name, 
			(SELECT COUNT(1) FROM `internal`.`applications` WHERE Sales_Code=a.DSR_Code AND Created_Date2 LIKE '%$tanggal%' AND Status='SEND_BCA' AND Apply_Card_Code in('2','4','7')) as jml
			FROM `internal`.`data_sales_structure` a 
			WHERE a.SPV_Code='$sales_code' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
			return $query;
		}elseif($product == "EDC")
		{
			$query = $this->db->query("SELECT a.DSR_Code, a.Name,
			(SELECT COUNT(1) FROM `internal`.`edc_merchant` WHERE Sales_Code=a.DSR_Code AND MID_Type = 'NEW' AND Created_Date LIKE '%$tanggal%' AND Status in ('SUBMIT_TO_BCA', 'RESUBMIT_TO_BCA')) as jml
			FROM `internal`.`data_sales_structure` a 
			WHERE a.SPV_Code='$sales_code' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
			return $query;
		}elseif($product == "SC")
		{
			$query = $this->db->query("SELECT a.DSR_Code, a.Name, 
			(SELECT COUNT(1) FROM `internal`.`apps_sc` WHERE Sales_Code=a.DSR_Code AND Created_Date LIKE '%$tanggal%' AND Status='SEND_BCA') as jml
			FROM `internal`.`data_sales_structure` a 
			WHERE a.SPV_Code='$sales_code' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
			return $query;
		}
		elseif($product == "PL")
		{
			$query = $this->db->query("SELECT a.DSR_Code, a.Name,
			(SELECT COUNT(1) FROM `internal`.`apps_pl` WHERE Sales_Code=a.DSR_Code AND Created_Date LIKE '%$tanggal%' AND Status='SEND_BCA') as jml
			FROM `internal`.`data_sales_structure` a 
			WHERE a.SPV_Code='$sales_code' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
			return $query;
		}
		elseif($product == "CORP")
		{
			$query = $this->db->query("SELECT a.DSR_Code, a.Name,
			(SELECT COUNT(1) FROM `internal`.`applications` WHERE Sales_Code=a.DSR_Code AND Created_Date2 LIKE '%$tanggal%' AND Apply_Card_Code ='6' AND Status='SEND_BCA') as jml
			FROM `internal`.`data_sales_structure` a 
			WHERE a.SPV_Code='$sales_code' AND a.Status='ACTIVE' ORDER BY a.Name ASC");
			return $query;
		}
	}
	
	function getDataAppsRtsAkt($sales_code, $product, $tanggal)
	{
		$sql = $this->db->query("SELECT
				a.DSR_Code, a.Name, a.Join_Date, a.Efektif_date, a.Product,
				(SELECT COUNT(1) FROM `internal`.`applications_temp` WHERE Sales_Code=a.DSR_Code AND Status='SEND_BCA' AND Created_Date2 LIKE '%$tanggal%') as inc_cc,
				(SELECT COUNT(1) FROM `internal`.`edc_merchant` WHERE Sales_Code=a.DSR_Code AND Status in('SUBMIT_TO_BCA','RESUBMIT_TO_BCA') AND Created_Date LIKE '%$tanggal%') as inc_edc,
				(SELECT COUNT(1) FROM `internal`.`apps_sc` WHERE Sales_Code=a.DSR_Code AND Status='SEND_BCA' AND Created_Date LIKE '%$tanggal%') as inc_sc,
				(SELECT COUNT(1) FROM `internal`.`applications_temp` WHERE Sales_Code=a.DSR_Code AND Status='RETURN_TO_SALES' AND Created_Date2 LIKE '%$tanggal%') as rts_cc,
				(SELECT COUNT(1) FROM `internal`.`edc_merchant` WHERE Sales_Code=a.DSR_Code AND Status='RETURN_TO_SALES' AND Created_Date LIKE '%$tanggal%') as rts_edc,
				(SELECT COUNT(1) FROM `internal`.`apps_sc` WHERE Sales_Code=a.DSR_Code AND Status='RETURN_TO_SALES' AND Created_Date LIKE '%$tanggal%') as rts_sc
				FROM `internal`.`data_sales_copy` a WHERE a.SPV_Code='$sales_code' AND a.Status='ACTIVE'");
		return $sql;
	}
	
	function getDataAtasan($sales_code)
	{
		$query = $this->db->query("SELECT * FROM `internal`.`data_sales_structure` WHERE DSR_Code='$sales_code'");
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
	
	function getCek($sales_code, $kategori, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_keterangan_sales` WHERE kode_pembuat='$sales_code' AND kategori='$kategori' AND created_date='$tanggal'");
		return $query;
	}
	
	function getDsrUnderPerform($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_rating_sales` WHERE SPV_Code='$sales_code' AND Rating LIKE '%Under Perform%' AND periode='$tanggal' ORDER BY Name ASC");
		return $query;
	}

	function getDsrUnderPerforms($sales_code, $tanggal, $product)
	{

		$query = $this->db->query("SELECT a.DSR_Code, a.Name, b.Product, b.Join_Date FROM `internal_sms`.`tbl_summary_apps_dsr` a INNER JOIN `internal`.`data_sales_copy` b ON a.DSR_Code=b.DSR_Code WHERE b.SPV_Code='$sales_code' AND ratings IN('Under Perform','Under Perform 1', 'Under Perform 2') AND a.Periode LIKE '%$tanggal%'");
		return $query;
	}

	function getSumUnderPerform($sales_code)
	{
		$tgl1 = date('Y-m');
		$tgl2 = date('Y-m', strtotime('-1 month'));
		$query = $this->db->query("SELECT 
			(SELECT (Inc_cc + Inc_edc + Inc_sc) FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tgl1') as inc,
			(SELECT (Rts_cc + Rts_edc + Rts_sc) FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tgl1') as rts,
			(SELECT (Noa_cc + Noa_edc + Noa_sc) FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tgl1') as noa,
			(SELECT (Dec_cc + Dec_edc + Dec_sc) FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tgl1') as decl,
			(SELECT (Apprate_cc + Apprate_edc + Apprate_sc) FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tgl1') as apprate,
			(SELECT ratings FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tgl1') as ratingnya,
			(SELECT (Inc_cc + Inc_edc + Inc_sc) FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tgl2') as inc_ls,
			(SELECT (Rts_cc + Rts_edc + Rts_sc) FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tgl2') as rts_ls,
			(SELECT (Noa_cc + Noa_edc + Noa_sc) FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tgl2') as noa_ls,
			(SELECT (Dec_cc + Dec_edc + Dec_sc) FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tgl2') as decl_ls,
			(SELECT (Apprate_cc + Apprate_edc + Apprate_sc) FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tgl2') as apprate_ls,
			(SELECT ratings FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tgl2') as rating_ls");
		return $query;
	}
	
	function getDataKomitmen($sales_code, $date)
	{
		$query = $this->db->query("SELECT
			SUM(IF(a.kategori='JUMLAH_DSR',1,0)) as jmldsr,
			SUM(IF(a.kategori='APLIKASI',1,0)) as aplikasi,
			SUM(IF(a.kategori='RTS',1,0)) as rts,
			SUM(IF(a.kategori='UNDER_PERFORM',1,0)) as under_perform,
			SUM(IF(a.kategori='APPROVAL_RATE',1,0)) as app_rate,
			SUM(IF(a.kategori='ACCOUNT',1,0)) as account
			FROM `internal_sms`.`tbl_keterangan_sales` a WHERE a.kode_pembuat='$sales_code' AND a.created_date='$date'");
		return $query;
	}
	
	function getKomitmen($sales_code, $kategori)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_keterangan_sales` WHERE kode_pembuat='$sales_code' AND kategori='$kategori'");
		return $query;
	}
	
	function getDataUnderPerform($sales_code, $periode)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_rating_sales` WHERE SPV_Code='$sales_code' AND Rating='Under Perform' AND periode LIKE '%$periode%' order by Name ASC");
		return $query;
	}

	function getAplications($sales_code, $product, $tanggal)
	{
		if($product == "CC")
		{
			$query = $this->db->query("SELECT
				SUM(IF(Status='SEND_BCA',1,0)) as inc,
				SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
				FROM `internal`.`applications` WHERE Sales_Code='$sales_code' AND Apply_Card_Code IN('2','4','7') AND Created_Date2 LIKE '%$tanggal%'");
			return $query;
		}elseif($product == "EDC"){
			$query = $this->db->query("SELECT
				SUM(IF(Status='SUBMIT_TO_BCA' AND Status='RESUBMIT_TO_BCA',1,0)) as inc,
				SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
				FROM `internal`.`edc_merchant` WHERE Sales_Code='$sales_code' AND MID_Type='NEW' AND Created_Date LIKE '%$tanggal%'");
			return $query;
		}elseif($product == "SC"){
			$query = $this->db->query("SELECT
				SUM(IF(Status='SEND_BCA',1,0)) as inc,
				SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
				FROM `internal`.`apps_sc` WHERE Sales_Code='$sales_code' AND Created_Date LIKE '%$tanggal%'");
			return $query;
		}elseif($product == "PL"){
			$query = $this->db->query("SELECT
				SUM(IF(Status='SEND_BCA',1,0)) as inc,
				SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
				FROM `internal`.`apps_pl` WHERE Sales_Code='$sales_code' AND Created_Date LIKE '%$tanggal%'");
			return $query;
		}elseif($product == "CORP"){
			$query = $this->db->query("SELECT
				SUM(IF(Status='SEND_BCA',1,0)) as inc,
				SUM(IF(Status='RETURN_TO_SALES',1,0)) as rts
				FROM `internal`.`applications` WHERE Sales_Code='$sales_code' AND Apply_Card_Code='6' AND Created_Date2 LIKE '%$tanggal%'");
			return $query;
		}
	}

	function getApprove($sales_code, $date)
	{
		$query = $this->db->query("SELECT approve, reject, Rating FROM `internal_sms`.`tbl_rating_sales` WHERE DSR_Code='$sales_code' AND periode LIKE '%$date%' ORDER BY Name ASC");
		return $query;
	}

	// HERE KNOW

	function getAllIncoming($sales_code, $ls_date)
	{
		$query = $this->db->query("SELECT 
									SUM(Inc_cc) as inc_cc, 
									SUM(Inc_edc) as inc_edc, 
									SUM(Inc_sc) as inc_sc,
									SUM(Noa_cc) as noa_cc,
									SUM(Noa_edc) as noa_edc,
									SUM(Noa_sc) as noa_sc,
									SUM(Dec_cc) as dec_cc,
									SUM(Dec_edc) as dec_edc,
									SUM(Dec_sc) as dec_sc,
									Rating_cc as rating_cc,
									Rating_edc as rating_edc,
									Rating_sc as rating_sc,
									Apprate_cc as apprate_cc,
									Apprate_edc as apprate_edc,
									Apprate_sc as apprate_sc,
									FROM `internal_sms`.`tbl_summary_apps` WHERE DSR_Code='$sales_code' AND Periode='$ls_date'");
		return $query;
	}

	function getAllIncomingCurrent($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT
				(SELECT COUNT(1) FROM `internal`.`applications` WHERE Sales_Code='$sales_code' AND Status='SEND_BCA' AND Created_Date2 LIKE '%$tanggal%' AND Apply_Card_Code IN('2','4','7')) as inc_cc,
				(SELECT COUNT(1) FROM `internal`.`edc_merchant` WHERE Sales_Code='$sales_code' AND Status IN('SUBMIT_TO_BCA','RESUBMIT_TO_BCA') AND Created_Date LIKE '%$tanggal%' AND MID_Type='NEW') as inc_edc,
				(SELECT COUNT(1) FROM `internal`.`apps_sc` WHERE Sales_Code='$sales_code' AND Status ='SEND_BCA' AND Created_Date LIKE '%$tanggal%') as inc_sc,
				(SELECT COUNT(1) FROM `internal`.`applications` WHERE Sales_Code='$sales_code' AND Status='RETURN_TO_SALES' AND Created_Date2 LIKE '%$tanggal%' AND Apply_Card_Code IN('2','4','7')) as rts_cc,
				(SELECT COUNT(1) FROM `internal`.`edc_merchant` WHERE Sales_Code='$sales_code' AND Status ='RETURN_TO_SALES' AND Created_Date LIKE '%$tanggal%' AND MID_Type='NEW') as rts_edc,
				(SELECT COUNT(1) FROM `internal`.`apps_sc` WHERE Sales_Code='$sales_code' AND Status ='RETURN_TO_SALES' AND Created_Date LIKE '%$tanggal%') as rts_sc
				");
		return $query;
	}

	function getAllNoaCurrent($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $query;
	}

	function getAllAps($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_summary_apps_dsr` WHERE DSR_Code='$sales_code' AND Periode='$tanggal'");
		return $query;
	}

	function getCustNameCc($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT Customer_Name as Apps_name FROM `internal`.`applications_temp`
		 WHERE Sales_Code='$sales_code' AND Status='RETURN_TO_SALES' AND Apply_Card_Code IN ('2','4','7') AND Created_Date2 LIKE '%$tanggal%'");
		return $query;
	}

	function getCustNameEdc($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT Merchant_Name as Apps_name FROM `internal`.`edc_merchant`
		 WHERE Sales_Code='$sales_code' AND Status='RETURN_TO_SALES' AND MID_Type='NEW' AND Created_Date LIKE '%$tanggal%'");
		return $query;
	}

	function getCustNameSc($sales_code, $tanggal)
	{
		$query = $this->db->query("SELECT Customer_Name as Apps_name FROM `internal`.`apps_sc`
		 WHERE Sales_Code='$sales_code' AND Status='RETURN_TO_SALES' AND Created_Date LIKE '%$tanggal%'");
		return $query;
	}
	
	function getCountNoa($sales_code, $tanggal)
	{
		$sql = $this->db->query("SELECT Total_Noa FROM `tbl_summary_apps_spv` WHERE DSR_Code='$sales_code' AND Periode LIKE '%$tanggal%'");
		return $sql;
	}
	
	
}