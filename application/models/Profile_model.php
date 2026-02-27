<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends MY_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function get_qr($id)
	{
	    $query = $this->db->get_where('qr_code', array('sales_code'=>$id));
		return $query;
		$query->free_result();
	}
	
	function cek_id_card()
	{
		$product = $this->session->userdata('product');
		$channel = $this->session->userdata('channel');
		$position = $this->session->userdata('position');
		$where = array('Product' => $product, 'Channel' => $channel,  'Position' => $position);
	    $query = $this->db->get_where('set_id_card', $where);
		return $query;
		$query->free_result();
	}
}