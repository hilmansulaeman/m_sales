<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Approval extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
        //load library dan helper yg dibutuhkan
        $this->load->library('template');
        $this->load->helper(array('url', 'html'));
        $this->load->model('approval_model');
	}
	
	function index()
	{
		$sales_code = $this->session->userdata('sl_code');
		$level = $this->session->userdata('level');
		$product = $this->session->userdata('product');
		$position = $this->session->userdata('position');
 
		 $data['sqlRecruitment'] = $this->approval_model->getRecruitment($sales_code,$position);
		 
		$this->template->set('title','APPROVAL');
		$this->template->load('template','approval/index', $data);
	}
	function listed($sales_code)
	{
		$position = $this->session->userdata('position');  
		 $data['sqlRecruitment'] = $this->approval_model->getRecruitment_list($sales_code,$position);		 
		$this->template->set('title','LIST APPROVE REJECT');
		$this->template->load('template','approval/listed', $data);
	}
	
	 
	function add_keterangan($kategori,$id,$sales_code)
	{
		//load view
		$data['getRecruitment_id'] = $this->approval_model->getRecruitment_id($id);
		$this->template->set('title','FORM ADD');
		$this->load->view('approval/add_keterangan', $data);
	}
	
	function simpan_keterangan($flag,$id)
	{
		global $aksi;
		global $no,$message_mail,$email_to,$email_cc,$keterangan, $table;						
		$sales_code = $this->session->userdata('sl_code');
		$nama = $this->session->userdata('realname');		 
		$position = $this->session->userdata('position');
		$date = date('Y-m-d');
		
		$qry_in 	= $this->approval_model->getRecruitment_id($id);
		$row_i		= $qry_in->row();				
		
		$qry_email 	= $this->approval_model->get_all_email($row_i->recruiter);
		$rowmail	= $qry_email->row();
		
		 
		if($flag == "approve"){
			$aksi="1";
			$table="";
			$email_to  	= "hrd.ptdika.com"; //"gilang.aprila@ptdika.com"; //
			$keterangan="Dear All, <br><br><br> Sales yang diajukan telah disetujui/ diapprove oleh Bpk/Ibu ".$nama." (<i>".$position."</i>).";
		}else{
			$aksi="2";
			$table="";
			$email_to  	= $rowmail->email_dika; //"gilang.aprila@ptdika.com";
			$keterangan="Dear Bpk/Ibu  ".$row_i->nama_perekrut.", <br><br><br> Sales yang diajukan telah ditolak/ direject oleh Bpk/Ibu ".$nama." (<i>".$position."</i>).";
		}
		if($position == "SPV"){
			$data_insert = array(			
				'approve1'					=>$aksi,
				'approve1_sales_code'		=>$sales_code,
				'approve1_name'				=>$nama,
				'approve1_note'				=>$this->input->post('note'),
				'approve1_date'				=>$date
			);				 				
		}else{
			$data_insert = array(			
				'approve2'					=>$aksi,
				'approve2_sales_code'		=>$sales_code,
				'approve2_name'				=>$nama,
				'approve2_note'				=>$this->input->post('note'),
				'approve2_date'				=>$date
			);				 							
		}
		
						
		$this->approval_model->update_approve($data_insert, $id);
		
		 
		
		// $qry_email_cc 	= $this->approval_model->get_all_email_cc();
		// $rowmail_cc	= $qry_email_cc->row();
		// $email_cc  	= $rowmail_cc->email;
		
		$message_mail = "<HTML><BODY>
									". $keterangan ."
									<br>
									<br>									
									Note ". $nama ." : <span style=\'color:#FFFFFF;background-color:#990000;\'>  ". $this->input->post('note') ."  </span> 								
									<br>
									<br>
									<br>
									<br>
									Detail  Sales:
									<br>
									<br>
									<table >													 
										<tbody>
											<tr>
												<td style=\'width:15%\'>Nama</td>
												<td style=\'width:2%\'>:</td>	
												<td>". $row_i->name ."</td>
												<td>Tanggal Lahir</td>
												<td style=\'width:2%\'>:</td>
												<td>". $row_i->dob ."</td>													
											</tr>
											<tr>
												<td style=\'width:15%\'>Jenis Kelamin </td>
												<td style=\'width:2%\'>:</td>	
												<td>". $row_i->gender ."</td>
												<td>Agama</td>
												<td style=\'width:2%\'>:</td>
												<td>". $row_i->agama ."</td>													
											</tr>
											<tr>
												<td>Posisi Lamar</td>
												<td style=\'width:2%\'>:</td>										
												<td>". $row_i->posisi_lamar .", ". $row_i->posisi_lamar2 ."</td>
												<td>Status Nikah</td>
												<td style=\'width:2%\'>:</td>
												<td>". $row_i->status ."</td>
											</tr>	
											<tr>																							
												<td>Pengalaman</td>
												<td style=\'width:2%\'>:</td>
												<td>". $row_i->pengalaman ."</td>	
												<td style=\'width:15%\'>Tanggal ".$flag." </td>
												<td style=\'width:2%\'>:</td>	
												<td>". $date ."</td>																							
											</tr>
											<tr>																			
												<td>Alamat</td>
												<td style=\'width:2%\'>:</td>
												<td colspan=4>". $row_i->alamat .", Kel : ". $row_i->kelurahan .", Kec ". $row_i->kecamatan .", Kota ". $row_i->kota_ktp .", Negara ". $row_i->bangsa ."</td>
											</tr>							 													 																																	
									</tbody>							 							
								</table>
							 	
									<br><br><br>
									<br>Regards,<br/>
									<br/>
									<span style=color:#3276b1;>
										Support SALES MONITORING SISTEM 
										<br> 
										PT. Danamas Insan Kreasi Andalan										 
									</span>																	
									<br/>
									<br/>
									<i>Noted : Email ini dikirim oleh sistem secara automatis</i>
									</BODY></HTML>
								";
				$this->load->library('email');
				$this->config->load('setting');
				$config['mailtype'] = "html";
				$this->email->initialize($config);
				$this->email->clear(TRUE); 
				$this->email->from($this->config->item('email_admin'), $this->config->item('admin_name'));
				$this->email->to($email_to);
				// $this->email->cc($email_cc);
				$this->email->subject('Approval Pengajuan Sales');
				$this->email->message($message_mail);
				$this->email->send();		
			//$this->session->set_flashdata('message', 'Data berhasil diapprove !');			
		
		
		echo "<script>";
		echo "window.parent.parent.location='".base_url()."approval'";
		echo "</script>";
		return $message_mail;
	}
	
	 
	
	
}