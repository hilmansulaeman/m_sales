<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edc_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// get datatable query
	function _get_data_query()
    {
		$sales_code = $this->session->userdata('username');
        $uri4 		= $this->session->userdata('uri4');

		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'search' 	=> $this->input->post('search')['value'],
			'sales_code'=> $sales_code,
			'uri4'		=> $uri4
		);

		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/api_merchant_edc/getDataTable';

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
    function _count_data()
    {
		$sales_code = $this->session->userdata('username');
        $uri4 		= $this->session->userdata('uri4');

		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'search' 	=> $this->input->post('search')['value'],
			'sales_code'=> $sales_code,
			'uri4'		=> $uri4
		);

        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $rowAPI->url.'api/api_merchant_edc/countDataTable';

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

	// insert data
    function insert_data_api()
    {
        $data = array(
            'Product_Type'			=> $this->input->post('product_type'),
            'Email'					=> $this->input->post('email'),
            'MID_Type'				=> $this->input->post('mid_type'),
            'Owner_Name'			=> $this->input->post('owner_name'),
            'Merchant_Name'			=> $this->input->post('merchant_name'),
            'Account_Number'		=> $this->input->post('account_number'),
            'Mobile_Phone_Number'	=> $this->input->post('mobile_phone_number'),
            'Other_Phone_Number'	=> $this->input->post('other_phone_number'),
            'Sales_Code'			=> $this->input->post('sales_code'),
            'Sales_Name'			=> $this->input->post('sales_name'),
            'Branch'				=> $this->input->post('branch'),
            'Created_By'			=> $this->input->post('sales_name')
        );

        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

        $url = $rowAPI->url.'api/api_merchant_edc/add';

		// API URL

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

	// update data
	function update_data_api($id)
	{
		$data = array(
			'Product_Type'			=> $this->input->post('product_type'),
			'Email'					=> $this->input->post('email'),
			'MID_Type'				=> $this->input->post('mid_type'),
			'Owner_Name'			=> $this->input->post('owner_name'),
			'Merchant_Name'			=> $this->input->post('merchant_name'),
			'Account_Number'		=> $this->input->post('account_number'),
			'Mobile_Phone_Number'	=> $this->input->post('mobile_phone_number'),
			'Other_Phone_Number'	=> $this->input->post('other_phone_number'),
			'RegnoId'				=> $id
		);

		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

        $url = $rowAPI->url.'api/api_merchant_edc/edit';

		// API URL

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

	// validasi cek email
	function cekEmail($email, $part)
    {
        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

        $url = $rowAPI->url.'api/api_merchant_edc/cekEmail?email='.$email.'&part='.$part;

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

	// validasi cek email update
	function cekEmailUpdate($id, $email, $part)
    {
        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

        $url = $rowAPI->url.'api/api_merchant_edc/cekEmailUpdate?id='.$id.'&email='.$email.'&part='.$part;

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

	// validasi cek nomor rekening
	function cekNorek($type, $account_number)
    {
        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

        $url = $rowAPI->url.'api/api_merchant_edc/cekNorek?type='.$type.'&account_number='.$account_number;

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

	// validasi cek nomor rekening update
	function cekNorekUpdate($id, $account_number)
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

        $url = $rowAPI->url.'api/api_merchant_edc/cekNorekUpdate?id='.$id.'&account_number='.$account_number;

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

	// get by id
	function getById($id)
    {
        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

        $url = $rowAPI->url.'api/api_merchant_edc/getById?id='.$id;

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
	
	// private function _get_datatables_query()
    // {
    //     $sales_code = $this->session->userdata('sales_code');
    //     $uri4 = $this->session->userdata('uri4');

	//     $column_order = array(null,'RegnoId','Sales_Code','Sales_Name'); //field yang ada di table recruitment
	// 	$column_search = array('Account_Number','Mobile_Phone_Number'); //field yang diizin untuk pencarian 
	// 	$order = array('RegnoId' => 'DESC'); // default order
	// 	$this->db->select('*');
	// 	$this->db->from('`internal`.`edc_merchant`');
    //     if($uri4 == 'new_merchant') {
    //         $where = "Product_Type IN('EDC','EDC_QRIS') AND Sales_Code = '$sales_code' AND (Hit_Code IN ('101','107') OR (Hit_Code = '102' AND (Status IS NULL OR Status = '')))";
    //     }
    //     else {
    //         $where = "Product_Type IN('EDC','EDC_QRIS') AND Sales_Code = '$sales_code' AND (Hit_Code IN('103','104'))";
    //     }
    //     $this->db->where($where);

    //     $i = 0;
    //     foreach ($column_search as $item) // looping awals
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
    //             }
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
	// 	//$this->db->group_by($this->primary_key);
    // }

    // function get_datatables()
    // {
    //     $this->_get_datatables_query();
    //     if($_POST['length'] != -1)
    //     $this->db->limit($_POST['length'], $_POST['start']);
    //     $query = $this->db->get();
    //     return $query;
	// 	$query->free_result();
    // }

    // function count_filtered()
    // {
    //     $this->_get_datatables_query();
    //     $query = $this->db->get();
    //     return $query->num_rows();
    // }
	
	// function count_all()
    // {
	//     $this->db->select('count(1) as total');
	//     $this->db->from('`internal`.`edc_merchant`');
    //     $query = $this->db->get();
    //     return $query;
	// 	$query->free_result();
    // } 
    
    // function insert($data)
    // {
    //     $this->db->insert('`internal`.`edc_merchant`',$data);
    // }
	
	// function edit($id)
	// {
	// 	$this->db->where('RegnoId', $id);
	// 	return $this->db->get('`internal`.`edc_merchant`');
	// }
	
	// function auto_number($code)
	// {
	// 	$query = $this->db->query("SELECT max(Barcode) AS maxCode FROM `internal`.`edc_merchant` WHERE Barcode LIKE '$code%'");
	// 	return $query;
	// 	$query->free_result();
	// }
}

/* End of file Db_model.php */
/* Location: ./application/models/Db_model.php */
	