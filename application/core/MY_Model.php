<?php

class MY_Model extends CI_Model
{

    public function __construct(){
        parent::__construct();
		$primary_key = '';
		$table_name = '';
		$table_name2 = '';
    }
    
    public function config($table,$id)
    {
        $this->table_name = $table;
        $this->primary_key = $id;
    }
	
	public function config_join($table1,$table2,$id)
    {
        $this->table_name = $table1;
        $this->table_name2 = $table2;
        $this->primary_key = $id;
    }
	
	public function getTable($table, $where='', $order=''){
		if($where){
			$this->db->where($where);
		}
		
		if($order){
			$this->db->order_by($order,'ASC');
		}

		$query = $this->db->get($table);
		
		return $query;
		$query->free_result();
	}
	
	function get_provinsi()
	{
		$this->db->select('distinct(Province)');
		$this->db->order_by('Province','asc');
		return $this->db->get('ref_zip_code');
	}
	
	public function get_by_id ($id)
	{
		$this->db->where($this->primary_key,$id);
		return $this->db->get($this->table_name);
	}

    public function insert ($data)
	{
		$this->db->insert($this->table_name,$data);
		return $this->db->insert_id();
	}
	
	public function update ($data,$id)
	{
		$this->db->where($this->primary_key,$id);
		$this->db->update($this->table_name,$data);
	}
	
	public function delete ($id)
	{
		$this->db->where($this->primary_key,$id);
		$this->db->delete($this->table_name);
	}
    
}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */