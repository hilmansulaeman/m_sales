<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Recruitment_approval_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    // get datatable query
    private function _get_datatables($url)
    {
        $sl_code         = $this->session->userdata('sl_code');
        $position    = $this->session->userdata('position');
        $data = array(
            'length'     => $this->input->post('length'),
            'start'      => $this->input->post('start'),
            'search'     => $this->input->post('search')['value'],
            'sl_code'    => $sl_code,
            'position'   => $position
        );

        $rowAPI = $this->get_api();

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
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);

        $data = json_decode($result);
        return $data;
    }

    // get datatable query
    function get_datatables()
    {
        $rowAPI = $this->get_api();

        // API URL
        $url = $rowAPI->url . 'api/recruitment_approval/getDataTable';

        $query = $this->_get_datatables($url);
        $data = $query->data;
        return $data;
    }

    // count datatable query
    function count_filtered()
    {
        $rowAPI = $this->get_api();

        // API URL
        $url = $rowAPI->url . 'api/recruitment_approval/countDataTable';

        $query = $this->_get_datatables($url);

        $data = $query->data;
        return $data;
    }

    private function get_api()
    {
        $query = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Recruitment'");
        return $query->row();
        $query->free_result();
    }

    private function get_data_api($method = "POST", $url, $body = [])
    {
        $data = array(

            'rec_id'   => $this->input->post('rec_id')
        );

        $data = array_merge($data, $body);

        // echo $data;
        // die;

        $rowAPI = $this->get_api();


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
        if (in_array($method, ['POST'])) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        }

        $result = curl_exec($ch);

        // Close cURL resource
        $data = json_decode($result);

        curl_close($ch);

        return $data;
    }

    function get_by_id($rec_id)
    {
        $rec_id = $rec_id;
        // var_dump($app_id);
        // die;
        $rowApp = $this->get_api();
        $url = $rowApp->url . 'api/recruitment_approval/getDetailby_id/?rec_id=' . $rec_id;

        $query = $this->get_data_api('GET', $url);

        $data = $query->data;
        return $data;
    }

    function get_data_recruitment_documents($rec_id)
    {
        $rec_id = $rec_id;
        $rowApp = $this->get_api();
        $url = $rowApp->url . 'api/recruitment_approval/recruitment_documents/?rec_id=' . $rec_id;

        $query = $this->get_data_api('GET', $url);

        $data = $query->data;
        return $data;
    }

    function insertUpdate($data_update)
    {
        $rowApp = $this->get_api();
        $url    = $rowApp->url . 'api/recruitment_approval/insertUpdate';
        $query  = $this->get_data_api("POST", $url, $data_update);

        $data = $query;
        return $data;
    }

    private function get_data_api_return($method = "POST", $url, $body = [])
    {
        $data = array(

            'rec_id'   => $this->input->post('rec_id')
        );

        $data = array_merge($data, $body);

        // echo $data;
        // die;

        $rowAPI = $this->get_api();


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
        if (in_array($method, ['POST'])) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        }

        $result = curl_exec($ch);

        // Close cURL resource
        $data = json_decode($result);

        curl_close($ch);

        return $data;
    }

    function returnData($data_return)
    {
        $rowApp = $this->get_api();
        $url    = $rowApp->url . 'api/recruitment_approval/return';
        $query  = $this->get_data_api_return("POST", $url, $data_return);

        $data = $query;
        // var_dump($data);
        return $data;
    }

    function smCode($nik)
    {
        $rowAPI = $this->get_api();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        // Create a new cURL resource

        $url = $rowAPI->url . 'api/recruitment/smCode/?nik=' . $nik;
        // echo $url;die();
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
        curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");


        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);

        $data = json_decode($result);
        return $data;
    }
}
