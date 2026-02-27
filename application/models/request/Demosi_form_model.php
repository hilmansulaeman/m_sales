<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Demosi_form_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function get_by($request_id)
    {
        $this->db->where('Request_ID', $request_id);
        return $this->db->get('internal_sms.data_request');
    }

    function get_demosi_detail($request_id, $form_id)
    {
        return $getData = $this->db->query("SELECT a.*, b.*, c.*, d.Status, d.Resign_Date, d.Employee_ID
            FROM internal_sms.data_request_user a 
            INNER JOIN internal_sms.data_request b ON b.Request_ID = a.Request_ID 
            INNER JOIN internal_sms.set_hit_code c ON c.Hit_Code = a.Hit_Code 
            INNER JOIN internal_sms.data_sales_logs d ON d.Request_User_ID = a.Request_User_ID 
            WHERE a.Request_ID = '$request_id' AND b.Form_ID = '$form_id' 
            GROUP BY a.Sales_Name");
    }




    function get_all_sales()
    {
        return $getData = $this->db->query("SELECT t1.*, t2.Employee_ID, t2.DSR_Code FROM db_hrd.data_sales_structure t1 INNER JOIN db_hrd.data_employee t2 ON t2.DSR_Code = t1.DSR_Code WHERE t1.Status = 'ACTIVE' AND t1.Position = 'SPV' ORDER BY t1.Name ASC");
    }

    function get_all_sales_where()
    {
        $sales_code = $this->session->userdata('sl_code');
        return $getData = $this->db->query("SELECT t1.*, t2.Employee_ID, t2.DSR_Code FROM db_hrd.data_sales_structure t1 INNER JOIN db_hrd.data_employee t2 ON t2.DSR_Code = t1.DSR_Code WHERE ASM_Code = '$sales_code' AND t1.Status = 'ACTIVE' AND t1.Position = 'SPV' ORDER BY t1.Name ASC");
    }


    function getDetailSales($id = NULL)
    {

        $this->db->select('t1.*, t2.Employee_ID')
            ->from('db_hrd.data_sales_structure AS t1, db_hrd.data_employee AS t2')
            ->where('t1.DSR_Code = t2.DSR_Code')
            ->where('t1.DSR_Code', $id);
        // $this->db->select("*");
        // $this->db->from("db_hrd.data_employee");
        // $this->db->where(array('DSR_Code' => $id));
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }

    function insert_data_api($data)
    {
        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'HRD'");
        $rowAPI = $getAPI->row();

        $url_api = $rowAPI->url;
        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        // API URL

        $url = $url_api . '/api/request/add_promodemo';

        // Create a new cURL resource
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: $apiKey"));
        curl_setopt($ch, CURLOPT_USERPWD, "$apiUser:$apiPass");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);

        //get ID
        $response = json_decode($result);

        $data = $response->data;

        return $data;
    }
}
