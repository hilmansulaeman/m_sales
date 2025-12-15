<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addendum_model extends CI_Model
{
	private $primary_key ='';
	private $table_name = '';
	private $table_name2 =''; 
	function __construct()
	{
		parent::__construct();
	}

	function config($table,$id)
	{
		$this->table_name = $table;
		$this->primary_key = $id;
	}

	function config_join($table1, $table2, $id)

	{
		$this->table_name = $table1;
		$this->table_name2 = $table2;
		$this->primary_key = $id;
	}
	
	private function _get_datatables_query()
  {
		$sl_code = $this->session->userdata('sl_code');
		$name = $this->session->userdata('realname');

		$this->db->select('t1.*');
		$this->db->from('internal_sms.data_adendum_sales AS t1');
		$this->db->join('internal_sms.data_adendum_upload AS t2', 't1.upload_id=t2.upload_id');
		$this->db->group_start();
			$this->db->where('t1.sales_code', $sl_code);
			$this->db->or_where('t1.sales_name', $name);
		$this->db->group_end();
		$this->db->where('t2.is_active', '1');
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
	
	public function get_all_data()
	{
		// $andendum_id = $this->session->userdata('andendum_id');
		// $this->db->select('t1.*, t2.*');
		// $this->db->from('data_adendum_sales t1');
		// $this->db->join('users t2', 't1.sales_code = t2.sales_code','left');

		// $query = $this->db->get();
		// return $query;


		$sales_code = $this->session->userdata('sl_code');
		
		$this->db->select('t1.*');
		$this->db->from('data_adendum_sales t1');
	
		$this->db->where('t1.sales_code', $sales_code);

		$query = $this->db->get();
		return $query;
	}
	public function getRecruitment($id)
	{
		// $recruitment = $this->session->userdata('sl_code');
		$this->db->select('t1.*, t2.*');
		$this->db->from('data_adendum_sales t1');
		$this->db->join('data_recruitment t2', 't1.recruitment_id = t2.recruitment_id','left');
		// $this->db->join('users t3', 't1.sales_code = t3.sales_code','left');		
		$this->db->where('t1.adendum_id', $id);		
		
		
		$query = $this->db->get();
		return $query->result();
		// $result = $this->db->query($);
		// return $result->result_array();
	}
	public function aggrement()
	{
		$this->db->select('data_adendum_sales.*');
		$this->db->from('data_adendum_sales');
		$this->db->where('agreement');

		$query = $this->db->get();
		return $query->result();
	}
	public function getAddendum($file)
	{
		$sales_code = $this->session->userdata('sl_code');
		$this->db->select('t1.*, t2.*');
		$this->db->from('internal_sms.data_adendum_sales t1');
		$this->db->join('internal_sms.set_agreement_mitra t2', 't1.template_name = t2.template_name','left');
		$this->db->where('t1.template_name', $file);
		$this->db->where('t1.sales_code',$sales_code);
		
		$query = $this->db->get();
		return $query;
	}

	public function getDataVerified()
	{
		$salescode = $this->session->userdata('sl_code');
		$this->db->select('*');
        $this->db->from('internal_sms.data_adendum_sales');
		$this->db->where("sales_code = '$salescode'");
		// $this->db->order_by("sales_code", "desc");
		$query = $this->db->get();
		return $query;
	}

	function get_agreement($id)
	{
		$this->db->select('*');
		$this->db->order_by('Agreement_ID','DESC');
		$query = $this->db->get_where('data_agreement',array('Recruitment_ID'=>$id),1,0);
		return $query;
		$query->free_result();
	}

	function get_exist_agreement($id)
	{
	    $query = $this->db->get_where('data_agreement',array('Recruitment_ID'=>$id));
		return $query;
		$query->free_result();
	}

	function insert_agreement ($data)
	{
		$this->db->insert('data_agreement',$data);
		return $this->db->insert_id();
	}

	function update_agreement ($data,$id)
	{
		$this->db->where('Recruitment_ID',$id);
		$this->db->update('data_agreement',$data);
	}

	function insert_log($data)
	{
		$this->db->insert('data_process_logs',$data);
		return $this->db->insert_id();
	}

	function duplicate_nik ($code)
	{
		$this->db->where('NIK',$code);
		$query = $this->db->get('data_employee');
		return $query;
		$query->free_result();
	}

	function duplicate_sales_code ($code)
	{
		$this->db->where('DSR_Code',$code);
		$query = $this->db->get('data_employee');
		return $query;
		$query->free_result();
	}

	function export_sid ($start,$end)
	{
		//get data
		if($start == '' && $end == ''){
			$where = "(Hit_Code > 8 AND SID_Checking_Status = '0')";
		}
		else{
			$where = "(Hit_Code > 8 AND Input_Date >= '$start' AND Input_Date <= '$end')";
		}
		$this->db->where($where);
		$query = $this->db->get('data_recruitment');
		return $query;
		$query->free_result();

		//flagging update
		$data_update = array(
			'SID_Checking_Status' => '1'
		);
		$this->db->update('data_recruitment',$data_update);
	}
	
	function get_page_setup_mitra($id)
	{
	    $this->db->distinct();
		$this->db->select('Set_Page');
		$this->db->from('set_agreement_mitra_detail');
		$this->db->where('Template_ID', $id);
		// $this->db->where('Template_name',$template_name);
		$this->db->order_by('Set_Page', 'ASC');
		$query = $this->db->get();
		return $query;
		$query->free_result();
	}

	function get_page_mitra($id)
	{
		
		$this->db->select('t1.*, t2.*');
		$this->db->from('set_agreement_mitra t1');
		$this->db->join('set_agreement_mitra_detail t2', 't1.template_id = t2.template_id','left');
		$this->db->where('t1.template_id', $id);		
		$query = $this->db->get();
		
		return $query->result();
	}
	function get_mitra()
	{
		$this->db-> select('*');
		$this->db-> from('set_agreement_mitra_detail');
	}


	function get_data_mitra($id,$page,$type)
	{

	    $where = array('Template_ID'=>$id, 'Set_Page'=>$page, 'Set_Type'=>$type);
		// var_dump($where);
	    $query = $this->db->get_where('set_agreement_mitra_detail',$where);
		return $query;
		$query->free_result();
	}

	function get_addendum_by_id($id)
	{
		$this->db->where('adendum_id', $id);
		$query = $this->db->get($this->table_name);
		return $query;
		$query->free_result();
	}

	function get_by_sl_code ($slcode)
	{
		$this->db->where('sales_code', $slcode);
		$query = $this->db->get($this->table_name);
		return $query;
		$query->free_result();
	}
	
	function insert ($data)
	{
		$this->db->insert($this->table_name,$data);
		return $this->db->insert_id();
	}
	
	function update ($data, $adendum_id)
	{
		$this->db->where('adendum_id', $adendum_id);
		$this->db->update($this->table_name, $data);
	}

	// function update ($data,$id)
	// {
	// 	$this->db->where($this->primary_key,$id);
	// 	$this->db->update($this->table_name,$data);
	// }
	
	function delete ($id)
	{
		$this->db->where($this->primary_key,$id);
		$this->db->delete($this->table_name);
	}

	function getDataJoin($where='', $order=''){
		if($where){
			$this->db->where($where);
		}

		if($order){
			$this->db->order_by($order,'DESC');
		}

		$this->db->select('*');
		$this->db->from($this->table_name.' t1');
		$this->db->join($this->table_name2.' t2', 't2.'.$this->primary_key.' = t1.'.$this->primary_key,'left');
		$query = $this->db->get();
		
		return $query;
		$query->free_result();
	}

	function getTable($table,$where='')
	{
		if($where){
			$this->db->where($where);
		}
		$query = $this->db->get($table);
		
		return $query;
		$query->free_result();
	}

	
	// function insert($data)
	// {
	// 	$this->db->insert('adendum_id',$data);
	// }
	
	function get_by_id($id)
	{
		$this->db->where('adendum_id',$id);
		return $this->db->get('data_adendum_sales');
	}
	
	// function update($data,$id)
	// {
	// 	$this->db->where('id',$id);
	// 	$this->db->update('adendum_id',$data);
	// }
	
	// function delete($id)
	// {
	// 	$this->db->where('id',$id);
	// 	$this->db->delete('adendum_id');
	// }

	function get_data_adendum($adendum_id, $sl_code)
	{
		$this->db->select('*');
		$this->db->from('data_adendum_sales');
	
		$this->db->where('sales_code', $sl_code);
		$this->db->where('adendum_id', $adendum_id);

		$query = $this->db->get();
		return $query;
	}

	function ab($recruitment)
	{
		$query = $this->db->query(
			"SELECT `Signature` 
			FROM data_adendum_sales t1 
			INNER JOIN `db_hrd`.`data_recruitment` t2 ON t1.recruitment_id = t2.Recruitment_ID 
			WHERE t2.Recruitment_ID = '$recruitment'")->row();
		return $query->Signature;
	}
	function getSignature($recruitment)
	{
		// API URL
		$url = 'dev.ptdika.com/employee/api/sales?id='.$recruitment;
		

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		$result = curl_exec($ch);	
		
		
		$dataDecode = json_decode($result);
		
		
		

		return $dataDecode;
	}
	

}
