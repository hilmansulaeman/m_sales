<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Decision_sc_model extends MY_Model {

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


	function get_breakdown_sc($sales_code,$groupDate,$upVar,$upSales_code)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as count_app_cc,
			COALESCE(SUM(IF(Status_1 = 'APPROVED',1,0)),0) as approve,
			COALESCE(SUM(IF(Status_1 = 'INPROCESS',1,0)),0) as inprocess,
			COALESCE(SUM(IF(Status_1 IN('CANCEL','CANCELED'),1,0)),0) as cancel,
			COALESCE(SUM(IF(Status_1 = 'DECLINED',1,0)),0) as decline
			FROM `internal`.`sc_result`
			WHERE Group_Date = '$groupDate'
			AND (Sales_Code ='$sales_code'
				OR SPV_Code ='$sales_code'
				OR ASM_Code ='$sales_code'
				OR RSM_Code ='$sales_code'
				OR BSH_Code ='$sales_code'
			)
			AND $upVar = '$upSales_code'
		");
		return $query;
		$query->free_result();
	}
}