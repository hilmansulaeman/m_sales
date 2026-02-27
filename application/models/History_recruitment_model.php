<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class History_recruitment_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables($url)
    {
        $sl_code     = $this->session->userdata('sl_code');
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
        $url = $rowAPI->url . 'api/recruitment_history/getDataTable';

        $query = $this->_get_datatables($url);
        $data = $query->data;
        return $data;
    }


    // count datatable query
    function count_filtered()
    {
        $rowAPI = $this->get_api();

        // API URL
        $url = $rowAPI->url . 'api/recruitment_history/countDataTable';

        $query = $this->_get_datatables($url);

        $data = $query->data;
        return $data;
    }

    // get datatable query
    function get_export()
    {
        $rowAPI = $this->get_api();

        // API URL
        $url = $rowAPI->url . 'api/recruitment_history/dataExport';

        $query = $this->_get_dataexport($url);
        $data = $query->data;
        return $data;
    }

    private function _get_dataexport($url)
    {
        $sl_code         = $this->session->userdata('sl_code');
        $position    = $this->session->userdata('position');
        $data = array(
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

    private function get_api()
    {
        $query = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Recruitment'");
        return $query->row();
        $query->free_result();
    }
}
