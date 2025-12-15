<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Slipincentive_model extends CI_Model
{
	function __construct()
	{
		
	}
	
	function getPeriode($sales_code)
	{
		$query = $this->db->query("SELECT DISTINCT(Periode_Payroll) as periode FROM `internal`.`data_payroll` WHERE Sales_Code='$sales_code' Order By ID DESC limit 3");
		return $query;
		$query->free_result();
	}
	
	function dataIncentive($sales_code, $Periode_Payroll)
	{
		$query = $this->db->query("SELECT * FROM `internal`.`data_payroll` WHERE Sales_Code='$sales_code' AND Periode_Payroll LIKE '%$Periode_Payroll%'");
		return $query;
		$query->free_result();
	}
}