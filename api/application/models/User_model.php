<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model {

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
	
	/*
     * Login Admin
     */
	function do_login_admin($username,$password){
	    $username_ = $this->db->escape_str($username);
		$password_ = MD5($this->db->escape_str($password));
		$this->db->from('users');
		$this->db->where('username',$username_);
		$this->db->where('password',$password_);
		$this->db->where('status','active');
		$query = $this->db->get();
		if($query->num_rows() == 0){ 
			return false;
		}
		else{
			return $query;
			$query->free_result();
		}
	}
	
	/*
     * Login Sales With DOB
     */
	function do_login_sales($username,$password){
	    $username_ = $this->db->escape_str($username);
		$password_ = $this->db->escape_str($password);
		$this->db->from('user_employee');
		$this->db->where('DSR_Code',$username_);
		$this->db->where('Date_Of_Birth',$password_);
		$this->db->where('Status','ACTIVE');
		$query = $this->db->get();
		if($query->num_rows() == 0){ 
			return false;
		}
		else{
			return $query;
			$query->free_result();
		}
	}
	
	/*
     * Login Sales With Password
     */
	function do_login_saless($username,$password){
	    $username_ = $this->db->escape_str($username);
		$password_ = MD5($this->db->escape_str($password));
		$this->db->from('user_employee');
		$this->db->where('DSR_Code',$username_);
		$this->db->where('Password',$password_);
		$this->db->where('Status','ACTIVE');
		$query = $this->db->get();
		if($query->num_rows() == 0){ 
			return false;
		}
		else{
			return $query;
			$query->free_result();
		}
	}
	
	/*
     * Get rows from the users table
     */
    function getRows($params = array()){
        $this->db->select('*');
        $this->db->from($this->table_name);
        
        //fetch data by conditions
        if(array_key_exists("conditions",$params)){
            foreach($params['conditions'] as $key => $value){
                $this->db->where($key,$value);
            }
        }
        
        if(array_key_exists("Employee_ID",$params)){
            $this->db->where('Eployee_ID',$params['Eployee_ID']);
            $query = $this->db->get();
            $result = $query->row_array();
        }else{
            //set start and limit
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();    
            }elseif(array_key_exists("returnType",$params) && $params['returnType'] == 'single'){
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->row_array():false;
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():false;
            }
        }

        //return fetched data
        return $result;
    }
}