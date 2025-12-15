<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Meeting_monitoring_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	private function _get_datatables_query($where)
    {
	    $column_order = array(null,'DSR_Code','Name','Branch'); //field yang ada di tabel
		$column_search = array('DSR_Code','Name','Branch'); //field yang diizin untuk pencarian 
		$order = array('Name' => 'ASC'); // default order
		$this->db->select('*');
		$this->db->from('data_sales_structure');
		$this->db->where($where);        
		$this->db->like('Status','active');        
 
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
 
    function get_datatables($where)
    {
        $this->_get_datatables_query($where);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query;
    }
 
    function count_filtered($where)
    {
        $this->_get_datatables_query($where);
        $query = $this->db->get();
        return $query->num_rows();
    }


    function get_meeting($sales,$from,$to)
	{
		$this->db->select('count(Schedule_ID) as total');
		$this->db->from('ref_schedule');
		// $this->db->where('NIK',$sales);
		$this->db->where('Schedule_Date >=',$from);
		$this->db->where('Schedule_Date <=',$to);
        $this->db->where('Created_By_ID',$sales);
		$getData = $this->db->get();
		return $getData;
	}
	
	function get_total_meeting_up($sales,$niklow,$from,$to)
	{
		$this->db->select('count(1) as total');
		$this->db->from('data_participant a');
		$this->db->join('ref_schedule b','a.Schedule_ID=b.Schedule_ID','right');
		// $this->db->where('NIK',$sales);
		$this->db->where('b.Schedule_Date >=',$from);
		$this->db->where('b.Schedule_Date <=',$to);
		$this->db->where('b.Status =',"Closed");
		
		
		$this->db->where('a.NIK',$niklow);
        // $this->db->or_where('a.NIK', $niklow);
        $this->db->where('b.Created_By_ID',$sales);
		$getData = $this->db->get();
		return $getData;
	}
	
	function get_total_hadir_up($sales,$niklow,$from,$to)
	{
		$this->db->select('count(1) as total');
		$this->db->from('data_participant a');
		$this->db->join('ref_schedule b','a.Schedule_ID=b.Schedule_ID','right');
		// $this->db->where('NIK',$sales);
		$this->db->where('b.Schedule_Date >=',$from);
		$this->db->where('b.Schedule_Date <=',$to);
		$this->db->where('a.Absent_Status',1);
		$this->db->where('a.NIK',$niklow);
        // $this->db->or_where('a.NIK', $niklow);
        $this->db->where('b.Created_By_ID',$sales);
		$getData = $this->db->get();
		return $getData;
	}
	
	function get_total_meeting_bottom($sales,$niklow,$from,$to)
	{
		$this->db->select('count(1) as total');
		
		$this->db->from('ref_schedule b');
		// $this->db->where('NIK',$sales);
		$this->db->where('b.Schedule_Date >=',$from);
		$this->db->where('b.Schedule_Date <=',$to);
		$this->db->where('b.Status =',"Closed");
		
		
		
        // $this->db->or_where('a.NIK', $niklow);
        $this->db->where('b.Created_By_ID',$niklow);
		$getData = $this->db->get();
		return $getData;
	}
	
	function get_total_hadir_bottom($sales,$niklow,$from,$to)
	{
		$this->db->select('count(1) as total');
		$this->db->from('data_participant a');
		$this->db->join('ref_schedule b','a.Schedule_ID=b.Schedule_ID','right');
		// $this->db->where('NIK',$sales);
		$this->db->where('b.Schedule_Date >=',$from);
		$this->db->where('b.Schedule_Date <=',$to);
		$this->db->where('a.Absent_Status',1);
		//$this->db->where('a.NIK',$niklow);
        // $this->db->or_where('a.NIK', $niklow);
        $this->db->where('b.Created_By_ID',$niklow);
		$getData = $this->db->get();
		return $getData;
	}
	
	

	function get_anak_buah($sales)
	{
		$this->db->select('count(1) as total');
		$this->db->from('data_sales_structure');
		$this->db->where('SM_Code',$sales);		
		$this->db->like('Status','active');        
		$getData = $this->db->get();
		return $getData;
	}

	private function _get_schedule_query($sales,$from,$to)
    {
	    $column_order = array(null,'Location_Name','Schedule_Day','Schedule_Date','Schedule_Time','Schedule_Type','Status'); //field yang ada di tabel
		$column_search = array('Location_Name','Schedule_Day','Schedule_Date','Schedule_Time','Schedule_Type','Status'); //field yang diizin untuk pencarian 
		$order = array('Schedule_Date' => 'ASC'); // default order
		$this->db->select('*');
		$this->db->from('ref_schedule');
		// $this->db->where('NIK',$sales);
		$this->db->where('Schedule_Date >=',$from);
		$this->db->where('Schedule_Date <=',$to);
        $this->db->where('Created_By_ID',$sales);		
 
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

	function get_schedule($sales,$from,$to)
	{
		$this->_get_schedule_query($sales,$from,$to);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query;
	}

    function count_filtered_schedule($sales,$from,$to)
    {
        $this->_get_schedule_query($sales,$from,$to);
        $query = $this->db->get();
        return $query->num_rows();
    }
	
	
	private function _get_schedule_up_query($sales,$from,$to)
    {
	    $column_order = array(null,'Location_Name','Schedule_Day','Schedule_Date','Schedule_Time','Schedule_Type','Status'); //field yang ada di tabel
		$column_search = array('Location_Name','Schedule_Day','Schedule_Date','Schedule_Time','Schedule_Type','Status'); //field yang diizin untuk pencarian 
		$order = array('Schedule_Date' => 'ASC'); // default order
		$this->db->select('*');
		$this->db->from('ref_schedule');
		// $this->db->where('NIK',$sales);
		$this->db->where('Schedule_Date >=',$from);
		$this->db->where('Schedule_Date <=',$to);
        $this->db->where('Created_By_ID',$sales);		
 
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

	function get_schedule_up($sales,$from,$to)
	{
		$this->_get_schedule_up_query($sales,$from,$to);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query;
	}

    function count_filtered_schedule_up($sales,$from,$to)
    {
        $this->_get_schedule_up_query($sales,$from,$to);
        $query = $this->db->get();
        return $query->num_rows();
    }
	

    function get_absent_by_Schedule_ID_up($Schedule_ID)
	{
		$this->db->select('count(1) as total');
		$this->db->from('data_participant');		
        $this->db->where('Schedule_ID',$Schedule_ID);
		$this->db->where('Absent_Status',1);
		$getData = $this->db->get();
		return $getData;
	}
	
	function get_absent_by_Schedule_ID($Schedule_ID)
	{
		$this->db->select('count(1) as total');
		$this->db->from('data_participant');		
        $this->db->where('Schedule_ID',$Schedule_ID);
		$this->db->where('Absent_Status',1);
		$getData = $this->db->get();
		return $getData;
	}
	
	function get_tidak_absent_by_Schedule_ID($Schedule_ID)
	{
		$this->db->select('count(1) as total');
		$this->db->from('data_participant');		
        $this->db->where('Schedule_ID',$Schedule_ID);
		$this->db->where('Absent_Status',0);
		$getData = $this->db->get();
		return $getData;
	}
	


	private function _get_participant_query($Schedule_ID)
    {
	    $column_order = array(null,'Name'); //field yang ada di tabel
		$column_search = array('Name'); //field yang diizin untuk pencarian 
		$order = array('Name' => 'ASC'); // default order
		$this->db->select('*');
		$this->db->from('data_participant');
		$this->db->where('Schedule_ID',$Schedule_ID);
		$this->db->where('Absent_Status',1);
 
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

	function get_participant($Schedule_ID)
	{
		$this->_get_participant_query($Schedule_ID);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query;
	}

    function count_filtered_participant($Schedule_ID)
    {
        $this->_get_participant_query($Schedule_ID);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function get_by_id($id){
        $this->db->select('*');
		$this->db->from('ref_schedule');
		// $this->db->where('NIK',$sales);
        $this->db->where('Schedule_ID',$id);
		$getData = $this->db->get();
		return $getData;
    }

    function get_list_data($Schedule_ID)
    {
        $this->db->select('a.*,b.Created_Date, c.position');
        $this->db->from('data_participant a');
        $this->db->join('ref_schedule b','a.Schedule_ID=b.Schedule_ID');		
        $this->db->join('data_sales_structure c','a.nik=c.DSR_Code');		
        $this->db->where('a.Absent_Status',1); 
        $this->db->where('b.Schedule_ID', $Schedule_ID);
        $this->db->order_by('a.Name');

        $query = $this->db->get();
        return $query;
    }

    function get_list_tidakabsen($Schedule_ID)
    {
        $this->db->select('a.*,b.Created_Date, c.position');
        $this->db->from('data_participant a');
        $this->db->join('ref_schedule b','a.Schedule_ID=b.Schedule_ID');		
        $this->db->join('data_sales_structure c','a.nik=c.DSR_Code');		
        $this->db->where('a.Absent_Status',0); 
        $this->db->where('b.Schedule_ID', $Schedule_ID);
        $this->db->order_by('a.Name');

        $query = $this->db->get();
        return $query;
    }

    function get_list_meeting($Schedule_ID)
    {
        $this->db->select('a.*,b.Created_Date, c.position');
        $this->db->from('data_participant a');
        $this->db->join('ref_schedule b','a.Schedule_ID=b.Schedule_ID');		
        $this->db->join('data_sales_structure c','a.nik=c.DSR_Code');		
        $this->db->where('a.Absent_Status',1); 
        $this->db->where('b.Schedule_ID', $Schedule_ID);
        $this->db->order_by('a.Name');

        $query = $this->db->get();
        return $query;
    }

    function get_list_meetingtidakhadir($Schedule_ID)
    {
        $this->db->select('a.*,b.Created_Date, c.position');
        $this->db->from('data_participant a');
        $this->db->join('ref_schedule b','a.Schedule_ID=b.Schedule_ID');		
        $this->db->join('data_sales_structure c','a.nik=c.DSR_Code');		
        $this->db->where('a.Absent_Status',0); 
        $this->db->where('b.Schedule_ID', $Schedule_ID);
        $this->db->order_by('a.Name');

        $query = $this->db->get();
        return $query;
    }

}
