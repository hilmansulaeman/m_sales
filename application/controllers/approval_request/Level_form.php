<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Level_form extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'file', 'download'));
        $this->load->library(array('template'));
        $this->load->model('approval_request/level_form_model');

        // $this->db2 = $this->load->database('hrd', TRUE);
    }

    function index()
    {
        $username = $this->session->userdata('sl_code');
        $this->session->set_userdata('username', $username);

        $position = $this->session->userdata('position');
        $this->session->set_userdata('position', $position);

        //load view
        $this->template->set('title', 'Data Form Promosi');
        $this->template->load('template', 'approval_request/level_form/index');
    }

    function get_data_level()
    {
        $query       = $this->level_form_model->get_datatables();
        $namalengkap = $this->session->userdata('realname');
        $count_data  = $this->level_form_model->count_all()->row();
        $data        = array();
        $no          = $_POST['start'];
        foreach ($query->result() as $row) {
            $request_id = $row->Request_ID;

            $action = '
            <a href="' . site_url() . "approval_request/level_form/detail/" . $request_id . '" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-list" title="Detail Data"></i></span></a>
            ';

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
                $row->Category,
                $row->Efective_Date,
                $row->Created_Date,
                $row->Created_Name,
                '<span class="btn btn-xs btn-info">' . $cek->total . '</span>',
                '<span class="btn btn-xs btn-success">' . $cek->approved . '</span>',
                '<span class="btn btn-xs btn-danger">' . $cek->rejected . '</span>',
                $action
            );
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count_data->total,
            "recordsFiltered" => $this->level_form_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function detail($request_id)
    {
        if ($this->idor->detail_approval($request_id) == 0) return redirect('update_error');

        $data['request_user'] = $this->level_form_model->get_level_detail($request_id);
        $data['db']           = $this->level_form_model->get_by($request_id)->row();

        //load view
        $this->template->set('title', 'Data Approval Detail');
        $this->template->load('template', 'approval_request/level_form/detail', $data);
    }

    function approve($request_id)
    {
        $username = $this->session->userdata('sl_code');
        $realname = $this->session->userdata('realname');
        $position = $this->session->userdata('position');

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

            $get_employee_id = $this->level_form_model->get_employee_id($sales_code, $product);
            $employee_id     = $get_employee_id->Employee_ID;

            // echo $employee_id;die();

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
            $dataSM = $this->level_form_model->getData_api($username);
            // echo '<pre>';
            // print_r($dataSM->Position);
            // echo '</pre>';
            // die();
            $sm_code = $dataSM->DSR_Code;
            $sm_name = $dataSM->Name;
            $sm_position = $dataSM->Position;

            // echo $sm_position;die();

            $getApproval = $this->db->get_where('ref_category_structure', array('Category' => $category))->row();

            if ($position == $getApproval->Approval_Max) {
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
                    'Description'       => 'Approve GM',
                    'Updated_By'        => $this->session->userdata('realname'),
                    'Updated_Date'      => date('Y-m-d h:i:s')
                );
                $this->db->insert('data_process_log', $data_process_log);

                $updateRequest = array(
                    'Is_Send'   => '1'
                );
                $this->db->where('Request_ID', $request_id);
                $this->db->update('data_request', $updateRequest);

                $data_logs_hrd[] = array(
                    'regnoID'           => $this->input->post('regnoID')[$form],
                    'request_user_id'   => $this->input->post('request_user_id')[$form],
                    // 'employee_id'       => $this->input->post('employee_id')[$form],
                    'employee_id'       => $employee_id,
                    'dsr_code'          => $this->input->post('sales_code')[$form],
                    'name'              => $this->input->post('name')[$form],
                    'reason'            => $this->input->post('reason')[$form],
                    'note'              => $this->input->post('note')[$form],
                    'category'          => $category,
                    'created_by'        => $created_name
                );

            } else {
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
            }
        }
        if ($position == $getApproval->Approval_Max) {
            $this->db->update_batch('internal_sms.data_request_user', $data_update, 'Request_User_ID');
            // echo '<pre>';
            // print_r($data_logs_hrd);
            // echo '</pre>';
            // die();
            $response = $this->level_form_model->insert_data_api($data_logs_hrd);
        }

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil di setujui!</b></span>");

        $cek = $this->db->get_where('data_request_approval', array('Request_ID' => $request_id,  'Sales_Code' => $username, 'Status' => '0'))->num_rows();
        if ($cek > 0) {
            redirect('approval_request/level_form/detail/' . $request_id);
        } else {
            redirect('approval_request/level_form');
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

            // $getCategory = $this->db->get_where('data_request', array('Request_ID' => $request_id))->row();
            // $category = $getCategory->Category;

            // $getApproval = $this->db->get_where('ref_category_structure', array('Category' => $category))->row();
            // if ($position == $getApproval->Approval_Max) {

                $data_process_log = array(
                    'Request_ID'        => $request_id,
                    'Request_User_ID'   => $request_user_id,
                    'Hit_Code'          => '1004',
                    'Description'       => 'Ditolak '.$position,
                    'Updated_By'        => $this->session->userdata('realname'),
                    'Updated_Date'      => date('Y-m-d h:i:s')
                );
                $this->db->insert('data_process_log', $data_process_log);

            // }
        }
        $this->db->update_batch('internal_sms.data_request_user', $data_update, 'Request_User_ID');

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data ditolak!</b></span>");

        $cek = $this->db->get_where('data_request_approval', array('Request_ID' => $request_id,  'Sales_Code' => $username, 'Status' => '0'))->num_rows();
        if ($cek > 0) {
            redirect('approval_request/level_form/detail/' . $request_id);
        } else {
            redirect('approval_request/level_form');
        }
    }

    public function getDetailSales($id = NULL)
    {
        if (!empty($id)) {
            $query = $this->level_form_model->getDetailSales($id);
            if ($query->num_rows() > 0) {
                $data[] = $query->row_array();
                echo json_encode($data);
            }
        }
    }

    // =============================================================================================================================
    // =============================================================================================================================
    // =============================================================================================================================
    // PERFORMANCE
        function performance_popup($sales_code, $product, $position)
        {
            $month     = date('m');
            $monthMin1 = mktime(0,0,0,date("m")-1,1,date("Y"));
            $monthMin2 = mktime(0,0,0,date("m")-2,1,date("Y"));
            $monthMin3 = mktime(0,0,0,date("m")-3,1,date("Y"));

            $productBase = str_replace('%20', ' ', $product);
            
            if ($productBase == 'PEMOL') {
                $data['query1'] = $this->level_form_model->getPerformancePemol($sales_code, $product, $monthMin1)[0];
                $data['query2'] = $this->level_form_model->getPerformancePemol($sales_code, $product, $monthMin2)[0];
                $data['query3'] = $this->level_form_model->getPerformancePemol($sales_code, $product, $monthMin3)[0];

                $views = 'detail_popup_pemol';
            } else if ($productBase == 'Sales Merchant') {
                // $getDataIS = $this->level_form_model->getDataInput($sales_code, $product, 'IS'); // IS = Input => input System
			    // $getDataBS = $this->level_form_model->getDataInput($sales_code, $product, 'BS'); // BS = Input => bukan System

                // $getTotalsReceived   = $this->level_form_model->getTotalsProcessing($sales_code, $product, 'received');
                // $getTotalsInprocess  = $this->level_form_model->getTotalsProcessing($sales_code, $product, 'inprocess');
                // $getTotalsRTS  		 = $this->level_form_model->getTotalsProcessing($sales_code, $product, 'rts');
                $getTotalsSend  	 = $this->level_form_model->getTotalsProcessing($sales_code, $product, 'send');

                // $getDataPR   = $this->level_form_model->getDataProcessing($sales_code, $product, 'PR'); // PR = Processing => Process Received
                // $getDataPI   = $this->level_form_model->getDataProcessing($sales_code, $product, 'PI'); // PI = Processing => Process Inprocess
                // $getDataPRTS = $this->level_form_model->getDataProcessing($sales_code, $product, 'PRTS'); // PRTS = Processing => Processs RTS
                $getDataPS   = $this->level_form_model->getDataProcessing($sales_code, $product, 'PS'); // PS = Processing => Process Send
                // $getDataPC = $this->level_form_model->getDataProcessing($sales_code, $product, 'PC'); // PC = Processing => Processs Cancel
                // $getDataPPS = $this->level_form_model->getDataProcessing($sales_code, $product, 'PPS'); // PPS = Processing => Process Pending

                // $data['dataIS']          = (array) $getDataIS;
                // $data['dataBS']          = (array) $getDataBS;

                // $data['totalsReceived']  = (array) $getTotalsReceived;
                // $data['totalsInprocess'] = (array) $getTotalsInprocess;
                // $data['totalsRTS']       = (array) $getTotalsRTS;
                $data['totalsSend']      = (array) $getTotalsSend;

                // $data['dataPR']          = (array) $getDataPR;
                // $data['dataPI']          = (array) $getDataPI;
                // $data['dataPRTS']        = (array) $getDataPRTS;
                $data['dataPS']          = (array) $getDataPS;

                $views = 'detail_popup_merchant';
            } else {
                $data['query1'] = $this->level_form_model->getPerformance($sales_code, $product, $position, $monthMin1)[0];
                $data['query2'] = $this->level_form_model->getPerformance($sales_code, $product, $position, $monthMin2)[0];
                $data['query3'] = $this->level_form_model->getPerformance($sales_code, $product, $position, $monthMin3)[0];

                $views = 'detail_popup';
            }
            $this->load->view('approval_request/level_form/'.$views, $data);
        }
    // END
}
