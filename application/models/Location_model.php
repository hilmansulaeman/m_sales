<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Location_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	private function _get_datatables_query()
    {
	    $column_order = array(null,'Location_Name','Location_Address','Location_City'); //field yang ada di tabel
		$column_search = array('Location_Name','Location_Address','Location_City'); //field yang diizin untuk pencarian 
		$order = array('Location_Name' => 'ASC'); // default order
		$this->db->select('*');
		$this->db->from('ref_location');        
 
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
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query;
		$query->free_result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
	
	function insert_data($data)
	{
		$this->db->insert('ref_location', $data);
	}
	
	function update_data($Location_ID, $data)
	{
		$this->db->where('Location_ID', $Location_ID);
		$this->db->update('ref_location', $data);
	}
	
	function getData()
	{
		$sql = $this->db->query("SELECT * FROM `ref_location` Order By Location_Name ASC");
		return $sql;
	}
	
	function getDataById($Location_ID)
	{
		$sql = $this->db->query("SELECT * FROM `ref_location` WHERE Location_ID='$Location_ID'");
		return $sql;
	}

	function delete_data($Location_ID)
	{
		$this->db->where('Location_ID', $Location_ID);
		$this->db->delete('ref_location');
	}
}