<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_presence_meeting extends CI_Model
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

    function get_total_meeting_up($sales,$niklow,$from,$to)
	{
		$this->db->select('count(1) as total');
		$this->db->from('data_participant a');
		$this->db->join('ref_schedule b','a.Schedule_ID=b.Schedule_ID','right');
		$this->db->where('b.Schedule_Date >=',$from);
		$this->db->where('b.Schedule_Date <=',$to);
		$this->db->where('b.Status =',"Closed");
		
		
		$this->db->where('a.NIK',$niklow);
        $this->db->where('b.Created_By_ID',$sales);
		$getData = $this->db->get();
		return $getData;
	}
	
	function get_total_hadir_up($sales,$niklow,$from,$to)
	{
		$this->db->select('count(1) as total');
		$this->db->from('data_participant a');
		$this->db->join('ref_schedule b','a.Schedule_ID=b.Schedule_ID','right');
		$this->db->where('b.Schedule_Date >=',$from);
		$this->db->where('b.Schedule_Date <=',$to);
		$this->db->where('a.Absent_Status',1);
		$this->db->where('a.NIK',$niklow);
        $this->db->where('b.Created_By_ID',$sales);
		$getData = $this->db->get();
		return $getData;
	}

    function get_total_meeting_bottom($sales,$niklow,$from,$to)
	{
		$this->db->select('count(1) as total');
		
		$this->db->from('ref_schedule b');
		$this->db->where('b.Schedule_Date >=',$from);
		$this->db->where('b.Schedule_Date <=',$to);
		$this->db->where('b.Status =',"Closed");
		
        $this->db->where('b.Created_By_ID',$niklow);
		$getData = $this->db->get();
		return $getData;
	}
	
	function get_total_hadir_bottom($sales,$niklow,$from,$to)
	{
		$this->db->select('count(1) as total');
		$this->db->from('data_participant');
        $this->db->where('created_date >=', $from);
        $this->db->where('created_date <=', $to);
		$this->db->where('Absent_Status',1);
        $this->db->where('NIK', $niklow); 
        
		$getData = $this->db->get();
		return $getData;
	}

    function get_total_invite_room($sales,$niklow,$from,$to)
    {
        $this->db->select('count(1) as total');
        $this->db->from('data_participant');
        $this->db->where('created_date >=', $from);
        $this->db->where('created_date <=', $to);
        $this->db->where('NIK', $niklow); 

        $getData = $this->db->get();
        return $getData;
    }

    function get_total_tidak_hadir_bottom($sales,$niklow,$from,$to)
	{
		
        $this->db->select('count(1) as total');
		$this->db->from('data_participant');
        $this->db->where('created_date >=', $from);
        $this->db->where('created_date <=', $to);
		$this->db->where('Absent_Status',0);
        $this->db->where('NIK', $niklow); 

		$getData = $this->db->get();
		return $getData;
	}

    function count_filtered($where)
    {
        $this->_get_datatables_query($where);
        $query = $this->db->get();
        return $query->num_rows();
    }

}