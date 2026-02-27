<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Absen_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    //datatable query
    private function _get_datasession_query()
    {
        $column_order = array(null, 'username'); //field yang ada di table recruitment
        $column_search = array('username'); //field yang diizin untuk pencarian 
        $order = array('timestamp' => 'DESC'); // default order
        $this->db->select('*');
        $this->db->from('user_sessions');
        $this->db->where('username !=', 'admindika');

        $i = 0;
        foreach ($column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                if ($i === 0) { // looping awal
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_data_foto($foto = '')
    {   
        if($foto !='')
        {
            $this->db->where('foto',$foto);
        }
        return $this->db->get('data_absen');

    }
    function get_data_absen($id ='')
    {
        if($id !='')
        {
            $this->db->where('id',$id);
        }
        return $this->db->get('data_absen');
    }
    function get_datasession()
    {
        $this->_get_datasession_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }
	
	function get_branch($dsr_code)
    {
        $getData = $this->db->query("select branch from  users WHERE sales_code = '$dsr_code'  ");
		
		return $getData;
		$getData->free_result();
    }

    function get_branch_jabodetabek(){
        $this->db->select('office_branch');
        $this->db->where('branch', 'jabodetabek');
        $query = $this->db->get('db_welma.ref_branch');
        return $query->result();
    }

    function get_branch_surabaya(){
        $this->db->select('office_branch');
        $this->db->where('branch', 'surabaya');
        $query = $this->db->get('db_welma.ref_branch');
        return $query->result();
    }

    function get_branch_bandung(){
        $this->db->select('office_branch');
        $this->db->where('branch', 'bandung');
        $query = $this->db->get('db_welma.ref_branch');
        return $query->result();
    }
    function get_branch_semarang(){
        $this->db->select('office_branch');
        $this->db->where('branch', 'semarang');
        $query = $this->db->get('db_welma.ref_branch');
        return $query->result();
    }

    function get_branch_malang(){
        $this->db->select('office_branch');
        $this->db->where('branch', 'malang');
        $query = $this->db->get('db_welma.ref_branch');
        return $query->result();
    }
    function get_branch_medan(){
        $this->db->select('office_branch');
        $this->db->where('branch', 'medan');
        $query = $this->db->get('db_welma.ref_branch');
        return $query->result();
    }

    function get_branch_makassar(){
        $this->db->select('office_branch');
        $this->db->where('branch', 'makassar');
        $query = $this->db->get('db_welma.ref_branch');
        return $query->result();
    }

    function get_all_branch()
    {
        $query = $this->db->get('db_welma.ref_branch');
		return $query;
    }


    function get_office_branch(){
        

        
    }

   

    function count_datasession()
    {
        $this->_get_datasession_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user_sessions');
    }

}
