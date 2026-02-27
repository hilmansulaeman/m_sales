<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Update_meeting extends MY_Controller 
{
	function __construct()
    {
        parent::__construct();
        $this->load->library('template');
        $this->load->helper(array('url', 'html','form','file'));
		$this->load->model('update_meeting_model');
    }
	
	function index($id='')
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('hasil_mom', 'Hasil MOM', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);

		// $this->form_validation->set_rules('image_name', 'Foto MOM', 'callback_file_check');
		
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

		if ($this->form_validation->run() == FALSE){
			$data['query'] = $this->update_meeting_model->getData($id);
			
			$data['title'] = 'Update Meeting';
			$data['status_schedule'] = $this->update_meeting_model->getMeeting($id)->row('Status');

			// cekvar($data['query']->result());
	
			//Load View
			if(!empty($data['query']->result()) && $data['status_schedule'] == 'Open'){
				$this->template->load('template','meeting/update_meeting/index', $data);
			}elseif($data['status_schedule'] == 'Closed'){				
				$data['data_update_meeting'] = $this->update_meeting_model->getMeeting($id);
				// $data['data_update_meeting_dokumen'] = $this->update_meeting_model->getDataUpdateMeetingDokumen($id);
				// cekvar('test');

				$this->template->load('template','meeting/update_meeting/index', $data);

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
				
				// //upload foto
				// $this->save_dokumen($id);

				// //upload dokumen
				// $this->save_dokumen_catatan($id);

			$this->db->trans_complete();

			$this->session->set_flashdata('message', "Schedule meeting telah diupdate");

    		redirect('meeting/schedule');
		}
		
	}

	public function file_check($str){
	    $allowed_mime_type_arr = array('image/jpeg','image/pjpeg','image/png','image/x-png');
	    $mime = get_mime_by_extension($_FILES['image_name']['name']);
	    if(isset($_FILES['image_name']['name']) && $_FILES['image_name']['name']!=""){
	        if(in_array($mime, $allowed_mime_type_arr)){
			    if(isset($_FILES['image_name']['size']) && $_FILES['image_name']['size']<=5242880){
		            return true;
			    }else{
			    	$this->form_validation->set_message('file_check', 'Foto Max 5MB.');
	        		return false;
			    }
	        }else{
	            $this->form_validation->set_message('file_check', 'Gunakan Format Foto jpg/jpeg/png.');
	            return false;
	        }
	    }else{
	        $this->form_validation->set_message('file_check', 'Foto harap di input.');
	        return false;
	    }
	}
	
	function save_dokumen($schedule_id='')
	{
		if(empty($schedule_id)){
			$config['file_name'] = $this->set_file_name();
		}else{
			$config['file_name'] = $this->set_file_name($schedule_id);
		}
		$config['upload_path'] = './upload/temp/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = '5000';
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		$this->load->library('image_lib');
		
		if ( ! $this->upload->do_upload('image_name'))
		{
			// if upload fail, grab error 
			$this->session->set_flashdata('message', "<span style='font-size:14px; text-align:center' class='col-lg-3 alert alert-danger alert-dismissable'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<b>Dokumen gagal diupload!</b>
					</button>
			</span>");		
			$this->CI =& get_instance();
			echo print_r($this->CI->upload->display_errors());
			anchor ('meeting/update_meeting/'.$schedule_id, 'Kembali ke form input', array('class'=>'back'));
			exit();	
    		// redirect('meeting/update_meeting/'.$schedule_id);
		}
		else
		{
			// otherwise, put the upload datas here.
			// if you want to use database, put insert query in this loop
			$upload_data = $this->upload->data();
			$image_info = getimagesize($upload_data['full_path']);
			$image_width = $image_info[0];
			$image_height = $image_info[1];
			$new_width = $image_width*(70/100);
			$new_height = $image_height*(70/100);
			
			// set the resize config
			$resize_conf = array(
				'source_image'  => $upload_data['full_path'], 
				'new_image'     => './upload/mom/'.$upload_data['file_name'],
				'width'         => $new_width,
				'height'        => $new_height
			);

			// initializing
			$this->image_lib->initialize($resize_conf);
			
			// do it!
			if ( ! $this->image_lib->resize())
			{
				// if upload fail, grab error 
				$this->session->set_flashdata('message', "<span style='font-size:14px; text-align:center' class='col-lg-3 alert alert-danger alert-dismissable'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<b>Dokumen gagal diresize!!</b>
					</button>
				</span>");
				$this->CI =& get_instance();
				echo print_r($this->CI->image_lib->display_errors());
				anchor ('meeting/update_meeting/'.$schedule_id, 'Kembali ke form input', array('class'=>'back'));
				exit();	
    			// redirect('meeting/update_meeting/'.$schedule_id);
			}
			else
			{
				$file_name = $upload_data['file_name'];

				$category_id = $this->input->post('category_id');
				$data_insert = array(
					'Schedule_ID'			=> $schedule_id,
					'category_id'			=> $category_id,
					'image_name'			=> $file_name,
				);
				
				$this->db->trans_start();
				$this->update_meeting_model->insert_document($data_insert);
				
				$this->db->trans_complete();				
			}
			unlink('./upload/temp/'.$upload_data['file_name']);			
		}		
	}

	function save_dokumen_catatan($schedule_id=''){
		if(empty($schedule_id)){
			$config['file_name'] = $this->set_file_name_catatan();
		}else{
			$config['file_name'] = $this->set_file_name_catatan($schedule_id);
		}
		$config['upload_path'] = './upload/mom/';
        $config['allowed_types'] = 'pdf|docx|doc';
        $this->load->library('upload', $config);
		$this->upload->initialize($config);
        
        $this->upload->do_upload('dokumen');
                    
		$upload_data = $this->upload->data();
        $file_name = $upload_data['file_name'];
    	$category_id_dokumen = $this->input->post('category_id_dokumen');
		$data_insert = array(
			'Schedule_ID'			=> $schedule_id,
			'category_id'			=> $category_id_dokumen,
			'image_name'			=> $file_name,
		);
		
		$this->db->trans_start();
		$this->update_meeting_model->insert_document($data_insert);
		
		$this->db->trans_complete();				
        
	}

	//set_file_name
	private function set_file_name($schedule_id='')
	{
		if(empty($schedule_id)){
	    	$id = $this->input->post('schedule_id');
		}else{
	    	$id = $schedule_id;
		}
		$foto = $this->input->post('category_id');
		if(!empty($_FILES['image_name']['name']))
		{
			$file = $_FILES['image_name']['name'];
			$gabung = str_replace(" ","_","$file");
			$pisah = explode(".",$gabung);
			$nama = time();
			$ext = end($pisah);
		}
		else
		{
			$nama = '';
			$ext = '';
		}
		return $id.'_'.$nama.'_'.$foto.'.'.$ext;
	}

	//set_file_name_catatan
	private function set_file_name_catatan($schedule_id='')
	{
		if(empty($schedule_id)){
	    	$id = $this->input->post('schedule_id');
		}else{
	    	$id = $schedule_id;
		}
		$dokumen = $this->input->post('category_id_dokumen');
		if(!empty($_FILES['dokumen']['name']))
		{
			$file = $_FILES['dokumen']['name'];
			$gabung = str_replace(" ","_","$file");
			$pisah = explode(".",$gabung);
			$nama = time();
			$ext = end($pisah);
		}
		else
		{
			$nama = '';
			$ext = '';
		}
		return $id.'_'.$nama.'_'.$dokumen.'.'.$ext;
	}
	
}
	
