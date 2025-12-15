<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welma extends MY_Controller
{
	private $model = 'input/welma_model';
	private $limit = 20;
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('template'));
		$this->load->helper(array('form', 'url', 'html'));
		$this->load->model($this->model);
		//$this->db2 = $this->load->database('db_welma', TRUE);
		//  pending dulu   $this->welma_model->config('data_pemol', 'RegnoId');
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
		$this->template->set('title', 'Data W');
		$this->template->load('template', 'welma/index');
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
				//dsr masuk ke sini
				$where = "t1.Sales_Code = '$nik' AND t1.Input_Date >= '$date_from' AND t1.Input_Date <= '$date_to'";
			}
		}

		$query = $this->welma_model->_get_datawelma_query($nik, $where);

		// $query = $this->welma_model->_get_datapemol_query($nik, $where);
		//cekdb();
		$data = array();
		$no = $this->input->post('start');
		foreach ($query as $row) {
			$data[] = array(
				'<div style="color:#FFFFFF;font-size:12px;font-family: Arial;"><strong>' . ++$no . '</strong></div>',
				'<div style="color:#FFFFFF;font-size:12px;font-family: Arial;">
					<i class="fa-fw fa fa-building"></i> <strong>Customer Name &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : ' . $row->Customer_Name . ' </strong><br>						
					<i class="fa-fw fa fa-building"></i> <strong>Email &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : ' . $row->Email . ' </strong><br>						
					<i class="fa-fw fa fa-building"></i> <strong>Phone Number &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : ' . $row->Phone_Number . ' </strong><br>						
					<i class="fa-fw fa fa-building"></i> <strong>Kode Promo &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : ' . $row->Kode_Promo . ' </strong><br>						
					<i class="fa-fw fa fa-calendar"></i> <strong>Tanggal Input : ' . $row->Input_Date . '</strong></i><br>
					<i class="pull-right">
						<i class="fa-fw fa fa-user"></i> <strong>Nama Sales : ' . $row->Sales_Name . ' (' . $row->Sales_Code . ') </strong>
					</i><br>
					<span class="pull-right">
						<a href="' . site_url('input/welma/detail/' . $row->Customer_ID) . '" class="btn btn-primary btn-xs"><i class="fa fa-building"></i></a>						
						<a href="' . site_url('input/welma/edit/' . $row->Customer_ID) . '" class="btn btn-danger btn-xs"><i class="fa fa-edit"></i></a>						
					</span>
				</div>'
			);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->welma_model->_count_datawelma($nik, $where),
			"recordsFiltered" => $this->welma_model->_count_datawelma($nik, $where),
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
	function addd()
	{
		$data['promo'] = $this->welma_model->get_promo()->result();
		$this->template->set('title', 'Input Data Welma');
		$this->template->load('template', 'welma/add', $data);
	}



	function add()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('Customer_Name', 'Nama Customer', 'required');
		//$this->form_validation->set_rules('Phone_Number', 'Nomor Handphone', 'required|min_length[10]|callback_duplicate_phone');
		$this->form_validation->set_rules('Email', 'Email', 'required|valid_email|callback_duplicate_email');
		$this->form_validation->set_rules('Kode_Promo', 'Kode Promo', 'required');
		$this->form_validation->set_error_delimiters('<span style="color:#FF0000">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			//load view
			$data['promo'] = $this->welma_model->getPromo_data_api();
			// cekvar($data['promo']);
		
			$this->template->set('title', 'Input Data Welma');
			$this->template->load('template', 'welma/add', $data);
		} else {
			$dataInsert = $this->welma_model->insert_data_api();
			$id = $dataInsert->Customer_ID;
			$this->session->set_flashdata('message', 'Data berhasil disimpan, CudtomerID = ' . $id);
			redirect('input/welma');
		}
	}

	// function insert_welma()
	// {
	// 	//echo ($this->input->post('Kode_Promo'));die();

	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('Customer_Name', 'Nama Customer', 'required|max_length[30]');
	// 	$this->form_validation->set_rules('Phone_Number', 'Nomor Handphone', 'required|min_length[10]|callback_duplicate_phone');
	// 	$this->form_validation->set_rules('Email', 'Email', 'required|valid_email|callback_duplicate_email');
	// 	$this->form_validation->set_rules('Kode_Promo', 'Kode Promo', 'required');
	// 	$this->form_validation->set_error_delimiters('<span style="color:#FF0000">', '</span>');

	// 	if ($this->form_validation->run() == FALSE) {
	// 		//load view
	// 		$data['promo'] = $this->welma_model->get_promo()->result();
	// 		$this->template->set('title', 'Input Data Welma');
	// 		$this->template->load('template', 'welma/add', $data);
	// 	} else {
	// 		$data = array(
	// 			'Customer_Name' => $this->input->post('Customer_Name'),
	// 			'Phone_Number' => $this->input->post('Phone_Number'),
	// 			'Email' => $this->input->post('Email'),
	// 			'Kode_Promo' => $this->input->post('Kode_Promo'),
	// 			'Sales_Code'	 => $this->input->post('Sales_Code'),
	// 			'Sales_Name'	 => $this->input->post('Sales_Name'),
	// 			'Branch'		 => $this->input->post('Branch'),
	// 			'Created_Date'	 => $this->input->post('Created_Date')
	// 		);

	// 		$dataInsert = $this->welma_model->input_data($data);
	// 		//$id = $dataInsert->Customer_ID;
	// 		$this->session->set_flashdata('message', 'Data berhasil disimpan');

	// 		$this->template->set('title', 'Input Data Welma');
	// 		$this->template->load('template', 'welma/add');

	// 		redirect('input/welma/add');
	// 	}
	// }

	// public function emailExist($email)
	// {
	// 	$query = $this->welma_model->emailExist($email);

	// 	if ($query) {
	// 		$this->form_validation->set_message(
	// 			'emailExist',
	// 			'Email sudah ada'
	// 		);
	// 		return false;
	// 	} else {
	// 		return true;
	// 	}
	// }

	// public function phoneExist($phone)
	// {
	// 	$query = $this->welma_model->phoneExist($phone);

	// 	if ($query) {
	// 		$this->form_validation->set_message(
	// 			'phoneExist',
	// 			'Nomor Handphone sudah ada .'
	// 		);
	// 		return false;
	// 	} else {
	// 		return true;
	// 	}
	// }

	// function edit($id)
	// {
	// 	$data['query'] = $this->welma_model->getWhere_data_api($id, 'Customer_ID');
	// 	$data['promo'] = $this->welma_model->get_promo()->result();
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('Customer_Name', 'Nama Customer', 'required|max_length[30]');
	// 	$this->form_validation->set_rules('Phone_Number', 'Nomor Handphone', 'required|min_length[10]');
	// 	$this->form_validation->set_rules('Email', 'Email', 'required|valid_email');
	// 	$this->form_validation->set_rules('Kode_Promo', 'Kode Promo', 'required');
	// 	$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

	// 	if ($this->form_validation->run() == FALSE) {
	// 		//load view
	// 		$this->template->set('title', 'Edit Data Welma');
	// 		$this->template->load('template', 'welma/edit', $data);
	// 	} else {
	// 		$data = array(
	// 			'Customer_Name' => $this->input->post('Customer_Name'),
	// 			'Phone_Number' => $this->input->post('Phone_Number'),
	// 			'Email' => $this->input->post('Email'),
	// 			'Kode_Promo' => $this->input->post('Kode_Promo'),
	// 			'Sales_Code'	 => $this->input->post('Sales_Code'),
	// 			'Sales_Name'	 => $this->input->post('Sales_Name'),
	// 			'Branch'		 => $this->input->post('Branch'),
	// 			'Created_Date'	 => $this->input->post('Created_Date'),

	// 		);
	// 		$this->welma_model->edit_data_api($id);
	// 		$this->session->set_flashdata('message', 'Data berhasil di edit');
	// 		redirect('input/welma');
	// 	}
	// }
	function edit($id)
	{
		$data['query'] = $this->welma_model->getWhere_data_api($id, 'Customer_ID');
		$data['promo'] = $this->welma_model->getPromo_data_api();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('Customer_Name', 'Nama Customer', 'required');
		//$this->form_validation->set_rules('Phone_Number', 'Nomor Handphone', 'required|min_length[10]');
		$this->form_validation->set_rules('Email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('Kode_Promo', 'Kode Promo', 'required');
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

		if ($this->form_validation->run() == FALSE) {
			//load view
			$this->template->set('title', 'Edit Data Welma');
			$this->template->load('template', 'welma/edit', $data);
		} else {
			$this->welma_model->edit_data_api($id);
			$this->session->set_flashdata('message', 'Data berhasil di update');
			redirect('input/welma');
		}
	}

	function detail($Customer_ID)
	{
		$data['query'] = $this->welma_model->getWhere_data_api($Customer_ID, 'Customer_ID');
		$data['Customer_ID'] = $Customer_ID;
		//load view
		$this->template->set('title', 'Detail');
		$this->template->load('template', 'welma/detail', $data);
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

	//check duplicate by email
	function duplicate_email($str)
	{
		$query = $this->welma_model->getWhere_data_api($str, 'Email');

		// Is there a row with this name?
		if ($query->total > 0) {
			// Let's return false for the validation and set a custom message for this function
			$this->form_validation->set_message('duplicate_email', 'Email sudah ada.');
			return FALSE;
		} else {
			// Everything is good, don't return an error.
			return TRUE;
		}
	}

	//
	function duplicate_phone($str)
	{
		$query = $this->welma_model->getWhere_data_api($str, 'Phone_Number');

		// Is there a row with this name?
		if ($query->total > 0) {
			// Let's return false for the validation and set a custom message for this function
			$this->form_validation->set_message('duplicate_phone', 'Nomor HP sudah ada.');
			return FALSE;
		} else {
			// Everything is good, don't return an error.
			return TRUE;
		}
	}
}
