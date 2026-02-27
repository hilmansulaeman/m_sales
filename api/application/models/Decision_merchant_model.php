<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Decision_merchant_model extends MY_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function empty_response()
	{
		$response['status']=502;
		$response['error']=true;
		$response['message']='Field tidak boleh kosong';
		return $response;
	}
	
	// get datatable query part 2
	private function _get_dataTable_query($where, $groups, $groupDate)
    {
		$column_order = array(null,'DSR_Code','Name','Branch','Position'); //field yang ada di table recruitment
		$column_search = array('DSR_Code','Name','Branch','Position'); //field yang diizin untuk pencarian 
		//$order = array('Branch' => 'ASC'); // default order
		$this->db->select('*');
        $this->db->from('`internal`.`edc_result`');
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
			if($this->input->post('search')) // jika datatable mengirimkan pencarian dengan metode POST
			{
				if($i===0){ // looping awal
					$this->db->group_start(); 
					$this->db->like($item, $this->input->post('search'));
				}
				else{
					$this->db->or_like($item, $this->input->post('search'));
				}

				if(count($column_search) - 1 == $i){
					$this->db->group_end();
				}
			}
			$i++;
		}
		$this->db->order_by("id", "DESC");
			
		// if(isset($_POST['order'])) 
		// {
		// 	$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		// } 
		// else if(isset($order))
		// {
		// 	$this->db->order_by(key($order), $order[key($order)]);
		// }
    }

	// get datatable query part 1
	function get_dataTable($where, $groups, $groupDate)
    {
        $this->_get_dataTable_query($where, $groups, $groupDate);
        if($this->input->post('length') != -1)
        $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query;
		$query->free_result();
    }

	// count datatable query
    function count_dataTable($where, $groups, $groupDate)
    {
        $this->_get_dataTable_query($where, $groups, $groupDate);
        $query = $this->db->get();
        return $query->num_rows();
    }


	function get_summary($var,$sales_code,$groupDate,$product,$upVar,$upSales_code)
	{
		$query = $this->db->query("SELECT
			SUM(IF(MID_Type = 'NEW',1,0)) AS ntb,
			SUM(IF(MID_Type = 'EXISTING',1,0)) AS exis,
			SUM(IF(MID_Type IN('REJECT','REJECTED'),1,0)) AS rejected,
			SUM(IF(Facilities_Type2 = 'KREDIT',1,0)) AS kredit,
			SUM(IF(Facilities_Type2 = 'NON KREDIT',1,0)) AS non_kredit,
			SUM(IF(Facilities_Type2 = 'TAMBAHAN CABANG',1,0)) AS tc,
			SUM(IF(Facilities_type2 = 'TAMBAHAN TERMINAL',1,0)) AS terminal,
			SUM(IF(Facilities_Type2 = 'UBAH FASILITAS',1,0)) AS uf,
			SUM(IF(Facilities_Type2 = 'REAGREMENT',1,0)) AS ra,
			COUNT(1) as total
			FROM `internal`.`edc_result` WHERE $var = '$sales_code'
			AND Product LIKE '$product%'
			AND Group_Date = '$groupDate'
			AND $upVar = '$upSales_code'
		")->row();
		return $query;
		$query->free_result();
	}

	function get_summary_detail($var,$sales_code,$groupDate,$product,$upVar,$upSales_code)
	{
		$query = $this->db->query("SELECT
				SUM(IF(MID_Type = 'NEW',1,0)) AS ntb,
				SUM(IF(MID_Type = 'EXISTING',1,0)) AS exis,
				SUM(IF(MID_Type IN('REJECT','REJECTED'),1,0)) AS rejected,
				SUM(IF(Facilities_Type2 = 'KREDIT',1,0)) AS kredit,
				SUM(IF(Facilities_Type2 = 'NON KREDIT',1,0)) AS non_kredit,
				SUM(IF(Facilities_Type2 = 'TAMBAHAN CABANG',1,0)) AS tc,
				SUM(IF(Facilities_type2 = 'TAMBAHAN TERMINAL',1,0)) AS terminal,
				SUM(IF(Facilities_Type2 = 'UBAH FASILITAS',1,0)) AS uf,
				SUM(IF(Facilities_Type2 = 'REAGREMENT',1,0)) AS ra,
				COUNT(1) as total
				FROM `internal`.`edc_result` WHERE $var = '$sales_code'
				AND Product = '$product'
				AND Group_Date = '$groupDate'
				AND $upVar = '$upSales_code'
			")->row();
		return $query;
		$query->free_result();
	}

	function get_summary_qris($var,$sales_code,$groupDate,$type,$upVar,$upSales_code)
	{
		if ($type == "New") {
			$wheres = "MID_Type = 'New'";
		}else{
			$wheres = "MID_Type != 'New'";
		}

		$query = $this->db->query("SELECT
				SUM(IF(Facilities_Type2 = 'QRD',1,0)) AS qrd,
				SUM(IF(Facilities_Type2 = 'QSD',1,0)) AS qsd,
				COUNT(1) as total
				FROM `internal`.`edc_result` WHERE $var = '$sales_code'
				AND Product LIKE 'QRIS'
				AND Group_Date = '$groupDate'
				AND $wheres
				AND $upVar = '$upSales_code'
		")->row();
		return $query;
		$query->free_result();
	}

	function calculate_point($product, $mid, $total)
	{
		$query = $this->db->query("SELECT * FROM `set_point` WHERE Product = '$product' AND MID_Type = '$mid'");
		if($query->num_rows() == 0){
			$point = 0;
		}
		else{
			$row = $query->row();
			$point = $row->Point;
		}
		return $point * $total;
	}
}