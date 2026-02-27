<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Idor library
 */

class Idor
{
	var $CI = NULL;
	var $user = NULL;

	function __construct()
	{
		// get CI's object
		$this->CI = &get_instance();
		// $this->CI->load->library('session');
		$this->user = $this->CI->session->userdata('sl_code');
	}
	
	//======================================= SALES ======================================//

	function detail_request($request_id)
	{
		$this->CI->db->from('internal_sms.data_request');
		$this->CI->db->where('Request_ID',$request_id);
		$this->CI->db->where('Created_By',$this->user);
		$query = $this->CI->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	function detail_approval($request_id)
	{
		$this->CI->db->from('internal_sms.data_request_approval');
		$this->CI->db->where('Request_ID',$request_id);
		$this->CI->db->where('Sales_Code',$this->user);
		$query = $this->CI->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}
	
}
