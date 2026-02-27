<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class Model_randomdata extends CI_Model
 {
	 function __construct()
	 {
		 parent::__construct();
	 }
	 
	 function get_all_data($sql)
	{
		$sql = $this->db->query("$sql");
		return $sql;
		$sql->free_result();
	}
	
	function getData()
	{
		$sql = $this->db->query("select * from training_participants where flag_win='1' order by id ASC");
		return $sql;
		$sql->free_result();
	}
	
	function update_flag($data, $id)
	{
		$this->db->query("update training_participants set flag_win='1' where id='$id'");
	}
	
 }