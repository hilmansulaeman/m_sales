<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class exit_form extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template'));
        $this->load->model('request/exit_form_model');
    }

    function index()
    {
        $created_by = $this->session->userdata('sl_code');
        $this->session->set_userdata('created_by', $created_by);

        //load view
        $this->template->set('title', 'Data Form Level');
        $this->template->load('template', 'request/exit_form/index');
    }

    function get_data_exit()
    {
        $query = $this->exit_form_model->get_datatables();
        $count_data = $this->exit_form_model->count_all()->row();
        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row) {
            $request_id = $row->Request_ID;
            $action = '<a href="' . site_url() . "request/exit_form/detail/" . $request_id . "/" . $row->Category_ID . '" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-list" title="Detail Data"></i></span></a>';
            $created_by = $this->session->userdata('realname');

            // $cek1 = $this->db->query("SELECT  
            //                         COUNT(Request_ID) AS total,
            //                         SUM(IF(Hit_Code IN('1006','1007'),1,0)) AS approved,
            //                         SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected,
            //                         SUM(IF(Hit_Code = '1010',1,0)) AS cancel
            //                         FROM internal_sms.data_request_user
            //                         WHERE Request_ID = '$request_id'
            //                         ");
            // $cek = $cek1->row();

            $this->db->select("
                COUNT(Request_ID) AS total,
                SUM(IF(Hit_Code IN('1005','1006','1007'),1,0)) AS approved,
                SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected,
                SUM(IF(Hit_Code = '1010',1,0)) AS cancel
            ");
            $this->db->from("internal_sms.data_request_user");
            $this->db->where("Request_ID", $request_id);
            $cek1 = $this->db->get();

            $cek = $cek1->row();

            $data[] = array(
                ++$no,
                $row->Efective_Date,
                $row->Created_Date,
                $created_by,
                '<span class="btn btn-xs btn-info">' . $cek->total . '</span>',
                '<span class="btn btn-xs btn-success">' . $cek->approved . '</span>',
                '<span class="btn btn-xs btn-warning">' . $cek->cancel . '</span>',
                $action
            );
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count_data->total,
            "recordsFiltered" => $this->exit_form_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function add()
    {
        $sales_code = $this->session->userdata('sl_code');
        $position_ = $this->session->userdata('position');
        $position = $this->session->set_userdata('position', $position_);

        $qry = $this->db->get_where("ref_category_structure", array("Category" => "EXIT"))->row();

        $data['categoryid'] = $qry->Category_ID;
        $data['category'] = $qry->Category;

        $getData = $this->db->get_where("data_request", array("Category" => "EXIT", "Created_By" => $sales_code));
        
        // if($getData->num_rows() >= 1) {
        //     $row = $getData->row();
        //     redirect('request/exit_form/detail/' . $row->Request_ID . '/' . $qry->Category_ID);

        // }

        $list = array('ASM', 'RSM', 'BSH');

        if (in_array($position_, $list)) {
            $data['sales'] = $this->exit_form_model->getData_api($sales_code, 'add');
        } else {
            $data['sales'] = $this->exit_form_model->getData_api('', 'add');
        }

        // echo '<pre>';
        //     print_r($data['sales']);
        // echo '</pre>';
        // die();

        $data['exit_reason'] = $this->db->get("ref_exit_reason");

        //load view
        $this->template->set('title', 'Add Form Exit');
        $this->template->load('template', 'request/exit_form/add', $data);
    }

    function save_request_exit($uri3, $uri4)
    { //uri3 = add/detail, uri4 = id_request 

        //GET Request_ID dari table ref_form
        $slug_form = $this->uri->segment(2); //slug form

        $row_form = $this->db->get_where("internal_sms.ref_form", array("Slug" => "$slug_form"))->row();
        $form_id = $row_form->Form_ID;

        $efective_date = $this->input->post('efective_date');
        $explode = explode('|', $this->input->post('data_employee_id'));
        // $employee_id = $this->input->post('data_employee_id');
        // $get_data = $this->db->get_where('db_hrd.data_employee', array('Employee_ID' => $employee_id))->row();
        // $get_data = $this->exit_form_model->getData_api($employee_id, 'send');

        // echo '<pre>';
        //     print_r($explode);die();
        // echo '</pre>';
        $employee_id = $explode[0];
        $regnoID    = $explode[1];
        $sales_code = $explode[2];
        $sales_name = $explode[3];
        $position   = $explode[4];
        $level      = $explode[5];
        $product    = $explode[6];

        $exit_reason    = $this->input->post('exit_reason');
        $reason         = $this->input->post('reason');

        $category_id = $this->input->post('category_id');
        $category = $this->input->post('category');

        if ($uri3 == 'add') {
            //INSERT TABLE data_request jika uri->segment(3) nya add
            $request = array(
                'Form_ID'       => $form_id,
                'Efective_Date' => $efective_date,
                'Category'      => strtoupper($category),
                'Created_By'    => $this->session->userdata('sl_code'),
                'Created_Name'  => $this->session->userdata('realname')
            );
            $this->db->insert('internal_sms.data_request', $request);
            $request_id = $this->db->insert_id();
        } else {
            $request_id = $uri4;
        }

        // $query_cek = $this->db->query("SELECT a.* FROM internal_sms.data_request_user a INNER JOIN internal_sms.data_request b ON b.Request_ID = a.Request_ID WHERE a.Sales_Code = '$sales_code' AND b.Category = '$category' AND b.Form_ID = '$form_id' AND a.Hit_Code NOT IN('1002','1004','1007','1010') ");
        // $cek = $query_cek->num_rows();

        $arr = array('1002','1004','1007','1010');

        $this->db->select('a.*');
        $this->db->from('data_request_user a');
        $this->db->join('data_request b', 'b.Request_ID = a.Request_ID', 'inner');
        $this->db->where('a.Sales_Code', $sales_code);
        $this->db->where('b.Category', $category);
        $this->db->where('b.Form_ID', $form_id);
        $this->db->where_not_in('a.Hit_Code', $arr);
        $cek = $this->db->get()->num_rows();

        if ($cek > 0) {
            //MESSAGE
            $this->session->set_flashdata('message', "<span class='btn btn-danger'><b>Data sedang dalam proses!</b></span>");
            // $this->db->where('Request_ID', $request_id);
            // $this->db->delete('data_request');

            redirect('request/exit_form/detail/' . $request_id . '/' . $category_id);
        } else {

            $note = $exit_reason.' - '.$reason.' - '.$efective_date;

            $request_user = array(
                'Regno_ID'                  => $regnoID,
                'Sales_Code'                => $sales_code,
                'Sales_Name'                => $sales_name,
                'Position'                  => $position,
                'Level'                     => $level,
                'Product'                   => $product,
                'Status_Date'               => date('Y-m-d'),
                'Exit_Status'               => 'RESIGN',
                'Exit_Reason'               => $exit_reason,
                'Reason'                    => $reason,
                'Note'                      => $note,
                'Hit_Code'                  => '1000',
                'Request_ID'                => $request_id
            );
            $this->db->insert('internal_sms.data_request_user', $request_user);

            //MESSAGE
            $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil ditambahkan!</b></span>");

            redirect('request/exit_form/detail/' . $request_id . '/' . $category_id);
        }
    }

    function detail($request_id, $category_id)
    {
        $sales_code = $this->session->userdata('sl_code');

        $data['category_id'] = $category_id;
        $getCategory = $this->db->get_where("ref_category_structure", array("Category_ID" => "$category_id"))->row();
        $data['category'] = $getCategory->Category;

        $slug_form = $this->uri->segment(2);
        $row = $this->db->get_where("ref_form", array("Slug" => "$slug_form"))->row();
        $form_id = $row->Form_ID;

        $position = $this->session->userdata('position');

        $list = array('ASM', 'RSM', 'BSH');

        if (in_array($position, $list)) {
            $data['sales'] = $this->exit_form_model->getData_api($sales_code, 'add');
        } else {
            $data['sales'] = $this->exit_form_model->getData_api('', 'add');
        }

        // echo '<pre>';
        //     print_r($data['sales']);die();
        // echo '</pre>';

        //CEK DETAIL
        $data['cekDetail'] = $this->db->get_where("internal_sms.data_request_user", array("Request_ID" => "$request_id", "Hit_Code >" => "1000"))->num_rows();

        $data['exit_reason'] = $this->db->get("ref_exit_reason");

        $data['request_user'] = $this->exit_form_model->get_exit_detail($request_id, $form_id);
        $data['db'] = $this->exit_form_model->get_by($request_id)->row();

        //load view
        $this->template->set('title', 'Data Request Detail');
        $this->template->load('template', 'request/exit_form/detail', $data);
    }

    function delete_detail($request_id, $request_user_id, $category_id)
    {

        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_user');

        // $this->db->where('Request_User_ID', $request_user_id);
        // $this->db->delete('internal_sms.data_request_approval');

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dihapus!</b></span>");

        redirect('request/exit_form/detail/' . $request_id . '/' . $category_id);
    }

    function send($request_id)
    {
        $querykategori = $this->db->get_where("internal_sms.data_request", array("Request_ID" => "$request_id"))->row();

        $update_request = array(
            'Is_Send'  => '1'
        );
        $this->db->where('Request_ID', $request_id);
        $this->db->update('internal_sms.data_request', $update_request);

        $data_update = array(
            'Hit_Code'  => '1009'
        );
        $this->db->where('Request_ID', $request_id);
        $this->db->update('internal_sms.data_request_user', $data_update);


        $request_user_id = $this->input->post('request_user_id');

        // $post = $this->input->post();
        $data = array();
        foreach ($request_user_id as $item => $val) {
            $dsr_code = $this->input->post('sales_code')[$item];

            $getEmployeeID = $this->exit_form_model->getData_api($dsr_code, 'employeeDSR');

            $employee_id = $getEmployeeID->Employee_ID;
            
            $cek_resign_date1 = $getEmployeeID->Resign_Date1;
            $cek_resign_date2 = $getEmployeeID->Resign_Date2;

            //CEK UDAH PERNAH RESIGN ?
            if($cek_resign_date1 == NULL || $cek_resign_date1 == '' || $cek_resign_date1 == '0000-00-00') {
                $resign_date1 = $this->input->post('resign_date')[$item];
                $resign_date2 = $cek_resign_date2;
            }
            else {
                $resign_date1 = $cek_resign_date1;
                $resign_date2 = $this->input->post('resign_date')[$item];
            }

            $note = $this->input->post('exit_reason')[$item].' - '.$this->input->post('reason')[$item].' - '.$this->input->post('resign_date')[$item];
            $data[] = array(
                //INI BUAT API
                'request_user_id'   => $this->input->post('request_user_id')[$item],
                'regnoID'           => $this->input->post('regnoID')[$item],
                'employee_id'       => $employee_id,
                'dsr_code'          => $this->input->post('sales_code')[$item],
                'name'              => $this->input->post('name')[$item],
                'reason'            => $this->input->post('reason')[$item],
                'status'            => 'RESIGN',
                'note'              => $note,
                'resign_reason'     => $this->input->post('exit_reason')[$item],
                'resign_date1'       => $resign_date1,
                'resign_date2'      => $resign_date2,
                'category'          => strtoupper(($querykategori->Category)),
                'created_by'        => $this->session->userdata('realname')
            );
        }
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';die();
        $response = $this->exit_form_model->insert_data_api($data);

        $data_log = array();
        foreach ($request_user_id as $form => $val) {
            $data_log[] = array(
                'Request_ID'            => $request_id,
                'Request_User_ID'       => $_POST['request_user_id'][$form],
                'Hit_Code'              => '1009',
                'Description'           => 'New Request From ASM',
                'Updated_By'            => $this->session->userdata('realname'),
                'Updated_Date'          => date('Y-m-d h:i:s')
            );
        }
        $this->db->insert_batch('internal_sms.data_process_log', $data_log);

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dikirim!</b></span>");

        redirect('request/exit_form');
    }




    function getDate($val, $form_id) {
        $sales_code = $this->session->userdata('sl_code');
        $this->db->select('a.*, b.Category_ID');
        $this->db->from('data_request a');
        $this->db->join('ref_category_structure b', 'b.Category = a.Category', 'left');
        $this->db->where('Created_By', $sales_code)->where('a.Efective_Date', $val)->where('a.Category', 'LEVEL')->where('a.Form_ID', $form_id)->where('Is_Send', '0');;
        $query = $this->db->get();
        $row = $query->row();

        if ($query->num_rows() > 0) {
            echo json_encode(array('status' => true, 'request_id'=> $row->Request_ID, 'category_id' => $row->Category_ID));
        } else {
            echo json_encode(array('status' => false));
        }

    }

    // public function getDetailSales($id = NULL)
    // {
    //     if (!empty($id)) {
    //         // $query = $this->exit_form_model->getDetailSales($id);
    //         $query = $this->exit_form_model->getData_api($id, 'send');
    //         if ($query->num_rows() > 0) {
    //             $data[] = $query();
    //             echo json_encode($data);
    //         }
    //     }
    // }
}
