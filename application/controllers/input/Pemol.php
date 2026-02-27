<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pemol extends MY_Controller
{
	private $model = 'input/pemol_model';
	private $limit = 20;
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('template'));
		$this->load->helper(array('form', 'url', 'html'));
		$this->load->model($this->model);
		$this->pemol_model->config('data_pemol', 'RegnoId');
	}

	function index()
	{
		$level = $this->session->userdata('level');
		$position = $this->session->userdata('position');

		if ($level == '1' || $level == '2') {
			$this->admin();
		} else {
			if ($position == 'RSM') {
				$this->rsm();
			} else if ($position == 'ASM') {
				$this->asm();
			} else if ($position == 'SPV') {
				$this->spv();
			} else {
				$this->dsr();
			}
		}
	}

	function dsr()
	{
		$this->session->set_userdata('date_from', date('Y-m-01'));
		$this->session->set_userdata('date_to', date('Y-m-d'));
		//load view
		$this->template->set('title', 'Data Pemol');
		$this->template->load('template', 'pemol/index');
	}

	function spv()
	{
		$this->session->set_userdata('date_from', date('Y-m-01'));
		$this->session->set_userdata('date_to', date('Y-m-d'));
		$nik = $this->session->userdata('username');
		$this->session->set_userdata('spv_code', $nik);
		//load view
		$this->template->set('title', 'Data Pemol');
		$this->template->load('template', 'pemol/index_spv');
	}

	function asm()
	{
		$this->session->set_userdata('date_from', date('Y-m-01'));
		$this->session->set_userdata('date_to', date('Y-m-d'));
		$nik = $this->session->userdata('username');
		$this->session->set_userdata('spv_code', $nik);
		$data['getSPV'] = $this->pemol_model->get_by_asm($nik);
		//load view
		$this->template->set('title', 'Data Pemol');
		$this->template->load('template', 'pemol/index_asm', $data);
	}

	function rsm()
	{
		$this->session->set_userdata('date_from', date('Y-m-01'));
		$this->session->set_userdata('date_to', date('Y-m-d'));
		$nik = $this->session->userdata('username');
		$this->session->set_userdata('spv_code', $nik);
		$data['getSPV'] = $this->pemol_model->get_by_rsm($nik);
		//load view
		$this->template->set('title', 'Data Pemol');
		$this->template->load('template', 'pemol/index_rsm', $data);
	}

	function admin()
	{
		$this->session->set_userdata('date_from', date('Y-m-01'));
		$this->session->set_userdata('date_to', date('Y-m-d'));
		$this->session->set_userdata('branch_pemol', '');
		$data['branch'] = $this->pemol_model->getBranch();
		//load view
		$this->template->set('title', 'Data Pemol');
		$this->template->load('template', 'pemol/index_admin', $data);
	}

	function get_data()
	{
		$nik = $this->session->userdata('username');
		$level = $this->session->userdata('level');
		$position = $this->session->userdata('position');
		$spv_code = $this->session->userdata('spv_code');
		$branch = $this->session->userdata('branch_pemol');
		$date_from = $this->session->userdata('date_from');
		$date_to = $this->session->userdata('date_to');

		if ($level == '1' || $level == '2') {
			$where = "t1.Branch = '$branch' AND t1.Input_Date >= '$date_from' AND t1.Input_Date <= '$date_to'";
		} else {
			if ($position == 'ASM' || $position == 'RSM') {
				$where = "t2.SPV_Code != '' AND t2.SPV_Code = '$spv_code' AND t1.Input_Date >= '$date_from' AND t1.Input_Date <= '$date_to'";
			} else if ($position == 'SPV') {
				//$where = "(t1.Sales_Code = '$nik' OR t2.SPV_Code = '$nik') AND t1.Input_Date >= '$date_from' AND t1.Input_Date <= '$date_to'";
				$where = "t2.SPV_Code = '$nik' AND t1.Input_Date >= '$date_from' AND t1.Input_Date <= '$date_to'";
			} else {
				$where = "t1.Sales_Code = '$nik' AND t1.Input_Date >= '$date_from' AND t1.Input_Date <= '$date_to'";
			}
		}

		$query = $this->pemol_model->_get_datapemol_query($nik, $where);

		$data = array();
		$no = $this->input->post('start');
		foreach ($query as $row) {
			$data[] = array(
				'<div style="color:#FFFFFF;font-size:12px;font-family: Arial;"><strong>' . ++$no . '</strong></div>',
				'<div style="color:#FFFFFF;font-size:12px;font-family: Arial;">
					<i class="fa-fw fa fa-building"></i> <strong>Nomor Rekening &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : ' . $row->Account_Number . ' </strong><br>						
					<i class="fa-fw fa fa-calendar"></i> <strong>Tanggal Input : ' . $row->Input_Date . '</strong></i><br>
					<i class="pull-right">
						<i class="fa-fw fa fa-user"></i> <strong>Nama Sales : ' . $row->Sales_Name . ' (' . $row->Sales_Code . ') </strong>
					</i><br>
					<span class="pull-right">
						<a href="' . site_url('input/pemol/detail/' . $row->RegnoId) . '" class="btn btn-primary btn-xs"><i class="fa fa-building"></i></a>						
						<a href="' . site_url('input/pemol/edit/' . $row->RegnoId) . '" class="btn btn-danger btn-xs"><i class="fa fa-edit"></i></a>						
					</span>
				</div>'
			);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->pemol_model->_count_datapemol($nik, $where),
			"recordsFiltered" => $this->pemol_model->_count_datapemol($nik, $where),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	public function filter_data()
	{
		$this->session->set_userdata('date_from', $this->input->post('date_from'));
		$this->session->set_userdata('date_to', $this->input->post('date_to'));
		$this->session->set_userdata('spv_code', $this->input->post('SPV'));
		$this->session->set_userdata('branch_pemol', $this->input->post('branch'));
		echo json_encode(array("status" => TRUE));
	}

	function add()
	{
		$this->session->unset_userdata('showAdd');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('Account_Number', 'No Rekening', 'required|numeric|max_length[10]|min_length[10]|callback_duplicate_account');
		$this->form_validation->set_rules('Source', 'Source', 'required');
		$this->form_validation->set_rules('Category', 'Kategori', 'required');
		if ($this->input->post('Category') == "Referral") {
			$this->form_validation->set_rules('Referral', 'Referral', 'required');
			$this->session->set_userdata('showAdd', 'show');
		}
		$this->form_validation->set_error_delimiters('<span style="color:#FF0000">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			//load view
			$data = [
				'ref_source'   => $this->db->get('ref_source'),
				'ref_category' => $this->db->get('ref_category'),
				'ref_referal'  => $this->db->order_by('Referral', 'ASC')->get('ref_referal'),
				'ref_referal2'  => $this->pemol_model->get_referal()
			];
			$this->template->set('title', 'Input Data Pemol');
			$this->template->load('template', 'pemol/add', $data);
		} else {
     		$username = $this->session->userdata('sl_code');
            $product = $this->session->userdata('product');
            $tanggal_hari_ini = date('Y-m-d');
			// check event limit
			$check_data_event = $this->pemol_model->check_limit_data_event($username,$product,$tanggal_hari_ini);
			// ada
			if($check_data_event->num_rows() > 0){
				$data_event = $check_data_event->row();

				$check_pemol_data = $this->pemol_model->check_pemol_exist($username,$tanggal_hari_ini);
				// kalo ada
                if(count($check_pemol_data) > 0){
                    // kondisi apakah data cc hari ini melebihi limit dari data event
                    if(count($check_pemol_data) >= $data_event->limit){

						// Maksimal input harian untuk produk PEMOL adalah 10 aplikasi per sales per hari.
                        // redirect kalo melebihi kuota
                        $this->session->set_flashdata('limit', 'Maksimal input harian untuk produk PEMOL adalah '.$data_event->limit.' aplikasi per sales per hari.');

                        //Direct ke view
                        redirect('input/pemol/add', 'refresh');
                    } else {
						// insert
						$dataInsert = $this->pemol_model->insert_data_api();
						// $id = $dataInsert->RegnoId;
						$id = $dataInsert;
						$this->session->unset_userdata('showAdd');
						$this->session->set_flashdata('message', 'Data berhasil disimpan, RegnoId = ' . $id);
						redirect('input/pemol/add');
                    }
                } else {
					// insert
					$dataInsert = $this->pemol_model->insert_data_api();
					// $id = $dataInsert->RegnoId;
					$id = $dataInsert;
					$this->session->unset_userdata('showAdd');
					$this->session->set_flashdata('message', 'Data berhasil disimpan, RegnoId = ' . $id);
					redirect('input/pemol/add');

                }
			} else {
                // check data limit
                $get_data_limit = $this->pemol_model->check_limit($product);

                if($get_data_limit->num_rows() > 0){
                    $data_limit = $get_data_limit->row();

                    // check data cc nya
                    $check_pemol_data = $this->pemol_model->check_pemol_exist($username,$tanggal_hari_ini);

                    if(count($check_pemol_data) > 0){

                        // check apakah data cc hari ini sudah melebihi limit 
                        if(count($check_pemol_data) >= $data_limit->limit){

                        	$this->session->set_flashdata('limit', 'Maksimal input harian untuk produk PEMOL adalah '.$data_limit->limit.' aplikasi per sales per hari.');

                            //Direct ke view
                            redirect('input/pemol/add', 'refresh');

                        } else {
							// insert
							$dataInsert = $this->pemol_model->insert_data_api();
							// $id = $dataInsert->RegnoId;
							$id = $dataInsert;
							$this->session->unset_userdata('showAdd');
							$this->session->set_flashdata('message', 'Data berhasil disimpan, RegnoId = ' . $id);
							redirect('input/pemol/add');
                        }

                    } else {
						// insert
						$dataInsert = $this->pemol_model->insert_data_api();
						// $id = $dataInsert->RegnoId;
						$id = $dataInsert;
						$this->session->unset_userdata('showAdd');
						$this->session->set_flashdata('message', 'Data berhasil disimpan, RegnoId = ' . $id);
						redirect('input/pemol/add');
                    }

                } else {
                    $dataInsert = $this->pemol_model->insert_data_api();
					// $id = $dataInsert->RegnoId;
					$id = $dataInsert;
					$this->session->unset_userdata('showAdd');
					$this->session->set_flashdata('message', 'Data berhasil disimpan, RegnoId = ' . $id);
					redirect('input/pemol/add');
                }
			}
		}
	}

	function edit($id)
	{
		$cat = $this->pemol_model->getWhere_data_api($id, 'regnoid')->Category;
		if ($cat == "Referral") {
			$this->session->set_userdata('show', 'show');
		}
		// $data['query'] = $this->pemol_model->getWhere_data_api($id, 'regnoid');
		$data = [
			'query'	       => $this->pemol_model->getWhere_data_api($id, 'regnoid'),
			'ref_source'   => $this->db->get('ref_source'),
			'ref_category' => $this->db->get('ref_category'),
			'ref_referal'  => $this->db->order_by('Referral', 'ASC')->get('ref_referal'),
			'ref_referal2'  => $this->pemol_model->get_referal()
		];

		$this->load->library('form_validation');
		$this->form_validation->set_rules('Account_Number', 'No Rekening', 'required|max_length[10]');
		$this->form_validation->set_rules('Source', 'Source', 'required');
		$this->form_validation->set_rules('Category', 'Kategori', 'required');
		if ($this->input->post('Category') == "Referral") {
			$this->form_validation->set_rules('Referral', 'Referral', 'required');
			$this->session->set_userdata('show', 'show');
		}
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			//load view
			$this->template->set('title', 'Edit Data Pemol');
			$this->template->load('template', 'pemol/edit', $data);
		} else {
			$this->pemol_model->edit_data_api($id);
			$this->session->unset_userdata('show');
			$this->session->set_flashdata('message', 'Data berhasil di edit');
			redirect('input/pemol');
		}
	}

	function detail($id)
	{
		$referral = $this->pemol_model->getWhere_data_api($id, 'regnoid')->Referral;
		// die;
		$data['referalChoice'] = $this->db->select('Description')->where('Referral', $referral)->get('ref_referal')->row()->Description;
		$data['query'] = $this->pemol_model->getWhere_data_api($id, 'regnoid');
		$data['id'] = $id;
		//load view
		$this->template->set('title', 'Detail');
		$this->template->load('template', 'pemol/detail', $data);
	}

	// public function edit_foto($id)
	// {
	// 	$data = $this->{$this->model}->get_by_id($id)->row_array();
	// 	echo json_encode($data);
	// }

	// public function simpan_foto()
	// {
	// 	//$this->uri->segment(3);
	// 	$id = $this->input->post('id');
	// 	$config['file_name'] = $this->set_file_name();
	// 	$config['upload_path'] = './temp_upload/temp/';
	// 	$config['allowed_types'] = 'jpg|jpeg|png';
	// 	$config['max_size'] = '10000';
	// 	//$config['encrypt_name'] = TRUE;
	// 	$this->load->library('upload', $config);
	// 	$this->upload->initialize($config);
	// 	$this->load->library('image_lib');

	// 	if ( ! $this->upload->do_upload('foto')){
	// 		// if upload fail, grab error 
	// 		//$data['message'] = $this->upload->display_errors();
	// 		$data['inputerror'][] = 'foto';
	// 		$data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
	// 		$data['status'] = FALSE;
	// 		echo json_encode($data);
	// 		exit();
	// 	}
	// 	else{

	// 		// otherwise, put the upload datas here.
	// 		// if you want to use database, put insert query in this loop
	// 		$upload_data = $this->upload->data();
	// 		$file_name = $upload_data['file_name'];
	// 		$image_info = getimagesize($upload_data['full_path']);
	// 		$image_width = $image_info[0];
	// 		$image_height = $image_info[1];
	// 		$new_width = $image_width*(60/100);
	// 		$new_height = $image_height*(60/100);

	// 		//latitude & longitude
	// 		if($this->input->post('image_type') == "selfie" ){
	// 		    $geoInfo = $this->get_image_location('./temp_upload/temp/'.$upload_data['file_name']);

	// 		    $imgLat = $geoInfo['latitude'];
	// 		    $imgLng = $geoInfo['longitude'];
	// 		    $geoLocation = $imgLat.','.$imgLng;
	// 		}
	// 		else{
	// 		    $geoLocation = '';
	// 		}

	// 		// set the resize config
	// 		$resize_conf = array(
	// 			// it's something like "/full/path/to/the/image.jpg" maybe
	// 			'source_image'  => $upload_data['full_path'], 
	// 			// and it's "/full/path/to/the/" + "thumb_" + "image.jpg
	// 			// or you can use 'create_thumbs' => true option instead
	// 			'new_image'     => './temp_upload/foto/'.$upload_data['file_name'],
	// 			'width'         => $new_width,
	// 			'height'        => $new_height
	// 		);

	// 		// initializing
	// 		$this->image_lib->initialize($resize_conf);

	// 		// do it!
	// 		if ( ! $this->image_lib->resize())
	// 		{
	// 			// if got fail.
	// 			$data['message'] = $this->image_lib->display_errors();
	// 			//remove temporary upload
	// 			unlink('./temp_upload/temp/'.$file_name);
	// 		}
	// 		else
	// 		{
	// 			if($this->input->post('image_type') == "selfie" ){
	// 				$data_insert = array(					
	// 					'foto_selfie'	=> $file_name,
	// 					'geo_info_selfie'	=> $geoLocation
	// 				);				
	// 			}
	// 			elseif($this->input->post('image_type') == "pemol" ){
	// 				$data_insert = array(					
	// 					'file_ss'	=> $file_name,
	// 					'geo_info_ss'	=> $geoLocation
	// 				);				
	// 			}
	// 			else{
	// 				$data_insert = array(					
	// 					'file_terima'	=> $file_name,
	// 					'geo_info_terima'	=> $geoLocation
	// 				);
	// 			}

	// 			$this->db->trans_start();
	// 			$this->{$this->model}->update($data_insert,$id);
	// 			//$id = $this->db->insert_id();				
	// 			$this->db->trans_complete();

	// 			//remove temporary upload
	// 			unlink('./temp_upload/temp/'.$file_name);

	// 			$data['message'] = "Data Tersimpan";
	// 		}
	// 		echo json_encode(array("status" => TRUE));

	// 	}
	// }

	// function upload($id1,$kategori)
	// {	
	// 	global $tombol;
	// 	$tombol = $this->input->post('submit');

	// 	if ($tombol == ""){

	// 		$data['query'] = $this->{$this->model}->get_all_list_detail($id1);		
	// 		$data['id'] = $id1;				 
	// 		$data['kategori'] = $kategori;
	// 		$this->template->set('title','Upload');
	// 		$this->template->load('template','funding_officer/pemol/upload',$data);	 
	// 	}
	// 	else
	// 	{
	// 		if($kategori == "selfie" ){	 		 
	// 			$config['file_name'] = $this->set_file_name_($id1,"selfie");
	// 		}
	// 		elseif($kategori == "pemol" ){	 		 
	// 			$config['file_name'] = $this->set_file_name_($id1,"pemol");
	// 		}
	// 		else{
	// 			$config['file_name'] = $this->set_file_name_($id1,"setoran");
	// 		}

	// 		$config['upload_path'] = './temp_upload/temp/';
	// 		$config['allowed_types'] = 'jpg|jpeg|png';
	// 		$config['max_size'] = '8000';
	// 		//$config['encrypt_name'] = TRUE;
	// 		$this->load->library('upload', $config);
	// 		$this->upload->initialize($config);
	// 		$this->load->library('image_lib');

	// 		if ( ! $this->upload->do_upload('file'))
	// 		{
	// 			// if upload fail, grab error 
	// 			//$data['message'] = $this->upload->display_errors();
	// 			$data['inputerror'][] = 'foto';
	// 			$data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
	// 			$data['status'] = FALSE;
	// 			echo json_encode($data);
	// 			exit();
	// 		}
	// 		else
	// 		{				
	// 			// otherwise, put the upload datas here.
	// 			// if you want to use database, put insert query in this loop
	// 			$upload_data = $this->upload->data();
	// 			$image_info = getimagesize($upload_data['full_path']);
	// 			$image_width = $image_info[0];
	// 			$image_height = $image_info[1];
	// 			$new_width = $image_width*(60/100);
	// 			$new_height = $image_height*(60/100);

	// 			//latitude & longitude
	// 			if($kategori == "selfie" ){
	// 			    $geoInfo = $this->get_image_location('./temp_upload/temp/'.$config['file_name']);				 
	// 			    $imgLat = $geoInfo['latitude'];
	// 			    $imgLng = $geoInfo['longitude'];
	// 			    $geoLocation = $imgLat.','.$imgLng;
	// 			}
	// 			else{
	// 			    $geoLocation = '';
	// 			}


	// 			// set the resize config
	// 			$resize_conf = array(
	// 				// it's something like "/full/path/to/the/image.jpg" maybe
	// 				'source_image'  => $upload_data['full_path'], 
	// 				// and it's "/full/path/to/the/" + "thumb_" + "image.jpg
	// 				// or you can use 'create_thumbs' => true option instead
	// 				'new_image'     => './temp_upload/foto/'.$config['file_name'],
	// 				'width'         => $new_width,
	// 				'height'        => $new_height
	// 			);

	// 			// initializing
	// 			$this->image_lib->initialize($resize_conf);
	// 			// $this->image_lib->initialize($resize_conf_tt);

	// 			// do it!
	// 			if ( ! $this->image_lib->resize())
	// 			{
	// 				// if got fail.
	// 				$data['message'] = $this->image_lib->display_errors();
	// 			}
	// 			else
	// 			{
	// 				$file_name = $upload_data['file_name'];
	// 				if($kategori == "selfie" ){
	// 					$data_insert = array(					
	// 						'foto_selfie'	=> $file_name,
	// 						'geo_info_selfie'	=> $geoLocation
	// 					);				
	// 				}
	// 				elseif($kategori == "pemol" ){
	// 					$data_insert = array(					
	// 						'file_ss'	=> $file_name,
	// 						'geo_info_ss'		=> $geoLocation
	// 					);				
	// 				}
	// 				else{
	// 					$data_insert = array(					
	// 						'file_terima'	=> $file_name,
	// 						'geo_info_terima'		=> $geoLocation
	// 					);
	// 				}
	// 				$this->{$this->model}->update($data_insert,$id1);
	// 				//$id = $this->db->insert_id();				
	// 				//$this->db->trans_complete();

	// 				//delete temporary upload
	// 				unlink('./temp_upload/temp/'.$file_name);

	// 				$data['message'] = "Data Tersimpan";
	// 			}
	// 			// unlink('./temp_upload/temp/'.$upload_data['file_name_tt']);
	// 			$this->session->set_flashdata('message', 'Data customer berhasil disimpan.');
	// 			redirect('pemol/detail/'.$id1);
	// 		}
	// 	}
	// }

	// function search()
	// {
	//     $data['id'] = 0;

	// 	$array = explode('?keyword=',  $_SERVER['REQUEST_URI']);
	// 	$data['key'] = $array[1];
	// 	$data['query'] = $this->{$this->model}->search($data['key']);
	// 	$data['search_count'] = $this->{$this->model}->search($data['key'])->num_rows();

	//     //load view
	// 	$this->template->set('title','Search Data');
	// 	$this->template->load('template','pemol/search',$data);
	// }

	// //================================================= INTERNAL FUNCTION =============================================//
	// private function set_file_name()
	// {
	// 	$foto = $this->input->post('id').'_'.$this->input->post('image_type');
	// 	if(!empty($_FILES['foto']['name']))
	// 	{
	// 		$file = $_FILES['foto']['name'];
	// 		$gabung = str_replace(" ","_","$file");
	// 		$pisah = explode(".",$gabung);
	// 		$temp = time();
	// 		$ext = end($pisah);
	// 	}
	// 	else
	// 	{
	// 		$temp = '';
	// 		$ext = '';
	// 	}
	// 	return $foto.'_'.$temp.'.'.$ext;
	// }

	// private function set_file_name_($id1,$foto)
	// {

	// 	if(!empty($_FILES['file']['name']))
	// 	{
	// 		$file = $_FILES['file']['name'];
	// 		$gabung = str_replace(" ","_","$file");
	// 		$pisah = explode(".",$gabung);
	// 		$temp = time();
	// 		$ext = end($pisah);
	// 	}
	// 	else
	// 	{
	// 		$temp = '';
	// 		$ext = '';
	// 	}
	// 	return $id1.'_'.$foto.'_'.$temp.'.'.$ext;
	// }

	// private function get_image_location($image = ''){
	// 	$exif = exif_read_data($image, 0, true);
	// 	if($exif && isset($exif['GPS'])){
	// 		$GPSLatitudeRef = $exif['GPS']['GPSLatitudeRef'];
	// 		$GPSLatitude    = $exif['GPS']['GPSLatitude'];
	// 		$GPSLongitudeRef= $exif['GPS']['GPSLongitudeRef'];
	// 		$GPSLongitude   = $exif['GPS']['GPSLongitude'];

	// 		$lat_degrees = count($GPSLatitude) > 0 ? $this->gps2Num($GPSLatitude[0]) : 0;
	// 		$lat_minutes = count($GPSLatitude) > 1 ? $this->gps2Num($GPSLatitude[1]) : 0;
	// 		$lat_seconds = count($GPSLatitude) > 2 ? $this->gps2Num($GPSLatitude[2]) : 0;

	// 		$lon_degrees = count($GPSLongitude) > 0 ? $this->gps2Num($GPSLongitude[0]) : 0;
	// 		$lon_minutes = count($GPSLongitude) > 1 ? $this->gps2Num($GPSLongitude[1]) : 0;
	// 		$lon_seconds = count($GPSLongitude) > 2 ? $this->gps2Num($GPSLongitude[2]) : 0;

	// 		$lat_direction = ($GPSLatitudeRef == 'W' or $GPSLatitudeRef == 'S') ? -1 : 1;
	// 		$lon_direction = ($GPSLongitudeRef == 'W' or $GPSLongitudeRef == 'S') ? -1 : 1;

	// 		$latitude = $lat_direction * ($lat_degrees + ($lat_minutes / 60) + ($lat_seconds / (60*60)));
	// 		$longitude = $lon_direction * ($lon_degrees + ($lon_minutes / 60) + ($lon_seconds / (60*60)));

	// 		return array('latitude'=>$latitude, 'longitude'=>$longitude);
	// 	}else{
	// 		return false;
	// 	}
	// }

	// private function gps2Num($coordPart){
	// 	$parts = explode('/', $coordPart);
	// 	if(count($parts) <= 0)
	// 	    return 0;
	// 	if(count($parts) == 1)
	// 	    return $parts[0];
	// 	    return floatval($parts[0]) / floatval($parts[1]);
	// }

	// private function hitungJarak($lat1, $long1, $lat2, $long2, $unit = 'km', $desimal = 2)
	// {
	// 	// Menghitung jarak dalam derajat
	// 	if($lat1 != '' && $long1 != '' && $lat2 != '' && $long2 != ''){
	// 		$derajat = rad2deg(acos((sin(deg2rad($lat1))*sin(deg2rad($lat2))) + (cos(deg2rad($lat1))*cos(deg2rad($lat2))*cos(deg2rad($long1-$long2)))));
	// 		// Mengkonversi derajat kedalam unit yang dipilih (kilometer, mil atau mil laut)
	// 		switch($unit){
	// 			case 'km':
	// 				$jarak = $derajat * 111.13384; // 1 derajat = 111.13384 km, berdasarkan diameter rata-rata bumi (12,735 km)
	// 			break;
	// 			case 'mi':
	// 				$jarak = $derajat * 69.05482; // 1 derajat = 69.05482 miles(mil), berdasarkan diameter rata-rata bumi (7,913.1 miles)
	// 			break;
	// 			case 'nmi':
	// 				$jarak =  $derajat * 59.97662; // 1 derajat = 59.97662 nautic miles(mil laut), berdasarkan diameter rata-rata bumi (6,876.3 nautical miles)
	// 		}
	// 		return round($jarak, $desimal);
	// 	}
	// 	else{
	// 		return "Undefined";
	// 	}
	// }

	// //check duplicate by nomor rekening
	// function duplicate_rekening($str)
	// {
	// 	$this->db->select('no_rek');
	// 	$this->db->where(array('no_rek'=>$str));
	// 	$this->db->from('tbl_kartu');
	// 	$query = $this->db->get();

	//     // Is there a row with this name?
	//     if ($query->num_rows() > 0)
	//     {
	//         // Let's return false for the validation and set a custom message for this function
	//         $this->form_validation->set_message('duplicate_rekening', 'Nomor rekening sudah ada.');
	//         return FALSE;
	//     }
	//     else
	//     {
	//         // Everything is good, don't return an error.
	//         return TRUE;
	//     }
	// }

	//check duplicate by nomor rekening
	function duplicate_account($str)
	{
		$query = $this->pemol_model->getWhere_data_api($str, 'account_number');

		// Is there a row with this name?
		if ($query->total > 0) {
			// Let's return false for the validation and set a custom message for this function
			$this->form_validation->set_message('duplicate_account', 'Nomor rekening sudah ada.');
			return FALSE;
		} else {
			// Everything is good, don't return an error.
			return TRUE;
		}
	}
}
