<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exit_form extends MY_Controller
{	

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url','file','download'));
		$this->load->library(array('template'));
		$this->load->model('approval_request/exit_form_model');
	}
	
	function index()
    {
        $username = $this->session->userdata('sl_code');
        $this->session->set_userdata('username', $username); 
        //load view
		$this->template->set('title','Data Form Resign');
		$this->template->load('template','approval_request/exit_form/index');
    }
	
	function get_data_exit()
	{
        $query = $this->exit_form_model->get_datatables();
		$count_data = $this->exit_form_model->count_all()->row();
        $data = array();
        $no = $_POST['start'];
		foreach ($query->result() as $row){
            $request_id = $row->Request_ID;
            $created_by = $row->Created_By;
            $q = $this->db->query("SELECT * FROM internal.data_sales WHERE DSR_Code = '$created_by'")->row();
            $created_name = $q->Name;

			$action = '<a href="'.site_url()."approval_request/exit_form/detail/".$request_id.'" ><span class="btn btn-xs btn-warning"><i class="fa fa-md fa-list" title="Detail Data"></i></span></a>';

            $cek = $this->db->query("SELECT  
                                    COUNT(Request_ID) AS total,
                                    SUM(IF(Hit_Code IN('1005','1006','1007'),1,0)) AS approved,
                                    SUM(IF(Hit_Code IN('1002','1004'),1,0)) AS rejected
                                    FROM internal_sms.data_request_user
                                    WHERE Request_ID = '$request_id'
                                    ")->row();

                                    
            $data[] = array(
                ++$no,
                $row->Efective_Date,
                $row->Created_Date,
                $created_name,
                '<span class="btn btn-xs btn-info">'.$cek->total.'</span>',
                '<span class="btn btn-xs btn-success">'.$cek->approved.'</span>',
                '<span class="btn btn-xs btn-danger">'.$cek->rejected.'</span>',
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

    function detail($request_id) {
        $username = $this->session->userdata('sl_code');
        $q = $this->db->query("SELECT * FROM internal_sms.data_request_user WHERE Request_ID = '$request_id'")->row();
        $checker = $q->Checker;

        if($username == $checker) {
            $checker_status = '0';
        }
        else {
            $checker_status = '1';
        }

        $slug_form = $this->uri->segment(2);
        $query = $this->db->query("SELECT * FROM internal_sms.ref_form WHERE Slug = '$slug_form'");
        $row = $query->row();
        $form_id = $row->Form_ID;

        $data['request_user'] = $this->exit_form_model->get_exit_detail($request_id, $form_id, $checker);
        $data['db'] = $this->exit_form_model->get_by($request_id)->row();

        //load view
		$this->template->set('title','Data Request Detail');
        $this->template->load('template','approval_request/exit_form/detail', $data);
    } 

    function send_to_checker($request_id) {
        //UPDATE HIT CODE data_request_user
        $checker = $this->input->post('checker');
        $username = $this->session->userdata('sl_code');

        if($username == $checker) {
            $hit_code = '1003';
        }
        else {
            $hit_code = '1001';
        }

        //insert data_request_user
        $data_update = array(
            'Hit_Code'  => $hit_code
        );
        $this->db->where('Request_ID', $request_id);
        $this->db->update('internal_sms.data_request_user', $data_update);

        //INSERT data_process_log
        $updated_by = $this->session->userdata('realname');

        $request_user_id = $this->input->post('request_user_id');
        $data_log = array();
        foreach($request_user_id as $form => $val){
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

        $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil disubmit!</b></span>");

        redirect('request/exit_form');
    }

    function approve($request_id) {
        $username = $this->session->userdata('sl_code');
        $checker = $this->input->post('checker');
        $approval = $this->input->post('approval');

        $forms = $this->input->post('request_user_id[]');

        $data_update = array();
        foreach($forms as $form => $val){
            if($username == $checker) {
                $hit_code = '1003';
                $desc = 'Approve By Checker';
                $data_update[] = array(
                    'Request_User_ID'   => $_POST['request_user_id'][$form],
                    'Status_Date'       => date('Y-m-d'),
                    'Checker_Status'    => '1',
                    'Hit_Code'          => $hit_code
                );
            }
            else {
                $hit_code = '1005';
                $desc = 'Approve By Approval';
                $data_update[] = array(
                    'Request_User_ID'   => $_POST['request_user_id'][$form],
                    'Status_Date'       => date('Y-m-d'),
                    'Approval_Status'   => '1',
                    'Hit_Code'          => $hit_code
                );
            }
        }
        $this->db->update_batch('internal_sms.data_request_user', $data_update, 'Request_User_ID');

        //INSERT data_process_log
        $updated_by = $this->session->userdata('realname');
        $data_log = array();
        foreach($forms as $form => $val){
            $data_log[] = array(
                'Request_ID'            => $request_id,
                'Request_User_ID'       => $_POST['request_user_id'][$form],
                'Hit_Code'              => $hit_code,
                'Description'           => $desc,
                'Updated_By'            => $updated_by,
                'Updated_Date'          => date('Y-m-d h:i:s')
            );
        }
        $this->db->insert_batch('internal_sms.data_process_log', $data_log);

        if($username == $checker) {
            $data_request = array(
                'Checked_Status'    => '1',
                'Checked_By'    => $updated_by
            );
        }
        else {
            $data_request = array(
                'Approved_Status'    => '1',
                'Approved_By'        => $updated_by
            );
        }
        $this->db->where('Request_ID', $request_id);
        $this->db->update('internal_sms.data_request', $data_request);

        $this->session->set_flashdata('message', "<span class='btn btn-info'><b>Data berhasil di approve!</b></span>");

        redirect('approval_request/exit_form/detail/'.$request_id);
    }

    function reject($request_id) {
        $username = $this->session->userdata('sl_code');
        $checker = $this->input->post('checker');
        $approval = $this->input->post('approval');

        $forms = $this->input->post('request_user_id[]');

        $data_update = array();
        foreach($forms as $form => $val){
            if($username == $checker) {
                $hit_code = '1002';
                $desc = 'Reject By Checker';
                $data_update[] = array(
                    'Request_User_ID'   => $_POST['request_user_id'][$form],
                    'Status_Date'      => date('Y-m-d'),
                    'Checker_Status'    => '2',
                    'Hit_Code'          => $hit_code
                );
            }
            else {
                $hit_code = '1004';
                $desc = 'Reject By Approval';
                $data_update[] = array(
                    'Request_User_ID'   => $_POST['request_user_id'][$form],
                    'Status_Date'     => date('Y-m-d'),
                    'Approval_Status'    => '2',
                    'Hit_Code'          => $hit_code
                );
            }
        }
        $this->db->update_batch('internal_sms.data_request_user', $data_update, 'Request_User_ID');

        //INSERT data_process_log
        $updated_by = $this->session->userdata('realname');
        $data_log = array();
        foreach($forms as $form => $val){
            $data_log[] = array(
                'Request_ID'            => $request_id,
                'Request_User_ID'       => $_POST['request_user_id'][$form],
                'Hit_Code'              => $hit_code,
                'Description'           => $desc,
                'Updated_By'            => $updated_by,
                'Updated_Date'          => date('Y-m-d h:i:s')
            );
        }
        $this->db->insert_batch('internal_sms.data_process_log', $data_log);

        if($username == $checker) {
            $data_request = array(
                'Checked_Status'    => '1',
                'Checked_By'    => $updated_by
            );
        }
        else {
            $data_request = array(
                'Approved_Status'    => '1',
                'Approved_By'        => $updated_by
            );
        }
        $this->db->where('Request_ID', $request_id);
        $this->db->update('internal_sms.data_request', $data_request);

        $this->session->set_flashdata('message', "<span class='btn btn-info'><b>Data berhasil di reject!</b></span>");

        redirect('approval_request/exit_form/detail/'.$request_id);
    }

    function send($request_id,$checker) {
        $username = $this->session->userdata('sl_code');
        
        if($username == $checker) {
            $data_update = array(
                'Hit_Code'  => '1008'
            );
            $this->db->where('Request_ID', $request_id);
            $this->db->where('Hit_Code', '1003');
            $this->db->update('internal_sms.data_request_user', $data_update);
        }
        else {
            $data_update = array(
                'Hit_Code'  => '1009'
            );
            $this->db->where('Request_ID', $request_id);
            $this->db->where('Hit_Code', '1005');
            $this->db->update('internal_sms.data_request_user', $data_update);
            
            $request_user_id = $this->input->post('request_user_id');
            $data = array();
            foreach ($request_user_id as $item =>$val) {
                $data[] = array(
                    'request_user_id'   => $this->input->post('request_user_id')[$item],
                    'employee_id'       => $this->input->post('employee_id')[$item],
                    'status'            => $this->input->post('status')[$item],
                    'reason'            => $this->input->post('reason')[$item],
                    'resign_date'       => $this->input->post('resign_date')[$item],
                    'created_by'        => $this->session->userdata('realname')
                );   
            }
    
            $response = $this->exit_form_model->insert_data_api($data);
            
        }

        $this->session->set_flashdata('message', "<span class='btn btn-info'><b>Data berhasil di kirim!</b></span>");

        redirect('approval_request/exit_form');

    }

    function send_old($request_id,$checker, $approval) {
        $username = $this->session->userdata('sl_code');
        
        if($username == $checker) {
            $ht = '1003';
            $hit_code = '1008';
        }
        else {
            $ht = '1005';
            $hit_code = '1009';
        }
        $data_update = array(
            'Hit_Code'  => $hit_code
        );
        $this->db->where('Request_ID', $request_id);
        $this->db->where('Hit_Code', $ht);
        $this->db->update('internal_sms.data_request_user', $data_update);

        $this->session->set_flashdata('message', "<span class='btn btn-info'><b>Data berhasil di kirim!</b></span>");

        redirect('approval_request/exit_form');
    }






	public function getDetailSales($id = NULL) {
        if (!empty($id)) {
			$query = $this->exit_form_model->getDetailSales($id);
            if ($query->num_rows() > 0) {
                $data[] = $query->row_array() ;
                echo json_encode($data) ;
            }
        }
    }


}