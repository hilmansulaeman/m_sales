<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Global query library
 */
 
class Global_query
{
	var $CI = NULL;
	
	function __construct()
	{
		// get CI's object
		$this->CI =& get_instance();
	}
	
	//get count team
	function get_count_team(){
		$sales_code = $this->CI->session->userdata('sl_code');
		$posisi = $this->CI->session->userdata('position');
		if($posisi == "GM"){
			$where = "";
		}
		elseif($posisi == "BSH"){
			$where = " AND BSH_Code = '$sales_code'";
		}
		elseif($posisi == "RSM"){
			$where = " AND RSM_Code = '$sales_code'";
		}
		elseif($posisi == "ASM"){
			$where = " AND ASM_Code = '$sales_code'";
		}
		elseif($posisi == "SPV"){
			$where = " AND SPV_Code = '$sales_code'";
		}
		else{
			$where = " AND DSR_Code = '$sales_code'";
		}
		$query = $this->CI->db->query("SELECT
			COALESCE(SUM(if(Position = 'RSM',1,0))) as RSM,
			COALESCE(SUM(if(Position = 'ASM',1,0))) as ASM,
			COALESCE(SUM(if(Position = 'SPV',1,0))) as SPV,
			COALESCE(SUM(if(Position IN('DSR','SPG','SPB','Mobile Sales'),1,0))) as DSR
			FROM internal.data_sales_structure WHERE Status='ACTIVE' $where"
		);
		if($query->num_rows() == 0){ 
			return false;
		}
		else{
			return $query;
			$query->free_result();
		}
	}

}

