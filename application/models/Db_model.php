<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Db_model extends CI_Model
{
	private $primary_key = '';
	private $table_name = '';
	var $table_='schedule_training';
	
	function __construct()
	{
		parent::__construct();
	}
	
	function config($table,$id)
    {
        $this->table_name = $table;
        $this->primary_key = $id;
    }
	
	function get_all_data($limit = 10, $offset = 0, $order_column = '', $order_type = 'desc')
	{
		$user = $this->session->userdata('realname');
		$level = $this->session->userdata('level');
		$query = $this->db->get($this->table_name, $limit, $offset);
		
		if (empty($order_column)||empty($order_type))
			$this->db->order_by($this->primary_key, 'desc');
		else
			$this->db->order_by($order_column, $order_type);
		return $query;
		$query->free_result();
	}
	
	function total()
	{
		$user = $this->session->userdata('realname');
		$level = $this->session->userdata('level');
		$this->db->select('count(1) as total');
		$query = $this->db->get($this->table_name);
		return $query;
		$query->free_result();
	}
	
	function get_all($table)
	{
		$query = $this->db->get($table);
		return $query;
		$query->free_result();
	}
	
	function get_by_id ($id)
	{
		$this->db->where($this->primary_key,$id);
		$query = $this->db->get($this->table_name);
		$query->free_result();
	}
	
	function insert ($data)
	{
		$this->db->insert($this->table_name,$data);
		return $this->db->insert_id();
	}
	
	function update ($data,$id)
	{
		$this->db->where($this->primary_key,$id);
		$this->db->update($this->table_name,$data);
	}
	
	function delete ($id)
	{
		$this->db->where($this->primary_key,$id);
		$this->db->delete($this->table_name);
	}
	
	function search_id($key)
	{
		$table = $this->table_name;
		$query = $this->db->query("select * from $table where RegnoId='$key'");	
		return $query;
		$query->free_result();
	}
	
	function archive($user,$level)
	{
		$table = $this->table_name;
		if ($level == 1 or $level == 2)
			$query = $this->db->query("SELECT DISTINCT DATE_FORMAT(Created_Date, '%d %M %Y') AS Date FROM $table order by RegnoId DESC");
		else
			$query = $this->db->query("SELECT DISTINCT DATE_FORMAT(Created_Date, '%d %M %Y') AS Date FROM $table where Created_by='$user' order by RegnoId DESC");
		return $query;
		$query->free_result();
	}
	
	function location($kata)
	{
		if($kata == "")
		{
			$filter = "";
		}
		else
		{
			$filter = " where area='$kata'";
		}
		$sql = $this->db->query("select distinct location from schedule_training $filter order by id asc");
		return $sql;
		$sql->free_result();
	}
	
	function available_date($kata1, $kata2)
	{
		if($kata1 == "")
		{
			$filter = "";
		}
		else
		{
			$filter = " where area = '$kata1' and location='$kata2'";
		}
		$sql = $this->db->query("select distinct available_date from schedule_training $filter order by id asc");
		return $sql;
		$sql->free_result();
	}
	
	function available_time($kata1, $kata2, $kata3)
	{
		if($kata1 == "")
		{
			$filter = "";
		}
		else
		{
			$filter = " where area = '$kata1' and location='$kata2' and available_date='$kata3'";
		}
		$sql = $this->db->query("select distinct time from schedule_training $filter order by id asc");
		return $sql;
		$sql->free_result();
	}
	
	function update_seat_old($id)
	{
		$sql = $this->db->query("update schedule_training set available_seat=available_seat+1 where id='$id'");
		return $sql;
		$sql->free_result();
	}
	
	function update_seat_new($id)
	{
		$sql = $this->db->query("update schedule_training set available_seat=available_seat-1 where id='$id'");
		return $sql;
		$sql->free_result();
	}
	
	function update_particips($id)
	{
		$sql = $this->db->query("update training_participants set Area='',  Location='', tgl_training='', waktu_training='', schedule_id='' available_seat=available_seat+1 where id='$id'");
		return $sql;
	}
	
	function getKota($params = array())
	{
		$this->db->select('*');
		$this->db->from('`internal`.`data_kota`');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('provinsi',$params['search']['keywords']);
			$this->db->or_like('kota',$params['search']['keywords']);
			$this->db->or_like('kecamatan',$params['search']['keywords']);
			$this->db->or_like('kelurahan',$params['search']['keywords']);
			$this->db->or_like('kode_pos',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('provinsi',$params['search']['sortBy']);
        }else{
            $this->db->order_by('provinsi','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result():FALSE;
    }
	
}

/* End of file db_model.php */
/* Location: ./application/models/db_model.php */
	