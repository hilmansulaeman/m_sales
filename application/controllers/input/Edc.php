<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edc extends MY_Controller
{	

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form','url','file','download'));
		$this->load->library(array('template'));
		$this->load->model('input/edc_model');
	}
	
	function index()
    {
		$uri4 = $this->uri->segment(4);
		$this->session->set_userdata('uri4', $uri4);

        // $sales_code = $this->session->userdata('sales_code');
        // $this->session->set_userdata('sales_code', $sales_code);

        //load view
		$this->template->set('title','Data Merchant EDC');
		$this->template->load('template','input/edc/index');
    }
	
	function get_data_merchant()
	{
		$query = $this->edc_model->_get_data_query();

		$data = array();
		$no = $this->input->post('start');
		foreach ($query as $row){
			$hit_code = $row->Hit_Code;
			$edit = array('102','107');
			if (in_array($hit_code, $edit)) {
				$action = '<a href="'.site_url()."input/edc/edit/".$row->RegnoId.'" ><span class="btn btn-xs btn-warning"><i class="fa fa-md fa-edit" title="Edit Data"></i></span></a>';
			}
			else {
				$action = '&nbsp;';
			}

			$data[] = array(
				++$no,
				$row->Product_Type,
				$row->MID_Type,
				$row->Owner_Name,
				$row->Merchant_Name,
				$row->Account_Number,
				$row->Mobile_Phone_Number,
				$action
			);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->edc_model->_count_data(),
			"recordsFiltered" => $this->edc_model->_count_data(),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
    }
	
	function add()
	{
		$data['product_type'] = $this->input->post('product_type');
		$email 				  = $this->input->post('email');
		$data['mid_type'] 	  = $this->input->post('mid_type');
		$owner_name 		  = $this->input->post('owner_name');
		$merchant_name 		  = $this->input->post('merchant_name');
		$no_rek 			  = $this->input->post('account_number');
		$no_hp 				  = $this->input->post('mobile_phone_number');
		$other_no_hp 		  = $this->input->post('other_phone_number');

		$product_type 	= $data['product_type'];
		$mid_type 		= $data['mid_type'];

		$this->load->library('form_validation');
		$this->form_validation->set_rules('product_type', 'Jenis Pengajuan', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);
		$this->form_validation->set_rules('mid_type', 'Status Merchant', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);
		$this->form_validation->set_rules('owner_name', 'Nama Owner', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);
		$this->form_validation->set_rules('merchant_name', 'Nama Merchant', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);
		/*$this->form_validation->set_rules('account_number', 'No Rekening', 'trim|required', [
			'required' => '{field} harus di isi!!!'
		]);*/
		$this->form_validation->set_rules('mobile_phone_number', 'No Handphone', 'trim|required|callback_valid_number');
		
		if($product_type == 'EDC') {
			if($mid_type == 'NEW') {
				$this->form_validation->set_rules('account_number', 'No Rekening', 'trim|required|callback_check_norek');
			}
		}
		else {
			if($mid_type == 'NEW') {
				$this->form_validation->set_rules('email', 'Email', 'trim|required|callback_check_email');
				$this->form_validation->set_rules('account_number', 'No Rekening', 'trim|required|callback_check_norek');
			}
		}
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
		// 		$data['edc_officer_code'] = $this->db->query('SELECT * FROM `internal`.`edc_officer_code`');
				
			$this->template->set('title','Add Merchant EDC');
			$this->template->load('template','input/edc/add', $data);
		}
		else
		{
			$dataInsert = $this->edc_model->insert_data_api();
			$id = $dataInsert->RegnoId;
			
			$this->session->set_flashdata('message', "<span style='font-size:14px; text-align:center' class='col-lg-4 alert alert-info alert-dismissable'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<b>ID APLIKASI : <span style='color:red'>".$id."</span>, Mohon untuk dicatat di aplikasi!</b>
				</button>
				</span>");

			//Direct ke view
			redirect('input/edc/index/new_merchant');
			// redirect('sales/merchant_edc/index/');
		}
	}
	
	function edit($id)
	{
		$data['product_type'] = $this->input->post('product_type');
		$email 			      = $this->input->post('email');
		$data['mid_type'] 	  = $this->input->post('mid_type');
		$owner_name 		  = $this->input->post('owner_name');
		$merchant_name 		  = $this->input->post('merchant_name');
		$no_rek 			  = $this->input->post('account_number');
		$no_hp 				  = $this->input->post('mobile_phone_number');
		$other_no_hp 		  = $this->input->post('other_phone_number');
		
		$product_type 	= $data['product_type'];
		$mid_type 		= $data['mid_type'];

		$this->load->library('form_validation');
		$this->form_validation->set_rules('product_type', 'Jenis Pengajuan', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);
		$this->form_validation->set_rules('mid_type', 'Status Merchant', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);
		$this->form_validation->set_rules('owner_name', 'Nama Owner', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);
		$this->form_validation->set_rules('merchant_name', 'Nama Merchant', 'trim|required', [
			'required' => '{field} harus di isi!'
		]);
	// 	/*$this->form_validation->set_rules('account_number', 'No Rekening', 'trim|required', [
	// 		'required' => '{field} harus di isi!'
	// 	]);*/

		$this->form_validation->set_rules('mobile_phone_number', 'No Handphone', 'trim|required|callback_valid_number');
		
		if($product_type == 'EDC_QRIS') {
			$this->form_validation->set_rules('email', 'Email', 'trim|required|callback_check_email_update');
		}
		
		if($mid_type == 'NEW') {
			$this->form_validation->set_rules('account_number', 'No Rekening', 'trim|required|callback_check_norek_update');
		}
		
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
		$data['db'] = $this->edc_model->getById($id);

		$this->template->set('title','Edit Merchant EDC');
		$this->template->load('template','input/edc/edit', $data);
		}
		else
		{
			$this->edc_model->update_data_api($id);
			
			$this->session->set_flashdata('message', "<span style='font-size:14px; text-align:center' class='col-lg-4 alert alert-info alert-dismissable'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<b>Data berhasil diupdate!</b>
				</button>
				</span>");

			//Direct ke view
			redirect('input/edc/index/new_merchant');
		}
	}
	
	// function edit_old($id)
		// {
		// 	$data['product_type'] = $this->input->post('product_type');
		// 	$product_type 	= $data['product_type'];
		// 	$data['mid_type'] = $this->input->post('mid_type');
		// 	$mid_type 			= $data['mid_type'];
			
		// 	$data['db'] = $this->edc_model->edit($id)->row();
		// 	$this->template->load('template','merchant_edc/edit', $data);
	// }
	
	// function update($id)
		// {
		// 	$owner_name 	= $this->input->post('owner_name');
		// 	$merchant_name 	= $this->input->post('merchant_name');
		// 	$no_rek 		= $this->input->post('account_number');
		// 	$no_hp 			= $this->input->post('mobile_phone_number');
		// 	$mid_type 		= $this->input->post('mid_type');
		// 	$hc 			= $this->input->post('hit_code');
			
		// 	$hc_return = array('103','104','107');
			
		// 	if(in_array($hc, $hc_return)) {
		// 		$hit_code = '107';
		// 	}
		// 	else {
		// 		$hit_code = $hc;
		// 	}
			
		// 	$q_rek = $this->db->query("SELECT * FROM edc_merchant WHERE RegnoId != '$id' AND Account_Number = '$no_rek'");
		// 	$cek_rek = $q_rek->num_rows();
		// 	if($cek_rek > 0 ) {
		// 		echo "<script LANGUAGE='JavaScript'>window.alert('Maaf, No Rekening Sudah Terdaftar');
		// 		window.location.href='merchant/index/returned';
		// 		</script>";
		// 	}
		// 	else {
		// 		$data_update = array(
		// 			'Owner_Name'			=> strtoupper($owner_name),
		// 			'Merchant_Name'			=> strtoupper($merchant_name),
		// 			'Account_Number'		=> $no_rek,
		// 			'Mobile_Phone_Number'	=> $no_hp,
		// 			'MID_Type'				=> $mid_type,

		// 			'Hit_Code'				=> $hit_code
		// 		);
		// 		$this->db->where('RegnoId', $id);
		// 		$this->db->update('edc_merchant', $data_update);
				
		//         //MESSAGE
		//         $this->session->set_flashdata('message', "<span class='btn btn-success'><b>Data berhasil disubmit!</b></span>");
				
		// 		if(in_array($hc, $hc_return)) {
					
		// 		redirect('sales/merchant_edc/index/returned');
		// 		}
		// 		else {
		// 			redirect('sales/merchant_edc/index/new_merchant');
		// 		}
		// 	}
	// }
	
	//function auto number

    // function auto_number()
		// {
		// 	$sales_code = strtoupper($this->input->post('Sales_Code'));
		// 	$date = date('d');
		// 	$month = date('m');
		// 	$year = date('y');
		// 	$code = $sales_code.$year.$month.$date;

		// 	//get last code
		// 	$query = $this->edc_model->auto_number($code);
		// 	$rows = $query->row();
		// 	$result = $query->num_rows();
		// 	$barcode = $rows->maxCode;
			
		// 	$number = (int) substr($barcode, 16, 4);
		// 	//increase number
		// 	$number++;

		// 	//create new number
		// 	$new = $code.sprintf("%04s", $number);
		// 	return $new;
    // }
	
	function valid_number($str)
	{
		// VALID FORMAT = 08xxxxxxxx
		$valid_format = substr($str,0,2);
		
		if ($valid_format != '08'){
			// Let's return false for the validation and set a custom message for this function
			$this->form_validation->set_message('valid_number', 'Nomor handphone wajib diisi dan harus diawali 08');
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
			$emailQris = $this->edc_model->cekEmail($str, 'qris');

			$emailEDC  = $this->edc_model->cekEmail($str, 'edc');
			
			if($emailQris->total > 0 OR $emailEDC->total > 0) {
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
			// $q = $this->db->query("SELECT email FROM `db_qris`.`data_customers` WHERE id != '$id' AND email = '$str'");
			// $r = $q->num_rows();
			// $query = $this->db->query("SELECT Email FROM `internal`.`edc_merchant` WHERE RegnoId != '$id' AND Email = '$str'");
			// $row = $query->num_rows();

			$emailQris = $this->edc_model->cekEmailUpdate($id, $str, 'qris');

			$emailEDC  = $this->edc_model->cekEmailUpdate($id, $str, 'edc');

			if($emailQris->total > 0 OR $emailEDC->total > 0) {
				// Let's return false for the validation and set a custom message for this function
				$this->form_validation->set_message('check_email_update', 'Maaf, Email yang anda masukan sudah terdaftar!');
				return FALSE;
			}
			else {
				return TRUE;
			}
		}
	}
	
	/*function check_norek($str) {
		if($str == '') {
            // Let's return false for the validation and set a custom message for this function
            $this->form_validation->set_message('check_norek', 'No Rekening wajib diisi, tidak boleh kosong!');
			return FALSE;
		}
		else {
			$q = $this->db->query("SELECT * FROM `internal`.`edc_merchant` WHERE MID_Type = 'NEW' AND Account_Number = '$str' AND Expire = '0' AND Status NOT IN('CANCEL','REJECT')");
			$r = $q->num_rows();
			if($r > 0) {
				// Let's return false for the validation and set a custom message for this function
				$this->form_validation->set_message('check_norek', 'Maaf, No Rekening yang anda masukan sudah terdaftar!');
				return FALSE;
			}
			else {
				return TRUE;
			}

		}	
	}*/
	
	function check_norek($str)
	{
		$type = $this->input->post('mid_type');

		$query = $this->edc_model->cekNorek($type, $str);
		
		if ($query->total > 0){
			// Let's return false for the validation and set a custom message for this function
			$this->form_validation->set_message('check_norek', 'Maaf, No Rekening yang anda masukan sudah terdaftar!');
			return FALSE;
		}
		else{
			// Everything is good, don't return an error.
			return TRUE;
		}
	}
	
	function check_norek_update($str) {
		$id = $this->uri->segment(4);
		if($str == '') {
			// Let's return false for the validation and set a custom message for this function
			$this->form_validation->set_message('check_norek_update', 'No Rekening wajib diisi, tidak boleh kosong!');
			return FALSE;
		}
		else {
			// $q = $this->db->query("SELECT * FROM `internal`.`edc_merchant` WHERE RegnoId != '$id' AND MID_Type = 'NEW' AND Account_Number = '$str' AND Expire = '0'");
			// $r = $q->num_rows();

			$query = $this->edc_model->cekNorekUpdate($id, $str);

			if ($query->total > 0){
				// Let's return false for the validation and set a custom message for this function
				$this->form_validation->set_message('check_norek_update', 'Maaf, No Rekening yang anda masukan sudah terdaftar!');
				return FALSE;
			}
			else {
				return TRUE;
			}
		}
	}
}

/* End of file project.php */
/* Location: ./application/controllers/admin/project.php */								   