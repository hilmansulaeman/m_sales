<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pemol_model extends MY_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// get ASM
	function get_by_asm($str)
	{
		$getAPI = $this->db->get_where('key_api', array('Description'=>'Pemol'));
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url . 'api/customer/dataByASM?sm_code=' . $str;

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$dataDecode = json_decode($result);
		$data = $dataDecode->data;

		return $data;
	}

	// get RSM
	function get_by_rsm($str)
	{
		$getAPI = $this->db->get_where('key_api', array('Description'=>'Pemol'));
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url . 'api/customer/dataByRSM?sm_code=' . $str;

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$dataDecode = json_decode($result);
		$data = $dataDecode->data;

		return $data;
	}

	// get Branch
	function getBranch()
	{
		$getAPI = $this->db->get_where('key_api', array('Description'=>'Pemol'));
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url . 'api/customer/dataBranch';

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$dataDecode = json_decode($result);
		$data = $dataDecode->data;

		return $data;
	}

	// get datatable query
	function _get_datapemol_query($nik, $where)
	{
		$position = $this->session->userdata('position');

		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'sales_code' => $nik,
			'search' 	=> $this->input->post('search')['value'],
			'wheres'	=> $where,
			'position'	=> $position
		);

		$getAPI = $this->db->get_where('key_api', array('Description'=>'Pemol'));
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url . 'api/customer/getDataTable';

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$dataDecode = json_decode($result);
		$data = $dataDecode->data;

		return $data;
	}

	// count datatable query
	function _count_datapemol($nik, $where)
	{
		$position = $this->session->userdata('position');

		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'sales_code' => $nik,
			'search' 	=> $this->input->post('search')['value'],
			'wheres'	=> $where,
			'position'	=> $position
		);

		$getAPI = $this->db->get_where('key_api', array('Description'=>'Pemol'));
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url . 'api/customer/countDataTable';

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$dataDecode = json_decode($result);
		$data = $dataDecode->data;

		return $data;
	}

	// insert query
	function insert_data_api()
	{
		$data = array(
			'Account_Number' => $this->input->post('Account_Number'),
			'Source'		 => $this->input->post('Source'),
			'Category'		 => $this->input->post('Category'),
			'Referral'	     => ($this->input->post('Category') == 'Referral') ? $this->input->post('Referral') : null,
			'Sales_Code'	 => $this->session->userdata('sl_code'),
			'Sales_Name'	 => $this->session->userdata('realname'),
			'Branch'		 => $this->session->userdata('branch'),
			'Input_Date'	 => date('Y-m-d')
		);

		$getAPI = $this->db->get_where('key_api', array('Description'=>'Pemol'));
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url . 'api/customer/add';

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$data = json_decode($result);

		return $data;
	}

	// get where query
	function getWhere_data_api($id, $part) //get by id or account_number
	{
		$getAPI = $this->db->get_where('key_api', array('Description'=>'Pemol'));
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url . 'api/customer/dataWhere?id=' . $id . '&reference=' . $part;

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$dataDecode = json_decode($result);
		$data = $dataDecode->data;

		return $data;
	}

	// get edit query
	function edit_data_api($id)
	{
		$data = array(
			'Account_Number' => $this->input->post('Account_Number'),
			'Source'		 => $this->input->post('Source'),
			'Category'		 => $this->input->post('Category'),
			'Referral'	     => ($this->input->post('Category') == 'Referral') ? $this->input->post('Referral') : null,
			'Modified_Date'	 => date('Y-m-d H:i:s'),
			'Modified_By'	 => $this->session->userdata('realname'),
			'RegnoId'        => $id
		);

		$getAPI = $this->db->get_where('key_api', array('Description'=>'Pemol'));
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url . 'api/customer/edit';

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);
	}
	
	// get Referal
	function get_referal()
	{
		$getAPI = $this->db->get_where('key_api', array('Description'=>'Pemol'));
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url . 'api/referral';

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		$dataDecode = json_decode($result);
		$data = $dataDecode->data;

		return $data;
	}

	//datatable query
	// private function _get_datatables_query($where)
	// {
	//     $position = $this->session->userdata('position');
	// 	$allow_join = array('SPV','ASM','RSM','ASH','BSH');
	//     $column_order = array(null,'t1.nomor_kartu','t1.no_rek','t1.nama_customer','t1.sales_code','t1.sales_name'); //field yang ada di table recruitment
	// 	$column_search = array('t1.nomor_kartu','t1.no_rek','t1.nama_customer','t1.sales_code','t1.sales_name'); //field yang diizin untuk pencarian 
	// 	$order = array('t1.id' => 'DESC'); // default order
	// 	$this->db->select('*');
	//     $this->db->from('tbl_kartu t1');
	// 	if(in_array($position,$allow_join)){
	// 	    $this->db->join('`internal`.`data_sales_copy` t2', 't1.sales_code=t2.DSR_Code', 'left');
	// 		$this->db->where($where);
	// 	}
	// 	else{
	// 	    $this->db->where($where);
	// 	}
	// 	$this->db->where('t1.jenis_kartu', '1');

	//     $i = 0;
	//     foreach ($column_search as $item) // looping awal
	//     {
	//         if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
	//         {
	//             if($i===0){ // looping awal
	//                 $this->db->group_start(); 
	//                 $this->db->like($item, $_POST['search']['value']);
	//             }
	//             else{
	//                 $this->db->or_like($item, $_POST['search']['value']);
	//             }

	//             if(count($column_search) - 1 == $i){
	//                 $this->db->group_end();
	// 			}
	//         }
	//         $i++;
	//     }

	//     if(isset($_POST['order'])) 
	//     {
	//         $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
	//     } 
	//     else if(isset($order))
	//     {
	//         $this->db->order_by(key($order), $order[key($order)]);
	//     }
	// }

	// function get_datatables($where)
	// {
	//     $this->_get_datatables_query($where);
	//     if($_POST['length'] != -1)
	//     $this->db->limit($_POST['length'], $_POST['start']);
	//     $query = $this->db->get();
	//     return $query;
	// 	$query->free_result();
	// }

	// function count_filtered($where)
	// {
	//     $this->_get_datatables_query($where);
	//     $query = $this->db->get();
	//     return $query->num_rows();
	// }

	//get SPV BY ASM
	// function get_by_asm($sm_code)
	// {
	//     $this->db->select('*');
	// 	$this->db->from('`internal`.`data_sales`');
	// 	$this->db->where('SM_Code', $sm_code);
	// 	$this->db->where('Position', 'SPV');
	// 	$this->db->where('Status', 'ACTIVE');
	// 	$this->db->order_by('Name', 'ASC');
	// 	$query = $this->db->get();
	// 	return $query;
	// 	$query->free_result();
	// }

	//get SPV BY RSM
	// function get_by_rsm($sm_code)
	// {
	//     $this->db->select('*');
	// 	$this->db->from('`internal`.`data_sales_structure`');
	// 	$this->db->where('RSM_Code', $sm_code);
	// 	$this->db->where('Position', 'SPV');
	// 	$this->db->where('Status', 'ACTIVE');
	// 	$this->db->order_by('Name', 'ASC');
	// 	$query = $this->db->get();
	// 	return $query;
	// 	$query->free_result();
	// }

	// function get_all_list_detail($id)
	// {
	// 	$getData = $this->db->get_where('tbl_kartu',array('id'=>$id));
	// 	return $getData;
	// 	$getData->free_result();
	// }

	// function get_kartu($nomor_kartu)
	// {
	// 	$getData = $this->db->get_where('tbl_kartu',array('nomor_kartu'=>$nomor_kartu));		
	// 	return $getData;
	// 	$getData->free_result();
	// }

	// function get_upload_id($id)
	// {
	// 	$getData = $this->db->get_where('tbl_kartu',array('id'=>$id));
	// 	return $getData;
	// 	$getData->free_result();
	// }

	// function get_max_id()
	// {
	// 	$getData = $this->db->query("SELECT id FROM tbl_kartu ORDER BY id DESC LIMIT 0,1");	
	// 	return $getData->row();
	// 	$getData->free_result();
	// }

	/*function get_event()
	{
	    $this->db->select('DISTINCT(event)');
		$this->db->from('event');
		$this->db->where('is_active','1');
		$getData = $this->db->get();
		return $getData;
		$getData->free_result();
	}*/

	// function get_event($category)
	// {
	// 	$this->db->where('category',$category);
	// 	$this->db->where('is_active','1');
	// 	$this->db->order_by('event','asc');
	// 	$sql_reason = $this->db->get('event');
	// 	if($sql_reason->num_rows()>0){

	// 		foreach ($sql_reason->result_array() as $row)
	// 		{
	// 			$result[$row['kode_promosi'].'||'.$row['event']]= $row['kode_promosi'].' ('.$row['event'].')';
	// 		}
	// 	}
	// 	else{
	// 		$result['']= '- Silahkan pilih kategori -';
	// 	}
	// 	return $result;
	// }

	// function search($key='')
	// {
	//     $key_ = $this->db->escape_str($key);
	// 	$this->db->select('*');
	// 	$this->db->from('data_pemol');
	// 	$this->db->where('Account_Number', $key_);
	// 	$query = $this->db->get();
	// 	return $query;
	// 	$query->free_result();
	// }

	// function search1($key='')
	// {
	//     $key_ = $this->db->escape_str($key);
	// 	$this->db->select('*');
	// 	$this->db->from('tbl_kartu');
	// 	$this->db->where('jenis_kartu', '1');
	// 	$this->db->group_start();
	// 	    $this->db->or_where('nomor_kartu',$key_);
	// 	    $this->db->or_where('no_rek',$key_);
	// 	    $this->db->or_like('replace(nama_customer," ","+")',$key_);
	// 	$this->db->group_end();
	// 	$query = $this->db->get();
	// 	return $query;
	// 	$query->free_result();
	// }

	// function insert_log($data)
	// {
	// 	$this->db->insert('log_activity',$data);
	// }

	// function insert_kartu($data)
	// {
	// 	$this->db->insert('tbl_kartu',$data);
	// }

	// function count_datapemol_api($where, $position)
	// {
	// 	$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Pemol'");
	// 	$rowAPI = $getAPI->row();

	// 	$apiKey = $rowAPI->api_key;

	// 	// API auth credentials
	// 	$apiUser = $rowAPI->Username;
	// 	$apiPass = $rowAPI->Password;

	// 	// API URL
	// 	$url = 'https://dev.ptdika.com/funding_officer/new/api/customer/countDataPemol?wheres='.$where.'&position='.$position;

	// 	// Create a new cURL resource
	// 	$ch = curl_init($url);

	// 	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// 	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
	// 	curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");

	// 	$result = curl_exec($ch);

	// 	// Close cURL resource
	// 	curl_close($ch);

	// 	$dataDecode = json_decode($result);
	// 	$data = $dataDecode->data;

	// 	return $data;
	// }
		public function check_limit_data_event($nik,$product,$tanggal){
        $this->db->select("*");
        $this->db->from('data_limit_event');
        $this->db->where('start_date <=', $tanggal);
        $this->db->where('end_date >=', $tanggal);
        $this->db->where('sales_code', $nik);
        $this->db->where('product', $product);
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }

    public function check_pemol_exist($sales_code,$created_date){
        $getAPI = $this->db->get_where('key_api', array('Description' => 'Pemol'));
        $rowAPI = $getAPI->row();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        $url = $rowAPI->url . 'api/customer/getPemol_sales_code_tanggal?sales_code='.$sales_code.'&created_date='.$created_date;

        // API URL

        // Create a new cURL resource
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
        curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");

        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);

        $dataDecode = json_decode($result);
        $data = $dataDecode->data;

        return $data;
    }

    public function check_limit($product){
        $this->db->select("*");
        $this->db->from('data_limit');
        $this->db->where('product', $product);
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }
}
