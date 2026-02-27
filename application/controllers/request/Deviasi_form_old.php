<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Deviasi_form extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template'));
        $this->load->model('request/deviasi_form_model');
    }

    function index()
    {
        $created_by = $this->session->userdata('sl_code');
        $this->session->set_userdata('created_by', $created_by);

        //load view
        $this->template->set('title', 'Data Form Deviasi');
        $this->template->load('template', 'request/deviasi_form/index');
    }

    function get_data_deviasi()
    {
        $query = $this->deviasi_form_model->get_datatables();
        $count_data = $this->deviasi_form_model->count_all()->row();
        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row) {
            $request_id = $row->Request_ID;
            $action = '<a href="' . site_url() . "request/deviasi_form/detail/" . $request_id . "/" . $row->Category_ID . '" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-list" title="Detail Data"></i></span></a>';
            $created_by = $this->session->userdata('realname');

            $cek1 = $this->db->query("SELECT  
                                    COUNT(Request_ID) AS total,
                                    SUM(IF(Hit_Code IN('1005','1006','1007'),1,0)) AS approved,
                                    SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected,
                                    SUM(IF(Hit_Code = '1010',1,0)) AS cancel
                                    FROM internal_sms.data_request_user
                                    WHERE Request_ID = '$request_id'
                                    ");
            $cek = $cek1->row();

            $data[] = array(
                ++$no,
                $row->Efective_Date,
                $row->Created_Date,
                $created_by,
                '<span class="btn btn-xs btn-info">' . $cek->total . '</span>',
                '<span class="btn btn-xs btn-success">' . $cek->approved . '</span>',
                '<span class="btn btn-xs btn-danger">' . $cek->rejected . '</span>',
                '<span class="btn btn-xs btn-warning">' . $cek->cancel . '</span>',
                $action
            );
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count_data->total,
            "recordsFiltered" => $this->deviasi_form_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function add()
    {

        $position_ = $this->session->userdata('position');
        $position = $this->session->set_userdata('position', $position_);

        $qry = $this->db->query("SELECT * FROM ref_category_structure WHERE Category = 'DEVIASI'")->row();

        $data['categoryid'] = $qry->Category_ID;
        $data['category'] = $qry->Category;

        $list = array('ASM', 'RSM', 'BSH');

        $today = date('Y-m-d');
        $periode = date('Y-m-d', strtotime('-1 month', strtotime($today))); //today dikurangin 1 bulan

        if (in_array($position_, $list)) {
            $data['sales'] = $this->deviasi_form_model->get_all_sales_where($periode);
        } else {
            $data['sales'] = $this->deviasi_form_model->get_all_sales();
        }

        //load view
        $this->template->set('title', 'Add Form Deviasi');
        $this->template->load('template', 'request/deviasi_form/add', $data);
    }

    function save_request_deviasi($uri3, $uri4)
    { //uri3 = add/detail, uri4 = id_request 

        //GET Request_ID dari table ref_form
        $slug_form = $this->uri->segment(2); //slug form

        $query_form = $this->db->query("SELECT * FROM internal_sms.ref_form WHERE Slug = '$slug_form'");
        $row_form = $query_form->row();
        // var_dump($row_form);
        // die;
        $form_id = $row_form->Form_ID;

        $efective_date = $this->input->post('efective_date');
        $employee_id = $this->input->post('employee_id');
        $sales_code = $this->input->post('sales_code');
        $sales_name = $this->input->post('sales_name');
        $position   = $this->input->post('position');
        $level      = $this->input->post('level');
        $reason     = $this->input->post('reason');

        $category_id = $this->input->post('category_id');
        $category = $this->input->post('category');

        if ($uri3 == 'add') {
            //INSERT TABLE data_request jika uri->segment(3) nya add
            $request = array(
                'Form_ID'       => $form_id,
                'Efective_Date' => $efective_date,
                'Category'      => strtoupper($category),
                'Created_By'    => $this->session->userdata('username')
            );
            $this->db->insert('internal_sms.data_request', $request);
            $request_id = $this->db->insert_id();
        } else {
            $request_id = $uri4;
        }

        $query_cek = $this->db->query("SELECT a.* FROM internal_sms.data_request_user a INNER JOIN internal_sms.data_request b ON b.Request_ID = a.Request_ID WHERE a.Sales_Code = '$sales_code' AND b.Category = '$category' AND b.Form_ID = '$form_id' AND a.Hit_Code NOT IN('1002','1004','1007','1010') ");
        $cek = $query_cek->num_rows();

        if ($cek > 0) {
            //MESSAGE
            $this->session->set_flashdata('message', "<span class='btn btn-danger'><b>Data sedang dalam proses!</b></span>");
            // $this->db->where('Request_ID', $request_id);
            // $this->db->delete('data_request');

            redirect('request/deviasi_form/detail/' . $request_id . '/' . $category_id);
        } else {
            //get RSM Code
            $querycek = $this->db->query("SELECT * FROM db_hrd.data_sales_structure WHERE DSR_Code = '$sales_code'");
            $rowcek = $querycek->row();
            $approval_code = $rowcek->RSM_Code;
            $approval_name = $rowcek->RSM_Name;
            $approval_position = 'RSM';
            // var_dump($approval_code);
            // die;
            //get BSH Code

            if ($approval_code == '0') {
                $approval_code = $rowcek->BSH_Code;
                $approval_name = $rowcek->BSH_Name;
                $approval_position = 'BSH';
            }
            $request_user = array(
                //'Request_User_ID'              => ini auto increment
                'Sales_Code'                => $sales_code,
                'Sales_Name'                => $sales_name,
                'Position'                  => $position,
                'Level'                     => $level,
                'Approval'                  => $approval_code,
                'Approval_Name'             => $approval_name,
                'Approval_Status'           => '0',
                'Status_Date'               => date('Y-m-d'),
                'Reason'                    => $reason,
                'Hit_Code'                  => '1000',
                'Request_ID'                => $request_id
            );
            $this->db->insert('internal_sms.data_request_user', $request_user);

            //MESSAGE
            $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil ditambahkan!</b></span>");

            redirect('request/deviasi_form/detail/' . $request_id . '/' . $category_id);
        }
    }

    function detail($request_id, $category_id)
    {
        $data['category_id'] = $category_id;
        $getCategory = $this->db->query("SELECT * FROM ref_category_structure WHERE Category_ID='$category_id'")->row();
        $data['category'] = $getCategory->Category;

        $slug_form = $this->uri->segment(2);
        $query = $this->db->query("SELECT * FROM ref_form WHERE Slug = '$slug_form'");
        $row = $query->row();
        $form_id = $row->Form_ID;

        $position = $this->session->userdata('position');


        $list = array('ASM', 'RSM', 'BSH');

        $today = date('Y-m-d');
        $periode = date('Y-m-d', strtotime('-1 month', strtotime($today))); //today dikurangin 1 bulan

        if (in_array($position, $list)) {
            $data['sales'] = $this->deviasi_form_model->get_all_sales_where($periode);
        } else {
            $data['sales'] = $this->deviasi_form_model->get_all_sales();
        }

        $data['request_user'] = $this->deviasi_form_model->get_deviasi_detail($request_id, $form_id);
        $data['db'] = $this->deviasi_form_model->get_by($request_id)->row();

        //load view
        $this->template->set('title', 'Data Request Detail');
        $this->template->load('template', 'request/deviasi_form/detail', $data);
    }

    function delete_detail($request_id, $request_user_id, $category_id)
    {

        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_user');

        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_approval');

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dihapus!</b></span>");

        redirect('request/deviasi_form/detail/' . $request_id . '/' . $category_id);
    }

    function send($request_id)
    {

        $querykategori = $this->db->query("SELECT * FROM internal_sms.data_request WHERE Request_ID = '$request_id'")->row();


        $data_update = array(
            'Hit_Code'  => '1009'
        );
        $this->db->where('Request_ID', $request_id);
        $this->db->update('internal_sms.data_request_user', $data_update);


        $request_user_id = $this->input->post('request_user_id');

        $data = array();
        foreach ($request_user_id as $item => $val) {
            $data[] = array(
                'request_user_id'   => $this->input->post('request_user_id')[$item],
                'employee_id'       => $this->input->post('employee_id')[$item],
                'dsr_code'          => $this->input->post('sales_code')[$item],
                'name'              => $this->input->post('name')[$item],
                'reason'            => $this->input->post('reason')[$item],
                'category'          => strtoupper(($querykategori->Category)),
                'created_by'        => $this->session->userdata('realname')
            );
        }
        $response = $this->deviasi_form_model->insert_data_api($data);

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

        redirect('request/deviasi_form');
    }
}
