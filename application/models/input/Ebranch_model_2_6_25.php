<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ebranch_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // get datatable query
    public function _get_data_query()
    {
        $sales_code = $this->session->userdata('username');
        $uri4 = $this->session->userdata('uri4');

        $data = array(
            'length' => $this->input->post('length'),
            'start' => $this->input->post('start'),
            'search' => $this->input->post('search')['value'],
            'sales_code' => $sales_code,
            'uri4' => $uri4,
        );

        $getAPI = $this->db->get_where('key_api', array('Description' => 'CC'));
        $rowAPI = $getAPI->row();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        // API URL
        $url = $rowAPI->url . 'api/api_merchant_edc/getDataTable';

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
    public function _count_data()
    {
        $sales_code = $this->session->userdata('username');
        $uri4 = $this->session->userdata('uri4');

        $data = array(
            'length' => $this->input->post('length'),
            'start' => $this->input->post('start'),
            'search' => $this->input->post('search')['value'],
            'sales_code' => $sales_code,
            'uri4' => $uri4,
        );

        $getAPI = $this->db->get_where('key_api', array('Description' => 'CC'));
        $rowAPI = $getAPI->row();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        // API URL
        $url = $rowAPI->url . 'api/api_merchant_edc/countDataTable';

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
    public function insert($data)
    {
        $getAPI = $this->db->get_where('key_api', array('Description' => 'CC'));
        $rowAPI = $getAPI->row();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        $url = $rowAPI->url . 'cc/ebranch/add';

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

        //get ID
        $response = json_decode($result);
        $status = $response->status;
        if ($status == true) {
            $id = $response->RegnoId;
        } else {
            $id = '0';
        }
        $this->session->set_userdata('RegnoId', $id);
    }

    // insert data
    public function insert_new($data)
    {
        $getAPI = $this->db->get_where('key_api', array('Description' => 'CC'));
        $rowAPI = $getAPI->row();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        $url = $rowAPI->url . 'cc/ebranch/add_new';

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

        $response = json_decode($result);
        return $response;
    }

    // update data
    public function update_data_api($id)
    {
        $data = array(
            'Product_Type' => $this->input->post('product_type'),
            'Email' => $this->input->post('email'),
            'MID_Type' => $this->input->post('mid_type'),
            'Owner_Name' => $this->input->post('owner_name'),
            'Merchant_Name' => $this->input->post('merchant_name'),
            'Account_Number' => $this->input->post('account_number'),
            'Mobile_Phone_Number' => $this->input->post('mobile_phone_number'),
            'Other_Phone_Number' => $this->input->post('other_phone_number'),
            'RegnoId' => $id,
        );

        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
        $rowAPI = $getAPI->row();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        $url = $rowAPI->url . 'api/api_merchant_edc/edit';

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

    // validasi cek nomor referensi
    public function cek_noref($noref)
    {
        $getAPI = $this->db->get_where('key_api', array('Description' => 'CC'));
        $rowAPI = $getAPI->row();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        $url = $rowAPI->url . 'cc/ebranch/cek_noref?noref=' . $noref;

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

    // validasi cek nomor referensi
    public function cek_noref_new($noref)
    {
        $getAPI = $this->db->get_where('key_api', array('Description' => 'CC'));
        $rowAPI = $getAPI->row();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        $url = $rowAPI->url . 'cc/ebranch/cek_noref_new?noref=' . $noref;

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

    // get source code
    public function get_source_code($id)
    {
        $getAPI = $this->db->get_where('key_api', array('Description' => 'CC'));
        $rowAPI = $getAPI->row();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        $url = $rowAPI->url . 'cc/ebranch/cek_sourcecode?id=' . $id;

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

        $response = json_decode($result);
        $status = $response->status;
        if ($status == true) {
            $data = $response->data;
        } else {
            $data = '';
        }

        return $data;
    }

    // get by id
    public function getById($id)
    {
        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'Merchant'");
        $rowAPI = $getAPI->row();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        $url = $rowAPI->url . 'api/api_merchant_edc/getById?id=' . $id;

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

    // get kode referensi
    public function get_kode_referensi()
    {
        $query = $this->db->get_where('ref_kode_referensi', array('is_active' => '1'));
        return $query;
        $query->free_result();
    }

    // get event
    public function get_event()
    {
        $rowAPI = $this->get_api();

        // API URL
        $url = $rowAPI->url . 'cc/ebranch/getEvent';

        $query = $this->get_data($url);

        $data = $query->data;
        return $data;
    }

    public function get_project($idx)
    {
        $rowAPI = $this->get_api();

        // API URL
        $url = $rowAPI->url . 'cc/ebranch/get_project?idx=' . $idx;

        $query = $this->get_data($url);

        $data = $query->data;
        return $data;
    }

    // ================================= INTERNAL FUNCTION =============================== //

    public function get_api()
    {
        $query = $this->db->get_where('key_api', array('Description' => 'CC'));
        return $query->row();
        $query->free_result();
    }

    private function get_data($url)
    {
        $rowAPI = $this->get_api();

        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        // Create a new cURL resource
        $ch = curl_init($url);

        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
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

/* End of file Ebranch_model.php */
/* Location: ./application/models/Ebranch_model.php */
