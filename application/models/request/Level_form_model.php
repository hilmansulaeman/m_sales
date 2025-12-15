<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Level_form_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $created_by = $this->session->userdata('created_by');

        $tgl1 = $this->input->post('startDate') ? $this->input->post('startDate') : null;
        $tanggal1 = $tgl1 ? $tgl1 : date('Y-m-01 00:00:01');
        $tgl2 = $this->input->post('endDate') ? $this->input->post('endDate') : null;
        $tanggal2 = $tgl2 ? "$tgl2 23:59:59" : date('Y-m-d 23:59:59');

        $column_order = array(null, 'a.Efective_Date','a.Category'); //field yang ada di table recruitment
        $column_search = array('a.Efective_Date','a.Category'); //field yang diizin untuk pencarian 
        $order = array('a.Request_ID' => 'DESC'); // default order
        $this->db->select('a.*,b.Category_ID');
        $this->db->from('internal_sms.data_request a');
        $this->db->join('internal_sms.ref_category_structure b', 'b.Category = a.Category', 'LEFT');

        $where = "a.Created_By = '$created_by' AND a.Form_ID = '8' AND a.Created_Date BETWEEN '$tanggal1' AND '$tanggal2'";

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

    function get_level_detail($request_id, $form_id)
    {
        // return $getData = $this->db->query("SELECT a.*, b.*, c.*
        //     FROM internal_sms.data_request_user a 
        //     INNER JOIN internal_sms.data_request b ON b.Request_ID = a.Request_ID 
        //     INNER JOIN internal_sms.set_hit_code c ON c.Hit_Code = a.Hit_Code 
        //     WHERE a.Request_ID = '$request_id' AND b.Form_ID = '$form_id' 
        //     GROUP BY a.Sales_Name");

        $this->db->select('a.*, b.*, c.*');
        $this->db->from('data_request_user a');
        $this->db->join('data_request b', 'b.Request_ID = a.Request_ID', 'inner');
        $this->db->join('set_hit_code c', 'c.Hit_Code = a.Hit_Code', 'inner');
        $this->db->where('a.Request_ID', $request_id);
        $this->db->where('b.Form_ID', $form_id);
        $this->db->group_by('a.Sales_Name');
        return $getData = $this->db->get();
    }

    // function get_level_detail($request_id, $form_id)
    // {
    //     return $getData = $this->db->query("SELECT a.*, b.*, c.*, d.Employee_ID
    //         FROM internal_sms.data_request_user a 
    //         INNER JOIN internal_sms.data_request b ON b.Request_ID = a.Request_ID 
    //         INNER JOIN internal_sms.set_hit_code c ON c.Hit_Code = a.Hit_Code 
    //         INNER JOIN db_hrd.data_employee d ON d.DSR_Code = a.Sales_Code 
    //         WHERE a.Request_ID = '$request_id' AND b.Form_ID = '$form_id' 
    //         GROUP BY a.Sales_Name");
    // }



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
        // var_dump($response);
        // die;
        $data = $response->data;


        return $data;
    }

    function getData_api($id, $param, $category)
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
        if($category == 'add') {
            $url = $url_api . '/api/request/dataSalesLevel?sales_code='.$id.'&category='.$param;
        }
        elseif($category == 'get_structure') {
            $url = $url_api . '/api/request/structureByID?sales_code='.$id;
        }
        elseif($category == 'get_employee') {
            $url = $url_api . '/api/request/ByID?employee_id='.$id;
        }
        elseif($category == 'getDetailSales') {
            $url = $url_api . '/api/request/bySalesCode?sales_code='.$id;
        }

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

    // function get_all_sales()
    // {
    //     return $getData = $this->db->query("SELECT * FROM db_hrd.data_sales_structure WHERE Status = 'ACTIVE' ORDER BY Name ASC");
    // }

    // function get_all_sales_where()
    // {
    //     $sales_code = $this->session->userdata('sl_code');
    //     $getData = $this->db->query("SELECT a.*, b.Employee_ID FROM db_hrd.data_sales_structure a
    //     INNER JOIN db_hrd.data_employee b
    //     ON b.DSR_Code = a.DSR_Code
    //     WHERE a.ASM_Code = '$sales_code' AND a.Position != 'SPV' AND b.Status = 'ACTIVE' 
    //     ORDER BY a.Name ASC");
    //     return $getData;
    //     $getData->free_result();
    // }

    // function get_all_spv_where()
    // {
    //     $sales_code = $this->session->userdata('sl_code');
    //     $getData = $this->db->query("SELECT a.*, b.Employee_ID FROM db_hrd.data_sales_structure a
    //     INNER JOIN db_hrd.data_employee b
    //     ON b.DSR_Code = a.DSR_Code
    //     WHERE a.ASM_Code = '$sales_code' AND a.Position = 'SPV' AND b.Status = 'ACTIVE' 
    //     ORDER BY a.Name ASC");
    //     return $getData;
    //     $getData->free_result();
    // }

    // function get_all_where_cc() {
    //     $sales_code = $this->session->userdata('sl_code');
    //     $getData = $this->db->query("SELECT a.*, b.Employee_ID FROM db_hrd.data_sales_structure a
    //     INNER JOIN db_hrd.data_employee b
    //     ON b.DSR_Code = a.DSR_Code
    //     WHERE a.ASM_Code = '$sales_code' AND b.Status = 'ACTIVE' AND b.Product = 'CC'
    //     ORDER BY a.Name ASC");
    //     return $getData;
    //     $getData->free_result();
    // }


    function getDetailSales($id = NULL)
    {

        $this->db->select("*");
        $this->db->from("db_hrd.data_employee");
        $this->db->where(array('Employee_Id' => $id));
        $query = $this->db->get();
        return $query;
        $query->free_result();
    }
}
