<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Restruct_form extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template'));
        $this->load->model('approval_request/restruct_form_model');
    }

    function index()
    {
        $username = $this->session->userdata('sl_code');
        $this->session->set_userdata('username', $username);

        $position = $this->session->userdata('position');
        $this->session->set_userdata('position', $position);

        //load view
        $this->template->set('title', 'Data Form Struktur');
        $this->template->load('template', 'approval_request/restruct_form/index');
    }

    function get_data_restruct()
    {
        $query = $this->restruct_form_model->get_datatables();
        $namalengkap = $this->session->userdata('realname');
        $count_data = $this->restruct_form_model->count_all()->row();
        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row) {
            $request_id = $row->Request_ID;
            $created_by = $row->Created_By;
            $q = $this->db->query("SELECT * FROM internal.data_sales WHERE DSR_Code = '$created_by'")->row();
            $created_name = $q->Name;

            $action = '<a href="' . site_url() . "approval_request/promosi_form/detail/" . $request_id . '" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-list" title="Detail Data"></i></span></a>';

            // $cek = $this->db->query("SELECT  
            //                         COUNT(Request_ID) AS total,
            //                         SUM(IF(Hit_Code IN('1005','1006','1007','1008'),1,0) AND Approval_Name = '$namalengkap' AND Approval_Status = '1' ) AS approved,
                                    
            //                         SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected
            //                         FROM internal_sms.data_request_user
            //                         WHERE Request_ID = '$request_id' 
            //                         ")->row();

            // $this->db->select("
            //     COUNT(Request_ID) AS total,
            //     SUM(IF(Hit_Code IN('1005','1006','1007','1008'),1,0) AND Approval_Name = '$namalengkap' AND Approval_Status = '1' ) AS approved,
            //     SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected
            // ");
            // $this->db->from("internal_sms.data_request_user");

            $this->db->select("
                SUM(IF(Status = '0' && Sales_Code = '$sales_code',1,0)) AS total,
                SUM(IF(Status = '1' && Sales_Code = '$sales_code',1,0)) AS approved,
                SUM(IF(Status = '2' && Sales_Code = '$sales_code',1,0)) AS rejected
            ");
            $this->db->from("internal_sms.data_request_approval");
            
            $this->db->where("Request_ID", $request_id);
            $cek1 = $this->db->get();

            $cek = $cek1->row();


            $data[] = array(
                ++$no,
                $row->Efective_Date,
                $row->Created_Date,
                $created_name,
                '<span class="btn btn-xs btn-info">' . $cek->total . '</span>',
                '<span class="btn btn-xs btn-success">' . $cek->approved . '</span>',
                '<span class="btn btn-xs btn-danger">' . $cek->rejected . '</span>',
                $action
            );
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count_data->total,
            "recordsFiltered" => $this->restruct_form_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function detail($request_id)
    {
        if ($this->idor->detail_approval($request_id) == 0) return redirect('update_error');

        $data['request_user'] = $this->restruct_form_model->get_restruct_detail($request_id);
        //$data['db'] = $this->restruct_form_model->get_by($request_id)->row();

        //load view
        $this->template->set('title', 'Data Request Detail');
        $this->template->load('template', 'approval_request/restruct_form/detail', $data);
    }

    function approve($request_id)
    {

        $username = $this->session->userdata('sl_code');
        $realname = $this->session->userdata('realname');
        $position = $this->session->userdata('position');

        $forms = $this->input->post('request_user_id[]');

        $data_update = array();
        foreach ($forms as $form => $val) {
            $request_user_id = $_POST['request_user_id'][$form];

            $get_data = $this->db->get_where('data_request_user', array('Request_User_ID' => $request_user_id))->row();
            $reason = $get_data->Reason;
            $sales_code = $get_data->Sales_Code;
            $sales_name = $get_data->Sales_Name;
            $checker_name = $get_data->Approval_Name;

            $getCategory = $this->db->get_where('data_request', array('Request_ID' => $request_id))->row();
            $category = $getCategory->Category;

            $get_data_employee = $this->db->get_where('db_hrd.data_employee', array('DSR_Code' => $get_data->Sales_Code))->row();
            $employee_id = $get_data_employee->Employee_ID;

            $data_update[] = array(
                'Request_User_ID'   => $request_user_id,
                'Approval'          => $username,
                'Approval_Name'     => $realname,
                'Status_Date'       => date('Y-m-d'),
                'Approval_Status'   => '1'
            );

            $data_update_request_approval = array(
                'Status'            => '1',
                'Approved_Date'     => date('Y-m-d')
            );
            $this->db->where('Request_User_ID', $request_user_id);
            $this->db->where('Sales_Code', $username);
            $this->db->update('data_request_approval', $data_update_request_approval);

            $q_sm = $this->db->query("SELECT * FROM db_hrd.data_employee WHERE DSR_Code = '$username'")->row();
            $sm_code = $q_sm->SM_Code;
            $sm_name = $q_sm->SM_Name;
            $get_position = $this->db->query("SELECT * FROM db_hrd.data_employee WHERE DSR_Code = '$sm_code'")->row();
            $sm_position = $get_position->Position;

            $getApproval = $this->db->query("SELECT * FROM ref_category_structure WHERE Category = '$category'")->row();

            if ($position == $getApproval->Approval_Max) {
                $data_updates[] = array(
                    'Request_User_ID'   => $request_user_id,
                    'Hit_Code'  => '1009'
                );

                $this->db->where('Request_ID', $request_id);
                $this->db->where('Hit_Code', '1008');
                $this->db->update_batch('internal_sms.data_request_user', $data_updates, 'Request_User_ID');



                $data_process_log = array(
                    'Request_ID'        => $request_id,
                    'Request_User_ID'   => $request_user_id,
                    'Hit_Code'          => '1009',
                    'Description'       => 'New Request From GM',
                    'Updated_By'        => $this->session->userdata('realname'),
                    'Updated_Date'      => date('Y-m-d h:i:s')
                );
                $this->db->insert('data_process_log', $data_process_log);

                $data_logs_hrd = array();
                $data_logs_hrd[] = array(
                    'request_user_id'   => $request_user_id,
                    'employee_id'       => $employee_id,
                    'dsr_code'          => $sales_code,
                    'name'              => $sales_name,
                    'reason'            => $reason,
                    'created_by'        => $checker_name,
                    'category'          => $category
                );

                $this->restruct_form_model->insert_data_api($data_logs_hrd);
            } else {
                $data_insert_request_approval = array(
                    'Request_ID'        => $request_id,
                    'Request_User_ID'   => $request_user_id,
                    'Sales_Code'        => $sm_code,
                    'Sales_Name'        => $sm_name,
                    'Position'          => $sm_position,
                    'Status'            => '0',
                    'Created_Date'      => date('Y-m-d'),
                    'Created_By'        => $username
                );
                $this->db->insert('data_request_approval', $data_insert_request_approval);
            }
        }
        $this->db->update_batch('internal_sms.data_request_user', $data_update, 'Request_User_ID');

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil di setujui!</b></span>");

        $cek = $this->db->query("SELECT * FROM data_request_approval WHERE Request_ID = '$request_id' AND Sales_Code = '$username' AND Status = '0'")->num_rows();
        if ($cek > 0) {
            redirect('approval_request/restruct_form/detail/' . $request_id);
        } else {
            redirect('approval_request/restruct_form');
        }
    }

    function reject($request_id)
    {
        $username = $this->session->userdata('sl_code');
        $realname = $this->session->userdata('realname');
        $position = $this->session->userdata('position');

        $forms = $this->input->post('request_user_id[]');

        $data_update = array();
        foreach ($forms as $form => $val) {
            $request_user_id = $_POST['request_user_id'][$form];

            $data_update[] = array(
                'Request_User_ID'   => $request_user_id,
                'Approval'          => $username,
                'Approval_Name'     => $realname,
                'Status_Date'       => date('Y-m-d'),
                'Approval_Status'   => '2',
                'Hit_Code'          => '1004'
            );

            $data_update_request_approval = array(
                'Status'            => '2',
                'Approved_Date'     => date('Y-m-d')
            );
            $this->db->where('Request_User_ID', $request_user_id);
            $this->db->where('Sales_Code', $username);
            $this->db->update('data_request_approval', $data_update_request_approval);


            $q_sm = $this->db->query("SELECT * FROM db_hrd.data_employee WHERE DSR_Code = '$username'")->row();
            $sm_code = $q_sm->SM_Code;
            $sm_name = $q_sm->SM_Name;
            $get_position = $this->db->query("SELECT * FROM db_hrd.data_employee WHERE DSR_Code = '$sm_code'")->row();
            $sm_position = $get_position->Position;
        }
        $this->db->update_batch('internal_sms.data_request_user', $data_update, 'Request_User_ID');

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data ditolak!</b></span>");

        $cek = $this->db->query("SELECT * FROM data_request_approval WHERE Request_ID = '$request_id' AND Sales_Code = '$username' AND Status = '0'")->num_rows();
        if ($cek > 0) {
            redirect('approval_request/restruct_form/detail/' . $request_id);
        } else {
            redirect('approval_request/restruct_form');
        }
    }

    
}
