<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Export_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	
	// ======================= Export data schedule =======================//
	function schedule_training($date)
    {
		$query = $this->db->query("select * from schedule_training
										where available_date like '$date%'  
										order by id ASC");	
		return $query;
		$query->free_result();
    }
	
	// ======================= Export data registrant =======================//
	function registrant($schedule)
    {
		$query = $this->db->query("select * from training_participants
										where schedule_id = '$schedule' 
										and status_schedule = 'Register'
										order by id ASC");	
		return $query;
		$query->free_result();
    }
	
	// ======================= Export data participant =======================//
	function participant_daily($date1,$date2)
    {
		$query = $this->db->query("select * from training_participants
										where tgl_training >= '$date1' 
										and tgl_training <= '$date2'
										and status_schedule = 'Register'
										order by id ASC");	
		return $query;
		$query->free_result();
    }
	
	function participant_monthly($date)
    {
		$query = $this->db->query("select * from training_participants
										where tgl_training like '$date%' 
										and status_schedule = 'Register'
										order by id ASC");	
		return $query;
		$query->free_result();
    }
}

/* End of file export_model.php */
/* Location: ./application/models/export_model.php */
	