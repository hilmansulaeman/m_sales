<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Check_limit_model extends MY_Model {

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

	// get datatable query part 1
	function check_limit_data_event($sales_code, $product, $date_now)
    {
        $this->db->select("*");
        $this->db->from('data_limit_event');
        $this->db->where('start_date <=', $date_now);
        $this->db->where('end_date >=', $date_now);
        $this->db->where('sales_code', $sales_code);
        $this->db->where('product', $product);
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }

    public function check_limit($product){
        $this->db->select("*");
        $this->db->from('data_limit');
        $this->db->where('product', $product);
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }
}