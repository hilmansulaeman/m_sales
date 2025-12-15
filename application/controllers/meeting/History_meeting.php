<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class History_meeting extends MY_Controller
{	
	function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->helper(array('url', 'html', 'form'));
		$this->load->model('history_meeting_model');
		$this->db2 = $this->load->database('db_user', TRUE);
	}

	function index()
	{
		$data['title'] = 'History Meeting';
		$start_date = date('Y-m-01');
		$end_date = date('Y-m-d');
		$this->session->set_userdata('start_date', $start_date);
		$this->session->set_userdata('end_date', $end_date);

		$this->template->load('template', 'meeting/history_meeting/index', $data);
	}

	function get_data()
	{

		$nik = $this->session->userdata('sl_code');

		$query = $this->history_meeting_model->get_datatables($nik);

		// cekvar($query->result());
		// cekdb();

		$data = array();
		$no = $_POST['start'];
		foreach ($query->result() as $row) {


			// $action = '<a href="' . site_url('meeting/history_meeting/detailmeeting/' . $row->Schedule_ID) . '" class="btn btn-success btn-icon btn-circle btn-sm"><i class="fa fa-eye"></i></a>';

			if($row->Location_Name == ''){
				$lokasi = $row->Location_Name = '-';
			}else{
				$lokasi = $row->Location_Name;
			}

			if($row->Link_Meeting == ''){
				$link = $row->Link_Meeting = '-';
			}else{
				$link = $row->Link_Meeting;
			}

			if($row->Status == 'Open'){
				$action = '<a href="" class="btn btn-danger btn-icon btn-circle btn-sm" style="width:35px;" ><i class="fa fa-lock"></i></a>';
			}else{
				$action = '<a href="' . site_url('meeting/history_meeting/detailmeeting/' . $row->Schedule_ID) . '" class="btn btn-success btn-icon btn-circle btn-sm" style="width:35px;"><i class="fa fa-eye"></i></a>';
			}

			$data[] = array(
				++$no,
				$row->Name,
				$row->Schedule_Date,
				$row->Schedule_Day,
				$row->Tema,
				$row->Schedule_Type,
				$lokasi,
				$link,
				$row->Status,
				$row->Created_By,
				$action,
			);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->history_meeting_model->count_filtered($nik),
			"recordsFiltered" => $this->history_meeting_model->count_filtered($nik),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function detailmeeting($id='')
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('hasil_mom', 'Hasil MOM', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);

		
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

		if ($this->form_validation->run() == FALSE){
			$data['query'] = $this->history_meeting_model->getData($id);
			
			$data['title'] = 'Update Meeting';
			$data['status_schedule'] = $this->history_meeting_model->getMeeting($id)->row('Status');

	
			if(!empty($data['query']->result()) && $data['status_schedule'] == 'Open'){
				$this->template->load('template','meeting/update_meeting/index', $data);
			}elseif($data['status_schedule'] == 'Closed'){				
				$data['data_update_meeting'] = $this->history_meeting_model->getMeeting($id);
				

				$this->template->load('template','meeting/history_meeting/detail', $data);

			}else{
				redirect('meeting/schedule');
			}
		}else{
			$data_update_schedule = array(
				'Status'		=> 'Closed',
				'Updated_By'	=> $this->session->userdata('realname'),
				'Update_Date'	=> date('Y-m-d H:i:s'),
				'MOM'			=> $this->input->post('hasil_mom'),
			);
					
			$this->db->trans_start();
				$this->update_meeting_model->update_data('ref_schedule','Schedule_ID',$id,$data_update_schedule);
				if(!empty($this->input->post('participantID'))){

	                $jml_du = count($this->input->post('participantID'));
	                $du = $this->input->post('participantID');

	                for($i=0 ; $i < $jml_du ; $i++){
                        $data_update_participant = array(
                            'Absent_Status' => 1,
                        );
						$this->update_meeting_model->update_data('data_participant','Participant_ID',$du[$i],$data_update_participant);
	                }
	            }
				
			

			$this->db->trans_complete();

			$this->session->set_flashdata('message', "Schedule meeting telah diupdate");

    		redirect('meeting/schedule');
		}
		
	}


}