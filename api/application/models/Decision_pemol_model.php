<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Decision_pemol_model extends MY_Model {

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
		$this->db->order_by('Position', 'DESC');
			
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

	// get Pemol
	function get_pemol($var,$sales_code,$groupDate,$upVar,$upSales_code)
	{
		$query = $this->db->query("SELECT
			SUM(IF(`Status` = 'OA', 1, 0)) AS oa,
			SUM(IF(`Status` = 'SN', 1, 0)) AS sn,
			SUM(IF(`Status` = 'SK', 1, 0)) AS sk,
			SUM(IF(`Status` = 'SD', 1, 0)) AS sd,
			SUM(IF(`Status` = 'KTB', 1, 0)) AS ktb,
			COUNT(1) AS total
			FROM `data_upload_oa_pemol_detail` 
			WHERE $var = '$sales_code'
			AND Group_Date = '$groupDate' 
			AND $upVar = '$upSales_code'
		");
		return $query;
		$query->free_result();
	}
}