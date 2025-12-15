<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Update_meeting_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function update_data($table,$table_id,$Participant_ID, $data)
	{
		$this->db->where($table_id, $Participant_ID);
		$this->db->update($table, $data);
	}
	
	function getData($Schedule_ID)
	{
		$this->db->select('a.*,b.Created_Date, c.position');
		$this->db->from('data_participant a');
		// $this->db->join('ref_schedule b','a.Schedule_ID=b.Schedule_ID AND (SELECT LEFT(b.Created_Date,10)) = (SELECT LEFT(a.created_date,10))');		
		$this->db->join('ref_schedule b','a.Schedule_ID=b.Schedule_ID');		
		$this->db->join('data_sales_structure c','a.nik=c.DSR_Code');		
		$this->db->where('b.Schedule_ID',$Schedule_ID);
		$this->db->order_by('a.Name');
		$sql = $this->db->get();
		return $sql;
	}

	function insert_document($data)
    {
        $this->db->insert('upload_temp',$data);
    }

    function getMeeting($Schedule_ID)
	{
		$this->db->from('ref_schedule');
		$this->db->where('Schedule_ID',$Schedule_ID);		
		$sql = $this->db->get();
		return $sql;
	}

	function getDataUpdateMeeting($Schedule_ID)
	{
		$this->db->select('a.MOM,b.image_name');
		$this->db->from('ref_schedule a');
		$this->db->join('upload_temp b','a.Schedule_ID=b.Schedule_ID');
		$this->db->where('a.Schedule_ID',$Schedule_ID);		
		$this->db->where('b.category_id',1);		
		$sql = $this->db->get();
		return $sql;
	}

	function getDataUpdateMeetingDokumen($Schedule_ID)
	{
		$this->db->select('image_name');
		$this->db->from('upload_temp');
		$this->db->where('Schedule_ID',$Schedule_ID);		
		$this->db->where('category_id',2);		
		$sql = $this->db->get();
		return $sql;
	}

}