<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Recruitment_return_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    //get datatable query
    private function _get_datatables_query($url)
    {
        $sl_code = $this->session->userdata('sl_code');
        $position = $this->session->userdata('position');
        $data = array(
            'length'     => $this->input->post('length'),
            'start'      => $this->input->post('start'),
            'search'     => $this->input->post('search')['value'],
            'sl_code'    => $sl_code,
            'position'   => $position
        );

        $rowAPI = $this->get_api();
        $apiKey = $rowAPI->api_key;

        //API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        //creater a new cURL resource
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

    private function get_api()
    {
        $this->db->select('*');
        $this->db->from('key_api');
        $this->db->where('Description', 'Recruitment');
        $query = $this->db->get()->row();
        return $query;
    }

    function get_datatables()
    {
        $rowAPI = $this->get_api();

        //API URL
        $url = $rowAPI->url . 'api/recruitment_return/getDataTable';

        $query = $this->_get_datatables_query($url);
        $data = $query->data;
        return $data;
    }

    function count_filtered()
    {
        $rowAPI = $this->get_api();

        //API URL
        $url    = $rowAPI->url . 'api/recruitment_return/countDataTable';
        $query  = $this->_get_datatables_query($url);
        $data   = $query->data;
        return $data;
    }

    private function get_data_api($method = "POST", $url, $body = [])
    {
        $data = array(
            'rec_id' => $this->input->post('rec_id')
        );

        $data = array_merge($data, $body);

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

        $rowApp = $this->get_api();
        $url = $rowApp->url . 'api/recruitment_return/getDetailby_id/?rec_id=' . $rec_id;

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

    function returnData($data_return)
    {
        $rowApp = $this->get_api();
        $url    = $rowApp->url . 'api/recruitment_return/return';
        $query  = $this->get_data_api("POST", $url, $data_return);

        $data = $query;
        // var_dump($data);
        return $data;
    }
}
