<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function get_all_users()
	{
		$query = $this->db->get('users');
		return $query;
		$query->free_result();
	}
	
	function get_all_level()
	{
		$query = $this->db->get('user_privileges');
		return $query;
		$query->free_result();
	}
	
	function insert($data)
	{
		$this->db->insert('users',$data);
	}
	
	function get_by_id($id)
	{
		$this->db->where('id',$id);
		return $this->db->get('users');
	}
	
	function update($data,$id)
	{
		$this->db->where('id',$id);
		$this->db->update('users',$data);
	}
	
	function delete($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('users');
	}
}