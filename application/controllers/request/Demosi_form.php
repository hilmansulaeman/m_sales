<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Demosi_form extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template'));
        $this->load->model('request/demosi_form_model');
    }

    function add()
    {

        $position_ = $this->session->userdata('position');
        $position = $this->session->set_userdata('position', $position_);

        $list = array('SPV', 'ASM', 'RSM', 'BSH');

        if (in_array($position_, $list)) {
            $data['sales'] = $this->demosi_form_model->get_all_sales_where();
        } else {
            $data['sales'] = $this->demosi_form_model->get_all_sales();
        }
        //load view
        $this->template->set('title', 'Add Form Demosi');
        $this->template->load('template', 'request/prodemo/demosi_form/add', $data);
    }

    function save_request_demosi($uri3, $uri4)
    { //uri3 = add/detail, uri4 = id_request

        //GET Request_ID dari table ref_form
        // $slug_form = $this->uri->segment(2); //slug form
        // $query_form = $this->db->query("SELECT * FROM internal_sms.ref_form WHERE Slug = '$slug_form'");
        // $row_form = $query_form->row();

        $efective_date = $this->input->post('efective_date');
        $employee_id = $this->input->post('employee_id');
        $sales_code = $this->input->post('sales_code');
        $sales_name = $this->input->post('sales_name');
        $position   = $this->input->post('position');
        $level      = $this->input->post('level');
        $reason     = $this->input->post('reason');
        $category   = $this->input->post('category');
        $form_id    = '3';



        if ($uri3 == 'add') {

            //INSERT TABLE data_request jika uri->segment(3) nya add
            $request = array(
                'Form_ID'       => $form_id,
                'Efective_Date' => $efective_date,
                'Category'      => strtoupper($category),
                'Created_By'    => $this->session->userdata('username')
            );
            // var_dump($request);
            // die;
            $this->db->insert('internal_sms.data_request', $request);
            $request_id = $this->db->insert_id();
        } else {
            $request_id = $uri4;
        }

        $query_cek = $this->db->query("SELECT a.* FROM internal_sms.data_request_user a INNER JOIN internal_sms.data_request b ON b.Request_ID = a.Request_ID WHERE a.Sales_Code = '$sales_code' AND b.Form_ID = '$form_id' AND a.Hit_Code NOT IN('1002','1004','1007','1010') ");
        $cek = $query_cek->num_rows();

        if ($cek > 0) {
            //MESSAGE
            $this->session->set_flashdata('message', "<span class='btn btn-danger'><b>Data sedang dalam proses!</b></span>");
            $this->db->where('Request_ID', $request_id);
            $this->db->delete('data_request');

            redirect('request/demosi_form/add');
        } else {

            //get checker atau SM Code
            $query_checker = $this->db->query("SELECT * FROM db_hrd.data_sales_structure WHERE DSR_Code = '$sales_code'");
            $row_checker = $query_checker->row();


            //CHECKER
            $checker = $row_checker->ASM_Code;

            $checker_name = $row_checker->ASM_Name;

            //get position checker
            $query_position = $this->db->query("SELECT * FROM db_hrd.data_sales_structure WHERE DSR_Code = '$checker'");
            $row_position = $query_position->row();
            $checker_position = $row_position->Position; //sample SPV, ASM, RSM or BSH
            //get sm nya checker
            $sm_checker = $row_position->ASM_Code;
            $sm_checker_name = $row_position->ASM_Name;
            $query_checker_position = $this->db->query("SELECT * FROM db_hrd.data_sales_structure WHERE DSR_Code = '$sm_checker'")->row();
            $sm_checker_position = $query_checker_position->Position;

            //get structure yg di request
            $query = $this->db->query("SELECT * FROM db_hrd.data_sales_structure WHERE DSR_Code = '$sales_code'");
            $row = $query->row();


            $username = $this->session->userdata('sl_code');
            if ($username == $checker) {
                //$checker_date = date('Y-m-d H:i:s');
                $status_date = date('Y-m-d');
                $checker_status = '1';
            } else {
                $status_date   = '0000-00-00';
                $checker_status = '0';
            }


            $request_user = array(
                //'Request_User_ID'              => ini auto increment
                'Sales_Code'                => $sales_code,
                'Sales_Name'                => $sales_name,
                'Position'                  => $position,
                'Level'                     => $level,
                // 'Checker'                   => $checker,
                // 'Checker_Name'              => $checker_name,
                'Status_Date'               => $status_date,
                // 'Checker_Status'            => $checker_status,
                // 'Approval'                  => $approval_code,
                // 'Approval_Name'             => $approval_name,
                'Reason'                    => $reason,
                'Hit_Code'                  => '1000',
                'Request_ID'                => $request_id
            );
            $this->db->insert('internal_sms.data_request_user', $request_user);
            $request_user_id = $this->db->insert_id();

            //insert data_sales_log
            // $data_sales_log = array(
            //     'Employee_ID'       => $employee_id,
            //     'Request_User_ID'   => $request_user_id,
            //     'Reason'            => $reason
            // );
            // $this->db->insert('internal_sms.data_sales_logs', $data_sales_log);

            // //insert data_request_approval
            // $data_request_approval = array(
            //     'Request_ID'        => $request_id,
            //     'Request_User_ID'   => $request_user_id,
            //     'Sales_Code'        => $sm_checker,
            //     'Sales_Name'        => $sm_checker_name,
            //     'Position'          => $sm_checker_position,
            //     'Created_By'        => $this->session->userdata('username')
            // );
            // $this->db->insert('internal_sms.data_request_approval', $data_request_approval);

            /*    //insert data_sales_current_log
            $data_sales_current_logs = array (
                'Employee_ID'       => $employee_id,
                'Request_User_ID'   => $request_user_id,
                'Status'            => $status_old,
                'Resign_Date'       =>  $resign_date_old
            );
            $this->db->insert('internal_sms.data_sales_current_logs', $data_sales_current_logs);
        */
            $user_login = $this->session->userdata('sl_code');
            if ($user_login == $checker) {
                //update tabel data_request
                $data_request = array(
                    'Checked_Status'    => '1',
                    'Checked_By'        => $this->session->userdata('realname')
                );
            } else {
                $data_request = array(
                    'Checked_Status'    => '0',
                    'Checked_By'        => ''
                );
            }

            $this->db->where('Request_ID', $request_id);
            $this->db->update('internal_sms.data_request', $data_request);

            //MESSAGE
            $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil ditambahkan!</b></span>");

            redirect('request/demosi_form/detail/' . $request_id);
        }
    }

    function detail($request_id)
    {

        // $slug_form = $this->uri->segment(2);
        // $query = $this->db->query("SELECT * FROM ref_form WHERE Slug = '$slug_form'");
        // $row = $query->row();
        $form_id = '3';

        $position = $this->session->userdata('position');

        $list = array('SPV', 'ASM', 'RSM', 'BSH');

        if (in_array($position, $list)) {
            $data['sales'] = $this->demosi_form_model->get_all_sales_where();
        } else {
            $data['sales'] = $this->demosi_form_model->get_all_sales();
        }

        $data['request_user'] = $this->demosi_form_model->get_demosi_detail($request_id, $form_id);


        $data['db'] = $this->demosi_form_model->get_by($request_id)->row();

        //load view
        $this->template->set('title', 'Data Request Detail');
        $this->template->load('template', 'request/prodemo/demosi_form/detail', $data);
    }

    function delete_detail($request_id, $request_user_id)
    {
        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_user');

        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_approval');

        $cek1 = $this->db->query("SELECT  
                                    t1.*,t2.*
                                    FROM internal_sms.data_request_user t1
                                    LEFT JOIN internal_sms.data_request t2 ON t2.Request_ID = t1.Request_ID                                  
                                    WHERE t1.Request_ID = '$request_id'                                    
                                    ");
        $cek = $cek1->result();
        $countdata = count($cek);

        if ($countdata == 0) {
            $this->db->where('Request_ID', $request_id);
            $this->db->delete('internal_sms.data_request');
            redirect('request/demosi_form/add');
        }

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dihapus!</b></span>");
        redirect('request/demosi_form/detail/' . $request_id);
    }

    function send_to_checker($request_id)
    {
        //UPDATE HIT CODE data_request_user
        $checker = $this->input->post('checker');
        $username = $this->session->userdata('sl_code');
        if ($username == $checker) {
            $hit_code = '1008';
            $checker_status = '1';
        } else {
            $hit_code = '1001';
            $checker_status = '0';
        }

        //insert data_request_user
        $data_update = array(
            'Hit_Code'          => $hit_code,
            'Checker_Status'    => $checker_status
        );
        $this->db->where('Request_ID', $request_id);
        $this->db->update('internal_sms.data_request_user', $data_update);

        //INSERT data_process_log
        $updated_by = $this->session->userdata('realname');

        $request_user_id = $this->input->post('request_user_id');
        $data_log = array();
        foreach ($request_user_id as $form => $val) {
            $data_log[] = array(
                'Request_ID'            => $request_id,
                'Request_User_ID'       => $_POST['request_user_id'][$form],
                'Hit_Code'              => $hit_code,
                'Description'           => 'New Request',
                'Updated_By'            => $updated_by,
                'Updated_Date'          => date('Y-m-d h:i:s')
            );
        }
        $this->db->insert_batch('internal_sms.data_process_log', $data_log);

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dikirim!</b></span>");

        redirect('request/Prodemo_form');
    }

    function send($request_id)
    {
        $data_update = array(
            'Hit_Code'  => '1009'
        );
        $this->db->where('Request_ID', $request_id);
        $this->db->where('Hit_Code', '1000');
        $this->db->update('internal_sms.data_request_user', $data_update);

        $request_user_id = $this->input->post('request_user_id');
        $data = array();
        foreach ($request_user_id as $item => $val) {
            $data[] = array(
                'request_user_id'   => $this->input->post('request_user_id')[$item],
                'employee_id'       => $this->input->post('employee_id')[$item],
                'dsr_code'          => $this->input->post('dsr_code')[$item],
                'name'              => $this->input->post('name')[$item],
                'reason'            => $this->input->post('reason')[$item],
                'category'          => strtoupper($this->input->post('category')[$item]),
                'created_by'        => $this->session->userdata('realname')
            );
        }
        // var_dump($data);
        // die;


        $response = $this->demosi_form_model->insert_data_api($data);

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

        redirect('request/prodemo_form');
    }

    public function getDetailSales($id = NULL)
    {
        if (!empty($id)) {
            $query = $this->demosi_form_model->getDetailSales($id);
            if ($query->num_rows() > 0) {
                $data[] = $query->row_array();
                echo json_encode($data);
            }
        }
    }
}
