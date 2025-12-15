<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class History_meeting_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('db_user',TRUE);
	}

	private function _get_datatables_query($sales)
    {
	    // $column_order = array(null,'NIK','Name'); //field yang ada di tabel
	    // $column_search = array(null,'NIK','Name'); //field yang ada di tabel
	    $column_order = array(null,'Location_Name','Schedule_Day','Schedule_Date','Schedule_Time','Schedule_Type','Status'); //field yang ada di tabel
		$column_search = array('Location_Name','Schedule_Day','Schedule_Date','Schedule_Time','Schedule_Type','Status'); //field yang diizin untuk pencarian 
		
		
		$this->db->select('*');
		$this->db->from('data_participant');
		$this->db->join('ref_schedule','data_participant.Schedule_ID = ref_schedule.Schedule_ID');
		$this->db->where('NIK',$sales); 
		// $this->db->where('Status','Closed');
		$this->db->order_by('ref_schedule.Schedule_Date', 'DESC');


        $i = 0;
        foreach ($column_search as $item) 
        {
            if($_POST['search']['value']) 
            {
                if($i===0){ 
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
 
    function get_datatables($sales)
    {
        $this->_get_datatables_query($sales);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query;
		$query->free_result();
    }

	function count_filtered($sales)
    {
        $this->_get_datatables_query($sales);
        $query = $this->db->get();
        return $query->num_rows();
    }

	function getData($Schedule_ID)
	{
		$this->db->select('a.*,b.Created_Date, c.position');
		$this->db->from('data_participant a');
		$this->db->join('ref_schedule b','a.Schedule_ID=b.Schedule_ID');		
		$this->db->join('data_sales_structure c','a.nik=c.DSR_Code');		
		$this->db->where('b.Schedule_ID',$Schedule_ID);
		$this->db->order_by('a.Name');
		$sql = $this->db->get();
		return $sql;
	}

	function getMeeting($Schedule_ID)
	{
		$this->db->from('ref_schedule');
		$this->db->where('Schedule_ID',$Schedule_ID);		
		$sql = $this->db->get();
		return $sql;
	}

}