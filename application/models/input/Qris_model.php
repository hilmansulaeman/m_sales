<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Qris_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
/*	
	private function _get_datatables_query()
    {
        $sales_code = $this->session->userdata('sales_code');
        $uri4 = $this->session->userdata('uri4');

	    $column_order = array(null,'RegnoId','Sales_Code','Sales_Name'); //field yang ada di table recruitment
		$column_search = array('Email','Mobile_Phone_Number'); //field yang diizin untuk pencarian 
		$order = array('RegnoId' => 'DESC'); // default order
		$this->db->select('*');
		$this->db->from('`internal`.`edc_merchant`');
        if($uri4 == 'new_merchant') {
            $where = "Product_Type = 'QRIS' AND Sales_Code = '$sales_code' AND (Hit_Code IN ('101','107') OR (Hit_Code = '102' AND Status = ''))";
        }
        else {
            $where = "Product_Type = 'QRIS' AND Sales_Code = '$sales_code' AND (Hit_Code = '103' OR Hit_Code = '104')";
        }
        $this->db->where($where);
 
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
	    $this->db->from('`internal`.`edc_merchant`');
        $query = $this->db->get();
        return $query;
		$query->free_result();
    } 
    
    function insert($data)
    {
        $this->db->insert('`internal`.`edc_merchant`',$data);
    }
    
    function insert_document($data)
    {
        $this->db->insert('`db_qris`.`upload_temp`',$data);
    }

    function edit_document($doc_id)
    {
        $this->db->where('id',$doc_id);
        return $this->db->get('`db_qris`.`upload_temp`');
    }

    function edit($id)
    {
        $this->db->where('RegnoId',$id);
        return $this->db->get('`internal`.`edc_merchant`');
    }
	
	function update_document($id, $data_update)
    {
        $this->db->where('id', $id);
        $this->db->update('`db_qris`.`upload_temp`',$data_update);
    }


	function get_datamerchant_api()
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		$nik = $this->session->userdata('username');
		$status = $this->session->userdata('uri4');

		$url = 'https://dev.ptdika.com/merchant/api/merchant/dataMerchant?sales_code='.$nik.'&status='.$status;
		
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
*/
	
	// get datatable query
	function _get_dataqris_query($where)
    {
		$sales_code = $this->session->userdata('username');
		
		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'search' 	=> $this->input->post('search')['value'],
			'sales_code'=> $sales_code,
			'wheres'	=> $where
		);

		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/getDataTable';

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
    function _count_dataqris($where)
    {
		$sales_code = $this->session->userdata('username');
		
		$data = array(
			'length' 	=> $this->input->post('length'),
			'start' 	=> $this->input->post('start'),
			'search' 	=> $this->input->post('search')['value'],
			'sales_code'=> $sales_code,
			'wheres'	=> $where
		);

        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/countDataTable';

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

	function insert_data_api($data)
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/add';

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
		
		//get ID
		$response = json_decode($result);
		$status = $response->status;
		if($status == 'TRUE'){
			$id = $response->regnoid;
		}
		else{
			$id = '0';
		}
		$this->session->set_userdata('RegnoId', $id);
	}
	
	function get_by_id($regnoid) {
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/merchant_by_id?regnoid='.$regnoid;

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

		$mainData = json_decode($result);
		$data = $mainData->data;
		
		return $data;
	}

	function update_data_api($id)
	{
		$data = array(
			'owner_name'			=> $this->input->post('owner_name'),
			'merchant_name'			=> $this->input->post('merchant_name'),
			'email'					=> $this->input->post('email'),
			'mid_type'				=> $this->input->post('mid_type'),
			'account_number'		=> $this->input->post('account_number'),
			'mobile_phone_number'	=> $this->input->post('mobile_phone_number'),
			'other_phone_number'	=> $this->input->post('other_phone_number'),
			'officer_code'			=> $this->input->post('officer_code'),
			'regnoid'        		=> $id
		);
		
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/edit';

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
		
		//get ID
		$response = json_decode($result);
		$status = $response->status;
		if($status == 'TRUE'){
			$regnoid = $response->regnoid;
		}
		else{
			$regnoid = '0';
		}
		$this->session->set_userdata('RegnoId', $regnoid);
	}
	
	function get_area_akusisi() {
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/area_akusisi';

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

		$mainData = json_decode($result);
		$data = $mainData->data;
		
		return $data;
	}
	
	function get_ktp($regnoid) {
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/dokumen_category?customer_id='.$regnoid.'&category=ktp';

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

		$mainData = json_decode($result);
		$data = $mainData->data;
		
		return $data;
	}
	
	function get_merchant($regnoid) {
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/dokumen_category?customer_id='.$regnoid.'&category=merchant';

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

		$mainData = json_decode($result);
		$data = $mainData->data;
		
		return $data;
	}
	
	function get_product($regnoid) {
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/dokumen_category?customer_id='.$regnoid.'&category=product';

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

		$mainData = json_decode($result);
		$data = $mainData->data;
		
		return $data;
	}
	
	function get_npwp($regnoid) {
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/dokumen_category?customer_id='.$regnoid.'&category=npwp';

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

		$mainData = json_decode($result);
		$data = $mainData->data;
		
		return $data;
	}
	
	function get_doc($doc_id) {
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/doc_by_id?doc_id='.$doc_id;

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

		$mainData = json_decode($result);
		//$data = $mainData->data;
	
		$status = $mainData->status;
		if($status == 'TRUE'){
			$data = $mainData->data;
		}
		else{
			$data = array('image_name'=>'upload.png');
		}
		
		return $data;
	}
	
	function add_document_api($doc_id, $customer_id, $file_name, $geoLocation, $category_name) {
		//echo $doc_id.' &'.$customer_id.' & '.$file_name.' & '.$geoLocation.' & '.$category_name.' & '.$is_old;die;
		
		$data = array(
			'customer_id'			=> $customer_id,
			'category_id'			=> $doc_id,
			'category_name'			=> $category_name,
			'image_name'			=> $file_name,
			'geo_info'				=> $geoLocation
		);
		
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/add_document';

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
		
		//get ID
		$response = json_decode($result);
		$status = $response->status;
		
		if($status == 'TRUE'){
			$regnoid = $response->regnoid;
		}
		else{
			$regnoid = '0';
		}
		$this->session->set_userdata('RegnoId', $regnoid);
	}
	
	function add_document_api_copy($doc_id, $customer_id, $file_name, $geoLocation, $category_name) {
		//echo $doc_id.' &'.$customer_id.' & '.$file_name.' & '.$geoLocation.' & '.$category_name;die;
		
		$data = array(
			'customer_id'			=> $customer_id,
			'category_id'			=> $doc_id,
			'category_name'			=> $category_name,
			'image_name'			=> $file_name,
			'geo_info'				=> $geoLocation
		);
		
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/add_document';

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
		
		//get ID
		$response = json_decode($result);
		$status = $response->status;
		
		if($status == 'TRUE'){
			$regnoid = $response->regnoid;
		}
		else{
			$regnoid = '0';
		}
		$this->session->set_userdata('RegnoId', $regnoid);
	}
	
	function update_document_api($doc_id, $customer_id, $file_name, $geoLocation) {
		
		$data = array(
			'customer_id'			=> $customer_id,
			'doc_id'				=> $doc_id,
			'image_name'			=> $file_name,
			'geo_info'				=> $geoLocation
		);
		
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.'api/merchant/update_document';

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
		
		//get ID
		$response = json_decode($result);
		$status = $response->status;
		if($status == 'TRUE'){
			$regnoid = $response->regnoid;
		}
		else{
			$regnoid = '0';
		}
		$this->session->set_userdata('RegnoId', $regnoid);
	}

	function delete_npwp_api($regnoid, $npwp_id)
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		$url = $url_api.'api/merchant/delete_npwp?regnoid='.$regnoid.'&npwp_id='.$npwp_id;
		
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
		$data = $dataDecode->status;
		$id = $dataDecode->regnoid;
		
		$this->session->set_userdata('RegnoId', $id);

		return $data;
	}
	
	function upload_document_api($data)
	{
		$this->post_data('api/fileupload/add',$data);
	}
	
	function edit_document_api($data)
	{
		$this->post_data('api/fileupload/edit',$data);
	}
	
	//======================================= INTERNAL FUNCTION ========================================//
	
	private function post_data($uri,$data)
	{
		$getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
		$rowAPI = $getAPI->row();

		$url_api = $rowAPI->url;
		$apiKey = $rowAPI->api_key;

		// API auth credentials
		$apiUser = $rowAPI->Username;
		$apiPass = $rowAPI->Password;

		// API URL
		$url = $url_api.$uri;

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
		curl_setopt($ch, CURLOPT_POST, 1);
		/*curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  'Content-type: multipart/form-data;',
		));*/
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);
		
		$response = json_decode($result);
		$this->session->set_flashdata('message', "<span class='btn btn-info'><b>".$response->message."</b></span>");
	}

}