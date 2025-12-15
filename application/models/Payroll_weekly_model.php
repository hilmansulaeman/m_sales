<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Payroll_weekly_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getPeriode($sales_code)
	{
		$query = $this->db->query("SELECT periode FROM `internal_sms`.`tbl_new_estimasi` WHERE sales_code='$sales_code' ORDER BY periode DESC limit 3");
		return $query;
		$query->free_result();
	}
	
	function getCountPayroll($sales_code, $periode)
	{
		$query = $this->db->query("SELECT COUNT(1) as counts FROM `internal_sms`.`tbl_new_estimasi` WHERE sales_code='$sales_code' AND periode='$periode'");
		return $query;
		$query->free_result();
	}
	
	function getDataPayroll($sales_code, $periode)
	{
		$query = $this->db->query("SELECT * FROM `internal_sms`.`tbl_new_estimasi` WHERE sales_code='$sales_code' AND periode='$periode'");
		return $query;
		$query->free_result();
	}
	
	
}