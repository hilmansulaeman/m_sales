<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reaktif_form extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template'));
        $this->load->model('approval_request/reaktif_form_model');
    }

    function index()
    {
        $username = $this->session->userdata('sl_code');
        $this->session->set_userdata('username', $username);

        $position = $this->session->userdata('position');
        $this->session->set_userdata('position', $position);

        //load view
        $this->template->set('title', 'Data Form Reaktif');
        $this->template->load('template', 'approval_request/reaktif_form/index');
    }

    function get_data_reaktif()
    {
        $namalengkap = $this->session->userdata('realname');
        $position = $this->session->userdata('position');
        $sales_code = $this->session->userdata('sl_code');

        $query = $this->reaktif_form_model->get_datatables();
        $count_data = $this->reaktif_form_model->count_all()->row();
        // var_dump($query->num_rows());die;

        $data = array();
        $no = $_POST['start'];
        foreach ($query->result() as $row) {
            $request_id = $row->Request_ID;
            // var_dump($request_id);die;

            $action = '<a href="' . site_url() . "approval_request/reaktif_form/detail/" . $request_id . '" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-list" title="Detail Data"></i></span></a>';

            // $cek = $this->db->query("SELECT  
            //                         COUNT(Request_ID) AS total,
            //                         SUM(IF(Hit_Code IN('1005','1006','1007','1008'),1,0) AND Approval_Name = '$namalengkap' AND Approval_Status = '1' ) AS approved,
            //                         SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected
            //                         FROM internal_sms.data_request_user
            //                         WHERE Request_ID = '$request_id' 
            //                         ")->row();

            /*$this->db->select("
                COUNT(Request_ID) AS total,
                SUM(IF(Hit_Code IN('1006'),1,0) && Approval_Name = '$namalengkap' && Approval_Status = '1' ) AS approved,
                SUM(IF(Hit_Code = '1008' && File IS NOT NULL,1,0)) AS need_gm,
                SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected
            ");
            $this->db->from("internal_sms.data_request_user");
            $this->db->where("Request_ID", $request_id);
            $cekQuery = $this->db->get();*/

            $this->db->select("
                SUM(IF(Status = '0' && Sales_Code = '$sales_code',1,0)) AS total,
                SUM(IF(Status = '1' && Sales_Code = '$sales_code',1,0)) AS approved,
                SUM(IF(Status = '2' && Sales_Code = '$sales_code',1,0)) AS rejected
            ");
            $this->db->from("internal_sms.data_request_approval");
            $this->db->where("Request_ID", $request_id);
            $cekQuery = $this->db->get();
            
            $cek = $cekQuery->row();

            // if($position == 'GM') {
            //     $total_data = '<span class="btn btn-xs btn-info">' . $cek->need_gm . '</span>';
            // }
            // else {
            //     $total_data = '<span class="btn btn-xs btn-info">' . $cek->total . '</span>';
            // }

            $total_data = '<span class="btn btn-xs btn-info">' . $cek->total . '</span>';
            
            $data[] = array(
                ++$no,
                $row->Efective_Date,
                $row->Created_Date,
                $row->Created_Name,
                $total_data,
                '<span class="btn btn-xs btn-success">' . $cek->approved . '</span>',
                '<span class="btn btn-xs btn-danger">' . $cek->rejected . '</span>',
                $action
            );
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count_data->total,
            "recordsFiltered" => $this->reaktif_form_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function detail($request_id)
    {
        if ($this->idor->detail_approval($request_id) == 0) return redirect('update_error');

        $data['request_user'] = $this->reaktif_form_model->get_reaktif_detail($request_id);
        //$data['db'] = $this->restruct_form_model->get_by($request_id)->row();

        //load view
        $this->template->set('title', 'Data Request Detail');
        $this->template->load('template', 'approval_request/reaktif_form/detail', $data);
    }

    function approve($request_id)
    {

        $username = $this->session->userdata('sl_code');
        $realname = $this->session->userdata('realname');
        $position = $this->session->userdata('position');
        // echo $position;die();

        $forms = $this->input->post('request_user_id[]');

        $data_update   = array();
        $data_logs_hrd = array();
        foreach ($forms as $form => $val) {
            $request_user_id = $_POST['request_user_id'][$form];

            $get_data = $this->db->get_where('data_request_user', array('Request_User_ID' => $request_user_id))->row();
            $reason = $get_data->Reason;
            $sales_code = $get_data->Sales_Code;
            $sales_name = $get_data->Sales_Name;
            $note       = $get_data->Note;
            $product    = $get_data->Product;

            //GET DETAIL SALES
            $getDataEmployee = $this->reaktif_form_model->getData_api($sales_code, 'bySalesCode');
            $employee_id     = $getDataEmployee->Employee_ID;
            $join_date1     = $getDataEmployee->Join_Date1;
            $join_date2     = $getDataEmployee->Join_Date2;

            // echo '<pre>';
            // print_r($employee_id);
            // echo '</pre>';
            // die();

            $getCategory = $this->db->get_where('data_request', array('Request_ID' => $request_id))->row();
            $category = $getCategory->Category;
            $created_name = $getCategory->Created_Name;

            $data_update_request_approval = array(
                'Status'            => '1',
                'Approved_Date'     => date('Y-m-d')
            );
            $this->db->where('Request_User_ID', $request_user_id);
            $this->db->where('Sales_Code', $username);
            $this->db->update('data_request_approval', $data_update_request_approval);

            //GET UPLINER YANG DI REQUEST
            $q_sm = $this->reaktif_form_model->getData_api($username, 'sm_position');
            $sm_code = $q_sm->DSR_Code;
            $sm_name = $q_sm->Name;
            $sm_position = $q_sm->Position;
            // echo '<pre>';
            // print_r($q_sm);
            // echo '</pre>';
            // die();

            $getApproval = $this->db->get_where('ref_category_structure', array('Category' => $category))->row();

            if ($position == $getApproval->Approval_Max || $sm_position == 'GM') {
                // echo 'IF '.$position;die();
                //POSITION BSH, terus belum pernah reactive, langsung send HRD
                
                // echo 'JOIN DATE 1 NULL '.$join_date1;die();
                $data_update[] = array(
                    'Approval'          => $username,
                    'Approval_Name'     => $realname,
                    'Approval_Status'   => 1,
                    'Request_User_ID'   => $request_user_id,
                    'Hit_Code'          => '1009'
                );

                $data_process_log = array(
                    'Request_ID'        => $request_id,
                    'Request_User_ID'   => $request_user_id,
                    'Hit_Code'          => '1009',
                    'Description'       => 'Approve '.$position,
                    'Updated_By'        => $this->session->userdata('realname'),
                    'Updated_Date'      => date('Y-m-d h:i:s')
                );
                $this->db->insert('data_process_log', $data_process_log);

                $data_logs_hrd[] = array(
                    'regnoID'           => $this->input->post('regnoID')[$form],
                    'request_user_id'   => $this->input->post('request_user_id')[$form],
                    'employee_id'       => $employee_id,
                    'dsr_code'          => $this->input->post('sales_code')[$form],
                    'name'              => $this->input->post('name')[$form],
                    'join_date1'        => $join_date1,
                    'join_date2'        => $this->input->post('efective_date')[$form],
                    'status'            => 'ACTIVE',
                    'reason'            => $this->input->post('reason')[$form],
                    'note'              => $this->input->post('note')[$form],
                    'category'          => $category,
                    'created_by'        => $created_name,
                    'approve_gm'        => 0
                );
                
            }
            elseif ($position == 'GM') {
                // echo 'ELSEIF '.$position;die();
                $data_update[] = array(
                    'Approval'          => $username,
                    'Approval_Name'     => $realname,
                    'Approval_Status'   => 1,
                    'Request_User_ID'   => $request_user_id,
                    'Hit_Code'  => '1009'
                );

                $data_logs_hrd[] = array(
                        'request_user_id'   => $this->input->post('request_user_id')[$form],
                        'approve_gm'        => 1
                );

                $data_process_log = array(
                    'Request_ID'        => $request_id,
                    'Request_User_ID'   => $request_user_id,
                    'Hit_Code'          => '1009',
                    'Description'       => 'Approve GM',
                    'Updated_By'        => $this->session->userdata('realname'),
                    'Updated_Date'      => date('Y-m-d h:i:s')
                );
                $this->db->insert('data_process_log', $data_process_log);
            }
            else {
                // echo 'ELSE '.$position;die();
                $data_request_user = array(
                    'Approval'          => $username,
                    'Approval_Name'     => $realname,
                    'Approval_Status'   => 1
                );
                $this->db->where('Request_User_ID', $request_user_id);
                $this->db->update('data_request_user', $data_request_user);

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

                $data_process_log = array(
                    'Request_ID'        => $request_id,
                    'Request_User_ID'   => $request_user_id,
                    'Description'       => 'Approve RSM',
                    'Hit_Code'          => '1008',
                    'Updated_By'        => $this->session->userdata('realname'),
                    'Updated_Date'      => date('Y-m-d h:i:s')
                );
                $this->db->insert('data_process_log', $data_process_log);
            }
        }

        // echo $sm_position;die();

        if($position == $getApproval->Approval_Max || $sm_position == 'GM') {
            $this->db->update_batch('internal_sms.data_request_user', $data_update, 'Request_User_ID');
            // echo '<pre>';
            // print_r($data_logs_hrd);
            // echo '</pre>';
            // die();
            $response = $this->reaktif_form_model->insert_data_api($data_logs_hrd, 'bsh');
        }
        elseif($position == 'GM') {
            $this->db->update_batch('internal_sms.data_request_user', $data_update, 'Request_User_ID');
            $response = $this->reaktif_form_model->insert_data_api($data_logs_hrd, 'gm');
        }


        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil di setujui!</b></span>");

        $cek = $this->db->get_where('data_request_approval', array('Request_ID' => $request_id, 'Sales_Code' => $username, 'Status' => '0'))->num_rows();
        if ($cek > 0) {
            redirect('approval_request/reaktif_form/detail/' . $request_id);
        } else {
            redirect('approval_request/reaktif_form');
        }
    }

    private function _checkDate($join_date2, $efective) {
        if($join_date2 == null && $join_date2 == '0000-00-00' && $join_date2 == '') {
            return $efective;
        }
        else {
            return $join_date2;
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

            $q_sm    = $this->db->get_where("SELECT * FROM db_hrd.data_employee WHERE DSR_Code = '$username'")->row();
            $sm_code = $q_sm->SM_Code;
            $sm_name = $q_sm->SM_Name;

            // $get_position = $this->db->query("SELECT * FROM db_hrd.data_employee WHERE DSR_Code = '$sm_code'")->row();
            $get_position = $this->db->get_where("db_hrd.data_employee", array("DSR_Code" => $sm_code))->row();
            $sm_position  = $get_position->Position;

            $data_updaterestruct_hrd = array();
            $data_updaterestruct_hrd[] = array(
                'request_user_id'   => $request_user_id
            );

            $this->reaktif_form_model->insert_data_api_update($data_updaterestruct_hrd);
        }
        $this->db->update_batch('internal_sms.data_request_user', $data_update, 'Request_User_ID');

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data ditolak!</b></span>");

        // $cek = $this->db->query("SELECT * FROM data_request_approval WHERE Request_ID = '$request_id' AND Sales_Code = '$username' AND Status = '0'")->num_rows();
        $cek = $this->db->get_where("data_request_approval", array("Request_ID" => $request_id, "Sales_Code" => $username, "Status" => 0))->num_rows();
        if ($cek > 0) {
            redirect('approval_request/reaktif_form/detail/' . $request_id);
        } else {
            redirect('approval_request/reaktif_form');
        }
    }


    public function getDetailSales($id = NULL)
    {
        if (!empty($id)) {
            $query = $this->reaktif_form_model->getDetailSales($id);
            if ($query->num_rows() > 0) {
                $data[] = $query->row_array();
                echo json_encode($data);
            }
        }
    }
}
