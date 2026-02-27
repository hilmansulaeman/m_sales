<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Check_decision_model extends MY_Model {

	function __construct()
	{
		parent::__construct();
	}
	

	//get merchant
	function get_decision_merchant($key,$sales=''){
		$key_ = $this->db->escape_str($key);
		$this->db->select('*');
		$this->db->from('internal.edc_result');
		if($sales != ''){
			$this->db->where('Sales_Code',$sales);
		}
		$this->db->group_start();
		$this->db->or_like('Merchant_Name', urldecode($key_));
		$this->db->or_like('Owner_Name', urldecode($key_));
		$this->db->group_end();
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}

	//get cc
	function get_decision_cc($key,$sales=''){
		$key_ = $this->db->escape_str($key);
		$this->db->select('*');
		$this->db->from('internal.application_process');
		$this->db->like('Cust_Name', urldecode($key_));
		if($sales != ''){
			$this->db->where('Sales_Code',$sales);
		}
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}
	
	//get corporate
	function get_decision_corporate($key,$sales=''){
		$key_ = $this->db->escape_str($key);
		$this->db->select('*');
		$this->db->from('internal.corporate_ro_result');
		$this->db->like('customer_name', urldecode($key_));
		if($sales != ''){
			$this->db->where('sales_code',$sales);
		}
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}
	
	//get sc
	function get_decision_sc($key,$sales=''){
		$key_ = $this->db->escape_str($key);
		$this->db->select('*');
		$this->db->from('internal.sc_result');
		$this->db->like('cust_name', urldecode($key_));
		if($sales != ''){
			$this->db->where('sales_code',$sales);
		}
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}
	
	//get pl
	function get_decision_pl($key,$sales=''){
		$key_ = $this->db->escape_str($key);
		$this->db->select('*');
		$this->db->from('internal.apps_pl_result');
		$this->db->like('Debitur_Name', urldecode($key_));
		if($sales != ''){
			$this->db->where('sales_code',$sales);
		}
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}
}