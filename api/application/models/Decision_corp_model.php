<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Decision_corp_model extends MY_Model {

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
		$column_order = array(null,'sales_code','sales_name'); //field yang ada di table recruitment
		$column_search = array('sales_code','sales_name'); //field yang diizin untuk pencarian 
		//$order = array('Branch' => 'ASC'); // default order
		$this->db->select('*');
        $this->db->from('`internal`.`corporate_ro_result`');
		if($where){
			$this->db->where($where);
		}
		$this->db->where("group_date", $groupDate);
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
		$this->db->order_by('id', 'DESC');
			
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

	
	function breakdown_corp($sales_code,$groupDate,$upVar,$upSales_code)
	{
		$query = $this->db->query("SELECT 
			COUNT(1) as counter_corp,
			COALESCE(SUM(IF(`Status` = 'APPROVED',1,0)),0) as approve_corp,
			COALESCE(SUM(IF(`Status` IN('CANCEL','CANCELED'),1,0)),0) as cancel_corp,
			COALESCE(SUM(IF(`Status` = 'DECLINED',1,0)),0) as decline_corp
			FROM `internal`.`corporate_ro_result`
			WHERE group_date = '$groupDate'
			AND (sales_code ='$sales_code'
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