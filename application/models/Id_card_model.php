<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Id_card_model extends CI_Model
{
	
    function __construct()
    {
        parent::__construct();
    }

    private function get_api()
    {
        $this->db->select('*');
        $this->db->from('key_api');
        $this->db->where('Description', 'Rest API');
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }
    // get datatable query
    private function _get_datatables_api($url)
    {
        $rowAPI = $this->get_api()->row();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        // Create a new cURL resource
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
        curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);

        $data = json_decode($result);
        
        return $data;
    }

    // get datatable query
    function get_datatables_api($hit_code, $created_by)
    {
        $rowAPI = $this->get_api()->row();
        $createdby = urlencode($created_by);
        // API URL
        // https://dev.ptdika.com/rest-api/api/api_idcard/get_index?hit_code=8&sales_code=D8260002
        $url = $rowAPI->url . 'api/api_idcard/request?hit_code='.$hit_code.'&created_by='.$createdby.'';

        // $query = $this->_get_datatables_api($url);
        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        // Create a new cURL resource
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
        curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);

        $data = json_decode($result);
        
        return $data->data;
    }

    function get_sales_api($sales_code){
        $rowAPI = $this->get_api()->row();

        // API URL
        // $url = 'http://localhost/rest-api/api/api_idcard/get_sales/'.$sales_code.'';
        $url = $rowAPI->url . 'api/api_idcard/sales/'.$sales_code.'';
        
        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
        curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
        // curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);

        $data = json_decode($result);
        return $data->data;
    }

    public function insert_api($data)
    {
        $rowAPI = $this->get_api()->row();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        $url = $rowAPI->url . 'api/api_idcard/add';

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
        $data = $response;
        
        return $data;
    }
}