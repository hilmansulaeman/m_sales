<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Deviasi_form_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $username = $this->session->userdata('username');
        $position = $this->session->userdata('position');


        $tgl1 = $this->input->post('startDate') ? $this->input->post('startDate') : null;
        $tanggal1 = $tgl1 ? $tgl1 : date('Y-m-01 00:00:01');
        $tgl2 = $this->input->post('endDate') ? $this->input->post('endDate') : null;
        $tanggal2 = $tgl2 ? "$tgl2 23:59:59" : date('Y-m-d 23:59:59');

        $column_order = array(null, 'a.Efective_Date'); //field yang ada di table recruitment
        $column_search = array('a.Efective_Date'); //field yang diizin untuk pencarian 
        $order = array('b.Hit_Code' => 'ASC'); // default order
        $this->db->select('a.*');
        $this->db->from('internal_sms.data_request a');
        $this->db->join('internal_sms.data_request_user b', 'b.Request_ID = a.Request_ID', 'INNER');
        $this->db->join('internal_sms.data_request_approval c', 'c.Request_User_ID = b.Request_User_ID', 'INNER');

        $checker = "b.Checker = '$username' AND b.Hit_Code IN('1001','1003') AND Checker_Status = '0'";

        if ($position == 'SPV') {
            $approval = "c.Sales_Code = '$username' AND c.Position = 'SPV' AND c.Status = '0' AND b.Hit_Code = '1001'";
        } elseif ($position == 'ASM') {
            $approval = "c.Sales_Code = '$username' AND c.Position = 'ASM' AND c.Status = '0' AND b.Hit_Code IN('1009','1008','1005')";
        } elseif ($position == 'RSM') {
            $approval = "c.Sales_Code = '$username' AND c.Position = 'RSM' AND c.Status = '0' AND b.Hit_Code IN('1009','1008','1005')";
        } elseif ($position == 'BSH') {
            $approval = "c.Sales_Code = '$username' AND c.Position = 'BSH' AND c.Status = '0' AND b.Hit_Code IN('1009','1008','1005')";
        } elseif ($position == 'GM') {
            $approval = "c.Sales_Code = '$username' AND c.Position = 'GM' AND c.Status = '0' AND b.Hit_Code IN('1009','1008','1005')";
        }

        //$where = "a.Form_ID = '1' AND ($checker OR $approval)  AND a.Created_Date BETWEEN '$tanggal1' AND '$tanggal2'";
        $where = "a.Form_ID = '6' AND ($checker OR $approval)";

        $this->db->where($where);

        $this->db->group_by('a.Request_ID');

        $i = 0;
        foreach ($column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                if ($i === 0) { // looping awal
                    //$this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i) {
                    //$this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            $this->db->order_by(key($order), $order[key($order)]);
        }
        //$this->db->group_by($this->primary_key);
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
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
        $this->db->from('internal_sms.data_request');
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }

    function get_by($request_id)
    {
        $this->db->where('Request_ID', $request_id);
        return $this->db->get('internal_sms.data_request');
    }

    function get_deviasi_detail($request_id)
    {
        $username = $this->session->userdata('username');
        // var_dump($request_id);
        // die;
        return $getData = $this->db->query("SELECT a.*, b.*, c.Employee_ID, e.*, d.Status
            FROM internal_sms.data_request_user a 
            INNER JOIN internal_sms.set_hit_code b ON b.Hit_Code = a.Hit_Code 
            INNER JOIN db_hrd.data_employee c ON c.DSR_Code = a.Sales_code 
            INNER JOIN internal_sms.data_request_approval d ON d.Request_User_ID = a.Request_User_ID 
            INNER JOIN internal_sms.data_request e ON e.Request_ID = a.Request_ID 
            WHERE a.Request_ID = '$request_id' AND e.Form_ID = '6' AND d.Sales_Code = '$username' AND d.Status = '0'
            GROUP BY a.Sales_Name");
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
        $url = $url_api . '/api/request/add_deviasilog';

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

    function insert_data_api_update($data)
    {
        $getAPI = $this->db->query("SELECT * FROM `key_api` WHERE `Description` = 'HRD'");
        $rowAPI = $getAPI->row();

        $url_api = $rowAPI->url;
        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        // API URL
        $url = $url_api . '/api/request/add_deviasiupdate';

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




    function get_all_sales()
    {
        return $getData = $this->db->query("SELECT * FROM internal.data_sales WHERE Status = 'ACTIVE' ORDER BY Name ASC");
    }

    function get_all_sales_where()
    {
        $sales_code = $this->session->userdata('sl_code');
        return $getData = $this->db->query("SELECT * FROM internal.data_sales WHERE SM_Code = '$sales_code' AND Status = 'ACTIVE' ORDER BY Name ASC");
    }


    function getDetailSales($id = NULL)
    {

        $this->db->select("*");
        $this->db->from("internal.data_sales");
        $this->db->where(array('Number_Id' => $id));
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }
}
