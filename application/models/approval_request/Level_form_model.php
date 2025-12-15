<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Level_form_model extends CI_Model
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
            $approval = "c.Sales_Code = '$username' AND c.Position = 'ASM' AND c.Status = '0' AND b.Hit_Code IN('1008','1005')";
        } elseif ($position == 'RSM') {
            $approval = "c.Sales_Code = '$username' AND c.Position = 'RSM' AND c.Status = '0' AND b.Hit_Code IN('1008','1005')";
        } elseif ($position == 'BSH') {
            $approval = "c.Sales_Code = '$username' AND c.Position = 'BSH' AND c.Status = '0' AND b.Hit_Code IN('1008','1005')";
        } elseif ($position == 'GM') {
            $approval = "c.Sales_Code = '$username' AND c.Position = 'GM' AND c.Status = '0' AND b.Hit_Code IN('1008','1005')";
        }

        //$where = "a.Form_ID = '1' AND ($checker OR $approval)  AND a.Created_Date BETWEEN '$tanggal1' AND '$tanggal2'";
        $where = "a.Form_ID = '8' AND ($checker OR $approval)";

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

    function get_level_detail($request_id)
    {
        $username = $this->session->userdata('username');

        // return $getData = $this->db->query("SELECT a.*, b.*, e.*, d.Status
        //     FROM internal_sms.data_request_user a 
        //     INNER JOIN internal_sms.set_hit_code b ON b.Hit_Code = a.Hit_Code
        //     INNER JOIN internal_sms.data_request_approval d ON d.Request_User_ID = a.Request_User_ID 
        //     INNER JOIN internal_sms.data_request e ON e.Request_ID = a.Request_ID 
        //     WHERE a.Request_ID = '$request_id' AND e.Form_ID = '8' AND d.Sales_Code = '$username' AND d.Status = '0'
        //     GROUP BY a.Sales_Name");


        $this->db->select('a.*, b.*, e.*, d.Status');
        $this->db->from('data_request_user a');
        $this->db->join('set_hit_code b', 'b.Hit_Code = a.Hit_Code', 'inner');
        $this->db->join('data_request_approval d', 'd.Request_User_ID = a.Request_User_ID', 'inner');
        $this->db->join('data_request e', 'e.Request_ID = a.Request_ID', 'inner');
        $this->db->where('a.Request_ID', $request_id);
        $this->db->where('e.Form_ID', '8');
        $this->db->where('d.Sales_Code', $username);
        $this->db->where('d.Status', '0');
        $this->db->group_by('a.Sales_Name');
        return $getData = $this->db->get();
    }

    function insert_data_api($data)
    {
        $getAPI = $this->db->get_where('key_api', array('Description' => 'HRD'));
        $rowAPI = $getAPI->row();

        $url_api = $rowAPI->url;
        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        // API URL
        $url = $url_api . '/api/request/add_level';

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

    function getData_api($id)
    {
        // echo $id.' - '.$category;die();
        $getAPI = $this->db->get_where('key_api', array('Description' => 'HRD'));
        $rowAPI = $getAPI->row();

        $url_api = $rowAPI->url;
        $apiKey = $rowAPI->api_key;

        // API auth credentials
        $apiUser = $rowAPI->Username;
        $apiPass = $rowAPI->Password;

        // API URL
        $url = $url_api . '/api/request/getUpliner?dsr_code='.$id;
        
        // echo $url;die();

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

        $dataDecode = json_decode($result);
        $data = $dataDecode->data;

        return $data;
    }



    // function getDetailSales($id = NULL)
    // {

    //     $this->db->select("*");
    //     $this->db->from("internal.data_sales");
    //     $this->db->where(array('Number_Id' => $id));
    //     $query = $this->db->get();
    //     return $query;
    //     $query->free_result();
    // }


    // =============================================================================================================================
    // =============================================================================================================================
    // =============================================================================================================================
    // API FUNCTION
        private function get_api($product)
        {
            if ($product == 'PEMOL') {
                // $desc = "funding_officer_dev";
                $desc = "Pemol";
            } else {
                // $desc = "rest_api";
                $desc = "CC";
            }

            $query = $this->db->get_where('key_api', array('Description' => $desc));
            return $query->row();
            $query->free_result();
        }

        private function get_full_api($url, $product)
        {
            $rowAPI = $this->get_api($product);

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

        // =============================================================================================================================
        // PEMOL
            function getPerformancePemol($sales_code, $product, $month)
            {
                $rowAPI = $this->get_api($product);

                // API URL
                $url = $rowAPI->url.'sales_monitoring/level/getDataPerformance?sales_code='.$sales_code.'&product='.$product.'&month='.$month;
                
                $query = $this->get_full_api($url, $product);
                
                $data = $query->data;
                return $data;
            }
        // END

        // =============================================================================================================================
        // CC (REG, MS) PL SC
            function getPerformance($sales_code, $product, $position, $month)
            {
                $rowAPI = $this->get_api($product);

                // API URL
                $url = $rowAPI->url.'sales_monitoring/level/getDataPerformance?sales_code='.$sales_code.'&product='.$product.'&position='.$position.'&month='.$month;
                
                $query = $this->get_full_api($url, $product);
                
                $data = $query->data;
                return $data;
            }
        // END

        // =============================================================================================================================
        // MERCHANT
            function getDataInput($sales_code, $product, $type)
            {
                $rowAPI = $this->get_api($product);

                // API URL
                $url = $rowAPI->url.'sales_monitoring/level/getDataInput?sales_code='.$sales_code.'&type='.$type;
                
                $query = $this->get_full_api($url, $product);
                
                $data = $query->data;
                return $data;
            }

            function getTotalsProcessing($sales_code, $product, $type)
            {
                $rowAPI = $this->get_api($product);

                // API URL
                $url = $rowAPI->url.'sales_monitoring/level/getTotalsProcessing?sales_code='.$sales_code.'&type='.$type;
                
                $query = $this->get_full_api($url, $product);
                
                $data = $query->data;
                return $data;
            }

            function getDataProcessing($sales_code, $product, $type)
            {
                $rowAPI = $this->get_api($product);

                // API URL
                $url = $rowAPI->url.'sales_monitoring/level/getDataProcessing?sales_code='.$sales_code.'&type='.$type;
                
                $query = $this->get_full_api($url, $product);
                
                $data = $query->data;
                return $data;
            }
        // END

        function get_employee_id($sales_code, $product)
        {
            $rowAPI = $this->get_api('NOTPEMOL');

            $url = $rowAPI->url.'sales_monitoring/level/getEmployeeID?sales_code='.$sales_code;
            
            $query = $this->get_full_api($url, 'NOTPEMOL');

            $data = $query->data;

            return $data;
        }
    // END
}
