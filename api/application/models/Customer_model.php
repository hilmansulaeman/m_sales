<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_model extends MY_Model {

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

	// get ASM
	function getByASM($sm_code)
	{
		$this->db->select('*');
		$this->db->from('`internal`.`data_sales`');
		$this->db->where('SM_Code', $sm_code);
		$this->db->where('Position', 'SPV');
		$this->db->where('Status', 'ACTIVE');
		$this->db->order_by('Name', 'ASC');
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}

	// get RSM
	function getByRSM($sm_code)
	{
		$this->db->select('*');
		$this->db->from('`internal`.`data_sales_structure`');
		$this->db->where('RSM_Code', $sm_code);
		$this->db->where('Position', 'SPV');
		$this->db->where('Status', 'ACTIVE');
		$this->db->order_by('Name', 'ASC');
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}

	// get Branch
	function getBranch()
	{
		$this->db->select('*');
		$this->db->from('branch');
		$this->db->where('Status', 'ACTIVE');
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}

	// get datatable query part 2
	private function _get_datapemol_query($sales_code, $where, $position)
    {
		$allow_join = array('SPV','ASM','RSM','ASH','BSH');
		$column_order = array(null,'t1.Account_Number','t1.Sales_Code','t1.Sales_Name'); //field yang ada di table recruitment
		$column_search = array('t1.Account_Number','t1.Sales_Code','t1.Sales_Name'); //field yang diizin untuk pencarian 
		$order = array('t1.RegnoId' => 'DESC'); // default order

		$this->db->select('t1.*');
        $this->db->from('data_pemol t1');
		if(in_array($position,$allow_join)){
			$this->db->join('`internal`.`data_sales_copy` t2', 't1.Sales_Code=t2.DSR_Code', 'left');
			$this->db->where($where);
		}
		else{
			$this->db->where($where);
		}

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
	function get_datapemol($sales_code, $where, $position)
    {
        $this->_get_datapemol_query($sales_code, $where, $position);
        if($this->input->post('length') != -1)
        $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query;
		$query->free_result();
    }

	// count datatable query
    function count_datapemol($sales_code, $where, $position)
    {
        $this->_get_datapemol_query($sales_code, $where, $position);
        $query = $this->db->get();
        return $query->num_rows();
    }

	// get Where
	function getId($id, $part)
	{
		if ($part == 'regnoid') {
			$query = $this->db->query("SELECT * FROM `data_pemol` WHERE `RegnoId` = '$id'");
		}else{
			$query = $this->db->query("SELECT COUNT(1) AS total FROM `data_pemol` WHERE `Account_Number` = '$id'");
		}

		if($query->num_rows() == 0){ 
			return false;
		}
		else{
			return $query;
			$query->free_result();
		}
	}

	/* 
		* Main Function
	*/

	/* 
		*other function
	*/

	// =====================================================================================================================================================================
	// =====================================================================================================================================================================
	// =====================================================================================================================================================================
	
	/*
     * Login Admin
     */
	// function do_login_admin($username,$password){
	//     $username_ = $this->db->escape_str($username);
	// 	$password_ = MD5($this->db->escape_str($password));
	// 	$this->db->from('users');
	// 	$this->db->where('username',$username_);
	// 	$this->db->where('password',$password_);
	// 	$this->db->where('status','active');
	// 	$query = $this->db->get();
	// 	if($query->num_rows() == 0){ 
	// 		return false;
	// 	}
	// 	else{
	// 		return $query;
	// 		$query->free_result();
	// 	}
	// }
	
	/*
     * Login Sales With DOB
     */
	// function do_login_sales($username,$password){
	//     $username_ = $this->db->escape_str($username);
	// 	$password_ = $this->db->escape_str($password);
	// 	$this->db->from('user_employee');
	// 	$this->db->where('DSR_Code',$username_);
	// 	$this->db->where('Date_Of_Birth',$password_);
	// 	$this->db->where('Status','ACTIVE');
	// 	$query = $this->db->get();
	// 	if($query->num_rows() == 0){ 
	// 		return false;
	// 	}
	// 	else{
	// 		return $query;
	// 		$query->free_result();
	// 	}
	// }
	
	/*
     * Login Sales With Password
     */
	// function do_login_saless($username,$password){
	//     $username_ = $this->db->escape_str($username);
	// 	$password_ = MD5($this->db->escape_str($password));
	// 	$this->db->from('user_employee');
	// 	$this->db->where('DSR_Code',$username_);
	// 	$this->db->where('Password',$password_);
	// 	$this->db->where('Status','ACTIVE');
	// 	$query = $this->db->get();
	// 	if($query->num_rows() == 0){ 
	// 		return false;
	// 	}
	// 	else{
	// 		return $query;
	// 		$query->free_result();
	// 	}
	// }
	
	/*
     * Get rows from the users table
     */
    // function getRows($params = array()){
    //     $this->db->select('*');
    //     $this->db->from($this->table_name);
        
    //     //fetch data by conditions
    //     if(array_key_exists("conditions",$params)){
    //         foreach($params['conditions'] as $key => $value){
    //             $this->db->where($key,$value);
    //         }
    //     }
        
    //     if(array_key_exists("Employee_ID",$params)){
    //         $this->db->where('Eployee_ID',$params['Eployee_ID']);
    //         $query = $this->db->get();
    //         $result = $query->row_array();
    //     }else{
    //         //set start and limit
    //         if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
    //             $this->db->limit($params['limit'],$params['start']);
    //         }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
    //             $this->db->limit($params['limit']);
    //         }
            
    //         if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
    //             $result = $this->db->count_all_results();    
    //         }elseif(array_key_exists("returnType",$params) && $params['returnType'] == 'single'){
    //             $query = $this->db->get();
    //             $result = ($query->num_rows() > 0)?$query->row_array():false;
    //         }else{
    //             $query = $this->db->get();
    //             $result = ($query->num_rows() > 0)?$query->result_array():false;
    //         }
    //     }

    //     //return fetched data
    //     return $result;
    // }
}