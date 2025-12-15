<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Filter_incoming_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function view($sales_code, $field, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT 
				(SELECT COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code in('2','4') AND 
				status in('SEND_BCA','RESEND_BCA','SEND_DIKA','DUPLICATE','DUPLICATE_DIKA','DUPLICATE_BCA','RETURN_TO_SALES','RETURN_FROM_BCA','PENDING_FU','PROJECT','REJECT','CANCEL','DUPLICATE_HP') AND tgl_input BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as cc, 
				(SELECT COUNT(1) FROM `tbl_incoming_edc` WHERE $field='$sales_code' AND mid_type in('NEW','CUP') AND tgl_input BETWEEN '$tgl1 00:00:00 23:59:59' AND '$tgl2') as edc, 
				(SELECT COUNT(1) FROM `tbl_incoming_sc` WHERE $field='$sales_code' AND tgl_input BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as sc, 
				(SELECT COUNT(1) FROM `tbl_incoming_pl` WHERE $field='$sales_code' AND tgl_input BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as pl, 
				(SELECT COUNT(1) FROM `tbl_incoming_coporate` WHERE $field='$sales_code' AND tgl_input BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as corp,
				(SELECT COUNT(1) FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code in('7') AND 
				status in('SEND_BCA','RESEND_BCA','SEND_DIKA','DUPLICATE','DUPLICATE_DIKA','DUPLICATE_BCA','RETURN_TO_SALES','RETURN_FROM_BCA','PENDING_FU','PROJECT','REJECT','CANCEL','DUPLICATE_HP') AND tgl_input BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59') as tele");
		return $query;
		$query->free_result();
	}

	function breakdown_sts_cc($sales_code, $field, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
				SUM(IF(status='SEND_BCA',1,0)) AS send_bca,
				SUM(IF(status='RESEND_BCA',1,0)) AS resend_bca,
				SUM(IF(status='SEND_DIKA',1,0)) AS send_dika,
				SUM(IF(status='DUPLICATE' OR status='DUPLICATE_DIKA' ,1,0)) AS duplicate_dika,
				SUM(IF(status='DUPLICATE_BCA',1,0)) AS duplicate_bca,
				SUM(IF(status='DUPLICATE_HP',1,0)) AS duplicate_hp,
				SUM(IF(status='RETURN_TO_SALES',1,0)) AS return_to_sales,
				SUM(IF(status='RETURN_FROM_BCA',1,0)) AS return_from_bca,
				SUM(IF(status='PENDING_FU',1,0)) AS pending_fu,
				SUM(IF(status='PROJECT' OR status='REJECT',1,0)) AS reject_by_dika,
				SUM(IF(status='CANCEL',1,0)) AS cancel
				FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code in('2','4') AND tgl_input BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'");
		return $query;
		$query->free_result();
	}

	function breakdown_sts_edc($sales_code, $field, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
				SUM(IF(status='SUBMIT_TO_BCA',1,0)) AS submit_to_bca, 
				SUM(IF(status='SUBMIT_TO_DIKA',1,0)) AS submit_to_dika, 
				SUM(IF(status='RETURN_TO_SALES',1,0)) AS return_to_sales, 
				SUM(IF(status='RETURN_TO_DIKA',1,0)) AS return_to_dika, 
				SUM(IF(status='RETURN_FROM_BCA',1,0)) AS return_from_bca, 
				SUM(IF(status='RESUBMIT_TO_BCA',1,0)) AS resubmit_to_bca,
				SUM(IF(status='REJECT',1,0)) AS reject_by_dika,
				SUM(IF(status='CANCEL',1,0)) AS cancel 
				FROM `tbl_incoming_edc` WHERE $field='$sales_code' AND mid_type in('NEW','CUP') AND tgl_input >= '$tgl1' AND tgl_input <= '$tgl2'");
		return $query;
		$query->free_result();
	}

	function breakdown_sts_sc($sales_code, $field, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
				SUM(IF(status='SEND_BCA',1,0)) AS send_bca,
				SUM(IF(status='SEND_DIKA',1,0)) AS send_dika,
				SUM(IF(status='DUPLICATE',1,0)) AS duplicate,
				SUM(IF(status='RETURN_TO_SALES',1,0)) AS return_to_sales,
				SUM(IF(status='PROJECT' OR status='REJECT',1,0)) AS reject_by_dika,
				SUM(IF(status='CANCEL',1,0)) AS cancel
				FROM `tbl_incoming_sc` WHERE $field='$sales_code' AND tgl_input >= '$tgl1' AND tgl_input <= '$tgl2'");
		return $query;
		$query->free_result();
	}
	
	function breakdown_sts_pl($sales_code, $field, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
				SUM(IF(status='SEND_BCA',1,0)) AS send_bca,
				SUM(IF(status='SEND_DIKA',1,0)) AS send_dika,
				SUM(IF(status='DUPLICATE',1,0)) AS duplicate,
				SUM(IF(status='RETURN_TO_SALES',1,0)) AS return_to_sales,
				SUM(IF(status='PROJECT' OR status='REJECT',1,0)) AS project,
				SUM(IF(status='CANCEL',1,0)) AS cancel
				FROM `tbl_incoming_pl` WHERE $field='$sales_code' AND tgl_input >= '$tgl1' AND tgl_input <= '$tgl2'");
		return $query;
		$query->free_result();
	}
	
	function breakdown_sts_corp($sales_code, $field, $tgl1, $tgl2)
	{
		
		$query = $this->db->query("SELECT
						SUM(IF(status='SEND_BCA',1,0)) AS send_bca,
						SUM(IF(status='SEND_DIKA',1,0)) AS send_dika,
						SUM(IF(status='DUPLICATE' OR status='DUPLICATE_DIKA' ,1,0)) AS duplicate_dika,
						SUM(IF(status='RETURN_TO_SALES',1,0)) AS return_to_sales,
						SUM(IF(status='RETURN_FROM_BCA',1,0)) AS return_from_bca,
						SUM(IF(status='PENDING_FU',1,0)) AS pending_fu,
						SUM(IF(status='PROJECT' OR status='REJECT',1,0)) AS reject_by_dika,
						SUM(IF(status='CANCEL',1,0)) AS cancel
						FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code='6' AND tgl_input >= '$tgl1' AND tgl_input <= '$tgl2'");
		return $query;
		$query->free_result();
	}
	
	function breakdown_sts_tele($sales_code, $field, $tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
				SUM(IF(status='SEND_BCA',1,0)) AS send_bca,
				SUM(IF(status='RESEND_BCA',1,0)) AS resend_bca,
				SUM(IF(status='SEND_DIKA',1,0)) AS send_dika,
				SUM(IF(status='DUPLICATE' OR status='DUPLICATE_DIKA' ,1,0)) AS duplicate_dika,
				SUM(IF(status='DUPLICATE_BCA',1,0)) AS duplicate_bca,
				SUM(IF(status='DUPLICATE_HP',1,0)) AS duplicate_hp,
				SUM(IF(status='RETURN_TO_SALES',1,0)) AS return_to_sales,
				SUM(IF(status='RETURN_FROM_BCA',1,0)) AS return_from_bca,
				SUM(IF(status='PENDING_FU',1,0)) AS pending_fu,
				SUM(IF(status='PROJECT' OR status='REJECT',1,0)) AS reject_by_dika,
				SUM(IF(status='CANCEL',1,0)) AS cancel
				FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code in('7') AND tgl_input BETWEEN '$tgl1 00:00:00' AND '$tgl2 23:59:59'");
		return $query;
		$query->free_result();
	}
	
	function detail_breakdown_cc($sales_code, $field, $tgl1, $tgl2, $status)
	{
		if($status == "DUPLICATE")
		{
			$filter = " AND status in('DUPLICATE', 'DUPLICATE_DIKA')";
		}
		elseif($status == "PROJECT")
		{
			$filter = " AND status IN('PROJECT', 'REJECT')";
		}
		else
		{
			$filter = " AND status='$status'";
		}
		$query = $this->db->query("SELECT customer_name, sales_name, tgl_input FROM `tbl_incoming_cc` 
								WHERE $field='$sales_code' AND apply_card_code in('2','4') AND tgl_input >= '$tgl1' AND tgl_input <= '$tgl2' $filter");
		return $query;
		$query->free_result();
	}
	
	function detail_breakdown_edc($sales_code, $field, $tgl1, $tgl2, $status)
	{
		$query = $this->db->query("SELECT merchant_name, sales_name, tgl_input 
				FROM `tbl_incoming_edc` WHERE $field='$sales_code' AND mid_type in('NEW','CUP') AND tgl_input >= '$tgl1' AND tgl_input <= '$tgl2' AND status='$status'");
		return $query;
		$query->free_result();
	}
	
	function detail_breakdown_sc($sales_code, $field, $tgl1, $tgl2, $status)
	{
		IF($status == "PROJECT")
		{
			$filter = " AND status in('PROJECT', 'REJECT')";
		}
		ELSE
		{
			$filter = " AND status='$status'";
		}
		$query = $this->db->query("SELECT customer_name, sales_name, tgl_input
				FROM `tbl_incoming_sc` WHERE $field='$sales_code' AND tgl_input >= '$tgl1' AND tgl_input <= '$tgl2' $filter");
		return $query;
		$query->free_result();
	}
	
	function detail_breakdown_pl($sales_code, $field, $tgl1, $tgl2, $status)
	{
		if($status == "PROJECT")
		{
			$filter = " AND status in('PORJECT', 'REJECT')";
		}
		else
		{
			$filter = " AND status='$status'";
		}
		$query = $this->db->query("SELECT debt_name, sales_name, tgl_input
				FROM `tbl_incoming_pl` WHERE $field='$sales_code' AND tgl_input >= '$tgl1' AND tgl_input <= '$tgl2' AND status='$status'");
		return $query;
		$query->free_result();
	}
	
	function detail_breakdown_corp($sales_code, $field, $tgl1, $tgl2, $status)
	{
		if($status == "DUPLICATE")
		{
			$filter = " AND status in('DUPLICATE', 'DUPLICATE_DIKA')";
		}
		elseif($status == "PROJECT")
		{
			$filter = " AND status IN ('PROJECT', 'REJECT')";
		}
		else
		{
			$filter = " AND status='$status'";
		}
		
		$query = $this->db->query("SELECT customer_name, sales_name, tgl_input
						FROM `tbl_incoming_cc` WHERE $field='$sales_code' AND apply_card_code='6' AND tgl_input >= '$tgl1' AND tgl_input <= '$tgl2' $filter");
		return $query;
		$query->free_result();
	}

	function detail_breakdown_tele($sales_code, $field, $tgl1, $tgl2, $status)
	{
		if($status == "DUPLICATE")
		{
			$filter = " AND status in('DUPLICATE', 'DUPLICATE_DIKA')";
		}
		elseif($status == "PROJECT")
		{
			$filter = " AND status IN('PROJECT', 'REJECT')";
		}
		else
		{
			$filter = " AND status='$status'";
		}
		$query = $this->db->query("SELECT customer_name, sales_name, tgl_input FROM `tbl_incoming_cc` 
								WHERE $field='$sales_code' AND apply_card_code in('7') AND tgl_input >= '$tgl1' AND tgl_input <= '$tgl2' $filter");
		return $query;
		$query->free_result();
	}
	
	
	
	
	
	
	
	
}