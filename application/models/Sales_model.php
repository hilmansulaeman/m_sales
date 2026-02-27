<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	private function _get_datatables_query()
    {
	    $position = $this->session->userdata('position');
		$salescode = $this->session->userdata('sl_code');
		if($position == 'BSH'){
		    $where = "BSH_Code = '$salescode'";
		}
		else if($position == 'RSM'){
		    $where = "RSM_Code = '$salescode'";
		}
		else if($position == 'ASM'){
		    $where = "ASM_Code = '$salescode'";
		}
		else if($position == 'SPV'){
		    $where = "SPV_Code = '$salescode'";
		}
		else{
		    $where = "DSR_Code = '$salescode'";
		}
	    $column_order = array(null,'DSR_Code','Name'); //field yang ada di table recruitment
		$column_search = array('DSR_Code','Name'); //field yang diizin untuk pencarian 
		$order = array('Status' => 'ASC'); // default order
		$this->db->select('*');
        $this->db->from('internal.data_sales_structure');
		$this->db->where($where);
 
        $i = 0;
        foreach ($column_search as $item) // looping awal
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
	
	function get_all_data()
	{
		return $this->db->get('user_ro');
	}
	
	function insert($data)
	{
		$this->db->insert('user_ro',$data);
	}
	
	function get_by_id($id)
	{
		$this->db->where('id',$id);
		return $this->db->get('user_ro');
	}
	
	function update($data,$id)
	{
		$this->db->where('id',$id);
		$this->db->update('user_ro',$data);
	}
	
	function delete($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('user_ro');
	}
}