<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('db_user',TRUE);
	}
	
	function getRoom()
	{
		$sql = $this->db->query("SELECT * FROM `ref_room` Order By Room_Name ASC");
		return $sql;
	}
	
	function getModul()
	{
		$sql = $this->db->query("SELECT * FROM `ref_training_modules` Order By Module_Name ASC");
		return $sql;
	}

	function getAnggota($smcode){
		$sql = $this->db2->query("SELECT * FROM `users` WHERE sm_code = '$smcode'");
		return $sql;
		$sql->free_result();
	}

	function getLokasi(){
		$sql = $this->db->query("SELECT * FROM `ref_location`");
		return $sql;
		$sql->free_result();
	}
	
	function getTrainer()
	{
		$sql = $this->db->query("SELECT * FROM `users` WHERE Position IN('Trainer', 'RTO', 'Deputy Manager') Order By name ASC");
		return $sql;
	}
	
	function getNameTrainer($trainer_code)
	{
		$sql = $this->db->query("SELECT * FROM `users` WHERE nik='$trainer_code'");
		return $sql;
	}
	
	function insert_schedule($data)
	{
		$this->db->insert('ref_schedule', $data);
	}
	
	function getSchedule($date)
	{
		$sql = $this->db->query("SELECT *, (SELECT COUNT(Name) FROM `data_training_participant` WHERE Schedule_ID=a.Schedule_ID) as total_participant, (SELECT COUNT(Name) FROM `data_training_participant` WHERE Schedule_ID=a.Schedule_ID AND Absent_Status='REGISTERED') as regristred_participant FROM `data_training_schedule` a LEFT JOIN `ref_room` b ON a.Room_ID=b.Room_ID WHERE a.Training_Date='$date' Order By a.Training_Date ASC");
		return $sql;
		$sql->free_result();
	}

	function getScheduleId($id){
		$sql = $this->db->query("SELECT * FROM `ref_schedule` WHERE Schedule_ID='$id'");
		return $sql;
		$sql->free_result();
	}

	function getParticipant($schedule_id)
	{
		$sql = $this->db->query("SELECT * FROM `data_training_participant` WHERE Schedule_ID='$schedule_id'");
		return $sql;
		$sql->free_result();
	}

	function getScheduleByIdSchedule($schedule_id)
	{
		$sql = $this->db->query("SELECT a.*, b.* FROM `data_training_schedule` a LEFT JOIN `ref_room` b ON a.Room_ID=b.Room_ID WHERE a.Schedule_ID='$schedule_id'");
		return $sql;
		$sql->free_result();
	}

	function getParticipantBySchduleId($schedule_id)
	{
		$sql = $this->db->query("SELECT * FROM `data_training_participant` WHERE Schedule_ID='$schedule_id' Order By Name ASC");
		return $sql;
		$sql->free_result();
	}

	function insert_data_module($data)
	{
		$this->db->insert('data_modules',$data);
	}

	function insert_data_participant($data){
		$this->db->insert('data_participant',$data);
	}

	function update_data_participant($data, $schedule_id){
		$this->db->where('Schedule_ID', $schedule_id);
		$this->db->update('data_participant', $data);
	}

	public function deleteParticipant($schedule_id)
	{
		$this->db->where('Schedule_ID',$schedule_id);
		$this->db->delete('data_participant');
	}

	function getParticipantId($id){
		$sql = $this->db->query("SELECT * FROM `data_participant` WHERE Schedule_ID='$id'");
		return $sql;
		$sql->free_result();
	}

	function getDataModul($schedule_id)
	{
		$sql = $this->db->query("SELECT * FROM `data_modules` a 
			LEFT JOIN `ref_training_modules` b ON a.Module_ID=b.Module_ID
			LEFT JOIN `data_training_schedule` c ON a.Schedule_ID=c.Schedule_ID
			WHERE a.Schedule_ID='$schedule_id'");
		return $sql;
		$sql->free_result();
	}

	function getFixSchedule()
	{
		$sql = $this->db->query("SELECT * FROM `set_training_fix_schedule` ORDER BY Setup_ID ASC");
		return $sql;
		$sql->free_result();
	}

	function update_fix_sch($data, $id)
	{
		$this->db->WHERE('Setup_ID', $id);
		$this->db->update('set_training_fix_schedule', $data);
	}

	function getParticipantByRecruitmentId($Recruitment_ID)
	{
		$this->db->WHERE('Recruitment_ID', $Recruitment_ID);
		$sql = $this->db->get('data_recruitment');
		return $sql;
		$sql->free_result();
	}

	function update_participant($data, $id)
	{
		$this->db->WHERE('Participant_ID', $id);
		$this->db->update('data_training_participant', $data);
	}

	function updated_pelamar($data, $id)
	{
		$this->db->WHERE('Recruitment_ID', $id);
		$this->db->update('data_recruitment', $data);
	}

	function Insert_LogProcess($data)
	{
		$this->db->insert('data_process_logs', $data);
	}

	function getDaftarSchedule()
	{
		$sql = $this->db->query("SELECT * FROM `data_training_schedule` WHERE Status='ACTIVE' Group By Training_Date Order By Training_Date ASC");
		return $sql;
		$sql->free_result();
	}

	function getScheduleDetailPertanggal($tanggal)
	{
		$sql = $this->db->query("SELECT a.*, b.Room_Name, b.Room_Location FROM `data_training_schedule` a LEFT JOIN `ref_room` b ON a.Room_ID=b.Room_ID WHERE a.Status='ACTIVE' AND a.Training_Date='$tanggal' Order By b.Room_Name ASC");
		return $sql;
		$sql->free_result();
	}

	function getDataParticipants($Participant_ID)
	{
		$sql = $this->db->query("SELECT * FROM `data_training_participant` WHERE Participant_ID='$Participant_ID'");
		return $sql;
		$sql->free_result();
	}

	function insert_log_training($data)
	{
		$this->db->insert('data_training_logs', $data);
	}
	
	function getListScheduleOld()
	{
		$sql = $this->db->query("SELECT a.*, b.* FROM `ref_schedule` a INNER JOIN `ref_room` b ON a.Room_ID=b.Room_ID Order By a.Training_Date DESC");
		return $sql;
		$sql->free_result();
	}

	function getListSchedule()
	{
		$sql = $this->db->query("SELECT * FROM `ref_schedule` Order By Schedule_Date DESC");
		return $sql;
		$sql->free_result();
	}
	
	function getModules($Schedule_ID)
	{
		$sql = $this->db->query("SELECT b.* FROM `data_modules` a INNER JOIN `ref_training_modules` b ON a.Module_ID=b.Module_ID WHERE Schedule_ID='$Schedule_ID'");
		return $sql;
		$sql->free_result();
	}
	
	function getDataModulByScheduleID($Schedule_ID)
	{
		$this->db->where('Schedule_ID', $Schedule_ID);
		$sql = $this->db->get('data_modules');
		return $sql;
		$sql->free_result();
	}
	
	function update_jadwal($data, $schedule_id)
	{
		$this->db->where('Schedule_ID', $schedule_id);
		$this->db->update('data_training_schedule', $data);
	}

	function update_schedule($data, $schedule_id){
		$this->db->where('Schedule_ID', $schedule_id);
		$this->db->update('ref_schedule', $data);
	}
	
	function update_module($data, $schedule_id)
	{
		$this->db->where('Schedule_ID', $schedule_id);
		$this->db->update('data_modules', $data);
	}
	
	function insert_new_participants($data)
	{
		$this->db->insert('data_training_participant_rest', $data);
	}
	
	function getParticipantRest($Schedule_ID)
	{
		$this->db->where('Schedule_ID', $Schedule_ID);
		$this->db->order_by('Name', 'ASC');
		$sql = $this->db->get('data_training_participant_rest');
		return $sql;
		$sql->free_result();
	}
	
	function update_participant_rest($data, $Participant_ID_Rest)
	{
		$this->db->where('Participant_ID_Rest', $Participant_ID_Rest);
		$this->db->update('data_training_participant_rest', $data);
	}
	
	function update_sunvote2($data, $Participant_ID_Rest)
	{
		$this->db->where('Participant_ID_Rest', $Participant_ID_Rest);
		$this->db->update('data_training_participant_rest', $data);
	}
	
	
}