<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Sc_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	// START UPDATE By m.a
	private function _get_dataTable_query($where, $groups, $groupDate)
    {
		$column_order = array(null,'Sales_Code','Sales_Name'); //field yang ada di table recruitment
		$column_search = array('Sales_Code','Sales_Name'); //field yang diizin untuk pencarian 
		//$order = array('Branch' => 'ASC'); // default order
		$this->db->select('*');
        $this->db->from('`internal`.`application_process`');
		if($where){
			$this->db->where($where);
		}
		$this->db->where("Group_Date", $groupDate);
		$this->db->group_by("$groups");

		$i = 0;
		foreach ($column_search as $item) // looping awal
		{
			if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
			{
				if($i===0){ // looping awal
					$this->db->group_start(); 
					$this->db->like($item, $_POST['search']['value']);
				}
				else{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($column_search) - 1 == $i){
					$this->db->group_end();
				}
			}
			$i++;
		}
		$this->db->order_by("id", "DESC");
    }

	// get datatable query part 1
	function get_datatables($where, $groups, $groupDate)
    {
        $this->_get_dataTable_query($where, $groups, $groupDate);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query;
		$query->free_result();
    }

	// count datatable query
    function count_filtered($where, $groups, $groupDate)
    {
        $this->_get_dataTable_query($where, $groups, $groupDate);
        $query = $this->db->get();
        return $query->num_rows();
    }
	
	function breakdown_sc_dsr($sales_code, $group_date)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as count_app_sc,
			COALESCE(SUM(IF(Status_1 = 'APPROVED',1,0)),0) as approve,
			COALESCE(SUM(IF(Status_1 = 'INPROCESS',1,0)),0) as inprocess,
			COALESCE(SUM(IF(Status_1 IN('CANCEL','CANCELED'),1,0)),0) as cancel,
			COALESCE(SUM(IF(Status_1 = 'DECLINED',1,0)),0) as decline
			FROM `internal`.`sc_result`
			WHERE Group_Date = '$group_date'
			AND (Sales_Code ='$sales_code'
				OR SPV_Code ='$sales_code'
				OR ASM_Code ='$sales_code'
				OR RSM_Code ='$sales_code'
				OR BSH_Code ='$sales_code'
			)
		")->row();
		return $query;
		$query->free_result();
	}

	function breakdown_sc($sales_code, $group_date)
	{
		$nik 		= $this->session->userdata('sl_code');
		$position 	= $this->session->userdata('position');
		$upPosition = $position.'_Code';
		
		$query = $this->db->query("SELECT 
			COUNT(1) as count_app_cc,
			COALESCE(SUM(IF(Status_1 = 'APPROVED',1,0)),0) as approve,
			COALESCE(SUM(IF(Status_1 = 'INPROCESS',1,0)),0) as inprocess,
			COALESCE(SUM(IF(Status_1 IN('CANCEL','CANCELED'),1,0)),0) as cancel,
			COALESCE(SUM(IF(Status_1 = 'DECLINED',1,0)),0) as decline
			FROM `internal`.`sc_result`
			WHERE Group_Date = '$group_date'
			AND (Sales_Code ='$sales_code'
				OR SPV_Code ='$sales_code'
				OR ASM_Code ='$sales_code'
				OR RSM_Code ='$sales_code'
				OR BSH_Code ='$sales_code'
			)
			AND $upPosition = '$nik'
		")->row();
		return $query;
		$query->free_result();
	}
	
	function breakdown_sc_detail($sales_code, $var, $upliner, $group_date)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as count_app_cc,
			COALESCE(SUM(IF(Status_1 = 'APPROVED',1,0)),0) as approve,
			COALESCE(SUM(IF(Status_1 = 'INPROCESS',1,0)),0) as inprocess,
			COALESCE(SUM(IF(Status_1 IN('CANCEL','CANCELED'),1,0)),0) as cancel,
			COALESCE(SUM(IF(Status_1 = 'DECLINED',1,0)),0) as decline
			FROM `internal`.`sc_result`
			WHERE Group_Date = '$group_date'
			AND (Sales_Code ='$sales_code'
				OR SPV_Code ='$sales_code'
				OR ASM_Code ='$sales_code'
				OR RSM_Code ='$sales_code'
				OR BSH_Code ='$sales_code'
			)
			AND $var = '$upliner'
		")->row();
		return $query;
		$query->free_result();
	}
	
	function getBreakdownSc($sales_code, $status, $tgl)
	{
		if($status == "APPROVE")
		{
			$filter = " AND Status_1 in ('APPROVED', 'Approved')";
		}
		elseif($status == "INPROCESS")
		{
			$filter = " AND Status_1 = 'INPROCESS'";
		}
		elseif($status == "CANCEL")
		{
			$filter = " AND Status_1 = 'CANCEL'";
		}elseif($status == "DECLINE")
		{
			$filter = " AND Status_1 = 'DECLINED'";
		}else
		{
			$filter = " AND Status_1 = '$status'";
		}
		
		$query = $this->db->query("SELECT * 
									FROM `internal`.`sc_result`
									WHERE (Sales_Code ='$sales_code' AND Group_Date = '$tgl' $filter)
									OR (SPV_Code ='$sales_code' AND Group_Date = '$tgl' $filter)
									OR (ASM_Code ='$sales_code' AND Group_Date = '$tgl' $filter)
									OR (RSM_Code ='$sales_code' AND Group_Date = '$tgl' $filter)
									OR (BSH_Code ='$sales_code' AND Group_Date = '$tgl' $filter) 
								");
		return $query;
		$query->free_result();
	}
	
	function get_last_period()
	{
		$query = $this->db->query("SELECT Group_Date FROM internal.sc_result ORDER BY Group_Date DESC LIMIT 1");
		return $query;
		$query->free_result();
	}

	function getBreakdownSCexport($tgl)
	{

		$nik 		= $this->session->userdata('sl_code');
		$position 	= $this->session->userdata('position');
		if ($position == 'BSH') {
			$where = "BSH_Code = '$nik'";
		} else if ($position == 'RSM') {
			$where = "RSM_Code = '$nik'";
		} else if ($position == 'ASM') {
			$where = "ASM_Code = '$nik'";
		} else if ($position == 'SPV') {
			$where = "SPV_Code = '$nik'";
		} else {
			$where = "Sales_Code = '$nik'";
		}
		$query = $this->db->query("SELECT * 
									FROM `internal`.`sc_result`
									WHERE $where AND Group_Date = '$tgl'
								");
		return $query;
		$query->free_result();
	}
}