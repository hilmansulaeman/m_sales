<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unblock_form extends MY_Controller
{	

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url','file','download'));
		$this->load->library(array('template'));
		$this->load->model('request/unblock_form_model');
	}
	
	function index()
    {
        $created_by = $this->session->userdata('sl_code');
        $this->session->set_userdata('created_by', $created_by); 

        //load view
		$this->template->set('title','Data Form Unblock');
		$this->template->load('template','request/unblock_form/index');
    }
	
	function get_data_unblock()
	{
        $query = $this->unblock_form_model->get_datatables();
		$count_data = $this->unblock_form_model->count_all()->row();
        $data = array();
        $no = $_POST['start'];
		foreach ($query->result() as $row){
            // var_dump( $request_id);
            $request_id = $row->Request_ID;
			$action = '<a href="'.site_url()."request/unblock_form/detail/".$request_id.'" ><span class="btn btn-xs btn-info"><i class="fa fa-md fa-list" title="Detail Data"></i></span></a>';
            $created_by = $this->session->userdata('realname');
            
            
            // var_dump($created_by);

            $cek1 = $this->db->query("SELECT  
                                    COUNT(Request_USER_ID) AS total,
                                    SUM(IF(Hit_Code IN('1006','1007'),1,0)) AS approved,
                                    SUM(IF(Hit_Code IN('1010'),1,0)) AS rejected,
                                    SUM(IF(Hit_Code = '1010',1,0)) AS cancel
                                    FROM internal_sms.data_request_user 
                                    -- INNER JOIN internal_sms.data_request b ON b.Request_ID = a.Request_ID
                                    -- INNER JOIN internal_sms.ref_form c ON c.Form_ID = b.Form_ID
                                    WHERE Request_ID = '$request_id'
                                    -- AND Form_Name = 'UNBLOCK FORM'
                                    ");
            $cek = $cek1->row();
            // var_dump($cek);
                                    
            $data[]= array(
                ++$no,
                $row->Efective_Date,
                
                $row->Created_Date,
                $created_by,
                '<span class="btn btn-xs btn-info">'.$cek->total.'</span>',
                '<span class="btn btn-xs btn-success">'.$cek->approved.'</span>',
                '<span class="btn btn-xs btn-danger">'.$cek->rejected.'</span>',
                '<span class="btn btn-xs btn-warning">'.$cek->cancel.'</span>',
                $action
            );
            // var_dump($data); 
		}
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count_data->total,
            "recordsFiltered" => $this->unblock_form_model->count_filtered(),
            "data" => $data,
        );
        // var_dump($output); die;
        //output dalam format JSON
        echo json_encode($output);
    }

    function add() {

        $position_ = $this->session->userdata('position');
        $position = $this->session->set_userdata('position', $position_);

        $list = array('SPV','ASM','RSM','BSH');

        if(in_array($position_, $list)) {
            $data['sales'] = $this->unblock_form_model->get_all_sales_where();
        }
        else {
            $data['sales'] = $this->unblock_form_model->get_all_sales();
        }
        
        //load view
		$this->template->set('title','Add Form unblock ');
        $this->template->load('template','request/unblock_form/add', $data);
    }

    function save_request_unblock($uri3, $uri4) 
    { //uri3 = add/detail, uri4 = id_request
        
        //GET Request_ID dari table ref_form
        $slug_form = $this->uri->segment(2); //slug form
        $query_form = $this->db->query("SELECT * FROM internal_sms.ref_form WHERE Slug = '$slug_form'");
        $row_form = $query_form->row();
        $form_id = $row_form->Form_ID;

        $efective_date = $this->input->post('efective_date');
        $employee_id = $this->input->post('employee_id');
        $sales_code = $this->input->post('sales_code');
        $sales_name = $this->input->post('sales_name');
        $position   = $this->input->post('position');
        $level      = $this->input->post('level');
        $reason     = $this->input->post('reason');

        //echo $reason;die;
        
        if($uri3 == 'add') {
            //INSERT TABLE data_request jika uri->segment(3) nya add
            $request = array (
                'Form_ID'       => $form_id,
                'Efective_Date' => $efective_date,
                'Created_By'    => $this->session->userdata('username')
            );
            // var_dump($request); die;
            $this->db->insert('internal_sms.data_request', $request);
            $request_id = $this->db->insert_id();
        }
        else {
            $request_id = $uri4;
        }

        $query_cek = $this->db->query("SELECT a.*, b.* FROM internal_sms.data_request_user a INNER JOIN internal_sms.data_request b ON b.Request_ID = a.Request_ID WHERE a.Sales_Code = '$sales_code' AND b.Form_ID = '$form_id' AND a.Hit_Code NOT IN('1006','1007','1009','1010') ");
        $cek = $query_cek->num_rows();
        

        if($cek > 0) {
            // var_dump($cek>0); die;
            //MESSAGE
            $this->session->set_flashdata('message', "<span class='btn btn-danger'><b>Data sedang dalam proses!</b></span>");
            $this->db->where('Request_ID', $request_id);
            $this->db->delete('data_request');

            redirect('request/unblock_form/add');
        }
        else {
            //get checker atau SM Code
            $query_checker = $this->db->query("SELECT * FROM db_hrd.data_employee WHERE DSR_Code = '$sales_code'");
            $row_checker = $query_checker->row();

            //CHECKER
            $checker = $row_checker->SM_Code;
            $checker_name = $row_checker->SM_Name;

            //get position checker
            $query_position = $this->db->query("SELECT * FROM db_hrd.data_employee WHERE DSR_Code = '$checker'");
            $row_position = $query_position->row();
            $checker_position = $row_position->Position; //sample SPV, ASM, RSM or BSH
            //get sm nya checker
            $sm_checker = $row_position->SM_Code;
            $sm_checker_name = $row_position->SM_Name;
            $query_checker_position = $this->db->query("SELECT * FROM db_hrd.data_employee WHERE DSR_Code = '$sm_checker'")->row();
            $sm_checker_position = $query_checker_position->Position;

            //get structure yg di request
            $query = $this->db->query("SELECT * FROM db_hrd.data_sales_structure WHERE DSR_Code = '$sales_code'");
            $row = $query->row();
            
        
            // var_dump($row); die;

            // if($checker_position == 'SPV') {
            //     $cek1 = $row->ASM_Code;
            //     if($cek1 == '0') { // jika ASM dummy, get RSM
            //         $cek2 = $row->RSM_Code;
            //         if($cek2 == '0') { // jika RSM dummy, get BSH
            //             $approval_code = $row->BSH_Code;
            //             $approval_name = $row->BSH_Name;
            //         }
            //         else {
            //             $approval_code = $row->RSM_Code;
            //             $approval_name = $row->RSM_Name;
            //         }
            //     }
            //     else {
            //         $approval_code = $row->ASM_Code;
            //         $approval_name = $row->ASM_Name;
            //     }
            // }
            // elseif($checker_position == 'ASM') {
            //     $cek1 = $row->RSM_Code;
            //     if($cek1 == '0') {
            //         $approval_code = $row->BSH_Code;
            //         $approval_name = $row->BSH_Name;
            //     }
            //     else {
            //         $approval_code = $row->RSM_Code;
            //         $approval_name = $row->RSM_Name;
            //     }
            // }
            

            

            // $username = $this->session->userdata('sl_code');
            // if($username == $checker) {
            //     //$checker_date = date('Y-m-d H:i:s');
            //     $status_date = date('Y-m-d');
            //     $checker_status = '1';
            // }
            // else {
            //     $status_date   = '0000-00-00';
            //     $checker_status = '0';
            // }

            // //query untuk get Need_Approved_GM tabel setup_approval
            // $query_setup = $this->db->query("SELECT * FROM internal_sms.setup_approval WHERE Form_ID = '$form_id' AND Checker = '$checker_position'");
            // $row_setup = $query_setup->row();
            // $need_approved_gm = $row_setup->Need_Approved_GM;



            $request_user = array(
                //'Request_User_ID'              => ini auto increment
                'Sales_Code'                => $sales_code,
                'Sales_Name'                => $sales_name,
                'Position'                  => $position,
                'Level'                     => $level,
                // 'Checker'                   => $checker,
                'Checker'                   => $this->session->userdata('sl_code'),
                // 'Checker_Name'              => $checker_name,
                'Checker_Name'              => $this->session->userdata('realname'),
                'Status_Date'               => date('Y-m-d '),
                // 'Checker_Status'            => $checker_status,
                // 'Approval'                  => $approval_code,
                // 'Approval_Name'             => $approval_name,
                'Reason'                    => $reason,
                // 'Need_Approved_GM'          => $need_approved_gm,
                'Hit_Code'                  => '1000',
                'Request_ID'                => $request_id
            );
            $this->db->insert('internal_sms.data_request_user', $request_user);
            $request_user_id = $this->db->insert_id();
            // var_dump($request_user);die;

            //insert data_sales_log
            $data_sales_log = array (
                'Employee_ID'       => $employee_id,
                'Request_User_ID'   => $request_user_id,
                'Reason'            => $reason
            );
            $this->db->insert('internal_sms.data_sales_logs', $data_sales_log);

            //insert data_request_approval
            // $data_request_approval = array (
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
            if($user_login == $checker) {
                //update tabel data_request
                $data_request = array(
                    'Checked_Status'    => '1',
                    'Checked_By'        => $this->session->userdata('realname')
                );
            }
            else {
                $data_request = array(
                    'Checked_Status'    => '0',
                    'Checked_By'        => ''
                );
            }

            $this->db->where('Request_ID', $request_id);
            $this->db->update('internal_sms.data_request', $data_request);

            //MESSAGE
            $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil ditambahkan!</b></span>");

            redirect('request/unblock_form/detail/'.$request_id);
        }

    } 

    function detail($request_id) {

        $slug_form = $this->uri->segment(2);
        $query = $this->db->query("SELECT * FROM ref_form WHERE Slug = '$slug_form'");
        $row = $query->row();
        $form_id = $row->Form_ID;

        $position = $this->session->userdata('position');

        $list = array('SPV','ASM','RSM','BSH');

        if(in_array($position, $list)) {
            $data['sales'] = $this->unblock_form_model->get_all_sales_where();
        }
        else {
            $data['sales'] = $this->unblock_form_model->get_all_sales();
        }

        $data['request_user'] = $this->unblock_form_model->get_restruct_detail($request_id, $form_id);
        $data['db'] = $this->unblock_form_model->get_by($request_id)->row();
        // var_dump($data['db']);die;

        //load view
		$this->template->set('title','Data Request Detail');
        $this->template->load('template','request/unblock_form/detail', $data);
    } 

    function delete_detail($request_id, $request_user_id) {
        
        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_request_user');

        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_sales_logs');

        $this->db->where('Request_User_ID', $request_user_id);
        $this->db->delete('internal_sms.data_sales_current_logs');

        
        

        $cek1 = $this->db->query("SELECT  
                                    t1.*,t2.*
                                    FROM internal_sms.data_request_user t1
                                    LEFT JOIN internal_sms.data_request t2 ON t2.Request_ID = t1.Request_ID                                  
                                    WHERE t1.Request_ID = '$request_id'                                    
                                    ");
        $cek = $cek1->result();
        $countdata = count($cek);
        
        if($countdata == 0){
            $this->db->where('Request_ID', $request_id);        
            $this->db->delete('internal_sms.data_request');
            redirect('request/unblock_form/add');
        }
        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil dihapus!</b></span>");
        redirect('request/unblock_form/detail/'.$request_id);
    }

    // function send_to_checker($request_id) {
    //     //UPDATE HIT CODE data_request_user
    //     $checker = $this->input->post('checker');
    //     $username = $this->session->userdata('sl_code');

    //     if($username == $checker) {
    //         $hit_code = '1007';
    //         $checker_status = '1';
    //     }
    //     else {
    //         $hit_code = '1000';
    //         $checker_status = '0';
    //     }

    //     //insert data_request_user
    //     $data_update = array(
    //         'Hit_Code'          => $hit_code,
    //         'Checker_Status'    => $checker_status
    //     );
    //     $this->db->where('Request_ID', $request_id);
    //     $this->db->update('internal_sms.data_request_user', $data_update);

    //     //INSERT data_process_log
    //     $updated_by = $this->session->userdata('realname');

    //     $request_user_id = $this->input->post('request_user_id');
    //     $data_log = array();
    //     foreach($request_user_id as $form => $val){
    //         $data_log[] = array(
    //             'Request_ID'            => $request_id,
    //             'Request_User_ID'       => $_POST['request_user_id'][$form],
    //             'Hit_Code'              => $hit_code,
    //             'Description'           => 'New Request',
    //             'Updated_By'            => $updated_by,
    //             'Updated_Date'          => date('Y-m-d h:i:s')
    //         );
    //     }
    //     $this->db->insert_batch('internal_sms.data_process_log', $data_log);

    //     $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil disubmit!</b></span>");

    //     redirect('request/unblock_form');
    // }

    function send($request_id) {
        
        $data_update = array(
            'Hit_Code'  => '1009'
        );
        $this->db->where('Request_ID', $request_id);
        $this->db->where('Hit_Code', '1000');
        $this->db->update('internal_sms.data_request_user', $data_update);
        
        $request_user_id = $this->input->post('request_user_id');
        // var_dump($request_user_id); 
        $data = array();
        foreach ($request_user_id as $item =>$val) {
            $data[] = array(
                'request_user_id'   => $this->input->post('request_user_id')[$item],
                'employee_id'       => $this->input->post('employee_id')[$item],
                'dsr_code'          => $this->input->post('dsr_code')[$item],
                'name'              => $this->input->post('name')[$item],
                'status'            => $this->input->post('status')[$item],
                'reason'            => $this->input->post('reason')[$item],
                // 'resign_date'       => $this->input->post('resign_date')[$item],
                'created_by'        => $this->session->userdata('realname')
            );   
        }
        // $response = $data;
        // var_dump($data); die;
        $response = $this->unblock_form_model->insert_data_api($data);
        

        $data_log = array();
        foreach($request_user_id as $form => $val){
            $data_log[] = array(
                'Request_ID'            => $request_id,
                'Request_User_ID'       => $_POST['request_user_id'][$form],
                'Hit_Code'              => '1009',
                'Description'           => 'New Request Status From ASM',
                'Updated_By'            => $this->session->userdata('realname'),
                'Updated_Date'          => date('Y-m-d h:i:s')
            );
        }
        $this->db->insert_batch('internal_sms.data_process_log', $data_log);

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil disubmit!</b></span>");

        redirect('request/unblock_form');
    }






	public function getDetailSales($id = NULL) {
        if (!empty($id)) {
			$query = $this->unblock_form_model->getDetailSales($id);
            if ($query->num_rows() > 0) {
                $data[] = $query->row_array() ;
                echo json_encode($data) ;
            }
        }
    }


}