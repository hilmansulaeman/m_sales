<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Approval_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getRecruitment($sales_code,$position)
	{
		if($position == "SPV"){
				$query = $this->db->query("SELECT * FROM `internal`.`data_recruitment` where upliner='$sales_code' and approve1='0' order by id desc ");
				return $query;			
		}elseif($position == "ASM"){
				$query = $this->db->query("SELECT 
												a.* 
											FROM 
												`internal`.`data_recruitment` a 
											inner join 
												`internal`.`data_sales_structure` b
												on a.upliner=b.dsr_code
											where b.asm_code='$sales_code' and b.status='active' and a.approve1='1' and a.approve2='0'  order by a.id desc ");
				 						
				return $query;			
		}	 
	}
	function getRecruitment_list($sales_code,$position)
	{
		
		if($position == "SPV"){				
				$query = $this->db->query("SELECT * FROM `internal`.`data_recruitment` where upliner='$sales_code' and approve1 <> '0' order by id desc ");
				return $query;			
		}elseif($position == "ASM"){
				$query = $this->db->query("SELECT 
												a.* 
											FROM 
												`internal`.`data_recruitment` a 
											inner join 
												`internal`.`data_sales_structure` b
												on a.upliner=b.dsr_code
											where b.asm_code='$sales_code' and b.status='active' and a.approve1='1' and a.approve2 <> '0'  order by a.id desc ");
				 						
				return $query;			
		}	  
	}	
	function getRecruitment_id($id)
	{
		$query = $this->db->query("SELECT a.*,b.name as nama_perekrut 
								   FROM 
									`internal`.`data_recruitment` a 
									INNER JOIN `internal`.`data_employee` b on a.recruiter=b.nik
								   where id='$id' order by id desc ");
		return $query;
	}
	function getSpvAktual($sales_code)
	{
		$query = $this->db->query("SELECT COUNT(1) jmlspv FROM `internal`.`data_sales_copy` WHERE ASM_Code='$sales_code' AND Position='SPV' AND Status='ACTIVE'");
		return $query;
	}
	function get_all_email($nik)
	{
		$query = $this->db->query("SELECT * FROM `internal`.`data_employee` WHERE nik='$nik' AND status='ACTIVE'");
		//echo "SELECT * FROM `internal`.`data_employee` WHERE nik='$nik' AND status='ACTIVE'";
		return $query;
	}
	function update_approve($data,$id)
	{
		$this->db->where('id',$id);
		$this->db->update('`internal`.`data_recruitment`',$data);
	}
 
}