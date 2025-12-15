<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require_once APPPATH.'third_party/spout/src/Spout/Autoloader/autoload.php';

//use Box\Spout\Reader\ReaderFactory;
//use Box\Spout\Common\Type;

class Qris extends MY_Controller
{	

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url','file','download'));
		$this->load->library(array('template'));
		$this->load->model(array('input/qris_model'));
	}
	function index()
    {
    	$status = $this->uri->segment(4);
    	$this->session->set_userdata('status', $status);

        //load view
		$this->template->set('title','Data QRIS');
		$this->template->load('template','qris/index');
    }	
	
	function get_data_qris()
    {
		$status = $this->session->userdata('status');
		$sales_code = $this->session->userdata('username');
		
        if($status == 'new_merchant') {
            $where = "Product_Type = 'QRIS' AND Sales_Code = '$sales_code' AND (Hit_Code IN ('101','107') OR (Hit_Code = '102' AND Status = ''))";
        }
        else {
            $where = "Product_Type = 'QRIS' AND Sales_Code = '$sales_code' AND (Hit_Code = '103' OR Hit_Code = '104')";
        }
		
		$query = $this->qris_model->_get_dataqris_query($where);
		
        $data = array();
        $no = $this->input->post['start'];
        foreach ($query as $row){
        	$hit_code = $row->Hit_Code;
        	if($hit_code == '101') {
        		$status_aplikasi ='<span style = "color:red">Lengkapi Dokumen, <br>& klik Submit to APP !</span>';
        	}
        	elseif ($hit_code == '102') {
        		$status_aplikasi ='<span class="label label-info">Pending APP</span>';
        	}
        	else {
        		$status_aplikasi ='<span class="label label-warning">Return</span>';
        	}

        	$action = '<a href="'.site_url()."input/qris/upload_dokumen/".$row->RegnoId.'" ><span class="btn btn-xs btn-warning"><i class="fa fa-md fa-th-list" title="View Detail"></i></span></a>';

		    $data[] = array(
				++$no,
				$row->Owner_Name,
				$row->Merchant_Name,
				$row->Mobile_Phone_Number.' / '.$row->Other_Phone_Number,
				$row->MID_Type,
				$row->Email,
				$row->Account_Number,
				$row->Officer_Code,
				$status_aplikasi,
				$action
			);
        }
 
        $output = array(
            "draw" => $this->input->post['draw'],
            "recordsTotal" => $this->qris_model->_count_dataqris($where),
            "recordsFiltered" => $this->qris_model->_count_dataqris($where),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
	
	function add()
	{
		$owner_name 	= $this->input->post('owner_name');
		$merchant_name 	= $this->input->post('merchant_name');
		$email 			= $this->input->post('email');
		$no_hp 			= $this->input->post('no_hp');
		$officer_code 	= $this->input->post('officer_code');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('owner_name', 'Nama Owner', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);
		$this->form_validation->set_rules('merchant_name', 'Nama Merchant', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);
		$this->form_validation->set_rules('mid_type', 'Status Merchant', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);
		$this->form_validation->set_rules('account_number', 'No Rekening', 'trim|required|callback_check_norek');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|callback_check_email');
		$this->form_validation->set_rules('mobile_phone_number', 'No Handphone', 'trim|required|callback_valid_number');
		$this->form_validation->set_rules('officer_code', 'Area Akusisi', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);
		
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['area_akusisi'] = $this->qris_model->get_area_akusisi();
			$this->template->set('title','Add Customer');
			$this->template->load('template','qris/add', $data);
		}
		else
		{
			$data = array(
				'owner_name'			=> $this->input->post('owner_name'),
				'merchant_name'			=> $this->input->post('merchant_name'),
				'email'					=> $this->input->post('email'),
				'mid_type'				=> $this->input->post('mid_type'),
				'account_number'		=> $this->input->post('account_number'),
				'mobile_phone_number'	=> $this->input->post('mobile_phone_number'),
				'other_phone_number'	=> $this->input->post('other_phone_number'),
				'officer_code'			=> $this->input->post('officer_code'),
				'sales_code'			=> $this->input->post('sales_code'),
				'sales_name'			=> $this->input->post('sales_name'),
				'branch'				=> $this->input->post('branch'),
				'created_by'			=> $this->input->post('created_by')
			);
			
			$this->qris_model->insert_data_api($data);
			$id = $this->session->userdata('RegnoId');
			
			$this->session->set_flashdata('message', "<span class='btn btn-info'><b>ID APLIKASI : <label style='color:red'>".$id."</label>, Mohon untuk dicatat di aplikasi!</b></span>");

			//Direct ke view
			redirect('input/qris/upload_dokumen/'.$id);
		
		}
	}
	
	function upload_dokumen($id) {
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('account_number', 'No Rekening', 'trim|required|callback_check_norek_update');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|callback_check_email_update');
		$this->form_validation->set_rules('mobile_phone_number', 'No Handphone', 'trim|required|callback_valid_number');
		
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['area_akusisi'] = $this->qris_model->get_area_akusisi();
			$data['db'] = $this->qris_model->get_by_id($id);
			$data['query_ktp'] = $this->qris_model->get_ktp($id);
			$data['query_merchant'] = $this->qris_model->get_merchant($id);
			$data['query_product'] = $this->qris_model->get_product($id);
			$data['query_npwp'] = $this->qris_model->get_npwp($id);
			
			//view
			$this->template->set('title','Upload Dokumen');
			$this->template->load('template','qris/upload_dokumen', $data);
		}
		else
		{
					
			$this->qris_model->update_data_api($id);
			$id = $this->session->userdata('RegnoId');
				
			$this->session->set_flashdata('message', "<span class='btn btn-info'><b>Data Berhasil Diupdate!</b></span>");

			//Direct ke view
			redirect('input/qris/upload_dokumen/'.$id);
		}
	}
	
	function save_dokumen($customer_id)
	{
		$config['upload_path']   = './upload/temp/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size']      = '5000';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('image_name')){
			// if upload fail, grab error
			$this->session->set_flashdata('message', "<span class='btn btn-danger'><b>".$this->upload->display_errors()."</b></span>");
			//Direct ke view
			redirect('input/qris/upload_dokumen/'.$customer_id);
		}
		else{
			$upload_data = $this->upload->data();
			$file = $upload_data['full_path'];
			$mime = mime_content_type($file);
			$info = pathinfo($file);
			$name = $info['basename'];
			$output = new CURLFile($file, $mime, $name);
			$data = array(
				'customer_id'		=> $this->input->post('customer_id'),
				'category_id'		=> $this->input->post('category_id'),
				'category_name'		=> $this->input->post('category_name'),
				'file'				=> $output
			);
			
			$query = $this->qris_model->upload_document_api($data);
			
			//delete temporary file
			unlink($upload_data['full_path']);

			//Direct ke view
			redirect('input/qris/upload_dokumen/'.$customer_id);
		}
	}
	
	function save_dokumen_copy($customer_id, $doc_id)
	{
		//echo $doc_id.' - '.$customer_id;die;
		
		$config['file_name'] = $this->set_file_name();
		$config['upload_path'] = '/var/www/html/development/public_html/merchant/upload/temp/';
		
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = '5000';
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		$this->load->library('image_lib');
		
		if ( ! $this->upload->do_upload('image_name'))
		{
			// if upload fail, grab error 
			$data['error'] = $this->upload->display_errors();
			//$data['link_back'] = anchor ('customer/add', 'Kembali ke form input', array('class'=>'back'));

			$this->session->set_flashdata('message', "<span style='font-size:14px; text-align:center' class='col-lg-3 alert alert-danger alert-dismissable'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<b>Dokumen gagal diupload!</b>
					</button>
			</span>");
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
		
			//latitude & longitude
			$geoInfo = $this->get_image_location('/var/www/html/development/public_html/qris/upload/temp/'.$upload_data['file_name']);
			
			$imgLat = $geoInfo['latitude'];
			$imgLng = $geoInfo['longitude'];
			$geoLocation = $imgLat.','.$imgLng;
			
			// set the resize config
			$resize_conf = array(
				'source_image'  => $upload_data['full_path'], 
				'new_image'     => '/var/www/html/development/public_html/qris/upload/dokumen/'.$upload_data['file_name'],
				
				'width'         => $new_width,
				'height'        => $new_height
			);

			// initializing
			$this->image_lib->initialize($resize_conf);
			
			// do it!
			if ( ! $this->image_lib->resize())
			{
				// if upload fail, grab error 
				$data['error'] = $this->upload->display_errors();

				$this->session->set_flashdata('message', "<span style='font-size:14px; text-align:center' class='col-lg-3 alert alert-danger alert-dismissable'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<b>Dokumen gagal diupload!!</b>
					</button>
				</span>");
			}
			else
			{
				$file_name = $upload_data['file_name'];

				$category_name = $this->input->post('category_name');
				
				$this->db->trans_start();
				$this->qris_model->add_document_api($doc_id, $customer_id, $file_name, $geoLocation, $category_name);
				$id = $this->session->userdata('RegnoId');
				
				$this->db->trans_complete();
			}
			unlink('/var/www/html/development/public_html/merchant/upload/temp/'.$upload_data['file_name']);

			$this->session->set_flashdata('message', "<span class='btn btn-info'><b>Dokumen Berhasil Diupload!</b></span>");
		}

		//Direct ke view
		redirect('input/qris/upload_dokumen/'.$id);	
	}	

	function edit_document($doc_id) {
		$data['db'] = $this->qris_model->get_doc($doc_id);
		//view
		$this->template->set('title','Edit Dokumen');
		$this->template->load('template','qris/edit_dokumen', $data);
	}
	
	function update_document($customer_id)
	{
		$config['upload_path']   = './upload/temp/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size']      = '5000';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('image_name')){
			// if upload fail, grab error
			$this->session->set_flashdata('message', "<span class='btn btn-danger'><b>".$this->upload->display_errors()."</b></span>");
			//Direct ke view
			redirect('input/qris/upload_dokumen/'.$customer_id);
		}
		else{
			$upload_data = $this->upload->data();
			$file = $upload_data['full_path'];
			$mime = mime_content_type($file);
			$info = pathinfo($file);
			$name = $info['basename'];
			$output = new CURLFile($file, $mime, $name);
			$data = array(
				'doc_id'			=> $this->input->post('doc_id'),
				'customer_id'		=> $this->input->post('customer_id'),
				'category_id'		=> $this->input->post('category_id'),
				'category_name'		=> $this->input->post('category_name'),
				'image_old'			=> $this->input->post('image_old'),
				'file'				=> $output
			);
			
			$query = $this->qris_model->edit_document_api($data);
			//$response = json_decode($query);
			//$this->session->set_flashdata('message', "<span class='btn btn-info'><b>".$response->message."</b></span>");
			$this->session->set_flashdata('message', "<span class='btn btn-info'><b>Dokumen Berhasil Diupdate!</b></span>");
			
			//delete temporary file
			unlink($upload_data['full_path']);

			//Direct ke view
			redirect('input/qris/upload_dokumen/'.$customer_id);
		}
	}
	
	function update_document_old($doc_id, $customer_id)
	{
		//echo $doc_id.' - '.$customer_id;die;
		
		$config['file_name'] = $this->set_file_name();
		$config['upload_path'] = '/var/www/html/development/public_html/sales_monitoring/upload/temp/';
		//$config['upload_path'] = '../merchant/upload/temp/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = '5000';
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		$this->load->library('image_lib');
		
		if ( ! $this->upload->do_upload('image_name'))
		{
			// if upload fail, grab error 
			//var_dump($this->upload->display_errors());die;
			$data['error'] = $this->upload->display_errors();
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
		
			//latitude & longitude
			$geoInfo = $this->get_image_location('/var/www/html/development/public_html/sales_monitoring/upload/temp/'.$upload_data['file_name']);
			//$geoInfo = $this->get_image_location('../merchant/upload/temp/'.$upload_data['file_name']);
			$imgLat = $geoInfo['latitude'];
			$imgLng = $geoInfo['longitude'];
			$geoLocation = $imgLat.','.$imgLng;
			
			// set the resize config
			$resize_conf = array(
				'source_image'  => $upload_data['full_path'], 
				'new_image'     => '/var/www/html/development/public_html/sales_monitoring/upload/dokumen/'.$upload_data['file_name'],
				'width'         => $new_width,
				'height'        => $new_height
			);

			// initializing
			$this->image_lib->initialize($resize_conf);
			
			// do it!
			if ( ! $this->image_lib->resize())
			{
				//echo "ini error di else + ".$doc_id;die;
				// if upload fail, grab error 
				$data['error'] = $this->upload->display_errors();
			}
			else
			{
					$file_name = $upload_data['file_name'];
					
					$this->db->trans_start();
					$this->qris_model->update_document_api($doc_id, $customer_id, $file_name, $geoLocation);
					
					$this->db->trans_complete();
			}
			unlink('./upload/temp/'.$upload_data['file_name']);
			unlink('./upload/dokumen/'.$this->input->post('image_old'));
			
			$id = $this->session->userdata('RegnoId');

			$this->session->set_flashdata('message', "<span class='btn btn-info'><b>Dokumen Berhasil Diupdate!</b></span>");
		}
		
		redirect('input/qris/upload_dokumen/'.$id);
	}

	function submit_aplikasi($customer_id) {
		$q = $this->db->query("SELECT * FROM `internal`.`edc_merchant` WHERE RegnoId = '$customer_id'");
		$r = $q->row();
		$hit_code = $r->Hit_Code;
		$st = $r->Status; //status_aplikasi old
		if($hit_code == '101') {
			$new_hitcode = '102';
			$status_aplikasi = '';
		}
		else {
			$new_hitcode = '107';
			$status_aplikasi = $st;
		}

		//update data_customers status //
		$data_update = array(
			'Status'		=> $status_aplikasi,
			'Submit_Date'			=> date('Y-m-d H:i:s'),
			'Status_Aplikasi_Date'	=> date('Y-m-d H:i:s'),
			'Decision_Date'			=> date('Y-m-d H:i:s'),
			'Hit_Code'				=> $new_hitcode
		);
		$this->db->where('RegnoId', $customer_id);
		$this->db->update('`internal`.`edc_merchant`',$data_update);
		
    	$data_customers_log = array(
			'customer_id'	=> $customer_id,
			'hit_code'		=> $new_hitcode,
			'description'	=> 'submit',
			'updated_date'	=> date('Y-m-d H:i:s'),
			'updated_by'	=> $this->session->userdata('realname')
		);
		$this->db->insert('`internal`.`edc_merchant_log`',$data_customers_log);

		$this->session->set_flashdata('message', "<span class='btn btn-info'><b>Data Berhasil Disubmit!</b></span>");

    	redirect('input/qris/index/new_merchant');
	}
	
	function delete_npwp($regnoid, $npwp_id) {
		//delete dokumen npwp //
		
		$this->qris_model->delete_npwp_api($regnoid, $npwp_id);
				
		$this->session->set_flashdata('message', "<span class='btn btn-info'><b>Dokumen Berhasil Dihapus!</b></span>");
		$customer_id = $this->session->userdata('RegnoId');

    	redirect('input/qris/upload_dokumen/'.$customer_id);
	}
/*
	function view($id) {
		//$date_from = $this->session->set_userdata('date_from');
		//$date_to = $this->session->set_userdata('date_to');
		//$sales_code = $this->session->set_userdata('sales_code');
		$data['db'] = $this->qris_model->edit($id)->row();
		$this->template->load('template','merchant/view', $data);
	}
*/





	//set_file_name
	private function set_file_name()
	{
	    $id = $this->input->post('customer_id');
		$foto = $this->input->post('category_name');
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
	
	function valid_number($str)
	{
		// VALID FORMAT = 08xxxxxxxx
		$valid_format = substr($str,0,2);
		
		if ($valid_format != '08'){
            // Let's return false for the validation and set a custom message for this function
            $this->form_validation->set_message('valid_number', 'Nomor handphone harus diawali 08');
            return FALSE;
        }
        else{
            // Everything is good, don't return an error.
            return TRUE;
        }
	}

	/**
	 * get_image_location
	 * Returns an array of latitude and longitude from the Image file
	 * @param $image file path
	 * @return multitype:array|boolean
	 */
	private function get_image_location($image = ''){
		$exif = exif_read_data($image, 0, true);
		if($exif && isset($exif['GPS'])){
			$GPSLatitudeRef = $exif['GPS']['GPSLatitudeRef'];
			$GPSLatitude    = $exif['GPS']['GPSLatitude'];
			$GPSLongitudeRef= $exif['GPS']['GPSLongitudeRef'];
			$GPSLongitude   = $exif['GPS']['GPSLongitude'];
			
			$lat_degrees = count($GPSLatitude) > 0 ? $this->gps2Num($GPSLatitude[0]) : 0;
			$lat_minutes = count($GPSLatitude) > 1 ? $this->gps2Num($GPSLatitude[1]) : 0;
			$lat_seconds = count($GPSLatitude) > 2 ? $this->gps2Num($GPSLatitude[2]) : 0;
			
			$lon_degrees = count($GPSLongitude) > 0 ? $this->gps2Num($GPSLongitude[0]) : 0;
			$lon_minutes = count($GPSLongitude) > 1 ? $this->gps2Num($GPSLongitude[1]) : 0;
			$lon_seconds = count($GPSLongitude) > 2 ? $this->gps2Num($GPSLongitude[2]) : 0;
			
			$lat_direction = ($GPSLatitudeRef == 'W' or $GPSLatitudeRef == 'S') ? -1 : 1;
			$lon_direction = ($GPSLongitudeRef == 'W' or $GPSLongitudeRef == 'S') ? -1 : 1;
			
			$latitude = $lat_direction * ($lat_degrees + ($lat_minutes / 60) + ($lat_seconds / (60*60)));
			$longitude = $lon_direction * ($lon_degrees + ($lon_minutes / 60) + ($lon_seconds / (60*60)));
	
			return array('latitude'=>$latitude, 'longitude'=>$longitude);
		}else{
			return array('latitude'=>'', 'longitude'=>'');
		}
	}
	
	private function gps2Num($coordPart){
		$parts = explode('/', $coordPart);
		if(count($parts) <= 0)
		    return 0;
		if(count($parts) == 1)
		    return $parts[0];
		    return floatval($parts[0]) / floatval($parts[1]);
	}
	
	function check_norek($str)
	{
		$type = $this->input->post('mid_type');
		if ($type == 'NEW MERCHANT'){
			$query = $this->db->query("select * from internal.edc_merchant where Account_Number='$str' and Expire='0' AND Status NOT IN('CANCEL','REJECT')");
		}
		else {
			$query = $this->db->query("select * from internal.edc_merchant where Account_Number='xxxxxxxxxx'");
		}
		
		if ($query->num_rows() > 0){
			// Let's return false for the validation and set a custom message for this function
			$this->form_validation->set_message('check_norek', 'Maaf, No Rekening yang anda masukan sudah terdaftar!');
			return FALSE;
		}
		else{
			// Everything is good, don't return an error.
			return TRUE;
		}
	}
	
	function check_norek_update($str)
	{
		$id = $this->input->post('RegnoId');
		$type = $this->input->post('mid_type');
		if ($type == 'NEW MERCHANT'){
			$query = $this->db->query("SELECT * FROM internal.edc_merchant WHERE RegnoId != '$id' AND Account_Number='$str' and Expire='0' AND Status NOT IN('CANCEL','REJECT')");
		}
		else {
			$query = $this->db->query("SELECT * FROM internal.edc_merchant WHERE Account_Number='xxxxxxxxxx'");
		}
		
		if ($query->num_rows() > 0){
			// Let's return false for the validation and set a custom message for this function
			$this->form_validation->set_message('check_norek_update', 'Maaf, No Rekening yang anda masukan sudah terdaftar!');
			return FALSE;
		}
		else{
			// Everything is good, don't return an error.
			return TRUE;
		}
	}
	
	function check_email($str) {
		if($str == '') {
            // Let's return false for the validation and set a custom message for this function
            $this->form_validation->set_message('check_email', 'Email Wajib diisi, tidak boleh kosong!');
			return FALSE;
		}
		else {
			//$q = $this->db->query("SELECT email FROM `db_qris`.`data_customers` WHERE email = '$str'");
			//$r = $q->num_rows();
			$query = $this->db->query("SELECT Email FROM `internal`.`edc_merchant` WHERE Email = '$str'");
			$row = $query->num_rows();
			if($row > 0) {
				// Let's return false for the validation and set a custom message for this function
				$this->form_validation->set_message('check_email', 'Maaf, Email yang anda masukan sudah terdaftar!');
				return FALSE;
			}
			else {
				return TRUE;
			}

		}	
	}
	
	function check_email_update($str) {
		$id = $this->uri->segment(4);
		if($str == '') {
            // Let's return false for the validation and set a custom message for this function
            $this->form_validation->set_message('check_email_update', 'Email Wajib diisi, tidak boleh kosong!');
			return FALSE;
		}
		else {
			//$q = $this->db->query("SELECT email FROM `db_qris`.`data_customers` WHERE email = '$str'");
			//$r = $q->num_rows();
			$query = $this->db->query("SELECT Email FROM `internal`.`edc_merchant` WHERE RegnoId != '$id' AND Email = '$str'");
			$row = $query->num_rows();
			if($row > 0) {
				// Let's return false for the validation and set a custom message for this function
				$this->form_validation->set_message('check_email_update', 'Maaf, Email yang anda masukan sudah terdaftar!');
				return FALSE;
			}
			else {
				return TRUE;
			}

		}	
	}

}