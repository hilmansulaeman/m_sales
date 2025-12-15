<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule_meeting_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('db_user',TRUE);
	}

	private function _get_datatables_query($sales,$date,$dateto)
    {
	    $column_order = array(null,'Location_Name','Schedule_Day','Schedule_Date','Schedule_Time','Schedule_Type','Status'); //field yang ada di tabel
		$column_search = array('Location_Name','Schedule_Day','Schedule_Date','Schedule_Time','Schedule_Type','Status'); //field yang diizin untuk pencarian 
		$order = array('Schedule_Date' => 'ASC'); // default order
		$this->db->select('*');
		$this->db->from('ref_schedule');
		$this->db->where('Created_By_ID',$sales);   
		$this->db->where('Schedule_Date >=', $date);
		$this->db->where('Schedule_Date <=', $dateto);     
 
        $i = 0;
        foreach ($column_search as $item) // looping awals
        {
            if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                if($i===0){ // looping awal
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($column_search) - 1 == $i){
                    $this->db->group_end();
                }
            }
            $i++;
        }
         
        if(isset($_POST['order'])) 
        {
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($order))
        {
            $this->db->order_by(key($order), $order[key($order)]);
        }		
    }
 
    function get_datatables($sales,$date,$dateto)
    {
        $this->_get_datatables_query($sales,$date,$dateto);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query;
		$query->free_result();
    }
 
    function count_filtered($sales,$date,$dateto)
    {
        $this->_get_datatables_query($sales,$date,$dateto);
        $query = $this->db->get();
        return $query->num_rows();
    }


	private function _get_datatables_anggota($nik)
    {
	    $column_order = array(null,'name','email','branch','departement','position','sm_code','sm_name'); //field yang ada di tabel
		$column_search = array('name','email','branch','departement','position','sm_code','sm_name'); //field yang diizin untuk pencarian 
		$order = array('name' => 'ASC'); // default order
		$this->db2->select('*');
		$this->db2->from('users');
		$this->db2->where('sm_code',$nik);    
 
        $i = 0;
        foreach ($column_search as $item) // looping awals
        {
            if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                if($i===0){ // looping awal
                    $this->db2->group_start(); 
                    $this->db2->like($item, $_POST['search']['value']);
                }
                else{
                    $this->db2->or_like($item, $_POST['search']['value']);
                }
 
                if(count($column_search) - 1 == $i){
                    $this->db2->group_end();
                }
            }
            $i++;
        }
         
        if(isset($_POST['order'])) 
        {
            $this->db2->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($order))
        {
            $this->db2->order_by(key($order), $order[key($order)]);
        }		
    }
 
    function get_datatables_anggota($nik)
    {
        $this->_get_datatables_anggota($nik);
        if($_POST['length'] != -1)
        $this->db2->limit($_POST['length'], $_POST['start']);
        $query = $this->db2->get();
        return $query;
		$query->free_result();
    }


 
    function count_filtered_anggota($nik)
    {
        $this->_get_datatables_anggota($nik);
        $query = $this->db2->get();
        return $query->num_rows();
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

	function getAnggota1($smcode){
// cekvar($a);
		$sql = $this->db2->query("SELECT * FROM `user_employee` WHERE sm_code = '$smcode'");
		return $sql;
		$sql->free_result();
	}

	function getAnggota($where,$smcode,$position,$status){
		
		$this->db->where($where,$smcode);
		$this->db->where('Position',$position);
		$this->db->where('Status',$status);
		$this->db->order_by('Name');
		$sql = $this->db->get('data_sales_structure');
		return $sql;
		$sql->free_result();
	}


	function getAtasanrsm($nik){
		$this->db->where('DSR_Code', $nik);
		$this->db->where('Status', 'ACTIVE');
		$result = $this->db->get('data_sales_structure')->row();
		$atasan = $result->RSM_Code;
	
		$this->db->where('DSR_Code', $atasan);
		$this->db->where('Status', 'ACTIVE');
		$result = $this->db->get('data_sales_structure');
		
		return $result;
	}

	function getAtasanbsh1($nik){
		$this->db->where('DSR_Code', $nik);
		$this->db->where('Status', 'ACTIVE');
		$result = $this->db->get('data_sales_structure')->row();
		$atasan = $result->BSH_Code;
	
		$this->db->where('DSR_Code', $atasan);
		$this->db->where('Status', 'ACTIVE');
		$result = $this->db->get('data_sales_structure');
		
		
		return $result;
	}


	function getAtasanbsh($get_rsm){
		$this->db->where('DSR_Code', $get_rsm);
		$this->db->where('Status', 'ACTIVE');
		$result = $this->db->get('data_sales_structure');
		return $result;
	}

	function getAtasangm1($get_gm){
		$this->db->where('DSR_Code', $get_gm);
		$this->db->where('Status', 'ACTIVE');
		$result = $this->db->get('data_sales_structure');
		return $result;
	}
	

	function getAtasangm($nik){
		$this->db->where('DSR_Code', $nik);
		$this->db->where('Status', 'ACTIVE');
		$result = $this->db->get('data_sales_structure')->row();
		$atasan = $result->SM_Code;
	
		$this->db->where('DSR_Code', $atasan);
		$this->db->where('Status', 'ACTIVE');
		$result = $this->db->get('data_sales_structure');
		
		return $result;
	}


	function getAtasanasm($nik){
		$this->db->where('DSR_Code', $nik);
		$this->db->where('Status', 'ACTIVE');
		$result = $this->db->get('data_sales_structure')->row();
		$atasan = $result->ASM_Code;
	
		$this->db->where('DSR_Code', $atasan);
		$this->db->where('Status', 'ACTIVE');
		$this->db->where('Position', 'ASM');
		$result = $this->db->get('data_sales_structure');
		
		return $result;
	
	}

	function getAtasanrsm1($get_rsm){
		$this->db->where('DSR_Code', $get_rsm);
		$this->db->where('Status', 'ACTIVE');
		$this->db->where('Position', 'RSM');
		$result = $this->db->get('data_sales_structure');
		
		return $result;
	
	}



	function getDsr($where1,$smcode,$status,$where2,$position){
		$this->db->where($where1,$smcode);
		$this->db->where('Status',$status);
		$this->db->where_in($where2,$position);
		$this->db->order_by('Name');
		$sql = $this->db->get('data_sales_structure');
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