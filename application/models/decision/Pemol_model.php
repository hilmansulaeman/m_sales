<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pemol_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// get datatable query part 2
	private function _get_dataTable_query($where, $groups, $groupDate)
    {
		$column_order = array(null,'Sales_Code','Sales_Name','SPV_Name','ASM_Name','RSM_Name','Branch','Position'); //field yang ada di table recruitment
		$column_search = array('Sales_Code','Sales_Name','SPV_Name','ASM_Name','RSM_Name','Branch','Position'); //field yang diizin untuk pencarian 
		//$order = array('Branch' => 'ASC'); // default order
		$this->db->select('*');
        $this->db->from('data_upload_oa_pemol_detail');
		// $this->db->where("(CASE WHEN Position = 'SPV' THEN Status = 'ACTIVE' AND Product = 'PEMOL'
		//                     WHEN Position = 'DSR' THEN Status = 'ACTIVE' AND Product = 'PEMOL'
		// 					WHEN Position = 'SPG' THEN Status = 'ACTIVE' AND Product = 'PEMOL'
		// 					WHEN Position = 'SPB' THEN Status = 'ACTIVE' AND Product = 'PEMOL'
		// 					ELSE Status = 'ACTIVE'
		// 					END)");
		if($where){
			$this->db->where($where);
		}
		$this->db->where("Group_Date", $groupDate);
		$this->db->group_by($groups);

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
		$this->db->order_by('Position', 'DESC');
			
		if(isset($_POST['order'])) 
		{
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($order))
		{
			$this->db->order_by(key($order), $order[key($order)]);
		}
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
	
	// get Pemol DSR
	function get_pemol_dsr($sales,$group_date)
	{
		$query = $this->db->query("SELECT
			SUM(IF(`Status` = 'OA', 1, 0)) AS oa,
			SUM(IF(`Status` = 'SN', 1, 0)) AS sn,
			SUM(IF(`Status` = 'SK', 1, 0)) AS sk,
			SUM(IF(`Status` = 'SD', 1, 0)) AS sd,
			SUM(IF(`Status` = 'KTB', 1, 0)) AS ktb
			FROM `data_upload_oa_pemol_detail` 
			WHERE Sales_Code = '$sales'
			AND Group_Date = '$group_date'
		")->row();
		return $query;
		$query->free_result();
	}

	// get Pemol
	function get_pemol($var,$sales,$group_date)
	{
		$nik 		= $this->session->userdata('sl_code');
		$position 	= $this->session->userdata('position');
		$upPosition = $position.'_Code';
		
		$query = $this->db->query("SELECT
			-- SUM(IF(`Status` = 'OA', 1, 0)) AS oa,
			SUM(IF(Source = 'MyBCA' AND `Status` = 'OA', 1, 0)) AS my_oa,
			SUM(IF(Source = 'mobileBCA' AND `Status` = 'OA', 1, 0)) AS mb_oa,
			-- SUM(IF(`Status` = 'SN', 1, 0)) AS sn,
			SUM(IF(`Status` = 'NE', 1, 0)) AS ne,
			SUM(IF(`Status` = 'SK', 1, 0)) AS sk,
			-- SUM(IF(`Status` = 'SD', 1, 0)) AS sd,
			SUM(IF(`Status` = 'KTB', 1, 0)) AS ktb,
			COUNT(1) AS total
			FROM `data_upload_oa_pemol_detail` 
			WHERE $var = '$sales'
			AND Group_Date = '$group_date' 
			AND $upPosition = '$nik'
		")->row();
		return $query;
		$query->free_result();
	}
	
	// get Pemol detail
	function get_pemol_detail($var,$sales,$var2,$upliner,$group_date)
	{
		$query = $this->db->query("SELECT
			-- SUM(IF(`Status` = 'OA', 1, 0)) AS oa,
			SUM(IF(Source = 'MyBCA' AND `Status` = 'OA', 1, 0)) AS my_oa,
			SUM(IF(Source = 'BCA Mobile' AND `Status` = 'OA', 1, 0)) AS mb_oa,
			-- SUM(IF(`Status` = 'SN', 1, 0)) AS sn,
			SUM(IF(`Status` = 'NE', 1, 0)) AS ne,
			SUM(IF(`Status` = 'SK', 1, 0)) AS sk,
			-- SUM(IF(`Status` = 'SD', 1, 0)) AS sd,
			SUM(IF(`Status` = 'KTB', 1, 0)) AS ktb
			FROM `data_upload_oa_pemol_detail` 
			WHERE $var = '$sales'
			AND Group_Date = '$group_date' 
			AND $var2 = '$upliner'
		")->row();
		return $query;
		$query->free_result();
	}

	// get Pemol Dummy
	function get_pemolDummy($sales,$group_date,$part)
	{
		if ($part == 'rsm') {
			$query = $this->db->query("SELECT count(1) as total
								FROM data_upload_oa_pemol_detail
								WHERE RSM_Code = '0'
								AND BSH_Code = '$sales'
								AND Group_date = '$group_date'
							")->row();
		}
		else if($part == 'asm') {
			$query = $this->db->query("SELECT count(1) as total
											FROM data_upload_oa_pemol_detail
											WHERE ASM_Code = '0'
											AND RSM_Code = '$sales'
											AND Group_date = '$group_date'
										")->row();
		}
		else if($part == 'spv') {
			$query = $this->db->query("SELECT count(1) as total
										FROM data_upload_oa_pemol_detail
										WHERE SPV_Code = '0'
										AND ASM_Code = '$sales'
										AND Group_date = '$group_date'
									")->row();
		}
		return $query;
		// $query->free_result();
	}
	
	function get_last_period()
	{
		$query = $this->db->query("SELECT Group_Date FROM data_upload_oa_pemol ORDER BY Group_Date DESC LIMIT 1");
		return $query;
		$query->free_result();
	}
	
	function get_pemol_export($tgl)
	{
		$nik 		= $this->session->userdata('sl_code');
		$position 	= $this->session->userdata('position');
		if ($position == 'BSH') {
			$where = "BSH_Code = '$nik'";
		}
		else if ($position == 'RSM') {
			$where = "RSM_Code = '$nik'";
		}
		else if ($position == 'ASM') {
			$where = "ASM_Code = '$nik'";
		}
		else if ($position == 'SPV') {
			$where = "SPV_Code = '$nik' OR ASM_Code = '$nik'";
		}
		else {
			$where = "Sales_Code = '$nik'";
		}
		$this->db->select('*');
		$this->db->from('data_upload_oa_pemol_detail');
		$this->db->where('Group_Date', $tgl);
		$this->db->where($where);
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}

}
