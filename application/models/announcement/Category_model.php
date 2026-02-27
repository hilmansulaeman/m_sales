<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	private function _get_datatables_query()
    {
	    $column_order = array(null,'Category_ID','Category', 'Schedule', 'Schedule_tgl', 'Schedule_bln'); //field yang ada di table recruitment
		$column_search = array('Category_ID','Category'); //field yang diizin untuk pencarian 
		$order = array('Category_ID' => 'DESC'); // default order
		$this->db->select('*');
		$this->db->from('ref_announcement_category');
 
        $i = 0;
        foreach ($column_search as $item) // looping awal
        {
            if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                if($i===0){ // looping awal
                    //$this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($column_search) - 1 == $i){
                    //$this->db->group_end();
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
		//$this->db->group_by($this->primary_key);
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
	
	function count_all()
    {
	    $this->db->select('count(1) as total');
	    $this->db->from('ref_announcement_category');
        $query = $this->db->get();
        return $query;
		$query->free_result();
    }  


    function get_category(){
        $this->db->select('*');
        $this->db->from('ref_announcement_category');
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }

}